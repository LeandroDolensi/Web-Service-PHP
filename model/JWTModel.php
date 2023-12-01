<?php

class JWTModel {


    public static function addToken($token, $email){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE usuario SET token = :token WHERE email = :email");
            $stmt->execute([
                "token" => $token,
                "email" => $email
            ]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getToken($email){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT token FROM usuario WHERE email = ?");
            $stmt->execute([$email]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }
}

?>