import React from "react";
import { useNavigate, useLocation } from "react-router-dom";

const SCMNavbar: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();

    const menuItems = [
        { label: "SCM Profile", path: "/admin/transport/scm-profile" },
        { label: "Asset Armada", path: "/admin/transport/assetscm" },
        { label: "SLA and Orders", path: "/admin/transport/sla" },
    ];

    const isActive = (path: string) => location.pathname === path;

    // ambil label halaman aktif
    const activeLabel = menuItems.find((m) => isActive(m.path))?.label;

    return (
        <>
            <div className="content">
                <h1 className="display-5 fw-bold text-dark mb-2">
                    SCM Transport Profile
                </h1>
            </div>

            {/* DESKTOP NAVBAR */}
            <nav
                className="navbar navbar-expand-lg navbar-dark d-none d-md-flex"
                style={{
                    background: "transparent",
                    position: "sticky",
                    top: 0,
                    zIndex: 9000,
                }}
            >
                <div className="container-fluid px-3">

                    <div className="collapse navbar-collapse show">
                        <ul className="navbar-nav ms-auto mb-2 d-flex align-items-center gap-3">
                            {menuItems.map((item) => (
                                <li className="nav-item" key={item.path}>
                                    <span
                                        onClick={() => navigate(item.path)}
                                        className={`
                                            nav-link fw-semibold px-2 
                                            ${isActive(item.path)
                                                ? "active-nav"
                                                : "text-black"
                                            }
                                        `}
                                        style={{ cursor: "pointer" }}
                                    >
                                        {item.label}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            </nav>

            {/* MOBILE BREADCRUMB */}
            <div className="mobile-breadcrumb d-md-none px-3 py-2">
                {menuItems.map((item, index) => (
                    <span
                        key={item.path}
                        onClick={() => navigate(item.path)}
                        style={{
                            fontSize: "13px",
                            cursor: "pointer",
                            color: isActive(item.path) ? "#0d6efd" : "#6c757d",
                            fontWeight: isActive(item.path) ? "600" : "400",
                        }}
                    >
                        {item.label}
                        {index < menuItems.length - 1 && (
                            <span style={{ color: "#999" }}> / </span>
                        )}
                    </span>
                ))}
            </div>

            {/* CUSTOM CSS */}
            <style>
                {`
                    .nav-link {
                        transition: all 0.25s ease-in-out;
                    }
                    .nav-link:hover {
                        color: #0d6efd !important;
                        border-bottom: 2px solid #0d6efd;
                    }
                    .active-nav {
                        color: #0d6efd !important;
                        border-bottom: 3px solid #0d6efd;
                        font-weight: bold;
                    }

                    .mobile-breadcrumb {
                        background: #f8f9fa;
                        border-radius: 6px;
                    }
                `}
            </style>
        </>
    );
};

export default SCMNavbar;
