@extends('admin.dashboard')
@section('admin')

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">SCM Facility Management</h3>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Add Facility
        </button>
    </div>

    {{-- ALERT --}}
    <div id="alertBox"></div>

    {{-- DROPDOWN PILIH FACILITY --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <label class="form-label fw-bold">Select Facility to Edit/Update</label>
            <select class="form-select" id="facilitySelector">
                <option value="">-- Select Facility --</option>
            </select>
        </div>
    </div>

    {{-- LIST TABLE --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped" id="facilityTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Loading Dock</th>
                        <th>Opening Date</th>
                        <th>Address</th>
                        <th>Demand</th>
                        <th>Capacity DO</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data auto loaded by AJAX --}}
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- =================== ADD MODAL =================== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addForm" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Facility Name</label>
                        <input type="text" class="form-control" name="Name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Loading Dock?</label>
                        <select class="form-select" name="Is_Loading_Dock">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Date</label>
                        <input type="date" class="form-control" name="Opening_Date">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="Alamat">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Demand DO</label>
                        <input type="number" class="form-control" name="Demand_DO">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacity DO</label>
                        <input type="number" class="form-control" name="Capacity_DO">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacity CBM</label>
                        <input type="number" class="form-control" name="Capacity_CBM">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>
</div>



{{-- =================== EDIT MODAL =================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" name="facility_ID" id="editFacilityID">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Facility Name</label>
                        <input type="text" class="form-control" name="Name" id="editName" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Loading Dock?</label>
                        <select class="form-select" name="Is_Loading_Dock" id="editDock">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Date</label>
                        <input type="date" class="form-control" name="Opening_Date" id="editDate">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="Alamat" id="editAlamat">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Demand DO</label>
                        <input type="number" class="form-control" name="Demand_DO" id="editDemand">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacity DO</label>
                        <input type="number" class="form-control" name="Capacity_DO" id="editCapacityDO">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacity CBM</label>
                        <input type="number" class="form-control" name="Capacity_CBM" id="editCBM">
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-warning">Update</button>
            </div>

        </form>
    </div>
</div>


@endsection


@section('scripts')
<script>
    const BASE_URL = "/admin/dashboard/transport/facility";


    /* ------------------------------
       LOAD TABLE DATA
    ------------------------------ */
    function loadData() {
        $.get(BASE_URL, function (response) {
            let rows = "";

            response.data.forEach((item, i) => {
                rows += `
                <tr>
                    <td>${i+1}</td>
                    <td>${item.Name}</td>
                    <td>${item.Is_Loading_Dock == 1 ? 'Yes' : 'No'}</td>
                    <td>${item.Opening_Date ?? '-'}</td>
                    <td>${item.Alamat ?? '-'}</td>
                    <td>${item.Demand_DO ?? '-'}</td>
                    <td>${item.Capacity_DO ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editData(${item.facility_ID})">Edit</button>
                    </td>
                </tr>
                `;
            });

            $("#facilityTable tbody").html(rows);
        });
    }



    /* ------------------------------
       LOAD DROPDOWN FACILITY
    ------------------------------ */
    function loadFacilityDropdown() {
        $.get(BASE_URL, function (response) {

            let options = `<option value="">-- Select Facility --</option>`;

            response.data.forEach(item => {
                options += `
                    <option value="${item.facility_ID}">
                        [${item.facility_ID}] - ${item.Name}
                    </option>
                `;
            });

            $("#facilitySelector").html(options);
        });
    }



    /* ------------------------------
       DROPDOWN TRIGGER EDIT
    ------------------------------ */
    $("#facilitySelector").change(function () {
        let id = $(this).val();
        if (id !== "") {
            editData(id);
        }
    });



    /* ------------------------------
       LOAD EDIT DATA
    ------------------------------ */
    function editData(id) {

        $.get(BASE_URL + "?facility_ID=" + id, function (res) {

            if (!res.data.length) {
                showAlert("danger", "Data not found!");
                return;
            }

            let d = res.data[0];

            $("#editFacilityID").val(d.facility_ID);
            $("#editName").val(d.Name);
            $("#editDock").val(d.Is_Loading_Dock);
            $("#editDate").val(d.Opening_Date);
            $("#editAlamat").val(d.Alamat);
            $("#editDemand").val(d.Demand_DO);
            $("#editCapacityDO").val(d.Capacity_DO);
            $("#editCBM").val(d.Capacity_CBM);

            $("#editModal").modal("show");
        });
    }



    /* ------------------------------
       UPDATE DATA
    ------------------------------ */
    $("#editForm").submit(function (e) {
        e.preventDefault();

        let id = $("#editFacilityID").val();

        $.ajax({
            url: BASE_URL + "/" + id,
            type: "PUT",
            data: $(this).serialize(),
            success: (res) => {
                $("#editModal").modal("hide");
                showAlert("success", res.message);
                loadData();
                loadFacilityDropdown();
            },
            error: () => {
                showAlert("danger", "Validation error");
            }
        });
    });



    /* ------------------------------
       ADD DATA
    ------------------------------ */
    $("#addForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: BASE_URL,
            type: "POST",
            data: $(this).serialize(),
            success: (res) => {
                $("#addModal").modal("hide");
                showAlert("success", res.message);
                loadData();
                loadFacilityDropdown();
            },
            error: () => {
                showAlert("danger", "Validation error");
            }
        });
    });



    /* ------------------------------
       ALERT HELPER
    ------------------------------ */
    function showAlert(type, msg) {
        $("#alertBox").html(`
            <div class="alert alert-${type} alert-dismissible fade show">
                ${msg}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }



    /* ------------------------------
       INIT ON LOAD
    ------------------------------ */
    loadData();
    loadFacilityDropdown();

</script>
@endsection
