<html>
  <body>
 
  <?php
  /* ARC2 static class inclusion */ 
  include_once('./scripts/semsol/ARC2.php');   
 $dbpconfig = array(
  "remote_store_endpoint" => "http://dbpedia.org/sparql",
   );
 
  $store = ARC2::getRemoteStore($dbpconfig); 
 
  if ($errs = $store->getErrors()) {
     echo "<h1>getRemoteSotre error<h1>" ;
  }
 
  $query = "
      PREFIX rdf:      <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
      PREFIX rdfs:     <http://www.w3.org/2000/01/rdf-schema#>
      select distinct ?l ?c 
         where {?c a rdfs:Class. ?c rdfs:label ?l}
      limit 100
  ";
  
    $query1 = '
     PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT * WHERE {
  ?sub ?pred ?obj .
} 
LIMIT 10
  ';
  

 
  $rows = $store->query($query1, 'rows'); /* execute the query */
 
  if ($errs = $store->getErrors()) {
     echo "Query errors" ;
     print_r($errs);
  }
 
  /* display the results in an HTML table */
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
 
  ?>
  </body>
</html>
