document.addEventListener("DOMContentLoaded", function () {
    const ministryLinks = document.querySelectorAll(".rating-nav a");
    const ministrySections = document.querySelectorAll(".rating-section");

    ministryLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            ministryLinks.forEach((l) => l.classList.remove("active"));
            ministrySections.forEach((section) => section.classList.remove("active"));

            this.classList.add("active");

            const ministryId = this.getAttribute("data-rating");
            document.getElementById(ministryId).classList.add("active");
        });
    });
});