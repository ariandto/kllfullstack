import React, { useState, useEffect } from "react";
import API_URL from "../config/api";
import { motion } from "framer-motion";
import { AlertCircle, Truck, Building2 } from "lucide-react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    ResponsiveContainer,
    Tooltip,
} from "recharts";

/* ---- INTERFACE ---- */
interface ZoneItem {
    zone: string;
}

interface PivotRow {
    zone: string;
    facility: string;
    source: string;
    armada: { [key: string]: number };
    total_armada: number;
}

const SummaryAsset: React.FC = () => {
    const [zoneList, setZoneList] = useState<ZoneItem[]>([]);
    const [selectedZone, setSelectedZone] = useState("");
    const [pivotData, setPivotData] = useState<PivotRow[]>([]);
    const [chartData, setChartData] = useState<any[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    /* -------------------------------------------
       FETCH ZONE LIST 
    --------------------------------------------*/
    const fetchZoneList = async () => {
        console.log("➡ FETCH ZONE LIST:", `${API_URL}/admin/transport/assetscm/zones`);

        try {
            const res = await fetch(`${API_URL}/admin/transport/assetscm/zones`, {
                credentials: "include"
            });

            console.log("⬅ ZONE STATUS:", res.status);

            const json = await res.json();
            console.log("⬅ ZONE JSON:", json);

            setZoneList(json.zone || []);
        } catch (err) {
            console.error("❌ ZONE ERROR:", err);
            setError("Gagal memuat daftar Zone.");
        }
    };

    /* -------------------------------------------
       FLATTEN PIVOT >
    --------------------------------------------*/
    const convertForChart = (rows: PivotRow[]) => {
        return rows.map(r => ({
            facility: r.facility,
            ...r.armada,
            total: r.total_armada
        }));
    };

    /* -------------------------------------------
       FETCH PIVOT (GET, NO CSRF REQUIRED)
    --------------------------------------------*/
    const fetchPivot = async (zone: string) => {
        setLoading(true);
        setError("");

        const url = `${API_URL}/admin/transport/assetscm/pivot?zone=${encodeURIComponent(zone)}`;
        console.log("➡ FETCH PIVOT URL:", url);

        try {
            const res = await fetch(url, { credentials: "include" });

            console.log("⬅ STATUS:", res.status);

            const text = await res.text();
            console.log("⬅ RAW PIVOT RESPONSE:", text);

            const json = JSON.parse(text);

            if (!json.status) {
                setError(json.message || "Gagal mengambil data pivot.");
                setPivotData([]);
                setChartData([]);
            } else {
                setPivotData(json.react || []);
                setChartData(convertForChart(json.react || []));
            }

        } catch (err) {
            console.error("❌ ERROR PIVOT:", err);
            setError("Error API pivot.");
        }

        setLoading(false);
    };

    /* -------------------------------------------
       LOAD ZONES ONCE 
    --------------------------------------------*/
    useEffect(() => {
        fetchZoneList();
    }, []);

    /* -------------------------------------------
       ZONE CHANGE HANDLER
    --------------------------------------------*/
    const handleZoneChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        const val = e.target.value;
        console.log("▶ SELECT ZONE:", val);

        setSelectedZone(val);

        if (val) fetchPivot(val);
    };

    /* -------------------------------------------
       GET DYNAMIC COLUMNS
    --------------------------------------------*/
    const dynamicColumns =
        pivotData.length > 0 ? Object.keys(pivotData[0].armada) : [];

    return (
        <div
            style={{
                minHeight: "100vh",
                background: "linear-gradient(135deg, #fffcfc 0%, #f3ecec 100%)",
            }}
        >

            {/* ===================================================
                PREMIUM HEADER (SAMA PERSIS DARI COMPANY PROFILE)
            ==================================================== */}
            <div className="content container-fluid px-3 px-md-5 mb-5 py-4">
                <div className="mb-4">
                    <div className="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <h1 className="display-5 fw-bold text-dark mb-2">
                            SCM Transport Profile
                        </h1>
                    </div>

                    <nav className="navbar navbar-expand-lg" style={{ background: "#ffffff" }}>
                        <div className="container-fluid px-3">
                            <button
                                className="navbar-toggler bg-light"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#coverageNavbar"
                            >
                                <span className="navbar-toggler-icon"></span>
                            </button>

                            <div className="collapse navbar-collapse" id="coverageNavbar">
                                <ul className="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3">

                                    <li className="nav-item">
                                        <a className="nav-link text-dark fw-semibold" href="/admin/transport/assetscm">
                                            Summary Asset Armada
                                        </a>
                                    </li>

                                    <li className="nav-item">
                                        <a className="nav-link text-dark fw-semibold" href="#">
                                            SLA and Orders
                                        </a>
                                    </li>

                                    <li className="nav-item">
                                        <a className="nav-link text-dark fw-semibold" href="#">
                                            Dept. Structure
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>

                {/* ===================================================
                    MAIN SECTION BELOW HEADER
                ==================================================== */}

                {/* TITLE */}
                <motion.h3
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="mb-4 fw-bold text-primary d-flex align-items-center"
                >
                    <Truck size={28} className="me-2" />
                    Summary Asset Armada
                </motion.h3>

                {/* ZONE SELECT */}
                <div className="card p-3 shadow-sm mb-4">
                    <label className="fw-semibold mb-2 d-flex align-items-center">
                        <Building2 size={18} className="me-2" />
                        Pilih Zone
                    </label>

                    <select
                        className="form-select"
                        value={selectedZone}
                        onChange={handleZoneChange}
                    >
                        <option value="">-- Pilih Zone --</option>
                        {zoneList.map((z, idx) => (
                            <option key={idx} value={z.zone}>
                                {z.zone}
                            </option>
                        ))}
                    </select>
                </div>

                {/* ERROR */}
                {error && (
                    <div className="alert alert-danger d-flex align-items-center">
                        <AlertCircle size={20} className="me-2" />
                        {error}
                    </div>
                )}

                {/* LOADING */}
                {loading && (
                    <div className="text-center py-3">
                        <div className="spinner-border text-primary" />
                    </div>
                )}

                {/* TABLE */}
                {!loading && pivotData.length > 0 && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        className="card p-3 shadow-sm mb-4 table-responsive"
                    >
                        <table className="table table-bordered table-striped">
                            <thead className="table-primary">
                                <tr>
                                    {/* <th>Zone</th> */}
                                    <th>Facility</th>
                                    {dynamicColumns.map((col, idx) => (
                                        <th key={idx} className="text-center">{col}</th>
                                    ))}
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {pivotData.map((row, idx) => (
                                    <tr key={idx}>
                                        {/* <td>{row.zone}</td> */}
                                        <td>{row.facility}</td>

                                        {dynamicColumns.map((col, i2) => (
                                            <td key={i2} className="text-center fw-bold">
                                                {row.armada[col] ?? 0}
                                            </td>
                                        ))}

                                        <td className="fw-bold text-primary">
                                            {row.total_armada}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </motion.div>
                )}

                {/* CHART */}
                {chartData.length > 0 && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        className="card p-3 shadow-sm"
                    >
                        <h5 className="fw-bold mb-3">Visual Summary</h5>

                        <div style={{ width: "100%", height: 350 }}>
                            <ResponsiveContainer>
                                <BarChart data={chartData}>
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="facility" />
                                    <YAxis />
                                    <Tooltip />

                                    {dynamicColumns.map((col, idx) => (
                                        <Bar key={idx} dataKey={col} />
                                    ))}
                                </BarChart>
                            </ResponsiveContainer>
                        </div>
                    </motion.div>
                )}
            </div>
        </div>
    );
};

export default SummaryAsset;
