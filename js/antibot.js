/**
 * Sistema Anti-Bot Frontend
 * - Integración con Google reCAPTCHA v3
 * - Validaciones del lado del cliente
 */

// Configuración de reCAPTCHA v3
const RECAPTCHA_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'; // TEST KEY - Reemplazar

/**
 * Ejecuta reCAPTCHA v3 y envía el formulario
 * @param {HTMLFormElement} form Formulario a enviar
 * @param {string} action Acción específica (contact_form, register_form, etc)
 */
function enviarFormularioConRecaptcha(form, action) {
    // Prevenir envío por defecto
    event.preventDefault();
    
    // Verificar que el honeypot está vacío (campo trampa)
    const honeypot = form.querySelector('input[name="website"]');
    if (honeypot && honeypot.value !== '') {
        console.warn('Honeypot detectado');
        return false;
    }
    
    // Ejecutar reCAPTCHA
    grecaptcha.ready(function() {
        grecaptcha.execute(RECAPTCHA_SITE_KEY, {action: action}).then(function(token) {
            // Agregar el token al formulario
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recaptcha_token';
            input.value = token;
            form.appendChild(input);
            
            // Enviar el formulario
            form.submit();
        }).catch(function(error) {
            console.error('Error reCAPTCHA:', error);
            alert('Error de validación. Por favor, intenta de nuevo.');
        });
    });
    
    return false;
}

/**
 * Inicializa la protección anti-bot en un formulario
 * @param {string} formSelector Selector CSS del formulario
 * @param {string} action Acción de reCAPTCHA
 */
function inicializarAntiBot(formSelector, action) {
    const form = document.querySelector(formSelector);
    if (!form) return;
    
    // Interceptar el submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        enviarFormularioConRecaptcha(form, action);
    });
}

// Auto-inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Detectar qué página es y configurar automáticamente
    const path = window.location.pathname;
    
    if (path.includes('contact.php')) {
        inicializarAntiBot('form[method="post"]', 'contact_form');
    } else if (path.includes('registro.php')) {
        inicializarAntiBot('form[method="post"]', 'register_form');
    } else if (path.includes('registroAgencias.php')) {
        inicializarAntiBot('form[method="post"]', 'register_agency_form');
    } else if (path.includes('registroPrestadores.php')) {
        inicializarAntiBot('form[method="post"]', 'register_provider_form');
    } else if (path.includes('registroFreelancers.php')) {
        inicializarAntiBot('form[method="post"]', 'register_freelancer_form');
    } else if (path.includes('registroOperadores.php')) {
        inicializarAntiBot('form[method="post"]', 'register_operator_form');
    } else if (path.includes('recuperar_contrasena.php')) {
        inicializarAntiBot('form[method="post"]', 'reset_password_form');
    }
});
