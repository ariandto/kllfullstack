document.addEventListener("DOMContentLoaded", function () {
    // Cek apakah ada pesan pembatasan akses
    if (typeof pageRestrictedMessage !== "undefined" && pageRestrictedMessage) {
        Swal.fire({
            title: "Akses Ditolak!",
            text: pageRestrictedMessage,
            icon: "warning",
            confirmButtonColor: "#d33",
        });
    }
});
