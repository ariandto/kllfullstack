import React, { useState, useEffect } from "react";

const SummaryDcandHub = () => {
    return (
        <div
            style={{
                minHeight: "100vh",
                background: "linear-gradient(135deg, #fffcfcff 0%, #f1e8e8ff 100%)",
            }}
        >
            <div className="content container-fluid px-3 px-md-5 mb-5 py-4">
                {/* Premium Header */}
                <div className="mb-4">
                    <div className="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h1 className="display-5 fw-bold text-dark mb-2">SCM Transport Profile</h1>
                        </div>
                    </div>
                </div>

                <nav className="navbar navbar-expand-lg" style={{ background: "#ffffff" }}>
                    <div className="container-fluid px-3">
                        <button className="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#coverageNavbar">
                            <span className="navbar-toggler-icon"></span>
                        </button>

                        <div className="collapse navbar-collapse" id="coverageNavbar">
                            <ul className="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3">
                                <li className="nav-item">
                                    <a className="nav-link text-dark fw-semibold" href="#coverage-summary">
                                        Summary DC & HUB
                                    </a>
                                </li>

                                <li className="nav-item">
                                    <a className="nav-link text-dark fw-semibold" href="#coverage-city">
                                        SLA and Orders
                                    </a>
                                </li>

                                <li className="nav-item">
                                    <a className="nav-link text-dark fw-semibold" href="#coverage-city">
                                        Dept. Structure
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    );
};

export default SummaryDcandHub;
