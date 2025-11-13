<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ APP_SECTION }} - {{ APP_NAME }}</title>
    <style>
        .icon { width:1rem; height:1rem; vertical-align:middle; display:inline-block; margin-right:.35rem; }
        .icon-lg { width:1.15rem; height:1.15rem; }
        .field-line { display:flex; align-items:center; gap:.35rem; }
        .field-line svg { flex:0 0 auto; }
        :root {
    /* Paleta de colores */
    --color-fondo-principal: {{ COLOR_FONDO_PRINCIPAL }};
    --color-texto-principal: {{ COLOR_TEXTO_PRINCIPAL }};
    --color-texto-secundario: {{ COLOR_TEXTO_SECUNDARIO }};
    --color-acento-alerta: {{ COLOR_ACENTO_ALERTA }}; /* Urgencia / alerta */
    --color-acento-secundario: {{ COLOR_ACENTO_SECUNDARIO }}; /* Confianza / enlaces */
    --color-boton-principal-fondo: var(--color-acento-alerta);
    --color-boton-principal-texto: {{ COLOR_BOTON_PRINCIPAL_TEXTO }};
    --color-boton-secundario-fondo: var(--color-acento-secundario);
    --color-boton-secundario-texto: {{ COLOR_BOTON_SECUNDARIO_TEXTO }};
    --color-header-fondo: {{ COLOR_HEADER_FONDO }};
    --color-footer-fondo: {{ COLOR_FOOTER_FONDO }};
    --color-footer-texto: {{ COLOR_FOOTER_TEXTO }};
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--color-fondo-principal);
            color: var(--color-texto-principal);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Sticky footer */
        }


        header {
            background-color: var(--color-header-fondo);
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.5rem;
            color: var(--color-acento-alerta);
        }

        nav a {
            margin-left: 1rem;
            text-decoration: none;
            color: var(--color-texto-secundario);
            font-weight: bold;
        }

        nav a:hover {
            color: var(--color-acento-secundario);
        }

        .hero {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('https://images.unsplash.com/photo-1520975922203-42d2444e6590?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat;
            color: #fff;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .botones-accion {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none; /* quitar subrayado en <a> */
            display: inline-block; /* comportamiento de botón */
            font-weight: 600; /* texto más sólido */
        }

        .btn-principal {
            background-color: var(--color-boton-principal-fondo);
            color: var(--color-boton-principal-texto);
        }

        .btn-principal:hover {
            background-color: #b21f1f;
        }

        .btn-secundario {
            background-color: var(--color-boton-secundario-fondo);
            color: var(--color-boton-secundario-texto);
        }

        .btn-secundario:hover {
            background-color: #35608d;
        }

        .seccion {
            padding: 2rem;
            max-width: 1100px;
            margin: auto;
            text-align: center;
        }

        /* Panel: listado de estaciones */
        .grid-estaciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .btn-estacion {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 1rem;
            text-align: left;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            color: var(--color-texto-principal);
            text-decoration: none;
        }
        .btn-estacion:hover { background: #f6f8fa; }
        /* Soporte tanto est-* como estacion-* (plantilla actual usa estacion-*) */
        .btn-estacion .est-apodo, .btn-estacion .estacion-apodo { display:block; font-weight:700; margin-bottom: .25rem; }
        .btn-estacion .est-ubicacion, .btn-estacion .estacion-ubicacion { display:block; color: var(--color-texto-secundario); margin-bottom: .5rem; }
        .btn-estacion .est-visitas, .btn-estacion .estacion-visitas { display:block; font-size: .9rem; color: var(--color-acento-secundario); }
        .btn-estacion .est-actions { margin-top: .75rem; }
        .btn-estacion .btn-ver { padding: .5rem .9rem; border-radius: 6px; }
        .btn-estacion--inactiva { opacity: .9; position: relative; padding-top: 2.6rem; }
        .btn-estacion--inactiva:before {
            content: 'Inactiva';
            position: absolute; top: 4px; left: 8px;
            background: #ffe5e5; color: #b00000; font-size: .72rem;
            padding: 4px 10px; border-radius: 14px; font-weight: 700; letter-spacing:.5px;
            box-shadow: 0 0 0 1px rgba(176,0,0,0.15);
            pointer-events:none;
        }

        .seccion h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .seccion p {
            color: var(--color-texto-secundario);
        }

        footer {
            background-color: var(--color-footer-fondo);
            color: var(--color-footer-texto);
            text-align: center;
            padding: 1rem;
            margin-top: auto; /* fija el footer al final */
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Sprite SVG global -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display:none">
        <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s7-6.2 7-12A7 7 0 0 0 5 10c0 5.8 7 12 7 12Z" />
            <circle cx="12" cy="10" r="3" />
        </symbol>
        <symbol id="i-cal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </symbol>
        <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </symbol>
    </svg>