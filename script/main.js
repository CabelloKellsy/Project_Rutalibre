document.addEventListener("DOMContentLoaded", function() {
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
