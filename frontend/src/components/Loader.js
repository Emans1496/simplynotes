import React from 'react';
import './Loader.css';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");



function Loader() {
  return (
    <div className="loader">
      <div className="spinner"></div>
      <p>Caricamento in corso...</p>
    </div>
  );
}

export default Loader;
