<?php
require "../../bootstrap.php";

//Dirección de api
$issuer ='http://apirestcontactos.local';

//Credenciales
$credenciales =  [
    'usuario' => 'admin',
    'password'=> 'admin'
];

// Obtenemos el token de acceso.
$token = obtainToken($credenciales, $issuer);
echo $token;

//Tests 
getAllContacts($token);
getContact($token, 4);

$contacto = [
    "id"=>"3",
    "name"=>"Rafael Nadal",
    "tlf"=>"938289328",
    "mail"=>"rnada@correo.es"
];
addContact($token, $contacto);

function obtainToken($datos, $issuer) {
    echo "Obteniendo token...<br/>";

    //Comprobamos si disponemos del token en almacenamiento local, en cuyo caso lo leemos.

    //Cargamos ruta de login
    $uri = $issuer . '/login';
    
    // Petición curl
    
    //Inicio
    $ch = curl_init();
   
    //Parametrización
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Petición
    $response = curl_exec($ch);

    //Comprabamos que el token es correcto
    $response = json_decode($response, true);
    if (! isset($response['jwt'])) {
        exit('failed, exiting.');
    }

    echo "Token OK <br/>";
    //Almacenamiento local del token.

    
    return $response['jwt'];
}

function getAllContacts($token) {
    echo "<br/>Obteniendo todos los contactos...<br/>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://apirestcontactos.local/contactos");
    curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json',"Authorization: Bearer $token" ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    echo $response;
}

function getContact($token, $id) {
    echo "<br/>Obteniendo contacto por id: $id...<br/>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://apirestcontactos.local/contactos/" . $id);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    echo $response;
}

function addContact($token, $datos) {
    echo "<br/>Nuevo contacto...<br/>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://apirestcontactos.local/contactos");
    curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', "Authorization: Bearer $token" ]);
    curl_setopt($ch,CURLOPT_POST,true);
    var_dump($datos);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($datos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    echo $response;
   
}