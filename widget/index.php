<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Videos - YouTube API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Buscar videos en YouTube :D</h2>
        <form method="GET" action="" class="search-container d-flex">
            <input type="text" class="form-control me-2" name="q" placeholder="Ingrese el nombre del video"
                value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" required>
            <button type="submit" class="btn btn-danger">Buscar</button>
        </form>

        <div class="row mt-4">
            <?php
            require 'youtube_api.php';

            $query = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (!empty($query)) {
                $response = getYouTubeVideos($query);
                $data = json_decode($response, true);

                if (isset($data['contents'])) {
                    foreach ($data['contents'] as $video) {
                        if (isset($video['video'])) {
                            $videoId = $video['video']['videoId'];
                            $title = htmlspecialchars($video['video']['title']);
                            $thumbnail = $video['video']['thumbnails'][0]['url'];
                            // Información adicional
                            $publishedDate = isset($video['video']['publishedTimeText']) ? $video['video']['publishedTimeText'] : 'Fecha desconocida';
                            $views = isset($video['video']['stats']['views']) ? number_format($video['video']['stats']['views']) . ' vistas' : 'No disponible';
                            ?>
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <img src="<?= $thumbnail ?>" alt="<?= $title ?>" class="card-img-top video-thumbnail"
                                        data-bs-toggle="modal" data-bs-target="#videoModal" data-video-id="<?= $videoId ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= $title ?></h6>
                                        <p class="text-muted mb-1"><?= $publishedDate ?></p>
                                        <p class="small text-muted"><?= $views ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "<p class='text-center'>No se encontraron videos.</p>";
                }
            }
            ?>
        </div>
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="videoModalLabel">
                            <i class="fa-solid fa-circle-play"></i> Reproduciendo video
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Video -->
                        <div class="ratio ratio-16x9">
                            <iframe id="videoFrame" src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <hr>

                        <!-- Detalles del Video -->
                        <div id="videoDetails" class="p-3">
                            <!-- Título del Video -->
                            <h4 id="title" class="fw-bold mb-3"><i class="fa-solid fa-film"></i> Cargando...</h4>

                            <!-- Información en una fila -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <span id="publishedDate" class="text-muted">Cargando...</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-eye me-2"></i>
                                    <span id="views">Cargando...</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-thumbs-up me-2 text-primary"></i>
                                    <span id="likes">Cargando...</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-comments me-2 text-secondary"></i>
                                    <span id="comments">Cargando...</span>
                                </div>
                                <div>
                                    <a id="subscribeButton" href="#" target="_blank" class="btn btn-danger">
                                        <i class="fa-solid fa-bell me-1"></i> Suscribirse
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- Sección de Comentarios -->
                        <h5 class="mb-3"><i class="fa-solid fa-comments"></i> Comentarios</h5>
                        <div id="videoComments" class="overflow-auto p-2 bg-light rounded" style="max-height: 300px;">
                            <p class="text-center text-muted">Cargando comentarios...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Script Externo -->
        <script src="logica.js"></script>

    </div>
</body>

</html>