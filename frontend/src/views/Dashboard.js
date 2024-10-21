import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Note from "../components/Note";
import Loader from "../components/Loader";
import "../App.css";

const baseURL = process.env.REACT_APP_API_BASE_URL;

function Dashboard() {
  const [notes, setNotes] = useState([]);
  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const refreshNotes = () => {
    setLoading(true);
    const userId = localStorage.getItem("userId");
    if (!userId) {
      setMessage("Utente non autenticato. Per favore fai il login di nuovo.");
      navigate("/");
      return;
    }

    axios
      .get(`${baseURL}/api/get_notes`, {
        params: { user_id: userId },
        withCredentials: true,
      })
      .then((response) => {
        if (response.data.success) {
          setNotes(response.data.notes);
        }
      })
      .catch((error) => {
        console.error("Errore:", error);
      })
      .finally(() => {
        setLoading(false);
      });
  };

  useEffect(() => {
    refreshNotes();
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem("isAuthenticated");
    localStorage.removeItem("userId");
    axios
      .post(`${baseURL}/api/logout`, {}, { withCredentials: true })
      .then(() => {
        navigate("/");
      })
      .catch((error) => {
        console.error("Errore durante il logout:", error);
      });
  };

  const handleAddNote = (e) => {
    e.preventDefault();

    const userId = localStorage.getItem("userId");
    if (!userId) {
      setMessage("Utente non autenticato. Per favore fai il login di nuovo.");
      navigate("/");
      return;
    }

    const formData = new FormData();
    formData.append("title", title);
    formData.append("content", content);
    formData.append("user_id", userId);

    axios
      .post(`${baseURL}/api/add_note`, formData, { withCredentials: true })
      .then((response) => {
        if (response.data.success) {
          setMessage(response.data.message);
          setTitle("");
          setContent("");
          refreshNotes();
        } else {
          setMessage(response.data.message);
        }
      })
      .catch((error) => {
        console.error("Errore durante l'aggiunta della nota:", error);
        setMessage("Errore durante l'aggiunta della nota.");
      });
  };

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <button className="btn btn-secondary" onClick={handleLogout}>
          Logout
        </button>
      </div>
      {message && <div className="alert alert-success">{message}</div>}
      <form onSubmit={handleAddNote} className="mb-4">
        <h3>Aggiungi una nuova nota</h3>
        <div className="mb-3">
          <label className="form-label">Titolo</label>
          <input
            type="text"
            className="form-control"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            required
          />
        </div>
        <div className="mb-3">
          <label className="form-label">Contenuto</label>
          <textarea
            className="form-control"
            value={content}
            onChange={(e) => setContent(e.target.value)}
            required
          />
        </div>
        <button type="submit" className="btn btn-primary">
          Aggiungi Nota
        </button>
      </form>
      <h3>Le tue note</h3>
      {loading ? (
        <Loader />
      ) : notes.length > 0 ? (
        <div className="row">
          {notes.map((note) => (
            <Note key={note.id} note={note} refreshNotes={refreshNotes} />
          ))}
        </div>
      ) : (
        <p>Non hai ancora note.</p>
      )}
    </div>
  );
}

export default Dashboard;
