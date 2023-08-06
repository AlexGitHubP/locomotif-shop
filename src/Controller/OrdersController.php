<?php


namespace Locomotif\Shop\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Locomotif\Media\Models\Media;
use Locomotif\Shop\Models\Orders;


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
                               ->find($order->id);
                               
        return view('orders::edit')
                ->with('item', $currentOrder);
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
}
