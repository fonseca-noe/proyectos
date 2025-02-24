<?php
require 'youtube_api.php';

if (isset($_GET['id'])) {
    $videoId = trim($_GET['id']);

    $url = "https://youtube138.p.rapidapi.com/video/comments/?id=$videoId&hl=es&gl=ES";

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
    $comments = [];

    if (isset($data['comments'])) {
        foreach ($data['comments'] as $comment) {
            $comments[] = [
                'author' => $comment['author']['title'] ?? 'Anónimo',
                'avatar' => !empty($comment['author']['avatar'][0]['url'])
                    ? $comment['author']['avatar'][0]['url']
                    : 'https://via.placeholder.com/50?text=No+Image',
                'content' => $comment['content'] ?? 'Comentario no disponible',
                'publishedTimeText' => $comment['publishedTimeText'] ?? 'Fecha desconocida',
                'votes' => $comment['stats']['votes'] ?? 0,
                'replies' => $comment['stats']['replies'] ?? 0
            ];
        }
    }

    echo json_encode($comments);
}
