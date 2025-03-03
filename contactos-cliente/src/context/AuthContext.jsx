import { createContext, useState, useEffect } from 'react';
import axiosClient from '../apis/axiosClient';

export const AuthContext = createContext();

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token') || null);

  // Validación del token al inicio
  useEffect(() => {
    if (token) {
      // Si ya tenemos un token, intentamos hacer una solicitud a la API
      const validateToken = async () => {
        try {
          const response = await axiosClient.get('/contactos');  // Endpoint que requiere autenticación
          // Si la respuesta es exitosa, asumimos que el token es válido
          console.log('Token válido, datos de usuario:', response.data);
          setUser({ username: response.data.username });  // Establecer el usuario si el token es válido
        } catch (error) {
          // Si la validación falla (por ejemplo, si el backend devuelve 401), eliminamos el token
          console.error('Error al validar el token:', error);
          localStorage.removeItem('token');
          setToken(null);
          setUser(null);
        }
      };
      validateToken();
    }
  }, [token]);

  const login = async (usuario, password) => {
    try {
      const response = await axiosClient.post('/login/', { usuario, password });
      const jwt = response.data.jwt;
      const email = response.data.email;

      // Guardar el token y el usuario en el estado
      localStorage.setItem('token', jwt);
      setToken(jwt);
      setUser({ username: email });

    } catch (error) {
      console.error('Error al hacer login', error);
      throw error;
    }
  };

  const register = async (usuario, password) => {
    try {
      await axiosClient.post('/register/', { usuario, password });
    } catch (error) {
      console.error('Error al registrar', error);
      throw error;
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    setToken(null);
    setUser(null);
    window.location.href = '/';  // Redirigir al login
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout, register }}>
      {children}
    </AuthContext.Provider>
  );
}