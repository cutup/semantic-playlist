<?php

/*
// Classe per interrogare il repository spasql
https://github.com/semsol/arc2/wiki
/* ARC2 static class inclusion */


include_once('semsol/ARC2.php');  

class sparqlQuery
{
  static public function fetch($endpoint,$query)
  {
		$dbpconfig = array("remote_store_endpoint" => $endpoint);
		$store = ARC2::getRemoteStore($dbpconfig); 		
		if ($errs = $store->getErrors())
		{
			printf("Query failed: %s\n", $errs );
			exit; 
		}	
		/* execute the query */
		$rows = $store->query($query, 'rows');
		return 	$rows;		
  }
}

?>