import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Login from "./views/Login";
import Register from "./views/Register";
import Dashboard from "./views/Dashboard";
import ProtectedRoute from "./components/ProtectedRoute";
import "./App.css";
import { Helmet } from "react-helmet";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");


function App() {
  return (
    <Router>
      <div className="App">
        <Helmet>
          <title>Simply Notes - Gestisci le tue Note</title>
          <meta name="description" content="Simply Notes ti permette di creare e gestire note in modo semplice e sicuro." />
          <link rel="icon" type="favicon" href="%PUBLI_URL%/favicon.png" />
        </Helmet>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route
            path="/dashboard"
            element={
              <ProtectedRoute>
                <Dashboard />
              </ProtectedRoute>
            }
          />
        </Routes>
      </div>
    </Router>
  );
}

export default App;