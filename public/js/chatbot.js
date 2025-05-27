// TASKLY Chatbot - Sistema de asistencia simple
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el chatbot
    initChatbot();
});

function initChatbot() {
    // Crear la estructura del chatbot
    createChatbotUI();
    
    // Añadir los event listeners
    const chatbotButton = document.querySelector('.chatbot-button');
    const chatbotWidget = document.querySelector('.chatbot-widget');
    const closeButton = document.querySelector('.chatbot-close');
    const sendButton = document.querySelector('.chatbot-send');
    const inputField = document.querySelector('.chatbot-input');
    
    // Manejar la apertura/cierre del chatbot
    chatbotButton.addEventListener('click', function() {
        chatbotWidget.classList.toggle('active');
        chatbotButton.classList.remove('pulse');
        
        // Si se abre por primera vez, mostrar mensaje de bienvenida
        if (chatbotWidget.classList.contains('active') && document.querySelectorAll('.chatbot-messages .message').length === 0) {
            setTimeout(function() {
                addBotMessage("¡Hola! Soy el asistente de TASKLY. ¿En qué puedo ayudarte hoy?");
                showSuggestions([
                    "Tengo un problema con un pago",
                    "Cómo postularme a un trabajo",
                    "No puedo cambiar mis datos"
                ]);
            }, 500);
        }
    });
    
    closeButton.addEventListener('click', function() {
        chatbotWidget.classList.remove('active');
    });
    
    // Enviar mensaje al presionar Enter
    inputField.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleUserMessage();
        }
    });
    
    // Enviar mensaje al hacer clic en el botón
    sendButton.addEventListener('click', handleUserMessage);
    
    // Mostrar un recordatorio después de unos segundos si el usuario no ha interactuado
    setTimeout(function() {
        if (!chatbotWidget.classList.contains('active')) {
            chatbotButton.classList.add('pulse');
            
            // Añadir notificación
            if (!document.querySelector('.chatbot-notification')) {
                const notification = document.createElement('div');
                notification.className = 'chatbot-notification';
                notification.textContent = '1';
                chatbotButton.appendChild(notification);
            }
        }
    }, 15000); // 15 segundos
}

function createChatbotUI() {
    // Crear contenedor principal
    const chatbotContainer = document.createElement('div');
    chatbotContainer.className = 'chatbot-container';
    
    // HTML del chatbot
    chatbotContainer.innerHTML = `
        <button class="chatbot-button">
            <i class="fas fa-comment-dots"></i>
        </button>
        
        <div class="chatbot-widget">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <div class="chatbot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="chatbot-name">Asistente TASKLY</div>
                </div>
                <button class="chatbot-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="chatbot-messages"></div>
            
            <div class="chatbot-input-area">
                <input type="text" class="chatbot-input" placeholder="Escribe tu pregunta aquí...">
                <button class="chatbot-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    `;
    
    // Añadir a la página
    document.body.appendChild(chatbotContainer);
}

function handleUserMessage() {
    const inputField = document.querySelector('.chatbot-input');
    const userMessage = inputField.value.trim();
    
    if (userMessage === '') return;
    
    // Añadir el mensaje del usuario a la conversación
    addUserMessage(userMessage);
    
    // Limpiar el campo de entrada
    inputField.value = '';
    
    // Simular respuesta del bot (con un pequeño retraso para parecer más natural)
    setTimeout(function() {
        const botResponse = getBotResponse(userMessage);
        addBotMessage(botResponse.message);
        
        // Si hay sugerencias, mostrarlas
        if (botResponse.suggestions && botResponse.suggestions.length > 0) {
            showSuggestions(botResponse.suggestions);
        }
    }, 700);
}

function addUserMessage(message) {
    const messagesContainer = document.querySelector('.chatbot-messages');
    const time = getCurrentTime();
    
    const messageElement = document.createElement('div');
    messageElement.className = 'message user';
    messageElement.innerHTML = `
        <div class="message-bubble">${message}</div>
        <div class="message-time">${time}</div>
    `;
    
    messagesContainer.appendChild(messageElement);
    scrollToBottom();
}

