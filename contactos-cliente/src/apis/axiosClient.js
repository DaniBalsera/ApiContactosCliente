import axios from 'axios';

// Base URL de tu API en PHP. Ajusta según corresponda.
const baseURL = 'http://contactos.local';

const axiosClient = axios.create({
  baseURL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor para incluir el token en cada request
axiosClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  console.log('Token enviado:', token); // 👈 Esto para depurar
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default axiosClient;