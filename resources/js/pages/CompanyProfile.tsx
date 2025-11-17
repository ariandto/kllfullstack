import React, { useState, useEffect } from "react";
import {
    MapPin,
    Building2,
    Truck,
    Package,
    Phone,
    Calendar,
    Ruler,
    BarChart3,
    TrendingUp,
    AlertCircle,
} from "lucide-react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
    PieChart,
    Pie,
    Cell,
} from "recharts";
import './CompanyProfile.css';

interface Facility {
    Facility: string;
}

interface PivotResponse {
    [key: string]: any;
}

const CompanyProfile: React.FC = () => {
    const [facilities, setFacilities] = useState<Facility[]>([]);
    const [selectedFacility, setSelectedFacility] = useState<string>("");
    const [pivotData, setPivotData] = useState<PivotResponse | null>(null);
    const [loadingFacility, setLoadingFacility] = useState<boolean>(true);
    const [loadingPivot, setLoadingPivot] = useState<boolean>(false);

    //const API_URL = "http://localhost:8000";
    const API_URL = "https://scmlogisticapps.klgsys.com";

    useEffect(() => {
        const loadFacilities = async () => {
            try {
                const res = await fetch(`${API_URL}/transport/scm-profile`, {
                    credentials: "include",
                });
                const data = await res.json();
                setFacilities(data.facilities);
            } catch (error) {
                console.error("Error loading facility list:", error);
            } finally {
                setLoadingFacility(false);
            }
        };
        loadFacilities();
    }, []);

    const fetchPivotData = async (facilityName: string) => {
        setLoadingPivot(true);
        try {
            const res = await fetch(
                `${API_URL}/transport/scm-profile/armada?facility=${encodeURIComponent(
                    facilityName
                )}`,
                { credentials: "include" }
            );
            const data = await res.json();
            setPivotData(data.data[0] || null);
        } catch (error) {
            console.error("Error loading pivot data:", error);
        } finally {
            setLoadingPivot(false);
        }
    };

    const handleFacilityChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        const value = e.target.value;
        setSelectedFacility(value);
        setPivotData(null);
        if (value) fetchPivotData(value);
    };

    const imgUrl = pivotData?.Background_Image
    ? pivotData.Background_Image.startsWith("https")
        ? pivotData.Background_Image 
        : `${API_URL}/images/facilities/${pivotData.Background_Image}` // local file
    : null;

    return (
        <div
            style={{
                minHeight: "100vh",
                background: "linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%)",
            }}
        >
            <div className="content pb-5 pb-md-6">
                {/* Header */}
                <div className="mt-5">
                    <div className="d-flex align-items-center gap-2 mb-4">
                        <div className="p-2 bg-primary rounded">
                            <MapPin className="text-white" size={20} />
                        </div>

                        <h1 className="display-6 fw-bold text-dark mb-10">
                            SCM Transport Profile
                        </h1>
                    </div>
                    {/* <p className="text-muted ms-0 ms-md-5 ps-0 ps-md-2 small">Facility and Fleet Management System</p> */}
                </div>

                {/* Facility Selector Card */}
                <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 mb-4">
                    <div className="card-body p-3 p-md-4">
                        <label className="form-label fw-semibold text-dark mb-2 mb-md-3">
                            Select Facility
                        </label>

                        {loadingFacility ? (
                            <div className="d-flex align-items-center gap-2 text-muted">
                                <div
                                    className="spinner-border spinner-border-sm text-primary"
                                    role="status"
                                >
                                    <span className="visually-hidden">
                                        Loading...
                                    </span>
                                </div>
                                <span>Loading facilities...</span>
                            </div>
                        ) : (
                            <select
                                className="form-select form-select-lg shadow-sm"
                                value={selectedFacility}
                                onChange={handleFacilityChange}
                                style={{ borderWidth: "2px" }}
                            >
                                <option value="">-- Choose Facility --</option>
                                {facilities.map((f, idx) => (
                                    <option key={idx} value={f.Facility}>
                                        {f.Facility}
                                    </option>
                                ))}
                            </select>
                        )}
                    </div>
                </div>

                {/* Empty State */}
                {!selectedFacility && !loadingPivot && (
                    <div className="card shadow-sm border-0 rounded-4">
                        <div className="card-body text-center py-5">
                            <Building2
                                className="mx-auto mb-3 text-muted"
                                size={64}
                            />
                            <p className="text-muted fs-5 mb-0">
                                Please select a facility to view details
                            </p>
                        </div>
                    </div>
                )}

                {/* Loading State */}
                {loadingPivot && (
                    <div className="card shadow-sm border-0 rounded-4">
                        <div className="card-body text-center py-5">
                            <div
                                className="spinner-border text-primary mb-3"
                                role="status"
                            >
                                <span className="visually-hidden">
                                    Loading...
                                </span>
                            </div>
                            <p className="text-primary fw-semibold mb-0">
                                Loading facility data...
                            </p>
                        </div>
                    </div>
                )}

                {/* Facility Data */}
                {pivotData && (
                    <div className="vstack gap-4">
                        {/* Hero Section with Image */}
                        <div className="card shadow border-0 rounded-4 overflow-hidden">
                            {imgUrl && (
                                <div
                                    className="position-relative"
                                    style={{
                                        height: "200px",
                                        minHeight: "200px",
                                    }}
                                >
                                    <img
                                        src={imgUrl}
                                        alt="Facility"
                                        className="w-100 h-100 object-fit-cover"
                                        style={{ objectFit: "cover" }}
                                    />
                                    <div
                                        className="position-absolute top-0 start-0 w-100 h-100"
                                        style={{
                                            background:
                                                "linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 50%, transparent 100%)",
                                        }}
                                    ></div>

                                    <div className="position-absolute bottom-0 start-0 end-0 text-white p-3 p-md-4">
                                        <h2 className="fs-4 fs-md-3 fw-bold mb-1 mb-md-2">
                                            {pivotData.NAME}
                                        </h2>
                                        <div className="d-flex align-items-center gap-2">
                                            <Truck size={18} />
                                            <span className="fs-6 fs-md-5">
                                                {pivotData.Relasi_Armada}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Basic Info Grid */}
                            <div className="card-body p-3 p-md-4">
                                <div className="row g-3 g-md-4">
                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-primary bg-opacity-10 rounded flex-shrink-0">
                                                <Building2
                                                    className="text-primary"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Facility ID
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.FACILITY_ID}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-success bg-opacity-10 rounded flex-shrink-0">
                                                <Package
                                                    className="text-success"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Type
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.TYPE}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-warning bg-opacity-10 rounded flex-shrink-0">
                                                <MapPin
                                                    className="text-warning"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Zone
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.zone}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-info bg-opacity-10 rounded flex-shrink-0">
                                                <Calendar
                                                    className="text-info"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Opening Date
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.Opening_Date}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-secondary bg-opacity-10 rounded flex-shrink-0">
                                                <Truck
                                                    className="text-secondary"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Loading Dock
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.Is_Loading_Dock}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-12 col-sm-6 col-lg-4">
                                        <div className="d-flex align-items-start gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-dark bg-opacity-10 rounded flex-shrink-0">
                                                <Phone
                                                    className="text-dark"
                                                    size={20}
                                                />
                                            </div>
                                            <div className="flex-grow-1 min-w-0">
                                                <p className="text-muted small mb-1">
                                                    Phone
                                                </p>
                                                <p className="fw-semibold text-dark mb-0 text-truncate">
                                                    {pivotData.Telp}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Detailed Information */}
                        <div className="row g-4">
                            {/* Summary Cards */}
                            <div className="col-12">
                                <div className="row g-3 g-md-4">
                                    {/* Demand vs Capacity Card */}
                                    <div className="col-12 col-md-4">
                                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100 border-start border-primary border-4">
                                            <div className="card-body p-3 p-md-4">
                                                <div className="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <p className="text-muted small mb-1">
                                                            Capacity Utilization
                                                        </p>
                                                        <h3 className="fw-bold text-dark mb-0">
                                                            {pivotData.Capacity_DO >
                                                            0
                                                                ? Math.round(
                                                                      (pivotData.Demand_DO /
                                                                          pivotData.Capacity_DO) *
                                                                          100
                                                                  )
                                                                : 0}
                                                            %
                                                        </h3>
                                                    </div>
                                                    <div className="p-2 bg-primary bg-opacity-10 rounded">
                                                        <TrendingUp
                                                            className="text-primary"
                                                            size={20}
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    className="progress"
                                                    style={{ height: "8px" }}
                                                >
                                                    <div
                                                        className="progress-bar bg-primary"
                                                        role="progressbar"
                                                        style={{
                                                            width: `${
                                                                pivotData.Capacity_DO >
                                                                0
                                                                    ? Math.min(
                                                                          (pivotData.Demand_DO /
                                                                              pivotData.Capacity_DO) *
                                                                              100,
                                                                          100
                                                                      )
                                                                    : 0
                                                            }%`,
                                                        }}
                                                    ></div>
                                                </div>
                                                <p className="text-muted small mb-0 mt-2">
                                                    {pivotData.Demand_DO} /{" "}
                                                    {pivotData.Capacity_DO} DO
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Total Fleet Card */}
                                    <div className="col-12 col-md-4">
                                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100 border-start border-success border-4">
                                            <div className="card-body p-3 p-md-4">
                                                <div className="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <p className="text-muted small mb-1">
                                                            Total Asset
                                                        </p>
                                                        <h3 className="fw-bold text-dark mb-0">
                                                            {(() => {
                                                                const excluded =
                                                                    [
                                                                        "FACILITY_ID",
                                                                        "NAME",
                                                                        "TYPE",
                                                                        "Relasi_Armada",
                                                                        "zone",
                                                                        "Is_Loading_Dock",
                                                                        "Opening_Date",
                                                                        "Area_Staging_P",
                                                                        "Area_Staging_L",
                                                                        "Area_Staging_T",
                                                                        "Area_Loading_L",
                                                                        "Area_Loading_P",
                                                                        "Alamat",
                                                                        "Demand_DO",
                                                                        "Capacity_DO",
                                                                        "Capacity_CBM",
                                                                        "NIK_Leader",
                                                                        "Telp",
                                                                        "Background_Image",
                                                                    ];
                                                                const armadaKeys =
                                                                    Object.keys(
                                                                        pivotData
                                                                    ).filter(
                                                                        (
                                                                            key
                                                                        ) => {
                                                                            if (
                                                                                excluded.includes(
                                                                                    key
                                                                                )
                                                                            )
                                                                                return false;
                                                                            const value =
                                                                                pivotData[
                                                                                    key
                                                                                ];
                                                                            return (
                                                                                !isNaN(
                                                                                    parseFloat(
                                                                                        value
                                                                                    )
                                                                                ) &&
                                                                                isFinite(
                                                                                    value
                                                                                )
                                                                            );
                                                                        }
                                                                    );
                                                                return armadaKeys.reduce(
                                                                    (
                                                                        sum,
                                                                        key
                                                                    ) =>
                                                                        sum +
                                                                        (parseFloat(
                                                                            pivotData[
                                                                                key
                                                                            ]
                                                                        ) || 0),
                                                                    0
                                                                );
                                                            })()}
                                                        </h3>
                                                    </div>
                                                    <div className="p-2 bg-success bg-opacity-10 rounded">
                                                        <Truck
                                                            className="text-success"
                                                            size={20}
                                                        />
                                                    </div>
                                                </div>
                                                <p className="text-muted small mb-0">
                                                    Vehicles Asset
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* CBM Capacity Card */}
                                    <div className="col-12 col-md-4">
                                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100 border-start border-info border-4">
                                            <div className="card-body p-3 p-md-4">
                                                <div className="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <p className="text-muted small mb-1">
                                                            CBM Capacity
                                                        </p>
                                                        <h3 className="fw-bold text-dark mb-0">
                                                            {
                                                                pivotData.Capacity_CBM
                                                            }
                                                        </h3>
                                                    </div>
                                                    <div className="p-2 bg-info bg-opacity-10 rounded">
                                                        <Package
                                                            className="text-info"
                                                            size={20}
                                                        />
                                                    </div>
                                                </div>
                                                <p className="text-muted small mb-0">
                                                    Cubic Meter
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Charts Row */}
                            <div className="col-12 col-lg-6">
                                <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100">
                                    <div className="card-body p-3 p-md-4">
                                        <h5 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                            <BarChart3
                                                className="text-primary"
                                                size={20}
                                            />
                                            <span className="fs-6 fs-md-5">
                                                Demand vs Capacity
                                            </span>
                                        </h5>
                                        <ResponsiveContainer
                                            width="100%"
                                            height={250}
                                        >
                                            <BarChart
                                                data={[
                                                    {
                                                        name: "Demand",
                                                        value: pivotData.Demand_DO,
                                                        fill: "#0d6efd",
                                                    },
                                                    {
                                                        name: "Capacity",
                                                        value: pivotData.Capacity_DO,
                                                        fill: "#6c757d",
                                                    },
                                                ]}
                                            >
                                                <CartesianGrid strokeDasharray="3 3" />
                                                <XAxis dataKey="name" />
                                                <YAxis />
                                                <Tooltip />
                                                <Bar
                                                    dataKey="value"
                                                    radius={[8, 8, 0, 0]}
                                                />
                                            </BarChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                            </div>

                            {/* Fleet Distribution Pie Chart */}
                            <div className="col-12 col-lg-6">
                                <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100">
                                    <div className="card-body p-3 p-md-4">
                                        <h5 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                            <Truck
                                                className="text-primary"
                                                size={20}
                                            />
                                            <span className="fs-6 fs-md-5">
                                                Type Armada
                                            </span>
                                        </h5>
                                        {(() => {
                                            const excluded = [
                                                "FACILITY_ID",
                                                "NAME",
                                                "TYPE",
                                                "Relasi_Armada",
                                                "zone",
                                                "Is_Loading_Dock",
                                                "Opening_Date",
                                                "Area_Staging_P",
                                                "Area_Staging_L",
                                                "Area_Staging_T",
                                                "Area_Loading_L",
                                                "Area_Loading_P",
                                                "Alamat",
                                                "Demand_DO",
                                                "Capacity_DO",
                                                "Capacity_CBM",
                                                "NIK_Leader",
                                                "Telp",
                                                "Background_Image",
                                            ];
                                            const armadaKeys = Object.keys(
                                                pivotData
                                            ).filter((key) => {
                                                if (excluded.includes(key))
                                                    return false;
                                                const value = pivotData[key];
                                                return (
                                                    !isNaN(parseFloat(value)) &&
                                                    isFinite(value) &&
                                                    parseFloat(value) > 0
                                                );
                                            });

                                            const chartData = armadaKeys.map(
                                                (key) => ({
                                                    name: key,
                                                    value:
                                                        parseFloat(
                                                            pivotData[key]
                                                        ) || 0,
                                                })
                                            );

                                            const COLORS = [
                                                "#0d6efd",
                                                "#198754",
                                                "#ffc107",
                                                "#dc3545",
                                                "#0dcaf0",
                                                "#6c757d",
                                                "#d63384",
                                                "#fd7e14",
                                            ];

                                            return chartData.length > 0 ? (
                                                <>
                                                    <ResponsiveContainer
                                                        width="100%"
                                                        height={240}
                                                    >
                                                        <PieChart>
                                                            <Pie
                                                                data={chartData}
                                                                cx="50%"
                                                                cy="50%"
                                                                innerRadius={40}
                                                                outerRadius={80}
                                                                fill="#8884d8"
                                                                dataKey="value"
                                                                labelLine={true} // garis informatif
                                                                label={({
                                                                    name,
                                                                    value,
                                                                    percent,
                                                                }) =>
                                                                    `${name} (${value})`
                                                                }
                                                            >
                                                                {chartData.map(
                                                                    (
                                                                        entry,
                                                                        index
                                                                    ) => (
                                                                        <Cell
                                                                            key={`cell-${index}`}
                                                                            fill={
                                                                                COLORS[
                                                                                    index %
                                                                                        COLORS.length
                                                                                ]
                                                                            }
                                                                        />
                                                                    )
                                                                )}
                                                            </Pie>
                                                            <Tooltip />
                                                        </PieChart>
                                                    </ResponsiveContainer>

                                                    <div className="row g-2 mt-2">
                                                        {chartData.map(
                                                            (item, idx) => (
                                                                <div
                                                                    key={idx}
                                                                    className="col-6"
                                                                >
                                                                    <div className="d-flex align-items-center gap-2">
                                                                        <div
                                                                            style={{
                                                                                width: "12px",
                                                                                height: "12px",
                                                                                backgroundColor:
                                                                                    COLORS[
                                                                                        idx %
                                                                                            COLORS.length
                                                                                    ],
                                                                                borderRadius:
                                                                                    "2px",
                                                                            }}
                                                                        ></div>
                                                                        <small className="text-muted text-truncate">
                                                                            {
                                                                                item.name
                                                                            }
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            )
                                                        )}
                                                    </div>
                                                </>
                                            ) : (
                                                <div className="text-center py-4">
                                                    <AlertCircle
                                                        className="mx-auto mb-2 text-muted"
                                                        size={40}
                                                    />
                                                    <p className="text-muted mb-0 small">
                                                        No fleet data to display
                                                    </p>
                                                </div>
                                            );
                                        })()}
                                    </div>
                                </div>
                            </div>

                            {/* Capacity & Demand */}
                            <div className="col-12 col-lg-6">
                                <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100">
                                    <div className="card-body p-3 p-md-4">
                                        <h5 className="fw-bold text-dark mb-3 mb-md-4 d-flex align-items-center gap-2">
                                            <Package
                                                className="text-primary"
                                                size={20}
                                            />
                                            <span className="fs-6 fs-md-5">
                                                Capacity & Demand
                                            </span>
                                        </h5>

                                        <div className="vstack gap-2 gap-md-3">
                                            <div className="d-flex justify-content-between align-items-center p-2 p-md-3 bg-light rounded">
                                                <span className="text-muted small">
                                                    Demand DO
                                                </span>
                                                <span className="fw-bold text-dark">
                                                    {pivotData.Demand_DO}
                                                </span>
                                            </div>
                                            <div className="d-flex justify-content-between align-items-center p-2 p-md-3 bg-light rounded">
                                                <span className="text-muted small">
                                                    Capacity DO
                                                </span>
                                                <span className="fw-bold text-dark">
                                                    {pivotData.Capacity_DO}
                                                </span>
                                            </div>
                                            <div className="d-flex justify-content-between align-items-center p-2 p-md-3 bg-light rounded">
                                                <span className="text-muted small">
                                                    Capacity CBM
                                                </span>
                                                <span className="fw-bold text-dark">
                                                    {pivotData.Capacity_CBM}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Area Information */}
                            <div className="col-12 col-lg-6">
                                <div className="card shadow-sm border-0 rounded-3 rounded-lg-4 h-100">
                                    <div className="card-body p-3 p-md-4">
                                        <h5 className="fw-bold text-dark mb-3 mb-md-4 d-flex align-items-center gap-2">
                                            <Ruler
                                                className="text-primary"
                                                size={20}
                                            />
                                            <span className="fs-6 fs-md-5">
                                                Area Specifications
                                            </span>
                                        </h5>

                                        <div className="vstack gap-2 gap-md-3">
                                            <div className="p-2 p-md-3 bg-light rounded">
                                                <p className="text-muted small mb-1 mb-md-2">
                                                    Area Staging (P × L × T)
                                                </p>
                                                <p className="fw-bold text-dark mb-0 small">
                                                    {pivotData.Area_Staging_P} ×{" "}
                                                    {pivotData.Area_Staging_L} ×{" "}
                                                    {pivotData.Area_Staging_T}
                                                </p>
                                            </div>
                                            <div className="p-2 p-md-3 bg-light rounded">
                                                <p className="text-muted small mb-1 mb-md-2">
                                                    Area Loading (L × P)
                                                </p>
                                                <p className="fw-bold text-dark mb-0 small">
                                                    {pivotData.Area_Loading_L} ×{" "}
                                                    {pivotData.Area_Loading_P}
                                                </p>
                                            </div>
                                            <div className="p-2 p-md-3 bg-light rounded">
                                                <p className="text-muted small mb-1 mb-md-2">
                                                    Leader NIK
                                                </p>
                                                <p className="fw-bold text-dark mb-0 small">
                                                    {pivotData.NIK_Leader}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Address */}
                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4">
                            <div className="card-body p-3 p-md-4">
                                <h5 className="fw-bold text-dark mb-2 mb-md-3 d-flex align-items-center gap-2">
                                    <MapPin
                                        className="text-primary"
                                        size={20}
                                    />
                                    <span className="fs-6 fs-md-5">
                                        Address
                                    </span>
                                </h5>
                                <p className="text-dark small mb-0">
                                    {pivotData.Alamat}
                                </p>
                            </div>
                        </div>

                        {/* Location Map */}
                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4">
                            <div className="card-body p-3 p-md-4">
                                <h5 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                    <MapPin
                                        className="text-primary"
                                        size={20}
                                    />
                                    <span className="fs-6 fs-md-5">
                                        Location Map
                                    </span>
                                </h5>

                                {pivotData?.Alamat ? (
                                    <div
                                        className="rounded overflow-hidden"
                                        style={{ height: "300px" }}
                                    >
                                        <iframe
                                            width="100%"
                                            height="100%"
                                            style={{ border: 0 }}
                                            loading="lazy"
                                            allowFullScreen
                                            referrerPolicy="no-referrer-when-downgrade"
                                            src={`https://www.google.com/maps?q=${encodeURIComponent(
                                                pivotData.Alamat
                                            )}&output=embed`}
                                        ></iframe>
                                    </div>
                                ) : (
                                    <p className="text-muted small">
                                        No address available
                                    </p>
                                )}
                            </div>
                        </div>
                        {/* Fleet Availability */}
                        <div className="card shadow-sm border-0 rounded-3 rounded-lg-4">
                            <div className="card-body p-3 p-md-4">
                                <h5 className="fw-bold text-dark mb-3 mb-md-4 d-flex align-items-center gap-2">
                                    <Truck className="text-primary" size={20} />
                                    <span className="fs-6 fs-md-5">
                                        Asset Armada
                                    </span>
                                </h5>

                                {(() => {
                                    const excluded = [
                                        "FACILITY_ID",
                                        "NAME",
                                        "TYPE",
                                        "Relasi_Armada",
                                        "zone",
                                        "Is_Loading_Dock",
                                        "Opening_Date",
                                        "Area_Staging_P",
                                        "Area_Staging_L",
                                        "Area_Staging_T",
                                        "Area_Loading_L",
                                        "Area_Loading_P",
                                        "Alamat",
                                        "Demand_DO",
                                        "Capacity_DO",
                                        "Capacity_CBM",
                                        "NIK_Leader",
                                        "Telp",
                                        "Background_Image",
                                    ];

                                    const armadaKeys = Object.keys(
                                        pivotData
                                    ).filter((key) => {
                                        if (excluded.includes(key))
                                            return false;
                                        const value = pivotData[key];
                                        return (
                                            !isNaN(parseFloat(value)) &&
                                            isFinite(value)
                                        );
                                    });

                                    return armadaKeys.length > 0 ? (
                                        <div className="row g-2 g-md-3">
                                            {armadaKeys.map((key, i) => (
                                                <div
                                                    key={i}
                                                    className="col-12 col-sm-6 col-lg-4"
                                                >
                                                    <div className="d-flex justify-content-between align-items-center p-2 p-md-3 bg-light rounded border-start border-primary border-3 border-md-4">
                                                        <span className="fw-medium text-dark small text-truncate pe-2">
                                                            {key}
                                                        </span>
                                                        <span className="fs-6 fs-md-5 fw-bold text-primary flex-shrink-0">
                                                            {pivotData[key] ??
                                                                0}
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <div className="text-center py-4">
                                            <Truck
                                                className="mx-auto mb-3 text-muted"
                                                size={40}
                                            />
                                            <p className="text-muted mb-0 small">
                                                No fleet data available
                                            </p>
                                        </div>
                                    );
                                })()}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default CompanyProfile;
