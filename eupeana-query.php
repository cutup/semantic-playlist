
 
  <?php
  /* ARC2 static class inclusion */ 
  include_once('./scripts/semsol/ARC2.php');   
 $dbpconfig = array(
  "remote_store_endpoint" => "http://sparql.europeana.eu/",
   );
 
  $store = ARC2::getRemoteStore($dbpconfig); 
 
  if ($errs = $store->getErrors()) {
     echo "<h1>getRemoteSotre error<h1>" ;
  }
		
		
		// SELECT ?title ?mediaURL ?creator ?source ?proxy ?description  WHERE
 
  $query = "
      PREFIX edm: <http://www.europeana.eu/schemas/edm/>
						PREFIX ore: <http://www.openarchives.org/ore/terms/>
						PREFIX dc: <http://purl.org/dc/elements/1.1/>
						
						
						SELECT *  WHERE {
								?resource edm:type 'VIDEO' ;
																		ore:proxyIn ?proxy ;
																		dc:title ?title ;
																		dc:creator ?creator ;
																		dc:source ?source . 
								?proxy edm:isShownBy ?mediaURL .
								
							}
						OFFSET 10
						LIMIT 100
  ";
 
		$type = "VIDEO";
	
   $query_audio = "
    PREFIX dc: <http://purl.org/dc/elements/1.1/>
				PREFIX edm: <http://www.europeana.eu/schemas/edm/>
				PREFIX ore: <http://www.openarchives.org/ore/terms/>
				SELECT ?title ?creator ?mediaURL ?date
				WHERE {
					
				?CHO edm:type '$type' ;
								ore:proxyIn ?proxy;
								dc:title ?title ;
								dc:creator ?creator ;
								dc:date ?date .
				?proxy edm:isShownBy ?mediaURL .
				FILTER (?date > '2000' && ?date < '2016')
				}
				LIMIT 100
 ";
	
	
	$query_lingua = '
    PREFIX edm: <http://www.europeana.eu/schemas/edm/>

		SELECT DISTINCT ?Dataset
		WHERE {
				?Aggregation edm:datasetName ?Dataset ;
								edm:country "England"
		}

 ';
	//						FILTER (?title = "Milloin raukka onnen saan.")
  $rows = $store->query($query_audio, 'rows'); /* execute the query */
		
  if ($errs = $store->getErrors()) {
     echo "Query errors" ;
     print_r($errs);
  }
		
		

  /* display the results in an HTML table */
  echo "<table border='1'>" ;
foreach( $rows as $row ) { 
  foreach( $row as $key => $val) {
					if ($val!="" && isset($val))
         print "<tr><td>" .$key. "</td><td>" . $val. "</td></tr>";
									
  }
		print "<tr><td> -</td><td>- </td></tr>";
		}
  echo "</table>"
	
 
  /* display the results in an HTML table 

  foreach( $rows as $row ) { 
				foreach ($row as $key => $val)
				if ($val!="" && isset($val))
      		print_r($key. " : " .$val. '<br>' );
								else
				print_r('<br>');
								
  }
		
		*/
 
 
  ?>

<html>
  <body>
				
<select>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select>
Try it Yourself »

  </body>
</html>
