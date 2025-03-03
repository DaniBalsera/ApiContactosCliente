<?php
namespace App\Models;

class Users extends DBAbstractModel
{
    private static $instancia;

    // Patron singleton, no puedo tener dos objetos de la clase Users
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

    // Para obtener un usuario por id
    public function get($id = ''){
        if($id != ''){
            $this->query= "SELECT * FROM usuarios WHERE id = :id";

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

    public function set($dataUser=array()) {

        foreach ($dataUser as $campo=>$valor) {
            $$campo = $valor;
        }

        $this->query = "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)";
        $this->parametros['usuario'] = $usuario;
        $this->parametros['password'] = $password;
        $this->getResultFromQuery();
        // $this->execute_single_query();
        $this->mensaje = 'Usuario agregado';

    }

    public function edit($dataUser=array()){

        foreach ($dataUser as $campo=>$valor) {
            $$campo = $valor;
        }

        $this->query = "UPDATE usuarios SET usuario = :usuario, password = :password WHERE id = :id";
        $this->parametros['usuario'] = $usuario;
        $this->parametros['password'] = $password;
       
        $this->getResultFromQuery();
        // $this->execute_single_query();
        $this->mensaje = 'Usuario modificado';

    }

    public function delete($id = ''){
        if($id != ''){
            $this->query = "DELETE FROM usuarios WHERE id = :id";

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

    /**FUncion login */

    public function login($usuario, $password){
        $this->query = "SELECT id, usuario FROM usuarios WHERE usuario = :usuario AND password = :password";
        $this->parametros['usuario'] = $usuario;
        $this->parametros['password'] = $password;
        $this->getResultFromQuery();
        return $this->rows[0] ?? null; // Devuelve solo una fila
    }
    
}