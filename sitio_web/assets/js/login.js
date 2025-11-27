function togglePassword() {
    const passwordInput = document.getElementById("password");
    const checkbox = document.getElementById("mostrarPassword");
    if (checkbox.checked) {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}