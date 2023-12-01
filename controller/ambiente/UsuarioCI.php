<?php

require_once __DIR__ . "/../../system/ControllerCI.php";

class UsuarioCI extends ControllerCI {


    private $metodo;
    private $argumentos;
    private $metodos_disponiveis;

    public function __construct($parametros=[]){

        if (empty($parametros) || empty($parametros[0])){
            $this->metodo = 'getUserInfo';
        } else {
            $this->metodo = $parametros[0];
        }

        $this->argumentos = array_splice($parametros, 1);

        $this->metodos_disponiveis = ['getUserInfo', 
                                      'cadastrar', 
                                      'logar', 
                                      'logout',
                                      'atualizar_cadastro',
                                      'alterar_senha',
                                      'apagar_conta'];

        parent::__construct(self::class, $this->metodo, $this->metodos_disponiveis);
        $this->init();

    }

    public function init(){

        switch ($this->metodo) {
            case 'getUserInfo':
                $this->getUserInfo();
                break;
            
            case 'cadastrar':
                $this->cadastrar();
                break;

            case 'logar':
                $this->logar();
                break;
            
            case 'logout':
                $this->logout();
                break;

            case 'atualizar_cadastro':
                $this->atualizarCadastro();
                break;
            
            case 'alterar_senha':
                $this->alterarSenha();
                break;
            
            case 'apagar_conta':
                $this->removerUsuario();
                break;

            default:
                $this->metodoNaoEncontrado($this->metodo);
                break;
        }
    }

    
    private function getUserInfo(){

        if (!isMetodo("GET")){
            $this->metodoNaoPermitido("método precisa ser do tipo GET");
        }

        try {
            $usuario = Usuario::getFromEmail($this->getUsuario());
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
                "status" => OK,
                "mensagem" => [
                    "name" => $usuario[0]['nome'],
                    "email" => $usuario[0]['email'],
                    "data_conta_criada" => $usuario[0]['data_criacao']
                ]
            ]);
        
