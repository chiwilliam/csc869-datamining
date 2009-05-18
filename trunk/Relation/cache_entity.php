<?php

 require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/utils.php");

 function obtainEntity($type){

	 //open connection with DB
	include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

	if($type == 'cancers'){
		// $query = "SELECT PHRASE FROM ENTITIES WHERE TYPE = 'cancers' AND ENTITYID = '4640'";
		$query = "SELECT PHRASE FROM ENTITIES WHERE TYPE = 'cancers' ";


	}else{
		$query = "SELECT PHRASE FROM ENTITIES WHERE TYPE = 'foods'";
		//$query = "SELECT PHRASE FROM ENTITIES WHERE TYPE = 'foods' AND ENTITYID = '4642'";

	}


	//execute select query
	$result = mysql_query($query) or die('Error Selecting Cancers');
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	$enities = array();

	while($row = mysql_fetch_array($result))
	{
		$enities[] = $row['PHRASE'];
	}

	//close connection with DB
	include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";

	applyStemmer(&$enities);


	foreach( $enities as $key => $value){
		$vec = preg_split ("/\s+/", $value );
		array_pop($vec);

		applyStemmer(&$vec);

		$enities[$key]=$vec;
	}

	return $enities;

 }

?>
