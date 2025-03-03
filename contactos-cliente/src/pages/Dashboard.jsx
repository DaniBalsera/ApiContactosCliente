import React, { useContext, useEffect, useState } from 'react';
import { AuthContext } from '../context/AuthContext';
import axiosClient from '../apis/axiosClient';
import Layout from '../Components/Layout';
import ContactList from '../Components/ContactList';
import ContactForm from '../Components/ContactForm';

const Dashboard = () => {
  const { user} = useContext(AuthContext);
  console.log(user);
  const [contactos, setContactos] = useState([]);
  const [editingContact, setEditingContact] = useState(null);
  const [showAddForm, setShowAddForm] = useState(false); // Estado para controlar la visibilidad del formulario de agregar contacto

  useEffect(() => {
    const fetchContactos = async () => {
      try {
        const response = await axiosClient.get('/contactos/');
        setContactos(Array.isArray(response.data) ? response.data : []);
        // Muestra en consola el nombre de usuario
        console.log('Nombre de usuario:', user?.username);
      } catch (error) {
        console.error('Error al obtener contactos', error);
      }
    };
    fetchContactos();
  }, []);

  const handleAddContact = async (contactData) => {
    try {
      console.log('Datos a enviar:', contactData);

      const token = localStorage.getItem('token'); // Verificar si tienes el token guardado
      if (!token) {
        console.error('No hay token disponible');
        return;
      }

      const response = await axiosClient.post('/contactos/', contactData, {
        headers: {
          'Authorization': `Bearer ${token}`, // Aquí se envía el token en la cabecera
          'Content-Type': 'application/json', // Asegura que los datos sean JSON
        }
      });

      console.log('Nuevo contacto creado:', response.data); // Verifica qué devuelve el backend

      // En lugar de solo agregar el nuevo contacto al estado, hacemos una nueva solicitud para obtener todos los contactos
      const updatedContactList = await axiosClient.get('/contactos/', {
        headers: {
          'Authorization': `Bearer ${token}`, // Aquí se envía el token para autenticar la solicitud
        }
      });

      // Actualiza el estado con la lista completa de contactos, incluyendo el nuevo
      setContactos(updatedContactList.data);
      setShowAddForm(false); // Ocultar el formulario después de agregar el contacto

    } catch (error) {
      console.error('Error al agregar contacto', error.response?.data || error.message);
    }
  };

  const handleDeleteContact = async (id) => {
    try {
      await axiosClient.delete(`/contactos/${id}`);
      setContactos(contactos.filter(c => c.id !== id));
    } catch (error) {
      console.error('Error al eliminar contacto', error);
    }
  };

  const handleEditContact = (contacto) => {
    setEditingContact(contacto);
  };

  const handleUpdateContact = async (updatedData) => {
    try {
      await axiosClient.put(`/contactos/${editingContact.id}`, updatedData);
      setContactos(contactos.map(c => (c.id === editingContact.id ? { ...c, ...updatedData } : c)));
      setEditingContact(null);
    } catch (error) {
      console.error('Error al actualizar contacto', error);
    }
  };

  return (
    <Layout>
      <div className="container">
        <h2>📒 Bienvenido {user?.username}</h2>
        <h3>📇 Contactos</h3>
        <ContactList 
          contactos={contactos} 
          onDelete={handleDeleteContact} 
          onEdit={handleEditContact} 
        />
        <button onClick={() => setShowAddForm(true)}>➕ Agregar Contacto</button> {/* Botón para mostrar el formulario de agregar contacto */}
        {showAddForm && ( // Mostrar el formulario de agregar contacto si showAddForm es true
          <div>
            <h3>Agregar Contacto</h3>
            <ContactForm 
              onSubmit={handleAddContact} 
              initialData={{}} 
            />
          </div>
        )}
        {editingContact && ( // Mostrar el formulario de editar contacto si editingContact no es null
          <div>
            <h3>Editar Contacto</h3>
            <ContactForm 
              onSubmit={handleUpdateContact} 
              initialData={editingContact} 
            />
          </div>
        )}
      </div>
    </Layout>
  );
};

export default Dashboard;