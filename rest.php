<?php

//var_dump($_GET["type"]);
	include_once('QueryClass.php');

if (isset($_GET["type"]))
	$type = $_GET["type"];
else
$type = "VIDEO";
	
	
	

	
 //$type = "SOUND";
 
 $endpoint = "http://sparql.europeana.eu/";
 $query = "
      PREFIX edm: <http://www.europeana.eu/schemas/edm/>
						PREFIX ore: <http://www.openarchives.org/ore/terms/>
						PREFIX dc: <http://purl.org/dc/elements/1.1/>
						
						
						SELECT *  WHERE {
								?resource edm:type '$type' ;
																		ore:proxyIn ?proxy ;
																		dc:title ?title ;
																		dc:creator ?creator ;
																		dc:source ?source . 
								?proxy edm:isShownBy ?mediaURL .
								
							}
						OFFSET 10
						LIMIT 100
  ";
  
 return $rows = sparqlQuery::fetch($endpoint,$query);

"
	
?>