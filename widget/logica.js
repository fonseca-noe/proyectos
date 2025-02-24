document.addEventListener("DOMContentLoaded", function () {
  var videoModal = document.getElementById("videoModal");
  var videoFrame = document.getElementById("videoFrame");

  videoModal.addEventListener("show.bs.modal", function (event) {
    var button = event.relatedTarget;
    var videoId = button.getAttribute("data-video-id");

    videoFrame.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;

    // Obtener detalles del video
    fetch(`youtube_details.php?id=${videoId}`)
      .then((response) => response.json())
      .then((data) => {
        document.getElementById("title").textContent =
          data.title || "No disponible";
        document.getElementById("publishedDate").textContent =
          data.publishedDate || "Desconocido";
        document.getElementById("views").textContent = data.views
          ? new Intl.NumberFormat().format(data.views) + " vistas"
          : "No disponible";
        document.getElementById("likes").textContent = data.likes
          ? new Intl.NumberFormat().format(data.likes)
          : "No disponible";
        document.getElementById("comments").textContent = data.comments
          ? new Intl.NumberFormat().format(data.comments)
          : "No disponible";

        let subscribeButton = document.getElementById("subscribeButton");
        if (data.channelId && data.channelId !== "Desconocido") {
          subscribeButton.setAttribute(
            "href",
            `https://www.youtube.com/channel/${data.channelId}`
          );
          subscribeButton.setAttribute("target", "_blank");
          subscribeButton.setAttribute("rel", "noopener noreferrer");
          subscribeButton.style.display = "inline-block";
        } else {
          subscribeButton.style.display = "none";
        }
      })
      .catch((error) => {
        console.error("Error cargando detalles del video:", error);
      });

    // Obtener comentarios del video
    fetch(`youtube_comments.php?id=${videoId}`)
      .then((response) => response.json())
      .then((data) => {
        let commentsContainer = document.getElementById("videoComments");
        commentsContainer.innerHTML = ""; // Limpiar comentarios previos

        if (data.length > 0) {
          data.forEach((comment) => {
            let commentHTML = `
                  <div class="d-flex align-items-start mb-3 p-3 border rounded shadow-sm bg-white">
                      <!-- Avatar del usuario -->
                      <img src="${comment.avatar}" alt="Avatar" class="rounded-circle me-3" width="50" height="50">
      
                      <div class="w-100">
                          <!-- Autor y fecha -->
                          <p class="mb-1">
                              <strong>${comment.author}</strong> 
                              <small class="text-muted">(${comment.publishedTimeText})</small>
                          </p>
                          
                          <!-- Contenido del comentario -->
                          <p>${comment.content}</p>
      
                          <!-- Likes y respuestas -->
                          <p class="text-muted small">
                              <i class="fa-solid fa-thumbs-up text-primary"></i> ${comment.votes}  
                              <i class="fa-solid fa-reply text-secondary ms-3"></i> ${comment.replies}
                          </p>
                      </div>
                  </div>`;
            commentsContainer.innerHTML += commentHTML;
          });
        } else {
          commentsContainer.innerHTML =
            "<p class='text-center text-muted'>No hay comentarios disponibles.</p>";
        }
      })
      .catch((error) => {
        console.error("Error cargando comentarios:", error);
        document.getElementById("videoComments").innerHTML =
          "<p class='text-danger text-center'>Error al cargar comentarios.</p>";
      });
  });

  videoModal.addEventListener("hidden.bs.modal", function () {
    videoFrame.src = "";
  });
});
