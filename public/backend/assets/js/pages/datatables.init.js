$(document).ready(function () {
    $("#datatable").DataTable(),
        $("#datatable-buttons")
            .DataTable({
                lengthChange: !1,
                buttons: ["copy", "excel", "pdf", "colvis"],
            })
            .buttons()
            .container()
            .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
        $(".dataTables_length select").addClass("form-select form-select-sm");

    // $("#scroll-vertical-datatable").DataTable({
    //     scrollY: "300px", // Tinggi area scroll
    //     scrollCollapse: true, // Mengizinkan scroll collapsible
    //     paging: false, // Menonaktifkan pagination
    //     responsive: true, // Responsif
    // });

    // $("#scroll-horizontal-datatable").DataTable({
    //     scrollX: true, // Mengizinkan scroll horizontal
    //     paging: true, // Mengizinkan pagination jika diinginkan
    //     responsive: false, // Responsif
    //     autoWidth: false,
    // });
});
