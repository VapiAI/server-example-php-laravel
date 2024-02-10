<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class InboundController extends Controller
{
    public function create(Request $request)
    {
        try {
            $payload = $request->input('message');
            switch ($payload['type']) {
                case 'ASSISTANT_REQUEST':
                    $assistant = $payload['call']
                        ? [
                            'name' => 'Paula',
                            'model' => [
                                'provider' => 'openai',
                                'model' => 'gpt-3.5-turbo',
                                'temperature' => 0.7,
                                'systemPrompt' => "You're Paula, an AI assistant who can help user draft beautiful emails to their clients based on the user requirements. Then Call sendEmail function to actually send the email.",
                                'functions' => [
                                    [
                                        'name' => 'sendEmail',
                                        'description' => 'Send email to the given email address and with the given content.',
                                        'parameters' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'email' => [
                                                    'type' => 'string',
                                                    'description' => 'Email to which we want to send the content.',
                                                ],
                                                'content' => [
                                                    'type' => 'string',
                                                    'description' => 'Actual Content of the email to be sent.',
                                                ],
                                            ],
                                            'required' => ['email'],
                                        ],
                                    ],
                                ],
                            ],
                            'voice' => [
                                'provider' => '11labs',
                                'voiceId' => 'paula',
                            ],
                            'firstMessage' => "Hi, I'm Paula, your personal email assistant.",
                        ]
                        : null;
                    if ($assistant) return response()->json(['assistant' => $assistant], 200);
                    break;
                default:
                    throw new \Exception('Unhandled message type');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
