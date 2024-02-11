<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomLLMController extends Controller
{

    public function create_basic(Request $request)
    {
        try {

            $data = $request->json()->all();
            $messages = $data['messages'];

            $response = [
                'id' => 'chatcmpl-8mcLf78g0quztp4BMtwd3hEj58Uof',
                'object' => 'chat.completion',
                'created' => time(),
                'model' => 'gpt-3.5-turbo-0613',
                'system_fingerprint' => null,
                'choices' => [
                    [
                        'index' => 0,
                        'delta' => ['content' => $messages[count($messages) - 1]['content'] ?? ''],
                        'logprobs' => null,
                        'finish_reason' => 'stop',
                    ],
                ],
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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


    public function create_openai_advanced(Request $request)
    {
        try {
            $yourApiKey = env('OPENAI_API_KEY'); // Replace with your API Key.
            $client =  OpenAI::client($yourApiKey);

            $model = $request->input('model', 'gpt-3.5-turbo');
            $messages = $request->input('messages');
            $max_tokens = $request->input('max_tokens', 150);
            $temperature = $request->input('temperature', 0.7);
            $stream = $request->input('stream', true);

            $lastMessage = end($messages);
            $prompt = $client->completions()->create([
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => "Create a prompt which can act as a prompt templete where I put the original prompt and it can modify it according to my intentions so that the final modified prompt is more detailed.You can expand certain terms or keywords.\n----------\nPROMPT: {$lastMessage['content']}.\nMODIFIED PROMPT: ",
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            $modifiedMessage = array_merge(array_slice($messages, 0, count($messages) - 1), [['role' => $lastMessage['role'], 'content' => $prompt->choices[0]->text]]);

            if ($stream) {
                $result = $client->chat()->createStreamed([
                    'model' => $model,
                    'messages' => $modifiedMessage,
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
                    'messages' => $modifiedMessage,
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
}
