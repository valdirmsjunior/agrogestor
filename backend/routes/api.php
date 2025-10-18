<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportacaoController;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\PropriedadeController;
use App\Http\Controllers\RebanhoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UnidadeProducaoController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    Route::apiResource('produtores', ProdutorController::class)->parameter('produtores', 'produtor');
    Route::apiResource('propriedades', PropriedadeController::class)->parameter('propriedades', 'propriedade');
    Route::apiResource('unidades-producao', UnidadeProducaoController::class)->parameter('unidades_producao', 'unidade_producao');
    Route::apiResource('rebanhos', RebanhoController::class)->parameter('rebanhos', 'rebanho');

    Route::get('/relatorios', [RelatorioController::class, 'index']);
    Route::get('/export/propriedades', [ExportacaoController::class, 'propriedades']);
    Route::get('/export/rebanhos', [ExportacaoController::class, 'rebanhos']);
});
