import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  base: "/build/",
  plugins: [
    laravel({
      input: [
        "resources/js/App.tsx",
        "resources/css/app.css",
      ],
      refresh: true,
      buildDirectory: "build", // Laravel akan cari di /public/build
    }),
    react(),
    tailwindcss(),
  ],
  build: {
    outDir: "public/build", // hasil build ke public/build/
    manifest: true,
    emptyOutDir: true,
  },
});
