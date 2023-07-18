<?php

Route::group(['middleware'=>'web'], function(){
	Route::resource('/admin/orders', 'Locomotif\Shop\Controller\OrdersController');	
});
