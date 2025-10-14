<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\PropriedadeController;
use App\Http\Controllers\RebanhoController;
use App\Http\Controllers\UnidadeProducaoController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    Route::apiResource('produtores', ProdutorController::class);
    Route::apiResource('propriedades', PropriedadeController::class);
    Route::apiResource('unidades-producao', UnidadeProducaoController::class);
    Route::apiResource('rebanhos', RebanhoController::class);
});
