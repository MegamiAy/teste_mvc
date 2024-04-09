<?php
class Controller {                      // aqui ira ficar as funções principais, usadas em tudo... como:

    protected $db;                      // criação de uma propriedade protegida, se refere a uma conexão com o banco de dados 

    public function __construct(){      // função que recebe a configuração global
        global $config;
    }

    public function loadView($viewName, $viewData = array()){       // essa função é utilizada dentro dos controllers... a função é responsável por carregar na view e passar dados para ela
        extract($viewData);                                         // extrai os dados da view para que possam ser acessados
        include 'views/'.$viewName.'.php';                          // inclue os dados da view especificada, é utilizado da seguinte forma... por exemplo no controller notFoundContoller.php:
        // EXEMPLO: $this->loadView('404', $data); ... $this-> usado dentro da função para acessar propriedades e funções da própria classe (Controller)... o objeto ($this), passa (->) dois parâmetros para o loadView: dentro de () temos os parâmetros a serem enviados para $viewData e para $viewName = loadView('nomedaview', $data)
    }                                                               

    public function loadTemplate($viewName, $viewData = array()){
        include 'view/template.php';
    }

    public function loadViewInTemplate($viewName, $viewData) {      // função especifica para página de template
        extract($viewData);
        include 'views/'.$viewName.'.php';
    }	

}

// DIFERENÇA ENTRE loadView E loadViewInTemplate:
// ambos os métodos têm a mesma funcionalidade principal, que é carregar uma view em um template e extrair os dados para que possam ser usados na view.

//loadView($viewName, $viewData = array()):

// esta função tem um parâmetro opcional ($viewData), que é iniciado como um array vazio caso não seja fornecido o $viewData.
// ou seja, você pode chamar loadView() sem dar informações ao segundo parâmetro ($viewData), e ele ainda funcionará. exemplo: $this->loadView('nomedaview');
// e se você passar o segundo parâmetro $viewData, ele será extraído e usado na view, como é o caso em extract($viewData);.

// já em:

// loadViewInTemplate($viewName, $viewData):

// este método não tem um valor padrão para $viewData, o que significa que você deve sempre fornecer um array de dados quando chamar esta função. exemplo: $objeto->loadViewInTemplate('nomedaview', $data);
// não há verificação interna para garantir que $viewData seja realmente um array. se chamar loadViewInTemplate() sem um segundo argumento ou com um argumento que não seja um array, isso pode resultar em um erro de execução.

// resumo, a diferença é que loadView() permite que você chame a função sem fornecer explicitamente os dados da view, enquanto loadViewInTemplate() requer que você sempre forneça esses dados.
