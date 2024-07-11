<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Leaflet Map</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Custom CSS for map styling -->
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100vh; 
            width: 100vw; 
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Map Container -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>

        var map = L.map('map').setView([42.806438027393256, -8.447001118686217], 14);

       
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker at Pico Sacro near Lestedo with a popup
        var marker = L.marker([42.806438027393256, -8.447001118686217]).addTo(map)
            .bindPopup('Pico Sacro')
            .openPopup();
    </script>
</body>
</html>
