<?php


function parametrosValidos($metodo, $lista)
{
    if (!isset($metodo)){
        $resposta = "parâmetros da requisição não foram enviados";
        requisicaoInvalida($resposta);
    }

    $obtidos = array_keys($metodo);
    $nao_encontrados = array_diff($lista, $obtidos);

    if (count($lista) != count($obtidos)){
        $resposta = "a quantidade de parâmetros é inválido para a requisição";
        requisicaoInvalida($resposta);
    }

    if (empty($nao_encontrados)) {
        foreach ($lista as $p) {
            if (empty(trim($metodo[$p]))) {
                $resposta = "parâmetro '" . $p . "' não pode estar vazio";
                requisicaoInvalida($resposta);
            }
        }
        return true;
    }

    return false;
}


function isMetodo($metodo)
{
    if (!strcasecmp($_SERVER['REQUEST_METHOD'], $metodo)) {
        return true;
    }
    return false;
}

function requisicaoInvalida($resposta){

    $mensagem = ([
        "status" => BAD_REQUEST,
        "mensagem" => $resposta,
        "url_helper" => URL_HELPER
    ]);

    if(!(isset($mensagem)) || !(isset($mensagem['status']))){
        header("HTTP/1.0 " . INTERNAL_SERVER_ERROR);
        $mensagem = ([
            "status" => INTERNAL_SERVER_ERROR,
            "mensagem" => "a requisição não pode ser completada"
        ]);
        echo str_replace("\/", "/", json_encode($mensagem));
    }

    header("HTTP/1.0 " . $mensagem['status']);
    echo str_replace("\/", "/", json_encode($mensagem));
    die;
}




// function output($codigo, $msg)
// {
//     http_response_code($codigo);
//     echo json_encode($msg);
//     exit;
// }


// // Função que retorna o valor do campo "usuario" da sessão, se houver. False, caso não exista.
// function idUsuarioLogado() {
//     if(parametrosValidos($_SESSION, ["usuario"])) {
//         return $_SESSION["usuario"];
//     } 
//     return false;
// }

// // Função que retorna o valor do campo "usuario" da sessão, se houver. False, caso não exista.
// function setIdUsuarioLogado($id) {
//     $_SESSION["usuario"] = $id;
// }

