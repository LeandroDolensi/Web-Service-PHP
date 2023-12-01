<?php

// configs
require_once __DIR__ . "/constants.php";
require_once __DIR__ . "/Conexao.php";
require_once __DIR__ . "/header.php";
require_once __DIR__ . "/JWT.php";
require_once __DIR__ . "/util.php";
require_once __DIR__ . "/verbs.php";
require_once __DIR__ . "/url_helper.php";

// ambiente
require_once __DIR__ . "/../controller/ambiente/GamesCI.php";
require_once __DIR__ . "/../controller/ambiente/UsuarioCI.php";
require_once __DIR__ . "/../controller/ambiente/VendaCI.php";

// modelos 
require_once __DIR__ . "/../model/JWTModel.php";
require_once __DIR__ . "/../model/Games.php";
require_once __DIR__ . "/../model/Usuario.php";
require_once __DIR__ . "/../model/Venda.php";

// controladora geral do serviço
require_once __DIR__ . "/../system/ControllerCI.php";
require_once __DIR__ . "/../controller/Ambiente.php";
?>