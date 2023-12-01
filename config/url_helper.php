<?php



function build_url_helper(){
    $mensagem = ([
        "status" => OK,
        "mensagem" => "Bem vindo ao serviço de compra de jogos Epic Games",
        "ambientes" => [
            "games" => [
                "getAll" => [
                    "restricao" => "aberto para todos",
                    "acesso" => "aberto",
                    "tipo" => "GET",
                    "url" => URL_GAME_GETALL,
                    "retorno" => "todos os jogos do acervo"
                ],
                "adicionar" => [
                    "restricao" => "fechado para administradores do sistema",
                    "acesso" => "Bearer Token",
                    "tipo" => "POST",
                    "url" => URL_GAME_ADICIONAR,
                    "parametros" => [
                        "nome" => "nome_do_game",
                        "preco" => "preco_do_game"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "atualizar" => [
                    "restricao" => "fechado para administradores do sistema",
                    "acesso" => "Bearer Token",
                    "tipo" => "PUT",
                    "url" => URL_GAME_ATUALIZAR,
                    "parametros" => [
                        "id_game" => "id_do_game",
                        "nome" => "nome_do_game",
                        "preco" => "preco_do_game"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "deletar" => [
                    "restricao" => "fechado para administradores do sistema",
                    "acesso" => "Bearer Token",
                    "tipo" => "DELETE",
                    "url" => URL_GAME_DELETAR,
                    "exemplo" => [
                        "id_game" => "id_do_game",
                        "url_exemplo" => URL_GAME_DELETAR."/id_do_game"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ]
            ],
            "usuario" => [
                "cadastrar" => [
                    "restricao" => "aberto para todos",
                    "acesso" => "aberto",
                    "tipo" => "POST",
                    "url" => URL_USUARIO_CADASTRAR,
                    "parametros" => [
                        "nome" => "nome_da_pessoa",
                        "email" => "email_da_pessoa",
                        "senha" => "senha_individual"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "logar" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "aberto",
                    "tipo" => "POST",
                    "url" => URL_USUARIO_LOGAR,
                    "parametros" => [
                        "email" => "email_da_pessoa",
                        "senha" => "senha_individual"
                    ],
                    "retorno" => "Bearer Token para acesso aos ambientes"
                ],
                "logout" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "GET",
                    "url" => URL_USUARIO_LOGOUT,
                    "retorno" => "status de sucesso ou falha"
                ],
                "user_info" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "GET",
                    "url" => URL_USUARIO_INFO,
                    "retorno" => "informações de cadastro do usuário"
                ],
                "atualizar_cadastro" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "PUT",
                    "url" => URL_USUARIO_ATUALIZAR,
                    "parametros" => [
                        "nome" => "nome_do_usuario",
                        "email" => "email_do_usuario"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "alterar_senha" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "PUT",
                    "url" => URL_USUARIO_ALTERAR_SENHA,
                    "parametros" => [
                        "email" => "email_do_usuario",
                        "senha_atual" => "senha_atual_do_usuario",
                        "nova_senha" => "senha_nova"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "apagar_conta" => [
                    "restricao" => "fechado para usuarios cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "DELETE",
                    "url" => URL_USUARIO_DELETAR,
                    "parametros" => [
                        "email" => "email_do_usuario",
                        "senha" => "senha_da_conta"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ]
            ],
            "venda" => [
                "comprar" => [
                    "restricao" => "fechado para usuários cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "GET",
                    "url" => URL_VENDA_COMPRAR,
                    "exemplo" => [
                        "id_game" => "id_do_game",
                        "quantidade" => "quantide_para_comprar",
                        "url_exemplo" => URL_VENDA_COMPRAR."/id_do_game/quantide_para_comprar"
                    ],
                    "retorno" => "status de sucesso ou falha"
                ],
                "get_compras" => [
                    "restricao" => "fechado para usuários cadastrados",
                    "acesso" => "Bearer Token",
                    "tipo" => "GET",
                    "url" => URL_VENDA_GETALL,
                    "retorno" => "todas as compras realizadas pelo usuários"
                ]
            ]
        ]
    ]);

    return $mensagem;
    
}

function retornaURLHelper(){

    $mensagem = build_url_helper();

    header("HTTP/1.0 " . $mensagem['status']);
    echo str_replace("\/", "/", json_encode($mensagem));
}




?>