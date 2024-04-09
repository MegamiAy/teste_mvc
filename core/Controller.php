<?php
class Controller {                      // aqui ira ficar as funções principais, usadas em tudo... como:

    protected $db;                      // criação de uma propriedade protegida, se refere a uma conexão com o banco de dados 

    public function __construct(){      // função que recebe a configuração global
        global $config;
    }

    public function loadView($viewName, $viewData = array()){       // essa função é utilizada dentro dos controllers... a função é responsável por carregar na view e passar dados para ela
        extract($viewData);                                         // extrai os dados da view para que possam ser acessados
        include 'views/'.$viewName.'.php';                          // inclue os dados da view especificada, é utilizado da seguinte forma... por exemplo no controller notFoundContoller.php:
    }                                                               // $this->loadView('404', $data); ... $this-> usado dentro da função para acessar propriedades e funções da própria classe (Controller)... o objeto ($this), passa (->) dois parâmetros para o loadView: dentro de () temos os parâmetros a serem enviados para $viewData e para $viewName = loadView('nomedaview', $data)

}