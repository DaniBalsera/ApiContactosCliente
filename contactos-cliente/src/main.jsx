import React from 'react';
import ReactDOM from 'react-dom/client';
import AppRouter from './routes/AppRouter';  // Importa AppRouter que ya maneja las rutas
import { AuthProvider } from './context/AuthContext'; // Si estás usando un contexto de autenticación
import './index.css';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <AuthProvider>
      {/* Envolvemos todo con el AuthProvider, pero el Router lo maneja AppRouter */}
      <AppRouter />
    </AuthProvider>
  </React.StrictMode>
);