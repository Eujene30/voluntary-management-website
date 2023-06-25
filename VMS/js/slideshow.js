var slideIndex = 0;
var slideTimeout = 0;
showSlides();

function showSlides() {
    var slides = document.getElementsByClassName("slide");

    for (var i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;

    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    slides[slideIndex - 1].style.display = "block";

    clearTimeout = setTimeout(showSlides, 6000);
}

    var slideImages = document.getElementsByClassName("slide");
    for (var i = 0; i < slideImages.length; i++) {
        slideImages[i].addEventListener("click", function () {
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        showSlides(); 
        setTimeout(showSlides, 6000);
    });
}

   
