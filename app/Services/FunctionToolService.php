<?php

namespace App\Services;

use Illuminate\Http\Request;

class FunctionToolService
{
  /**
   * Get a random character inspiration.
   *
   * @param string $inspiration
   * @return array
   */
  public function getCharacterInspiration($inspiration)
  {
    $fallbackResponse = [
      'result' => 'Sorry, I am dealing with a technical issue at the moment, perhaps because of heightened user traffic. Come back later and we can try this again. Apologies for that.',
    ];

    if ($inspiration) {
      try {
        // $documents = (new SimpleDirectoryReader())->loadData([
        //     'directoryPath' => __DIR__ . '/../data',
        // ]);

        // $index = VectorStoreIndex::fromDocuments($documents);

        // $queryEngine = $index->asQueryEngine();
        // $response = $queryEngine->query(['query' => $inspiration]);

        $response = [
          'response' => 'This is a placeholder response for the getCharacterInspiration function. It should be replaced with the actual implementation.',
        ];

        return [
          'result' => $response['response'],
          'forwardToClientEnabled' => true,
        ];
      } catch (\Exception $error) {
        echo 'error: ' . $error->getMessage();
        return $fallbackResponse;
      }
    } else {
      return $fallbackResponse;
    }
  }

  /**
   * Get a random name.
   *
   * @return string
   */
  public function getRandomName($params)
  {
    $nats = [
      "AU",
      "CA",
      "FR",
      "IN",
      "IR",
      "MX",
      "NL",
      "NO",
      "NZ",
      "RS",
      "TR",
      "US",
    ];

    $nat = isset($params['nat']) && !in_array(strtoupper($params['nat']), $nats)
      ? $nats[rand(0, count($nats) - 1)]
      : $params['nat'] ?? "";

    $queryParams = http_build_query(array_merge($params, ['nat' => $nat]));

    try {
      $response = file_get_contents("https://randomuser.me/api/?" . $queryParams);
      if (!$response) {
        throw new \Exception("Error fetching random name");
      }
      $data = json_decode($response, true);
      $name = $data['results'][0]['name'];
      return [
        'result' => $name['first'] . " " . $name['last'],
      ];
    } catch (\Exception $err) {
      throw new \Exception("Error fetching random name");
    }
  }
}
