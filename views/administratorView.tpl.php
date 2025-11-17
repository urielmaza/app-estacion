@component(head)

<body>
    <header>
        <h1>{{ APP_NAME }}</h1>
        <nav>
            <a href="?slug=map">Mapa de clientes</a>
            <a href="?slug=logout">Cerrar sesión</a>
        </nav>
    </header>

    <main class="seccion">
        <h2>Administrador</h2>
        {{ ADMIN_CONTENT }}
    </main>

    @component(footer)
</body>
</html>
