<?php 



class Games {


    public static function add($nome, $preco)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO games(nome,preco) VALUES (?, ?)");
            $stmt->execute([$nome, $preco]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    
    public static function delete($id_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM games WHERE id_game = ?");
            $stmt->execute([$id_game]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function edit($id_game, $nome, $preco)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE games SET nome = :nome, preco = :preco WHERE id_game = :id_game");
            $stmt->execute([
                "nome" => $nome,
                "preco" => $preco,
                "id_game" => $id_game
            ]);

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getFromId($id_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM games WHERE id_game = ?");
            $stmt->execute([$id_game]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getFromName($name_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM games WHERE nome = ?");
            $stmt->execute([$name_game]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function getAll()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM games ORDER BY id_game");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }


    public static function existsById($id_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM games WHERE id_game = ?");
            $stmt->execute([$id_game]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }

    public static function existsByName($name_game)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM games WHERE nome = ?");
            $stmt->execute([$name_game]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }



    public static function getNumber()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM games");
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("erro no servidor de dados", INTERNAL_SERVER_ERROR);
        }
    }
}