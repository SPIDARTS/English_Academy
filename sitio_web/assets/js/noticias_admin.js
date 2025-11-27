// Archivo que maneja las peticiones de la API
const API_URL = "../api/noticias_api.php";
const RUTA_IMAGENES = "../assets/images/"; // Ruta donde se guardan las imágenes

// Espera a que cargue la página completa
document.addEventListener("DOMContentLoaded", () => {
    cargarNoticias();
    document.getElementById("form-noticia").addEventListener("submit", guardarNoticia);
    document.getElementById("imagen").addEventListener("change", mostrarVistaPrevia);
});

// Función para obtener y mostrar todas las noticias
async function cargarNoticias() {
    const res = await fetch(API_URL + "?accion=listar");
    const data = await res.json();
    const tbody = document.getElementById("tabla-noticias");
    
    // Si la tabla-noticias no existe (posible error HTML), este if evita el fallo
    if (!tbody) { 
        console.error("Error: Elemento tbody con ID 'tabla-noticias' no encontrado.");
        return;
    }
    
    tbody.innerHTML = "";

    // Recorre las noticias y crea filas en la tabla
    data.forEach(n => {
        const tituloEsc = n.titulo.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        const textoEsc = n.texto.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        // Definir y escapar la imagen aquí, para poder pasarla a la función editar
        const imagenEsc = n.imagen ? n.imagen.replace(/'/g, "\\'") : ''; 

        tbody.innerHTML += `
            <tr>
                <td>${tituloEsc}</td>
                <td>${n.autor}</td>
                <td>${n.fecha}</td>
                <td>
                    <button onclick="editar('${n.idNoticia}', '${tituloEsc}', '${textoEsc}', '${n.fecha}', '${imagenEsc}')">Editar</button> 
                    <button onclick="borrar('${n.idNoticia}')">Borrar</button>
                </td>
            </tr>
        `;
    });
}

// Rellena el formulario con los datos de la noticia seleccionada
// ¡Añadimos el parámetro 'imagen'!
function editar(id, titulo, texto, fecha, imagen) { 
    document.getElementById("idNoticia").value = id;
    document.getElementById("titulo").value = titulo;
    document.getElementById("texto").value = texto;
    document.getElementById("fecha").value = fecha;
    
    // Guardamos el nombre de la imagen actual en el campo oculto
    document.getElementById("imagen-actual").value = imagen;
    document.getElementById("imagen").value = null; // Limpiar el campo file

    // Muestra la imagen actual si existe (este bloque DEBE ir dentro de la función editar)
    const vistaPrevia = document.getElementById("vista-previa-imagen");
    vistaPrevia.innerHTML = ''; // Limpiamos la vista previa
    if (imagen) {
        vistaPrevia.innerHTML = `<p>Imagen actual:</p><img src="${RUTA_IMAGENES}${imagen}" style="max-width: 150px; height: auto;">`;
    }
}

// Muestra una vista previa de la imagen seleccionada
function mostrarVistaPrevia() {
    const input = document.getElementById('imagen');
    const vistaPrevia = document.getElementById('vista-previa-imagen');
    vistaPrevia.innerHTML = ''; 

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            vistaPrevia.innerHTML = `<p>Nueva imagen seleccionada:</p><img src="${e.target.result}" style="max-width: 150px; height: auto;">`;
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        // Si se borra la selección, volvemos a mostrar la imagen actual (si la hay)
        const imagenActual = document.getElementById("imagen-actual").value;
        if (imagenActual) {
            vistaPrevia.innerHTML = `<p>Imagen actual:</p><img src="${RUTA_IMAGENES}${imagenActual}" style="max-width: 150px; height: auto;">`;
        }
    }
}

// Guardar una noticia (crear o editar)
async function guardarNoticia(e) {
    e.preventDefault();

    const id = document.getElementById("idNoticia").value;
    const form = document.getElementById("form-noticia");

    // Usamos FormData para enviar datos y el archivo (¡CRUCIAL para subir archivos!)
    const formData = new FormData(form);
    formData.append("accion", id ? "editar" : "crear");
    formData.append("idNoticia", id || null);
    // Añadimos el nombre de la imagen actual (si estamos editando) para que PHP sepa qué borrar/mantener
    formData.append("imagen_actual", document.getElementById("imagen-actual").value); 

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            // Ya no usamos 'Content-Type': 'application/json'
            body: formData 
        });

        // Revisamos si la respuesta HTTP no fue 200-299
        if (!res.ok) {
            console.error("Error HTTP:", res.status, res.statusText);
            const text = await res.text();
            console.error("Contenido de la respuesta:", text);
            alert("Ocurrió un error al guardar la noticia. Revisa la consola.");
            return;
        }

        const result = await res.json();
        
        if (result.error) {
            console.error("Error de API:", result.error);
            alert("Ocurrió un error: " + result.error);
        } else {
            alert(result.mensaje);
        }

        limpiarFormulario();
        cargarNoticias();
    } catch (err) {
        console.error("Error de fetch o JSON:", err);
        alert("Ocurrió un error inesperado. Revisa la consola.");
    }
}


// Elimina una noticia seleccionada
async function borrar(id) {
    if (!confirm("¿Seguro que deseas borrar esta noticia?")) return;

    // Usamos FormData para enviar la acción de borrado, necesario cuando el API recibe FormData
    const formData = new FormData();
    formData.append("accion", "borrar");
    formData.append("idNoticia", id);

    const res = await fetch(API_URL, {
        method: "POST",
        // No necesitamos headers si enviamos FormData
        body: formData 
    });

    const result = await res.json();
    alert(result.mensaje);
    cargarNoticias();
}

// Limpia el formulario y borra el ID oculto
function limpiarFormulario() {
    document.getElementById("form-noticia").reset();
    document.getElementById("idNoticia").value = "";
    document.getElementById("imagen-actual").value = "";
    document.getElementById("vista-previa-imagen").innerHTML = "";
}
