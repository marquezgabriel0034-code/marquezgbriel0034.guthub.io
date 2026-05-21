<?php
// ========================================================================
// PHP BACKEND: Simulate fetching data from a MySQL Database.
// In the future, you will replace these arrays with a PDO/MySQLi query.
// ========================================================================

$dangerZones_PHP = [
    [
        'coords' => [[14.5735, 121.0085], [14.5740, 121.0089], [14.5743, 121.0086], [14.5739, 121.0082]],
        'name' => 'Tenorio Street Hotspot',
        'incidents' => 7,
        'type' => 'Riding-in-tandem',
        'peak' => '9PM–2AM',
        'level' => 'HIGH'
    ],
    [
        'coords' => [[14.5730, 121.0102], [14.5732, 121.0100], [14.5729, 121.0095], [14.5725, 121.0093], [14.5723, 121.0095]],
        'name' => 'Oro B Street Zone',
        'incidents' => 12,
        'type' => 'Phone/Bag Snatching',
        'peak' => 'All night',
        'level' => 'HIGH'
    ],
    [
        'coords' => [[14.5730, 121.0080], [14.5727, 121.0075], [14.5728, 121.0075], [14.5729, 121.0072], [14.5732, 121.0075]],
        'name' => 'Sagrada Familia Street',
        'incidents' => 4,
        'type' => 'Nighttime Assaults',
        'peak' => '10PM–3AM',
        'level' => 'MEDIUM'
    ],
    [
        'coords' => [[14.5873, 121.0078], [14.5869, 121.0081], [14.5867, 121.0079], [14.5870, 121.0075], [14.5874, 121.0076]],
        'name' => 'Tenorio × Oro Intersection',
        'incidents' => 5,
        'type' => 'Multiple incidents',
        'peak' => 'After dark',
        'level' => 'MEDIUM'
    ]
];

