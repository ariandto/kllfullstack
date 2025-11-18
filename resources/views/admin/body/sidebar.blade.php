<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @php
                    $facilityInfo = session('facility_info', []);
                    $facilityID = $facilityInfo[0]['Facility_ID'] ?? '';
                    $RELASI = $facilityInfo[0]['Relasi'] ?? '';
                    // Ambil hanya "IND" dari relasi
                    $relasiSuffix = substr($RELASI, -3); // Mengambil 3 karakter terakhir dari Relasi
                @endphp
                {{-- Ini tulisan yang diatas menu --}}
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-apps">Home</span>
                    </a>
                </li>

                {{-- Ini Halaman Page Khusus Dashboard --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="log-in"></i>
                                <span data-key="t-inbound">Inbound</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li>



                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-mhe">MHE</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-qc">QC</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}


                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-planner">Planner</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}




                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="file-text"></i>
                                <span data-key="t-inventory">Inventory</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.inventory.monitoringbarangrusak', ['PageName' => 'dashboard-monitoring-hold-lpn']) }}">
                                        Monitoring Hold LPN
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-ga">GA</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="log-out"></i>
                                <span data-key="t-outbound">Outbound</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.outbound.autocancelodi', ['PageName' => 'dashboard-auto-cancel-odi']) }}">
                                        Auto Cancel ODI
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="cloud"></i>
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.public.dashboardchecklist5r', ['PageName' => 'dashboard-checklist-5r']) }}">
                                        Checklist 5R
                                    </a>
                                </li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.public.dashboardperformancemhe', ['PageName' => 'dashboard-performance-mhe']) }}">
                                        Dashboard Performance MHE
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="package"></i>
                                <span data-key="t-storing">Storing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.storing.dashboardcaseidopennonlc', ['Pagename' => 'Dashboard-CaseID-NonLC']) }}">
                                        {{-- <i data-feather="truck"></i> --}}
                                        CaseID Open NonLC
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.storing.monitoringstockbalikan', ['PageName' => 'Monitoring_Stock_balikan']) }}">
                                        Monitoring Stock Balikan
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('admin.dashboard.storing.picklist', ['PageName' => 'Monitoring_picklist_balikan']) }}">
                                        Monitoring Picklist Balikan
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="truck"></i>
                                <span data-key="t-transport">Transport</span>

                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('transport.inline-plan', ['Pagename' => 'Monitoring-Progress-LC']) }}">
                                        Monitoring Progress LC
                                    </a>
                                </li>
                            </ul>

                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('transport.dashboard', ['Pagename' => 'Dashboard-Transport']) }}">
                                        Dashboard Transport
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('transport.scm.profile') }}">
                                        SCM Transport Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('scmcrud.view') }}">
                                        SCM Transport Config
                                    </a>
                                </li>

                            </ul>
                        </li>







                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-report">Report</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="log-in"></i>
                                <span data-key="t-inbound">Inbound</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.report.inbound.monitoringreceievebalikan', ['PageName' => 'Report_Rceieve_Balikan']) }}">
                                        Report Receive Balikan
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="package"></i>
                                <span data-key="t-storing">Storing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-mhe">MHE</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-qc">QC</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}




                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-planner">Planner</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="truck"></i>
                                <span data-key="t-transport">Transport</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="file-text"></i>
                                <span data-key="t-inventory">Inventory</span>
                            </a>

                            <ul class="sub-menu" aria-expanded="false">
                                <li><a
                                        href="{{ route('admin.report.inventory.throughput', ['PageName' => 'report-throughput-dc']) }}">Report
                                        Throughput DC</a></li>

                            </ul>
                            @if ($facilityID === 'WMWHSE2' && $relasiSuffix === 'IND')
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a
                                            href="{{ route('admin.report.inventory.reportintercompany', ['PageName' => 'report-intercompany']) }}">Report
                                            Intercompany DC</a>
                                    </li>

                                </ul>
                            @endif

                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="log-out"></i>
                                <span data-key="t-outbound">Outbound</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.report.outbound.demandinoutbacklog', ['PageName' => 'report-demand-in-out-backlog']) }}">
                                        Report Demand In Out Backlog
                                    </a>
                                </li>
                            </ul>
                            @if ($facilityID === 'WMWHSE2' && $relasiSuffix === 'IND')
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a
                                            href="{{ route('admin.report.outbound.reportkontributorlppb', ['PageName' => 'report-kontributor-lppb']) }}">
                                            Report Kontributor LPPB
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-ga">GA</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                            </ul>
                        </li> --}}

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="cloud"></i>
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.report.public.reportchecklist5r', ['PageName' => 'report-checklist-5r']) }}">
                                        Report Checklist 5R
                                    </a>
                                </li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.report.public.reporttaskmhe', ['PageName' => 'report-task-mhe']) }}">
                                        Report Task MHE
                                    </a>
                                </li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.report.public.reportassetdc', ['PageName' => 'report-asset-dc']) }}">
                                        Report Asset DC
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="truck"></i>
                                <span data-key="t-transport">Transport</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('transport.dailyreport.show') }}">
                                        Report Daily Transport
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.transport.report') }}">
                                        Trend Daily Report
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('transport.summary-progress-lc') }}">
                                        Summary Progress LC
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('admin.report.transport.reportimestampprosesdclc', ['PageName' => 'report-timestampprosesdclc']) }}">
                                        Monitoring Time Stamp Proses DC
                                    </a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="repeat"></i>
                        <span data-key="t-transaction">Transaction</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        Maintenance
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li>
                                            <a
                                                href="{{ route('admin.maintenance.public.assignmentschedule', ['PageName' => 'maintenance-assignment-schedule']) }}">
                                                Assignment Schedule
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-storing">Storing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a
                                        href="{{ route('admin.transaction.storing.picklistbalikan', ['PageName' => 'Create_Picklist_balikan']) }}">
                                        Create Picklist Balikan
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li></li>
                </li>

            </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="database"></i>
                    <span data-key="t-master">Master</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <span data-key="t-public">Public</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">

                            {{-- <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-inbound">Inbound</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-storing">Storing</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-mhe">MHE</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-qc">QC</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-outbound">Outbound</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-planner">Planner</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-transport">Transport</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li> --}}

                            {{-- <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-inventory">Inventory</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a
                                            href="{{ route('admin.master.inventory.master_damage_from_lpn', ['PageName' => 'master_damage_from_lpn']) }}">
                                            Master LPN Damage From
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}

                            {{-- <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <span data-key="t-ga">GA</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">

                                </ul>
                            </li> --}}

                            <!-- Checklist 5R menjadi item dengan submenu -->
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    Checklist 5R
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a
                                            href="{{ route('admin.master.public.checklist5r.masterpoint5r', ['PageName' => 'master-point-5r']) }}">
                                            Master Point 5R
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ route('admin.master.public.checklist5r.masterjam5r', ['PageName' => 'master-jam-5r']) }}">
                                            Master Jam Checkpoint 5R
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    Master Aset
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a
                                            href="{{ route('admin.master.public.maintenance.masteraset', ['PageName' => 'master-asset-dc']) }}">
                                            Master Aset DC
                                        </a>
                                    </li>
                                </ul>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a
                                            href="{{ route('admin.master.public.maintenance.masterasetbattery', ['PageName' => 'master-asset-dc']) }}">
                                            Master Aset Battery
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    Master User
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('admin.register') }}">
                                            Registrasi User
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <span data-key="t-inventory">Inventory</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a
                                    href="{{ route('admin.master.inventory.master_damage_from_lpn', ['PageName' => 'master_damage_from_lpn']) }}">
                                    Master LPN Damage From
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <span data-key="t-config">Config</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a
                                    href="{{ route('admin.master.public.config.masteractivitymaintenance', ['PageName' => 'master_activity_maintenance']) }}">
                                    Master Activity Maintenance
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow" id="application-request">
                    <i data-feather="monitor"></i>
                    <span data-key="t-applicationrequest">Application Request</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>

                        <a href="{{ route('admin.ar.dashboard.view', ['PageName' => 'dashboard_ar']) }}">
                            <i data-feather="bar-chart"></i>
                            <span data-key="bar-chart">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ar.create_ar.view', ['PageName' => 'create_ar']) }}">
                            <i data-feather="edit-3"></i>
                            <span data-key="edit-3">Create AR</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ar.list_ar.view', ['PageName' => 'list_ar']) }}">
                            <i data-feather="file-text"></i>
                            <span data-key="file-text">List AR</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ar.task_ar.view', ['PageName' => 'task_ar']) }}">
                            <i data-feather="mail"></i>
                            <span data-key="mail">Task AR</span>
                            <span id="task-ar-total" class="badge bg-danger"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ar.reassign_ar.view', ['PageName' => 'reassign_ar']) }}">
                            <i data-feather="repeat"></i>
                            <span data-key="repeat">ReAssign</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ar.create_ar.view', ['PageName' => 'addklip_ar']) }}">
                            <i data-feather="plus-square"></i>
                            <span data-key="plus-square">Add KLIP</span>
                        </a>
                    </li>
                </ul>
            </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let applicationRequest = document.querySelector("#application-request");

        if (applicationRequest) {
            applicationRequest.addEventListener("click", function () {
                fetch("{{ route('admin.ar.total_ar') }}", {
                    credentials: "include", // Pastikan cookie session dikirim
                    headers: {
                        "X-Requested-With": "XMLHttpRequest" // Tandai sebagai AJAX request
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        let taskArElement = document.querySelector("#task-ar-total");

                        if (taskArElement && data.total.length > 0) {
                            taskArElement.textContent = data.total[0]
                                .TotalTask; // Ambil nilai TotalTask
                        } else {
                            taskArElement.textContent = "0"; // Default jika tidak ada data
                        }
                    })
                    .catch(error => console.error("Error fetching Task AR total:", error));
            });
        }
    });
</script>