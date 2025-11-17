import React, { useState } from 'react';
import FilterForm from '../components/FilterForm';
import DataTable from '../components/DataTable';
import SLAChart from '../components/Charts/SlaChart';
import SummaryCard from '../components/SummaryCard';

interface ReportData {
  slaCustomer?: any[];
  prodCustomer?: any[];
  prodStore?: any[];
}

const DailyReportTransport: React.FC = () => {
  const [data, setData] = useState<ReportData | null>(null);
  const [loading, setLoading] = useState(false);
  const [alert, setAlert] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  const handleFetch = async (payload: any) => {
    setLoading(true);
    setAlert(null);

    try {
      const res = await fetch('/transport/dailyreport/data', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
        },
        body: JSON.stringify(payload),
      });

      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const result = await res.json();
      setData(result.data);

      // Notifikasi sukses
      setAlert({ type: 'success', message: `Data berhasil dimuat untuk ${payload.facility}` });
    } catch (err: any) {
      console.error(err);
      setAlert({ type: 'error', message: `Gagal mengambil data: ${err.message}` });
    } finally {
      setLoading(false);
      // Hilangkan notifikasi otomatis setelah 5 detik
      setTimeout(() => setAlert(null), 5000);
    }
  };

  return (
    
    <div className="p-6 bg-gray-50 min-h-screen">
      <h1 className="text-2xl font-bold mb-4 text-gray-700">DAILY REPORT TRANSPORT</h1>

      {/* 🔔 Notifikasi Alert */}
      {alert && (
        <div
          className={`mb-4 p-3 rounded-lg shadow-md border-l-4 transition-all ${
            alert.type === 'success'
              ? 'bg-green-50 border-green-500 text-green-700'
              : 'bg-red-50 border-red-500 text-red-700'
          }`}
        >
          {alert.message}
        </div>
      )}

      <div className="bg-white rounded-xl shadow-md p-5">
        <FilterForm onSubmit={handleFetch} loading={loading} />

        {data && (
          <>
            <div className="grid md:grid-cols-3 gap-4 mb-6">
              <SummaryCard title="SLA Customer" value={data.slaCustomer?.length || 0} color="blue" />
              <SummaryCard title="Produktivitas Customer" value={data.prodCustomer?.length || 0} color="green" />
              <SummaryCard title="Produktivitas Store" value={data.prodStore?.length || 0} color="cyan" />
            </div>

            {data.slaCustomer && <SLAChart data={data.slaCustomer} />}
            {data.prodCustomer && <DataTable title="Produktivitas Customer" data={data.prodCustomer} />}
            {data.prodStore && <DataTable title="Produktivitas Store" data={data.prodStore} />}
          </>
        )}
      </div>
    </div>
  );
};

export default DailyReportTransport;
