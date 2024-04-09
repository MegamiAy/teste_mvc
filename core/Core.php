
<?php
class Core {                                                            
    
    public function run(){                                              // obtém a URL requisitada, ou define como '/' caso não seja especificada 
        $url = '/'.(isset($_GET['url'])?$_GET['url']:'');               // esta linha inicializa a variável $url. ela concatena uma barra diagonal no início e então verifica se existe um parâmetro chamado 'url' na variável superglobal $_GET. Se existir, atribui o valor desse parâmetro à variável $url; caso contrário, atribui uma string vazia exemplo "teste_mvc/".

        $params = array();                                              // aqui é inicializado um array vazio

        if(!empty($url) && $url != '/') {                               // é uma estrutura condicional que verifica se a variável $url não está vazia e se não é igual a uma barra diagonal sozinha
            $url = explode('/', $url);                                  // se a condição do anterior for verdadeira, a string contida em $url é dividida em um array usando a barra diagonal como delimitador. significa que se a URL for algo como "/rota1/rota2", ela será dividida em um array contendo ['rota1', 'rota2']. essa é a função do explode()
            array_shift($url);

            $currentController = $url[0].'Controller';                  // cria uma variável chamada $currentController. Ela concatena a primeira parte da URL, armazenada na posição 0 do array $url, com a string 'Controller'. essa concatenação é feita para gerar o nome do controlador que será usado no código. exemplo: se a primeira parte da URL for 'pagina', a variável $currentController será 'paginaController'.
            array_shift($url);                                          // é feito para "limpar" a URL depois de ter extraído a primeira parte dela para usar no nome do controlador

            if(isset($url[0]) && $url[0] != '/'){                       // verifica se existe algum elemento na posição 0 do array $url e se esse elemento não é igual a '/'... se a condição for verdadeira, o código dentro deste bloco é executado, caso contrário, o bloco else é executado.
                $currentAction = $url[0];                               // o código atribui essa ação à variável $currentAction. por exemplo, se a URL for "/produto/listar", a variável $currentAction receberá "listar", que representa a ação a ser executada pelo controlador.
                array_shift($url);                                     
            } else {
                $currentAction = 'index';                               // se a condição do primeiro passo não for verdadeira, o código define $currentAction como 'index'. isso significa que ou uma ação padrão será executada pelo controlador.
            }

            if(count($url) > 0){                                        // qualquer segmento restante na URL é considerado como parâmetros  
                $params = $url;                                         // exemplo: /home/edit/3... o número 3 é parâmetro, edit é a ação do controlador e home é o nome do controlador
            }

        } else {
            $currentController = 'homeController';                      // URL vazia = controlador padrão definido como 'homeController' e a ação padrão como 'index'
            $currentAction = 'index';
        }

        if(!file_exists('controllers/'.$currentController.'.php')){     // se o controlador não existir, define o controlador de erro padrão
            $currentController = 'notFoundController';
            $currentAction = 'index';
        }

        $c = new $currentController();                                  // fornece o controlador apropriado

        if(!method_exists($c, $currentAction)){                         // verifica se a ação especificada dentro do controlador existe, senão, utiliza a ação padrão
            $currentAction = 'index';
        }

        call_user_func_array(array($c, $currentAction), $params);        // puxa a ação do controlador com os parâmetros fornecidos

    }

}


// em resumo, Core é responsável por inicializar e executar a lógica central