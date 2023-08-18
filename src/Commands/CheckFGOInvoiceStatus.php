<?php

namespace Locomotif\Shop\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Locomotif\Shop\Models\Orders;
use Locomotif\Shop\Models\FgoApi;
use Carbon\Carbon;

class CheckFGOInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locomotif:checkFGOstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status for FGO invoices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        Orders::with('invoice')->with('paymentStatus')->chunk(2, function($orders){
            foreach ($orders as $key => $order) {
                $orderDetailsFgo = [
                    'currency'             => 'RON',
                    'series'               => $order->invoice['invoice_series'],
                    'number'               => $order->invoice['invoice_number'],
                    'clientCompany'        => $order->invoice['invoice_number']
                ];
                $fgoInstance = new FgoApi($orderDetailsFgo);
                $response    = $fgoInstance->getStatus();

                if($response->Success){
                    $paid = self::checkIfPaid($response->Factura->Valoare, $response->Factura->ValoareAchitata);
                    if($paid){
                        self::markInvoiceAsPaid($order->invoice['id'], 'fgo_invoiced', $order->paymentStatus);
                    }
                }

                sleep(1);
            }
        });
        
       
        $this->info('Status check success!');
    }

    public function checkIfPaid($value, $paidValue){
        $paid = ($value===$paidValue) ? true : false;
        return $paid;
    }

    public function markInvoiceAsPaid($invoiceID, $status, $paymentStatus){
        $now = Carbon::now()->format('Y-m-d H:i:s');
        DB::table('orders_invoices')->where('id', $invoiceID)->update(['status' => $status]);

        DB::table('transactions')->insert([
            'order_id'               => $paymentStatus['order_id'],
            'provider_id'            => $paymentStatus['provider_id'],
            'user_id'                => $paymentStatus['user_id'],
            'transaction_identifier' => $paymentStatus['transaction_identifier'],
            'comments'               => $paymentStatus['comments'],
            'type'                   => $paymentStatus['type'],
            'status'                 => 'paymentCollected',
            'created_at'             => $now,
            'updated_at'             => $now,
        ]);
    }
}
