<?php 


class Usuario {


    public static function add($nome, $email, $senha)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO usuario(nome,email,senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    
    public static function delete($id_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function edit($id_usuario, $nome, $email)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE usuario SET nome = :nome, email = :email WHERE id_usuario = :id_usuario");
            $stmt->execute([
                "nome" => $nome,
                "email" => $email,
                "id_usuario" => $id_usuario
            ]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function editSenha($email, $senha){

        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE usuario SET senha = :senha WHERE email = :email");
            $stmt->execute([
                "senha" => $senha,
                "email" => $email]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromId($id_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromName($name_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE nome = ?");
            $stmt->execute([$name_usuario]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromEmail($email_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt->execute([$email_usuario]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getAll()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario ORDER BY id");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function existsById($id_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function existsByEmail($email_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
            $stmt->execute([$email_usuario]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getNumber()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario");
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function novoUsuario($nome, $email, $senha){


        if (self::existsByEmail($email) > 0){
            throw new Exception("usuário já cadastrado.", BAD_REQUEST);
        }


        $senha_hash = password_hash($senha, PASSWORD_BCRYPT, ["const" => 11]);

        try {
            self::add($nome, $email, $senha_hash);
        } catch (Exception $e) {
            throw $e;
        }
        

        $user = array(
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha
        );

        return $user;
    }

    public static function atualizarCadastro($nome, $email){

        if (self::existsByEmail($email) < 1){
            throw new Exception("usuário não cadastrado.", BAD_REQUEST);
        }

        try {
            $usuario = self::getFromEmail($email);
        } catch (Exception $e) {
            throw $e;
        }
        

        if (!empty($usuario)){
            try {
                self::edit($usuario[0]['id_usuario'], $nome, $email);
            } catch (Exception $e) {
                throw $e;
            }
        }

        return true;
    }

    public static function alterarSenha($user_out){

        if (self::existsByEmail($user_out['email']) < 1){
            throw new Exception("usuário não cadastrado.", BAD_REQUEST);
        }

        try {
            $user_in = self::getFromEmail($user_out['email']);
        } catch (Exception $e) {
            throw $e;
        }
        

        if (!password_verify($user_out['senha_atual'], $user_in[0]['senha'])){
            throw new Exception("senha inválida.", BAD_REQUEST);
        } 


        $senha_hash = password_hash($user_out['nova_senha'], PASSWORD_BCRYPT, ["const" => 11]);

        try {
            self::editSenha($user_out['email'], $senha_hash);
        } catch (Exception $e) {
            throw $e;
        }
        

        return true;
    }

    public static function removerUsuario($user_out){

        if (self::existsByEmail($user_out['email']) < 1){
            throw new Exception("usuário não cadastrado.", BAD_REQUEST);
        }

        try {
            $user_in = self::getFromEmail($user_out['email']);
        } catch (Exception $e) {
            throw $e;
        }
        

        if (!password_verify($user_out['senha'], $user_in[0]['senha'])){
            throw new Exception("senha inválida.", BAD_REQUEST);
        } 

        try {
            Venda::deleteByUser($user_in[0]['id_usuario']);
            self::delete($user_in[0]['id_usuario']);
        } catch (Exception $e) {
            throw $e;
        }

        
        
        return true;
    }

    
    public static function checkSenha($user_out){

        try {
            $user_in = self::getFromEmail($user_out['email']);
        } catch (Exception $e) {
            throw $e;
        }
        

        if (count($user_in) == 0){
            throw new Exception("usuário não cadastrado", NOT_FOUND);
        }

        if (password_verify($user_out['senha'], $user_in[0]['senha'])){
            return true;
        } else {
            return false;
        }
    }

}