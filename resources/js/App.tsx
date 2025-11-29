import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import TrendDailyReport from "./pages/TrendDailyReport";
import CompanyProfile from "./pages/CompanyProfile";
import SummaryAsset from "./pages/SummaryAsset";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";

// Router Mapping
const App = () => (
  <BrowserRouter>
    <Routes>
      <Route path="/admin/transport/report" element={<TrendDailyReport />} />
      <Route path="/admin/transport/scm-profile" element={<CompanyProfile />} />
      <Route path="/admin/transport/assetscm" element={<SummaryAsset />} />

      {/* fallback */}
      <Route
        path="*"
        element={
          <div className="container py-5 text-center text-muted">
            <h4>Halaman tidak ditemukan</h4>
          </div>
        }
      />
    </Routes>
  </BrowserRouter>
);

ReactDOM.createRoot(document.getElementById("root")!).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
