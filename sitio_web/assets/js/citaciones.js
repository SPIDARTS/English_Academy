// Definición de constantes
const formCita = document.getElementById("formCita");
const tablaCitas = document.querySelector("#tablaCitas tbody");
const mensaje = document.getElementById("mensaje");

// Muestra un mensaje en pantalla (verde si todo bien, rojo si hay un error)
function showMsg(text, ok= true) {
    mensaje.textContent = text;
    mensaje.style.color = ok ? "green" : "red";
}

// Carga todas las citas desde el servidor y muestra la tabla
async function cargarCitas() {
    try {
        const res = await fetch("../api/citaciones_api.php?action=listar");
        const data = await res.json();

        tablaCitas.innerHTML = "";
        if (data.success) {
            const hoy = new Date().toISOString().split("T")[0];
            
            data.citas.forEach(cita => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${cita.fecha_cita}</td>
                    <td>${cita.hora_cita}</td>
                    <td>${cita.motivo}</td>
                    <td>${cita.estado}</td>
                    <td>
                        ${cita.fecha_cita >= hoy ? `
                            <button class="btn-editar"
                                data-id="${cita.idCita}"
                                data-fecha="${cita.fecha_cita}"
                                data-hora="${cita.hora_cita}"
                                data-motivo="${cita.motivo}">
                                Editar
                            </button>
                            <button class="btn-borrar"
                                data-id="${cita.idCita}">
                                Borrar
                            </button>
                        ` : "❌ No modificable"}
                    </td>
                `;
                tablaCitas.appendChild(row);
            });

            // Asignar los eventos a los botones
            document.querySelectorAll(".btn-editar").forEach(btn => {
                btn.addEventListener("click", e => {
                    const id = btn.dataset.id;
                    const fecha = btn.dataset.fecha;
                    const hora = btn.dataset.hora;
                    const motivo = btn.dataset.motivo;
                    editarCita(id, fecha, hora, motivo);
                });
            });

            document.querySelectorAll(".btn-borrar").forEach(btn => {
                btn.addEventListener("click", e => {
                    const id = btn.dataset.id;
                    borrarCita(id);
                });
            });
        }
    } catch (err) {
        showMsg(`Error cargando citas: ${err.message}`, false);
    }
}

// Cuando el usuario envía el formulario, crea una nueva cita
formCita.addEventListener("submit", async e => {
    e.preventDefault();
    const formData =new FormData(formCita);
    formData.append("action","nueva");

    try{
        const res = await fetch("../api/citaciones_api.php", { method: "POST", body: formData});
        const data = await res.json();

        showMsg(data.msg, data.success);
        if (data.success) {
            formCita.reset();
            cargarCitas();
        }
    } catch (err) {
        showMsg("Error guardando cita: " + err.message, false);
    }
});

// Permite editar una cita 
async function editarCita(id,fecha,hora,motivo) {
    const nuevaFecha = prompt("Nueva fecha (YYYY-MM-DD):", fecha);
    const nuevaHora = prompt("Nueva hora (HH:MM):", hora);
    const nuevoMotivo = prompt("Nuevo motivo:", motivo);

    if (nuevaFecha && nuevaHora && nuevoMotivo) {
        const formData = new FormData();
        formData.append("action", "editar");
        formData.append("idCita", id);
        formData.append("fecha_cita", nuevaFecha);
        formData.append("hora_cita", nuevaHora);
        formData.append("motivo", nuevoMotivo);
    

        try {
            const res = await fetch("../api/citaciones_api.php", {method: "POST", body: formData});
            const data = await res.json();

            showMsg(data.msg, data.success);
            cargarCitas();
        } catch (err) {
            showMsg("Error editando cita: " + err.message,false);
        }   
    }
}

// Permite borrar una cita
async function borrarCita(id) {
    if (confirm("¿Seguro que deseas borrar esta cita?")) {
        const formData = new FormData();
        formData.append("action", "borrar");
        formData.append("idCita", id);

        try {
            const res = await fetch("../api/citaciones_api.php", { method: "POST", body: formData });
            const data = await res.json();

            showMsg(data.msg, data.success);
            cargarCitas();
        } catch (err) {
            showMsg("Error borrando cita: " + err.message,false);
        }
    }
}

// Llama a la función para cargar citas al abrir la página
cargarCitas();