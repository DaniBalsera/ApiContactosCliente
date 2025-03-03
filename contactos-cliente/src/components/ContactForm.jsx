import React, { useState } from 'react';

const ContactForm = ({ onSubmit, initialData = {} }) => {
  const [nombre, setNombre] = useState(initialData.nombre || '');
  const [telefono, setTelefono] = useState(initialData.telefono || '');
  const [email, setEmail] = useState(initialData.email || '');
  const [error, setError] = useState('');

  const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log({ nombre, telefono, email }); // 👈 Aquí para ver los datos antes de enviar

    if (!nombre || !telefono || !email) {
      setError('Todos los campos son obligatorios');
      return;
    }
    if (!/^\d+$/.test(telefono)) {
      setError('El teléfono debe contener solo números');
      return;
    }
    if (!validateEmail(email)) {
      setError('El email no es válido');
      return;
    }
    setError('');
    onSubmit({ nombre, telefono, email });
  };

  return (
    <form onSubmit={handleSubmit}>
      {error && <div style={{ color: 'red' }}>{error}</div>}
      <div>
        <label>Nombre:</label>
        <input
          type="text"
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
        />
      </div>
      <div>
        <label>Teléfono:</label>
        <input
          type="text"
          value={telefono}
          onChange={(e) => setTelefono(e.target.value)}
        />
      </div>
      <div>
        <label>Email:</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
      </div>
      <button type="submit">Guardar</button>
    </form>
  );
};

export default ContactForm;