function addBotMessage(message) {
    const messagesContainer = document.querySelector('.chatbot-messages');
    const time = getCurrentTime();
    
    const messageElement = document.createElement('div');
    messageElement.className = 'message bot';
    messageElement.innerHTML = `
        <div class="message-bubble">${message}</div>
        <div class="message-time">${time}</div>
    `;
    
    messagesContainer.appendChild(messageElement);
    scrollToBottom();
}

function showSuggestions(suggestions) {
    const messagesContainer = document.querySelector('.chatbot-messages');
    
    // Eliminar sugerencias anteriores si existen
    const existingSuggestions = document.querySelector('.chatbot-suggestions');
    if (existingSuggestions) {
        existingSuggestions.remove();
    }
    
    // Crear contenedor de sugerencias
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'chatbot-suggestions';
    
    // Añadir cada sugerencia
    suggestions.forEach(suggestion => {
        const chip = document.createElement('div');
        chip.className = 'suggestion-chip';
        chip.textContent = suggestion;
        
        // Al hacer clic en una sugerencia, usarla como mensaje del usuario
        chip.addEventListener('click', function() {
            const inputField = document.querySelector('.chatbot-input');
            inputField.value = suggestion;
            handleUserMessage();
        });
        
        suggestionsContainer.appendChild(chip);
    });
    
    messagesContainer.appendChild(suggestionsContainer);
    scrollToBottom();
}

function getCurrentTime() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    
    // Añadir cero si es necesario
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    
    return `${hours}:${minutes}`;
}

