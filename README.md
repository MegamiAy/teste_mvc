# MVC

* mvc = foi feito para separar o que é gráfico e o que é código

* user -> controller -> model -> controller -> view -> user

**view:** parte visual, págs em html

**controler:** intermediário do usuário, entre model e view

**model:** interação com o banco de dados

* apenas o arquivo index é acessado nesse modelo 

#

# SEG

* assets
  * css
    * style.css
  * imgs
  * js
    * script.js
      
* controllers
  * homeController.php
  * notFoundController.php
    
* core
  * Controller.php
  * Core.php
  * Model.php
 
* helpers
   * ExHelper.php

* models
  * User.php

* views
  * login.php
  * home.php
  * template.php
 
.htaccess, config.php, enviornmenti.php, index.php... na raíz do projeto

#

# BASE

#

## .htacess

* para afunilar tudo que o usuário acessa para o index, é necessário o .htacess

`RewriteEngine On` 		-> ativa o rewrite

`RewriteCond %{REQUEST_FILENAME} !-f`	-> condiciona: se acessar o nome de um arquivo real, ele vai ser acessado

`RewriteCond %{REQUEST_FILENAME} !-d`	-> condiciona: se acessar o nome de um diretório real, ele vai ser acessado

`RewriteRule ^(.*)$ /mvc/index.php/$1 [L]`	-> caso não seja acessado nada

`RewriteRule ^(.*)$ /mvc/index.php/$1 [L]`	-> se a condição for falsa, ele ira redirecionar para o index

#

## config.php
### configuração do banco de dados
    * `<?php 
        require 'environment.php';                                    // constante explicada abaixo
        
        global $config;
        global $db;
        
        $config = array();
        if (ENVIRONMENT == 'development'){                            // onde é utilizada a constante
            define('BASE_URL', "http://localhost/php7/mvc_laiz/");    // define a url base,
            $config['dbname'] = 'estoque_laiz';                       // nome do banco
            $config['host'] = '192.168.1.200';                        // host... localhost ou o ip
            $config['dbuser'] = 'root';                               // usuário do banco
            $config['dbpass'] = '';                                   // senha do banco
        } else {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $config['dbname'] = 'nova_loja';
            $config['host'] = '192.168.1.200';
            $config['dbuser'] = 'root';
            $config['dbpass'] = '';
        }
        
        $db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);`

#

 ## environment.php
  * constante que se refere ao ambiente de execução no qual o código está sendo executado.
    * **ambiente de desenvolvimento:** é onde os desenvolvedores escrevem e testam o código antes de disponibilizá-lo ao público.
    * **ambiente de produção:** é onde o aplicativo está disponível para os usuários finais.
   
    `<?php
        define("ENVIRONMENT", "development");`

#

## index.php
### onde tudo é acessado
    `<?php
        session_start();                                                  // esta linha inicia uma nova sessão ou resume a sessão existente, permitindo o uso de variáveis de sessão
        require 'config.php';                                             // requer o arquivo config.php, citado anteriomente
        
        spl_autoload_register(function ($class){                          // um autoload esté sendo carregado, isso permite carregar automaticamente as classes necessárias do aplicativo quando elas são instanciadas
            if(file_exists('controllers/'.$class.'.php')) {               // basicamente chamando todos autoloads e carregando eles, para que tudo que está dentro deles funione
                    require_once 'controllers/'.$class.'.php';
            } elseif(file_exists('models/'.$class.'.php')) {
                    require_once 'models/'.$class.'.php';
            } elseif(file_exists('core/'.$class.'.php')) {
                    require_once 'core/'.$class.'.php';
            } elseif(file_exists('helpers/'.$class.'.php')) {
                    require_once 'helpers/'.$class.'.php';
            }
        });
        
        $core = new Core();                                              // isso instancia um objeto da classe Core. esta classe é parte do núcleo que está sendo desenvolvido.
        $core->run();                                                    // inicia o roteamento e controla o fluxo do aplicativo, redirecionando as solicitações para os controladores apropriados com base nos URLs solicitados.`            

#

