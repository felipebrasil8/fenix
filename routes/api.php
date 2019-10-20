<?php



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/ticket/totais/{departamento}', 'Api\ApiTokenController@dadosTickets');	

Route::get('/atualizarAlarmesServidoresCliente', '\App\Services\MonitoramentoServidoresClientesService@apiAlarmesServidoresCliente');