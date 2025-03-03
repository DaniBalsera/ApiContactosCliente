<?php



require "../boostrap.php";
require_once "../vendor/autoload.php";
require_once "../app/Controllers/ContactosController.php";

use App\Core\Router;
use App\Controllers\ContactosController;
use App\Controllers\AuthController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Recuperamos el método utilizado

$requestMethod = $_SERVER['REQUEST_METHOD'];

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $request );

$userId = null;
if(isset($uri[2])){
    $userId = (int) $uri[2];
}

// Proceso de login

if($request == '/login/'){
    $auth = new AuthController($requestMethod);
    // LLAMADA DIRECTA, SIN if
    $auth->loginFromRequest();
    exit();
}


// Antes de verificar el token, por ejemplo
if($request == '/register/'){
    // Creamos un método registerFromRequest en AuthController
    $auth = new AuthController($requestMethod);
    $auth->registerFromRequest();
    exit();
}




$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$arr = explode(" ", $authHeader);
$jwt = $arr[1];
   


if($jwt){
    try {
        $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));
        // $decoded->data->usuario -> te dice quién es
    } catch (Exception $e) {
        echo json_encode([
            'message' => 'Acceso denegado',
            'error' => $e->getMessage()
        ]);
        exit(http_response_code(401));
    }
}

$router = new Router();
$router->add(array(
    'name'=>'home',
    'path'=>'/^\/contactos\/([0-9]+)?$/',
    'action'=>ContactosController::class)
);

$route = $router->match($request);
if ($route) {
    $controllerName = $route['action'];
    $controller = new $controllerName($requestMethod, $userId);
    $controller->processRequest();
} else {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    echo json_encode($response);
}

?>