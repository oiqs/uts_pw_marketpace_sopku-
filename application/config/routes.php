<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* ---- Halaman (React views) ---- */
$route['katalog']          = 'home/katalog';
$route['detail/(:num)']     = 'home/detail/$1';
$route['cart']              = 'home/cart';
$route['login']             = 'home/login';
$route['checkout']          = 'home/checkout';
$route['orders']            = 'home/orders';

/* ---- API: Produk & Kategori ---- */
$route['api/products']             = 'api/products';
$route['api/products/(:num)']      = 'api/product/$1';
$route['api/categories']           = 'api/categories';

/* ---- API: Cart ---- */
$route['api/cart']                 = 'api/cart';
$route['api/cart/add']             = 'api/cart_add';
$route['api/cart/update']          = 'api/cart_update';
$route['api/cart/remove/(:num)']   = 'api/cart_remove/$1';
$route['api/cart/clear']           = 'api/cart_clear';

/* ---- API: Auth ---- */
$route['api/login']                = 'api/login';
$route['api/register']             = 'api/register';
$route['api/logout']               = 'api/logout';
$route['api/me']                   = 'api/me';

/* ---- API: Checkout ---- */
$route['api/couriers']             = 'api/couriers';
$route['api/promo/check']          = 'api/promo_check';
$route['api/checkout']             = 'api/checkout';
$route['api/orders']               = 'api/orders';
$route['api/returns/create']       = 'api/return_create';

$route['admin']                        = 'admin/dashboard';
$route['admin/login']                  = 'admin/login';
$route['admin/logout']                 = 'admin/logout';
$route['admin/dashboard']              = 'admin/dashboard';

$route['admin/products']               = 'admin/products';
$route['admin/products/create']        = 'admin/product_create';
$route['admin/products/edit/(:num)']   = 'admin/product_edit/$1';
$route['admin/products/delete/(:num)'] = 'admin/product_delete/$1';

$route['admin/orders']                 = 'admin/orders';
$route['admin/orders/(:num)']          = 'admin/order_detail/$1';
$route['admin/orders/status/(:num)']   = 'admin/order_status/$1';
$route['admin/returns']                = 'admin/returns';
$route['admin/returns/(:num)']         = 'admin/return_detail/$1';
$route['admin/returns/status/(:num)']  = 'admin/return_status/$1';

$route['admin/users']                  = 'admin/users';
$route['admin/users/delete/(:num)']    = 'admin/user_delete/$1';

$route['admin/categories']             = 'admin/categories';
$route['admin/categories/create']      = 'admin/category_create';
$route['admin/categories/delete/(:num)'] = 'admin/category_delete/$1';

$route['admin/couriers']               = 'admin/couriers';
$route['admin/couriers/delete/(:num)'] = 'admin/courier_delete/$1';
$route['admin/promos']                 = 'admin/promos';
$route['admin/promos/create']          = 'admin/promo_create';
$route['admin/promos/delete/(:num)']   = 'admin/promo_delete/$1';

$route['admin/reviews']                = 'admin/reviews';
$route['admin/reviews/delete/(:num)']  = 'admin/review_delete/$1';
