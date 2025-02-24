<?php
require 'youtube_api.php';

if (isset($_GET['id'])) {
    $videoId = trim($_GET['id']);

    $url = "https://youtube138.p.rapidapi.com/video/details/?id=$videoId&hl=es&gl=ES";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Key: 6d67e9d775msh44d2c3326c2ba28p155971jsn8b015c2c99ec",
            "X-RapidAPI-Host: youtube138.p.rapidapi.com"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    // Extraer detalles
    $title = $data['title'] ?? 'Fecha desconocida';
    $publishedDate = $data['publishedDate'] ?? 'Fecha desconocida';
    $views = $data['stats']['views'] ?? 0;
    $likes = $data['stats']['likes'] ?? 0;
    $comments = $data['stats']['comments'] ?? 0;
    $channelId = $data['author']['channelId'] ?? 'Desconocido'; 

    echo json_encode([
        'title' => $title ?? 'No disponible',
        'publishedDate' => $publishedDate ?? 'Desconocido',
        'views' => $views ?? 0,
        'likes' => $likes ?? 0,
        'comments' => $comments ?? 0,
        'channelId' => $channelId ?? ''
    ]);
}
