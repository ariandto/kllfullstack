import React from "react";
import { useNavigate, useLocation } from "react-router-dom";

const SCMNavbar: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();

    // Cek apakah path saat ini adalah menu aktif
    const isActive = (path: string) => location.pathname === path;

    return (
        <>
            <div className="content">
                <h1 className="display-5 fw-bold text-dark mb-2">
                    SCM Transport Profile
                </h1>
            </div>

            <nav
                className="navbar navbar-expand-lg navbar-dark"
                style={{
                    background: "transparent",
                    position: "sticky",
                    top: 0,
                    zIndex: 9000,
                }}
            >
                <div className="container-fluid px-3">

                    <button
                        className="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#coverageNavbar"
                    >
                        <span className="navbar-toggler-icon"></span>
                    </button>

                    <div className="collapse navbar-collapse" id="coverageNavbar">
                        <ul className="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3">

                            {/* ITEM TEMPLATE */}
                            {[
                                { label: "SCM Profile", path: "/admin/transport/scm-profile" },
                                { label: "Asset Armada", path: "/admin/transport/assetscm" },
                                { label: "SLA and Orders", path: "/admin/transport/sla" },
                            ].map((item) => (
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
                `}
            </style>
        </>
    );
};

export default SCMNavbar;
