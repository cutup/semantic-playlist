<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./src/queryClass.php');

require_once "./src/PHPTAL/PHPTAL.php";

// definisco un nuovo tamplate
$response = new PHPTAL("./templates/page.html");  

// any field set on PHPTAL object is visible to templates as variable
$response->var = "World";


$array = array(
   'value1',
    'value2',
  'value3',
);

$response->a = $array; 


if (isset($_GET["type"]))
    echo $type = $_GET["type"];
else
    $type = "VIDEO";

$ofset = rand(0, 1000);

if (isset($_GET["limit"]))
    echo $limit = $_GET["limit"];
else
    $limit = 5;


$endpoint = "http://sparql.europeana.eu/";
$query = <<<EOD
   PREFIX edm: <http://www.europeana.eu/schemas/edm/>
    PREFIX ore: <http://www.openarchives.org/ore/terms/>
	PREFIX edm: <http://www.europeana.eu/schemas/edm/>
    PREFIX dc: <http://purl.org/dc/elements/1.1/>
    SELECT *  WHERE {
        ?resource edm:type '$type' ;
		edm:WebResource ?webresource;
        ore:proxyIn ?proxy ;
        dc:title ?title ;
        dc:creator ?creator ;
        dc:source ?source ;
        dc:date ?date.
        ?proxy edm:isShownBy ?mediaURL .
        FILTER (?date > '2000' && ?date < '2016')                      
    }
    OFFSET $ofset
    LIMIT $limit
EOD;


$query1 = <<<EOD
	PREFIX dc: <http://purl.org/dc/elements/1.1/>
	PREFIX edm: <http://www.europeana.eu/schemas/edm/>
	PREFIX ore: <http://www.openarchives.org/ore/terms/>
	SELECT ?title ?creator ?mediaURL ?date
	WHERE
	{
		?CHO edm:type '$type' ;
		ore:proxyIn ?proxy;
		dc:title ?title ;
		dc:creator ?creator ;
		dc:date ?date .
		?proxy edm:isShownBy ?mediaURL .
		FILTER (?date > '2000' && ?date < '2016')
	}
	LIMIT 100
 "
EOD;

$rows = sparqlQuery::fetch($endpoint, $query);


//var_dump($rows );

/* display the results in an HTML table */
$player ='';
	foreach ($rows as $row) {
		foreach ($row as $key => $val) {
			if ($val != "" && isset($val))
			 $key . "</td><td>" . $val . "</td></tr>";
			if ($key = 'mediaURL')
			switch ($type) {
				case "VIDEO":
					$player = " <video width='320' height='240' controls>
					<source src='$val' type='video/mp4'>
					Your browser does not support the video tag.
					</video>";
					break;
				case "SOUND":
					$player = " <video width='320' height='240' controls>
					<source src='$val' type='video/mp4'>
					Your browser does not support the video tag.
					</video>";
				break;
				case "IMAGE":
					$player = " <img src=''$val'>";
				break;
			}
		}
	}

$response->player = $player ;
$response->items = $rows ; 

// execute() returns result of template
echo $response->execute();
?>

