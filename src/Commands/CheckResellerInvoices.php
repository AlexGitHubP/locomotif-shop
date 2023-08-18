<?php

namespace Locomotif\Shop\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Locomotif\Admin\Models\Accounts;
use Locomotif\Shop\Mail\InvoiceNotification;
use Illuminate\Support\Facades\Mail;
use Exception;

class CheckResellerInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locomotif:resellerOperations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks reseller invoices and notifies them if an invoice should be generated';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $tva = DB::table('shop_settings')->select('tva', 'tax_type')->where('id', 1)->first();
        Accounts::where('type', '=', 'designer')->where('status', '=', 'published')->chunk(2, function($designers) use ($tva){
            foreach ($designers as $key => $designer) {
                $subtotalForCurrentMonth = self::getSubtotalForCurrentMonth($designer->id);
                $amountToInvoice         = self::calculateAmountToInvoice($subtotalForCurrentMonth);
                $calculatedTVA           = extractTVA($amountToInvoice, $tva->tva, $tva->tax_type);
                $amountToInvoiceWithTVA  = $amountToInvoice + $calculatedTVA;
                $recordExists = self::cehckIFInvoiceRecordAlreadyExists(now()->month, now()->year, $designer->id);
        
                if($recordExists['exists']){
                    $resellerInvoices = array(
                        'designer_id'          => $designer->id,
                        'subtotal_sales'       => $subtotalForCurrentMonth,
                        'amount_to_invoice'    => $amountToInvoice,
                        'amount_shown_to_shop' => $amountToInvoiceWithTVA,
                        'invoice_status'       => $recordExists['invoiceData']->invoice_status,
                        'invoice'              => $recordExists['invoiceData']->invoice,
                        'nr_of_notice_sent'    => $recordExists['invoiceData']->nr_of_notice_sent,
                        'month'                => $recordExists['invoiceData']->month,
                        'year'                 => $recordExists['invoiceData']->year,
                        'created_at'           => $recordExists['invoiceData']->created_at,
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s')
                    );
                    
                }else{
                    $resellerInvoices = array(
                        'designer_id'          => $designer->id,
                        'subtotal_sales'       => $subtotalForCurrentMonth,
                        'amount_to_invoice'    => $amountToInvoice,
                        'amount_shown_to_shop' => $amountToInvoiceWithTVA,
                        'invoice_status'       => 'notUploaded',
                        'invoice'              => null,
                        'nr_of_notice_sent'    => 0,
                        'month'                => now()->month,
                        'year'                 => now()->year,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s')
                    );
                }
                
                
                DB::table('reseller_invoices')->updateOrInsert(
                        ['designer_id'=>$designer->id, 'month'=>now()->month, 'year'=>now()->year],
                        $resellerInvoices
                );
                
                $resellerInvoiceID = DB::table('reseller_invoices')->select('id')->where('designer_id', $designer->id)->where('month', now()->month)->where('year', now()->year)->first();
                $lastDayofMonth    = self::checkIfIsLastDayOfMonth();
                $invoicesArePaid   = self::checkIFRelatedInvoicesArePaid($designer->id);

                if(!$lastDayofMonth){
                    if($invoicesArePaid){
                        self::sendNotificationForInvoice($resellerInvoices, $designer->email, $resellerInvoiceID->id);
                    }else{
                        self::sendNotificationToWait($resellerInvoices, $designer->email, $resellerInvoiceID->id);
                    }
                }
                
            }

        });
        
        $this->info('Invoices and notifications done!');
    }


    static function getSubtotalForCurrentMonth($accountID){
        $subtotal = DB::table('orders_items')
                        ->selectRaw('SUM(subtotal) as total')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where('designer_id', '=', $accountID)
                        ->get()
                        ->first();
        $subtotal = (isset($subtotal->total) && $subtotal->total > 0) ? $subtotal->total : 0;

        return (double)$subtotal;
    }

    static function extractResellerPrice($total, $fee, $type){
        $fee   = (double)$fee;
        $total = (double)$total;

        switch ($type) {
            case 'percent':
                $price = ($fee/100) * $total;
                break;

            case 'fixed':
                $price = $tva;
                break;
            
            default:
                $price = ($tva/100) * $total;
                break;
        }

        return $price;
    }

    static function calculateAmountToInvoice($subtotalForCurrentMonth){
        $shopFee = DB::table('shop_settings')->select('shop_fee', 'shop_fee_type')->where('id', 1)->first();
        $amountToInvoice = self::extractResellerPrice($subtotalForCurrentMonth, $shopFee->shop_fee, $shopFee->shop_fee_type );
        
        return $amountToInvoice;
    }

    static function cehckIFInvoiceRecordAlreadyExists($month, $year, $accountID){
        $currentRecord = DB::table('reseller_invoices')
                            ->select('id', 'designer_id', 'invoice_status', 'invoice', 'nr_of_notice_sent', 'month', 'year', 'created_at')
                            ->where('month', $month)
                            ->where('year', $year)
                            ->where('designer_id', '=', $accountID)
                            ->get()->first();
                            
        $invoiceData = array(
            'exists'      => isset($currentRecord->id) ? true : false,
            'invoiceData' => $currentRecord
        );

        return $invoiceData;

        
    }

    static function checkIfIsLastDayOfMonth(){
        
        $lastDayOfMonth = Carbon::now()->endOfMonth();
        $today          = Carbon::now()->today();

        $lastDayOfMonth = $lastDayOfMonth->format('Y-m-d');
        $today          = $today->format('Y-m-d');

        $isLastDay      = ($today==$lastDayOfMonth) ? true : false;
        
        return $isLastDay;
        
    }

    static function sendNotificationForInvoice($invoiceData, $email, $resellerInvoiceID){
        $dataFactura = Carbon::now()->today()->format('F').' '.Carbon::now()->today()->format('Y');
        $invoiceData['dataFactura'] = $dataFactura;
        
        try {
            Mail::to($email)->send(new InvoiceNotification($invoiceData, 'resellerInvoiceNotification', 'Generează și urca factura pentru MASARA, luna '.$invoiceData['dataFactura']));
            DB::table('reseller_invoices')->where('id', $resellerInvoiceID)->increment('nr_of_notice_sent', 1);
        } catch (Exception  $e) {
            $message = $e->getMessage();
            dd($message);
        }
    }

    static function sendNotificationToWait($invoiceData, $email, $resellerInvoiceID){
        $dataFactura = Carbon::now()->today()->format('F').' '.Carbon::now()->today()->format('Y');
        $invoiceData['dataFactura'] = $dataFactura;
        
        try {
            Mail::to($email)->send(new InvoiceNotification($invoiceData, 'resellerInvoiceWaitNotification', 'Informare legată de factura pentru MASARA, luna '.$invoiceData['dataFactura']));
            DB::table('reseller_invoices')->where('id', $resellerInvoiceID)->increment('nr_of_notice_sent', 1);
        } catch (Exception  $e) {
            $message = $e->getMessage();
            dd($message);
        }
    }

    

    static function returnPaymentstatus($orderID){
        $payStatus = DB::table('transactions')
                         ->select('status')
                         ->where('order_id', $orderID)
                         ->get()
                         ->last();
        return $payStatus->status;
    }

    static function checkIFRelatedInvoicesArePaid($accountID){
        $orderIds = DB::table('orders_items')
                        ->select('order_id')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where('designer_id', '=', $accountID)
                        ->get();
        $orderIds->map(function($order){
            $order->status = self::returnPaymentstatus($order->order_id);
        });
        $allPaymentConfirmed = $orderIds->every(function ($item) {
            return $item->status === 'paymentConfirmed' || $item->status === 'paymentFirstHalfConfirmed' || $item->status === 'paymentCollected';
        });

        return $allPaymentConfirmed;
    }
}
