@component(head)

<style>
    body {
        display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;
        background: var(--color-fondo-principal);
        color: var(--color-texto-principal);
        font-family: Arial, sans-serif;
    }
    .login-container {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        max-width: 400px; width:100%;
        border:1px solid rgba(0,0,0,0.05);
    }
    .login-container h2 {
        margin-bottom: 1rem; text-align:center; color: var(--color-texto-principal);
    }
    .form-group { margin-bottom:1rem; }
    label { display:block; margin-bottom:.35rem; font-weight:600; color: var(--color-texto-secundario); }
    input[type="email"], input[type="password"] {
        width:100%; padding:.8rem; border:1px solid #ccc; border-radius:6px; font-size:1rem; background:#fafafa;
    }
    input[type="email"]:focus, input[type="password"]:focus { outline:2px solid var(--color-acento-secundario); border-color: var(--color-acento-secundario); }
    input[type="submit"] {
        background: var(--color-acento-alerta); color: var(--color-boton-principal-texto);
        border:none; width:100%; padding:.8rem; font-size:1rem; border-radius:6px; cursor:pointer; font-weight:600; letter-spacing:.3px;
        transition: background .25s ease;
    }
    input[type="submit"]:hover { background:#b21f1f; }
    .error-message { color: var(--color-acento-alerta); text-align:center; margin-bottom:1rem; font-weight:600; min-height:1.2rem; }
    a { color: var(--color-acento-secundario); }
    a:hover { text-decoration:underline; }
    .links-secundarios { margin-top:1rem; text-align:center; font-size:.85rem; }
    .links-secundarios div { margin-top:.4rem; }
</style>

<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <div class="error-message">{{ ERROR_LOGIN }}</div>
        <form action="?slug=login" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="txt_email">Email</label>
                <input type="email" id="txt_email" name="txt_email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="form-group">
                <label for="txt_password">Contraseña</label>
                <input type="password" id="txt_password" name="txt_password" placeholder="Ingresa tu contraseña" required>
            </div>
            <input type="submit" id="btn_ingresar" name="btn_ingresar" value="Acceder">
            <div class="links-secundarios">
                <div><a href="?slug=recovery">¿Olvidaste tu contraseña?</a></div>
                <div><a href="?slug=register">¿No tienes una cuenta? Registrarse</a></div>
            </div>
        </form>
        <script>
        // Permitir 'admin-estacion' sin el símbolo '@' cambiando dinámicamente el tipo
        (function(){
            const input = document.getElementById('txt_email');
            const isPseudoEmail = v => v === 'admin-estacion';
            input.addEventListener('input', () => {
                if(isPseudoEmail(input.value.trim())){
                    // Evita validación nativa de email para este usuario especial
                    if(input.type !== 'text'){ input.type = 'text'; }
                } else {
                    if(input.type !== 'email'){ input.type = 'email'; }
                }
            });
            // Al cargar, forzar tipo correcto si ya está prellenado
            if(isPseudoEmail(input.value.trim())) input.type = 'text';
        })();
        </script>
    </div>
</body>
</html>
