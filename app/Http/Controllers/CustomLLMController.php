<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomLLMController extends Controller
{

    public function create_basic(Request $request)
    {
        return 'custom_llm_basic';
    }



    public function create_openai_sse(Request $request)
    {
        try {
            $yourApiKey = env('OPENAI_API_KEY'); // Replace with your API Key.
            $client =  OpenAI::client($yourApiKey);

            $model = $request->input('model', 'gpt-3.5-turbo');
            $messages = $request->input('messages');
            $max_tokens = $request->input('max_tokens', 150);
            $temperature = $request->input('temperature', 0.7);
            $stream = $request->input('stream', true);

            if ($stream) {

                $result = $client->chat()->createStreamed([
                    'model' => $model,
                    'messages' => $messages,
                    'max_tokens' => $max_tokens,
                    'temperature' => $temperature,
                    'stream' => true,
                ]);


                $response = new StreamedResponse(function () use ($result) {
                    foreach ($result as $chunk) {
                        echo 'data: ' . json_encode($chunk) . "\n\n";
                        ob_flush();
                        flush();
                    }
                });
                $response->headers->set('Content-Type', 'text/event-stream');
                $response->headers->set('Cache-Control', 'no-cache');
                $response->headers->set('Connection', 'keep-alive');

                return $response;
            } else {
                $result = $client->chat()->create([
                    'model' => $model,
                    'messages' => $messages,
                    'max_tokens' => $max_tokens,
                    'temperature' => $temperature,
                    'stream' => false,
                ]);

                return response()->json($result, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function create_openai_advancede(Request $request)
    {
        return 'custom_llm_openai_advanced';
    }
}
