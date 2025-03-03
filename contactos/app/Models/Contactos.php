<?php
namespace App\Models;

class Contactos extends DBAbstractModel
{
    private static $instancia;

    // Patron singleton, no puedo tener dos objetos de la clase mascotas
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miClase = __CLASS__;
            self::$instancia = new $miClase;
        }
        return self::$instancia;
    }

    public function __clone()
    {
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }

    // Para obtener todos los usuarios
    public function getAll(){
        $this->query = "SELECT * FROM usuarios";
        $this->getResultFromQuery();
        return $this->rows;
    }

    // Funcion para obtener todos los contactos que pertenezcan a un usuario

    public function getAllContactsByUser($user_id){
        $this->query = "SELECT * FROM contactos WHERE user_id = :user_id";
        $this->parametros['user_id'] = $user_id;
        $this->getResultFromQuery();
        return $this->rows;
    }

    // Para obtener un usuario por id
    public function get($id = ''){
        if($id != ''){
            $this->query= "SELECT * FROM contactos WHERE id = :id";

            // Cargamos los parametros
            $this->parametros['id'] = $id;

            // Ejecutamos la consulta
            $this->getResultFromQuery();
        }
        if(count($this->rows) == 1){
            foreach ($this->rows[0] as $propiedad=>$valor){
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario encontrado';
        } else {
            $this->mensaje = 'Usuario no encontrado';
        }
        return $this->rows[0] ?? null;
    }

    public function set($dataCont=array()) {
        foreach ($dataCont as $campo=>$valor) {
            $$campo = $valor;
        }
    
        // Debes recibir user_id de alguna forma. 
        // O bien lo extraes del token en tu controlador.
        $this->query = "INSERT INTO contactos (nombre, telefono, email, user_id) 
                        VALUES (:nombre, :telefono, :email, :user_id)";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['telefono'] = $telefono;
        $this->parametros['email'] = $email;
        $this->parametros['user_id'] = $user_id;
        $this->getResultFromQuery();
        $this->mensaje = 'Contacto agregado';
    }


    
    public function edit($dataCont=array()){

        foreach ($dataCont as $campo=>$valor) {
            $$campo = $valor;
        }

        $this->query = "UPDATE contactos SET nombre = :nombre, telefono = :telefono, email = :email WHERE id = :id";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['telefono'] = $telefono;
        $this->parametros['email'] = $email;
       
        $this->getResultFromQuery();
        // $this->execute_single_query();
        $this->mensaje = 'Contacto modificado';

    }

    public function delete($id = ''){
        if($id != ''){
            $this->query = "DELETE FROM contactos WHERE id = :id";

            // Cargamos los parametros
            $this->parametros['id'] = $id;

            // Ejecutamos la consulta
            $this->getResultFromQuery();
        }
        if(count($this->rows) == 1){
            foreach ($this->rows[0] as $propiedad=>$valor){
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario borrado';
        } else {
            $this->mensaje = 'Usuario no encontrado';
        }
        return $this->rows[0] ?? null;
    }
    
    public function getAllByUser($user_id){
        $this->query = "SELECT * FROM contactos WHERE user_id = :user_id";
        $this->parametros['user_id'] = $user_id;
        $this->getResultFromQuery();
        return $this->rows;
    }
    

}