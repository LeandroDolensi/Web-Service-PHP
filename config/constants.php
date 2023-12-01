<?php

// ambientes
$GLOBALS['ambientes'] = ['games', 'usuario', 'venda'];


// pasta da aplicação
defined("SERVER")                    || define ("SERVER", "http://localhost/");
defined("BASE_PATH")                 || define ("BASE_PATH", "epic_games/");
defined("URL_SERVER")                || define ("URL_SERVER", SERVER.BASE_PATH);

// jwt
defined("TEMPO_TOKEN_MINUTOS")       || define("TEMPO_TOKEN_MINUTOS", 60);

// verbos http
defined("OK")                        || define("OK", 200);
defined("CREATED")                   || define("CREATED", 201);
defined("BAD_REQUEST")               || define("BAD_REQUEST", 400);
defined("UNAUTHORIZED")              || define("UNAUTHORIZED", 401);
defined("FORBIDDEN")                 || define("FORBIDDEN", 403);
defined("NOT_FOUND")                 || define("NOT_FOUND", 404);
defined("METHOD_NOT_ALLOWED")        || define("METHOD_NOT_ALLOWED", 405);
defined("PRECONDITION_FAILED")       || define("PRECONDITION_FAILED", 412);
defineD("INTERNAL_SERVER_ERROR")     || define("INTERNAL_SERVER_ERROR", 500);


// endpoints

// url helper
defined("URL_HELPER")                || define("URL_HELPER", URL_SERVER);

// games
defined("URL_GAME")                  || define("URL_GAME", URL_SERVER."games/");
defined("URL_GAME_GETALL")           || define("URL_GAME_GETALL", URL_GAME ."getAll");
defined("URL_GAME_ADICIONAR")        || define("URL_GAME_ADICIONAR", URL_GAME ."adicionar");
defined("URL_GAME_ATUALIZAR")        || define("URL_GAME_ATUALIZAR", URL_GAME ."atualizar");
defined("URL_GAME_DELETAR")          || define("URL_GAME_DELETAR", URL_GAME ."deletar");

// usuario
defined("URL_USUARIO")               || define("URL_USUARIO", URL_SERVER."usuario/");
defined("URL_USUARIO_CADASTRAR")     || define("URL_USUARIO_CADASTRAR", URL_USUARIO."cadastrar");
defined("URL_USUARIO_LOGAR")         || define("URL_USUARIO_LOGAR", URL_USUARIO."logar");
defined("URL_USUARIO_LOGOUT")        || define("URL_USUARIO_LOGOUT", URL_USUARIO."logout");
defined("URL_USUARIO_INFO")          || define("URL_USUARIO_INFO", URL_USUARIO);
defined("URL_USUARIO_ATUALIZAR")     || define("URL_USUARIO_ATUALIZAR", URL_USUARIO."atualizar_cadastro");
defined("URL_USUARIO_ALTERAR_SENHA") || define("URL_USUARIO_ALTERAR_SENHA", URL_USUARIO."alterar_senha");
defined("URL_USUARIO_DELETAR")       || define("URL_USUARIO_DELETAR", URL_USUARIO."apagar_conta");

// venda
defined("URL_VENDA")                 || define("URL_VENDA",  URL_SERVER."venda/");
defined("URL_VENDA_COMPRAR")         || define("URL_VENDA_COMPRAR", URL_VENDA."comprar");
defined("URL_VENDA_GETALL")          || define("URL_VENDA_GETALL", URL_VENDA);
?>