// Validación de imágenes para el formulario de animales
function validarImagen(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        // Mostrar feedback visual
        const feedback = document.createElement('div');
        feedback.className = 'form-text';
        
        // Validar tipo de archivo
        if (!allowedTypes.includes(file.type)) {
            feedback.innerHTML = '<span class="text-danger">❌ Error: Solo se permiten archivos de imagen (JPG, PNG, GIF, WebP).</span>';
            input.parentNode.appendChild(feedback);
            input.value = '';
            
            // Remover feedback después de 5 segundos
            setTimeout(() => feedback.remove(), 5000);
            return false;
        }
        
        // Validar tamaño
        if (file.size > maxSize) {
            feedback.innerHTML = '<span class="text-danger">❌ Error: La imagen es demasiado grande. Máximo 5MB permitido.</span>';
            input.parentNode.appendChild(feedback);
            input.value = '';
            
            // Remover feedback después de 5 segundos
            setTimeout(() => feedback.remove(), 5000);
            return false;
        }
        
        // Feedback positivo
        feedback.innerHTML = '<span class="text-success">✅ Imagen válida</span>';
        input.parentNode.appendChild(feedback);
        
        // Remover feedback después de 3 segundos
        setTimeout(() => feedback.remove(), 3000);
    }
    return true;
}

// Validación del formulario completo
function validarFormularioAnimal(e) {
    const fotoInputs = document.querySelectorAll('input[type="file"]');
    let hasInvalidFile = false;
    let fotoPrincipalVacia = true;
    
    // Verificar foto principal
    if (fotoInputs[0].files && fotoInputs[0].files[0]) {
        fotoPrincipalVacia = false;
        if (!validarImagen(fotoInputs[0])) {
            hasInvalidFile = true;
        }
    }
    
    // Verificar si la foto principal está vacía
    if (fotoPrincipalVacia) {
        e.preventDefault();
        alert('Error: La foto principal es obligatoria.');
        return false;
    }
    
    // Verificar fotos adicionales
    for (let i = 1; i < fotoInputs.length; i++) {
        if (fotoInputs[i].files && fotoInputs[i].files[0]) {
            if (!validarImagen(fotoInputs[i])) {
                hasInvalidFile = true;
            }
        }
    }
    
    if (hasInvalidFile) {
        e.preventDefault();
        alert('Por favor, corrige los errores en las imágenes antes de enviar el formulario.');
        return false;
    }
    
    return true;
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', validarFormularioAnimal);
    }
    
    // Agregar contador de archivos seleccionados
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            actualizarContadorFotos();
        });
    });
    
    actualizarContadorFotos();
});

// Función para mostrar cuántas fotos se han seleccionado
function actualizarContadorFotos() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    let fotosSeleccionadas = 0;
    
    fileInputs.forEach(input => {
        if (input.files && input.files[0]) {
            fotosSeleccionadas++;
        }
    });
    
    // Buscar o crear el contador
    let contador = document.getElementById('contador-fotos');
    if (!contador) {
        contador = document.createElement('div');
        contador.id = 'contador-fotos';
        contador.className = 'form-text text-info mt-2';
        document.querySelector('input[name="foto_1"]').parentNode.parentNode.appendChild(contador);
    }
    
    contador.textContent = `${fotosSeleccionadas}/4 fotos seleccionadas`;
    
    // Cambiar color según la cantidad
    if (fotosSeleccionadas === 0) {
        contador.className = 'form-text text-danger mt-2';
    } else if (fotosSeleccionadas === 4) {
        contador.className = 'form-text text-warning mt-2';
    } else {
        contador.className = 'form-text text-info mt-2';
    }
}