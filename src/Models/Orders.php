<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use Locomotif\Admin\Models\Users;
use Locomotif\Shop\Models\OrdersTracking;
use Locomotif\Shop\Models\CarriersType;
use Locomotif\Shop\Models\TransactionsProvider;
use Locomotif\Shop\Models\Transactions;
use Locomotif\Shop\Models\OrdersItems;
use Locomotif\Shop\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class Orders extends Model{
    
    const orderStatus = ['sentToShop', 'inTransition-sentToCarrier', 'inTransition-pickedUpByCarrier', 'inTransition-sentFromCarrier', 'delivered', 'canceled', 'delayed', 'contested'];

    public function orderItems(){
        return $this->hasMany(OrdersItems::class, 'order_id', 'id');
    }

    public function user(){
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function trackingHistory(){
        return $this->hasMany(OrdersTracking::class, 'order_id', 'id');
    }

    public function currentStatus(){
        return $this->hasOne(OrdersTracking::class, 'order_id', 'id')->latest();
    }

    public function statusList(){
        $statusList = self::orderStatus;
        foreach ($statusList as $k => $statusItem) {
            $mappedStatus[$k] = array(
                $statusItem => mapStatus($statusItem) 
            );
        }
        return $mappedStatus;
    }

    public function carrier(){
        return $this->belongsTo(CarriersType::class, 'carrier_id', 'id');
    }

    public function transactionProvider(){
        return $this->belongsTo(TransactionsProvider::class, 'paymethod_id', 'id');
    }

    public function transactions(){
        return $this->hasMany(Transactions::class, 'order_id', 'id');
    }

    public function paymentStatus(){
        return $this->hasOne(Transactions::class, 'order_id', 'id')->latest();
    }

    public function deliveryAddress(){
        return $this->hasOne(OrderDetails::class, 'order_id', 'id');
    }

    public function invoice(){
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    static function markInvoiceAsPaid($invoiceID, $status, $paymentStatus){
        $now = Carbon::now()->format('Y-m-d H:i:s');
        
        $currentStatus = DB::table('orders_invoices')->select('status')->where('id', '=', $invoiceID)->first();
        $transactionStatus = ($paymentStatus['status']=='transactionFirstHalfRecieved') ? 'paymentFirstHalfConfirmed' : 'paymentCollected';
        
        DB::table('orders_invoices')->where('id', $invoiceID)->update(['status' => $status]);


        DB::table('transactions')->insert([
            'order_id'               => $paymentStatus['order_id'],
            'provider_id'            => $paymentStatus['provider_id'],
            'user_id'                => $paymentStatus['user_id'],
            'transaction_identifier' => $paymentStatus['transaction_identifier'],
            'comments'               => $paymentStatus['comments'],
            'type'                   => $paymentStatus['type'],
            'status'                 => $transactionStatus,
            'created_at'             => $now,
            'updated_at'             => $now,
        ]);
    }

    static function buildAddress($orderDetails){

        $city       = (isset($orderDetails['city'])       && !empty($orderDetails['city']))       ? 'Loc. '.$orderDetails['city']             : '';
        $street     = (isset($orderDetails['street'])     && !empty($orderDetails['street']))     ? ', Str. '.$orderDetails['street']         : '';
        $nr         = (isset($orderDetails['nr'])         && !empty($orderDetails['nr']))         ? ', Nr. '.$orderDetails['nr']              : '';
        $bloc       = (isset($orderDetails['bloc'])       && !empty($orderDetails['bloc']))       ? ', Bl. '.$orderDetails['bloc']            : '';
        $scara      = (isset($orderDetails['scara'])      && !empty($orderDetails['scara']))      ? ', Sc. '.$orderDetails['scara']           : '';
        $apartament = (isset($orderDetails['apartament']) && !empty($orderDetails['apartament'])) ? ', Ap. '.$orderDetails['apartament']      : '';
        $zip_code   = (isset($orderDetails['zip_code'])   && !empty($orderDetails['zip_code']))   ? ', Cod Postal '.$orderDetails['zip_code'] : '';

        $address = $city.$street.$nr.$bloc.$scara.$apartament.$zip_code;

        return $address;
    }

    static function buildFGOOrderDetails($orderDetails){

        $isCompany = $orderDetails['is_company'];
        $address   = self::buildAddress($orderDetails);
        $registru  = $orderDetails['company_j'].$orderDetails['company_nr'].'/'.$orderDetails['company_series'].'/'.$orderDetails['company_year'];
        $cui       = $orderDetails['company_vat_type'].$orderDetails['company_cui'];

        $clientCompany        = ($isCompany) ? $orderDetails['company_name'] : $orderDetails['name'].' '.$orderDetails['surname'];
        $clientCUI            = ($isCompany) ? $cui : '';
        $clientType           = ($isCompany) ? 'PJ' : 'PF';
        $clientRegistruComert = ($isCompany) ? $registru : '';
        $clientCounty         = $orderDetails['county'];
        $clientEmail          = $orderDetails['email'];
        $clientPhone          = $orderDetails['phone'];
        $clientCity           = $orderDetails['city'];
        $clientAddress        = $address;

        $orderDetailsFgo = [
            'currency'             => 'RON',
            'series'               => 'ACT',
            'invoiceType'          => 'Factura',
            'clientCompany'        => $clientCompany,
            'clientCUI'            => $clientCUI,
            'clientType'           => $clientType,
            'clientRegistruComert' => $clientRegistruComert,
            'clientCounty'         => $clientCounty,
            'clientEmail'          => $clientEmail,
            'clientPhone'          => $clientPhone,
            'clientCity'           => $clientCity,
            'clientAddress'        => $clientAddress,
            // 'shopOrderReference'   => $orderReference,
        ];
        
        return $orderDetailsFgo;
    }

    static function updateOrderInvoice($orderID, $invoiceID){
        DB::table('orders')->where('id', $orderID)->update(['invoice_id' => $invoiceID]);
    }

    static function addTransaction($paymentStatus){
        $now = Carbon::now()->format('Y-m-d H:i:s');
        DB::table('transactions')->insert([
            'order_id'               => $paymentStatus['order_id'],
            'provider_id'            => $paymentStatus['provider_id'],
            'user_id'                => $paymentStatus['user_id'],
            'transaction_identifier' => $paymentStatus['transaction_identifier'],
            'comments'               => $paymentStatus['comments'],
            'type'                   => $paymentStatus['type'],
            'status'                 => 'transactionSecondHalfRecieved',
            'created_at'             => $now,
            'updated_at'             => $now,
        ]);
    }

    static function buildStorno($order){

        $stornoTotal = ((int)$order->subtotal + (int)$order->delivery_fee) / 2;

        $storno = array(
            array(
                "name"           => "Storno 50% avans comandă",
                "quantity"       => -1,
                "subtotal"       => (string)$stornoTotal,
                "price_per_unit" => (string)$stornoTotal,
                "um" => 'lei'
            )
        );

        return $storno;
    }

    static function addCarrierFee($order){

        $carrierFee = array(
            array(
                "name"           => "Servicii de transport",
                "quantity"       => 1,
                "subtotal"       => (string)$order->delivery_fee,
                "price_per_unit" => (string)$order->delivery_fee,
                "um" => 'lei'
            )
        );

        return $carrierFee;
    }
    

}
