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

document.addEventListener("DOMContentLoaded", function () {
    let table = document.getElementById("shanyraqTable").getElementsByTagName("tbody")[0];

    let rows = Array.from(table.rows).map(row => {
        let firstHalf = parseInt(row.cells[1].textContent.trim()) || 0;
        let secondHalf = parseInt(row.cells[2].textContent.trim()) || 0;
        let overall = firstHalf + secondHalf;

        row.cells[3].textContent = overall; // Update the "Overall" column
        return { row, overall };
    });

    // Sort rows by overall points in descending order
    rows.sort((a, b) => b.overall - a.overall);

    // Reorder rows in the table
    rows.forEach(({ row }) => table.appendChild(row));
});
