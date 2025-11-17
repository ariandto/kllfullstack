$(document).ready(function () {
    // Function to initialize DataTable
    function initDataTable(tableId) {
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().destroy(); // Destroy existing DataTable
        }

        // Initialize DataTable with options
        return $(tableId).DataTable({
            scrollX: true,
            autoWidth: false,
            paging: true,
            responsive: false,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Export to Excel",
                    className: "d-none", // Hide the default button
                },
            ],
        });
    }

    // Initialize the first DataTable
    const table1 = initDataTable("#datatable");

    // Setup export button for the first DataTable
    $("#exportButton").on("click", function () {
        table1.button(".buttons-excel").trigger(); // Trigger Excel export
    });

    // Repeat for other DataTables as necessary
    const table2 = initDataTable("#datatable1"); // Initialize second DataTable
    const table3 = initDataTable("#datatable2"); // Initialize third DataTable

    // Setup export button for second and third DataTables if needed
    $("#exportButton2").on("click", function () {
        table2.button(".buttons-excel").trigger();
    });
    $("#exportButton3").on("click", function () {
        table3.button(".buttons-excel").trigger();
    });
});