## Core.php
### núcleo
é responsável por controlar o roteamento das requisições HTTP em um aplicativo web baseado em PHP, determinando qual controlador e ação deve ser executado com base na URL requisitada

         <?php
         class Core {                                                         // a classe Core é responsável por inicializar e executar a lógica central
         
         	public function run() {                                           // obtém a URL requisitada, ou define como '/' caso não seja especificada
                 $url = '/'.(isset($_GET['url'])?$_GET['url']:'');
         
         		$params = array();                                             // inicializa um array para os parâmetros da URL
           
         		if(!empty($url) && $url != '/') {                              
         			$url = explode('/', $url);
         			array_shift($url);
         
         			$currentController = $url[0].'Controller';                  //o primeiro segmento da URL é considerado o nome do controlador
         			array_shift($url);
         
         			if(isset($url[0]) && $url[0] != '/') {                      // se houver um próximo segmento na URL, ele é considerado a ação do controlador
         				$currentAction = $url[0];
         				array_shift($url);
         			} else {
         				$currentAction = 'index';                                // a ação padrão é 'index'
         			}
                                                            
         			if(count($url) > 0) {                                       // qualquer segmento restante na URL é considerado como parâmetros  
         				$params = $url;      
         			}
                                                                              // como: /home/edit/3
                                                                              o número 3 é parâmetro, edit é a ação do controlador e home é o nome do controlador
         		} else {
         			$currentController = 'homeController';                     // URL vazia = controlador padrão definido como 'homeController' e a ação padrão como 'index'
         			$currentAction = 'index';
         		}
                     
         		if(!file_exists('controllers/'.$currentController.'.php')) {  // se o controlador não existir, define o controlador de erro padrão
         			$currentController = 'notFoundController'; 
         			$currentAction = 'index';
         		}
         
         		$c = new $currentController();                                // fornece o controlador apropriado
         
         		if(!method_exists($c, $currentAction)) {                      // ve se a ação especificada dentro do controlador existe, senão, utiliza a ação padrão
         			$currentAction = 'index';
         		}
         		call_user_func_array(array($c, $currentAction), $params);     // puxa a ação do controlador com os parâmetros fornecidos
         	}

         }

#

## Controller.php
### controlador principal
é uma classe base para outros controladores no aplicativo

         <?php
         class Controller {                                                      // setar a classe Controler 
         
         	protected $db;                                             // criação de uma propriedade protegida chamada $db, se refere a uma conexão com o banco de dados.                                                     
         	public function __construct() { '                          // essa função "construtora" recebe a configuração global
         		global $config;
         	}
         	
         	public function loadView($viewName, $viewData = array()) {         // essa função é utilizada dentro dos controllers (mostrados mais a frente)... a função é responsável por carregar na view e passar dados para ela
         		extract($viewData);                                             // extrai os dados da view para que possam ser acessados
         		include 'views/'.$viewName.'.php';                              // inclue os dados da view especificada
         	}
         
         	public function loadTemplate($viewName, $viewData = array()) {      // essa função é responsável por carregar um template que contém a estrutura da página
         		include 'views/template.php';                                    // basicamente inclui o arquivo do template que contém a estrutura HTML básica
         	}
         
         	public function loadViewInTemplate($viewName, $viewData) {           // essa função é responsável por carregar uma view dentro de um template
               extract($viewData);                                               // essa linha extrai os dados da view para que possam ser acessados
         		include 'views/'.$viewName.'.php';                                // inclui o arquivo da view especificada dentro do template
         	}
         
         }

#

## Model
### modelo base
é uma classe base para outros modelos no aplicativo

         <?php
         class Model {                            // setar a classe Model
         	
         	protected $db;                        
         
         	public function __construct() {
         		global $db;                       // a função construtor recebe um pedido do objeto de conexão com o banco de dados global
         		$this->db = $db;                  // define a propriedade protegida $db como o pedido do objeto de conexão com o banco de dados global
         	}
         }

#

