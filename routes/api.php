<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::middleware('api')->group(function () {
    Route::post('/inbound', [APIController::class, 'inbound']);
    Route::post('/outbound', [APIController::class, 'outbound']);
    Route::post('/functions/basic', [APIController::class, 'functionsBasic']);
    Route::post('/functions/rag', [APIController::class, 'functionsRag']);
    Route::post('/custom-llm/basic', [APIController::class, 'customLlmBasic']);
    Route::post('/custom-llm/openai-sse', [APIController::class, 'customLlmOpenaiSse']);
    Route::post('/custom-llm/openai-advanced', [APIController::class, 'customLlmOpenaiAdvanced']);
    Route::post('/webhook', [APIController::class, 'webhook']);
});
