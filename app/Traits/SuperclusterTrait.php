<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait SuperclusterTrait {

    public function refreshSuperclusterIndex() {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://127.0.0.1:8080/refreshIndex');
        $body = $response->getBody();
        if($body != "OK") {
            Log::error("Failed to refresh Supercluster index");
        }
    }
}
