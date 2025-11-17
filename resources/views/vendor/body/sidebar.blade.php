<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu"> 
                {{-- Ini tulisan yang diatas menu --}}
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('vendor.dashboard') }}">
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
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-inventory">Inventory</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('admin.dashboard.inventory.monitoringbarangrusak', ['PageName' => 'dashboard-monitoring-hold-lpn']) }}">
                                        Monitoring Hold LPN
                                    </a>
                                </li>
                            </ul>
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-ga">GA</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('admin.dashboard.public.dashboardchecklist5r', ['PageName' => 'dashboard-checklist-5r']) }}">
                                        Dashboard Checklist 5R
                                    </a>
                                </li>
                            </ul>
                        </li>  --}}

                       
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-report">Report</span>
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
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-outbound">Outbound</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('admin.report.outbound.demandinoutbacklog', ['PageName' => 'report-demand-in-out-backlog']) }}">
                                        Report Demand In Out Backlog
                                    </a>
                                </li>
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
                                <span data-key="t-transport">Transport</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-inventory">Inventory</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('admin.report.inventory.throughput', ['PageName' => 'report-throughput-dc']) }}" >Report Throughput</a></li>
                           
                            </ul>
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-ga">GA</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                              
                            </ul>
                        </li>  --}}

                        {{-- <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('admin.report.public.reportchecklist5r', ['PageName' => 'report-checklist-5r']) }}">
                                        Report Checklist 5R
                                    </a>
                                </li>
                            </ul>
                        </li>  --}}
                       
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
                                <span data-key="t-inbound">Input Data VTMS</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li> 

                        {{-- <li>
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
                        </li> 

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-inventory">Inventory</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li> 

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-ga">GA</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li> 

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-public">Public</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                
                            </ul>
                        </li>  --}}
                       
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="database"></i>
                        <span data-key="t-master">Master</span>
                    </a>
                    {{-- <ul class="sub-menu" aria-expanded="false">  
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-public">Public</span>
                            </a> 
                            <ul class="sub-menu" aria-expanded="false"> 
                                <!-- Checklist 5R menjadi item dengan submenu --> 
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        Master User
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false"> 
                                        <li>
                                            <a href="{{route('vendor.register')}}">
                                                Registrasi User
                                            </a>
                                        </li> 
                                    </ul>
                                </li>  
                            </ul>  
                        </li> 
                      
                    </ul> --}}
                </li>
                
            </ul> 

        </div>
        <!-- Sidebar -->
    </div>
</div>


