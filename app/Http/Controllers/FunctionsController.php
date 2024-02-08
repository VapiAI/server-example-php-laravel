<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FunctionsController extends Controller
{
    public function create_basic(Request $request)
    {
        return 'functions_basic';
    }

    public function create_rag(Request $request)
    {
        return 'functions_rag';
    }
}
