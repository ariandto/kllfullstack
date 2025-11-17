{{-- Modal C --}}
<div class="modal fade" id="modalC" tabindex="-1" aria-labelledby="modalCLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCLabel">Detail Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <!-- Row 1: Data Header -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white fs-6">Header Project</h5>
                            <i class="fas fa-eye float-end toggle-section" data-target="#dataUtamaCard"></i>
                        </div>
                        <div class="card-body collapse" id="dataUtamaCard">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Application No</label>
                                    <input type="text" class="form-control" id="applicationNo" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Project Owner</label>
                                    <input type="text" class="form-control" id="projectOwner" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status Project</label>
                                    <input type="text" class="form-control" id="statusProject" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="projectName" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pilot Project</label>
                                    <input type="text" class="form-control" id="pilotProject" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Application Type</label>
                                    <input type="text" class="form-control" id="applicationType" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Row 2: Point 1 - Point 4 -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white fs-6">Detail Project</h5>
                            <i class="fas fa-eye float-end toggle-section" data-target="#pointDetailsCard"></i>
                        </div>
                        <div class="card-body collapse" id="pointDetailsCard">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 1</label>
                                    <textarea class="form-control" id="point1" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 2</label>
                                    <textarea class="form-control" id="point2" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 3</label>
                                    <textarea class="form-control" id="point3" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Point 4</label>
                                    <textarea class="form-control" id="point4" rows="2" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" style="font-style: italic;">Comment</label>
                                <div class="mb-2">
                                    <!-- Button Download -->
                                    <button type="button" class="btn btn-success btn-sm" id="btnDownload">
                                        <i class="mdi mdi-download"></i> Download
                                    </button>

                                </div>
                                <!-- File Lama (Current File di FTP) -->
                                <div>
                                    <strong>File Lama:</strong>
                                    <div id="attachment" class="text-muted">Tidak ada file</div>
                                </div>

                                <!-- Comment Lama -->
                                <div class="mb-2">
                                    <label class="fw-bold text-muted">Previous Comment</label>
                                    <textarea class="form-control bg-light text-muted border-secondary" id="comment" name="comment" rows="4"
                                        readonly></textarea>
                                </div>

                                <!-- Comment Baru -->
                                <div>
                                    <label class="fw-bold text-dark">New Comment</label>
                                    <textarea class="form-control border-primary" id="commentnew" name="commentnew" rows="4"></textarea>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div id="buttonContainer" class="mt-3 d-flex justify-content-center"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal A --}}
<div class="modal fade" id="modalA" tabindex="-1" aria-labelledby="modalALabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalALabel">Detail Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <!-- Row 1: Data Header -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white fs-6">Header Project</h5>
                            <i class="fas fa-eye float-end toggle-section" data-target="#dataUtamaCardA"></i>
                        </div>
                        <div class="card-body collapse" id="dataUtamaCardA">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Application No</label>
                                    <input type="text" class="form-control" id="applicationNoA" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="companyA" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Project Owner</label>
                                    <input type="text" class="form-control" id="projectOwnerA" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status Project</label>
                                    <input type="text" class="form-control" id="statusProjectA" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="projectNameA" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pilot Project</label>
                                    <input type="text" class="form-control" id="pilotProjectA" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Application Type</label>
                                    <input type="text" class="form-control" id="applicationTypeA" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Row 2: Point 1 - Point 4 -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white fs-6">Detail Project</h5>
                            <i class="fas fa-eye float-end toggle-section" data-target="#pointDetailsCardA"></i>
                        </div>
                        <div class="card-body collapse" id="pointDetailsCardA">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 1</label>
                                    <textarea class="form-control" id="point1A" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 2</label>
                                    <textarea class="form-control" id="point2A" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Point 3</label>
                                    <textarea class="form-control" id="point3A" rows="2" readonly></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Point 4</label>
                                    <textarea class="form-control" id="point4A" rows="2" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" style="font-style: italic;">Comment</label>
                                <div class="mb-2">
                                    <!-- Button Download -->
                                    <button type="button" class="btn btn-success btn-sm" id="btnDownloadA">
                                        <i class="mdi mdi-download"></i> Download
                                    </button>

                                </div>
                                <!-- File Lama (Current File di FTP) -->
                                <div>
                                    <strong>File Lama:</strong>
                                    <div id="attachmentA" class="text-muted">Tidak ada file</div>
                                </div>

                                <!-- Comment Lama -->
                                <div class="mb-2">
                                    <label class="fw-bold text-muted">Previous Comment</label>
                                    <textarea class="form-control bg-light text-muted border-secondary" id="commentA" name="comment" rows="4"
                                        readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div id="buttonContainerA" class="mt-3 d-flex justify-content-center"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal B --}}
