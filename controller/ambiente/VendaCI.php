<?php

class VendaCI extends ControllerCI {
    
    private $metodo;
    private $argumentos;
    private $metodos_disponiveis;

    public function __construct($parametros=[]){

        if (empty($parametros) || empty($parametros[0])){
            $this->metodo = 'getUserCompras';
        } else {
            $this->metodo = $parametros[0];
        }

        $this->argumentos = array_splice($parametros, 1);

        $this->metodos_disponiveis = ['getUserCompras', 
                                      'comprar'];

        parent::__construct(self::class, $this->metodo, $this->metodos_disponiveis);
        $this->init();

    }

    public function init(){

        switch ($this->metodo) {
            case 'getUserCompras':
                $this->getUserCompras();
                break;
        
            case 'comprar':
                $this->comprar();
                break;
            
            default:
                $this->metodoNaoEncontrado($this->metodo);
                break;
        }
    }

    private function getUserCompras(){

        if (!isMetodo("GET")){
            $this->metodoNaoPermitido("método precisa ser do tipo GET");
         }

        try {
            $usuario = Usuario::getFromEmail($this->getUsuario());
            $compras = Venda::getFromIdUsuario($usuario[0]['id_usuario']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }


        $cada_venda = [];

        foreach ($compras as $value) {

            $venda = array(
                "usuario" => $value['email_usuario'],
                "game" => $value['nome_game'],
                "quantidade" => $value['quantidade'],
                "preco_total" => $value['preco_total'],
                "data_compra" => $value['data_venda']
            );

            array_push($cada_venda, $venda);
        }


        $mensagem = ([
            "status" => OK,
            "mensagem" => "requisição realizada com sucesso",
            "jogos comprados" => $cada_venda
        ]);

        $this->retornaResposta($mensagem);

    }


    private function comprar(){

        if (!isMetodo("GET")){
            $this->metodoNaoPermitido("método precisa ser do tipo GET");
         }


        if (
                !(isset($this->argumentos) ) 
            && (!(isset($this->argumentos[0])))
            || (empty($this->argumentos[0]))
            || (count($this->argumentos)>2)

        ){$this->metodoNaoPermitido("quantidade de parâmetros inválidos");}


        $id_game = $this->argumentos[0];

        if (!is_numeric($id_game)){
            $this->metodoNaoPermitido("parâmetros devem ser numéricos");
        }

        if (isset($this->argumentos[1])){
            if (!is_numeric($this->argumentos[1])){
                $this->metodoNaoPermitido("parâmetros devem ser numéricos");
            }
        }

        try {
            $exist_game = Games::existsById($id_game);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        
        if (!$exist_game){
            $this->conteudoNaoEncontrado("jogo com id ".$id_game." não encontrado");
        }

        
        $quantidade = (isset($this->argumentos[1])) ? round($this->argumentos[1]) : 1;



        try {
            $game = Games::getFromId($id_game);
            $usuario = Usuario::getFromEmail($this->getUsuario());
        } catch (Exception $e) {
            $this->retornaException($e);
        }


        $preco_total = $game[0]['preco'] * $quantidade;

        try {
            Venda::add($id_game, $usuario[0]['id_usuario'], $quantidade, $preco_total);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
            "status" => CREATED,
            "mensagem" => "compra efetuada com sucesso",
            "compras_realizadas" => URL_VENDA_GETALL
        ]);

        $this->retornaResposta($mensagem);
    }
}

?>