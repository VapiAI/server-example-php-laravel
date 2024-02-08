<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\FunctionsController;
use App\Http\Controllers\CustomLLMController;
use App\Http\Controllers\WebhookController;


Route::middleware('api')->group(function () {
    Route::post('/inbound', [InboundController::class, 'create']);

    Route::post('/outbound', [OutboundController::class, 'create']);
    Route::post('/functions/basic', [FunctionsController::class, 'create_basic']);
    Route::post('/functions/rag', [FunctionsController::class, 'create_rag']);
    Route::post('/custom-llm/basic', [CustomLLMController::class, 'create_basic']);
    Route::post('/custom-llm/openai-sse', [CustomLLMController::class, 'create_openai_sse']);
    Route::post('/custom-llm/openai-advanced', [CustomLLMController::class, 'create_openai_advanced']);
    Route::post('/webhook', [WebhookController::class, 'create']);
});
