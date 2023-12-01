<?php

require_once __DIR__ . "/../config/JWT.php";
require_once __DIR__ . "/../config/verbs.php";

class ControllerCI {
    
    private $usuario;
    private $visitante;
    private $jwt;

    public function __construct($ambiente, $metodo, $metodos_disponiveis=[]){

        if ($this->checkVisitante($ambiente, $metodo, $metodos_disponiveis)){
            $this->visitante = true;
        }

        $this->jwt = new JWTToken();

        if (!$this->visitante){

            try {
                $this->usuario = $this->jwt->getUserEmail();
            } catch (Exception $e) {
                $this->retornaException($e);
            }

            if (!$this->usuario){
                $this->naoAutorizado();
            }
        }
    }



    public function getUsuario(){
        return $this->usuario;
    }

    
    public function isAdmin(){
        try {
            $usuario = Usuario::getFromEmail($this->usuario);
        } catch (Exception $e) {
            $this->retornaException($e);
        }

        if ($usuario[0]['admin'] == "S"){
            return true;
        } else {
            return false;
        }
    }


    public function naoAdmin(){

        $mensagem = ([
            'status' => UNAUTHORIZED,
            'mensagem' => 'método de acesso adminstrativo',
            'url_helper' => URL_HELPER
        ]);

        $this->retornaResposta($mensagem);
        die;
    }


    public function retornaResposta($mensagem){

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


    public function retornaException(Exception $e){
        $mensagem = ([
            "status" => $e->getCode(),
            "mensagem" => $e->getMessage()
        ]);

        $this->retornaResposta($mensagem);
        die;
    }


    private function checkVisitante($ambiente, $metodo, $metodos_disponiveis=[]){

        if(!(isset($ambiente)) || !(isset($metodo))){
            return false;
        }

        // usuário visitante quer ver lista de jogos
        if (($ambiente == "GamesCI") && ($metodo == "getAll")){
            return true;
        }

        // usuário novo ou deslogado
        if ($ambiente == "UsuarioCI"){
            
            // usuário esta fazendo novo cadastro
            if ($metodo == "cadastrar"){
                return true;
            }
            
            // usuário está tentando fazer login
            if ($metodo == "logar"){
                return true;
            }
            
        }

    

        foreach ($metodos_disponiveis as  $value) {
            if ($value == $metodo){
                return false;
            }
        }

        $this->metodoNaoEncontrado($metodo);
        
        
    }


    public function checkRequest($metodo, $paramentros=[]){

        if (!isMetodo($metodo)){
            $this->metodoNaoPermitido("método precisa ser do tipo ". $metodo);
        }

        switch ($metodo) {
            case 'POST':
                if (parametrosValidos($_POST, $paramentros)){return true;}
                break;

            case 'GET':
                if (parametrosValidos($_GET, $paramentros)){return true;}
                break;
            
            case 'PUT':
                global $_PUT;
                if (parametrosValidos($_PUT, $paramentros)){return true;}
                break;
            
            case 'DELETE':
                global $_DELETE;
                if (parametrosValidos($_DELETE, $paramentros)){return true;}
                break;

            default:
                return false;
                break;
        }
    }


    public function generateToken($usuario){

        return $this->jwt->generateToken($usuario['email']);

    }


    public function docLogin(){
        $mensagem = ([
                'url' => URL_USUARIO_LOGAR,
                'metodo' => 'POST',
                'exemplo_corpo' => [
                    'email' => 'exemplo@exemplo',
                    'senha' => 'exemplo_senha'
                    ]
        ]);

        return $mensagem;
    }


    public function docCadastro(){
        $mensagem = ([
                'url' => URL_USUARIO_CADASTRAR,
                'metodo' => 'POST',
                'exemplo_corpo' => [
                    'nome' => 'exemplo_nome',
                    'email' => 'exemplo@exemplo',
                    'senha' => 'exemplo_senha'
                ]
        ]);
        return $mensagem;
    }


    public function docLogOut(){
        $mensagem = ([
            'url' => URL_USUARIO_LOGOUT,
            'metodo' => 'GET'
        ]);

        return $mensagem;
    }


    public function naoAutorizado(){

        $mensagem = ([
            'status' => UNAUTHORIZED,
            'mensagem' => 'usuário não logado ou cadastrado',
            'logar_usuario' => $this->docLogin(),
            'cadastrar_usuario' => $this->docCadastro()
        ]);

        $this->retornaResposta($mensagem);
        die;
    }
    

    public function metodoNaoPermitido($texto){

        $mensagem = ([
            "status" => METHOD_NOT_ALLOWED,
            "mensagem" => $texto,
            "url_helper" => URL_HELPER
            ]);
        
        $this->retornaResposta($mensagem);
        die;
        
    }


    public function conteudoNaoEncontrado($texto){
        $mensagem = ([
            "status" => NOT_FOUND,
            "mensagem" => $texto
            ]);
        
        $this->retornaResposta($mensagem);
        die;
    }


    public function metodoNaoEncontrado($metodo){

        $mensagem = ([
            "status" => NOT_FOUND,
            "mensagem" => "o método '" . $metodo . "' não foi encontrado",
            "url_helper" => URL_HELPER
            ]);
        
        $this->retornaResposta($mensagem);
        die;
    }
}

?>