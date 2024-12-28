document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("memberTable");
    const rows = table.getElementsByTagName("tr");

    // Prevent Enter key from submitting the form
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Stop form submission
        }
    });

    // Search functionality
    searchInput.addEventListener("keyup", function () {
        const filter = this.value.toLowerCase();

        // Loop through table rows and hide those that don't match the query
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            rows[i].style.display = found ? "" : "none";
        }
    });


    // Sorting functionality
    const headers = document.querySelectorAll("#memberTable th");
    const tableBody = document.querySelector("#memberTable tbody");
    const rowArray = Array.from(tableBody.rows);

    headers.forEach((header, index) => {
        header.addEventListener("click", () => {
            // Determine sorting order
            const isAscending = header.classList.contains("asc");
            const direction = isAscending ? -1 : 1;

            // Remove existing sort classes
            headers.forEach((th) => th.classList.remove("asc", "desc"));
            header.classList.toggle("asc", !isAscending);
            header.classList.toggle("desc", isAscending);

            // Sort rows
            rowArray.sort((a, b) => {
                const aText = a.cells[index].textContent.trim().toLowerCase();
                const bText = b.cells[index].textContent.trim().toLowerCase();

                return aText > bText ? direction : aText < bText ? -direction : 0;
            });

            // Rebuild the table body
            rowArray.forEach((row) => tableBody.appendChild(row));
        });
    });
});
