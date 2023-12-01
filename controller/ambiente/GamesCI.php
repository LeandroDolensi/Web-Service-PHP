<?php

require_once __DIR__ . "/../../system/ControllerCI.php";


class GamesCI extends ControllerCI {

    private $metodo;
    private $argumentos;
    private $metodos_disponiveis;

    public function __construct($parametros=[]){

        if (empty($parametros) || empty($parametros[0])){
            $this->metodo = 'getAll';
        } else {
            $this->metodo = $parametros[0];
        }

        $this->argumentos = array_splice($parametros, 1);

        $this->metodos_disponiveis = ['getAll', 
                                      'adicionar', 
                                      'atualizar', 
                                      'deletar'];

        parent::__construct(self::class, $this->metodo, $this->metodos_disponiveis);

        $this->init();
    }

    public function init(){

        switch ($this->metodo) {
            case 'getAll':
                $this->getAll();
                break;
            
            case 'adicionar':
                $this->adicionar();
                break;
            
            case 'atualizar':
                $this->atualizar();
                break;

            case 'deletar':
                $this->deletar();
                break;

            default:
                $this->metodoNaoEncontrado($this->metodo);
                break;
        }
    }


    private function getAll(){

        if (!isMetodo("GET")){
            $this->metodoNaoPermitido("método precisa ser do tipo GET");
        }


        try {
            $list_games = Games::getAll();
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        foreach ($list_games as $key => $value) {

            $jogo = array(
                "url" => URL_VENDA_COMPRAR . "/" . $value['id_game'] . "/1"
            );

            $list_games[$key]['comprar_jogo'] = $jogo;
        }

        $mensagem = ([
            "status" => OK,
            "jogos_disponiveis" => $list_games
        ]);

        
        $this->retornaResposta($mensagem);
    }


    private function adicionar(){

        if(!$this->isAdmin()){
            $this->naoAdmin();
        }
        
        if (!$this->checkRequest('POST', ['nome', 'preco'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        if (!is_numeric($_POST['preco'])){
            $this->metodoNaoPermitido("parâmetro preco deve ser numéricos");
        }

        try {
            $exist = Games::existsByName($_POST['nome']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }


        if ($exist){
            $mensagem = ([
                "status" => BAD_REQUEST,
                "mensagem" => "este jogo já existe no acervo."
                ]);
            
            $this->retornaResposta($mensagem);
            die;
        }


        try {
            Games::add($_POST['nome'], $_POST['preco']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
            "status" => CREATED,
            "mensagem" => "jogo adicionado com sucesso ao acervo"
        ]);

        $this->retornaResposta($mensagem);

    }


    private function atualizar(){

        if(!$this->isAdmin()){
            $this->naoAdmin();
        }

        global $_PUT;

        if (!$this->checkRequest('PUT', ['id_game', 'nome', 'preco'])){
            $this->metodoNaoPermitido("nome ou quantidade de parâmentros inválidos");
        }

        try {
            $exist = Games::existsById($_PUT['id_game']);
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        if (!$exist){
            $this->conteudoNaoEncontrado("este game não existe no acervo.");
        }

        if ((!is_numeric($_PUT['id_game'])) || (!is_numeric($_PUT['preco']))){
            $this->metodoNaoPermitido("parâmetros id_game e preco devem ser numéricos");
        }

        try {
            Games::edit($_PUT['id_game'], $_PUT['nome'], $_PUT['preco']);
        } catch (Exception $e) {
            throw $e;
        }
        

        $mensagem = ([
            "status" => OK,
            "mensagem" => "jogo atualizado com sucesso no acervo"
        ]);

        $this->retornaResposta($mensagem);
    }


    private function deletar(){

        if(!$this->isAdmin()){
            $this->naoAdmin();
        }
        
        if (!isMetodo("DELETE")){
           $this->metodoNaoPermitido("método precisa ser do tipo DELETE");
        }

        if (
                !(isset($this->argumentos) ) 
            && (!(isset($this->argumentos[0])))
            || (empty($this->argumentos[0]))
            || (count($this->argumentos)>1)
        ){
            $this->metodoNaoPermitido("quantidade de parâmetros inválidos");
        }

        $id_game = $this->argumentos[0];

        if (!is_numeric($id_game)){
            $this->metodoNaoPermitido("parâmetros devem ser numéricos");
        }
        
        if (!Games::existsById($id_game)){
            $this->conteudoNaoEncontrado("jogo com id ".$id_game." não encontrado");
        }

        try {

            Venda::deleteByGame($id_game);
            Games::delete($id_game);
            
        } catch (Exception $e) {
            $this->retornaException($e);
        }
        

        $mensagem = ([
            "status" => OK,
            "mensagem" => "game removido com sucesso"
        ]);

        $this->retornaResposta($mensagem);
    }
}


?>