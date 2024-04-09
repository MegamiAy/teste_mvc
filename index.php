<?php
session_start();                                // função que inicia ou retoma uma sessão, as sessões são usadas para manter o estado do usuário entre diferentes requisições HTTP. exemplo: você pode usar sessões para manter informações de login do usuário, carrinhos de compras em sites de comércio eletrônico, preferências do usuário e assim por diante
require 'config.php';                           // puxa, requere... é usada para incluir e executar o conteúdo de um arquivo. se o arquivo especificado não for encontrado ou não puder ser incluído, o PHP emitirá um erro fatal e interromperá a execução do scriptz

spl_autoload_register(function($class){                        // esta função de autoload é responsável por carregar automaticamente classes quando elas são necessárias no script, baseando-se no nome da classe para determinar o caminho do arquivo a ser incluído. 
    if (file_exists('controllers/'.$class.'.php')){            // recebe o nome da classe como parâmetro $class. verifica se existe um arquivo correspondente ao nome da classe na pasta controllers.
        require_once 'controllers/'.$class.'.php';             // se exite, inclui o arquivo. se não existe, verifica na pasta controllers, models, core e inclui o arquivo, se existir.
    } elseif(file_exists('models/'.$class.'.php')) {
        require_once 'models/'.$class.'.php';
    } elseif(file_exists('core/'.$class.'.php')) {
        require_once 'core/'.$class.'.php';
    } elseif(file_exists('helpers/'.$class.'.php')) {
        require_once 'helpers/'.$class.'php';
    }
});

$core = new Core();                               // essas duas linhas de código estão pedindo um objeto da classe Core e,
$core->run();                                     // em seguida, chamando o método run() desse objeto

// em resumo, aqui é iniciada uma sessão, requerido as configurações do banco de dados dentro de "config.php"
// criadas as autoloads para conectar as funcionalidades do projeto todo
// e por fim inicia Core, que tem as configurações base do site/sistema/app