<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index')->name('front.index.index');

Route::get('/about-us', 'PagesController@aboutUs')->name('front.pages.about_us');
Route::get('/frequently-asked-questions', 'PagesController@faq')->name('front.pages.faq');

Route::get('/products', 'ProductsController@index')->name('front.products.index');
Route::get('/products/single/{product}', 'ProductsController@single')->name('front.products.single');

Route::get('/contact', 'ContactController@index')->name('front.contact.index');
Route::post('/contact/send-message', 'ContactController@sendMessage')->name('front.contact.send_message');

Route::get('/shopping-cart', 'ShoppingCartController@index')->name('front.shopping_cart.index');
Route::get('/shopping-cart/content', 'ShoppingCartController@content')->name('front.shopping_cart.content');
Route::post('/shopping-cart/add-product', 'ShoppingCartController@addProduct')->name('front.shopping_cart.add_product');
Route::post('/shopping-cart/remove-product', 'ShoppingCartController@removeProduct')->name('front.shopping_cart.remove_product');


Auth::routes(); //registracija ruta: /login, /password/reset ...



Route::middleware('auth')->prefix('/admin')->namespace('Admin')->group(function () {
    
    Route::get('/', 'IndexController@index')->name('admin.index.index');
    
    
    //Routes for SizesController
    Route::prefix('/sizes')->group(function () {
        
        Route::get('/', 'SizesController@index')->name('admin.sizes.index'); // /admin/sizes
        Route::get('/add', 'SizesController@add')->name('admin.sizes.add');
        Route::post('/insert', 'SizesController@insert')->name('admin.sizes.insert');
        
        Route::get('/edit/{size}', 'SizesController@edit')->name('admin.sizes.edit');
        Route::post('/update/{size}', 'SizesController@update')->name('admin.sizes.update');
        
        Route::post('/delete', 'SizesController@delete')->name('admin.sizes.delete');
        
    });

    //Routes for ProductCategoriesController
    Route::prefix('/product-categories')->group(function () {
        
        Route::get('/', 'ProductCategoriesController@index')->name('admin.product_categories.index'); // /admin/sizes
        Route::get('/add', 'ProductCategoriesController@add')->name('admin.product_categories.add');
        Route::post('/insert', 'ProductCategoriesController@insert')->name('admin.product_categories.insert');
        
        Route::get('/edit/{productCategory}', 'ProductCategoriesController@edit')->name('admin.product_categories.edit');
        Route::post('/update/{productCategory}', 'ProductCategoriesController@update')->name('admin.product_categories.update');
        
        Route::post('/delete', 'ProductCategoriesController@delete')->name('admin.product_categories.delete');
        
        Route::post('/change-priorities', 'ProductCategoriesController@changePriorities')->name('admin.product_categories.change_priorities');
        
        
    });
    
    //Routes for BrandsController
    Route::prefix('/brands')->group(function () {
        
        Route::get('/', 'BrandsController@index')->name('admin.brands.index'); // /admin/sizes
        Route::get('/add', 'BrandsController@add')->name('admin.brands.add');
        Route::post('/insert', 'BrandsController@insert')->name('admin.brands.insert');
        
        Route::get('/edit/{brand}', 'BrandsController@edit')->name('admin.brands.edit');
        Route::post('/update/{brand}', 'BrandsController@update')->name('admin.brands.update');
        
        Route::post('/delete', 'BrandsController@delete')->name('admin.brands.delete');
        
        Route::post('/delete-photo/{brand}', 'BrandsController@deletePhoto')->name('admin.brands.delete_photo');
    });
    
    
    //Routes for ProductsController
    Route::prefix('/products')->group(function () {
        
        Route::get('/', 'ProductsController@index')->name('admin.products.index'); // /admin/sizes
        Route::get('/add', 'ProductsController@add')->name('admin.products.add');
        Route::post('/insert', 'ProductsController@insert')->name('admin.products.insert');
        
        Route::get('/edit/{product}', 'ProductsController@edit')->name('admin.products.edit');
        Route::post('/update/{product}', 'ProductsController@update')->name('admin.products.update');
        
        Route::post('/delete', 'ProductsController@delete')->name('admin.products.delete');
        Route::post('/delete-photo/{product}', 'ProductsController@deletePhoto')->name('admin.products.delete_photo');

        Route::post('/datatable','ProductsController@datatable')->name('admin.products.datatable');
        
    });
    
    
});
