

@component(head)

<body>
    <header>
        <h1>{{ APP_NAME }}</h1>
        <nav>
            <a href="?slug=login">Acceder</a>
        </nav>
    </header>

    <section class="hero">
        <h2>Monitoreo Climático Local en Tiempo Real</h2>
        <p>{{ APP_DESCRIPTION }}</p>
        <div class="botones-accion">
            <a class="btn btn-principal" href="?slug=panel">Ver Panel</a>
        </div>
    </section>

    <section class="seccion">
        <h3>¿Qué ofrece?</h3>
        <p>Recopila datos de sensores (temperatura, humedad, presión, viento y lluvia) y los presenta en un panel claro para análisis y toma de decisiones.</p>
    </section>

    @component(footer)
</body>
</html>
