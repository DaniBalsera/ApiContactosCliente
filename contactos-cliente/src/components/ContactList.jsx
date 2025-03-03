import React from 'react';
import ContactItem from './ContactItem';

const ContactList = ({ contactos, onDelete, onEdit }) => {
  return (
    <ul style={{ listStyle: 'none', padding: 0 }}>
      {contactos.map((contacto) => (
        <ContactItem 
          key={contacto.id} 
          contacto={contacto} 
          onDelete={onDelete} 
          onEdit={onEdit} 
        />
      ))}
    </ul>
  );
};

export default ContactList;
