import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import Login from './components/Login.jsx';
import Dashboard from './components/Dashboard.jsx';

const loginComponent = document.getElementById('login-component');
const dashboardComponent = document.getElementById('dashboard-component');

if (loginComponent && (localStorage.getItem('authToken') == '' || localStorage.getItem('authToken') == null || localStorage.getItem('authToken') == undefined) ) {
    const root = createRoot(loginComponent);
    root.render(<Login />)
    /* root.render(
        <Router>
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/home" element={<Home />} />
            </Routes>
        </Router>
    ); */
} else {
    const dashboard = createRoot(dashboardComponent);
    dashboard.render(<Dashboard />)
}



