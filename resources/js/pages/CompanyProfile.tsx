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
    Users,
    Activity,
    Target,
    Award,
    Zap,
    Shield,
    MessageCircle,

} from "lucide-react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    PieChart,
    Pie,
    Cell,
} from "recharts";
import API_URL from "../config/api";

const CompanyProfile = () => {
    const [facilities, setFacilities] = useState<any[]>([]);
    const [selectedFacility, setSelectedFacility] = useState<string>("");
    const [pivotData, setPivotData] = useState<any | null>(null);
    const [loadingFacility, setLoadingFacility] = useState<boolean>(true);
    const [loadingPivot, setLoadingPivot] = useState<boolean>(false);
    const [jalurData, setJalurData] = useState<any[]>([]);
    const [showDetailModal, setShowDetailModal] = useState(false);

    // Normalisasi otomatis area ke: Dalam Kota / Luar Kota
    // Normalisasi otomatis area ke: Dalam Kota / Luar Kota
    const normalizeAreaType = (areaName: string) => {
        if (!areaName) return "";

        const text = String(areaName).toLowerCase().trim();

        // Semua variasi: "dalam kota", "Dalam Kota", "DALAM KOTA", dll
        if (text.includes("dalam kota")) {
            return "Dalam Kota";
        } else if (text.includes("luar kota")) {
            return "Luar Kota";
        }
        return areaName;
    };

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

    const fetchPivotData = async (facilityName: string): Promise<void> => {
        setLoadingPivot(true);
        try {
            const res = await fetch(
                `${API_URL}/transport/scm-profile/armada?facility=${encodeURIComponent(
                    facilityName
                )}`,
                { credentials: "include" }
            );
            const data = await res.json();
            setPivotData({
                ...data.facility_detail[0],
                armada: data.data ?? [],
            });

            setJalurData(data.jalur || []);
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
            : `${API_URL}/images/facilities/${pivotData.Background_Image}`
        : null;
    const demand = pivotData?.Demand_DO ?? 0;
const capacity = pivotData?.Capacity_DO ?? 0;
   const utilizationRate =
    capacity > 0 ? Math.round((demand / capacity) * 100) : 0;

let condition = "";
let conditionColor = ""; // untuk tampilan UI

if (demand > capacity) {
    condition = "Overload";
    conditionColor = "text-danger fw-bold"; // merah
} else if (demand === capacity) {
    condition = "Ideal";
    conditionColor = "text-success fw-bold"; // hijau
} else {
    condition = "Underload";
    conditionColor = "text-warning fw-bold"; // kuning
}

    const getUtilizationColor = (rate: number) => {
        if (rate >= 90) return "text-danger";
        if (rate >= 70) return "text-warning";
        return "text-success";
    };

    const getUtilizationBg = (rate: number) => {
        if (rate >= 90) return "bg-danger";
        if (rate >= 70) return "bg-warning";
        return "bg-success";
    };

    const prepareJalurChartData = () => {
        if (!jalurData.length) return [];

        const countMap: Record<string, number> = {};

        jalurData.forEach((j: any) => {
            const name = String(j?.jalur ?? "");
            if (!name) return;
            countMap[name] = (countMap[name] || 0) + 1;
        });

        return Object.keys(countMap).map((k) => ({
            name: k,
            value: countMap[k] ?? 0,
        }));
    };

    return (
        <div
            style={{
                minHeight: "100vh",
                background:
                    "linear-gradient(135deg, #fffcfcff 0%, #f1e8e8ff 100%)",
            }}
        >
            <div className="content container-fluid px-3 px-md-5 mb-5 py-4">
                {/* Premium Header */}
                <div className="mb-4">
                    <div className="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h1 className="display-5 fw-bold text-dark mb-2">
                                SCM Transport Profile
                            </h1>
                        </div>
                    </div>
                </div>

                {/* Facility Selector - Premium Style */}
                <div
                    className="card shadow-lg border-0 rounded-4 mb-4"
                    style={{
                        background: "rgba(255, 255, 255, 0.95)",
                        backdropFilter: "blur(10px)",
                    }}
                >
                    <div className="card-body p-4">
                        <div className="row align-items-center">
                            <div className="col-md-3">
                                <label className="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                    <Building2
                                        size={20}
                                        className="text-primary"
                                    />
                                    Select Facility
                                </label>
                            </div>
                            <div className="col-md-9">
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
                                        className="form-select form-select-lg shadow-sm border-2"
                                        value={selectedFacility}
                                        onChange={handleFacilityChange}
                                        style={{ borderColor: "#66dbeaff" }}
                                    >
                                        <option value="">
                                            -- Choose Facility --
                                        </option>
                                        {facilities.map((f, idx) => (
                                            <option
                                                key={idx}
                                                value={f.Facility}
                                            >
                                                {f.Facility}
                                            </option>
                                        ))}
                                    </select>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Empty State */}
                {!selectedFacility && !loadingPivot && (
                    <div
                        className="card shadow-lg border-0 rounded-4"
                        style={{ background: "rgba(255, 255, 255, 0.95)" }}
                    >
                        <div className="card-body text-center py-5">
                            <div className="mb-4">
                                <Building2
                                    className="mx-auto text-primary opacity-50"
                                    size={80}
                                />
                            </div>
                            <h3 className="fw-bold text-dark mb-2">
                                Please Select Facility
                            </h3>
                        </div>
                    </div>
                )}

                {/* Loading State */}
                {loadingPivot && (
                    <div
                        className="card shadow-lg border-0 rounded-4"
                        style={{ background: "rgba(255, 255, 255, 0.95)" }}
                    >
                        <div className="card-body text-center py-5">
                            <div
                                className="spinner-border text-primary mb-3"
                                style={{ width: "3rem", height: "3rem" }}
                                role="status"
                            >
                                <span className="visually-hidden">
                                    Loading...
                                </span>
                            </div>
                            <p className="text-primary fw-semibold fs-5 mb-0">
                                Loading facility ...
                            </p>
                        </div>
                    </div>
                )}

                {/* Main Content */}
                {pivotData && (
                    <div className="vstack gap-4">
                        {/* Hero Section with Gradient Overlay */}
                        <div className="card shadow-lg border-0 rounded-4 overflow-hidden">
                            {imgUrl && (
                                <div
                                    className="position-relative"
                                    style={{ height: "450px" }}
                                >
                                    <img
                                        src={imgUrl}
                                        alt="Facility"
                                        className="w-100 h-100"
                                        style={{ objectFit: "cover" }}
                                    />
                                    <div
                                        className="position-absolute top-0 start-0 w-100 h-100"
                                        style={{
                                            background:
                                                "linear-gradient(to top, rgba(0, 187, 171, 0.9) 0%, rgba(16, 165, 170, 0.7) 50%, transparent 100%)",
                                        }}
                                    ></div>
                                    <div className="position-absolute bottom-0 start-0 end-0 text-white p-4">
                                        <div className="row align-items-end">
                                            <div className="col-md-8">
                                                <h1 className="display-4 fw-bold mb-3">
                                                    {pivotData.NAME}
                                                </h1>
                                                {pivotData.Alamat && (
                                                    <div className="d-flex align-items-start gap-2 mb-3">
                                                        <MapPin
                                                            size={20}
                                                            className="mt-1 flex-shrink-0"
                                                        />
                                                        <span className="fs-5">
                                                            {pivotData.Alamat}
                                                        </span>
                                                    </div>
                                                )}
                                            </div>
                                            <div className="col-md-4 text-md-end">
                                                <div className="badge bg-white text-dark px-4 py-2 rounded-3 fs-6 mb-2">
                                                    <Award
                                                        size={16}
                                                        className="me-2"
                                                    />
                                                    {pivotData.TYPE}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Key Metrics Bar */}
                            <div
                                className="card-body p-0"
                                style={{
                                    background:
                                        "linear-gradient(135deg, #667eea 0%, #0c5fdbff 100%)",
                                }}
                            >
                                <div className="row g-0 text-white">
                                    <div className="col-6 col-md-3 border-end border-white border-opacity-25 p-3 p-md-4">
                                        <div className="d-flex align-items-center gap-2 mb-2">
                                            <Building2
                                                size={18}
                                                className="opacity-75"
                                            />
                                            <small className="opacity-75">
                                                Facility ID
                                            </small>
                                        </div>
                                        <div className="fw-bold fs-5">
                                            {pivotData.FACILITY_ID}
                                        </div>
                                    </div>
                                    <div className="col-6 col-md-3 border-end border-white border-opacity-25 p-3 p-md-4">
                                        <div className="d-flex align-items-center gap-2 mb-2">
                                            <MapPin
                                                size={18}
                                                className="opacity-75"
                                            />
                                            <small className="opacity-75">
                                                Zone
                                            </small>
                                        </div>
                                        <div className="fw-bold fs-5">
                                            {pivotData.zone}
                                        </div>
                                    </div>
                                    <div className="col-6 col-md-3 border-end border-white border-opacity-25 p-3 p-md-4">
                                        <div className="d-flex align-items-center gap-2 mb-2">
                                            <Calendar
                                                size={18}
                                                className="opacity-75"
                                            />
                                            <small className="opacity-75">
                                                Opening Date
                                            </small>
                                        </div>
                                        <div className="fw-bold fs-5">
                                            {pivotData.Opening_Date}
                                        </div>
                                    </div>
                                <div className="col-6 col-md-3 p-3 p-md-4">
    <div className="d-flex align-items-center gap-2 mb-2">
        <Phone size={18} className="opacity-75" />
        <small className="opacity-75">Contact</small>
    </div>

    {(() => {
        const raw = String(pivotData.Telp || "").trim();
        let phone = raw.replace(/[^0-9]/g, "");

        // Normalisasi ke 62
        if (phone.startsWith("0")) phone = "62" + phone.substring(1);
        if (phone.startsWith("+62")) phone = phone.replace("+", "");

        return (
            <a
                href={`https://wa.me/${phone}`}
                target="_blank"
                rel="noopener noreferrer"
                className="fw-bold fs-5 text-white text-decoration-none"
                style={{ cursor: "pointer" }}
            >
                {phone}
            </a>
        );
    })()}
</div>
                                </div>
                            </div>
                        </div>
                        {/* KPI Cards - Executive Summary */}
                        <div className="row g-4">
                            {/* Utilization Card */}
                            <div className="col-12 col-md-6 col-xl-3">
                                <div className="card shadow-lg border-0 rounded-4 h-100 overflow-hidden">
                                    <div
                                        className="card-body p-4"
                                        style={{
                                            background:
                                                "linear-gradient(135deg, #1e3fd4ff 0%, #33b2e4ff 100%)",
                                        }}
                                    >
                                        <div className="d-flex justify-content-between align-items-start mb-3">
    <div className="text-white">
        <div className="d-flex align-items-center gap-2 mb-2">
            <Target size={20} />
            <small className="opacity-75">
                Capacity Utilization
            </small>
        </div>

        <h1
            className={`display-4 fw-bold mb-0 ${getUtilizationColor(
                utilizationRate
            )}`}
            style={{ color: "white" }}
        >
            {utilizationRate}%
        </h1>
    </div>

    <div className="p-3 bg-white bg-opacity-25 rounded-3">
        <TrendingUp size={24} className="text-white" />
    </div>
</div>

<div
    className="progress bg-white bg-opacity-25"
    style={{ height: "8px" }}
>
    <div
        className={`progress-bar ${getUtilizationBg(utilizationRate)}`}
        style={{
            width: `${Math.min(utilizationRate, 100)}%`,
        }}
    ></div>
</div>

{/* -- DO VALUES -- */}
<div className="mt-3 text-white small">
    <strong>{pivotData.Demand_DO}</strong> of{" "}
    <strong>{pivotData.Capacity_DO}</strong> DO Capacity
</div>

{/* -- CONDITION DISPLAY --
<div className={`mt-1 small ${conditionColor}`}>
    {condition}
</div> */}
                                    </div>
                                </div>
                            </div>

                            {/* Total Fleet Card */}
                            <div className="col-12 col-md-6 col-xl-3">
                                <div className="card shadow-lg border-0 rounded-4 h-100 overflow-hidden">
                                    <div
                                        className="card-body p-4"
                                        style={{
                                            background:
                                                "linear-gradient(135deg, #11998e 0%, #38ef7d 100%)",
                                        }}
                                    >
                                        <div className="d-flex justify-content-between align-items-start mb-3">
                                            <div className="text-white">
                                                <div className="d-flex align-items-center gap-2 mb-2">
                                                    <Truck size={20} />
                                                    <small className="opacity-75">
                                                        Total Fleet
                                                    </small>
                                                </div>
                                                <h1 className="display-4 fw-bold mb-0">
                                                    {(() => {
                                                        if (
                                                            !pivotData.armada ||
                                                            pivotData.armada
                                                                .length === 0
                                                        )
                                                            return 0;
                                                        const row =
                                                            pivotData.armada[0];
                                                        const armadaKeys =
                                                            Object.keys(
                                                                row
                                                            ).filter(
                                                                (key) =>
                                                                    key.toLowerCase() !==
                                                                    "relasi"
                                                            );
                                                        return armadaKeys.reduce(
                                                            (total, key) => {
                                                                return (
                                                                    total +
                                                                    (parseFloat(
                                                                        row[key]
                                                                    ) || 0)
                                                                );
                                                            },
                                                            0
                                                        );
                                                    })()}
                                                </h1>
                                            </div>
                                            <div className="p-3 bg-white bg-opacity-25 rounded-3">
                                                <Zap
                                                    size={24}
                                                    className="text-white"
                                                />
                                            </div>
                                        </div>
                                        <div className="mt-3 text-white small">
                                            <strong>Active</strong> Vehicle
                                            Assets
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* CBM Capacity Card */}
                            <div className="col-12 col-md-6 col-xl-3">
                                <div className="card shadow-lg border-0 rounded-4 h-100 overflow-hidden">
                                    <div
                                        className="card-body p-4"
                                        style={{
                                            background:
                                                "linear-gradient(135deg, #fb9393ff 0%, #f5576c 100%)",
                                        }}
                                    >
                                        <div className="d-flex justify-content-between align-items-start mb-3">
                                            <div className="text-white">
                                                <div className="d-flex align-items-center gap-2 mb-2">
                                                    <Package size={20} />
                                                    <small className="opacity-75">
                                                        CBM Capacity
                                                    </small>
                                                </div>
                                                <h1 className="display-4 fw-bold mb-0">
                                                    {pivotData.Capacity_CBM}
                                                </h1>
                                            </div>
                                            <div className="p-3 bg-white bg-opacity-25 rounded-3">
                                                <Activity
                                                    size={24}
                                                    className="text-white"
                                                />
                                            </div>
                                        </div>
                                        <div className="mt-3 text-white small">
                                            <strong>Cubic Meter</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Manpower Card */}
                            <div className="col-12 col-md-6 col-xl-3">
                                <div className="card shadow-lg border-0 rounded-4 h-100 overflow-hidden">
                                    <div
                                        className="card-body p-4"
                                        style={{
                                            background:
                                                "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
                                        }}
                                    >
                                        <div className="d-flex justify-content-between align-items-start mb-3">
                                            <div className="text-white">
                                                <div className="d-flex align-items-center gap-2 mb-2">
                                                    <Users size={20} />
                                                    <small className="opacity-75">
                                                        Total Manpower
                                                    </small>
                                                </div>
                                                <h1 className="display-4 fw-bold mb-0">
                                                    {(() => {
                                                        const mppFields = [
                                                            "Staff_Up",
                                                            "Driver",
                                                            "Asst_Driver",
                                                            "WHM",
                                                            "Security",
                                                        ];
                                                        return mppFields.reduce(
                                                            (total, key) => {
                                                                const val =
                                                                    pivotData[
                                                                        key
                                                                    ];
                                                                return (
                                                                    total +
                                                                    (val
                                                                        ? parseFloat(
                                                                              val
                                                                          )
                                                                        : 0)
                                                                );
                                                            },
                                                            0
                                                        );
                                                    })()}
                                                </h1>
                                            </div>
                                            <div className="p-3 bg-white bg-opacity-25 rounded-3">
                                                <Shield
                                                    size={24}
                                                    className="text-white"
                                                />
                                            </div>
                                        </div>
                                        <div className="mt-3 text-white small">
                                            <strong>Active</strong> Personnel
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Charts Section */}
                        <div className="row g-4">
                            {/* Demand vs Capacity Chart */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <div className="d-flex justify-content-between align-items-center mb-4">
                                            <h5 className="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                                <BarChart3
                                                    className="text-primary"
                                                    size={24}
                                                />
                                                Demand vs Capacity Analysis
                                            </h5>
                                            <span className="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                DO Metrics
                                            </span>
                                        </div>

                                        <ResponsiveContainer
                                            width="100%"
                                            height={280}
                                        >
                                            <BarChart
                                                data={[
                                                    {
                                                        name: "Demand",
                                                        value: pivotData.Demand_DO,
                                                        fill: "#62ebf5ff",
                                                    },
                                                    {
                                                        name: "Capacity",
                                                        value: pivotData.Capacity_DO,
                                                        fill: "#32c6daff",
                                                    },
                                                ]}
                                                margin={{ top: 30 }}
                                            >
                                                <CartesianGrid
                                                    strokeDasharray="3 3"
                                                    stroke="#f0f0f0"
                                                />
                                                <XAxis dataKey="name" />
                                                <YAxis />
                                                <Tooltip />

                                                <Bar
                                                    dataKey="value"
                                                    radius={[12, 12, 0, 0]}
                                                    label={{
                                                        position: "top",
                                                        fill: "#000",
                                                        fontSize: 14,
                                                        fontWeight: 600,
                                                    }}
                                                />
                                            </BarChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                            </div>

                            {/* Coverage Area & Jalur */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <div className="d-flex justify-content-between align-items-center mb-4">
                                            <h5 className="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                                <MapPin
                                                    className="text-primary"
                                                    size={24}
                                                />
                                                Coverage Delivery
                                                <span
                                                    className="badge bg-info bg-opacity-10 text-info px-3 py-2"
                                                    style={{
                                                        cursor: "pointer",
                                                    }}
                                                    onClick={() =>
                                                        setShowDetailModal(true)
                                                    }
                                                >
                                                    Show Details
                                                </span>
                                            </h5>
                                        </div>

                                        {/* Summary + Donut */}
                                        {(() => {
                                            if (jalurData.length === 0)
                                                return (
                                                    <p className="text-muted">
                                                        No jalur data available
                                                    </p>
                                                );

                                            const grouped: Record<
                                                string,
                                                string[]
                                            > = {};
                                            jalurData.forEach((item: any) => {
                                                const area = normalizeAreaType(
                                                    item.area || ""
                                                );
                                                const jalur =
                                                    item.jalur ||
                                                    "Unknown Jalur";

                                                if (!grouped[area])
                                                    grouped[area] = [];
                                                if (
                                                    !grouped[area].includes(
                                                        jalur
                                                    )
                                                )
                                                    grouped[area].push(jalur);
                                            });

                                            const areas = Object.keys(grouped);
                                            const donutData = areas.map(
                                                (a) => ({
                                                    name: a,
                                                    value: grouped[a].length,
                                                })
                                            );

                                            const COLORS = [
                                                "#1034d8",
                                                "#139acf",
                                                "#4facfe",
                                                "#00f2fe",
                                                "#38ef7d",
                                                "#fb9393",
                                                "#f5576c",
                                            ];

                                            return (
                                                <div className="row g-4">
                                                    {/* LEFT */}
                                                    <div className="col-12">
                                                        <div
                                                            className="p-3 rounded-4 shadow-sm mb-4"
                                                            style={{
                                                                background:
                                                                    "linear-gradient(135deg,#4facfe15,#00f2fe15)",
                                                            }}
                                                        >
                                                            <h6 className="fw-bold text-dark mb-3">
                                                                Summary
                                                            </h6>

                                                            <div className="row text-center">
                                                                <div className="col-6">
                                                                    <h2 className="fw-bold text-primary mb-0">
                                                                        {
                                                                            areas.length
                                                                        }
                                                                    </h2>
                                                                    <small className="text-muted">
                                                                        Total
                                                                        Area
                                                                    </small>
                                                                </div>
                                                                <div className="col-6">
                                                                    <h2 className="fw-bold text-info mb-0">
                                                                        {
                                                                            new Set(
                                                                                jalurData.map(
                                                                                    (
                                                                                        j
                                                                                    ) =>
                                                                                        j.jalur
                                                                                )
                                                                            )
                                                                                .size
                                                                        }
                                                                    </h2>
                                                                    <small className="text-muted">
                                                                        Total
                                                                        City
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {/* Donut */}
                                                        <div className="card border-0 shadow-sm rounded-4">
                                                            <div className="card-body">
                                                                <h6 className="fw-bold text-dark mb-3">
                                                                    Area Cover
                                                                </h6>

                                                                <ResponsiveContainer
                                                                    width="100%"
                                                                    height={250}
                                                                >
                                                                    <PieChart>
                                                                        <Pie
                                                                            data={
                                                                                donutData
                                                                            }
                                                                            cx="50%"
                                                                            cy="50%"
                                                                            innerRadius={
                                                                                60
                                                                            }
                                                                            outerRadius={
                                                                                85
                                                                            }
                                                                            dataKey="value"
                                                                            label={({
                                                                                name,
                                                                                value,
                                                                            }) =>
                                                                                `${name} (${value})`
                                                                            }
                                                                        >
                                                                            {donutData.map(
                                                                                (
                                                                                    entry,
                                                                                    index
                                                                                ) => (
                                                                                    <Cell
                                                                                        key={
                                                                                            index
                                                                                        }
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
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {/* modal */}
                                                    {/* DETAIL MODAL */}
                                                    {showDetailModal && (
                                                        <div
                                                            className="modal fade show"
                                                            style={{
                                                                display:
                                                                    "block",
                                                                background:
                                                                    "rgba(0,0,0,0.45)",
                                                                backdropFilter:
                                                                    "blur(4px)",
                                                            }}
                                                        >
                                                            <div className="modal-dialog modal-xl modal-dialog-centered">
                                                                <div className="modal-content shadow-lg border-0 rounded-4">
                                                                    {/* HEADER */}
                                                                    <div className="modal-header">
                                                                        <h5 className="modal-title fw-bold d-flex align-items-center gap-2">
                                                                            <MapPin
                                                                                className="text-primary"
                                                                                size={
                                                                                    22
                                                                                }
                                                                            />
                                                                            Detail
                                                                            Coverage
                                                                            Jalur
                                                                            &
                                                                            Area
                                                                        </h5>
                                                                        <button
                                                                            className="btn-close"
                                                                            onClick={() =>
                                                                                setShowDetailModal(
                                                                                    false
                                                                                )
                                                                            }
                                                                        ></button>
                                                                    </div>

                                                                    {/* BODY */}
                                                                    <div
                                                                        className="modal-body"
                                                                        style={{
                                                                            maxHeight:
                                                                                "70vh",
                                                                            overflowY:
                                                                                "auto",
                                                                        }}
                                                                    >
                                                                        {(() => {
                                                                            const grouped: Record<
                                                                                string,
                                                                                string[]
                                                                            > =
                                                                                {};
                                                                            jalurData.forEach(
                                                                                (
                                                                                    item
                                                                                ) => {
                                                                                    const area = normalizeAreaType(item.area || "");
                                                                                    const jalur =
                                                                                        item.jalur ||
                                                                                        "Unknown Jalur";
                                                                                    if (
                                                                                        !grouped[
                                                                                            area
                                                                                        ]
                                                                                    )
                                                                                        grouped[
                                                                                            area
                                                                                        ] =
                                                                                            [];
                                                                                    if (
                                                                                        !grouped[
                                                                                            area
                                                                                        ].includes(
                                                                                            jalur
                                                                                        )
                                                                                    )
                                                                                        grouped[
                                                                                            area
                                                                                        ].push(
                                                                                            jalur
                                                                                        );
                                                                                }
                                                                            );

                                                                            const COLORS =
                                                                                [
                                                                                    "#1034d8",
                                                                                    "#139acf",
                                                                                    "#4facfe",
                                                                                    "#00f2fe",
                                                                                    "#38ef7d",
                                                                                    "#fb9393",
                                                                                    "#f5576c",
                                                                                ];

                                                                            return (
                                                                                <div className="vstack gap-4">
                                                                                    {Object.keys(
                                                                                        grouped
                                                                                    ).map(
                                                                                        (
                                                                                            area,
                                                                                            idx
                                                                                        ) => (
                                                                                            <div
                                                                                                key={
                                                                                                    idx
                                                                                                }
                                                                                                className="p-4 rounded-4 shadow-sm border-start border-4"
                                                                                                style={{
                                                                                                    borderColor:
                                                                                                        COLORS[
                                                                                                            idx %
                                                                                                                COLORS.length
                                                                                                        ],
                                                                                                    background: `${
                                                                                                        COLORS[
                                                                                                            idx %
                                                                                                                COLORS.length
                                                                                                        ]
                                                                                                    }10`,
                                                                                                }}
                                                                                            >
                                                                                                <h6 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                                                                                    <Building2
                                                                                                        size={
                                                                                                            18
                                                                                                        }
                                                                                                    />
                                                                                                    {
                                                                                                        area
                                                                                                    }
                                                                                                </h6>

                                                                                                <div className="vstack gap-2">
                                                                                                    {grouped[
                                                                                                        area
                                                                                                    ].map(
                                                                                                        (
                                                                                                            jalur,
                                                                                                            i
                                                                                                        ) => (
                                                                                                            <div
                                                                                                                key={
                                                                                                                    i
                                                                                                                }
                                                                                                                className="d-flex justify-content-between align-items-center p-2 bg-white rounded shadow-sm"
                                                                                                            >
                                                                                                                <span>
                                                                                                                    {
                                                                                                                        jalur
                                                                                                                    }
                                                                                                                </span>
                                                                                                                <span className="badge bg-primary bg-opacity-10 text-primary px-3 py-1">
                                                                                                                    Jalur
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        )
                                                                                                    )}
                                                                                                </div>
                                                                                            </div>
                                                                                        )
                                                                                    )}
                                                                                </div>
                                                                            );
                                                                        })()}
                                                                    </div>

                                                                    {/* FOOTER */}
                                                                    <div className="modal-footer">
                                                                        <button
                                                                            className="btn btn-secondary px-4"
                                                                            onClick={() =>
                                                                                setShowDetailModal(
                                                                                    false
                                                                                )
                                                                            }
                                                                        >
                                                                            Close
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                            );
                                        })()}
                                    </div>
                                </div>
                            </div>

                            {/* Fleet Distribution */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <div className="d-flex justify-content-between align-items-center mb-2">
                                            <h5 className="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                                <Truck
                                                    className="text-primary"
                                                    size={24}
                                                />
                                                Fleet Availability
                                            </h5>
                                            <span className="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                Armada
                                            </span>
                                        </div>

                                        {(() => {
                                            if (
                                                !pivotData.armada ||
                                                pivotData.armada.length === 0
                                            ) {
                                                return (
                                                    <div className="text-center py-4">
                                                        <AlertCircle
                                                            className="mx-auto mb-3 text-muted"
                                                            size={48}
                                                        />
                                                        <p className="text-muted mb-0">
                                                            No armada data
                                                            available
                                                        </p>
                                                    </div>
                                                );
                                            }

                                            const row = pivotData.armada[0];
                                            const keys = Object.keys(
                                                row
                                            ).filter(
                                                (key) =>
                                                    key.toLowerCase() !==
                                                        "relasi" &&
                                                    parseFloat(row[key]) > 0
                                            );

                                            const chartData = keys.map((k) => ({
                                                name: k,
                                                value: parseFloat(row[k]) || 0,
                                            }));

                                            const COLORS = [
                                                "#1034d8ff",
                                                "#139acfff",
                                                "#9593fbff",
                                                "#f5576c",
                                                "#4facfe",
                                                "#00f2fe",
                                                "#11998e",
                                                "#38ef7d",
                                            ];

                                            return (
                                                <>
                                                    <ResponsiveContainer width="100%" height={260}>
    <PieChart>
        <Pie
            data={chartData}
            cx="50%"
            cy="50%"
            innerRadius={55}
            outerRadius={85}
            paddingAngle={2}
            dataKey="value"
            labelLine={false}
            label={({ name, value }) =>
                `${name} (${value})`
            }
        >
            {chartData.map((entry, index) => (
                <Cell
                    key={index}
                    fill={COLORS[index % COLORS.length]}
                />
            ))}
        </Pie>
        <Tooltip
            formatter={(value, name) => [`${value}`, name]}
        />
    </PieChart>
</ResponsiveContainer>
<div className="row g-2 mt-2">
    {chartData.map((item, idx) => (
        <div key={idx} className="col-6">
            <div className="d-flex align-items-center gap-2 p-2 bg-light rounded">
                
                {/* ICON TRUCK BERWARNA */}
                <Truck 
                    size={16}
                    style={{
                        color: COLORS[idx % COLORS.length],
                    }}
                />

                <small className="text-truncate fw-medium">
                    {item.name}
                </small>
            </div>
        </div>
    ))}
</div>

                                                </>
                                            );
                                        })()}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Operational Details */}
                        <div className="row g-4">
                            {/* Capacity Details */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <h5 className="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                                            <Package
                                                className="text-primary"
                                                size={24}
                                            />
                                            Capacity Overview
                                        </h5>
                                        <div className="vstack gap-3">
                                            <div
                                                className="p-3 rounded-3"
                                                style={{
                                                    background:
                                                        "linear-gradient(135deg, #667eea15 0%, #764ba215 100%)",
                                                }}
                                            >
                                                <div className="d-flex justify-content-between align-items-center">
                                                    <span className="text-muted">
                                                        Demand DO
                                                    </span>
                                                    <span className="fw-bold fs-4 text-primary">
                                                        {pivotData.Demand_DO}
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                className="p-3 rounded-3"
                                                style={{
                                                    background:
                                                        "linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%)",
                                                }}
                                            >
                                                <div className="d-flex justify-content-between align-items-center">
                                                    <span className="text-muted">
                                                        Capacity DO
                                                    </span>
                                                    <span className="fw-bold fs-4 text-success">
                                                        {pivotData.Capacity_DO}
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                className="p-3 rounded-3"
                                                style={{
                                                    background:
                                                        "linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%)",
                                                }}
                                            >
                                                <div className="d-flex justify-content-between align-items-center">
                                                    <span className="text-muted">
                                                        Capacity CBM
                                                    </span>
                                                    <span className="fw-bold fs-4 text-danger">
                                                        {pivotData.Capacity_CBM}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Area Specifications */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <h5 className="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                                            <Ruler
                                                className="text-primary"
                                                size={24}
                                            />
                                            Area Specifications
                                        </h5>
                                        <div className="vstack gap-3">
                                            <div className="p-3 bg-light rounded-3">
                                                <p className="text-muted small mb-2">
                                                    Area Staging (P × L × T)
                                                </p>
                                                <p className="fw-bold text-dark mb-0 fs-5">
                                                    {pivotData.Area_Staging_P} ×{" "}
                                                    {pivotData.Area_Staging_L} ×{" "}
                                                    {pivotData.Area_Staging_T}
                                                </p>
                                            </div>
                                            <div className="p-3 bg-light rounded-3">
                                                <p className="text-muted small mb-2">
                                                    Area Loading (L × P)
                                                </p>
                                                <p className="fw-bold text-dark mb-0 fs-5">
                                                    {pivotData.Area_Loading_L} ×{" "}
                                                    {pivotData.Area_Loading_P}
                                                </p>
                                            </div>
                                            <div className="p-3 bg-light rounded-3">
                                                <p className="text-muted small mb-2">
                                                    Leader
                                                </p>
                                                <p className="fw-bold text-dark mb-0 fs-5">
                                                    {pivotData.Name_Leader}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Manpower Distribution */}
                            <div className="col-12 col-lg-4">
                                <div className="card shadow-lg border-0 rounded-4 h-100">
                                    <div className="card-body p-4">
                                        <h5 className="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                                            <Users
                                                className="text-primary"
                                                size={24}
                                            />
                                            Manpower Total
                                        </h5>
                                        {(() => {
                                            const mppFields = [
                                                {
                                                    label: "Staff UP",
                                                    key: "Staff_Up",
                                                    color: "#1c96b4ff",
                                                },
                                                {
                                                    label: "Driver",
                                                    key: "Driver",
                                                    color: "#11998e",
                                                },
                                                {
                                                    label: "Assistant Driver",
                                                    key: "Asst_Driver",
                                                    color: "#24a1a5ff",
                                                },
                                                {
                                                    label: "WHM",
                                                    key: "WHM",
                                                    color: "#4b4d4eff",
                                                },
                                                {
                                                    label: "Security",
                                                    key: "Security",
                                                    color: "#3d4142ff",
                                                },
                                            ];

                                            const availableData =
                                                mppFields.filter((f) => {
                                                    const v = pivotData[f.key];
                                                    return (
                                                        v !== null &&
                                                        v !== undefined &&
                                                        parseFloat(v) > 0
                                                    );
                                                });

                                            return availableData.length > 0 ? (
                                                <div className="vstack gap-2">
                                                    {availableData.map(
                                                        (item, i) => (
                                                            <div
                                                                key={i}
                                                                className="d-flex justify-content-between align-items-center p-3 rounded-3 border-start border-4"
                                                                style={{
                                                                    borderColor:
                                                                        item.color,
                                                                    background: `${item.color}10`,
                                                                }}
                                                            >
                                                                <span className="fw-medium text-dark">
                                                                    {item.label}
                                                                </span>
                                                                <span
                                                                    className="fs-4 fw-bold"
                                                                    style={{
                                                                        color: item.color,
                                                                    }}
                                                                >
                                                                    {
                                                                        pivotData[
                                                                            item
                                                                                .key
                                                                        ]
                                                                    }
                                                                </span>
                                                            </div>
                                                        )
                                                    )}
                                                </div>
                                            ) : (
                                                <div className="text-center py-4">
                                                    <Users
                                                        className="mx-auto mb-3 text-muted"
                                                        size={48}
                                                    />
                                                    <p className="text-muted mb-0">
                                                        No manpower data
                                                        available
                                                    </p>
                                                </div>
                                            );
                                        })()}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Additional Info Footer */}
                        <div
                            className="card shadow-lg border-0 rounded-4"
                            style={{
                                background:
                                    "linear-gradient(135deg, #12b2b8ff 0%, #42daf5ff 100%)",
                            }}
                        >
                            <div className="card-body p-4">
                                <div className="row text-white">
                                    <div className="col-md-6 mb-3 mb-md-0">
                                        <h6 className="fw-bold mb-3 opacity-75 text-dark">
                                            Facility Information
                                        </h6>
                                        <div className="vstack gap-2 small text-dark ">
                                            <div className="d-flex align-items-center gap-2 ">
                                                <Truck size={16} />
                                                <span>
                                                    Loading Dock:{" "}
                                                    <strong>
                                                        {
                                                            pivotData.Is_Loading_Dock
                                                        }
                                                    </strong>
                                                </span>
                                            </div>
                                            <div className="d-flex align-items-center gap-2">
                                                <Building2 size={16} />
                                                <span>
                                                    Type:{" "}
                                                    <strong>
                                                        {pivotData.TYPE}
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <h6 className="fw-bold mb-3 opacity-75 text-dark">
                                            Performance Summary
                                        </h6>
                                        <div className="vstack gap-2 small text-dark">
                                            <div className="d-flex justify-content-between">
                                                <span>Utilization Rate:</span>
                                                <strong>
                                                    {utilizationRate}%
                                                </strong>
                                            </div>
                                            <div className="d-flex justify-content-between">
                                                <span>Active Assets:</span>
                                                <strong>
                                                    {(() => {
                                                        if (
                                                            !pivotData.armada ||
                                                            pivotData.armada
                                                                .length === 0
                                                        )
                                                            return 0;
                                                        const row =
                                                            pivotData.armada[0];
                                                        const armadaKeys =
                                                            Object.keys(
                                                                row
                                                            ).filter(
                                                                (key) =>
                                                                    key.toLowerCase() !==
                                                                    "relasi"
                                                            );
                                                        return armadaKeys.reduce(
                                                            (total, key) => {
                                                                return (
                                                                    total +
                                                                    (parseFloat(
                                                                        row[key]
                                                                    ) || 0)
                                                                );
                                                            },
                                                            0
                                                        );
                                                    })()}
                                                </strong>
                                            </div>
                                            <div className="d-flex justify-content-between">
                                                <span>Total Personnel:</span>
                                                <strong>
                                                    {(() => {
                                                        const mppFields = [
                                                            "Staff_Up",
                                                            "Driver",
                                                            "Asst_Driver",
                                                            "WHM",
                                                            "Security",
                                                        ];
                                                        return mppFields.reduce(
                                                            (total, key) => {
                                                                const val =
                                                                    pivotData[
                                                                        key
                                                                    ];
                                                                return (
                                                                    total +
                                                                    (val
                                                                        ? parseFloat(
                                                                              val
                                                                          )
                                                                        : 0)
                                                                );
                                                            },
                                                            0
                                                        );
                                                    })()}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default CompanyProfile;