function scrollToBottom() {
    const messagesContainer = document.querySelector('.chatbot-messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function getBotResponse(userMessage) {
    // Convertir a minúsculas para facilitar la coincidencia
    const message = userMessage.toLowerCase();
    
    // Respuestas predefinidas basadas en palabras clave
    
    // Problemas con pagos
    if (message.includes('pago') || message.includes('cobrar') || message.includes('dinero') || 
        message.includes('stripe') || message.includes('transferencia')) {
        return {
            message: "Para resolver problemas de pago, puedes seguir estos pasos:<br><br>1. Verifica que tus datos bancarios estén correctamente introducidos en tu perfil.<br>2. Confirma que el trabajo ha sido marcado como completado.<br>3. Los pagos suelen procesarse en 1-3 días hábiles.<br><br>Si persiste el problema, contacta con soporte@taskly.com",
            suggestions: ["Actualizar datos bancarios", "No recibí mi pago", "Comisiones de TASKLY"]
        };
    }
    
    // Postulaciones a trabajos
    if (message.includes('postul') || message.includes('aplicar') || message.includes('trabajo') || 
        message.includes('empleo') || message.includes('candidato') || message.includes('oferta')) {
        return {
            message: "Para postularte a un trabajo en TASKLY:<br><br>1. Navega a la página principal y usa los filtros para encontrar trabajos.<br>2. Haz clic en 'Ver detalles' en el trabajo que te interese.<br>3. Pulsa el botón 'Postularme' y serás añadido a la lista de candidatos.<br><br>Recuerda completar tu perfil para aumentar tus posibilidades.",
            suggestions: ["Mejorar mi perfil", "No puedo postularme", "Ver mis postulaciones"]
        };
    }
    
    // Problemas con datos o perfil
    if (message.includes('datos') || message.includes('perfil') || message.includes('cuenta') || 
        message.includes('cambiar') || message.includes('actualizar') || message.includes('modificar')) {
        return {
            message: "Para actualizar tu información de perfil:<br><br>1. Haz clic en tu avatar en la esquina superior derecha.<br>2. Selecciona 'Mi Perfil'.<br>3. Haz clic en 'Editar Perfil' para modificar tus datos.<br><br>Si tienes problemas específicos, por favor detalla qué información no puedes actualizar.",
            suggestions: ["Cambiar contraseña", "Actualizar foto", "Datos bancarios"]
        };
    }
    
    // Valoraciones
    if (message.includes('valoración') || message.includes('calificación') || message.includes('opinión') || 
        message.includes('estrella') || message.includes('review') || message.includes('comentario')) {
        return {
            message: "Las valoraciones son importantes en TASKLY:<br><br>1. Solo puedes valorar trabajos completados y pagados.<br>2. La valoración debe ser justa y basada en el servicio recibido.<br>3. Las valoraciones afectan directamente la visibilidad de los perfiles.<br><br>¿Necesitas más información sobre algún aspecto específico?",
            suggestions: ["Mejorar mis valoraciones", "Reportar valoración injusta", "Ver mis valoraciones"]
        };
    }
    
    // Categorías y trabajos
    if (message.includes('categoría') || message.includes('tipo') || message.includes('filtro') || 
        message.includes('buscar') || message.includes('encontrar')) {
        return {
            message: "TASKLY ofrece múltiples categorías de trabajos:<br><br>1. Usa los filtros en la página principal para refinar tu búsqueda.<br>2. Puedes filtrar por categoría, precio, ubicación y más.<br>3. También puedes usar la barra de búsqueda para encontrar trabajos específicos.<br><br>¿Qué tipo de trabajo estás buscando?",
            suggestions: ["Ver todas las categorías", "Trabajos cerca de mí", "Trabajos mejor pagados"]
        };
    }
    
    // Comisiones
    if (message.includes('comisión') || message.includes('porcentaje') || message.includes('tarifa') || 
        message.includes('cobro') || message.includes('fee')) {
        return {
            message: "TASKLY cobra una comisión del 10% por cada trabajo completado:<br><br>1. Esta comisión se deduce automáticamente del pago.<br>2. El trabajador recibe el 90% del importe acordado.<br>3. Las comisiones nos permiten mantener la plataforma y ofrecer soporte.<br><br>¿Tienes alguna otra pregunta sobre los pagos?",
            suggestions: ["Desglose de pagos", "Facturas", "Impuestos"]
        };
    }
    
    // Problemas técnicos
    if (message.includes('error') || message.includes('fallo') || message.includes('problema') || 
        message.includes('bug') || message.includes('no funciona') || message.includes('carga')) {
        return {
            message: "Lamento que estés experimentando problemas técnicos. Algunas soluciones comunes:<br><br>1. Limpia la caché de tu navegador.<br>2. Intenta acceder desde otro navegador.<br>3. Verifica tu conexión a internet.<br><br>Si el problema persiste, por favor describe el error específico y en qué parte de TASKLY ocurre.",
            suggestions: ["Página no carga", "Error al enviar mensajes", "Problemas con el registro"]
        };
    }
    
    // Saludo o respuesta genérica
    if (message.includes('hola') || message.includes('buenos días') || message.includes('buenas tardes') || 
        message.includes('hey') || message.includes('saludos')) {
        return {
            message: "¡Hola! Soy el asistente virtual de TASKLY. ¿En qué puedo ayudarte hoy?",
            suggestions: ["Cómo funciona TASKLY", "Problemas comunes", "Contáctanos"]
        };
    }
    
    // Despedida
    if (message.includes('adiós') || message.includes('chao') || message.includes('hasta luego') || 
        message.includes('gracias') || message.includes('bye')) {
        return {
            message: "¡Gracias por usar el asistente de TASKLY! Si necesitas más ayuda, estaré aquí. ¡Que tengas un excelente día!",
            suggestions: ["Volver al inicio", "Contactar soporte humano", "Dar feedback"]
        };
    }
    
    // Respuesta por defecto si no hay coincidencias
    return {
        message: "No estoy seguro de entender tu consulta. ¿Podrías reformularla o elegir una de estas opciones comunes?",
        suggestions: [
            "Problemas de pago",
            "Cómo postularme a trabajos",
            "Actualizar mi perfil",
            "Contactar soporte"
        ]
    };
}
