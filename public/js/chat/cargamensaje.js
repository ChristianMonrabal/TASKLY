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
    seccionchat.style.display = "flex";
    var frmenviomensaje = document.getElementById("frmenviomensaje");
    
    // Para móvil: oculta la lista de contactos y muestra el chat
    if (window.innerWidth <= 768) {
        // Ocultar la lista de contactos
        const contactsColumn = document.getElementById('contactosColumn');
        if (contactsColumn) {
            contactsColumn.classList.add('hidden-mobile');
            contactsColumn.style.display = 'none';
        }
        
        // Mostrar el chat
        seccionchat.classList.add('visible-mobile');
        
        // Mostrar el botón de retorno
        const backButton = document.getElementById('backToContacts');
        if (backButton) {
            backButton.style.display = 'flex';
        }
    }

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
            cargachat(postulacionId)
            infotrabajo = document.getElementById("infotrabajo" + postulacionId).textContent;
            let contenidouser = "";
            infouser.innerHTML = "";
            // El botón de retorno está en el HTML y se muestra/oculta según sea necesario
            
            data.user.forEach((dato) => {
                // Estructura limpia del header del chat
                contenidouser += '<div class="chat-header-content">';
                
                // Información del usuario
                contenidouser += '  <div class="chat-user">';
                contenidouser += '    <div class="chat-user-avatar">';
                contenidouser += '      <img src="/img/profile_images/' + dato.foto_perfil + '" alt="foto de ' + dato.nombre + '">';
                contenidouser += '    </div>';
                contenidouser += '    <div class="chat-user-info">';
                contenidouser += '      <h3>' + dato.nombre + ' ' + dato.apellidos + ' <span class="chat-indicator"></span></h3>';
                contenidouser += '      <p class="user-description">' + dato.descripcion + '</p>';
                contenidouser += '      <div class="chat-context"><span class="project-label">Proyecto:</span> <span class="project-name">' + infotrabajo + '</span></div>';
                contenidouser += '    </div>';
                contenidouser += '  </div>';
                
                // Botones de acción
                contenidouser += '  <div class="chat-actions">';
                contenidouser += '    <button class="chat-action-btn" title="Llamar">';
                contenidouser += '      <i class="fas fa-phone"></i>';
                contenidouser += '    </button>';
                contenidouser += '    <button class="chat-action-btn" title="Videollamada">';
                contenidouser += '      <i class="fas fa-video"></i>';
                contenidouser += '    </button>';
                contenidouser += '    <button class="chat-action-btn" title="Más opciones">';
                contenidouser += '      <i class="fas fa-ellipsis-v"></i>';
                contenidouser += '    </button>';
                contenidouser += '  </div>';
                
                contenidouser += '</div>';
            });
            infouser.innerHTML = contenidouser;
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
            frmenvio += '  <textarea class="message-input" id="mensajeaenviar" placeholder="Escribe un mensaje..."></textarea>';
            frmenvio += '  <button onclick="mandarmensaje(' + postulacionId + ')" class="send-btn">';
            frmenvio += '         <i class="fas fa-paper-plane"></i>';
            frmenvio += "  </button>";
            frmenviomensaje.innerHTML = frmenvio;
        });
}


function cargachat(postulacionId) {
    // donde se imprimiran los chats y la informacion del usuario
    var mensajes = document.getElementById("mensajes");

    // envio de formulario adjuntando la id de usuario y la del trabajo
    var form = document.getElementById("chat" + postulacionId);
    var formData = new FormData(form);
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
            let contenido = "";
            mensajes.innerHTML = "";
            data.chats.forEach((mensaje) => {
                let fecha = mensaje.created_at;
                let partes = fecha.split("T")[1].split(":");
                let hora_minuto = `${partes[0]}:${partes[1]}`;
                if (mensaje.emisor.id == trabajador_id) {
                    contenido += '<div class="message">';
                    contenido += '  <div class="message-avatar">';
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
                    // contenido += '   <img src="/img/profile_images/' + mensaje.emisor.foto_perfil + '" alt="foto de ' + mensaje.emisor.nombre + '">';
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
        });
}

function mandarmensaje(postulacionId) {
    var mensaje = document.getElementById("mensajeaenviar");
    var trabajo = document.getElementById("trabajo_idfrmmensaje").value;
    var trabajador = document.getElementById("trabajador_idfrmmensaje").value;
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var formData = new FormData();
    formData.append('trabajo', trabajo);
    formData.append('trabajador', trabajador);
    formData.append('mensaje', mensaje.value);
    formData.append('_token', token);

    fetch("/enviomensaje/", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.text();
        })
        .then(function (data) {
            mensaje.value = "";
            cargachat(postulacionId)
        });
}

// Función para cargar y mostrar los contadores de mensajes no leídos
function cargarMensajesNoLeidos() {
    fetch('/mensajes-no-leidos', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Ocultar todas las insignias primero
        const badges = document.querySelectorAll('.contacto-badge');
        badges.forEach(badge => {
            badge.style.display = 'none';
        });
        
        // Remover la clase 'contacto-unread' de todos los contactos
        const contactos = document.querySelectorAll('.contacto-item');
        contactos.forEach(contacto => {
            contacto.classList.remove('contacto-unread');
        });
        
        // Mostrar las insignias con el contador correcto
        for (const [key, count] of Object.entries(data)) {
            const [trabajo_id, emisor_id] = key.split('_');
            
            // Buscar el contacto que coincida con esta conversación
            const contactos = document.querySelectorAll('.contacto-item');
            contactos.forEach(contacto => {
                const form = contacto.querySelector('form');
                if (form) {
                    const formTrabajo = form.querySelector('input[name="trabajo_id"]');
                    const formTrabajador = form.querySelector('input[name="trabajador_id"]');
                    
                    if (formTrabajo && formTrabajador) {
                        // Si coincide la conversación, mostrar la insignia
                        if (formTrabajo.value === trabajo_id && formTrabajador.value === emisor_id) {
                            const badge = contacto.querySelector('.contacto-badge');
                            if (badge) {
                                badge.textContent = count;
                                badge.style.display = 'flex';
                                
                                // Marcar el contacto como no leído
                                contacto.classList.add('contacto-unread');
                            }
                        }
                    }
                }
            });
        }
    })
    .catch(error => console.error('Error al cargar mensajes no leídos:', error));
}

