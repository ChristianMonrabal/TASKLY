function cargamensaje(postulacionId) {
    // marcar el chat como activo
    var chats = document.querySelectorAll(".contacto-item");
    chats.forEach(function (chat) {
        chat.classList.remove("active");
    });

    // Luego, activar el que corresponde
    var chatactivo = document.getElementById("chatactivo" + postulacionId);
    if (chatactivo) {
        chatactivo.classList.add("active");
    }
    // donde se imprimiran los chats y la informacion del usuario
    var seccionchat = document.getElementById("seccionchat");
    seccionchat.style.display = "";
    var mensajes = document.getElementById("mensajes");
    var infouser = document.getElementById("infouser");
    var frmenviomensaje = document.getElementById("frmenviomensaje");

    // envio de formulario adjuntando la id de usuario y la del trabajo
    var form = document.getElementById("chat" + postulacionId);
    var formData = new FormData(form);
    var trabajo_id = formData.get("trabajo_id");
    var trabajador_id = formData.get("trabajador_id");
    fetch("/cargamensajes/", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.json();
        })
        .then(function (data) {
            console.log(data);
            let contenido = "";
            mensajes.innerHTML = "";
            data.chats.forEach((mensaje) => {
                let fecha = mensaje.created_at;
                let partes = fecha.split("T")[1].split(":");
                let hora_minuto = `${partes[0]}:${partes[1]}`;
                if (mensaje.emisor.id == trabajador_id) {
                    contenido += '<div class="message">';
                    contenido += '  <div class="message-avatar">';
                    contenido += '   <img src="" alt="foto de ' + mensaje.emisor.nombre + '">';
                    contenido += "  </div>";
                    contenido += '  <div class="message-content">';
                    contenido += '      <div class="message-bubble">';
                    contenido += '          <p class="message-text">' + mensaje.contenido + "</p>";
                    contenido += "      </div>";
                    contenido += '      <div class="message-time">' + hora_minuto + " - " + mensaje.emisor.nombre + "</div>";
                    contenido += "      </div>";
                    contenido += "  </div>";
                    contenido += "</div>";
                } else {
                    contenido += '<div class="message outgoing">';
                    contenido += '  <div class="message-avatar">';
                    contenido += '   <img src="" alt="foto de ' + mensaje.emisor.nombre + '">';
                    contenido += "  </div>";
                    contenido += '  <div class="message-content">';
                    contenido += '      <div class="message-bubble">';
                    contenido += '          <p class="message-text">' + mensaje.contenido + "</p>";
                    contenido += "      </div>";
                    contenido += '          <div class="message-time">' + hora_minuto + " - " + mensaje.emisor.nombre + "</div>";
                    contenido += "      </div>";
                    contenido += "  </div>";
                    contenido += "</div>";
                }
                mensajes.innerHTML = contenido;
            });

            let contenidouser = "";
            infouser.innerHTML = "";
            data.user.forEach((dato) => {
                contenidouser += '<div class="chat-user">';
                contenidouser += '  <div class="chat-user-avatar">';
                contenidouser += '   <img src="' +dato.foto_perfil + '" alt="foto de ' + dato.nombre +'">';
                contenidouser += "  </div>";
                contenidouser += '  <div class="chat-user-info">';
                contenidouser += "     <h3>" + dato.nombre + " " + dato.apellidos + ' <span class="chat-indicator"></span></h3>';
                contenidouser += "     <p>" + dato.descripcion + "</p>";
                contenidouser += '     <div class="chat-context">Proyecto: ' + dato.descripcion + "</div>";
                contenidouser += "  </div>";
                contenidouser += "</div>";
                contenidouser += '<div class="chat-actions">';
                contenidouser += '<button class="chat-action-btn" title="Llamar">';
                contenidouser += '    <i class="fas fa-phone"></i>';
                contenidouser += "</button>";
                contenidouser += '<button class="chat-action-btn" title="Videollamada">';
                contenidouser += '    <i class="fas fa-video"></i>';
                contenidouser += "</button>";
                contenidouser += '<button class="chat-action-btn" title="Más opciones">';
                contenidouser += '    <i class="fas fa-ellipsis-v"></i>';
                contenidouser += "</button>";
                contenidouser += "</div>";
            });
            infouser.innerHTML = contenidouser;
            console.log(trabajador_id)

            let frmenvio = "";
            frmenviomensaje.innerHTML = "";
            frmenvio += '  <div class="chat-input-actions">';
            frmenvio += '  <input type="hidden" id="trabajo_idfrmmensaje" value="' + trabajo_id + '">';
            frmenvio += '  <input type="hidden" id="trabajador_idfrmmensaje" value="' + trabajador_id + '">';
            frmenvio += '  <button class="chat-input-btn" title="Adjuntar archivo">';
            frmenvio += '      <i class="fas fa-paperclip"></i>';
            frmenvio += "  </button>";
            frmenvio += '  <button class="chat-input-btn" title="Emoji">';
            frmenvio += '       <i class="far fa-smile"></i>';
            frmenvio += "  </button>";
            frmenvio += "  </div>";
            frmenvio += '  <div class="chat-input-field">';
            frmenvio += '        <input type="text" id="mensajeaenviar" placeholder="Escribe un mensaje...">';
            frmenvio += "  </div>";
            frmenvio += '  <button onclick="mandarmensaje()" class="send-btn">';
            frmenvio += '         <i class="fas fa-paper-plane"></i>';
            frmenvio += "  </button>";
            frmenviomensaje.innerHTML = frmenvio;
        });
}

function mandarmensaje() {
    var mensaje = document.getElementById("mensajeaenviar").value;
    var trabajo = document.getElementById("trabajo_idfrmmensaje").value;
    var trabajador = document.getElementById("trabajador_idfrmmensaje").value;
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var formData = new FormData();
    formData.append('trabajo', trabajo);
    formData.append('trabajador', trabajador);
    formData.append('mensaje', mensaje);
    formData.append('_token', token);

    fetch("/enviomensaje/", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.json();
        })
        .then(function (data) {
            console.log(data);
        });
}