## homeContrller.php
### exemplo de controller

         <?php
         class homeController extends Controller {                         // seta um controller em extão do Controller
         
             private $user;

             // primeira função de exibição e interação
         
             public function __construct(){                               // essa função construtora recebe uma configuração global, onde é necessário o login ser efetuado, para acessar as páginas seguintes
                 parent::__construct();
         
                 $this->user = new Users();                               // solicita um objeto Users(dentro da pasta models) para verificar o login do usuário
                 if(!$this->user->checkLogin()){                          // checar se o usuário fez o login, caso ele não esteja logado
                     header("Location: ".BASE_URL."login");               // redireciona para a página de login, para se logar
                     exit;
                 }
             }

             // segunda função, cria o conteúdo menu no home e criar pesquisa, na home
             
             public function index() {                                   // função que define a ação principal, carrega a página inicial do usuário logado
                 // parte do menu
                 
                 $data = array(                                          // dados a serem passados para a view, um menu de navegação presente no home
                     'menu' => array( 
                         BASE_URL.'home/add' => 'Adcionar Produto',
                         BASE_URL.'home/relatorio' => 'Relatório',
                         BASE_URL.'login/sair' => 'Sair'
                     )
                 );

                 // parte de pesquisa da view home
                 
                 $p = new Products();                                    // solicita um objeto Products(dentro da pasta models) para manipular os produtos
                 $s = '';                                                // inicia uma string de busca vazia, para fazer o input de pesquisa na home
         
                 if(!empty($_GET['busca'])){                             // verifica se foi feita uma busca
                     $s = $_GET['busca'];                                
                 }
                 $data['list'] = $p->getProducts($s);                   // obtém a lista de produtos, filtrada pela busca
                 $this->loadTemplate('home', $data);                    // carrega o template home, passando os dados
             }

             // terceira função, para criar conteúdo menu da Adicionar e criar adição de produtos, no add
         
             public function add(){                                      // função criada para adcionar produtos
                 // parte do menu
                 $data = array(                                         // dados a serem passados para a view, um menu de navegação presente no Adicionar Produtos
                     'menu' => array (
                         BASE_URL => 'Voltar'
                     )
                 );

                 // parte da adição de itens ao banco de dados
                 
                 $p = new Products();                                   // solicita um objeto Products(dentro da pasta models) para manipular os produtos
                 $filters = new FiltersHelper();                        // puxa o helper de filtro, que filtra os dados que serão adicionados ao banco, se certificando de que sejam formatados corretamente
         
                 if(!empty($_POST['cod'])){                                              // verificar se o formulário foi enviado
                     $cod = filter_input(INPUT_POST, 'cod', FILTER_VALIDATE_INT);        // filtra e valida os dados do formulário
                     $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);  
                     $price = $filters->filter_post_money('price');
                     $quantity = $filters->filter_post_money('quantity');
                     $min_quantity = $filters->filter_post_money('min_quantity');
         
         
                     if($cod && $name && $price && $quantity && $min_quantity) {          // verifica se todos os campos foram preenchidos corretamente, e se foram
                         $p->addProduct($cod, $name, $price, $quantity, $min_quantity);   // adiciona  o produto
         
                         header("Location: ".BASE_URL);                                   // tendo finalizado a adição do produto, será redirecionado para o caminho principal
                         exit;
                     } else {
                         $data['warning'] = 'digite os campos corretamente';              // mas caso ocorra algum erro, na tela vai aparecer uma mensagem de aviso
                     }
                 }
         
                 $this->loadTemplate('add', $data);                                       // carrega o template 'add' passando os dados para a view
         
             }      

             // quarta função, para criar conteúdo menu de Editar e para editar os produtos, em edit
             // parecido com adicionar
         
             public function edit($id){                                                      // função craida para editar produtos
                 // parte do menu
                 $data = array(                                                              
                     'menu' => array (
                         BASE_URL => 'Voltar'
                     )
                 );
                 $p = new Products();                                                       // solicita um objeto Products(dentro da pasta models) para manipular os produtos
                 $filters = new FiltersHelper();                                            // puxa o helper de filtro, que filtra os dados que serão editados no banco, se certificando de que sejam formatados corretamente
         
                 if(!empty($_POST['cod'])){                                                 // verificar se o formulário foi enviado
                     $cod = filter_input(INPUT_POST, 'cod', FILTER_VALIDATE_INT);           // filtra e valida os dados do forulário
                     $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                     $price = $filters->filter_post_money('price');
                     $quantity = $filters->filter_post_money('quantity');
                     $min_quantity = $filters->filter_post_money('min_quantity');
         
         
                     if($cod && $name && $price && $quantity && $min_quantity) {                // verifica se todos os campos foram preenchidos
                         $p->editProduct($cod, $name, $price, $quantity, $min_quantity, $id);   // edita o produto já existente
                      
                         header("Location: ".BASE_URL);                                         // e volta para a página inicial
                     exit;
                     } else {
                         $data['warning'] = 'digite os campos corretamente';                    // se não for possivél editar, será mostrado em tela um aviso de erro
                     }
                 }
                 $data['info'] = $p->getProduct($id);                                           // pega do banco, as informações do produto a ser editado
                 $this->loadTemplate('edit', $data);                                            // carrega o template 'edit' passando os dados na  view
             }
        }

#

