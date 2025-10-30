<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$authorization = $_SERVER["HTTP_AUTHORIZATION"]; // pega o valor do cabeçalho http

$token = str_replace('Bearer', ' ', $authorization);
$token = trim($token);

// verifica se o token que foi recebido do front end é válido e não expirou
try {
    $decoded = JWT::decode($token, new key($_ENV['KEY'], 'HS256'));
    echo json_encode($decoded);
} catch (\Throwable $th) {
    if ($th->getMessage() === 'Expired token') {
        http_response_code(401);
    }
    echo json_encode($th->getMessage());
}