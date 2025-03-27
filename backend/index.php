<?php
require_once __DIR__ . '/vendor/autoload.php';

use Src\Controllers\TaskController;
use Src\Controllers\AuthController;
use Src\Middleware\AuthMiddleware;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Remove a query string para facilitar o roteamento
if (strpos($requestUri, '?') !== false) {
    $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
}

// Trata requisições OPTIONS (preflight CORS)
if ($requestMethod === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Roteamento: endpoint de login não precisa de autenticação
if ($requestUri === '/login' && $requestMethod === 'POST') {
    $authController = new AuthController();
    $authController->login();
    exit;
}

// Para as demais rotas, verifica o token via middleware
AuthMiddleware::check();

// Cria instância do controller para as tarefas
$controller = new TaskController();

if ($requestUri === '/tasks' && $requestMethod === 'GET') {
    $controller->getTasks();
} elseif ($requestUri === '/tasks' && $requestMethod === 'POST') {
    $controller->createTask();
} elseif (preg_match('/\/tasks\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
    if ($requestMethod === 'PUT') {
        $controller->updateTask($id);
    } elseif ($requestMethod === 'DELETE') {
        $controller->deleteTask($id);
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint not found']);
}
