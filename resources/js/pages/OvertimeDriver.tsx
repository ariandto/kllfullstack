import React, { useEffect, useMemo, useState } from 'react';
import {
  Container, Row, Col, Card, Table, Button, Form, Badge, Spinner
} from 'react-bootstrap';
import {
  RiTimeLine, RiUserLine, RiFilterLine, RiTableLine, RiSmartphoneLine,
  RiCalendarEventLine, RiPlayCircleLine, RiStopCircleLine, RiTimerLine,
  RiFileTextLine, RiBuildingLine, RiTaskLine, RiUserStarLine, RiCheckboxCircleLine,
  RiQuestionLine, RiInboxLine, RiSearchLine, RiInformationLine, RiBarChartLine
} from 'react-icons/ri';
import API_URL from '../config/api'; // tetap dipertahankan sesuai permintaan

// ---------------------------
//  TYPE
// ---------------------------
type OvertimeItem = {
  Tanggal?: string;
  Jam_Lembur_Roster_Out?: string;
  Jam_Selesai_Lembur?: string;
  Keterangan_Lembur?: string;
  Facility?: string;
  Jobdesc_Lembur?: string;
  Status_Approval?: string;
  Approval_By_System?: string;
  Approved_By_Name?: string;
  Approve_Time?: string;
};

// ---------------------------
//  HELPERS
// ---------------------------
const toYMD = (d: Date) => d.toISOString().slice(0, 10);

function parseDateTime(dateStr?: string, timeStr?: string) {
  if (!dateStr && !timeStr) return null;

  if (timeStr && /\d{4}-\d{2}-\d{2}/.test(timeStr)) {
    const t = new Date(timeStr);
    if (!isNaN(t.getTime())) return t;
  }

  if (dateStr && timeStr) {
    const t = new Date(`${dateStr} ${timeStr}`);
    if (!isNaN(t.getTime())) return t;
  }

  if (dateStr) {
    const t = new Date(dateStr);
    if (!isNaN(t.getTime())) return t;
  }

  if (timeStr) {
    const today = toYMD(new Date());
    const t = new Date(`${today} ${timeStr}`);
    if (!isNaN(t.getTime())) return t;
  }

  return null;
}

function calcDurationHours(item: OvertimeItem) {
  try {
    const start = parseDateTime(item.Tanggal, item.Jam_Lembur_Roster_Out);
    const end = parseDateTime(item.Tanggal, item.Jam_Selesai_Lembur);
    if (!start || !end) return { hours: 0, detail: '-' };

    const diff = end.getTime() - start.getTime();
    if (diff <= 0) return { hours: 0, detail: '-' };

    const diffMin = Math.round(diff / 60000);
    const hours = Math.round((diffMin / 60) * 10) / 10;

    const hh = Math.floor(diffMin / 60);
    const mm = diffMin % 60;
    const detail = hh > 0 ? `${hh} jam ${mm} menit` : `${mm} menit`;

    return { hours, detail };
  } catch {
    return { hours: 0, detail: '-' };
  }
}

