@extends('admin::inc/header')
@section('title', 'Edit product')
@include('admin::inc/generalErrors')

@section('content')
<div class='details-bar'><!--details-bar-->
	<div class='details-left'>
		<h2>Editare: {{ \Illuminate\Support\Str::limit($item->name, $limit = 15, $end = '...') }} </h2>
	</div>
	<div class='details-center'>
		<ul class='details-nav nav-tabs'>
			<li class='detail-selected'>
				<a href="" data-target="detalii">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.1 16">
						<path id="Text-2" data-name="Text" d="M5.2,2H11a.75.75,0,0,1,.53.22l4.35,4.35a.75.75,0,0,1,.22.53v8.7A2.222,2.222,0,0,1,13.9,18H5.2A2.222,2.222,0,0,1,3,15.8V4.2A2.222,2.222,0,0,1,5.2,2Zm0,1.5a.721.721,0,0,0-.7.7V15.8a.721.721,0,0,0,.7.7h8.7a.721.721,0,0,0,.7-.7V7.85H11a.75.75,0,0,1-.75-.75V3.5Zm6.55,1.061L13.539,6.35H11.75ZM5.9,7.825a.75.75,0,0,1,.75-.75H8.1a.75.75,0,0,1,0,1.5H6.65A.75.75,0,0,1,5.9,7.825Zm.75,2.15a.75.75,0,1,0,0,1.5h5.8a.75.75,0,1,0,0-1.5Zm0,2.9a.75.75,0,0,0,0,1.5h5.8a.75.75,0,0,0,0-1.5Z" transform="translate(-3 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					</svg> 
					Detalii
				</a>
			</li>
            <li>
				<a href="" data-target="designeri">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.1 16">
						<path id="Text-2" data-name="Text" d="M5.2,2H11a.75.75,0,0,1,.53.22l4.35,4.35a.75.75,0,0,1,.22.53v8.7A2.222,2.222,0,0,1,13.9,18H5.2A2.222,2.222,0,0,1,3,15.8V4.2A2.222,2.222,0,0,1,5.2,2Zm0,1.5a.721.721,0,0,0-.7.7V15.8a.721.721,0,0,0,.7.7h8.7a.721.721,0,0,0,.7-.7V7.85H11a.75.75,0,0,1-.75-.75V3.5Zm6.55,1.061L13.539,6.35H11.75ZM5.9,7.825a.75.75,0,0,1,.75-.75H8.1a.75.75,0,0,1,0,1.5H6.65A.75.75,0,0,1,5.9,7.825Zm.75,2.15a.75.75,0,1,0,0,1.5h5.8a.75.75,0,1,0,0-1.5Zm0,2.9a.75.75,0,0,0,0,1.5h5.8a.75.75,0,0,0,0-1.5Z" transform="translate(-3 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					</svg> 
					Designer asociat
				</a>
			</li>
            <li>
				<a href="" data-target="pay">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path d="M9.665,2.079a.75.75,0,0,1,.671,0L17.585,5.7a.75.75,0,0,1,0,1.342l-7.25,3.625a.75.75,0,0,1-.671,0L2.415,7.046a.75.75,0,0,1,0-1.342Zm-5.238,4.3L10,9.161l5.573-2.786L10,3.589ZM2.079,13.29a.75.75,0,0,1,1.006-.335L10,16.412l6.915-3.457a.75.75,0,0,1,.671,1.342l-7.25,3.625a.75.75,0,0,1-.671,0L2.415,14.3A.75.75,0,0,1,2.079,13.29Zm1.006-3.96a.75.75,0,0,0-.671,1.342L9.665,14.3a.75.75,0,0,0,.671,0l7.25-3.625a.75.75,0,0,0-.671-1.342L10,12.787Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					  </svg>											
					Tranzacții/plăți
				</a>
			</li>
            <li>
				<a href="" data-target="invoice">
					<svg id="Settings" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path id="Settings-2" data-name="Settings" d="M2.309,6.772A2.1,2.1,0,0,0,3.125,9.7l.174.1c0,.069,0,.139-.005.205s0,.136.005.205l-.174.1a2.1,2.1,0,0,0-.816,2.928l.984,1.616a2.132,2.132,0,0,0,2.848.732l.158-.087c.2.128.4.247.612.357v.03A2.114,2.114,0,0,0,9.016,18h1.968a2.114,2.114,0,0,0,2.1-2.124v-.03c.21-.11.415-.229.612-.357l.158.087a2.132,2.132,0,0,0,2.848-.732l.984-1.616a2.1,2.1,0,0,0-.816-2.928l-.174-.1c0-.069,0-.139.005-.205s0-.136-.005-.205l.174-.1a2.1,2.1,0,0,0,.816-2.928l-.984-1.616a2.133,2.133,0,0,0-2.848-.732l-.158.087c-.2-.128-.4-.247-.612-.357v-.03A2.114,2.114,0,0,0,10.984,2H9.016a2.114,2.114,0,0,0-2.1,2.124v.03c-.21.11-.415.229-.612.357l-.158-.087a2.133,2.133,0,0,0-2.848.732Zm4.155-.144L5.29,5.984a.359.359,0,0,0-.48.1L3.826,7.7a.322.322,0,0,0,.151.443l1.241.679a4.8,4.8,0,0,0,0,2.363l-1.241.679a.322.322,0,0,0-.151.443l.984,1.616a.359.359,0,0,0,.48.1l1.174-.644a4.945,4.945,0,0,0,2.224,1.294v1.21a.337.337,0,0,0,.328.346h1.968a.337.337,0,0,0,.328-.346v-1.21a4.945,4.945,0,0,0,2.224-1.294l1.174.644a.359.359,0,0,0,.48-.1l.984-1.616a.322.322,0,0,0-.151-.443l-1.241-.679a4.8,4.8,0,0,0,0-2.363l1.241-.679a.322.322,0,0,0,.151-.443L15.19,6.081a.359.359,0,0,0-.48-.1l-1.174.644a4.935,4.935,0,0,0-2.224-1.293V4.124a.337.337,0,0,0-.328-.346H9.016a.337.337,0,0,0-.328.346V5.334A4.936,4.936,0,0,0,6.464,6.628ZM10,12.667A2.667,2.667,0,1,0,7.333,10,2.665,2.665,0,0,0,10,12.667ZM11,10a1,1,0,1,1-1-1A1,1,0,0,1,11,10Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					  </svg>					  
					Factura
				</a>
			</li>
            <li>
				<a href="" data-target="livrare">
					<svg id="Settings" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path id="Settings-2" data-name="Settings" d="M2.309,6.772A2.1,2.1,0,0,0,3.125,9.7l.174.1c0,.069,0,.139-.005.205s0,.136.005.205l-.174.1a2.1,2.1,0,0,0-.816,2.928l.984,1.616a2.132,2.132,0,0,0,2.848.732l.158-.087c.2.128.4.247.612.357v.03A2.114,2.114,0,0,0,9.016,18h1.968a2.114,2.114,0,0,0,2.1-2.124v-.03c.21-.11.415-.229.612-.357l.158.087a2.132,2.132,0,0,0,2.848-.732l.984-1.616a2.1,2.1,0,0,0-.816-2.928l-.174-.1c0-.069,0-.139.005-.205s0-.136-.005-.205l.174-.1a2.1,2.1,0,0,0,.816-2.928l-.984-1.616a2.133,2.133,0,0,0-2.848-.732l-.158.087c-.2-.128-.4-.247-.612-.357v-.03A2.114,2.114,0,0,0,10.984,2H9.016a2.114,2.114,0,0,0-2.1,2.124v.03c-.21.11-.415.229-.612.357l-.158-.087a2.133,2.133,0,0,0-2.848.732Zm4.155-.144L5.29,5.984a.359.359,0,0,0-.48.1L3.826,7.7a.322.322,0,0,0,.151.443l1.241.679a4.8,4.8,0,0,0,0,2.363l-1.241.679a.322.322,0,0,0-.151.443l.984,1.616a.359.359,0,0,0,.48.1l1.174-.644a4.945,4.945,0,0,0,2.224,1.294v1.21a.337.337,0,0,0,.328.346h1.968a.337.337,0,0,0,.328-.346v-1.21a4.945,4.945,0,0,0,2.224-1.294l1.174.644a.359.359,0,0,0,.48-.1l.984-1.616a.322.322,0,0,0-.151-.443l-1.241-.679a4.8,4.8,0,0,0,0-2.363l1.241-.679a.322.322,0,0,0,.151-.443L15.19,6.081a.359.359,0,0,0-.48-.1l-1.174.644a4.935,4.935,0,0,0-2.224-1.293V4.124a.337.337,0,0,0-.328-.346H9.016a.337.337,0,0,0-.328.346V5.334A4.936,4.936,0,0,0,6.464,6.628ZM10,12.667A2.667,2.667,0,1,0,7.333,10,2.665,2.665,0,0,0,10,12.667ZM11,10a1,1,0,1,1-1-1A1,1,0,0,1,11,10Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					  </svg>					  
					Livrare
				</a>
			</li>
			{{--
			<li>
				<a href="" data-target="componente">
					<svg id="Settings" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path id="Settings-2" data-name="Settings" d="M2.309,6.772A2.1,2.1,0,0,0,3.125,9.7l.174.1c0,.069,0,.139-.005.205s0,.136.005.205l-.174.1a2.1,2.1,0,0,0-.816,2.928l.984,1.616a2.132,2.132,0,0,0,2.848.732l.158-.087c.2.128.4.247.612.357v.03A2.114,2.114,0,0,0,9.016,18h1.968a2.114,2.114,0,0,0,2.1-2.124v-.03c.21-.11.415-.229.612-.357l.158.087a2.132,2.132,0,0,0,2.848-.732l.984-1.616a2.1,2.1,0,0,0-.816-2.928l-.174-.1c0-.069,0-.139.005-.205s0-.136-.005-.205l.174-.1a2.1,2.1,0,0,0,.816-2.928l-.984-1.616a2.133,2.133,0,0,0-2.848-.732l-.158.087c-.2-.128-.4-.247-.612-.357v-.03A2.114,2.114,0,0,0,10.984,2H9.016a2.114,2.114,0,0,0-2.1,2.124v.03c-.21.11-.415.229-.612.357l-.158-.087a2.133,2.133,0,0,0-2.848.732Zm4.155-.144L5.29,5.984a.359.359,0,0,0-.48.1L3.826,7.7a.322.322,0,0,0,.151.443l1.241.679a4.8,4.8,0,0,0,0,2.363l-1.241.679a.322.322,0,0,0-.151.443l.984,1.616a.359.359,0,0,0,.48.1l1.174-.644a4.945,4.945,0,0,0,2.224,1.294v1.21a.337.337,0,0,0,.328.346h1.968a.337.337,0,0,0,.328-.346v-1.21a4.945,4.945,0,0,0,2.224-1.294l1.174.644a.359.359,0,0,0,.48-.1l.984-1.616a.322.322,0,0,0-.151-.443l-1.241-.679a4.8,4.8,0,0,0,0-2.363l1.241-.679a.322.322,0,0,0,.151-.443L15.19,6.081a.359.359,0,0,0-.48-.1l-1.174.644a4.935,4.935,0,0,0-2.224-1.293V4.124a.337.337,0,0,0-.328-.346H9.016a.337.337,0,0,0-.328.346V5.334A4.936,4.936,0,0,0,6.464,6.628ZM10,12.667A2.667,2.667,0,1,0,7.333,10,2.665,2.665,0,0,0,10,12.667ZM11,10a1,1,0,1,1-1-1A1,1,0,0,1,11,10Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					  </svg>					  
					Componente
				</a>
			</li>
			<li>
				<a href="" data-target="zone">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path id="Copy" d="M3.705,3.705A.7.7,0,0,1,4.2,3.5h6.525a.7.7,0,0,1,.7.7v.725a.75.75,0,0,0,1.5,0V4.2a2.2,2.2,0,0,0-2.2-2.2H4.2A2.2,2.2,0,0,0,2,4.2v6.525a2.2,2.2,0,0,0,2.2,2.2h.725a.75.75,0,0,0,0-1.5H4.2a.7.7,0,0,1-.7-.7V4.2A.7.7,0,0,1,3.705,3.705Zm4.87,5.57a.7.7,0,0,1,.7-.7H15.8a.7.7,0,0,1,.7.7V15.8a.7.7,0,0,1-.7.7H9.275a.7.7,0,0,1-.7-.7Zm.7-2.2a2.2,2.2,0,0,0-2.2,2.2V15.8a2.2,2.2,0,0,0,2.2,2.2H15.8A2.2,2.2,0,0,0,18,15.8V9.275a2.2,2.2,0,0,0-2.2-2.2Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					</svg>									
					Zone produs
				</a>
			</li>
			<li>
				<a href="" data-target="imagini">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path id="Image" d="M4.361,3.5a.861.861,0,0,0-.861.861V15.639a.861.861,0,0,0,.593.818l8.6-8.6a.75.75,0,0,1,1.061,0L16.5,10.606V4.361a.861.861,0,0,0-.861-.861ZM16.5,12.727,13.222,9.45,6.172,16.5h9.467a.861.861,0,0,0,.861-.861ZM2,4.361A2.361,2.361,0,0,1,4.361,2H15.639A2.361,2.361,0,0,1,18,4.361V15.639A2.361,2.361,0,0,1,15.639,18H4.361A2.361,2.361,0,0,1,2,15.639ZM7.181,6.722a.458.458,0,1,0,.458.458A.458.458,0,0,0,7.181,6.722Zm-1.958.458A1.958,1.958,0,1,1,7.181,9.139,1.958,1.958,0,0,1,5.222,7.181Z" transform="translate(-2 -2)" fill="#6C7A87" fill-rule="evenodd"/>
					</svg>					  
					Imagini
				</a>
			</li> --}}
		</ul>	
	</div>
	<div class='details-right'>
		<a class='general-btn backBtn' href="/admin/orders">Înapoi</a>	
	</div>
