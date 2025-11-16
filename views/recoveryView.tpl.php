@component(head)

<style>
    body {display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:var(--color-fondo-principal);color:var(--color-texto-principal);font-family:Arial,sans-serif;}
    .login-container {background:#fff;padding:2rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.08);max-width:400px;width:100%;border:1px solid rgba(0,0,0,.05);}
    .login-container h2 {margin-bottom:1rem;text-align:center;color:var(--color-texto-principal);}
    .form-group {margin-bottom:1rem;}
    label {display:block;margin-bottom:.35rem;font-weight:600;color:var(--color-texto-secundario);}
    input[type="email"] {width:100%;padding:.8rem;border:1px solid #ccc;border-radius:6px;font-size:1rem;background:#fafafa;}
    input[type="email"]:focus {outline:2px solid var(--color-acento-secundario);border-color:var(--color-acento-secundario);}
    input[type="submit"] {background:var(--color-acento-secundario);color:var(--color-boton-principal-texto);border:none;width:100%;padding:.8rem;font-size:1rem;border-radius:6px;cursor:pointer;font-weight:600;letter-spacing:.3px;transition:background .25s ease;}
    input[type="submit"]:hover {background:#35608d;}
    .error-message {color:var(--color-acento-alerta);text-align:center;margin-bottom:1rem;font-weight:600;min-height:1.2rem;}
    a {color:var(--color-acento-secundario);}
    a:hover {text-decoration:underline;}
    .links-secundarios {margin-top:1rem;text-align:center;font-size:.85rem;}
</style>

<body>
    <div class="login-container">
        <h2>Recuperar contraseña</h2>
        <div class="error-message">{{ ERROR_RECOVERY }}</div>
        <form action="?slug=recovery" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="rec_email">Email</label>
                <input type="email" id="rec_email" name="rec_email" placeholder="Tu correo" required>
            </div>
            <input type="submit" name="btn_recovery" value="Iniciar recuperación">
            <div class="links-secundarios">
                <div><a href="?slug=login">Volver a login</a></div>
                <div><a href="?slug=register">¿No tienes cuenta? Registrarse</a></div>
            </div>
        </form>
    </div>
</body>
</html>