

@component(head)

<body>
    <header>
        <h1>{{ APP_NAME }}</h1>
        <nav>
            <a href="?slug=administrator">Volver</a>
        </nav>
    </header>

    <main style="padding:0; margin:0;">
        <div id="map" style="width:100%; height:60vh; background:#e9ecef;"></div>
    </main>

    <!-- Intentar Leaflet local con fallback a CDN -->
    <link rel="stylesheet" href="librarys/leaflet/leaflet.css" id="leaflet-css-local"/>
    <script>
    function ensureLeaflet(callback){
        function loadScript(src, done){ var s=document.createElement('script'); s.src=src; s.onload=done; s.onerror=done; document.head.appendChild(s); }
        function loadCDN(){
            loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', function(){
                // Asegurar CSS CDN si el local no existe
                if(!document.getElementById('leaflet-css-cdn')){
                    var l=document.createElement('link'); l.rel='stylesheet'; l.id='leaflet-css-cdn'; l.href='https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'; document.head.appendChild(l);
                }
                callback();
            });
        }
        if(window.L){ callback(); return; }
        loadScript('librarys/leaflet/leaflet.js', function(){ if(window.L){ callback(); } else { loadCDN(); } });
    }
    </script>

    <script>
    // Ajustar altura del mapa para ocupar casi todo el viewport entre header y footer
    function resizeMap(){
        const header = document.querySelector('header');
        const footer = document.querySelector('footer');
        const hH = header ? header.offsetHeight : 0;
        const fH = footer ? footer.offsetHeight : 0;
        const h = Math.max(300, window.innerHeight - hH - fH - 10);
        const mapDiv = document.getElementById('map');
        mapDiv.style.height = h + 'px';
        if(window.__leafletMap){ setTimeout(()=> window.__leafletMap.invalidateSize(), 120); }
    }
    window.addEventListener('load', resizeMap);
    window.addEventListener('resize', resizeMap);

    async function loadClients(){
        const url = '?list-clients-location=1';
        const resp = await fetch(url, { headers: { 'Accept':'application/json' } });
        if(!resp.ok) throw new Error('HTTP '+resp.status);
        const json = await resp.json();
        return json && json.data ? json.data : [];
    }

    function initMap(){
        const map = L.map('map').setView([-34.6, -58.4], 4);
        window.__leafletMap = map;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        return map;
    }

    (function(){
        ensureLeaflet(async function(){
            const map = initMap();
            try {
                const data = await loadClients();
                if(Array.isArray(data) && data.length){
                    const bounds = [];
                    data.forEach(item => {
                        const lat = parseFloat(item.latitud);
                        const lng = parseFloat(item.longitud);
                        if(!isFinite(lat) || !isFinite(lng)) return;
                        const marker = L.marker([lat, lng]).addTo(map);
                        const accesos = item.accesos ?? 1;
                        marker.bindPopup(`<b>${item.ip}</b><br/>Accesos: ${accesos}`);
                        bounds.push([lat,lng]);
                    });
                    if(bounds.length){ map.fitBounds(bounds, { padding:[20,20] }); }
                }
            } catch(e){ console.error('Error cargando clientes', e); }
            resizeMap();
        });
    })();
    </script>

    @component(footer)
</body>
</html>
