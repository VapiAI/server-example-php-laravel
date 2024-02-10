<?php

namespace App\Http\Controllers;

use App\Services\FunctionToolService;
use Illuminate\Http\Request;

class FunctionsController extends Controller
{

    private $functionToolService;

    public function __construct()
    {
        $this->functionToolService = new FunctionToolService();
    }
    public function create_basic(Request $request)
    {
        $payload = $request->input('message');
        $type = $payload['type'];

        switch ($type) {
            case 'function-call':
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
            default:
                // ignore other types of messages
                return;
        }
    }

    public function create_rag(Request $request)
    {
        $payload = $request->input('message');
        $type = $payload['type'];

        switch ($type) {
            case 'function-call':
                $functionCall = $payload['functionCall'];

                if (!$functionCall) {
                    throw new \Exception("Invalid Request.");
                }

                $name = $functionCall['name'];
                $parameters = $functionCall['parameters'];

                if ($name == "getCharacterInspiration") {
                    return $this->functionToolService->getCharacterInspiration($parameters);
                }

                return;
            default:
                // ignore other types of messages
                return;
        }
    }
}
