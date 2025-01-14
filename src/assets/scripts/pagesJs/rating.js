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

function calculateAndSort() {
    const table = document.getElementById("shanyraqTable");
    const rows = Array.from(table.querySelectorAll("tbody tr"));

    // Calculate overall points
    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        const points = Array.from(cells).slice(0, 4).map(cell => parseInt(cell.textContent || "0"));
        const overall = points.reduce((sum, point) => sum + point, 0);
        cells[4].textContent = overall; // Set overall column
    });

    // Sort rows by overall points (descending)
    rows.sort((a, b) => {
        const aOverall = parseInt(a.querySelector("td:last-child").textContent || "0");
        const bOverall = parseInt(b.querySelector("td:last-child").textContent || "0");
        return bOverall - aOverall;
    });

    // Re-append sorted rows to the table body
    const tbody = table.querySelector("tbody");
    rows.forEach(row => tbody.appendChild(row));
}

// Automatically calculate and sort on page load
window.addEventListener("DOMContentLoaded", calculateAndSort);