## Products.php
### exemplo de model

       <?php
       class Products extends Model{                                                      // seta um classe Products em extensão ao model

           // primeira função, pesquisar produto por nome ou código de barras, usada no template home e controller home
           public function getProducts($s=''){
               $array = array();
       
               if(!empty($s)){                                                            // monta a consulta SQL para buscar produtos com o código ou que contenham o nome semelhante a ao nome do produto
                   $sql = "SELECT * FROM products WHERE cod = :cod OR name LIKE :name";   // seleciona do banco de dados, da tabela products
                   $sql = $this->db->prepare($sql); 
                   $sql->bindValue(":cod", $s);
                   $sql->bindValue(":name", '%'.$s.'%');
                   $sql->execute();
               } else {                                                                    // caso não ache o código procurado ou nada parecdio com o nome, ele seleciona o inteiro
                   $sql = "SELECT * FROM products";
               $sql = $this->db->query($sql);
               }
       
               if($sql->rowCount() > 0){                                                    // este if, verifica se a pesquisa retornou alguma coisa
                   $array = $sql->fetchAll();                                               // caso obtenha um retorno, os resultados são armazenados em uma array
               }
       
               return $array;                                                               // retorna a array de produtos
           }

           // função privada que será usada em outra função (addProducts)
       
           private function verifyProducts($cod){     // verificar a existência de um produto pelo código
       
               return true;                           // essa função sempre retorna true, indicando que o produto existe
           }

           // terceira função, usada para adicionar os produtos, usada no template add e controller home
       
           public function addProduct($cod, $name, $price, $quantity, $min_quantity){            // para adicionar produto
               if($this->verifyProducts($cod)){                                                  // verifica se o produto existe
                   $sql = "INSERT INTO products (cod, name, price, quantity, min_quantity) VALUES (:cod, :name, :price, :quantity,  :min_quantity)";     // monta a consulta SQL para adicionar o produto
                   $sql = $this->db->prepare($sql);                                              
                   $sql->bindValue(":cod", $cod);
                   $sql->bindValue(":name", $name);
                   $sql->bindValue(":price", $price);
                   $sql->bindValue(":quantity", $quantity);
                   $sql->bindValue(":min_quantity", $min_quantity);
                   $sql->execute();
               } else {
                   return false;                                                                 // se o produto já existe, retorna false indicando falha na operação
               }
           }

           // quarta função, usada para editar os produtos existentes, usada no template edit e controller home
           // semelhante a função addProducts
           public function editProduct($cod, $name, $price, $quantity, $min_quantity, $id){                 // para editar um produto existente
               if($this->verifyProducts($cod)){
                   $sql = "UPDATE products SET cod = :cod, name = :name, price = :price, quantity = :quantity, min_quantity = :min_quantity WHERE id = :id"; // monta a consulta  SQL para editar o produto
                   $sql = $this->db->prepare($sql);
                   $sql->bindValue(":cod", $cod);
                   $sql->bindValue(":name", $name);
                   $sql->bindValue(":price", $price);
                   $sql->bindValue(":quantity", $quantity);
                   $sql->bindValue(":min_quantity", $min_quantity);
                   $sql->bindValue(":id", $id);
                   $sql->execute();
       
               } else {
                   return false;                               // se o produto não existe, retorna false indicando falha na operação
               }
           }

           quinta função, para criar o acesso a edição dos produtos especificos, usada no template home e no controller home
       
           public function getProduct($id){                          // para obter as infroamções de um produto somente pelo ID dele
               $array = array();
       
               $sql = "SELECT * FROM products WHERE id = :id";        // monta a consulta SQL para pegar o produto
               $sql = $this->db->prepare($sql);
               $sql->bindValue(":id", $id);
               $sql->execute();
       
               if($sql->rowCount() > 0){                              // verifica se a consulta obteve resultado
                   $array = $sql->fetch();                            // se sim, armazena os resultados em um array
       
               }
               return $array;                                         // retorna o array com informações sobre o produto
           }

           // sexta função, ver se os produtos da tab quantidade é menor que os da tab quantidade_min
       
           public function getLowQuantityProducts(){                  // para onter produtos com quantidada baixa 
               $array = array();
       
               $sql = "SELECT * FROM products WHERE quantity < min_quantity";       // cria a consulta no SQL para buscar produtos com quantidade inferior a quantidade minima
               $sql = $this->db->query($sql);
       
               if($sql->rowCount() > 0){                              // verificar se a consulta teve resultado
                   $array = $sql->fetchAll();                         // caso haja retorno, armazena os resultados em um array
        
               }
       
               return $array;                                         // retorna o array com as informações desejadas
           }
       
       }

#

## template.php
### exemplo de view/template

        <!DOCTYPE html>
        <html>
        	<head>
        		<meta charset="utf-8" />
        		<title>Sistema de Estoque</title>
        		<meta name="viewport" content="width=device-width, initial-scale=1" />                                         // base html
        		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/css/style.css">
        		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery.min.js"></script>
        	</head>
        	<body>
        		<?php if(isset($viewData['menu'])): ?>                      // exemplo de uso do menu criado no home controller... puxado dessa maneira
        			<div class="header">
        				<nav>
        					<ul>
        					<?php foreach($viewData['menu'] as $url => $menutitle): ?>
        						<li><a href="<?php echo $url; ?>"><?php echo $menutitle; ?></a></li>
        					<?php endforeach; ?>
        					<ul>
        				</nav>
        			</div>
        		<?php endif; ?>
        		<div class="container">
        			<?php
        				$this->loadViewInTemplate($viewName, $viewData);          // carregar a view determinada no template
        			?>
        		</div>
        
        		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery.mask.js"></script>                          // puxar os scripts de masks
        		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
        
        	</body>
       </html>