$crimeIncidents_PHP = [
    [ 'lat' => 14.6092, 'lng' => 120.9912, 'type' => 'robbery',   'desc' => 'Bag snatch – 2AM',      'time' => '2AM' ],
    [ 'lat' => 14.6087, 'lng' => 120.9914, 'type' => 'robbery',   'desc' => 'Phone theft – 11PM',    'time' => '11PM' ],
    [ 'lat' => 14.6078, 'lng' => 120.9928, 'type' => 'snatching', 'desc' => 'Motorcycle snatch',     'time' => 'Unknown' ],
    [ 'lat' => 14.6096, 'lng' => 120.9907, 'type' => 'assault',   'desc' => 'Group fight – 1AM',     'time' => '1AM' ],
    [ 'lat' => 14.6083, 'lng' => 120.9923, 'type' => 'snatching', 'desc' => 'Chain snatch',          'time' => '10PM' ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RouteSafe Barangay 770 — Safe Night Routes</title>

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

  <!-- App styles -->
  <link rel="stylesheet" href="navbar.css" />

  <style>
    /* ── Page-specific tweaks ── */
    body { overflow: hidden; margin: 0; padding: 0; }

    /* Leaflet tile dark filter */
    .leaflet-tile {
      filter: brightness(0.7) saturate(0.8) hue-rotate(195deg);
    }

    /* Routing container dark override */
    .leaflet-routing-container {
      max-width: 280px !important;
      max-height: 300px !important;
      overflow-y: auto !important;
    }

    /* Geocoder input */
    .leaflet-control-geocoder-form input {
      background: var(--rs-bg-raised, #222) !important;
      color: var(--rs-text-primary, #fff) !important;
      border: 1px solid var(--rs-border, #444) !important;
      border-radius: 4px !important;
    }

    /* Layer control */
    .leaflet-control-layers {
      background: var(--rs-bg-surface, #333) !important;
      border: 1px solid var(--rs-border, #444) !important;
      border-radius: 6px !important;
      color: var(--rs-text-secondary, #ddd) !important;
    }
    .leaflet-control-layers-toggle {
      background-color: var(--rs-bg-surface, #333) !important;
    }
    .leaflet-control-zoom a {
      background: var(--rs-bg-surface, #333) !important;
      color: var(--rs-text-primary, #fff) !important;
      border-color: var(--rs-border, #444) !important;
    }
    .leaflet-control-zoom a:hover {
      background: var(--rs-bg-raised, #444) !important;
    }

    /* Popup */
    .leaflet-popup-content-wrapper {
      background: var(--rs-bg-surface, #222) !important;
      border: 1px solid var(--rs-border, #444) !important;
      border-radius: 6px !important;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3) !important;
      color: var(--rs-text-primary, #fff) !important;
      font-size: 12px !important;
    }
    .leaflet-popup-tip {
      background: var(--rs-bg-surface, #222) !important;
    }

    /* Cluster marker */
    .crime-cluster-icon {
      background: #e63946;
      color: white;
      border-radius: 50%;
      border: 2px solid rgba(230,57,70,0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      box-shadow: 0 0 0 4px rgba(230,57,70,0.15);
    }
    
    #map { height: 100vh; width: 100vw; }
    
    /* Dummy styles for banner to render correctly */
    .info-banner { position: absolute; top: 60px; left: 10px; z-index: 1000; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; display: flex; flex-direction: column; gap: 5px; font-family: sans-serif; }
    #info { position: absolute; top: 120px; left: 10px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 10px; border-radius: 5px; font-family: sans-serif; font-size: 14px; }
    .legend { background: white; padding: 10px; border-radius: 5px; font-family: sans-serif; font-size: 12px; line-height: 1.5; }
    .legend-item { display: flex; align-items: center; margin-top: 5px; }
    .legend-color { width: 15px; height: 15px; border: 1px solid #ccc; margin-right: 8px; }
  </style>
</head>

<body>

  <!-- ── NAVBAR ── -->
  <nav class="navbar" style="position: absolute; top: 0; width: 100%; z-index: 1000; background: #111; color: white; padding: 10px; display: flex; justify-content: space-between; font-family: sans-serif;">
    <div class="navbar-brand">
      <div class="navbar-header">
        <span class="navbar-title"><b>RouteSafe Barangay 770</b></span>
      </div>
    </div>
    <div class="navbar-actions">
      <button class="btn-report" onclick="alert('Incident reporting coming soon!')" style="background: #e63946; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
        + Report Incident
      </button>
    </div>
  </nav>

  <!-- ── STATUS BANNER ── -->
  <div class="info-banner">
    <div class="banner-left">
      <span>Live safety data · Barangay 770, Sta. Ana, Manila</span>
    </div>
    <div class="banner-tags">
      <span id="night-badge">🌙 Night Alert Active</span>
    </div>
  </div>

  <!-- ── MAP INFO TOOLTIP ── -->
  <div id="info">⚠️ <b>Red zones</b> = crime hotspots · Click map to set <b>Start</b> then <b>End</b></div>

  <!-- ── MAP ── -->
  <div id="map"></div>


  <!-- ── SCRIPTS ── -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

  <script>
    /* ─────────────────────────────────────────────
       DYNAMIC PHP DATA INJECTION
    ───────────────────────────────────────────── */
    // Here we use PHP's json_encode to convert the PHP arrays into JavaScript objects securely.
    const dangerZones = <?php echo json_encode($dangerZones_PHP); ?>;
    const crimeIncidents = <?php echo json_encode($crimeIncidents_PHP); ?>;

    /* ─────────────────────────────────────────────
       INIT MAP
    ───────────────────────────────────────────── */
    const map = L.map('map', { zoomControl: false }).setView([14.5875, 121.0078], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
      maxZoom: 19
    }).addTo(map);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    /* ─────────────────────────────────────────────
       NIGHT MODE BADGE (auto-detect time)
    ───────────────────────────────────────────── */
    const hour = new Date().getHours();
    const isNight = hour >= 20 || hour < 6;
    const nightBadge = document.getElementById('night-badge');
    if (!isNight) {
      nightBadge.textContent = '☀️ Daytime Mode';
      nightBadge.style.color = '#eab308';
    }

    /* ─────────────────────────────────────────────
       DANGER ZONES
    ───────────────────────────────────────────── */
    const dangerZoneLayer = L.layerGroup().addTo(map);

    dangerZones.forEach((zone, i) => {
      const color = zone.level === 'HIGH'
        ? 'rgba(230,57,70,0.6)'
        : 'rgba(234,179,8,0.6)';

      const poly = L.polygon(zone.coords, {
        className: 'danger-zone',
        fillColor: zone.level === 'HIGH' ? '#e63946' : '#eab308',
        fillOpacity: 0.15,
        color: color,
        weight: 1.5,
        dashArray: zone.level === 'MEDIUM' ? '4 3' : null
      }).addTo(dangerZoneLayer);

      const levelBadge = zone.level === 'HIGH'
        ? `<span style="background:rgba(230,57,70,0.2);color:#e63946;padding:2px 7px;border-radius:4px;font-size:10px;font-weight:700;">HIGH RISK</span>`
        : `<span style="background:rgba(234,179,8,0.15);color:#eab308;padding:2px 7px;border-radius:4px;font-size:10px;font-weight:700;">MEDIUM RISK</span>`;

      poly.bindPopup(`
        <div style="min-width:200px; color:#333;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <strong style="font-size:13px;">${zone.name}</strong>
            ${levelBadge}
          </div>
          <div style="color:#666;font-size:11px;line-height:1.8;">
            📋 <strong>${zone.incidents}+</strong> incidents reported<br>
            ⚠️ Type: ${zone.type}<br>
            ⏰ Peak: ${zone.peak}
          </div>
        </div>
      `);

      poly.on('mouseover', function() { this.setStyle({ fillOpacity: 0.3 }); });
      poly.on('mouseout',  function() { this.setStyle({ fillOpacity: 0.15 }); });
    });

    /* ─────────────────────────────────────────────
       CRIME INCIDENT MARKERS
    ───────────────────────────────────────────── */
    const crimeIcons = { robbery: '🚨', snatching: '💨', assault: '👊' };
    const typeColors = { robbery: '#e63946', snatching: '#eab308', assault: '#f97316' };

    const crimeCluster = L.markerClusterGroup({
      iconCreateFunction: cluster => L.divIcon({
        html: `<div class="crime-cluster-icon" style="width:30px;height:30px;">${cluster.getChildCount()}</div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
      })
    });

    crimeIncidents.forEach(inc => {
      const marker = L.marker([inc.lat, inc.lng], {
        icon: L.divIcon({
          html: `<div style="font-size:18px;text-align:center;line-height:1;">${crimeIcons[inc.type] || '⚠️'}</div>`,
          className: 'crime-marker',
          iconSize: [24, 24],
          iconAnchor: [12, 12]
        })
      });

      const col = typeColors[inc.type] || '#94a3b8';
      marker.bindPopup(`
        <div style="color:#333;">
          <span style="background:rgba(${inc.type==='robbery'?'230,57,70':'234,179,8'},0.15);color:${col};padding:2px 8px;border-radius:4px;font-size:10px;font-weight:700;">
            ${inc.type.toUpperCase()}
          </span>
          <div style="margin-top:7px;color:#666;font-size:11px;">${inc.desc}</div>
          <div style="color:#444;font-size:10px;margin-top:3px;">⏰ ${inc.time}</div>
        </div>
      `);

      crimeCluster.addLayer(marker);
    });

    map.addLayer(crimeCluster);

    /* ─────────────────────────────────────────────
       ROUTING
    ───────────────────────────────────────────── */
    const routingControl = L.Routing.control({
      waypoints: [],
      routeWhileDragging: true,
      draggableWaypoints: true,
      lineOptions: {
        styles: [
          { color: '#1d4ed8', weight: 7, opacity: 0.15 },
          { color: '#3b82f6', weight: 4, opacity: 0.9  }
        ]
      },
      show: false,
      addWaypoints: false
    }).addTo(map);

    /* ─────────────────────────────────────────────
       CLICK TO SET START / END
    ───────────────────────────────────────────── */
    let startMarker = null, endMarker = null, clickStep = 0;

    const makeIcon = (emoji, label, bg) => L.divIcon({
      html: `
        <div style="display:flex;flex-direction:column;align-items:center;gap:2px;">
          <div style="
            width:32px;height:32px;border-radius:50%;
            background:${bg};border:3px solid white;
            display:flex;align-items:center;justify-content:center;
            font-size:14px;box-shadow:0 2px 8px rgba(0,0,0,0.5);">
            ${emoji}
          </div>
          <div style="
            background:${bg};color:white;
            font-size:9px;font-weight:700;
            padding:2px 5px;border-radius:3px;
            box-shadow:0 1px 4px rgba(0,0,0,0.4);
            font-family:Inter,sans-serif;letter-spacing:0.5px;">
            ${label}
          </div>
        </div>`,
      className: '',
      iconSize: [32, 48],
      iconAnchor: [16, 48]
    });

    map.on('click', e => {
      if (clickStep === 0) {
        if (startMarker) map.removeLayer(startMarker);
        startMarker = L.marker(e.latlng, {
          draggable: true,
          icon: makeIcon('S', 'START', '#22c55e')
        }).addTo(map).bindPopup('<b style="color:#333;">Start Point</b>').openPopup();
        startMarker.on('dragend', updateRoute);
        setInfo('✅ Start set! Now click your <b>destination</b>');
        clickStep = 1;

      } else {
        if (endMarker) map.removeLayer(endMarker);
        endMarker = L.marker(e.latlng, {
          draggable: true,
          icon: makeIcon('E', 'END', '#e63946')
        }).addTo(map).bindPopup('<b style="color:#333;">Destination</b>').openPopup();
        endMarker.on('dragend', updateRoute);
        updateRoute();
        clickStep = 0;
      }
    });

    function updateRoute() {
      if (!startMarker || !endMarker) return;
      routingControl.setWaypoints([startMarker.getLatLng(), endMarker.getLatLng()]);

      const start = startMarker.getLatLng();
      const end   = endMarker.getLatLng();
      let dangerCount = 0;

      dangerZones.forEach(zone => {
        const bounds = L.latLngBounds(zone.coords);
        if (bounds.contains(start) || bounds.contains(end)) dangerCount++;
      });

      const safetyScore = Math.max(30, 100 - dangerCount * 22);
      const scoreColor  = safetyScore >= 70 ? '#22c55e'
                        : safetyScore >= 45 ? '#eab308'
                        : '#e63946';

      const msg = dangerCount > 0
        ? `⚠️ Route near <b>${dangerCount}</b> danger zone${dangerCount > 1 ? 's' : ''}! Safety score: <span style="color:${scoreColor};font-weight:700;">${safetyScore}</span>`
        : `✅ Route looks safe. Safety score: <span style="color:${scoreColor};font-weight:700;">${safetyScore}</span> · Stay alert at night`;

      setInfo(msg);
    }

    function setInfo(html) {
      document.getElementById('info').innerHTML = `<span style="color:#333;">${html}</span>`;
    }

    /* ─────────────────────────────────────────────
       GEOCODER
    ───────────────────────────────────────────── */
    L.Control.geocoder({
      placeholder: 'Search address…',
      position: 'topleft'
    }).addTo(map);

    /* ─────────────────────────────────────────────
       LEGEND
    ───────────────────────────────────────────── */
    const legend = L.control({ position: 'bottomleft' });
    legend.onAdd = () => {
      const div = L.DomUtil.create('div', 'legend');
      div.innerHTML = `
        <div style="color:#333;">
            <b>Map Legend</b>
            <div class="legend-item">
            <div class="legend-color" style="background:rgba(230,57,70,0.2);border-color:rgba(230,57,70,0.5);"></div>
            High-risk zone
            </div>
            <div class="legend-item">
            <div class="legend-color" style="background:rgba(234,179,8,0.18);border-color:rgba(234,179,8,0.4);"></div>
            Medium-risk zone
            </div>
            <div class="legend-item">
            <div class="legend-color" style="background:#3b82f6;border-radius:2px;"></div>
            Safest route
            </div>
            <div class="legend-item">
            <span style="font-size:13px;margin-right:2px;">🚨 💨 👊</span>
            Crime incidents
            </div>
        </div>
      `;
      return div;
    };
    legend.addTo(map);

    /* ─────────────────────────────────────────────
       LAYER CONTROL
    ───────────────────────────────────────────── */
    L.control.layers(null, {
      'Danger Zones': dangerZoneLayer,
      'Crime Incidents': crimeCluster
    }, { position: 'topright' }).addTo(map);

  </script>
</body>
</html>