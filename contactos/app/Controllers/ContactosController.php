<?php

namespace App\Controllers;

use App\Models\Contactos;
use App\Models\Users;
use \Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class ContactosController
{

    private $requestMethod;
    private $contactos;
    private $contactosId;

    public function __construct($requestMethod, $contactosId)
    {
        $this->requestMethod = $requestMethod;
        $this->contactosId = $contactosId;
        $this->contactos = Contactos::getInstancia();
    }

    public function processRequest(){
        switch($this->requestMethod){
            case 'GET':
                // Si se envía un ID en la URL, devolvemos ese contacto.
                // De lo contrario, devolvemos todos los contactos del usuario.
                if ($this->contactosId) {
                    $response = $this->getContactos($this->contactosId);
                } else {
                    // Aquí deberías obtener el user_id a partir del token decodificado o de la sesión.
                    // En este ejemplo lo dejamos estático (por ejemplo, 1). 
                    // Reemplaza esta parte con la extracción del user_id real.
                    // Obtener el token del encabezado de autorización
                    $headers = apache_request_headers();
                    if (!isset($headers['Authorization'])) {
                        return $this->unprocessableEntityResponse();
                    }

                    $authHeader = $headers['Authorization'];
                    list($jwt) = sscanf($authHeader, 'Bearer %s');

                    if (!$jwt) {
                        return $this->unprocessableEntityResponse();
                    }

                    try {
                        $key = KEY; // Clave para decodificar el JWT
                        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
                        $userId = $decoded->data->id;
                    } catch (Exception $e) {
                        return $this->unprocessableEntityResponse();
                    }
                    $response = $this->getContactosAllByUser($userId);
                }
                break;

            case 'POST':
                $input = (array) json_decode(file_get_contents('php://input'), TRUE);
                $response = $this->createContactos($input);
                break;

            case 'PUT':
                $input = (array) json_decode(file_get_contents('php://input'), TRUE);
                $response = $this->updateContactos($input);
                break;

            case 'DELETE':
                $response = $this->deleteContactos($this->contactosId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if (isset($response['body'])) {
            echo $response['body'];
        }
    }

    private function createContactos($input)
    {
        // Obtener el token del encabezado de autorización
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            return $this->unprocessableEntityResponse();
        }

        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if (!$jwt) {
            return $this->unprocessableEntityResponse();
        }

        try {
            $key = KEY; // Clave para decodificar el JWT
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            $userId = $decoded->data->id;
        } catch (Exception $e) {
            return $this->unprocessableEntityResponse();
        }

        if (!isset($input['nombre'], $input['telefono'], $input['email'])) {
            return $this->unprocessableEntityResponse();
        }

        $input['user_id'] = $userId;
        $this->contactos->set($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(['message' => 'Contacto creado con éxito']);
        return $response;
    }

    private function updateContactos($input)
    {
        // Verifica que el contacto exista.
        if (!$this->getContactos($this->contactosId)) {
            return $this->notFoundResponse();
        }

        $this->contactos->edit($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['message' => 'Contacto actualizado con éxito']);
        return $response;
    }

    private function deleteContactos($id){
        // Verifica que el contacto exista.
        if (!$this->getContactos($id)) {
            return $this->notFoundResponse();
        }

        $this->contactos->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['message' => 'Contacto eliminado con éxito']);
        return $response;
    }

    // Método para obtener UN contacto (por id)
    private function getContactos($id){
        $result = $this->contactos->get($id);
        if(!$result){
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // NUEVO: Método para obtener TODOS los contactos del usuario
    private function getContactosAllByUser($userId){
        $result = $this->contactos->getAllContactsByUser($userId);
        if(!$result){
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(['message' => 'Recurso no encontrado']);
        return $response;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(['message' => 'Entrada no válida']);
        return $response;
    }
}
?>
