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

Route::get('/', function () {
    return view('auth/login');
});

Route::resource('almacen/categoria','CategoriaController');
Route::resource('precios/actualizar','PrecioController');
Route::resource('almacen/articulo','ArticuloController');
Route::resource('reportes/grafico','ReportesController');
Route::get('/test', 'ReportesController@projectsChartData');
Route::resource('ventas/cliente','ClienteController');
Route::resource('compras/proveedor','ProveedorController');
Route::resource('compras/ingreso','IngresoController');
Route::resource('ventas/venta','VentaController');
Route::resource('seguridad/usuario','UsuarioController');
Route::resource('compras/stockminimo','StockMinimoController');
Route::resource('pagos','PagosController');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/prodview','ArticuloController@prodfunct');
Route::get('/findProductName','ArticuloController@findProductName');
Route::get('/findPrice','ArticuloController@findPrice');
Route::get('/exportArticulo/{selectText}','ArticuloController@exportArticulo');
Route::get('/buscarUltimoId','ArticuloController@buscarUltimoId');
Route::get('/existeArticulo','ArticuloController@existeArticulo');


Route::get('/articulosSinStock','ReportesController@articulosSinStock');
Route::get('/cajaDelDiaReportes','ReportesController@cajaDelDiaReportes');
Route::get('/cajaDeAyer','ReportesController@cajaDeAyer');
Route::get('/ventasPorProductos','ReportesController@ventasPorProductos');
Route::get('/ventasDelAno','ReportesController@ventasDelAno');
Route::get('/proveedorQueMasProductosVende','ReportesController@proveedorQueMasProductosVende');
Route::get('/ganancias','ReportesController@ganancias');
Route::get('/volveracero/{id}','ReportesController@volveracero');
Route::get('/detalleganancias/{daterange}','ReportesController@detalleganancias');

Route::get('/buscarPrecioArticuloVentasPorCodigo','VentaController@buscarPrecioArticuloVentasPorCodigo');

Route::get('/buscarPrecioArticuloIngresosPorCodigo','IngresoController@buscarPrecioArticuloIngresosPorCodigo');

Route::get('/buscarArticuloParaIngreso','IngresoController@buscarArticuloParaIngreso');


Route::get('/mostrarPrecio/{id}','ArticuloController@mostrarPrecio');
Route::get('/buscarProveedor','ArticuloController@buscarProveedor');

//Route::get('/export','VentaController@export');
Route::get('/exportDetalle/{daterange}','VentaController@exportDetalle');
Route::get('/exportResultado/{daterange}','VentaController@exportResultado');
Route::get('/cajaDelDia','VentaController@cajaDelDia');


Route::get('/buscarArticuloPorProveedorEnIngreso','IngresoController@buscarArticuloPorProveedorEnIngreso');
Route::get('/buscarArticuloPorProveedor','VentaController@buscarArticuloPorProveedor');
Route::get('/buscarArticuloPorProveedor','PrecioController@buscarArticuloPorProveedor');
Route::get('/buscarArticuloPorPrecioYPorProveedor','PrecioController@buscarArticuloPorPrecioYPorProveedor');
Route::get('/buscarPrecioArticuloVentas','VentaController@buscarPrecioArticuloVentas');
Route::get('/buscarPrecioArticulo','PrecioController@buscarPrecioArticulo');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/editarEstado/{id}', 'CategoriaController@editarEstado');

Route::get('/cambiarEstadoArticulo/{id}', 'ArticuloController@cambiarEstadoArticulo');
Route::get('/cambiarEstado/{id}', 'ProveedorController@cambiarEstado');

Route::get('barcode', 'HomeController@barcode');
Route::get('search/autocomplete', 'VentaController@autocomplete');

Route::get('/reportes/cajadeayer', 'ReportesController@getCajaDeAyer');
Route::get('/reportes/cajadehoy', 'ReportesController@getCajaDeHoy');
Route::get('/reportes/detallestock', 'ReportesController@getDetalleStock');
Route::get('/reportes/detalleganancias', 'ReportesController@getDetalleGanancias');

Route::get('/precios/porarticulo', 'PrecioController@getPorArticulo');

Route::post('/precios/porarticulo', 'PrecioController@storeArticulo');

Route::get('/precios/porfamilia', 'PrecioController@getPorFamilia');

Route::post('/precios/porfamilia', 'PrecioController@storeFamilia');

Route::get('/almacen/createPorCodigo', 'ArticuloController@getPorCodigo');

Route::post('/precios/porfamilia', 'PrecioController@storeFamilia');

Route::post('/almacen/createPorCodigo', 'ArticuloController@storePorCodigo');

Auth::routes();
