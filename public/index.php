<?php
require "../bootstrap.php";

use App\Controllers\AuthController;
use App\Controllers\ContactsController;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use App\Models\Contact;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $request);
$requestMethod = $_SERVER["REQUEST_METHOD"];

// User data if exists 
$userId = null;
if (isset($uri[2])) {
    $userId = (int) $uri[2];
}

// Proccess login
if ($request == '/login') {
    $auth = new AuthController($requestMethod);
    if (!$auth->loginFromRequest()) {
        exit(http_response_code(401));
    }
}

$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ",$authHeader);
$jwt = $arr[1];

if ($jwt) {
    try {
        //If it is not possible to decode the token we generate an error
        //You could create a check method in the class
        $decoded = (JWT::decode($jwt, new Key(KEY,'HS256')));
    } catch (Exception $e) {
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()));
        exit(http_response_code(401));
    }
}

if ($uri[1] !== 'contacts') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$controller = new ContactsController($requestMethod,$userId);
$controller ->processRequest();


?>