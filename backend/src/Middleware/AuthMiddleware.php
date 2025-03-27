<?php
namespace Src\Middleware;

use Src\Controllers\AuthController;

class AuthMiddleware
{
    public static function check()
    {
        $auth = new AuthController();
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        // error_log("Headers recebidos: " . print_r($headers, true));
        // error_log("Secret Key usada na validação: " . ($auth->getSecretKey() ?? 'NÃO DEFINIDA'));

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Token nao informado']);
            exit;
        }

        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $token = $matches[1];
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Token mal formatado']);
            exit;
        }

        $decoded = $auth->validateToken($token);

        if (!$decoded) {
            // error_log("Falha na validação do token. Secret Key usada: " . $auth->getSecretKey());
            http_response_code(401);
            echo json_encode(['message' => 'Token invalido ou expirado']);
            exit;
        }
    }
}
