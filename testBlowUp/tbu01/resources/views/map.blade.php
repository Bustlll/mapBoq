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
            height: 100vh; /* Map height to fill the viewport height */
            width: 100vw; /* Map width to fill the viewport width */
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
        // Initialize the map and set its view to Pico Sacro near Lestedo in Galicia with a zoom level of 12
        var map = L.map('map').setView([42.7829, -8.4653], 12);

        // Add a tile layer to our map, in this case it's an OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Get the data from the server
        var data = @json($data);

        // Function to add Overpass data to the map
        function addDataToMap(data) {
            data.elements.forEach(element => {
                if (element.type === 'node') {
                    const lat = element.lat;
                    const lon = element.lon;
                    L.marker([lat, lon]).addTo(map).bindPopup(`Drinking water: Node ${element.id}`);
                }
            });
        }

        // Add data to the map
        addDataToMap(data);

    </script>
</body>
</html>
