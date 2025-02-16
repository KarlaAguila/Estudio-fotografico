document.addEventListener("DOMContentLoaded", function () {
    const authButton = document.getElementById("auth-button");
    const isLoggedIn = localStorage.getItem("loggedIn");

    if (isLoggedIn) {
        // Usuario autenticado: mostrar "Cerrar sesión"
        authButton.textContent = "CERRAR SESIÓN";
        authButton.classList.add("cerrar-sesion");
        authButton.onclick = function () {
            localStorage.removeItem("loggedIn");
            window.location.href = "../inicio/inicio.html"; // Redirige al inicio después de cerrar sesión
        };

        // Ocultar los botones de "Iniciar sesión" y "Registrarse" en la sección principal
        const heroButtons = document.querySelector(".hero .buttons");
        if (heroButtons) {
            heroButtons.style.display = "none";
        }
    } else {
        // Usuario no autenticado: mostrar "Iniciar sesión"
        authButton.textContent = "INICIAR SESIÓN";
        authButton.classList.add("iniciar-sesion");
        authButton.onclick = function () {
            window.location.href = "../login/login.html";
        };
    }
});
