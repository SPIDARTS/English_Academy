document.addEventListener("DOMContentLoaded", () => {
    cargarPerfil();
});

//Cargar datos del perfil desde el servidor
function cargarPerfil() {
    fetch("../api/perfil_get.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Obtiene los datos del perfil y genera el formulario
                let perfilDiv = document.getElementById("perfil");
                perfilDiv.innerHTML = `
                    <form id="perfilForm">
                        <label>Usuario:</label> 
                        <input type="text" value="${data.usuario}" disabled><br>
                        
                        <label>Nombre:</label> 
                        <input type="text" name="nombre" value="${data.nombre}"><br>
                        
                        <label>Apellidos:</label> 
                        <input type="text" name="apellidos" value="${data.apellidos}"><br>
                        
                        <label>Email:</label> 
                        <input type="text" name="email" value="${data.email}"><br>
                        
                        <label>Teléfono:</label> 
                        <input type="text" name="telefono" value="${data.telefono}"><br>
                        
                        <label>Fecha de nacimiento:</label> 
                        <input type="date" name="fecha_nacimiento" value="${data.fecha_nacimiento}"><br>
                        
                        <label>Dirección:</label> 
                        <input type="text" name="direccion" value="${data.direccion}"><br>
                        
                        <label>Sexo:</label> 
                        <select name="sexo">
                            <option value="M" ${data.sexo === "M" ? "selected" : ""}>Masculino</option>
                            <option value="F" ${data.sexo === "F" ? "selected" : ""}>Femenino</option>
                        </select><br>
                        
                        <label>Nueva Contraseña:</label> 
                        <input type="password" name="password"><br>
                        
                        <button type="submit">Actualizar</button>
                    </form>
                    `;
                    document.getElementById("perfilForm").addEventListener("submit", actualizarPerfil);
            }
        });
}
// Envía los cambios del perfil al servidor y muestra un mensaje
function actualizarPerfil(e) {
    e.preventDefault();

    let formData = new FormData(e.target);

    fetch("../api/perfil_update.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById("mensaje");
        if (data.success) {
            msg.style.color = "green";
            msg.textContent = "Perfil actualizado correctamente ✅";
            cargarPerfil();
        } else {
            msg.style.color = "red";
            msg.textContent = "Error" + data.error;
        }
    });
}