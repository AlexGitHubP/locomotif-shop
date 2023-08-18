<?php

Route::group(['middleware'=>'web'], function(){
	Route::resource('/admin/orders',               'Locomotif\Shop\Controller\OrdersController');	
	Route::POST('/admin/orders/updateStatus',      'Locomotif\Shop\Controller\OrdersController@updateTrackingStatus');	
	Route::POST('/admin/orders/markFgoInvoiced',   'Locomotif\Shop\Controller\OrdersController@markFgoInvoiced');
	Route::POST('/admin/orders/buildFinalInvoice', 'Locomotif\Shop\Controller\OrdersController@buildFinalInvoice');
	
});
