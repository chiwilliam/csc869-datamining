<?php


    //Load Sphinx API
    require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/sphinx/API/sphinxapi.php");

    //get Relationship string
    // $relationshipText = implode(' ', $potentialRelation);

    function classify($relationshipText){

		//Instantiate Sphinx object and set search parameters
		//Don't worry about this part now. I will explain later
		$data = new SphinxClient();
		$data->SetServer("localhost", 3315);
		$data->SetMatchMode(SPH_MATCH_ANY);
		$data->SetSortMode(SPH_SORT_RELEVANCE);
		$data->SetLimits(0, 1000, 1000 );

		//treate prefix searches
		$tmp = array();
		$tmp = explode(" ", $relationshipText);
		for($i=0;$i<count($tmp);$i++){
			if(strlen($tmp[$i]) > 4){
				$tmp[$i] .= '*';
			}
		}
		$input = implode(" ", $tmp);

		//search for all occurences of relationship
		$result = $data->Query($relationshipText);

		if ( $result === false ) {
			$message = "Query failed: " . $data->GetLastError() . "<br/>";
		}
		else {
			if ( $data->GetLastWarning() ) {
				$message = "WARNING: " . $data->GetLastWarning() . "";
			}
			else{
				if(!isset($result["matches"]))
				{
					//search for all occurences of relationship
					$result = $data->Query($input);
				}
				if(isset($result["matches"]))
				{
					$keys = array_keys($result["matches"]);

					//documents retrieved to calculate precision/recall
					$retrieved = 0;

					//eliminate occurrences with lower weight (weaker relationships)
					$weight = 1;
					for($i=0;$i<count($keys);$i++){
						//documents retrieved to calculate precision/recall
						//ignores weight = 1
						if($result["matches"][$keys[$i]]["weight"] > 1){
							$retrieved++;
						}
						if($result["matches"][$keys[$i]]["weight"] >= $weight){
							$weight = $result["matches"][$keys[$i]]["weight"];
						}
						else{
							unset($result["matches"][$keys[$i]]);
						}
					}
					//if all matches had weight = 1, consider all
					if($weight == 1){
						$retrieved = count($keys);
					}

					//mount query string based on IDs retrieved from SphinxSearch
						$relationships = array_keys($result["matches"]);
					foreach( $relationships as $index ){
						$queryIndexes .= " OR ENTITYID = ".$index;
					}
					$queryIndexes = substr($queryIndexes,4);
					$query = "SELECT * FROM ENTITIES WHERE ".$queryIndexes;

					//open connection with DB
					include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

					//execute select query
					$result = mysql_query($query) or die('Error Selecting Relationships');

					//sum all relationship weights
					$counter = 0;
					$weight = 0;
					while($row = mysql_fetch_array($result))
					{
						$counter++;
						$weight += $row['weight'];
					}

					//close connection with DB
					include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";

					//mention number of relationships considered in DB to classify entered relationship
					if($counter > 1){
						$message = "Based on ".$counter." abstracts, ";
					}
					else{
						$message = "Based on 1 abstract, ";
					}
					//if weights' sum is > 0, relationship is positive
					//if weights' sum is < 0, relationship is negative
					//if weights' sum is == 0, relationship is neutral
					switch($weight){
						case ($weight < 0):
							$message .= "the relationship '".$relationshipText."' is negative.";
							break;
						case ($weight > 0):
							$message .= "the relationship '".$relationshipText."' is positive.";
							break;
						default:
							$message .= "the relationship '".$relationshipText."' is neutral.";
							break;
					}

					//calculate precision
					if($retrieved == 0)
						$precision = 0;
					else{
						//if any match with weight > 1
						if($counter != $retrieved && $weight > 1)
							$precision = round(($counter/$retrieved)*100,2);
						//otherwise consider that just one document was relevant
						else
							$precision = round((1/$retrieved)*100,2);
					}
					//calculate recall
					if($counter == 0)
						$recall = 0;
					else
						$recall = round((1/$counter)*100,2);
					//calculate F_score
					if($precision == 0 & $recall == 0)
						$F_score = 0;
					else
						$F_score = round(2*($recall/100)*($precision/100)/
										 (($precision/100)+($recall/100)),2);

					$message .= "<br><br>Precision: ".$precision." %";
					$message .= "<br>Recall: ".$recall." %";
					$message .= "<br>F-score: ".$F_score;
				}
				else
					$message = "No match was found!";
			}
		}

		return $weight;

	}
?>
