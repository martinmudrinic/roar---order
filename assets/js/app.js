/*
@ASSESSME.USERID: mm2945
@ASSESSME.AUTHOR: Martin Mudrinic
@ASSESSME.DESCRIPTION: JavaScript animations and interactive effects for Roar & Order.
@ASSESSME.ANALYZE: YES
*/

document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".pizza-card");

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, {
        threshold: 0.2
    });

    cards.forEach(function (card) {
        observer.observe(card);
    });

    const hero = document.querySelector(".hero");

    if (hero) {
        window.addEventListener("scroll", function () {
            const scrollPosition = window.scrollY;
            hero.style.backgroundPositionY = scrollPosition * 0.4 + "px";
        });
    }

    const buttons = document.querySelectorAll(".btn");

    buttons.forEach(function (button) {
        button.addEventListener("mousemove", function (event) {
            const rect = button.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            button.style.setProperty("--x", x + "px");
            button.style.setProperty("--y", y + "px");
        });
    });
});