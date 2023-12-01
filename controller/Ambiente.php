<?php

if (!isset($_SERVER['REQUEST_URI']) || (empty($_SERVER['REQUEST_URI']))){
    require_once __DIR__ . "/notFound.php";
}

$url = explode(BASE_PATH, $_SERVER['REQUEST_URI']);

if (count($url) <= 1){
    require_once __DIR__ . "/notFound.php";
}



$ambiente = explode("/", $url[1]);

switch ($ambiente[0]) {
    case 'games':
        new GamesCI(array_splice($ambiente, 1));
        break;
    
    case 'usuario':
        new UsuarioCI(array_splice($ambiente, 1));
        break;
    
    case 'venda':
        new VendaCI(array_splice($ambiente, 1));
        break;
    
    case '':
        retornaURLHelper();
        break;

    default:
        require_once __DIR__ . "/notFound.php";
        break;
}

?>