// ---------------------------
//  MAIN COMPONENT
// ---------------------------
const OvertimeDriver = () => {
  // default: last 7 days
  const today = new Date();

  // window.__DATA__ fallback (injected by Blade)
  const wdata: any = (window as any).__DATA__ || {};
  const initialStart = wdata.defaultStartDate || toYMD(new Date(today.getTime() - 7 * 86400000));
  const initialEnd = wdata.defaultEndDate || toYMD(today);

  const [startDate, setStartDate] = useState<string>(initialStart);
  const [endDate, setEndDate] = useState<string>(initialEnd);
  const [data, setData] = useState<OvertimeItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // driver info from window.__DATA__
  const driverNik = wdata.nik || "-";
  const driverNama = wdata.nama || "-";

  // ---------------------------
  //  fetchData (POST JSON to /driver/overtime/data)
  // ---------------------------
  const fetchData = async (s = startDate, e = endDate) => {
    setLoading(true);
    setError(null);

    try {
      // CSRF token (Laravel)
      const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
      const csrf = meta?.content || '';

      const payload = {
        start_date: s,
        end_date: e,
      };

      const res = await fetch('/driver/overtime/data', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        credentials: 'same-origin', // ensure cookies/session for Laravel auth
        body: JSON.stringify(payload),
      });

      // handle non-json or error responses
      if (!res.ok) {
        // try to parse json error message
        let errmsg = `HTTP ${res.status}`;
        try {
          const errJson = await res.json();
          if (errJson && errJson.message) errmsg = errJson.message;
          else if (errJson && errJson.error) errmsg = errJson.error;
        } catch (e) { /* ignore json parse error */ }
        throw new Error(errmsg);
      }

      const json = await res.json();

      // controller returns { success: true, data: ..., meta: ... } (per your backend)
      // we accept both array responses or { data: [...] }
      let items: OvertimeItem[] = [];

      if (Array.isArray(json)) items = json as OvertimeItem[];
      else if (json && Array.isArray(json.data)) items = json.data as OvertimeItem[];
      else if (json && json.success && Array.isArray(json.data)) items = json.data as OvertimeItem[];
      else items = [];

      setData(items);

    } catch (err: any) {
      setError(err.message || 'Gagal memuat data');
      setData([]);
    } finally {
      setLoading(false);
    }
  };

  // ---------------------------
  //  totals
  // ---------------------------
  const totals = useMemo(() => {
    let total = 0;
    data.forEach(it => (total += calcDurationHours(it).hours));
    return {
      totalRound: Math.round(total),
      totalDecimal: Math.round(total * 10) / 10,
      total,
    };
  }, [data]);

  // ---------------------------
  //  onSubmitFilter
  // ---------------------------
  const onSubmitFilter = (e: React.FormEvent) => {
    e.preventDefault();
    fetchData(startDate, endDate);
  };

  // initial load: prefer server-injected dates if any
  useEffect(() => {
    // if Blade injected dataLembur directly (rare when using API mode), use it
    if (wdata.dataLembur && Array.isArray(wdata.dataLembur)) {
      setData(wdata.dataLembur);
      return;
    }

    // otherwise call API via POST
    fetchData(initialStart, initialEnd);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // ---------------------------
  //  UI
  // ---------------------------
  return (
    <div className="page-content">
      <Container fluid>

        {/* HEADER */}
        <Row className="mb-4">
          <Col>
            <div className="d-flex justify-content-between align-items-start">

              <div className="d-flex gap-3">
                <div className="icon-circle bg-white shadow-sm">
                  <RiTimeLine size={20} className="text-primary" />
                </div>

                <div>
                  <h4 className="mb-0">Data Lembur Driver</h4>
                  <div className="muted-sm">Ringkasan dan riwayat lembur</div>

                  {/* DRIVER INFO */}
                  <div className="d-flex gap-2 align-items-center mt-2">
                    <div className="icon-circle bg-icon-info">
                      <RiUserLine />
                    </div>
                    <div>
                      <div className="text-muted-sm">Informasi Driver</div>
                      <div className="fw-semibold">{driverNama}</div>
                      <div className="text-muted-sm"><strong>NIK:</strong> {driverNik}</div>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </Col>
        </Row>

        {/* FILTER SECTION */}
        <Row className="mb-4">
          <Col xs={12} md={6}>
            <div className="total-overtime-card">
              <div className="d-flex justify-content-between align-items-center">
                <div className="d-flex gap-3 align-items-center">
                  <RiTimeLine size={26} />
                  <div>
                    <div className="h4 mb-0">{totals.totalRound} Jam</div>
                    <div className="muted-sm">Total Jam Lembur</div>
                    {totals.totalRound !== totals.total && (
                      <div className="small opacity-90 mt-1">Detail: {totals.totalDecimal} Jam</div>
                    )}
                  </div>
                </div>

                <div className="text-end">
                  <small><RiCalendarEventLine /> Periode</small>
                  <div>{startDate} s/d {endDate}</div>
                </div>
              </div>
            </div>
          </Col>

          <Col xs={12} md={6}>
            <Card className="card-modern p-3">
              <Form onSubmit={onSubmitFilter} className="row g-2">

                <Col xs={12} sm={5}>
                  <Form.Label className="small">Tanggal Mulai</Form.Label>
                  <Form.Control type="date" value={startDate} onChange={e => setStartDate(e.target.value)} />
                </Col>

                <Col xs={12} sm={5}>
                  <Form.Label className="small">Tanggal Selesai</Form.Label>
                  <Form.Control type="date" value={endDate} onChange={e => setEndDate(e.target.value)} />
                </Col>

                <Col xs={12} sm={2}>
                  <Button type="submit" className="w-100" disabled={loading}>
                    {loading ? <Spinner size="sm" /> : <RiSearchLine className="me-1" />}
                    Tampilkan
                  </Button>
                </Col>

              </Form>
            </Card>
          </Col>
        </Row>

        {/* TABLE */}
        <Row>
          <Col>
            <Card className="card-modern">
              <Card.Body>

                {error && <div className="alert alert-danger">{error}</div>}

                <div className="table-responsive">
                  <Table hover className="align-middle">
                    <thead className="table-light">
                      <tr>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th className="text-center">Durasi (Jam)</th>
                        <th>Keterangan</th>
                        <th>Facility</th>
                        <th>Jobdesc</th>
                        <th>Sts. People Pro</th>
                        <th>Sts. Appr. MGR</th>
                        <th>Disetujui Oleh</th>
                      </tr>
                    </thead>

                    <tbody>
                      {loading ? (
                        <tr>
                          <td colSpan={10} className="text-center py-4">
                            <Spinner /> Memuat...
                          </td>
                        </tr>
                      ) : data.length === 0 ? (
                        <tr>
                          <td colSpan={10} className="text-center text-muted py-4">
                            <RiInboxLine size={36} />
                            <div>Tidak ada data</div>
                          </td>
                        </tr>
                      ) : (
                        data.map((item, i) => {
                          const { hours, detail } = calcDurationHours(item);
                          const hoursRound = Math.round(hours);
                          return (
                            <tr key={i}>
                              <td><RiCalendarEventLine className="me-1" /> {item.Tanggal ?? '-'}</td>
                              <td><RiPlayCircleLine className="me-1" /> {item.Jam_Lembur_Roster_Out ?? '-'}</td>
                              <td><RiStopCircleLine className="me-1" /> {item.Jam_Selesai_Lembur ?? '-'}</td>
                              <td className="text-center">
                                <Badge bg="primary" pill className="badge-pill"><RiTimerLine className="me-1" /> {hoursRound} Jam</Badge>
                                {hoursRound !== hours && <div className="text-muted-sm mt-1">{hours} jam</div>}
                              </td>
                              <td>{item.Keterangan_Lembur ?? '-'}</td>
                              <td>{item.Facility ?? '-'}</td>
                              <td>{item.Jobdesc_Lembur ?? '-'}</td>
                              <td>{item.Approval_By_System ?? '-'}</td>
                              <td>
                                {item.Status_Approval === 'Disetujui' ? (
                                  <Badge bg="success" pill className="badge-pill"><RiCheckboxCircleLine className="me-1" /> Disetujui</Badge>
                                ) : item.Status_Approval === 'Menunggu' ? (
                                  <Badge bg="warning" text="dark" pill className="badge-pill"><RiTimeLine className="me-1" /> Menunggu</Badge>
                                ) : (
                                  <Badge bg="secondary" pill className="badge-pill"><RiQuestionLine className="me-1" /> {item.Status_Approval ?? 'N/A'}</Badge>
                                )}
                              </td>
                              <td>
                                <div>{item.Approved_By_Name ?? '-'}</div>
                                {item.Approve_Time && <div className="text-muted-sm"><RiTimeLine className="me-1" /> {item.Approve_Time}</div>}
                              </td>
                            </tr>
                          );
                        })
                      )}
                    </tbody>

                  </Table>
                </div>

              </Card.Body>
            </Card>
          </Col>
        </Row>

      </Container>
    </div>
  );
};

export default OvertimeDriver;
