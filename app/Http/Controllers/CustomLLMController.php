<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomLLMController extends Controller
{
    
    public function create_basic(Request $request)
    {
        return 'custom_llm_basic';
    }

    public function create_openai_sse(Request $request)
    {
        return 'custom_llm_openai_sse';
    }
    public function create_openai_advancede(Request $request)
    {
        return 'custom_llm_openai_advanced';
    }
}
