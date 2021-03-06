<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('/usuarios', 'UserController@index')->name('users.index');
/*
Route::get('/usuarios', function () {
    return 'Usuarios';
});
*/

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show')    
        ;

/*
Route::get('/usuarios/{id}', 'UserController@show')
    ->where('id', '[0-9]+')
    ->name('users.show')    
        ;
 * 
 */
/*
Route::get('/usuarios/{id}', function ($id) {
    return "Mostrando detalle del usuario: {$id}";
})->where('id','[0-9]+');
 * 
 */

Route::get('/usuarios/nuevo', 'UserController@create');
/*
Route::get('/usuarios/nuevo', function () {
    return 'Crear nuevo usuario';
});
*/

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');
/*
Route::get('/saludo/{name}/{nickname?}', function ($name, $nickname = null) {
    $name = ucfirst($name);

    if ($nickname) {
        return "Bienvenido {$name}, tu apodo es {$nickname}";
    } else {
        return "Bienvenido {$name}";
    }
});
*/

Route::get('/usuarios/{user}/editar', 'UserController@edit')->name('users.edit');

Route::post('/usuarios', 'UserController@store');

Route::put('/usuarios/{user}', 'UserController@update');


