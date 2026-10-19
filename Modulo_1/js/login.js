function mostrarContrasena() {
    const campo = document.getElementById("contrasena");
    const boton = document.querySelector(".mostrar");

    if (campo.type === "password") {
        campo.type = "text";
        boton.textContent = "Ocultar";
    } else {
        campo.type = "password";
        boton.textContent = "Mostrar";
    }
}