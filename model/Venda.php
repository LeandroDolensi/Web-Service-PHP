<?php 

class Venda {


    public static function add($id_game, $id_usuario, $quantidade, $preco_total)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO venda(id_game, id_usuario, quantidade, preco_total) 
                                      VALUES (:id_game, :id_usuario, :quantidade, :preco_total)");
            $stmt->execute([
                "id_game" => $id_game,
                "id_usuario" => $id_usuario,
                "quantidade" => $quantidade,
                "preco_total" => $preco_total
            ]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    
    public static function delete($id_venda)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM venda WHERE id_venda = ?");
            $stmt->execute([$id_venda]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function deleteByUser($id_usuario){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM venda WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function deleteByGame($id_game){

        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM venda WHERE id_game = ?");
            $stmt->execute([$id_game]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getFromId($id_venda)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM venda WHERE id_venda = ?");
            $stmt->execute([$id_venda]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromIdGame($id_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM venda WHERE id_game = ?");
            $stmt->execute([$id_game]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromIdUsuario($id_usuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM vw_venda_games WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getAll()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM venda ORDER BY id");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function existsById($id_venda)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM venda WHERE id_venda = ?");
            $stmt->execute([$id_venda]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getNumber()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM venda");
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

}