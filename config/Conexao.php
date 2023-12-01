<?php


class Conexao
{
    private static $instancia;

    private function __construct()
    {

        if (in_array($_SERVER['REMOTE_ADDR'], ["127.0.0.1", "::1"])) {
            // O servidor roda na própria máquina
            $hostname = 'localhost';
            $database = 'epic_games';
            $username = 'root';
            $password = '';
        } else {
            $hostname = 'xxxxxx';
            $database = 'xxxxxx';
            $username = 'xxxxxx';
            $password = "xxxxxx";
        }

        $dsn = "mysql:host=$hostname;dbname=$database";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            self::$instancia = new PDO($dsn, $username, $password, $options);
        } catch (Exception $e) {
            throw new Exception("aplicação indisponível no momento", INTERNAL_SERVER_ERROR);
        }
    }

    public static function getConexao()
    {
        if (!isset(self::$instancia)) {
            try {
                new Conexao();
            } catch (Exception $e) {
                throw $e;
            }
        }
        
        return self::$instancia;

    }
}
