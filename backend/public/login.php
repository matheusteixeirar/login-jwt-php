<?php

require '../vendor/autoload.php';

use app\database\Connection;
use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *"); // permite qualquer origem acessar (isso pq o backend e o front end tem urls diferentes)

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2)); // entender
$dotenv->load();

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

$pdo = Connection::connect();

$prepare = $pdo->prepare("select * from usuarios where email  = :email");
$prepare->execute([
    'email' => $email
]);

$userFound = $prepare->fetch(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC -> retorna uma chave para cada coluna do bd (por padrao retornam 2)
// $userFound -> array com todos os pares chave-elemento que veio na query

$name = $userFound['name'];

// echo json_encode($userFound);

if (!$userFound) {
    http_response_code(401); // nao autorizado
    exit;
}

if (!password_verify($password, $userFound['password'])) {
    http_response_code(401); // nao autorizado
    exit;
}

$payload = [
    "exp" => time() + 60 * 60, // 1 hora
    "iat" => time(),
    "email" => $email,
    "name" => $name
];

// cria e envia o token jwt
$encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');
echo $encode;