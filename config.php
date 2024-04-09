<?php
require 'environment.php';                  // require é usada para incluir e executar o conteúdo de um arquivo

global $config;                             // usada para importar uma variável global definida fora do escopo atual para o escopo local de uma função
global $db;                                 //  usada para importar uma variável global também. $db se refere a uma conexão com o banco de dados que foi definida em algum lugar fora do escopo da função atual.

$config = array();                                              // dados de configuração
if (ENVIRONMENT == 'development'){                              // onde é utilizada a constante environment.php
    define('BASE_URL', "http://localhost/php7/teste_mvc/");     // defino uma url base
    $config['dbname'] = 'estoque_laiz';                         // nome do banco
    $config['host'] = '192.168.1.200';                          // host... localhost ou o ip do server
    $config['dbuser'] = 'root';                                 // usuário
    $config['dbpass'] = '';                                     // senha
} else {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $config['dbname'] = 'nova_loja';
    $config['host'] = '192.168.1.200';
    $config['dbuser'] = 'root';
    $dbpass['dbpass'] = '';
}

$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);        // esta linha cria uma nova atribuição da classe PDO, que é uma classe no PHP que representa uma conexão com um banco de dados usando o PDO
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                                                           // são cofnigurações do modo de erro

// new PDO(...): Cria uma nova atribuição.
// "mysql:dbname=".$config['dbname'].";host=".$config['host']: Especifica o DSN (Data Source Name) para a conexão com o banco de dados MySQL. o DSN contém informações sobre o tipo de banco de dados, o nome do banco de dados e o host

// PDO::ATTR_ERRMODE: é um atributo que controla como o PDO lida com erros
// PDO::ERRMODE_EXCEPTION: é um modo de erro que diz ao PDO para lançar exceções quando ocorrem erros. isso é útil para lidar com erros de forma mais "bonita" e controlada

// em resumo, este aruivo faz as configurações e ligações com o banco de dados