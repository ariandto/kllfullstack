// config/api.ts
// Centralized API configuration for your React + TypeScript app
// Automatically enables withCredentials for Laravel Sanctum / session-based auth

import axios from "axios";

// === API BASE URL ===
// Ganti sesuai domain backend Laravel kamu
export const API_URL = "http://localhost:8000";

// === AXIOS GLOBAL CONFIG ===
axios.defaults.withCredentials = true;
axios.defaults.baseURL = `${API_URL}`;

export default axios;

//
// production copas aja mas replace di atas

// const API_URL = https://scmlogisticapps.klgsys.com




