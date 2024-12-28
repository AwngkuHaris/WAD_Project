document.addEventListener('DOMContentLoaded', () => {
    const summaryTableBody = document.getElementById('summaryTableBody');
    const summaryTimeFrame = document.getElementById('summaryTimeFrame');

    // Fetch and render transaction summary
    async function fetchAndRenderSummary(timeFrame = 'daily') {
        try {
            const response = await fetch(`/project_wad/backend/admin/get_summary.php?time_frame=${timeFrame}`);
            const data = await response.json();

            // Clear the existing table
            summaryTableBody.innerHTML = '';

            // Populate the table with new data
            data.forEach((row) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.date || row.week || row.month}</td>
                    <td>${row.total_transactions}</td>
                    <td>RM${parseFloat(row.total_amount).toFixed(2)}</td>
                `;
                summaryTableBody.appendChild(tr);
            });
        } catch (error) {
            console.error('Error fetching summary:', error);
        }
    }

    // Initial load
    fetchAndRenderSummary();

    // Update on dropdown change
    summaryTimeFrame.addEventListener('change', (event) => {
        fetchAndRenderSummary(event.target.value);
    });
});
