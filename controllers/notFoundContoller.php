<?php 
class notFoundContoller extends Controller {

    public function index(){                             // a função index() é usada como o ponto de entrada padrão para um controlador. isso significa que quando nenhuma função específica é fornecida na URL, a função index() é chamada por padrão.
        $data = array();                                 // estabelece os dados a serem passados para a função loadView()

        $this->loadView('404', $data);                   // o objeto ($this), passa (->) dois parâmetros para o loadView: os parâmetros a serem enviados para $viewName e para $viewData, respectivamente = loadView('nomedaview', $data) 
    }

}