document.addEventListener("DOMContentLoaded", function () {
    // Mendapatkan nilai dari elemen hidden input
    var selecteddamage_from = document
        .getElementById("selected_damage_from")
        .value.split(";");

    // Menandai semua checkbox yang sesuai dengan nilai dari hidden input
    document
        .querySelectorAll(".damage_from-checkbox")
        .forEach(function (checkbox) {
            if (selecteddamage_from.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });

    // Menandai checkbox 'All' jika semua checkbox lainnya dicentang
    var checkAllElement = document.getElementById("check_all_damage_from");
    if (checkAllElement) {
        checkAllElement.checked =
            selecteddamage_from.length ===
            document.querySelectorAll(".damage_from-checkbox").length;
    }
});

function toggleDropdowndamage_from(event, dropdownId) {
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
function closeCheckboxListdamage_from() {
    var list = document.getElementById("damage_from-checkbox-list");

    if (list) {
        list.style.display = "none";

        var selecteddamage_from = [];

        // Mengumpulkan nilai dari checkbox yang dicentang
        document
            .querySelectorAll(".damage_from-checkbox:checked")
            .forEach(function (checkbox) {
                selecteddamage_from.push(checkbox.value.trim());
            });

        // Menggabungkan nilai dengan tanda titik koma
        var damage_fromtring = selecteddamage_from.join(";");

        // Menyimpan nilai ke dalam elemen hidden input
        document.getElementById("selected_damage_from").value =
            damage_fromtring;
    }
}

// Fungsi untuk menghapus centang dari semua checkbox
function clearCheckboxesdamage_from() {
    var checkboxes = document.querySelectorAll(".damage_from-checkbox");
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });
    document.getElementById("check_all_damage_from").checked = false;
    document.getElementById("selected_damage_from").value = ""; // Hapus nilai dari hidden input
}

// Fungsi untuk menangani checkbox "All"
var checkAllElement = document.getElementById("check_all_damage_from");
if (checkAllElement) {
    checkAllElement.addEventListener("change", function () {
        var isChecked = this.checked;
        var checkboxes = document.querySelectorAll(".damage_from-checkbox");
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });

        // Update hidden input dengan semua nilai jika checkbox 'All' dicentang
        document.getElementById("selected_damage_from").value = isChecked
            ? Array.from(checkboxes)
                  .map((cb) => cb.value)
                  .join(";")
            : "";
    });
}
