document.addEventListener("DOMContentLoaded", function () {
    // Mendapatkan nilai dari elemen hidden input
    var selectedOwners = document
        .getElementById("selected_owners")
        .value.split(";");

    // Menandai semua checkbox yang sesuai dengan nilai dari hidden input
    document.querySelectorAll(".owner-checkbox").forEach(function (checkbox) {
        if (selectedOwners.includes(checkbox.value)) {
            checkbox.checked = true;
        }
    });

    // Menandai checkbox 'All' jika semua checkbox lainnya dicentang
    var checkAllElement = document.getElementById("check_all");
    if (checkAllElement) {
        checkAllElement.checked =
            selectedOwners.length ===
            document.querySelectorAll(".owner-checkbox").length;
    }
});

function toggleDropdown(event, dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    var isVisible = dropdown.style.display === "block";

    // Tutup dropdown kustom lainnya
    document.querySelectorAll(".dropdown-menu").forEach(function (menu) {
        if (
            menu.id !== dropdownId &&
            !menu.classList.contains("dropdown-menu-end")
        ) {
            menu.style.display = "none";
        }
    });

    // Tampilkan atau sembunyikan dropdown
    dropdown.style.display = isVisible ? "none" : "block";

    // Hentikan event agar tidak menyebar ke document
    event.stopPropagation();
}

// Menutup dropdown kustom jika mengklik di luar area dropdown
document.addEventListener("click", function (event) {
    // Cek apakah klik terjadi di luar area dropdown kustom (Ini bisa mempengaruhi boostrap dropdwon ya)
    if (
        !event.target.closest(".custom-dropdown-menu") &&
        !event.target.closest(".btn")
    ) {
        document
            .querySelectorAll(".custom-dropdown-menu")
            .forEach(function (menu) {
                menu.style.display = "none";
            });
    }
});

// Fungsi untuk memperbarui hidden input dan menutup dropdown
function closeCheckboxList() {
    var list = document.getElementById("owner-checkbox-list");

    if (list) {
        list.style.display = "none";

        var selectedOwners = [];

        // Mengumpulkan nilai dari checkbox yang dicentang
        document
            .querySelectorAll(".owner-checkbox:checked")
            .forEach(function (checkbox) {
                selectedOwners.push(checkbox.value.trim());
            });

        // Menggabungkan nilai dengan tanda titik koma
        var ownerString = selectedOwners.join(";");

        // Menyimpan nilai ke dalam elemen hidden input
        document.getElementById("selected_owners").value = ownerString;
    }
}

// Fungsi untuk menghapus centang dari semua checkbox
function clearCheckboxes() {
    var checkboxes = document.querySelectorAll(".owner-checkbox");
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });
    document.getElementById("check_all").checked = false;
    document.getElementById("selected_owners").value = ""; // Hapus nilai dari hidden input
}

// Fungsi untuk menangani checkbox "All"
var checkAllElement = document.getElementById("check_all");
if (checkAllElement) {
    checkAllElement.addEventListener("change", function () {
        var isChecked = this.checked;
        var checkboxes = document.querySelectorAll(".owner-checkbox");
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });

        // Update hidden input dengan semua nilai jika checkbox 'All' dicentang
        document.getElementById("selected_owners").value = isChecked
            ? Array.from(checkboxes)
                  .map((cb) => cb.value)
                  .join(";")
            : "";
    });
}
