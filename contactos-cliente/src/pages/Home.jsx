import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
  // Simulamos el estado de inicio de sesión
  const [isLoggedIn] = useState(false);

  return (
    <div style={styles.homeContainer}>
      <h2 style={styles.heading}>📒 Bienvenido a la Agenda de Contactos</h2>
      <p style={styles.paragraph}>Gestiona todos tus contactos de manera fácil y rápida.</p>
      <nav>
        <ul style={styles.navList}>
          {!isLoggedIn && (
            <>
              <li style={styles.navItem}>
                <Link to="/login" style={styles.navLink}>🔑 Iniciar Sesión</Link>
              </li>
              <li style={styles.navItem}>
                <Link to="/register" style={styles.navLink}>📝 Registrarse</Link>
              </li>
            </>
          )}
        </ul>
      </nav>
    </div>
  );
};

const styles = {
  homeContainer: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    minHeight: '100vh',
    backgroundColor: '#ffffff',
    padding: '20px',
    borderRadius: '8px',
    boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)',
  },
  heading: {
    color: '#333',
    fontSize: '2.5em',
    marginBottom: '20px',
  },
  paragraph: {
    color: '#666',
    fontSize: '1.2em',
    textAlign: 'center',
    maxWidth: '600px',
  },
  navList: {
    listStyle: 'none',
    padding: 0,
    display: 'flex',
    justifyContent: 'center',
  },
  navItem: {
    margin: '0 10px',
  },
  navLink: {
    color: '#646cff',
    textDecoration: 'none',
    fontSize: '1.2em',
  },
};

export default Home;