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
        $currentOrder = Orders::find($order->id)
                               ->with('user', 'user.account')
                               ->with('currentStatus')
                               ->with('carrier')
                               ->with('trackingHistory')
                               ->with('transactionProvider', 'transactionProvider.transactions')
                               ->first();
                            // dd($currentOrder);
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
