<?php

function getYouTubeVideos($query)
{
    $query = urlencode($query);
    $apiKey = "6d67e9d775msh44d2c3326c2ba28p155971jsn8b015c2c99ec"; // Reemplaza con tu clave
    $host = "youtube138.p.rapidapi.com";
    $url = "https://$host/search/?q=$query&hl=es&gl=ES";
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: $host",
            "x-rapidapi-key: $apiKey"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return $err ? json_encode(["error" => $err]) : $response;
}
?>
