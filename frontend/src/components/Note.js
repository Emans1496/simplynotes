import React, { useState } from 'react';
import axios from 'axios';

function Note({ note, refreshNotes }) {
  const [isEditing, setIsEditing] = useState(false);
  const [title, setTitle] = useState(note.title);
  const [content, setContent] = useState(note.content);
  const userId = localStorage.getItem('userId');

  const handleDelete = () => {
    const formData = new FormData();
    formData.append('id', note.id);
    formData.append('user_id', userId);

    axios
      .post('https://simplynotes-backend.onrender.com/api/delete_note.php', formData, { withCredentials: true })
      .then(() => {
        refreshNotes();
      })
      .catch((error) => {
        console.error('Errore:', error);
      });
  };

  const handleUpdate = (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('id', note.id);
    formData.append('title', title);
    formData.append('content', content);
    formData.append('user_id', userId);

    axios
      .post('https://simplynotes-backend.onrender.com/api/update_note.php', formData, { withCredentials: true })
      .then(() => {
        setIsEditing(false);
        refreshNotes();
      })
      .catch((error) => {
        console.error('Errore:', error);
      });
  };

  return (
    <div className="col-md-4">
      <div className="card mb-4">
        {isEditing ? (
          <form onSubmit={handleUpdate} className="card-body">
            <div className="mb-3">
              <label className="form-labell">Titolo</label>
              <input
                type="text"
                className="form-control"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                required
              />
            </div>
            <div className="mb-3">
              <label className="form-labell">Contenuto</label>
              <textarea
                className="form-control"
                value={content}
                onChange={(e) => setContent(e.target.value)}
                required
              />
            </div>
            <button type="submit" className="btn btn-primary me-2">
              Aggiorna
            </button>
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setIsEditing(false)}
            >
              Annulla
            </button>
          </form>
        ) : (
          <div className="card-body">
            <h5 className="card-title">{note.title}</h5>
            <p className="card-text">{note.content}</p>
            <button className="btn btn-warning me-2" onClick={() => setIsEditing(true)}>
              Modifica
            </button>
            <button className="btn btn-danger" onClick={handleDelete}>
              Elimina
            </button>
          </div>
        )}
      </div>
    </div>
  );
}

export default Note;
