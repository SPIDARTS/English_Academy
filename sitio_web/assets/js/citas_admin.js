document.addEventListener("DOMContentLoaded", async () => {

    // Obtiene los elementos del HTML
    const selectUsuario = document.getElementById("selectUsuario");
    const tabla = document.querySelector("#table tbody");
    const form = document.getElementById("formCita");

    // Función para cargar los datos de una cita en el formulario 
    window.editarCita = (id, fecha, motivo) => {
        document.getElementById("idCita").value = id;
        document.getElementById("fecha_cita").value = fecha;
        document.getElementById("descripcion").value = motivo;
    };

    // Funcion para borrar una cita
    window.borrarCita = async (id) => {
        if (confirm("¿Seguro que quieres borrar esta cita?")){
            await fetch(`../api/citas_admin_api.php?action=borrar&idCita=${id}`);
            cargarCitas();
        }
    };

    // Cargar lista de usuarios en el selector
    const usuarios = await fetch("../api/citas_admin_api.php?action=listarUsuarios").then(r => r.json());
    
    usuarios.forEach((u, index) => {
        const opt = document.createElement("option");
        opt.value = u.idUser;
        opt.textContent = `${u.nombre} ${u.apellidos}`;
        selectUsuario.appendChild(opt);
    });
    
    if (usuarios.length > 0) cargarCitas();
    // Cuando se elige un usuario, carga sus citas
    selectUsuario.addEventListener("change", cargarCitas);

    // Cuando se envía el formulario, guarda o actualiza la cita
    form.addEventListener("submit", guardarCitas);

    // Función para cargar las citas del usuario seleccionado
    async function cargarCitas() {
        tabla.innerHTML =""; 
        const idUser = selectUsuario.value;
        const citas = await fetch(`../api/citas_admin_api.php?action=listarCitas&idUser=${idUser}`).then(r => r.json());
        
        // Recorre las citas y las muestra en la tabla
        citas.forEach(c => {
            const tr =document.createElement("tr");
            tr.innerHTML =`
                <td>${c.fecha_cita}</td>
                <td>${c.motivo}</td>
                <td>
                    <button onclick="editarCita(${c.idCita}, '${c.fecha_cita}', '${c.motivo}')">Editar</button>
                    <button onclick="borrarCita(${c.idCita})">Borrar</button>
                </td>`;
            tabla.appendChild(tr);
        });
    }

    // Función para crear a actualizar una cita
    async function guardarCitas(e) {
        e.preventDefault();
        const data = {
            idCita: document.getElementById("idCita").value,
            idUser: selectUsuario.value,
            fecha_cita: document.getElementById("fecha_cita").value,
            motivo: document.getElementById("descripcion").value
        };
        
        // Si existe idCita actualiza, si no crea
        const accion = data.idCita ? "actualizar" : "crear";
        try {
        const response = await fetch(`../api/citas_admin_api.php?action=${accion}`, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        });
        
        const text = await response.text();
        console.log("RESPUESTA CRUDA DEL SERVIDOR:", text);

        const result = JSON.parse(text);
        console.log("Respuesta parseada:", result);

        if (result.success) {
            alert("✅ Cita guardada correctamente");
            form.reset();
            cargarCitas();
        } else {
            alert("❌ Error al guardar la cita: " + (result.msg || "Desconocido"));
        }
    } catch (error) {
        console.error("Error en fetch:", error);
        alert("❌ Error al comunicarse con el servidor");
    }
    
  } 
});