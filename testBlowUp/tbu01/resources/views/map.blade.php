<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Leaflet Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
        #controls {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        #search-controls {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="map"></div>
        <div id="controls">
            <button id="fetch-restaurants">Restaurantes</button>
            <button id="fetch-water">Agua Potable</button>
            <button id="fetch-fuel">Combustible</button>
            <button id="fetch-hotels">Hoteles</button>
            <button id="fetch-parking">Aparcamiento</button>
            <div id="search-controls">
                <input type="text" id="search-query" placeholder="Buscar..." />
                <button id="fetch-search">Buscar</button>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([42.7829, -8.4653], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add 10 specific points of interest to the map
        const pointsOfInterest = @json($pointsOfInterest);
        pointsOfInterest.forEach(point => {
            L.marker([point.lat, point.lon])
                .addTo(map)
                .bindPopup(point.name)
                .openPopup();
        });

        const apiUrl = 'https://overpass-api.de/api/interpreter';

        const queries = {
            'fetch-restaurants': `
                [out:json][timeout:25];
                node["amenity"="restaurant"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `,
            'fetch-water': `
                [out:json][timeout:25];
                node["amenity"="drinking_water"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `,
            'fetch-fuel': `
                [out:json][timeout:25];
                node["amenity"="fuel"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `,
            'fetch-hotels': `
                [out:json][timeout:25];
                node["tourism"="hotel"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `,
            'fetch-parking': `
                [out:json][timeout:25];
                node["amenity"="parking"]["parking"="surface"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `,
        };
        //mandar el fetch para cada boton
        document.getElementById('fetch-restaurants').addEventListener('click', function() {
            fetchData('fetch-restaurants');
        });

        document.getElementById('fetch-water').addEventListener('click', function() {
            fetchData('fetch-water');
        });

        document.getElementById('fetch-fuel').addEventListener('click', function() {
            fetchData('fetch-fuel');
        });

        document.getElementById('fetch-hotels').addEventListener('click', function() {
            fetchData('fetch-hotels');
        });

        document.getElementById('fetch-parking').addEventListener('click', function() {
            fetchData('fetch-parking');
        });
        //boton para activar la funcion de traduccion
        document.getElementById('fetch-search').addEventListener('click', function() {
            let query = document.getElementById('search-query').value.trim();
            if (query) {
                let [type, value] = translateQuery(query);
                fetchDynamicData(type, value);
            } else {
                alert('Please enter a search query.');
            }
        });
        //handle los datos desde un fetch js en vez de desde el controller con una ruta
        function fetchData(queryKey) {
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ data: queries[queryKey] })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);  // Log the response data

                // Clear existing markers
                map.eachLayer(function (layer) {
                    if (layer instanceof L.Marker && !pointsOfInterest.some(p => p.lat === layer.getLatLng().lat && p.lon === layer.getLatLng().lng)) {
                        map.removeLayer(layer);
                    }
                });

                // Check if `data.elements` is defined and is an array
                if (Array.isArray(data.elements)) {
                    // Add new markers for each item
                    data.elements.forEach(function (node) {
                        if (node.type === 'node') {
                            L.marker([node.lat, node.lon])
                                .addTo(map)
                                .bindPopup(node.tags.name || 'No Name')
                                .openPopup();
                        }
                    });
                } else {
                    console.warn('No data found or data format is incorrect.');
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('An error occurred while fetching data. Check the console for details.');
            });
        }

        function translateQuery(query) {
            const translations = {
                'restaurante': 'amenity=restaurant',
                'agua_potable': 'amenity=drinking_water',
                'combustible': 'amenity=fuel',
                'hotel': 'tourism=hotel',
                'aparcamientos': 'amenity=parking["parking"="surface"]',
                'estacionamiento': 'amenity=parking["parking"="surface"]',
                'parque': 'leisure=park',
                'biblioteca': 'amenity=library',
                'iglesia': 'amenity=place_of_worship',
                'museo': 'tourism=museum',
                'centro_de_salud': 'amenity=clinic',
                'farmacia': 'amenity=pharmacy',
                'restaurante_vegetariano': 'amenity=restaurant["cuisine"="vegetarian"]',
                'cafe': 'amenity=cafe',
                'escuela': 'amenity=school',
                'supermercado': 'shop=supermarket',
                'tienda': 'shop=*',
                'hospital': 'amenity=hospital',
                'baño': 'amenity=toilets',
                'zona_de_ocio': 'leisure=*',
                'sede': 'office=*',
            };
            //parse la respuesta a lower case y reemplazar espacios
            let translatedQuery = translations[query.toLowerCase()];
            if (!translatedQuery) {
                translatedQuery = `amenity=${query.replace(/ /g, '_')}`;
            }

            let [type, value] = translatedQuery.split('=');
            if (value.includes('[')) {
                value = value.replace(/[\[\]]/g, '');
                return [type, value];
            }
            return [type, value];
        }

        //buscar coordenadas de nuevos querys input
        function fetchDynamicData(type, value) {
            let dynamicQuery = `
                [out:json][timeout:25];
                node["${type}"="${value}"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
                out geom;
            `;
            //fetch the data into the API
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ data: dynamicQuery })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);  // Log the response data

                // Clear existing markers
                map.eachLayer(function (layer) {
                    if (layer instanceof L.Marker && !pointsOfInterest.some(p => p.lat === layer.getLatLng().lat && p.lon === layer.getLatLng().lng)) {
                        map.removeLayer(layer);
                    }
                });

                // Check if `data.elements` is defined and is an array
                if (Array.isArray(data.elements)) {
                    // Add new markers for each item
                    data.elements.forEach(function (node) {
                        if (node.type === 'node') {
                            L.marker([node.lat, node.lon])
                                .addTo(map)
                                .bindPopup(node.tags.name || 'No Name')
                                .openPopup();
                        }
                    });
                } else {
                    console.warn('No data found or data format is incorrect.');
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('An error occurred while fetching data. Check the console for details.');
            });
        }
    </script>
</body>
</html>
