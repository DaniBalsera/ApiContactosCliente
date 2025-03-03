import React from 'react';

const ContactItem = ({ contacto, onDelete, onEdit }) => {
  return (
    <li style={{ marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
      <p><strong>Nombre:</strong> {contacto.nombre}</p>
      <p><strong>Teléfono:</strong> {contacto.telefono}</p>
      <p><strong>Email:</strong> {contacto.email}</p>
      <button onClick={() => onEdit(contacto)}>Editar</button>
      <button onClick={() => onDelete(contacto.id)}>Eliminar</button>
    </li>
  );
};

export default ContactItem;
