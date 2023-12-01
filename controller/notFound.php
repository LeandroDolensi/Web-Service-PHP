<?php

$mensagem = array(
    "status" => NOT_FOUND,
    "mesagem" => "página não encontrada",
    "url_heler" => URL_HELPER
);

header("HTTP/1.0 " . $mensagem['status']);
echo str_replace("\/", "/", json_encode($mensagem));

die;

?>