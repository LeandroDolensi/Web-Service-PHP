<?php

require(__DIR__ . "/vendor/autoload.php");

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    private $key;
    private $server;
    private $timezone;
    private $mensagem_jwt_invalido;

    public function __construct()
    {
        $this->key = "987654321";
        $this->server = URL_SERVER;
        $this->timezone = new DateTimeZone('America/Sao_Paulo');

        $this->mensagem_jwt_invalido = "verifique seu Bearer Token de acesso ou tente fazer login novamente: " . URL_USUARIO_LOGAR;
    }

    public function generateToken($user_email, $valid = TEMPO_TOKEN_MINUTOS)
    {
        try {

            $time = new DateTimeImmutable("now", $this->timezone);
            // $time = new DateTimeImmutable();
            $validUntil = $time->modify("+$valid minutes")->getTimestamp();
            $data = [
                "iat" => $time->getTimestamp(),
                "iss" => $this->server,
                "nbf" => $time->getTimestamp(),
                "exp" => $validUntil,
                "sub" => $user_email
            ];
            $token = JWT::encode($data, $this->key, 'HS256');
    
            JWTModel::addToken($token, $user_email);
            return $token;

        } catch (Exception $e) {
            throw $e;
        }

    }

    // Função auxiliar que verifica se o token recebido pelo usuário segue as regras de tempo e validade.
    private function checkJWT($jwt, $jwt_uncode="")
    {
        $now = new DateTimeImmutable("now", $this->timezone);
        // $now = new DateTimeImmutable();
        try {
            if ($jwt->iss !== $this->server) {
                throw new Exception("o token de acesso não é para este servidor", PRECONDITION_FAILED);
            }
            if ($jwt->nbf > $now->getTimestamp()) {
                throw new Exception("o token de acesso não pode ser usado antes do tempo mínimo esperado", PRECONDITION_FAILED);
            }
            if ($now->getTimestamp() > $jwt->exp) {
                throw new Exception("o token de acesso perdeu a validade, ".$this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }

            try {
                $token = JWTModel::getToken($jwt->sub);
            } catch (Exception $e) {
                throw new Exception("token inválido, ". $this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }
            

            if ((is_array($token)) && (isset($token[0]) && ($token[0]['token'] != $jwt_uncode))){
                throw new Exception("token inválido, ".$this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }
            
            return true;
        } catch (Exception $e) {
            throw $e;
            
        }
    }

    // Retorna o 'userId' do usuário presente no token se possível, ou, qualquer outro caso, false.
    public function getUserEmail()
    {
        try {
            // Verifica se o campo 'Bearer' com um token foi enviado na requisição
            if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                throw new Exception("token não enviado", PRECONDITION_FAILED);
            }

            // Verifica se o campo 'Bearer' é válido.
            $jwt = $matches[1];
            if (!$jwt) {
                throw new Exception("o token de acesso está com formato inválido, ".$this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }

            try {
                $token_decode = JWT::decode($jwt, new Key($this->key, 'HS256'));
            } catch (Exception $e) {
                throw new Exception("token inválido, ".$this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }
            

            // Verifica se o token em si é válido.
            try {
                $this->checkJWT($token_decode, $jwt);
            } catch (Exception $e) {
                throw new Exception("token inválido ou expirado, ". $this->mensagem_jwt_invalido, PRECONDITION_FAILED);
            }
            
            // Tudo certo, retorna então o 'sub' do token.
            return $token_decode->sub;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
