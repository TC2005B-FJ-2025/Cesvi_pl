<?php

namespace App\MetaFritterVerso;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class TokenJWT
{

    public static function verify()
    {
        if (is_null(self::getBearerToken())) {
            return ["msg" => "Acceso no autorizado", "status" => 401];
        }
        $token = self::getBearerToken();
        try {
            $decoded = JWT::decode($token, new Key(self::getPublicKey(), 'RS256'));
            return ["msg" => "Verificación exitosa", "jwt" => $decoded, "status" => 200];
        } catch (Exception $e) {
            return ["msg" => $e->getMessage(),"status"=>401];
        }
    }

    private static function getPublicKey()
    {
        $publicKey = env('KEYCLOAK_KEY_PUBLIC');
        $publicKey = <<<EOD
		-----BEGIN PUBLIC KEY-----
		$publicKey
		-----END PUBLIC KEY-----
		EOD;

        return $publicKey;
    }

    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    private static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    private function tokenSegments($token)
    {
        $jwt = explode(".", $token);
        return ["header" => $jwt[0], "payload" => $jwt[1], "signature" => $jwt[2]];
    }

    private function parseJWT($jwt)
    {
        return base64_decode($jwt);
    }
}
