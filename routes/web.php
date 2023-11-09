<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/api/v1/products', 'ProductController@index');
$router->get('/api/v1/products/{id}', 'ProductController@show');
$router->post('/api/v1/products/create', 'ProductController@store');
// for some reason put method is not working in update product
// By using put method we didn't get any data in controller update method
// That's why we used post method
$router->post('/api/v1/products/update/{id}', 'ProductController@update');
$router->delete('/api/v1/products/delete/{id}', 'ProductController@destroy');

$router->get('/', function () use ($router) {
    echo "<h1>Hello Lumen</h1>";
});

// Route::view('/products', 'product.index');
// Route::view('/products/create', 'product.create');
// Route::view('/product/show/{id}', 'product.show');
// Route::view('/product/edit/{id}', 'product.edit');