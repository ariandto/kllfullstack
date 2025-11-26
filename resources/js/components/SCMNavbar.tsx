import React from "react";
import { useNavigate } from "react-router-dom";

const SCMNavbar = () => {
    const navigate = useNavigate();

    return (
        <nav className="navbar navbar-expand-lg" style={{ background: "red" }}>
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

                        {/* MENU 1 */}
                        <li className="nav-item">
                            <a
                                className="nav-link text-dark fw-semibold"
                                style={{ cursor: "pointer" }}
                                onClick={() => navigate("/admin/transport/assetscm")}
                            >
                                Summary Asset Armada
                            </a>
                        </li>

                        {/* MENU 2 */}
                        <li className="nav-item">
                            <a className="nav-link text-dark fw-semibold" href="#coverage-city">
                                SLA and Orders
                            </a>
                        </li>

                        {/* MENU 3 */}
                        <li className="nav-item">
                            <a className="nav-link text-dark fw-semibold" href="#coverage-city">
                                Dept. Structure
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    );
};

export default SCMNavbar;
