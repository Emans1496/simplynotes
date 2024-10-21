import React from 'react';
import './Loader.css';

function Loader() {
  return (
    <div className="loader">
      <div className="spinner"></div>
      <p>Caricamento in corso...</p>
    </div>
  );
}

export default Loader;