// Variable global para guardar el ID de la postulación activa
let chatActivo = null;

// Modificar la función cargamensaje para guardar el ID
function cargamensaje(postulacionId) {
    // Guardar ID del chat activo
    chatActivo = postulacionId;
    
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
    seccionchat.style.display = "flex";
    var frmenviomensaje = document.getElementById("frmenviomensaje");
    
    // Para móvil: oculta la lista de contactos y muestra el chat
    if (window.innerWidth <= 768) {
        // Ocultar la lista de contactos
        const contactsColumn = document.getElementById('contactosColumn');
        if (contactsColumn) {
            contactsColumn.classList.add('hidden-mobile');
            contactsColumn.style.display = 'none';
        }
        
        // Mostrar el chat
        seccionchat.classList.add('visible-mobile');
        
        // Mostrar el botón de retorno
        const backButton = document.getElementById('backToContacts');
        if (backButton) {
            backButton.style.display = 'flex';
        }
    }

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
            cargachat(postulacionId)
            infotrabajo = document.getElementById("infotrabajo" + postulacionId).textContent;
            let contenidouser = "";
            infouser.innerHTML = "";
            // El botón de retorno está en el HTML y se muestra/oculta según sea necesario
            
            data.user.forEach((dato) => {
                // Estructura limpia del header del chat
                contenidouser += '<div class="chat-header-content">';
                
                // Información del usuario
                contenidouser += '  <div class="chat-user">';
                contenidouser += '    <div class="chat-user-avatar">';
                contenidouser += '      <img src="/img/profile_images/' + dato.foto_perfil + '" alt="foto de ' + dato.nombre + '">';
                contenidouser += '    </div>';
                contenidouser += '    <div class="chat-user-info">';
                contenidouser += '      <h3>' + dato.nombre + ' ' + dato.apellidos + ' <span class="chat-indicator"></span></h3>';
                contenidouser += '      <p class="user-description">' + dato.descripcion + '</p>';
                contenidouser += '      <div class="chat-context"><span class="project-label">Proyecto:</span> <span class="project-name">' + infotrabajo + '</span></div>';
                contenidouser += '    </div>';
                contenidouser += '  </div>';
                
                // Botones de acción
                contenidouser += '  <div class="chat-actions">';
                contenidouser += '    <button class="chat-action-btn" title="Llamar">';
                contenidouser += '      <i class="fas fa-phone"></i>';
                contenidouser += '    </button>';
                contenidouser += '    <button class="chat-action-btn" title="Videollamada">';
                contenidouser += '      <i class="fas fa-video"></i>';
                contenidouser += '    </button>';
                contenidouser += '    <button class="chat-action-btn" title="Más opciones">';
                contenidouser += '      <i class="fas fa-ellipsis-v"></i>';
                contenidouser += '    </button>';
                contenidouser += '  </div>';
                
                contenidouser += '</div>';
            });
            infouser.innerHTML = contenidouser;
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
            frmenvio += '  <textarea class="message-input" id="mensajeaenviar" placeholder="Escribe un mensaje..."></textarea>';
            frmenvio += '  <button onclick="mandarmensaje(' + postulacionId + ')" class="send-btn">';
            frmenvio += '         <i class="fas fa-paper-plane"></i>';
            frmenvio += "  </button>";
            frmenviomensaje.innerHTML = frmenvio;
        });
}

// Inicializar la funcionalidad del botón de volver (mobile)
document.addEventListener('DOMContentLoaded', function() {
    // Cargar mensajes no leídos al iniciar
    cargarMensajesNoLeidos();
    
    // Configurar intervalo para actualizar los contadores de mensajes no leídos cada 10 segundos
    setInterval(cargarMensajesNoLeidos, 15000);
    
    // Interval simple para refrescar el chat activo cada 3 segundos
    setInterval(function() {
        // Si hay un chat activo, actualizar sus mensajes
        if (chatActivo !== null) {
            cargachat(chatActivo);
        }
    }, 4000);
    
    const backButton = document.getElementById('backToContacts');
    
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botón de retorno presionado');
            
            // Ocultar la sección de chat y mostrar la lista de contactos en móvil
            const chatSection = document.getElementById('seccionchat');
            const contactsColumn = document.getElementById('contactosColumn');
            
            if (chatSection) {
                chatSection.classList.remove('visible-mobile');
                chatSection.style.display = 'none';
            }
            
            if (contactsColumn) {
                contactsColumn.classList.remove('hidden-mobile');
                contactsColumn.style.display = 'flex';
            }
            
            // Ocultar el botón de retorno
            backButton.style.display = 'none';
        });
    }
    
    // Manejar cambio de tamaño de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            // Resetear clases para pantallas grandes
            const chatSection = document.getElementById('seccionchat');
            const contactsColumn = document.getElementById('contactosColumn');
            const backButton = document.getElementById('backToContacts');
            
            if (chatSection) chatSection.classList.remove('visible-mobile');
            if (contactsColumn) contactsColumn.classList.remove('hidden-mobile');
            if (backButton) backButton.style.display = 'none';
        }
    });
});