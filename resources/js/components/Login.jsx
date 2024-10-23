import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom'; // Cambia a useNavigate
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://wellezy-prueba-tecnica.test/api/login', {
        email,
        password,
      }, { withCredentials: true });

      // Supongamos que el token está en response.data.token
      const token = response.data.data.token;

      // Guarda el token en localStorage
      localStorage.setItem('authToken', token);
      console.log(response.data.data);
      window.location.reload()
      //navigate('/dashboard');
      // Redirige a la página deseada, por ejemplo, el dashboard

    } catch (err) {
      setError('Login failed. Please check your credentials.');
    }
  };

  return (
    <div className="login-container">
      <h2>Login</h2>
      {error && <p className="error-message">{error}</p>}
      <form onSubmit={handleLogin}>
        <div>
          <label>Email</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
        </div>
        <div>
          <label>Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  );
};

export default Login;
