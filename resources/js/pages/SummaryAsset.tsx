import React, { useState, useEffect } from "react";
import API_URL from "../config/api";
import { motion } from "framer-motion";
import { AlertCircle, Truck, Building2, BarChart3, PieChart, TrendingUp, ChevronDown, X, Search } from "lucide-react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    ResponsiveContainer,
    Tooltip,
    Legend,
    PieChart as RechartsPieChart,
    Pie,
    Cell,
    LineChart,
    Line
} from "recharts";
import SCMNavbar from "../components/ScmNavbar";
import '../../css/SummaryAsset.css'

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

interface SummaryStats {
    totalFacilities: number;
    totalArmada: number;
    averagePerFacility: number;
    topFacility: string;
}

const SummaryAsset: React.FC = () => {
    const [zoneList, setZoneList] = useState<ZoneItem[]>([]);
    const [selectedZone, setSelectedZone] = useState<string[]>([]);
    const [pivotData, setPivotData] = useState<PivotRow[]>([]);
    const [chartData, setChartData] = useState<any[]>([]);
    const [pieChartData, setPieChartData] = useState<any[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const [summaryStats, setSummaryStats] = useState<SummaryStats | null>(null);
    const [isZoneDropdownOpen, setIsZoneDropdownOpen] = useState(false);
    const [zoneSearch, setZoneSearch] = useState("");
    const [tempSelectedZone, setTempSelectedZone] = useState<string[]>([]);

    const zoneDropdownRef = React.useRef<HTMLDivElement | null>(null);

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

            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            const json = await res.json();
            // console.log("⬅ ZONE JSON:", json);

            setZoneList(json.zone || []);
        } catch (err) {
            console.error("❌ ZONE ERROR:", err);
            setError("Failed to load zone.");
        }
    };

    const filteredZones = zoneList.filter(z =>
    z.zone.toLowerCase().includes(zoneSearch.toLowerCase())
);


    useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
        if (
            isZoneDropdownOpen &&
            zoneDropdownRef.current &&
            !zoneDropdownRef.current.contains(e.target as Node)
        ) {
            setIsZoneDropdownOpen(false);
            setTempSelectedZone(selectedZone); // rollback ke pilihan terakhir yang apply
            setZoneSearch("");
        }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
}, [isZoneDropdownOpen, selectedZone]);

   const renderCustomLabel = ({
    cx,
    cy,
    midAngle,
    innerRadius,
    outerRadius,
    percent,
    payload   // ← ambil name dari sini, bukan dari index
}: any) => {

    const RADIAN = Math.PI / 180;
    const radius = innerRadius + (outerRadius - innerRadius) * 0.55;

    const x = cx + radius * Math.cos(-midAngle * RADIAN);
    const y = cy + radius * Math.sin(-midAngle * RADIAN);

    const name = payload?.name || "";  // <<– dijamin ada

    const displayName = name.length > 12 ? name.substring(0, 12) + "…" : name;
    const percentValue = (percent * 100).toFixed(0) + "%";

    return (
        <text
            x={x}
            y={y}
            fill="#161414ff"
            textAnchor="middle"
            dominantBaseline="central"
            fontSize={12}
            fontWeight="600"
        >
            {`${displayName} (${percentValue})`}
        </text>
    );
};

    const convertForChart = (rows: PivotRow[]) => {
        return rows.map(r => ({
            facility: r.facility,
            ...r.armada,
            total: r.total_armada
        }));
    };

    const generatePieChartData = (rows: PivotRow[]) => {
        if (rows.length === 0) return [];
        
        const facilityTotals = rows.map(row => ({   
            name: row.facility,
            value: row.total_armada
        }));
        
        return facilityTotals.sort((a, b) => b.value - a.value).slice(0, 3);
    };

    
    const calculateSummaryStats = (rows: PivotRow[]): SummaryStats => {
        const totalFacilities = rows.length;
        const totalArmada = rows.reduce((sum, row) => sum + row.total_armada, 0);
        const averagePerFacility = totalFacilities > 0 ? Math.round(totalArmada / totalFacilities) : 0;
        const topFacility = rows.length > 0 
            ? rows.reduce((max, row) => row.total_armada > max.total_armada ? row : max).facility
            : "-";

        return {
            totalFacilities,
            totalArmada,
            averagePerFacility,
            topFacility
        };
    };

    const generateColors = (count: number): string[] => {
        // Menghindari warna ungu, menggunakan palette biru-hijau-orange
        const colors = [
            "#3b82f6", "#10b981", "#f59e0b", "#ef4444", "#06b6d4",
            "#8b5cf6", "#84cc16", "#f97316", "#6366f1", "#ec4899",
            "#14b8a6", "#eab308", "#dc2626", "#0ea5e9", "#7c3aed"
        ];
        return colors.slice(0, count);
    };

    const fetchPivot = async (zone: string) => {
        setLoading(true);
        setError("");

        const url = `${API_URL}/admin/transport/assetscm/pivot?zone=${encodeURIComponent(zone)}`;
        console.log("➡ FETCH PIVOT URL:", url);

        try {
            const res = await fetch(url, { credentials: "include" });

            console.log("⬅ STATUS:", res.status);

            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            const text = await res.text();
            console.log("⬅ RAW PIVOT RESPONSE:", text);

            if (!text) {
                throw new Error("Empty response from server");
            }

            const json = JSON.parse(text);

            if (!json.status) {
                setError(json.message || "Gagal mengambil data pivot.");
                setPivotData([]);
                setChartData([]);
                setPieChartData([]);
                setSummaryStats(null);
            } else {
                const data = json.react || [];
                setPivotData(data);
                setChartData(convertForChart(data));
                setPieChartData(generatePieChartData(data));
                setSummaryStats(calculateSummaryStats(data));
            }

        } catch (err) {
            console.error("❌ ERROR PIVOT:", err);
            setError("Error mengambil data dari API.");
        }

        setLoading(false);
    };

    /* -------------------------------------------
       LOAD ZONES ON MOUNT
    --------------------------------------------*/
    useEffect(() => {
        fetchZoneList();
    }, []);

 
    const dynamicColumns = pivotData.length > 0 
        ? Object.keys(pivotData[0].armada) 
        : [];

    const chartColors = generateColors(dynamicColumns.length);
    const pieChartColors = generateColors(pieChartData.length);

    const StatCard = ({ title, value, icon: Icon, color, subtitle }: any) => (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.1 }}
            className="col-12 col-sm-6 col-lg-3 mb-3"
        >
            <div className="card h-100 shadow-sm border-0">
                <div className="card-body">
                    <div className="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 className="card-subtitle text-muted mb-2">{title}</h6>
                            <h4 className="fw-bold mb-0" style={{ color }}>{value}</h4>
                            {subtitle && <small className="text-muted">{subtitle}</small>}
                        </div>
                        <div className={`p-2 rounded`} style={{ backgroundColor: `${color}15` }}>
                            <Icon size={20} style={{ color }} />
                        </div>
                    </div>
                </div>
            </div>
        </motion.div>
    );

    return (
        <>
            <SCMNavbar />
            
             {/* ZONE MULTI CHECKLIST - SAME LAYOUT AS FACILITY */}
 <div className="content mb-5 pb-5" style={{ paddingBottom: "80px" }}>

                <div className="row align-items-center">
                    <div className="col-md-3">
                        <label className="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                            <Building2 size={20} className="text-primary" />
                            Select Zone
                        </label>
                    </div>

       <div className="col-md-9">
    {loading ? (
        <div className="d-flex align-items-center gap-2 text-muted">
            <div className="spinner-border spinner-border-sm text-primary"></div>
            <span>Loading zones...</span>
        </div>
    ) : (
        <div
            className="zone-multiselect-container"
            ref={zoneDropdownRef}
        >
            {/* INPUT AREA (chips + placeholder) */}
            <div
                className={`zone-multiselect-input ${isZoneDropdownOpen ? "zone-multiselect-input-open" : ""}`}
                onClick={() => {
                    setIsZoneDropdownOpen(prev => !prev);
                    setTempSelectedZone(selectedZone);
                    setZoneSearch("");
                }}
            >
                {selectedZone.length === 0 && (
                    <span className="zone-placeholder">-- Choose Zone --</span>
                )}

                {selectedZone.length > 0 && (
                    <div className="zone-chips-wrapper">
                        {selectedZone.slice(0, 3).map(z => (
                            <span key={z} className="zone-chip">
                                {z}
                                <button
                                    type="button"
                                    className="zone-chip-remove"
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        const updated = selectedZone.filter(v => v !== z);
                                        setSelectedZone(updated);
                                        if (updated.length > 0) {
                                            fetchPivot(updated.join(","));
                                        } else {
                                            setPivotData([]);
                                            setChartData([]);
                                            setPieChartData([]);
                                            setSummaryStats(null);
                                        }
                                    }}
                                >
                                    <X size={12} />
                                </button>
                            </span>
                        ))}

                        {selectedZone.length > 3 && (
                            <span className="zone-chip zone-chip-more">
                                +{selectedZone.length - 3} more
                            </span>
                        )}
                    </div>
                )}

                <span className="zone-chevron">
                    <ChevronDown size={18} />
                </span>
            </div>

            {/* DROPDOWN PANEL */}
            {isZoneDropdownOpen && (
                <div className="zone-dropdown-panel">
                    {/* SEARCH BAR */}
                    <div className="zone-search-wrapper">
                        <Search size={14} className="zone-search-icon" />
                        <input
                            type="text"
                            className="zone-search-input"
                            placeholder="Search zone..."
                            value={zoneSearch}
                            onChange={e => setZoneSearch(e.target.value)}
                        />
                    </div>

                    {/* LIST ZONE */}
                    <div className="zone-list">
                        {filteredZones.length === 0 && (
                            <div className="zone-empty">No zone found</div>
                        )}

                        {filteredZones.map((z, idx) => {
                            const isChecked = tempSelectedZone.includes(z.zone);
                            return (
                                <label
                                    key={idx}
                                    className="zone-item"
                                    onClick={() => {
                                        let updated = isChecked
                                            ? tempSelectedZone.filter(v => v !== z.zone)
                                            : [...tempSelectedZone, z.zone];
                                        setTempSelectedZone(updated);
                                    }}
                                >
                                    <input
                                        type="checkbox"
                                        className="form-check-input me-2"
                                        checked={isChecked}
                                        readOnly
                                    />
                                    <span>{z.zone}</span>
                                </label>
                            );
                        })}
                    </div>

                    {/* ACTION BUTTONS */}
                    <div className="zone-actions">
                        <button
                            type="button"
                            className="btn btn-sm btn-outline-secondary"
                            onClick={() => {
                                setTempSelectedZone([]);
                            }}
                        >
                            Clear
                        </button>

                        <div className="ms-auto d-flex gap-2">
                            <button
                                type="button"
                                className="btn btn-sm btn-light"
                                onClick={() => {
                                    setIsZoneDropdownOpen(false);
                                    setTempSelectedZone(selectedZone);
                                    setZoneSearch("");
                                }}
                            >
                                Cancel
                            </button>

                            <button
                                type="button"
                                className="btn btn-sm btn-primary"
                                onClick={() => {
                                    setIsZoneDropdownOpen(false);
                                    setSelectedZone(tempSelectedZone);
                                    setZoneSearch("");

                                    if (tempSelectedZone.length > 0) {
                                        fetchPivot(tempSelectedZone.join(","));
                                    } else {
                                        setPivotData([]);
                                        setChartData([]);
                                        setPieChartData([]);
                                        setSummaryStats(null);
                                    }
                                }}
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    )}

    <small className="text-muted mt-2 d-block">
        * You can select more than one zone
    </small>
</div>


    </div>
{/* IF NO ZONE SELECTED — SHOW PLACEHOLDER CARD */}
{selectedZone.length === 0 && (
    <div className="card shadow-sm border-0 mt-3 p-5 text-center">

        <div className="text-center">
            <Building2
                size={48}
                className="text-primary mb-3 opacity-50"
            />
        </div>

        <h4 className="fw-bold text-dark">
            Please Select Zone
        </h4>

    </div>
)}





                    {/* SUMMARY CARDS */}
                    {!loading && summaryStats && (
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.2 }}
                            className="row mb-4"
                        >
                            <StatCard
                                title="Total Facilities"
                                value={summaryStats.totalFacilities}
                                icon={Building2}
                                color="#3b82f6"
                            />
                            <StatCard
                                title="Total Asset"
                                value={summaryStats.totalArmada}
                                icon={Truck}
                                color="#10b981"
                            />
                            <StatCard
                                title="Average Asset"
                                value={summaryStats.averagePerFacility}
                                icon={BarChart3}
                                color="#f59e0b"
                            />
                            <StatCard
                                title="Facility with Most Assets"
                                value={summaryStats.topFacility}
                                icon={TrendingUp}
                                color="#ef4444"
                                // subtitle="Highest Asset"
                            />
                        </motion.div>
                    )}

                    {!loading && chartData.length > 0 && (
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.3 }}
                            className="row mb-4"
                        >
                            {/* BAR CHART */}
                            <div className="col-12 col-xl-8 mb-4">
                                <div className="card p-3 shadow-sm border-0 h-100">
                                    <h5 className="fw-bold mb-3 d-flex align-items-center text-dark">
                                        <BarChart3 size={20} className="me-2 text-primary" />
                                         Fleet per Facility - Zone {selectedZone.join(" , ")}
                                    </h5>
                                    <div style={{ width: "100%", height: 400 }}>
                                        <ResponsiveContainer>
                                            <BarChart
                                                data={chartData}
                                                margin={{ top: 20, right: 30, left: 20, bottom: 60 }}
                                            >
                                                <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                                        <XAxis 
                                            dataKey="facility" 
                                            angle={-45}
                                            textAnchor="end"
                                            tick={{ style: { fontSize: 12 } }}
                                        />
                                                <YAxis tick={{ fontSize: 12 }} />
                                                <Tooltip 
                                                    contentStyle={{ 
                                                        backgroundColor: '#fff',
                                                        border: '1px solid #e2e8f0',
                                                        borderRadius: '8px'
                                                    }}
                                                />
                                                <Legend 
                                                    wrapperStyle={{
                                                        paddingTop: "20px"
                                                    }}
                                                />

                                                {dynamicColumns.map((col, idx) => (
                                                    <Bar 
                                                        key={idx} 
                                                        dataKey={col} 
                                                        fill={chartColors[idx]}
                                                        name={col}
                                                        radius={[2, 2, 0, 0]}
                                                    />
                                                ))}
                                            </BarChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                            </div>
                            {/* PIE CHART */}
                            <div className="col-12 col-xl-4 mb-4">
                                <div className="card p-3 shadow-sm border-0 h-100">
                                    <h5 className="fw-bold mb-3 d-flex align-items-center text-dark">
                                        <PieChart size={20} className="me-2 text-primary" />
                                        Top 3 Facilities
                                    </h5>
                                    <div style={{ width: "100%", height: 400 }}>
                                        <ResponsiveContainer>
                                            <RechartsPieChart>
                                                <Pie
    data={pieChartData}
    cx="50%"
    cy="50%"
    labelLine={false}
    label={renderCustomLabel}   //  << ✔ custom label aktif
    outerRadius={120}
    fill="#8884d8"
    dataKey="value"
