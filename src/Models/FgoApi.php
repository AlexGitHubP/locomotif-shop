<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InvalidArgumentException;

class FgoApi extends Model{
    
    const CUI      = '37424870';
    const SECRET   = 'C6101CFECD943764DA290D6AED6BCB57';
    const BASE_URL = 'https://testapp.fgo.ro/';

    private $currency;
    private $clientCompany;
    private $clientRegistruComert;
    private $clientCUI;
    private $clientCounty;
    private $clientCity;
    private $clientAddress;
    private $clientEmail;
    private $clientPhone;
    private $clientType;
    private $invoiceType;
    private $shopOrderReference;
    private $series;
    private $number;
    

    private $hash;

    public function __construct($params){
        try {
            $this->buildParams($params);
        } catch (\InvalidArgumentException $e) {
            dd($e->getMessage());
            //return $e->getMessage();
        }
        
        $this->generateHash();
    }

    private function buildParams($params){
        if(isset($params) && !empty($params) && count($params) > 0){
            foreach ($params as $key => $param) {
                if (property_exists($this, $key)) {
                    $this->$key = $param;
                } else {
                    throw new InvalidArgumentException("Property '$key' does not exist.");
                }
                
            }
        }
    }

    private function generateHash(){

        $cui    = FgoApi::CUI;
        $secret = FgoApi::SECRET;
        $client = $this->clientCompany;
        $hash   = strtoupper(SHA1($cui.$secret.$client));
        $this->hash = $hash;
    }

    public function getCurrentTime(){
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    public function getHash(){
        return $this->hash;
    }

    static function buildProducts($products){
        $formattedProducts = array();
        foreach ($products as $key => $product) {
            $formattedProducts = $formattedProducts + array(
                'Continut['.$key.'][Denumire]'   => (isset($product['name'])) ? $product['name'] : $product['name'],
                'Continut['.$key.'][PretUnitar]' => (isset($product['price'])) ? $product['price'] : $product['price_per_unit'],  
                'Continut['.$key.'][UM]'         => (isset($product['um'])) ? $product['um'] : 'buc',
                'Continut['.$key.'][NrProduse]'  => (isset($product['amount'])) ? $product['amount'] : $product['quantity'],
                'Continut['.$key.'][CotaTVA]'    => '19'
            );
        }
        
        return $formattedProducts;
        
    }
    static function build50AdvanceORder($halfOrderWithoutTVA){

        $formattedProducts = array(
            'Continut[0][Denumire]'   => 'Avans comandă 50%',
            'Continut[0][PretUnitar]' => (string)$halfOrderWithoutTVA,  
            'Continut[0][UM]'         => 'lei',
            'Continut[0][NrProduse]'  => 1,
            'Continut[0][CotaTVA]'    => '19'
        );

        return $formattedProducts;
    }

    //quick fix for now
    //$products    - an array of products/or one product
    //$paymentType - ex: `online` is regular invoice, but `moneyOrderAdvance` builds a proform order with 50% of the total order without TVA, ignoring the products
    //$totalOrder  - total order without TVA, used only if paymentType is moneyOrderAdvance
    public function generateInvoice($products, $paymentType, $halfOrderWithoutTVA){
          
        $endpoint = self::BASE_URL.'publicws/factura/emitere';
        switch ($paymentType) {
            case 'online':
                $formattedProducts = self::buildProducts($products);  
            break;

            case 'moneyOrderAdvance':
                $formattedProducts = self::build50AdvanceORder($halfOrderWithoutTVA);  
            break;

            default:
                $formattedProducts = self::buildProducts($products);  
            break;
        }
        

        $data = array(
                    'CodUnic'            => self::CUI,
                    'Hash'               => $this->getHash(),
                    'Valuta'             => $this->currency,
                    'Serie'              => $this->series,
                    'TipFactura'         => $this->invoiceType,
                    'PlatformaUrl'       => self::BASE_URL,
                    'Client[Denumire]'   => $this->clientCompany,
                    'Client[CodUnic]'    => $this->clientCUI,
                    'Client[Tip]'        => $this->clientType,
                    'Client[NrRegCom]'   => $this->clientRegistruComert,
                    'Client[Judet]'      => $this->clientCounty,
                    'Client[Email]'      => $this->clientEmail,
                    'Client[Telefon]'    => $this->clientPhone,
                    'Client[Localitate]' => $this->clientCity,
                    'Client[Adresa]'     => $this->clientAddress,
                    'Client[IdExtern]'   => $this->shopOrderReference,
                    // 'Explicatii'      => 'Explicatii factura',
                );
        $fgoData = array_merge($data, $formattedProducts);
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($fgoData)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($endpoint, false, $context);
        $response = json_decode($response);
        if($response->Success==true){
            return $response->Factura;
        }else{
            dd($response->Message);
        }
        
    }

    public function storeInvoice($invoice, $paymentType){
        $type = ($paymentType=='moneyOrderAdvance') ? 'fgo_sentHalf' : 'fgo_sent';
        $invoiceID = DB::table('orders_invoices')->insertGetId([
            'invoice_number' => $invoice->Numar,
            'invoice_series' => $invoice->Serie,
            'invoice_link'   => $invoice->Link,
            'status'         => $type,
            'created_at'     => $this->getCurrentTime(),
            'updated_at'     => $this->getCurrentTime(),
        ]);

        return $invoiceID;
    }

    public function getStatus(){
        $endpoint = self::BASE_URL.'publicws/factura/getstatus';
        $data = array(
            'CodUnic'            => self::CUI,
            'Hash'               => $this->getHash(),
            'Valuta'             => $this->currency,
            'Serie'              => $this->series,
            'Numar'              => $this->number,
        );
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        $response = file_get_contents($endpoint, false, $context);
        $response = json_decode($response);
        
        return $response;
    }

    public function setOrderAsInvoiced($amount, $type){
        $endpoint = self::BASE_URL.'publicws/factura/incasare';

        $data = array(
            'CodUnic'            => self::CUI,
            'Hash'               => $this->getHash(),
            'Valuta'             => $this->currency,
            'SerieFactura'       => $this->series,
            'NumarFactura'       => $this->number,
            'TipIncasare'        => $type,
            'SumaIncasata'       => $amount,
            'DataIncasare'       => self::getCurrentTime()
        );
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        $response = file_get_contents($endpoint, false, $context);
        $response = json_decode($response);

        return $response;
    }
    

}
