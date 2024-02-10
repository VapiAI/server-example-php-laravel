<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\FunctionToolService;

class WebhookService
{
  private $functionToolService;

  public function __construct()
  {
    $this->functionToolService = new FunctionToolService();
  }

  public function handleWebhook(Request $request)
  {
    $payload = $request->input('message');
    $type = $payload['type'];

    switch ($type) {
      case 'function-call':
        return $this->handleFunctionCall($payload);
      case 'status-update':
        return $this->handleStatusUpdate($payload);
      case 'assistant-request':
        return $this->handleAssistantRequest($payload);
      case 'end-of-call-report':
        return $this->handleEndOfCallReport($payload);
      case 'speech-update':
        return $this->handleSpeechUpdate($payload);
      case 'transcript':
        return $this->handleTranscript($payload);
      case 'hang':
        return $this->handleHang($payload);
      default:
        throw new \Exception('Unhandled message type');
    }
  }

  private function handleFunctionCall($payload)
  {
    // Handle function call event
    // Extract the function call details from the payload
    $functionCall = $payload['functionCall'];

    if (!$functionCall) {
      throw new \Exception("Invalid Request.");
    }

    $name = $functionCall['name'];
    $parameters = $functionCall['parameters'];

    if ($name == "getRandomName") {
      return  $this->functionToolService->getRandomName($parameters);
    } else if ($name == "getCharacterInspiration") {
      return $this->functionToolService->getCharacterInspiration($parameters);
    }

    return;
  }
  private function handleStatusUpdate($payload)
  {
    // Handle status update event
    // Extract the status details from the payload
    $status = $payload['status'];

    // You can then use this status to update your database or perform other actions
    // For example, you might want to log the status update or notify other parts of your system
  }

  private function handleAssistantRequest($payload)
  {
    /**!SECTION
     * Handle Business logic here.
     * You can fetch your database to see if there is an existing assistant associated with this call. If yes, return the assistant.
     * You can also fetch some params from your database to create the assistant and return it.
     * You can have various predefined static assistant here and return them based on the call details.
     */

    $assistant = $payload['call'] ? [
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
    ] : null;

    if ($assistant) {
      return ['assistant' => $assistant];
    }

    throw new \Exception('Invalid call details provided.');
  }

  private function handleEndOfCallReport($payload)
  {
    // Handle end of call report event
    // Extract the report details from the payload
    $endOfCallReport = $payload['endOfCallReport'];

    // You can then use this report to update your database or perform other actions
    // For example, you might want to store the report for later analysis or trigger some post-call actions
  }

  private function handleSpeechUpdate($payload)
  {
    // Handle speech update event
    // Extract the speech update details from the payload
    $speechUpdate = $payload['speechUpdate'];

    // You can then use this update to perform actions in your application
    // For example, you might want to update the UI to reflect who is currently speaking
  }

  private function handleTranscript($payload)
  {
    // Handle transcript event
    // Extract the transcript details from the payload
    $transcript = $payload['transcript'];

    // You can then use this transcript to update your database or perform other actions
    // For example, you might want to store the transcript for later analysis or display it in the UI
  }

  private function handleHang($payload)
  {
    // Handle hang event
    // Extract the hang details from the payload
    $hang = $payload['hang'];

    // You can then use this event to perform actions in your application
    // For example, you might want to log the hang event or notify your team of potential issues
  }
}
