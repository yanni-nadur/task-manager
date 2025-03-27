<?php
namespace Src\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = getenv('SECRET_KEY') ?: '';
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if ($username === 'admin' && $password === 'senha123') {
            $payload = [
                'iss' => 'seu_dominio.com',
                'iat' => time(),
                'exp' => time() + (1000000 * 1000000),
                'user' => [
                    'username' => $username
                ]
            ];

            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            echo json_encode(['token' => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Credenciais inválidas']);
        }
    }

    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}
