import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate, Link } from 'react-router-dom';
import './App.css';

function Register() {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);

    axios
      .post('https://simplynotes-backend.onrender.com/api/register.php', formData)
      .then((response) => {
        if (response.data.success) {
          navigate('/');
        } else {
          setMessage(response.data.message);
        }
      })
      .catch((error) => {
        console.error('Errore:', error);
        setMessage('Si è verificato un errore durante la registrazione.');
      });
  };

  return (
    <div className="container register-container">
      <img src="/1.png" alt="Register Banner" />
      <div className="form-container">
        <h2 className="text-center">Registrazione</h2>
        {message && <div className="alert alert-danger">{message}</div>}
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label">Username</label>
            <input
              type="text"
              className="form-control"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
            />
          </div>
          <div className="mb-3">
            <label className="form-label">Email</label>
            <input
              type="email"
              className="form-control"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div className="mb-3">
            <label className="form-label">Password</label>
            <input
              type="password"
              className="form-control"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <button type="submit" className="btn btn-primary w-100">
            Registrati
          </button>
        </form>
        <p className="mt-3 text-center">
          Hai già un account? <Link to="/">Accedi qui</Link>
        </p>
      </div>
    </div>
  );
}

export default Register;
