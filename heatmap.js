document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('heatmap').setView([-30.2638, 29.9381], 10); // Focus on specific coordinates

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var heat = L.heatLayer([
        [-30.2638, 29.9381, 0.8], // Higher intensity
        [-30.2738, 29.9481, 0.6],
        [-30.2838, 29.9581, 0.9],
        [-30.2938, 29.9681, 0.7]
    ], {
        radius: 25,
        blur: 15,
        maxZoom: 17,
        max: 1.0
    }).addTo(map);

    // Optional: Add interaction features
    map.on('click', function(e) {
        var latlng = e.latlng;
        L.popup()
            .setLatLng(latlng)
            .setContent('Coordinates: ' + latlng.toString())
            .openOn(map);
    });
});



