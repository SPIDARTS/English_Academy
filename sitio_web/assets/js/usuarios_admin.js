// Definición de constantes
const tabla = document.querySelector("#tablaUsuarios tbody");
const form = document.getElementById("formUsuario");
const msg = document.getElementById("mensaje");

// Evita que el navegador restaure los valores del formulario tras recargar o volver atrás
window.addEventListener("pageshow", (event) => {
  if (event.persisted || performance.getEntriesByType("navigation")[0].type === "reload") {
    form.reset();
  }
});

// Muestra un mensaje en pantalla (verde si todo bien, rojo si hay un error)
function showMsg(t, ok=true) {
    msg.textContent = t;
    msg.style.color = ok ? "green" : "red";
}

// Carga todos los usuarios desde la API y los muestra en la tabla
async function cargarUsuarios() {
    const res = await fetch("../api/usuarios_admin_api.php?action=listar");
    const data = await res.json();
    tabla.innerHTML = "";
    if (data.success) {
        data.usuarios.forEach(u => {
            const row = document.createElement("tr");
            row.innerHTML =`
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.usuario}</td>
                <td>${u.rol}</td>
                <td>
                    <button onclick='editar(${JSON.stringify(u)})'>Editar</button>
                    <button onclick='borrar(${u.idUSER})'>Borrar</button>
                </td>
            `;
            tabla.appendChild(row);
        });
    }
}

// Envía el formulario para crear o actualizar un usuario
form.addEventListener("submit", async e => {
    e.preventDefault();
    const fd = new FormData(form);
    fd.append("action", "guardar");

    const res = await fetch("../api/usuarios_admin_api.php", { method:"POST", body:fd });
    const data = await res.json();

    showMsg(data.msg, data.success);
    form.reset();
    cargarUsuarios();
});

// Rellena el formulario con los datos del usuario a editar
function editar(u) {
    form.idUser.value = u.idUSER;
    form.nombre.value = u.nombre;
    form.email.value = u.email;
    form.usuario.value = u.usuario;
    form.rol.value = u.rol;
}

// Elimina un usuario tras confirmación
async function borrar(id) {
    if (confirm("¿Eliminar este usuario")) {
        const fd = new FormData();
        fd.append("action", "borrar");
        fd.append("idUser", id);

        const res = await fetch("../api/usuarios_admin_api.php", { method:"POST", body:fd});
        const data = await res.json();

        showMsg(data.msg, data.success);
        cargarUsuarios();
    }
}

cargarUsuarios();

// Mostrar / ocultar contraseña
const toggle = document.getElementById("mostrarPassword");
const passInput = document.getElementById("password");

if (toggle && passInput) {
  toggle.addEventListener("change", () => {
    passInput.type = toggle.checked ? "text" : "password";
  });
}

// Limpiar el formulario completamente al cargar 
window.addEventListener("pageshow", (event) => {
  if (event.persisted) form.reset();
});

window.addEventListener("load", () => {
  setTimeout(() => form.reset(), 200);
});