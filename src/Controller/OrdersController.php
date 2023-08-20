<?php


namespace Locomotif\Shop\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Locomotif\Media\Models\Media;
use Locomotif\Shop\Models\Orders;
use Locomotif\Shop\Models\OrdersTracking;
use Locomotif\Shop\Models\FgoApi;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Orders::with('user', 'user.account')
                        ->with('currentStatus')
                        ->with('carrier')
                        ->with('transactionProvider')
                        ->with('invoice')
                        ->leftJoinSub(function ($query) {
                            // Subquery to fetch the latest transaction for each order
                            $query->from('transactions')
                                ->selectRaw('id as trID, order_id, provider_id, user_id, transaction_identifier, created_at, status as trStatus, MAX(created_at) as latest_created_at')
                                ->orderBy('transactions.created_at', 'ASC')
                                ->groupBy('order_id');
                        }, 'latest_transaction', function ($join) {
                            $join->on('orders.id', '=', 'latest_transaction.order_id');
                        })
                        ->leftJoin('transactions', function ($join) {
                            $join->on('orders.id', '=', 'transactions.order_id')
                                ->on('transactions.created_at', '=', 'latest_transaction.latest_created_at');
                        })
                        ->select('orders.*', 'transactions.status as transactionStatus')
                        ->orderBy('orders.created_at', 'DESC')
                        ->get();
                        
        $items->map(function($item){
            if(!isset($item->currentStatus->status)){
                dd('o comanda nu are setat tracking in orders_tracking');
            }
            $item->currentStatus->statusNice = mapStatus($item->currentStatus->status);
            $item->transactionStatusNice     = mapStatus($item->transactionStatus);
        });
        
        return view('orders::list')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function updateTrackingStatus(Request $request){
        
        $orderTracking = new OrdersTracking();
        $orderTracking->order_id        = $request->order;
        $orderTracking->delay_time_days = 0;
        $orderTracking->comments        = $request->comments;
        $orderTracking->status          = $request->orders_tracking;
        $orderTracking->save();

        return redirect('admin/orders/'.$request->order.'/edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $order)
    {
        $currentOrder = Orders::with('orderItems')
                               ->with('user', 'user.account')
                               ->with('currentStatus')
                               ->with('deliveryAddress')
                               ->with('carrier')
                               ->with('trackingHistory')
                               ->with('transactionProvider')
                               ->with('transactions')
                               ->with('invoice')
                               ->find($order->id);
        $designerIds = $currentOrder->orderItems->pluck('designer_id')->unique();
        $designers = DB::table('accounts')->whereIn('id', $designerIds)->get();
        $currentOrder->designers = $designers;

        $currentOrder->trackingHistory->map(function($history){
            $history->status = mapStatus($history->status);
        });
        $currentOrder->transactions->map(function($transaction){
            $transaction->status = mapStatus($transaction->status); 
        });
        $currentOrder->invoice['originalStatus'] = $currentOrder->invoice['status'];
        $currentOrder->invoice['status'] = mapStatus($currentOrder->invoice['status']);        
        
        $statusList = $currentOrder->statusList();
        
        return view('orders::edit')
                ->with('item', $currentOrder)
                ->with(compact('statusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }

    public function markFgoInvoiced(Request $request){
        $orderDetails = $request->all();
        
        $orderDetailsFgo = [
            'currency'             => 'RON',
            'series'               => $orderDetails['invoice_series'],
            'number'               => $orderDetails['invoice_number'],
            'clientCompany'        => $orderDetails['invoice_number']
            
            // 'shopOrderReference'   => $orderReference,
        ];
        
        $fgoInstance = new FgoApi($orderDetailsFgo);
        $response    = $fgoInstance->getStatus();
        
        if($response->Success){
            $paid = ($response->Factura->Valoare===$response->Factura->ValoareAchitata) ? true : false;
            if(!$paid){
                $invoiceResponse = $fgoInstance->setOrderAsInvoiced($response->Factura->Valoare, 'Banca');
            }else{
                dd('Factura este deja incasata');
            }
            
        }else{
            dd($response->Message);
        }
        if($invoiceResponse->Success){
            $order = Orders::with('paymentStatus')->find($orderDetails['order_id']);    
                     Orders::markInvoiceAsPaid($orderDetails['invoice_id'], $orderDetails['invoice_status'], $order->paymentStatus);
            return true;
        }else{
            return false;
        }
    }

    public function buildFinalInvoice(Request $request){
        $orderDetails = $request->all();

        $orderID = $orderDetails['order_id'];
        $order = Orders::with('orderItems')
                        ->with('deliveryAddress')
                        ->with('paymentStatus')
                        ->find($orderID);
        
        $orderDetailsFgo = Orders::buildFGOOrderDetails($order->deliveryAddress);
        $storno          = Orders::buildStorno($order);
        $addCarrierFee = (!empty($order->delivery_fee) && $order->delivery_fee > 0) ? true : false;
        if($addCarrierFee){
            $carrierFee = Orders::addCarrierFee($order);
            $storno     = array_merge($carrierFee, $storno);
        }
        
        $finalItems = array_merge($order->orderItems->toArray(), $storno);

        $fgoInstance    = new FgoApi($orderDetailsFgo);

        $invoice     = $fgoInstance->generateInvoice($finalItems, 'online', 0);
        $invoiceID   = $fgoInstance->storeInvoice($invoice, 'online');
        $response    = Orders::updateOrderInvoice($orderID, $invoiceID);
        
        $transaction = Orders::addTransaction($order->paymentStatus);
        dd($invoice, $invoiceID);
    }
}
