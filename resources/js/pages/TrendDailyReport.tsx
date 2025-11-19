import React, { useEffect, useState } from "react";
import axios from "axios";
import {
  Spinner, Card, Row, Col, Form, Button, Alert
} from "react-bootstrap";
import {
  ResponsiveContainer, ComposedChart, Line, Bar,
  CartesianGrid, XAxis, YAxis, Tooltip, Legend,
  LabelList
} from "recharts";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import "./TrendDailyReport.css";
import API_URL from "../config/api";


interface Site { Facility: string; }
interface DataRow { [key: string]: any; }

const TrendDailyReport: React.FC = () => {
  const [sites, setSites] = useState<Site[]>([]);
  const [selectedSite, setSelectedSite] = useState("");
  const [startDate, setStartDate] = useState("");
  const [endDate, setEndDate] = useState("");
  const [slaData, setSlaData] = useState<DataRow[]>([]);
  const [olfData, setOlfData] = useState<DataRow[]>([]);
  const [ujpData, setUjpData] = useState<DataRow[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");


  useEffect(() => {
    axios
      .get(`${API_URL}/admin/transport/get-site-list`, { withCredentials: true })
      .then(res => setSites(res.data.data || []))
      .catch(() => setError("Gagal memuat daftar site"));
  }, []);

  // 🚀 Auto-detect dataset (summary, OLF, expense)
  const fetchReportData = async () => {
    if (!startDate || !endDate || !selectedSite) {
      setError("Harap isi semua filter (tanggal & site)");
      return;
    }

    setLoading(true);
    setError("");

    const startTime = performance.now();

    try {
      const res = await axios.post(
        `${API_URL}/admin/transport/trend-data`,
        { startDate, endDate, site: selectedSite.trim() },
        { timeout: 180000, withCredentials: true }
      );

      const raw = res.data?.data || res.data || {};

      const resultSets = Array.isArray(raw) ? raw : Object.values(raw);

      let summarySet: any[] = [];
      let olfSet: any[] = [];
      let expenseSet: any[] = [];

      // 🔍 DETEKSI dataset berdasarkan nama kolom SQL
      resultSets.forEach((set: any[]) => {
  if (!Array.isArray(set) || set.length === 0) return;

  const col = Object.keys(set[0]).map(k => k.toLowerCase());

  // summary
  if (
    col.includes("trendsla") ||
    col.includes("pendingint") ||
    col.includes("pendingext")
  ) {
    summarySet = set;
  }

  // olf: Trip + StandarOlf + OLF
  else if (
    col.includes("trip") &&
    (col.includes("standarolf") || col.includes("standardolf"))
  ) {
    olfSet = set;
  }

  // expense: PASTIKAN mendeteksi UJP kolom utama
  else if (
  col.includes("expense") ||
  col.includes("expensestore") ||
  col.includes("expensecustomer") ||
  col.includes("droppoint") ||
  col.includes("ujpperdroppoint") ||              // 🟩 FIX TERPENTING
  col.includes("ujpperdroppoint_store") ||
  col.includes("ujpperdroppoint_customer")
) {
  expenseSet = set;
}

});


      console.log("🟦 Summary Detected:", summarySet);
      console.log("🟧 OLF Detected:", olfSet);
      console.log("🟩 Expense Detected:", expenseSet);

      // 🧩 NORMALISASI
      setSlaData(normalizeData(summarySet));
      setOlfData(normalizeData(olfSet));
      setUjpData(normalizeData(expenseSet));

      // ❗Jika semua kosong
      if (summarySet.length === 0 && olfSet.length === 0 && expenseSet.length === 0) {
        setError("Tidak ada data dikembalikan dari server.");
      }

      // 🕒 Durasi Eksekusi
      const endTime = performance.now();
      const duration = ((endTime - startTime) / 1000).toFixed(2);

      toast.success(`Data berhasil dimuat dalam ${duration} detik`, {
        position: "top-right", autoClose: 3000, hideProgressBar: false, theme: "colored"
      });

    } catch (err: any) {
      console.error("❌ Error load report:", err);

      if (err.response) {
        setError(err.response.data?.message || `Server error: ${err.response.status}`);
      } else if (err.request) {
        setError("Tidak ada respons dari server (network/CORS).");
      } else {
        setError(err.message || "Gagal memuat data laporan");
      }

    } finally {
      setLoading(false);
    }
  };

  // 🔧 NORMALISASI tanggal (gabungkan banyak variasi nama kolom SQL)
  const normalizeData = (data: any[]) => {
    return data
      .map((d) => {
        const date =
          d.PlanDeliveryDate ||
          d.plandeliverydate ||
          d.PLANDELIVERYDATE ||
          d.planDeliveryDate ||
          "";

        return { ...d, PlanDeliveryDate: date };
      })
      .filter((d) => d.PlanDeliveryDate)
      .sort(
        (a, b) =>
          new Date(a.PlanDeliveryDate).getTime() -
          new Date(b.PlanDeliveryDate).getTime()
      );
  };

  // 📊 Hitung summary KPI
  const getSummary = () => {
    if (slaData.length === 0) return null;

    const avgSLA =
      slaData.reduce(
        (a, b) => a + parseFloat(b["Trend SLA"] || b["TrendSLA"] || 0),
        0
      ) / slaData.length;

    const avgPending =
      slaData.reduce(
        (a, b) =>
          a +
          parseFloat(b["% Pending External"] || b["%PendingExternal"] || 0) +
          parseFloat(b["% Pending Internal"] || b["%PendingInternal"] || 0),
        0
      ) / slaData.length;

    const avgOLF =
      olfData.length > 0
        ? (
            olfData.reduce(
              (a, b) => a + parseFloat(b["OLF"] || b["olf"] || 0),
              0
            ) / olfData.length
          ).toFixed(2)
        : "0";

    const avgUJP =
  ujpData.length > 0
    ? (
        ujpData.reduce(
          (a, b) =>
            a +
            parseFloat(
              b["UJPperDropPoint"] ||
              b["ujpperdroppoint"] ||
              0
            ),
          0
        ) / ujpData.length
      ).toFixed(2)
    : "0";


    return {
      avgSLA: avgSLA.toFixed(2),
      avgPending: avgPending.toFixed(2),
      avgOLF,
      avgUJP
    };
  };

  const summary = getSummary();

  const formatRupiah = (value: number) => {
    return value.toLocaleString("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    });
  };

  const CustomTooltip = ({ active, payload, label }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white p-3 border rounded shadow-sm" style={{ minWidth: 220 }}>
        <p className="fw-bold mb-2 text-primary">
          {new Date(label).toLocaleDateString("id-ID", {
            weekday: "long", day: "numeric", month: "long", year: "numeric",
          })}
        </p>

        {payload.map((entry: any, index: number) => {
          const key = String(entry.name || entry.dataKey || "").toLowerCase();

          const isPercent = /sla|olf|pending|drop/i.test(key);
          const isExpenseCustomer = key.includes("expensecustomer");
          const isUjp = key.includes("ujpperdrop") || key.includes("ujpperdroppoint");

          // nilai raw
          const rawVal = entry.value;

          let display;
          if (typeof rawVal === "number") {
            if (isPercent) display = rawVal.toFixed(2) + "%";
            else if (isExpenseCustomer || isUjp) display = formatRupiah(rawVal);
            else display = rawVal.toLocaleString("id-ID");
          } else display = rawVal;

          return (
            <div key={index} className="d-flex justify-content-between mb-1">
              <span style={{ color: entry.color }} className="fw-semibold">● {entry.name}:</span>
              <span>{display}</span>
            </div>
          );
        })}
      </div>
    );
  }
  return null;
};


  const ChartCard = ({ title, data, bars, lines }: any) => {
    const isDualAxis = lines.some((l: string) =>
      l.toLowerCase().match(/sla|pending|olf|drop/)
    );

    const formatValue = (v: number) => {
      if (v >= 1_000_000) return (v / 1_000_000).toFixed(1) + "M";
      if (v >= 1_000) return (v / 1_000).toFixed(1) + "K";
      return v.toLocaleString("id-ID");
    };

    return (
      <Card className="shadow-sm mb-4 border-0">
        <Card.Body>
          <h5 className="fw-semibold text-secondary mb-3">
            <i className="bi bi-graph-up"></i> {title}
          </h5>

          <div style={{ width: "100%", height: 420 }}>
            <ResponsiveContainer>

              <ComposedChart
                data={data}
                margin={{ top: 40, right: 70, left: 50, bottom: 70 }}
              >
                <CartesianGrid strokeDasharray="3 3" stroke="#e0e0e0" />

                <XAxis
                  dataKey="PlanDeliveryDate"
                  angle={-45}
                  textAnchor="end"
                  height={80}
                  tick={{ fontSize: 11 }}
                  tickFormatter={(date) =>
                    new Date(date).toLocaleDateString("id-ID", {
                      day: "2-digit",
                      month: "short",
                    })
                  }
                />

                <YAxis
                  yAxisId="left"
                  domain={[0, "auto"]}
                  tickFormatter={formatValue}
                  label={{
                    value: "Nilai / Jumlah Order",
                    angle: -90,
                    position: "insideLeft",
                    style: { fontSize: 12 },
                  }}
                />

                {isDualAxis && (
                  <YAxis
                    yAxisId="right"
                    orientation="right"
                    domain={[0, 100]}
                    tickCount={6}
                    label={{
                      value: "Persentase (%)",
                      angle: 90,
                      position: "insideRight",
                      style: { fontSize: 12 },
                    }}
                  />
                )}

                <Tooltip content={<CustomTooltip />} />
                <Legend verticalAlign="top" height={36} />

                {/* BARS */}
                {bars.map((b: string, i: number) => {
                  let fillColor = `hsl(${i * 40 + 200},70%,55%)`;
                  if (b.toLowerCase().includes("store")) fillColor = "#0d6efd";
                  else if (b.toLowerCase().includes("customer")) fillColor = "#00a2ff";
                  else if (b.toLowerCase().includes("trip")) fillColor = "#da3c31";
                  else if (b.toLowerCase().includes("order")) fillColor = "#0b9c47";
                  else if (b.toLowerCase().includes("expensestore")) fillColor = "#9c450b";
                  else if (b.toLowerCase().includes("expenseother"))fillColor = "#ff6f00";

                  return (
                    <Bar
                      key={i}
                      yAxisId="left"
                      dataKey={b}
                      fill={fillColor}
                      name={b}
                      radius={[8, 8, 0, 0]}
                      maxBarSize={45}
                      barSize={30}
                    >
                      <LabelList
                        dataKey={b}
                        position="top"
                        style={{
                          fontSize: 11,
                          fill: "#0a0a0aff",
                          fontWeight: 700,
                        }}
                        formatter={(v: any) => {
  if (typeof v !== "number") return v;
  const key = String(b).toLowerCase();
  if (key.includes("expensecustomer") || key.includes("ujpperdrop") || key.includes("ujpperdroppoint")) {
    return formatRupiah(v);
  }
  return formatValue(v);
                        }
                        }
                      />
                    </Bar>
                  );
                })}

                {/* LINES */}
                {lines.map((l: string, i: number) => {
                  const color =
                    l.toLowerCase().includes("droppoint")
                      ? "#0b0c0bff"
                      : l.toLowerCase().includes("standar")
                      ? "#dc3545"
                      : l.toLowerCase().includes("olf")
                      ? "#ff9800"
                      : l.toLowerCase().includes("pendinginternal")
                      ? "#c7091c"
                      : l.toLowerCase().includes("pendingexternal")
                      ? "#0a9c42"
                      : "#198754";

                  return (
                    <Line
                      key={i}
                      yAxisId={isDualAxis ? "right" : "left"}
                      type="monotone"
                      dataKey={l}
                      stroke={color}
                      strokeWidth={2.5}
                      dot={{ r: 4, strokeWidth: 2, fill: "#fff" }}
                      activeDot={{ r: 6 }}
                      name={l}
                    >
                      <LabelList
                        dataKey={l}
                        position="top"
                        offset={10}
                        style={{
                          fontSize: 10,
                          fontWeight: 600,
                          fill: color,
                        }}
                        formatter={(v: any) =>
                          typeof v === "number" ? v.toFixed(2) + "%" : v
                        }
                      />
                    </Line>
                  );
                })}

              </ComposedChart>

            </ResponsiveContainer>
          </div>
        </Card.Body>
      </Card>
    );
  };

  return (
    <div className="content position-relative">
      <ToastContainer />

      <h3 className="fw-semibold text-primary mt-5 mb-4" style={{ paddingTop: "10px" }}>
        <i className="bi bi-bar-chart-line"></i> Trend Daily Report Transport
      </h3>

      {error && <Alert variant="danger">{error}</Alert>}

      <Card className="shadow-sm border-0 mb-4">
        <Card.Body>
          <Row className="g-3">
            <Col md={3}>
              <Form.Label className="fw-semibold">Start Date</Form.Label>
              <Form.Control
                type="date"
                value={startDate}
                onChange={(e) => setStartDate(e.target.value)}
              />
            </Col>
            <Col md={3}>
              <Form.Label className="fw-semibold">End Date</Form.Label>
              <Form.Control
                type="date"
                value={endDate}
                onChange={(e) => setEndDate(e.target.value)}
              />
            </Col>
            <Col md={3}>
              <Form.Label className="fw-semibold">Site</Form.Label>
              <Form.Select
                value={selectedSite}
                onChange={(e) => setSelectedSite(e.target.value)}
              >
                <option value="">-- Pilih Site --</option>
                {sites.map((s, i) => (
                  <option key={i}>{s.Facility}</option>
                ))}
              </Form.Select>
            </Col>
            <Col md={3} className="d-flex align-items-end">
              <Button
                variant="primary"
                className="w-100"
                onClick={fetchReportData}
                disabled={loading}
              >
                {loading ? <Spinner animation="border" size="sm" /> : "Tampilkan"}
              </Button>
            </Col>
          </Row>
        </Card.Body>
      </Card>

      {/* LOADING */}
      {loading && (
        <div className="text-center py-5">
          <Spinner animation="border" variant="primary" />
          <p className="mt-2 text-muted">Sedang memuat data...</p>
        </div>
      )}

      {/* CHARTS */}
      {!loading && slaData.length > 0 && (
        <>
          <ChartCard
            title="SLA Monitoring - Trend vs Standar"
            data={slaData}
            bars={["OrderCustomer"]}
            lines={["Trend SLA", "TrendSLA", "Standar SLA", "StandarSLA"]}
          />
          <ChartCard
            title="Pending Monitoring - External vs Internal"
            data={slaData}
            bars={["OrderCustomer"]}
            lines={[
              "PersentasePendingInternal",
              "PersentasePendingExternal",
            ]}
          />
          <ChartCard
  title="Trend UJP OLF Store - UJP & Expense vs OLF"
  data={ujpData}
  bars={["UJPperDropPoint_Store", "ExpenseStore"]}
  lines={["OLF", "olf"]}
/>

          <ChartCard
            title="Expense vs DropPoint"
            data={ujpData}
            bars={["ExpenseStore", "ExpenseCustomer", "ExpenseOther"]}
            lines={["DropPoint"]}
          />
        </>
      )}
    </div>
  );
};

export default TrendDailyReport;