<div class="modal fade" id="modalB" tabindex="-1" aria-labelledby="modalBLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBLabel">Assign Application Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    {{-- CARD BUSINESS IMPACT --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white py-2">Business Impact</div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Company Impact (Multiple Choice Dropdown) -->
                                <div class="col-md-3">

                                    <button type="button"
                                        class="form-control btn btn-light d-flex justify-content-between align-items-center"
                                        id="toggleCompanyImpact">
                                        All <span id="arrowIcon">▼</span>
                                    </button>

                                    <div id="companyImpactDropdown" class="dropdown-menu p-3"
                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="impact_all">
                                            <label class="form-check-label" for="impact_all">All</label>
                                        </div>

                                        @isset($getCompanyImpact)
                                            @foreach ($getCompanyImpact as $company)
                                                <div class="form-check">
                                                    <input class="form-check-input impact-option" type="checkbox"
                                                        value="{{ $company->Company }}" id="impact_{{ $loop->index }}">
                                                    <label class="form-check-label"
                                                        for="impact_{{ $loop->index }}">{{ $company->Company }}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted">Data tidak tersedia</div>
                                        @endisset
                                    </div>

                                    <input type="hidden" id="companyImpactValue" name="company_impact">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD ANALYST --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white py-2">Analyst</div>
                        <div class="card-body">
                            <div class="row align-items-center mb-2">
                                <div class="col-md-1">
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                        class="rounded-circle avatar-md" alt="Avatar">
                                </div>
                                <div class="col-md-3">
                                    <label for="nik_analyst" class="form-label">Analyst</label>
                                    <select class="form-select" id="nik_analyst">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="task_analyst" class="form-label">Active Task</label>
                                    <input type="text" class="form-control" id="task_analyst" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="available_analyst" class="form-label">Available Date</label>
                                    <input type="text" class="form-control" id="available_analyst" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label invisible">Detail</label>
                                    <button id="btn_detail_analyst" class="btn btn-primary w-100 btn-detail"
                                        data-type="analyst">Detail</button>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="start_analyst" class="form-label">Assignment Date</label>
                                    <input type="date" class="form-control" id="start_analyst"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_analyst" class="form-label">&nbsp;</label>
                                    <input type="date" class="form-control" id="end_analyst"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" onclick="addToGrid('analyst')">Add</button>
                                </div>
                            </div>

                            <table class="table table-bordered" id="table_analyst">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Analyst</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Lead Time (days)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- CARD DEVELOPER --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white py-2">Development</div>
                        <div class="card-body">
                            <div class="row align-items-center mb-2">
                                <div class="col-md-1">
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                        class="rounded-circle avatar-md" alt="Avatar">
                                </div>
                                <div class="col-md-3">
                                    <label for="nik_developer" class="form-label">Developer</label>
                                    <select class="form-select" id="nik_developer">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="task_developer" class="form-label">Active Task</label>
                                    <input type="text" class="form-control" id="task_developer" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="available_developer" class="form-label">Available Date</label>
                                    <input type="text" class="form-control" id="available_developer" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label invisible">Detail</label>
                                    <button id="btn_detail_developer" class="btn btn-primary w-100 btn-detail"
                                        data-type="developer">Detail</button>
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="col-md-3">
                                    <label for="start_developer" class="form-label">Assignment Date</label>
                                    <input type="date" class="form-control" id="start_developer"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_developer" class="form-label">&nbsp;</label>
                                    <input type="date" class="form-control" id="end_developer"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100"
                                        onclick="addToGrid('developer')">Add</button>
                                </div>
                            </div>

                            <table class="table table-bordered" id="table_developer">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Developer</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Lead Time (days)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="go_live_date" class="form-label">Go Live Date</label>
                            <input type="date" class="form-control" id="go_live_date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>


                    <input type="hidden" id="applicationNoB">
                    <input type="hidden" id="commentB">


                    <div class="d-flex mt-4 gap-2">
                        <button class="btn btn-primary w-50" onclick="submitModalB()">Submit</button>
                        <button class="btn btn-danger w-50" onclick="resetModalB()">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal D -->
<div class="modal fade" id="modalD" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Developer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="developer-detail-container">
                <!-- Detail akan masuk sini -->
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleCompanyImpact');
        const dropdown = document.getElementById('companyImpactDropdown');
        const arrowIcon = document.getElementById('arrowIcon');
        const allCheckbox = document.getElementById('impact_all');
        const checkboxes = document.querySelectorAll('.impact-option');
        const hiddenInput = document.getElementById('companyImpactValue');

        // Toggle dropdown on button click
        toggleButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the click from propagating to document
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            arrowIcon.textContent = dropdown.style.display === 'none' ? '▼' : '▲';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = toggleButton.contains(event.target) || dropdown.contains(event
                .target);
            if (!isClickInside) {
                dropdown.style.display = 'none';
                arrowIcon.textContent = '▼';
            }
        });

        // Handle "All" checkbox
        allCheckbox.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = allCheckbox.checked);
            updateHiddenInput();
        });

        // Handle individual checkboxes
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                allCheckbox.checked = [...checkboxes].every(c => c.checked);
                updateHiddenInput();
            });
        });

        // Update hidden input and toggle label
        function updateHiddenInput() {
            const selected = [...checkboxes].filter(cb => cb.checked).map(cb => cb.value);
            hiddenInput.value = selected.join(',');
            toggleButton.firstChild.textContent = selected.length > 0 ? selected.join(', ') : 'All';
        }
    });
</script>
