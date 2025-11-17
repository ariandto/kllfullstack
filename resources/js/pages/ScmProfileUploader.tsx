import React, { useEffect, useState } from "react";
import axios from "axios";

axios.defaults.withCredentials = true;

interface ImageItem {
  filename: string;
  url: string;
}

interface Props {
  folder: string;
}

const API_BASE = "http://localhost:8000"; 

const ScmProfileUploader: React.FC<Props> = ({ folder }) => {
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [images, setImages] = useState<ImageItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  // === Ambil list pertama kali ===
  const loadImages = async () => {
    try {
      const res = await axios.get(`${API_BASE}/upload-scm/${folder}`, {
        withCredentials: true,
      });
      setImages(res.data);
    } catch (err) {
      console.error(err);
    }
  };

  useEffect(() => {
    loadImages();
  }, []);

  // === Upload ===
  const uploadHandler = async () => {
    if (!selectedFile) {
      setErrorMsg("Silakan pilih file terlebih dahulu.");
      return;
    }

    setErrorMsg(null);
    setLoading(true);

    const formData = new FormData();
    formData.append("image", selectedFile);

    try {
      const res = await axios.post(
        `${API_BASE}/upload-scm/${folder}`,
        formData,
        {
          headers: { "Content-Type": "multipart/form-data" },
          withCredentials: true,
        }
      );

      setImages((prev) => [...prev, res.data]);
      setSelectedFile(null);

    } catch (err: any) {
      if (err.response?.data?.error) {
        setErrorMsg(err.response.data.error);
      } else {
        setErrorMsg("Upload gagal.");
      }
    }

    setLoading(false);
  };

  // === Delete ===
  const deleteHandler = async (filename: string) => {
    if (!window.confirm("Yakin hapus foto ini?")) return;

    try {
      await axios.delete(
        `${API_BASE}/upload-scm/${folder}/${filename}`,
        { withCredentials: true }
      );

      setImages((prev) => prev.filter((img) => img.filename !== filename));
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div className="content">
      <h4 className="mb-3 text-primary text-center">
        Upload SCM Profile ({folder})
      </h4>

      {errorMsg && <div className="alert alert-danger py-2">{errorMsg}</div>}

      <div className="mb-3">
        <input
          type="file"
          className="form-control"
          accept="image/png,image/jpeg"
          onChange={(e) => setSelectedFile(e.target.files?.[0] || null)}
        />
      </div>

      {/* Preview */}
      {selectedFile && (
        <div className="mb-3 text-center">
          <img
            src={URL.createObjectURL(selectedFile)}
            alt="preview"
            className="img-thumbnail"
            style={{ maxHeight: "200px" }}
          />
        </div>
      )}

      <button
        className="btn btn-primary w-100 mb-4"
        disabled={loading}
        onClick={uploadHandler}
      >
        {loading ? "Uploading..." : "Upload"}
      </button>

      <h5 className="mb-3">Daftar Gambar</h5>

      <div className="row">
        {images.map((img) => (
          <div className="col-md-3 mb-3" key={img.filename}>
            <div className="card border">
              <img
                src={img.url}
                className="card-img-top"
                alt={img.filename}
                style={{ height: "140px", objectFit: "cover" }}
              />

              <div className="card-body p-2">
                <p className="small text-break">{img.filename}</p>

                <button
                  className="btn btn-danger btn-sm w-100"
                  onClick={() => deleteHandler(img.filename)}
                >
                  Hapus
                </button>
              </div>
            </div>
          </div>
        ))}

        {images.length === 0 && (
          <p className="text-muted">Belum ada gambar.</p>
        )}
      </div>
    </div>
  );
};

export default ScmProfileUploader;