</div><!--details-bar-->

<div class="content-container orderDetailView">
    <div class="cms-body">
		<div class="tab-content">
			<div class="tab-pane active" id="detalii">
				<div class="perfect-flex-hold">
                    <div class='perfect-left'>
                        <h4>Detalii Comandă</h4>
                        <div class='flex-inputs viewFlex'>
                            <div class="input-element">
                                <p>Id comandă: <span>{{$item->reference}}</span></p>
                            </div>
    
                            <div class="input-element">
                                <p>Subtotal: <span>{{$item->subtotal}}</span></p>
                            </div>    
                            <div class="input-element">
                                <p>Cost livrare: <span>{{$item->delivery_fee}}</span></p>
                            </div>        
                            <div class="input-element">
                                <p>TVA calculat: <span>{{$item->tva}}</span></p>
                            </div>    
                            <div class="input-element">
                                <p>Total: <span>{{$item->total}}</span></p>
                            </div>        
                            
                            <div class="input-element">
                                <p>Adresă livrare: <span>Str. {{$item->deliveryAddress->street}}, nr. {{$item->deliveryAddress->nr}}, Oraș {{$item->deliveryAddress->city}}, Județ {{$item->deliveryAddress->county}}</span></p>
                            </div>

                            <form method="POST" action='/admin/orders/updateStatus'>
                                <input type="hidden" id='order' name='order' value="{{$item->id}}">
                                @csrf
                                @method('POST')
                                <div class="input-element textarea">
                                    <label for="comments">Comentarii comanda</label>
                                    <textarea name="comments" id="comments" value='{{$item->currentStatus->comments}}'>{{$item->currentStatus->comments}}</textarea>
                                </div>
                                <div class="input-element">
                                    <label for="orders_tracking">Status comandă:</label>
                                    <select name='orders_tracking' id='orders_tracking' value='xx'>
                                        @foreach ($statusList as $k => $status)
                                            <option value="{{array_key_first($status)}}" @if(array_key_first($status) == $item->currentStatus->status) selected @endif >{{$status[array_key_first($status)]}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input class='general-btn' type="submit" value="Update comandă">
                            </form>
                            
                        </div>
                    </div>
                    <div class='perfect-right'>
                        <h4>Comandat de:</h4>
                        <div class='flex-inputs viewFlex'>
                            <div class="input-element">
                                <p>Nume: <span><a href='/admin/accounts/{{$item->user->account->id}}/edit'> {{$item->user->account->name}} {{$item->user->account->surname}}</a></span></p>
                            </div>
    
                            <div class="input-element">
                                <p>Email: <span>{{$item->user->account->email}}</span></p>
                            </div>
                            <div class="input-element">
                                <p>Telefon: <span>{{$item->user->account->phone}}</span></p>
                            </div>        
                            @if ($item->deliveryAddress->is_company == 1)
                            <h4 class='inside'>Detalii Firmă</h4>
                            <div class='flex-inputs viewFlex'>
                                <div class="input-element">
                                    <p>Firmă: <span>{{$item->deliveryAddress->company_name}} {{$item->deliveryAddress->company_type}}</span></p>
                                </div>
                                <div class="input-element">
                                    <p>CUI: <span>{{$item->deliveryAddress->company_vat_type}}{{$item->deliveryAddress->company_cui}}</span></p>
                                </div>
                                <div class="input-element">
                                    <p>Serie: <span>{{$item->deliveryAddress->company_series}}</span></p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class='perfect-right'>
                        <h4>Istoric Comandă</h4>
                        @foreach ($item->trackingHistory as $key => $trackingHistory )
                            <p>{{$trackingHistory->created_at}} - <strong>{{$trackingHistory->status}}</strong></p>
                        @endforeach
                    </div>
                </div>

                <div class="ordered-items-hold">
                    <h4>Produse comandate</h4>
                    <div class='listing-element listing-header'>
                        <div class='listing-box'>
                            <p>ID</p>
                        </div>
                        <div class='listing-box'>
                            <p>Nume</p>
                        </div>
                        <div class='listing-box'>
                            <p>Cantitate</p>
                        </div>
                        <div class='listing-box'>
                            <p>Pret produs</p>
                        </div>
                        <div class='listing-box'>
                            <p>Subtotal</p>
                        </div>
                    </div>
                    <div class='listing-elements-hold'>
                        @foreach($item->orderItems as $key => $product)
                            <div class='listing-element {{ $loop->last ? 'lastElement' : '' }}'>
                                <div class='listing-box'>
                                    <p>{{$key+1}}</p>
                                </div>
                                <div class='listing-box'>
                                    <p><a href='/admin/products/{{$product->id}}/edit'>{{ $product->name }}</a></p>
                                </div>
                                <div class='listing-box'>
                                    <p>{{$product->quantity}}</p>
                                </div>
                                <div class='listing-box'>
                                    <p>{{$product->price_per_unit}} RON</p>
                                </div>
                                <div class='listing-box'>
                                    <p>{{$product->subtotal}} RON</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> <!--ordered-items hold-->

			</div>	
            
            <div class="tab-pane" id="designeri">
                    @foreach ($item->designers as $key => $designer)
                        <p><a href='/admin/accounts/{{$designer->id}}/edit'>{{$designer->name}} {{$designer->surname}} {{$designer->email}}</a></p>
                    @endforeach
			</div>

            <div class="tab-pane" id="pay">
				<div class="perfect-flex-hold">
                    <div class='perfect-left'>
                        <div class='flex-inputs flexInLine'>
                            <div class="input-element">
                                <label for="name">Tip plată</label>
                                <input type="text" name="reference" id='reference' value="{{$item->transactionProvider->name}}" readonly>
                            </div>
                            <div class="input-element">
                                <label for="name">Id tranzacție</label>
                                <input type="text" name="reference" id='reference' value="{{$item->transactions->first()->transaction_identifier}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class='perfect-right'>
                        <h3>Istoric Tranzacții</h3>
                        @foreach ($item->transactions as $transactionItem )
                            <p>{{$transactionItem->created_at}} - {{$transactionItem->status}}</p>
                        @endforeach
                    </div>
                </div>
			</div>
            <div class="tab-pane" id="invoice">
                <div class='flex-inputs viewFlex'>
                    <div class="input-element">
                        <p>Număr factură: <span>{{$item->invoice->invoice_number}}</p>
                    </div>
                    <div class="input-element">
                        <p>Serie factură: <span>{{$item->invoice->invoice_series}}</p>
                    </div>
                    <div class="input-element">
                        <p>Factura FGO: <span><a href='{{$item->invoice->invoice_link}}' target='_blank'>Vezi aici factura</a></p>
                    </div>
                    <div class="input-element">
                        <p>Status factură: <span>{{$item->invoice->status}}</p>
                    </div>

                    @if ($item->invoice->originalStatus=='fgo_invoicedHalf')
                    <form method="POST" action='/admin/orders/buildFinalInvoice'>
                        <input type="hidden" id='order_id' name='order_id' value="{{$item->id}}">
                        <input type="hidden" id='invoice_id' name='invoice_id' value="{{$item->invoice->id}}">
                        <input type="hidden" id='invoice_number' name='invoice_number' value="{{$item->invoice->invoice_number}}">
                        <input type="hidden" id='invoice_series' name='invoice_series' value="{{$item->invoice->invoice_series}}">
                        <input type="hidden" id='invoice_company' name='invoice_company' value="{{$item->user->account->name}} {{$item->user->account->surname}}">
                        @csrf
                        @method('POST')
                        <input class='general-btn' type="submit" value="Generează factura fiscală finală">
                    </form>
                    @else
                    <form method="POST" action='/admin/orders/markFgoInvoiced'>
                        <input type="hidden" id='order_id' name='order_id' value="{{$item->id}}">
                        <input type="hidden" id='invoice_id' name='invoice_id' value="{{$item->invoice->id}}">
                        <input type="hidden" id='invoice_number' name='invoice_number' value="{{$item->invoice->invoice_number}}">
                        <input type="hidden" id='invoice_series' name='invoice_series' value="{{$item->invoice->invoice_series}}">
                        <input type="hidden" id='invoice_company' name='invoice_company' value="{{$item->user->account->name}} 
                        {{$item->user->account->surname}}">
                        <input type="hidden" id='invoice_series' name='invoice_status' value="@php echo ($item->invoice->invoice_series=='PF') ? 'fgo_invoicedHalf' : 'fgo_invoiced'@endphp">
                        @csrf
                        @method('POST')
                        <input class='general-btn' type="submit" value="Setează factura {{$item->invoice->invoice_series}} {{$item->invoice->invoice_number}} ca și încasată">
                    </form>    
                    @endif

                    
                    

                </div>
			</div>	
            <div class="tab-pane" id="livrare">
				<div class='flex-inputs flexInLine'>
                    <div class="input-element">
                        <label for="name">Tip Livrare</label>
                        <input type="text" name="reference" id='reference' value="{{$item->carrier->name}}" readonly>
                    </div>
                </div>
			</div>
            
			{{-- 		
			<div class="tab-pane" id="componente">
				{!! $associatedAttributes !!}
				{!! $buildAttributes !!}
			</div>

			<div class="tab-pane" id="zone">
				{!! $associatedAreas !!}
			</div>
			
			<div class="tab-pane" id="imagini">
				<h2>Imagini:</h2>
				{!! $associatedMedia !!}
			</div> --}}
			
		</div><!--tab-content-->
    </div><!--cms-body-->
</div>

@endsection
