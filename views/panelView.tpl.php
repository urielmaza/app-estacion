


@component(head)

<body>
    <header>
        <h1>{{ APP_NAME }}</h1>
        <nav>
            <a href="?slug=landing">Inicio</a>
            <a href="?slug=logout">Cerrar sesión</a>
        </nav>
    </header>

    <main class="seccion">
        <h2>Estaciones Meteorológicas</h2>
        <p class="sub">Selecciona una estación para ver su detalle.</p>

        <!-- Nuevo template siguiendo el patrón solicitado -->
        <template id="tpl-btn-estacion">
            <a class="btn btn-estacion" href="#">
                <span class="estacion-apodo"></span>
                <span class="estacion-ubicacion"></span>
                <span class="estacion-visitas"></span>
            </a>
        </template>

        <div id="list-estacion" class="grid-estaciones"></div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Registrar abandono de la app: marca delete_date en backend
        const sendAbandon = () => {
            const url = '?slug=leave';
            const payload = JSON.stringify({ ts: Date.now(), path: location.pathname + location.search });
            if (navigator.sendBeacon) {
                const blob = new Blob([payload], { type: 'application/json' });
                navigator.sendBeacon(url, blob);
            } else {
                fetch(url, { method: 'POST', body: payload, headers: { 'Content-Type': 'application/json' }, keepalive: true }).catch(()=>{});
            }
        };
        // beforeunload es soportado en desktop; pagehide mejora en móviles/Safari
        window.addEventListener('beforeunload', sendAbandon);
        window.addEventListener('pagehide', sendAbandon);

        loadEstaciones().then(data => {
            if(!Array.isArray(data) || !data.length){
                document.querySelector('#list-estacion').innerHTML = '<p>No se encontraron estaciones.</p>';
                return;
            }
            data.forEach(est => addBtnEstacion(est));
        }).catch(err => {
            console.error('Error list-stations', err);
            document.querySelector('#list-estacion').innerHTML = '<p>Error cargando estaciones.</p>';
        });
    });

    async function loadEstaciones(){
        try {
            const resp = await fetch('https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations', {
                headers: { 'Accept': 'application/json' }
            });
            if(resp.ok){
                const json = await resp.json();
                if(Array.isArray(json) && json.length) return json;
            }
        } catch(e){ console.warn('Fallback a scraping', e); }

        // Fallback: scraping HTML (ya usado antes)
        try {
            const htmlResp = await fetch('https://mattprofe.com.ar/proyectos/app-estacion/', { headers: { 'Accept': 'text/html' } });
            const html = await htmlResp.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const anchors = Array.from(doc.querySelectorAll('a[href*="panel.php?chipid="]'));
            return anchors.map(a => {
                const href = a.getAttribute('href');
                const url = new URL(href, 'https://mattprofe.com.ar/proyectos/app-estacion/');
                const chipid = url.searchParams.get('chipid') || '';
                const raw = a.textContent.replace(/\u00A0/g,' ').replace(/\s+/g,' ').trim();
                const m = raw.match(/^(.+?)\s{2,}(.+)$/);
                return {
                    chipid,
                    apodo: m ? m[1].trim() : raw,
                    ubicacion: m ? m[2].replace(/\s*Inactiva\s*/i,'').trim() : '',
                    visitas: null,
                    dias_inactivo: /Inactiva/i.test(raw) ? 1 : 0
                };
            }).filter(x => x.chipid);
        } catch(e){
            console.error('Error scraping', e);
            return [];
        }
    }

    function addBtnEstacion(info){
        const tpl = document.querySelector('#tpl-btn-estacion');
        const clon = tpl.content.cloneNode(true);
        const root = clon.querySelector('.btn-estacion');
        if(info.dias_inactivo > 0) root.classList.add('btn-estacion--inactiva');
        root.setAttribute('href', `?slug=detalle/${encodeURIComponent(info.chipid)}`);
        clon.querySelector('.estacion-apodo').textContent = info.apodo || `Estación ${info.chipid}`;
        clon.querySelector('.estacion-ubicacion').innerHTML = `<svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg> ${info.ubicacion}`;
        clon.querySelector('.estacion-visitas').innerHTML = `${info.visitas !== null && info.visitas !== undefined ? info.visitas : ''} <i class="fa-solid fa-tower-observation color-visitas"></i>`;
        const btnVer = clon.querySelector('.btn-ver');
        if(btnVer) btnVer.href = `?slug=detalle/${encodeURIComponent(info.chipid)}`;
        const btnExt = clon.querySelector('.btn-external');
        if(btnExt) btnExt.href = `https://mattprofe.com.ar/proyectos/app-estacion/panel.php?chipid=${encodeURIComponent(info.chipid)}`;
        root.addEventListener('click', e => {
            if(e.target.closest('.btn-ver') || e.target.closest('.btn-external')) return;
            e.preventDefault();
            window.location.href = `?slug=detalle/${encodeURIComponent(info.chipid)}`;
        });
        document.querySelector('#list-estacion').appendChild(clon);
    }
    </script>

    @component(footer)
</body>
</html>
