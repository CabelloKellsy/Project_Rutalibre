document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll(".carousel-images img");
    const carouselContainer = document.querySelector(".carousel-images");
    let currentIndex = 0;
    const totalImages = images.length;

    function showNextImage() {
        currentIndex = (currentIndex + 1) % totalImages;
        const offset = -currentIndex * 100; // Desplaza en unidades de 100%
        carouselContainer.style.transform = `translateX(${offset}%)`;
    }

    setInterval(showNextImage, 6000); // Cambia de imagen cada 6 segundos
});


// Configura el mapa y desactiva el zoom en el scroll
const map = L.map('map', {
    scrollWheelZoom: false // Desactiva el zoom con el scroll
}).setView([40.7128, -74.0060], 2); // Coordenadas y zoom inicial

// Tile layer para el mapa
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Ejemplo de marcadores de destinos populares
const destinos = [
    { name: "Nueva York", coords: [40.7128, -74.0060] },
    { name: "París", coords: [48.8566, 2.3522] },
    { name: "Tokio", coords: [35.6895, 139.6917] },
    { name: "Sídney", coords: [-33.8688, 151.2093] }
];

// Agrega los marcadores al mapa
destinos.forEach(destino => {
    L.marker(destino.coords).addTo(map)
        .bindPopup(destino.name)
        .openPopup();
});


// Marcadores para diferentes ciudades
const locations = [
    { coords: [40.7128, -74.0060], name: 'Nueva York, EE.UU.' },
    { coords: [48.8566, 2.3522], name: 'París, Francia' },
    { coords: [35.6895, 139.6917], name: 'Tokio, Japón' },
    { coords: [-33.8688, 151.2093], name: 'Sídney, Australia' },
    { coords: [19.4326, -99.1332], name: 'Ciudad de México, México' },
    { coords: [51.5074, -0.1278], name: 'Londres, Reino Unido' },
    { coords: [34.0522, -118.2437], name: 'Los Ángeles, EE.UU.' },
    { coords: [55.7558, 37.6173], name: 'Moscú, Rusia' }
];

// Añadir cada marcador al mapa con un pop-up
locations.forEach(location => {
    L.marker(location.coords).addTo(map)
        .bindPopup(location.name);
});




//boton para volver al inicio
window.onscroll = function () {
    const button = document.getElementById('back-to-top');
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }
};

document.getElementById('back-to-top').onclick = function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
