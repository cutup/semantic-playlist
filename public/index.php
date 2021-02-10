<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// classe per query spasql
include_once('../src/queryClass.php');

// Create app
// $app = new \Slim\App;
$app = new \Slim\App(array(
    'debug' => true,
	'settings' => [
	        'displayErrorDetails' => true,
	    ],
    
));

// Get container
$container = $app->getContainer();

//$container['view'] = new \Slim\Views\PhpRenderer("../templates/");


// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', [
		'debug' => true
	]);
	
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};


/// route per   index

$app->any('/', function ($request, $response, $args) {
    // passo parametri al template
    $response = $this->view->render($response, "index.html", [
		
	]);
    
	//var_dump( $rows);
    return $response;
});

/// route per /playlist
$app->any('/playlist', function ($request, $response, $args) {
    // passo parametri al bosy
	
	$html="<a href=playlist/europeana> Europeana</a>";
	$html.="<br/>";
	$html.="<a href=playlist/dbpedia> DbPedia</a>";
	
   $response->getBody()->write(var_export($html, true));
    
	
    return $response;
});

/* Route Query Europeana  */

$app->any('/playlist/europeana', function (Request $request, Response $response, $args) {

  //$request->getMethod();
  
	$post=$request->getParsedBody();

    $limit = 10;
	$ofset = rand(0, 1000);

	$endpoint = "http://sparql.europeana.eu/";	
    //$request->getQueryParams()
    // $id= (int)$args['id'];
    
    //$id = $request->getAttribute('id'); // GET request
    $type = $post['type'];
	$title = $post['title'];

    
    //$response->getBody()->write("id, $id");
    // $response->getBody()->write("value, $value");
	$query = <<<EOD
	PREFIX edm: <http://www.europeana.eu/schemas/edm/>
    PREFIX ore: <http://www.openarchives.org/ore/terms/>
    PREFIX dc: <http://purl.org/dc/elements/1.1/>
    SELECT *  WHERE {
        ?resource edm:type '$type' ;
        ore:proxyIn ?proxy ;
		dc:title ?title ;
		dc:description ?description  ;
        dc:creator ?creator ;
        dc:source ?source ;
        dc:date ?date.
        ?proxy edm:isShownBy ?mediaURL .
		FILTER regex(?title ,'^$title','i').
		
    }
    OFFSET $ofset
    LIMIT $limit
EOD;

	
	// chiamo classe fetch
	$rows = sparqlQuery::fetch($endpoint, $query);
	
	// passo parametri al template
    $response = $this->view->render($response, "page-europeana.html", [
		"type" => $type,
		"search" => $title,
		"results" => $rows,
		"query" => $query,
	]);
    
	//var_dump( $rows);
    return $response;
});




/* Query Dbpedia  da terminate*/

$app->any('/playlist/dbpedia', function (Request $request, Response $response, $args) {

  //$request->getMethod();
  
	$post=$request->getParsedBody();

    $limit = 10;
	$ofset = rand(0, 1000);

	$endpoint = "http://dbpedia.org/sparql";	
    //$request->getQueryParams()
    // $id= (int)$args['id'];
    
    //$id = $request->getAttribute('id'); // GET request
    $director = $post['type'];
	$title = $post['title'];

    
  $query =  <<<EOD
     PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX dbo:  <http://dbpedia.org/ontology/>
PREFIX dbp:  <http://dbpedia.org/property/>

SELECT DISTINCT ?fname ?producer WHERE 
{
    ?film dbp:producer ?producer ;      
              dbp:starring ?producer ;
          dbo:director ?producer ;
          foaf:name    ?fname .
}
EOD;

	
    $queryfilm =  <<<EOD

	PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
	PREFIX dbpprop: <http://dbpedia.org/property/>
	PREFIX dbres: <http://dbpedia.org/resource/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	
	SELECT DISTINCT ?film_title ?film_abstract ?director 
	WHERE {
		?film_title rdf:type <http://dbpedia.org/ontology/Film> .
		?film_title rdfs:comment ?film_abstract.
	}
	LIMIT 10

EOD;

	
	// chiamo classe fetch
	$rows = sparqlQuery::fetch($endpoint,  $queryfilm );
	print_r($query);
	
	// passo parametri al template
    $response = $this->view->render($response, "page-dbpedia.html", [
		"search" => $title,
		"results" => $rows,
		"query" => $queryfilm ,
	]);
    
	//var_dump( $rows);
    return $response;
});



$app->run();