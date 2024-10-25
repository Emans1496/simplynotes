import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Register = () => {
  const [user, setUser] = useState({ username: '', password: '' });
  const [message, setMessage] = useState('');
  const navigate = useNavigate();

  const handleChange = (e) => {
    setUser({ ...user, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Creiamo un oggetto FormData
    const formData = new FormData();
    formData.append('username', user.username);
    formData.append('password', user.password);

    try {
      // Invio della richiesta con withCredentials per gestire i CORS
      const response = await axios.post(`${process.env.REACT_APP_API_BASE_URL}/register.php`, formData, {
        withCredentials: true
      });

      // Controllo della risposta e navigazione in caso di successo
      if (response.data.success) {
        setMessage('Registrazione avvenuta con successo!');
        navigate('/login');
      } else {
        setMessage(response.data.message);
      }
    } catch (error) {
      console.error('Errore durante la registrazione', error);
      setMessage('Si è verificato un errore durante la registrazione.');
    }
  };

  return (
    <div className="container">
      <h2>Register</h2>
      {message && <div className="alert alert-danger">{message}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="username">Username</label>
          <input
            type="text"
            name="username"
            className="form-control"
            value={user.username}
            onChange={handleChange}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="password">Password</label>
          <input
            type="password"
            name="password"
            className="form-control"
            value={user.password}
            onChange={handleChange}
            required
          />
        </div>
        <button type="submit" className="btn btn-primary">
          Register
        </button>
      </form>
    </div>
  );
};

export default Register;
