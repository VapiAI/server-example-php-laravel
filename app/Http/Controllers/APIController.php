<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function inbound(Request $request)
    {
        return 'inbound';
    }

    public function outbound(Request $request)
    {
        return 'outbound';
    }

    public function functionsBasic(Request $request)
    {
        return 'functions/basic';
    }

    public function functionsRag(Request $request)
    {
        return 'functions/rag';
    }

    public function customLlmBasic(Request $request)
    {
        return 'custom-llm/basic';
    }

    public function customLlmOpenaiSse(Request $request)
    {
        return 'custom-llm/openai-sse';
    }

    public function customLlmOpenaiAdvanced(Request $request)
    {
        return 'custom-llm/openai-advanced';
    }

    public function webhook(Request $request)
    {
        return 'webhook';
    }
}