>
                                                    {pieChartData.map((entry, index) => (
                                                        <Cell key={`cell-${index}`} fill={pieChartColors[index]} />
                                                    ))}
                                                </Pie>
                                                <Tooltip 
                                                    formatter={(value) => [`${value} armada`, 'Jumlah']}
                                                    contentStyle={{ 
                                                        backgroundColor: '#fff',
                                                        border: '1px solid #e2e8f0',
                                                        borderRadius: '8px'
                                                    }}
                                                />
                                            </RechartsPieChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    )}


                    {/* TABLE */}
                    {!loading && pivotData.length > 0 && (
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.5 }}
                            className="card p-3 shadow-sm border-0 mb-4"
                        >
                            <div className="d-flex justify-content-between align-items-center mb-3">
                                <h5 className="fw-bold mb-0 text-dark">
                                    Fleet Detail Data - Zone {selectedZone}
                                </h5>
                                <span className="badge bg-primary">
                                    Total: {pivotData.length} Facility
                                </span>
                            </div>
                            
                            <div className="table-responsive">
                                <table className="table table-bordered table-striped table-hover mb-0">
                                    <thead className="table-primary">
                                        <tr>
                                            <th>Facility</th>
                                    {dynamicColumns.map((col, idx) => (
                                        <th key={idx}>{col}</th>
                                    ))}
                                            <th className="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {pivotData.map((row, idx) => (
                                            <tr key={idx}>
                                                <td className="fw-semibold">{row.facility}</td>

                                                {dynamicColumns.map((col, i2) => (
                                                    <td key={i2} className="text-center fw-bold">
                                                        {row.armada[col] ?? 0}
                                                    </td>
                                                ))}

                                                <td className="fw-bold text-primary text-center">
                                                    {row.total_armada}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                            
                        </motion.div>
                    )}
                    </div>
        </>
    );
};

export default SummaryAsset;