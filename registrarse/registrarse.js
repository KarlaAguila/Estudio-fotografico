document.querySelector('form').addEventListener('submit', function(e) {
    var password = document.getElementById('password').value;
    var repeatPassword = document.getElementById('repeat-password').value;
    
    if (password !== repeatPassword) {
        alert("Las contraseñas no coinciden.");
        e.preventDefault();  // Evita el envío del formulario
    }
});
