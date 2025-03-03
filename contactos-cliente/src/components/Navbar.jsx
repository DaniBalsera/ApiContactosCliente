import React, { useContext } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';

function Navbar() {
  const { user, logout } = useContext(AuthContext);
  const location = useLocation();

  return (
    <nav>
      <ul>
        {user ? (
          <>
            <li>
              <span>👤 Bienvenido, {user.username}</span>
            </li>
            <li>
              <button onClick={logout}>🚪 Cerrar sesión</button>
            </li>
          </>
        ) : (
          location.pathname !== '/dashboard' && (
            <>
              <li>
                <Link to="/login">🔑 Iniciar sesión</Link>
              </li>
              <li>
                <Link to="/register">📝 Registrarse</Link>
              </li>
            </>
          )
        )}
      </ul>
    </nav>
  );
}

export default Navbar;