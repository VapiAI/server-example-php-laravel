<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutboundController extends Controller
{
    public function create(Request $request)
    {
        // Extract phoneNumberId, assistantId, and customerNumber from the request body
        $data = json_decode($request->getContent(), true);
        $phoneNumberId = $data['phoneNumberId'];
        $assistantId = $data['assistantId'];
        $customerNumber = $data['customerNumber'];

        try {
            /**!SECTION
             * Handle Outbound Call logic here.
             * This can initiate an outbound call to a customer's phonenumber using Vapi.
             */

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('VAPI_BASE_URL') . '/call/phone',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array(
                    'phoneNumberId' => $phoneNumberId,
                    'assistantId' => $assistantId,
                    'customer' => array(
                        'number' => $customerNumber
                    )
                )),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . env('VAPI_API_KEY')
                ),
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new \Exception('Curl error: ' . curl_error($curl));
            }

            curl_close($curl);

            $data = json_decode($response, true);
            return response()->json($data, 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Failed to place outbound call',
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
