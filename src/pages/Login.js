import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate, Link } from 'react-router-dom';
import { Helmet } from "react-helmet";

const baseURL = process.env.REACT_APP_API_BASE_URL;

function Login() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    axios
      .post(`${baseURL}/login.php`, formData, { withCredentials: true }) // "withCredentials" per inviare cookies se necessario
      .then((response) => {
        console.log(response.data);
        if (response.data.success) {
          // Salviamo il token JWT e altre informazioni nel localStorage
          localStorage.setItem('isAuthenticated', 'true');
          localStorage.setItem('userId', response.data.user_id); 
          localStorage.setItem('token', response.data.token); 

          // Aggiungiamo il token JWT alle future richieste
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

          navigate('/dashboard');
        } else {
          setMessage(response.data.message);
        }
      })
      .catch((error) => {
        console.error('Errore:', error);
        setMessage('Si è verificato un errore durante il login.');
      });
  };

  return (
    <div className="container login-container">
      <Helmet>
        <title>Login - Simply Notes</title>
        <meta name="description" content="Effettua il login su Simply Notes per gestire le tue note." />
      </Helmet>
      <img src="/1.png" alt="Login Banner" />
      <div className="form-container">
        <h2 className="text-center">Login</h2>
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
            <label className="form-label">Password</label>
            <input
              type="password"
              className="form-control"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              autoComplete="current-password"
            />
          </div>
          <button type="submit" className="btn btn-primary w-100 btnLogin">
            Accedi
          </button>
        </form>
        <p className="mt-3 text-center">
          Non hai un account? <Link to="/register">Registrati qui</Link>
        </p>
      </div>
    </div>
  );
}

export default Login;
