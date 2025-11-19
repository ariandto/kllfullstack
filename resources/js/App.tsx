import React from "react";
import ReactDOM from "react-dom/client";
import TrendDailyReport from "./pages/TrendDailyReport";
import CompanyProfile from "./pages/CompanyProfile";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";
import OvertimeDriver from "./pages/OvertimeDriver";

const path = window.location.pathname;

let Page;
if (path.includes("/admin/transport/report")) {
  Page = TrendDailyReport;
}
else if (path.includes("/admin/transport/scm-profile")) {
    Page = CompanyProfile;
}

else if (path.includes("/driver/overtime")) {
    Page = OvertimeDriver;
} else {
  Page = () => (
    <div className="container py-5 text-center text-muted">
      <h4>Halaman tidak ditemukan</h4>
    </div>
  );
}

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(
  <React.StrictMode>
    <Page />
  </React.StrictMode>
);
