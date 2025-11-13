@component(head)

<body>
    <header>
        <h1>{{ APP_NAME }}</h1>
        <nav>
            <a href="?slug=panel">Panel</a>
        </nav>
    </header>

    <main class="seccion" style="text-align:left;width:100%;max-width:none;padding:0 2.5rem;">
        <h2 style="text-align:center;">Detalle de Estación</h2>
        <div class="detalle-estacion" style="margin-bottom:1.5rem;text-align:center;">
            <p class="field-line"><use href="#i-pin"/><span id="det-apodo">—</span></p>
            <p class="field-line"><svg class="icon icon-lg" aria-hidden="true"><use href="#i-pin"/></svg><span id="det-ubicacion">—</span></p>
        </div>

        <!-- Secciones categorizadas -->
        <style>
        /* Grid 2 columnas amplias; colapsa a 1 en móviles */
        .grid-categorias.two-cols { display:grid; gap:1.6rem; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items:start; width:100%; }
        @media (max-width: 820px) { .grid-categorias.two-cols { grid-template-columns: 1fr; } }
        /* Ancho máximo individual para que no se estiren demasiado y mantener legibilidad */
        .grid-categorias.two-cols .card-cat { width:100%; margin:0; }
        /* Ajustar altura de charts proporcional si el contenedor es más ancho */
        .grid-categorias.two-cols .card-cat .chart-wrap { height:340px !important; }
        .grid-categorias.two-cols .card-cat { background:#fff; border:1px solid #ddd; border-radius:8px; padding:1rem; }
        .grid-categorias.two-cols .chart-wrap { position:relative; }
        </style>
        <div class="grid-categorias two-cols">
            <section class="card-cat" id="sec-temperatura">
                <h3>Temperatura</h3>
                <div class="cat-body">
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-cal"/></svg><span data-field="fecha">—</span></p>
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span data-field="ubicacion">—</span></p>
                    <p><strong>Temperatura:</strong> <span data-field="temperatura">—</span> °C</p>
                    <p><strong>Sensación:</strong> <span data-field="sensacion">—</span> °C</p>
                    <p><strong>Temp Máx:</strong> <span data-field="tempmax">—</span> °C</p>
                    <p><strong>Temp Mín:</strong> <span data-field="tempmin">—</span> °C</p>
                    <p><strong>Sensación Máx:</strong> <span data-field="sensamax">—</span> °C</p>
                    <p><strong>Sensación Mín:</strong> <span data-field="sensamin">—</span> °C</p>
                    <div class="chart-wrap" style="margin-top:.75rem;height:260px;">
                        <canvas id="chartTemp"></canvas>
                    </div>
                </div>
            </section>
            <section class="card-cat" id="sec-fuego">
                <h3>Índices de Fuego</h3>
                <div class="cat-body">
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-cal"/></svg><span data-field="fecha">—</span></p>
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span data-field="ubicacion">—</span></p>
                    <p><strong>FFMC:</strong> <span data-field="ffmc">—</span></p>
                    <p><strong>DMC:</strong> <span data-field="dmc">—</span></p>
                    <p><strong>DC:</strong> <span data-field="dc">—</span></p>
                    <p><strong>ISI:</strong> <span data-field="isi">—</span></p>
                    <p><strong>BUI:</strong> <span data-field="bui">—</span></p>
                    <p><strong>FWI:</strong> <span data-field="fwi">—</span></p>
                    <div class="chart-wrap" style="margin-top:.75rem;height:260px;">
                        <canvas id="chartFuego"></canvas>
                    </div>
                </div>
            </section>
            <section class="card-cat" id="sec-humedad">
                <h3>Humedad</h3>
                <div class="cat-body">
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-cal"/></svg><span data-field="fecha">—</span></p>
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span data-field="ubicacion">—</span></p>
                    <p><strong>Humedad:</strong> <span data-field="humedad">—</span> %</p>
                    <div class="chart-wrap" style="margin-top:.75rem;height:260px;">
                        <canvas id="chartHumedad"></canvas>
                    </div>
                </div>
            </section>
            <section class="card-cat" id="sec-presion">
                <h3>Presión</h3>
                <div class="cat-body">
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-cal"/></svg><span data-field="fecha">—</span></p>
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span data-field="ubicacion">—</span></p>
                    <p><strong>Presión:</strong> <span data-field="presion">—</span> hPa</p>
                    <div class="chart-wrap" style="margin-top:.75rem;height:260px;">
                        <canvas id="chartPresion"></canvas>
                    </div>
                </div>
            </section>
            <section class="card-cat" id="sec-viento">
                <h3>Viento</h3>
                <div class="cat-body">
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-cal"/></svg><span data-field="fecha">—</span></p>
                    <p class="field-line"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span data-field="ubicacion">—</span></p>
                    <p><strong>Dirección:</strong> <span data-field="veleta">—</span></p>
                    <p><strong>Viento:</strong> <span data-field="viento">—</span> Km/h</p>
                    <p><strong>Viento Máx:</strong> <span data-field="maxviento">—</span> Km/h</p>
                    <div class="chart-wrap" style="margin-top:.75rem;height:260px;">
                        <canvas id="chartViento"></canvas>
                    </div>
                </div>
            </section>
        </div>
        <p style="margin-top:2rem;text-align:center;"><a class="btn btn-secundario" href="?slug=panel">Volver al Panel</a></p>
    </main>

    <script>
    (async () => {
        const URL_LISTA = '?slug=api-estaciones';
        const chipidTpl = '{{ CHIPID }}';
        const slug = new URLSearchParams(location.search).get('slug') || '';
        const slugChip = slug.includes('/') ? slug.split('/')[1] : '';
        const chipid = chipidTpl && chipidTpl !== '{{ CHIPID }}' ? chipidTpl : slugChip;

        // Cargar Chart.js si no existe
        if(!window.Chart) {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
            s.async = false;
            document.head.appendChild(s);
            await new Promise(res => { s.onload = res; s.onerror = res; });
        }
        try {
            const res = await fetch(URL_LISTA, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            const items = Array.isArray(data) ? data : (Array.isArray(data.items) ? data.items : []);
            const est = items.find(s => String(s.chipid) === String(chipid));
            if (est) {
                document.getElementById('det-apodo').textContent = est.apodo || chipid;
                document.getElementById('det-ubicacion').textContent = est.ubicacion || '';
            }
        } catch (e) {
            console.error('Error cargando detalle', e);
        }

        // Cargar última lectura desde la API pública del panel externo
        try {
            const urlDatos = `https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=${encodeURIComponent(chipid)}&cant=1`;
            const r = await fetch(urlDatos, { headers: { 'Accept': 'application/json' } });
            if (!r.ok) throw new Error('HTTP ' + r.status);
            const json = await r.json();
            const reg = Array.isArray(json) ? (json[0] || {}) : (json || {});
            // Helper para colocar datos por atributo data-field en cada sección
            function fillSection(sectionId, fields){
                const sec = document.getElementById(sectionId);
                if(!sec) return;
                // Fecha y ubicación siempre
                sec.querySelectorAll('[data-field="fecha"]').forEach(el => el.textContent = reg.fecha || '—');
                sec.querySelectorAll('[data-field="ubicacion"]').forEach(el => el.textContent = reg.ubicacion || '—');
                fields.forEach(f => {
                    sec.querySelectorAll(`[data-field="${f}"]`).forEach(el => {
                        let val = reg[f];
                        if(val === undefined || val === null || val === '') val = '—';
                        el.textContent = val;
                    });
                });
            }
            fillSection('sec-temperatura',[ 'temperatura','sensacion','tempmax','tempmin','sensamax','sensamin' ]);
            fillSection('sec-fuego',[ 'ffmc','dmc','dc','isi','bui','fwi' ]);
            fillSection('sec-humedad',[ 'humedad' ]);
            fillSection('sec-presion',[ 'presion' ]);
            fillSection('sec-viento',[ 'veleta','viento','maxviento' ]);

            // Si no se pudo cargar apodo/ubicación desde la lista, usarlos desde este JSON
            const apodoEl = document.getElementById('det-apodo');
            const ubicEl = document.getElementById('det-ubicacion');
            if (apodoEl && (!apodoEl.textContent || apodoEl.textContent.trim() === '—')) {
                if (reg.estacion) apodoEl.textContent = String(reg.estacion);
            }
            if (ubicEl && (!ubicEl.textContent || ubicEl.textContent.trim() === '—')) {
                if (reg.ubicacion) ubicEl.textContent = String(reg.ubicacion);
            }
        } catch (e) {
            console.error('Error cargando última lectura', e);
            ['sec-temperatura','sec-fuego','sec-humedad','sec-presion','sec-viento'].forEach(id => {
                const sec = document.getElementById(id);
                if(sec) sec.querySelector('.cat-body').innerHTML = '<p>Error al cargar datos.</p>';
            });
        }

        // ================== Gráficos históricos ==================
        async function loadHistorico(cant=60){
            try {
                const url = `https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=${encodeURIComponent(chipid)}&cant=${cant}`;
                const r = await fetch(url, { headers:{'Accept':'application/json'} });
                if(!r.ok) throw new Error('HTTP '+r.status);
                const json = await r.json();
                return Array.isArray(json)? json : [];
            } catch(e){ console.error('Error histórico', e); return []; }
        }

        const historico = await loadHistorico(60);
        if(!historico.length){
            ['chartTemp','chartFuego','chartHumedad','chartPresion','chartViento'].forEach(id => {
                const canvas = document.getElementById(id);
                if(canvas){
                    const parent = canvas.parentElement;
                    if(parent) parent.innerHTML = '<p style="color:#a00;font-size:.9rem;">Sin datos para graficar.</p>';
                }
            });
            return; // No continuar con graficado
        }
        historico.sort((a,b)=> (a.fecha||'').localeCompare(b.fecha||''));
        const labels = historico.map(r => (r.fecha||'').split(' ').pop());

        const num = v => { const n = parseFloat(v); return isNaN(n)? null : n; };

        // Construcción datasets
        const dsTemp = historico.map(r => num(r.temperatura));
        const dsSens = historico.map(r => num(r.sensacion));
        const dsHum  = historico.map(r => num(r.humedad));
        const dsPres = historico.map(r => num(r.presion));
        const dsViento = historico.map(r => num(r.viento));
        const dsVientoMax = historico.map(r => num(r.maxviento));
        const dsFFMC = historico.map(r => num(r.ffmc));
        const dsDMC = historico.map(r => num(r.dmc));
        const dsDC = historico.map(r => num(r.dc));
        const dsISI = historico.map(r => num(r.isi));
        const dsBUI = historico.map(r => num(r.bui));
        const dsFWI = historico.map(r => num(r.fwi));

        const commonOpts = {
            responsive:true,
            maintainAspectRatio:false,
            interaction:{mode:'index', intersect:false},
            plugins:{
                legend:{position:'bottom'},
                tooltip:{ callbacks:{ label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue}` } }
            },
            scales:{ x:{ ticks:{ maxRotation:0 } }, y:{ beginAtZero:false } }
        };

        function createChart(id, datasets, extraOpts={}){
            const el = document.getElementById(id);
            if(!el) return;
            new Chart(el.getContext('2d'), {
                type:'line',
                data:{ labels, datasets },
                options: Object.assign({}, commonOpts, extraOpts)
            });
        }

        createChart('chartTemp', [
            { label:'Temp', data: dsTemp, borderColor:'#d62828', backgroundColor:'rgba(214,40,40,0.15)', tension:.25, spanGaps:true },
            { label:'Sensación', data: dsSens, borderColor:'#457b9d', backgroundColor:'rgba(69,123,157,0.15)', tension:.25, spanGaps:true }
        ]);

        createChart('chartFuego', [
            { label:'FFMC', data: dsFFMC, borderColor:'#ff7b00', backgroundColor:'rgba(255,123,0,0.15)', tension:.2 },
            { label:'DMC', data: dsDMC, borderColor:'#ff0059', backgroundColor:'rgba(255,0,89,0.15)', tension:.2 },
            { label:'DC',  data: dsDC,  borderColor:'#7b2cbf', backgroundColor:'rgba(123,44,191,0.15)', tension:.2 },
            { label:'ISI', data: dsISI, borderColor:'#008dff', backgroundColor:'rgba(0,141,255,0.15)', tension:.2 },
            { label:'BUI', data: dsBUI, borderColor:'#2d6a4f', backgroundColor:'rgba(45,106,79,0.15)', tension:.2 },
            { label:'FWI', data: dsFWI, borderColor:'#ffbe0b', backgroundColor:'rgba(255,190,11,0.15)', tension:.2 }
        ], { scales:{ y:{ beginAtZero:true }}});

        createChart('chartHumedad', [
            { label:'Humedad %', data: dsHum, borderColor:'#1d3557', backgroundColor:'rgba(29,53,87,0.15)', tension:.25, spanGaps:true }
        ], { scales:{ y:{ beginAtZero:true, max:100 }}});

        createChart('chartPresion', [
            { label:'Presión hPa', data: dsPres, borderColor:'#6a4c93', backgroundColor:'rgba(106,76,147,0.15)', tension:.25, spanGaps:true }
        ]);

        createChart('chartViento', [
            { label:'Viento Km/h', data: dsViento, borderColor:'#0a9396', backgroundColor:'rgba(10,147,150,0.15)', tension:.25 },
            { label:'Viento Máx Km/h', data: dsVientoMax, borderColor:'#005f73', backgroundColor:'rgba(0,95,115,0.15)', tension:.25 }
        ], { scales:{ y:{ beginAtZero:true }}});
    })();
    </script>

    @component(footer)
</body>
</html>
