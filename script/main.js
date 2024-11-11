document.addEventListener("DOMContentLoaded", function() {
    let currentIndex = 0;
    const images = document.querySelectorAll(".carousel-images img");
    const totalImages = images.length;

    function showNextImage() {
        currentIndex = (currentIndex + 1) % totalImages;
        const offset = -currentIndex * 100; // Mover el carrusel
        document.querySelector(".carousel-images").style.transform = `translateX(${offset}%)`;
    }

    setInterval(showNextImage, 3000); // Cambia de imagen cada 3 segundos
});