        $this->retornaResposta($mensagem);
    }


    private function cadastrar(){

        if (!$this->checkRequest('POST', ['nome', 'email', 'senha'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $mensagem = ([
                "status" => BAD_REQUEST,
                "mensagem" => "email inválido"
            ]);

            $this->retornaResposta($mensagem);
        }

        if (empty($_POST['nome']) || empty($_POST['senha'])){
            $mensagem = ([
                "status" => BAD_REQUEST,
                "mensagem" => "parâmetros não podem estar vazios"
            ]);

            $this->retornaResposta($mensagem);
        }
        
        try {
            $usuario = Usuario::novoUsuario($_POST['nome'], $_POST['email'], $_POST['senha']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }

        $mensagem = ([
            "status" => CREATED,
            "mensagem" => "usuário cadastrado com sucesso",
            "logar_usuario" => $this->docLogin()
        ]);

        $this->retornaResposta($mensagem);

    }


    private function logar(){

        if (!$this->checkRequest('POST', ['email', 'senha'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        try {
            $exist = Usuario::existsByEmail($_POST['email']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        if (!$exist){
            $mensagem = ([
                "status" => NOT_FOUND,
                "mensagem" => "usuário não cadastrado",
                "cadastrar_usuario" => $this->docCadastro()
            ]);
            $this->retornaResposta($mensagem);
        }

        try {
            $check_senha = Usuario::checkSenha($_POST);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        if (!$check_senha){
            $mensagem = ([
                "status" => BAD_REQUEST,
                "mensagem" => "senha inválida"
            ]);
            $this->retornaResposta($mensagem);
        }

        try {
            $token = $this->generateToken($_POST);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
            "status" => OK,
            "mensagem" => "login realizado com sucesso",
            "token" => $token,
            "validade" => TEMPO_TOKEN_MINUTOS . " minutos",
            "logout" => $this->docLogOut()
        ]);
        
        $this->retornaResposta($mensagem);
    }


    private function logout(){

        if (!isMetodo("GET")){
            $this->metodoNaoPermitido("método precisa ser do tipo GET");
        }

        $usuario = $this->getUsuario();
        
        if (!$usuario){
            $mensagem = ([
                "status" => NOT_FOUND,
                "mensagem" => "usuário não está logado",
                "logar" => $this->docLogin(),
                "cadastrar" => $this->docCadastro()
            ]);

            $this->retornaResposta($mensagem);
        }


        try {
            $token = $this->generateToken(['email'=>$this->getUsuario($usuario)]);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
            "status" => OK,
            "mensagem" => "logout realizado com sucesso",
            "login" => $this->docLogin()
        ]);

        $this->retornaResposta($mensagem);
    }


    private function atualizarCadastro(){

        global $_PUT;
        
        if (!$this->checkRequest('PUT', ['nome', 'email'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }
        
        $usuario = $this->getUsuario();

        if ($usuario != $_PUT['email']){
            $mensagem = ([
                "status" => FORBIDDEN,
                "mensagem" => "email enviado não corresponde ao email do solicitante: " . $usuario,
                "user_info" => URL_USUARIO_INFO
            ]);

            $this->retornaResposta($mensagem);
        }

        if (!filter_var($_PUT['email'], FILTER_VALIDATE_EMAIL)){
            $mensagem = ([
                "status" => BAD_REQUEST,
                "mensagem" => "o novo email é inválido"
            ]);

            $this->retornaResposta($mensagem);
        }

        try {
            $usuario = Usuario::atualizarCadastro($_PUT['nome'], $_PUT['email']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }

        $mensagem = ([
            "status" => OK,
            "mensagem" => "cadastro atualizado com sucesso",
            "user_info" => URL_USUARIO_INFO
        ]);

        $this->retornaResposta($mensagem);
    }


    private function alterarSenha(){

        global $_PUT;
        
        if (!$this->checkRequest('PUT', ['email', 'senha_atual', 'nova_senha'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        try {
            $exist = Usuario::existsByEmail($_PUT['email']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        
        if (!$exist){
            $mensagem = ([
                "status" => NOT_FOUND,
                "mensagem" => "usuário não cadastrado",
                "cadastrar_usuario" => $this->docCadastro()
            ]);
            $this->retornaResposta($mensagem);
        }

        $usuario = $this->getUsuario();

        if ($usuario != $_PUT['email']){
            $mensagem = ([
                "status" => FORBIDDEN,
                "mensagem" => "email enviado não corresponde ao email do solicitante: " . $usuario,
                "user_info" => URL_USUARIO_INFO
            ]);

            $this->retornaResposta($mensagem);
        }

        
        try {
            $usuario = Usuario::alterarSenha($_PUT);
        } catch (Exception $e) {
            $this->retornaException($e);
        }

        $mensagem = ([
            "status" => OK,
            "mensagem" => "senha alterada com sucesso"
        ]);

        $this->retornaResposta($mensagem);
    }


    private function removerUsuario(){
        global $_DELETE;
        
        if (!$this->checkRequest('DELETE', ['email', 'senha'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        $usuario = $this->getUsuario();

        if ($usuario != $_DELETE['email']){
            $mensagem = ([
                "status" => FORBIDDEN,
                "mensagem" => "email enviado não corresponde ao email do solicitante: " . $usuario,
                "user_info" => URL_USUARIO_INFO
            ]);

            $this->retornaResposta($mensagem);
        }


        try {
            $exist = Usuario::existsByEmail($_DELETE['email']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        
        
        if (!$exist){
            $mensagem = ([
                "status" => NOT_FOUND,
                "mensagem" => "usuário não cadastrado",
                "cadastrar_usuario" => $this->docCadastro()
            ]);
            $this->retornaResposta($mensagem);
        }

        

       
        
        try {
            $usuario = Usuario::removerUsuario($_DELETE);
        } catch (Exception $e) {
            $this->retornaException($e);
        }

        $mensagem = ([
            "status" => OK,
            "mensagem" => "usuario removido com sucesso"
        ]);

        $this->retornaResposta($mensagem);
    }
}