import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Login from './Login'; // Asegúrate de que la ruta sea correcta
import Dashboard from './Dashboard'; // Otro componente que quieras

const App = () => {
  return (
    <Routes>
      <Route path="/" element={<Login />} />
      <Route path="/dashboard" element={<Dashboard />} />
      {/* Agrega más rutas según sea necesario */}
    </Routes>
  );
};

export default App;
