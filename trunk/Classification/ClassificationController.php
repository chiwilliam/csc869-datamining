<?php

    //Load Sphinx API
    require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/sphinx/API/sphinxapi.php");

    //get Relationship string
    $relationshipText = trim($_POST["relationshipText"]);

    //Instantiate Sphinx object and set search parameters
    //Don't worry about this part now. I will explain later
    $data = new SphinxClient();
    $data->SetServer("localhost", 3315);
    $data->SetMatchMode(SPH_MATCH_ANY);
    $data->SetSortMode(SPH_SORT_RELEVANCE);
    $data->SetLimits(0, 1000, 1000 );

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

            //eliminate occurrences with lower weight (weaker relationships)
            $keys = array_keys($result["matches"]);
            $weight = 1;
            for($i=0;$i<count($keys);$i++){
                if($result["matches"][$keys[$i]]["weight"] >= $weight){
                    $weight = $result["matches"][$keys[$i]]["weight"];
                }
                else{
                    unset($result["matches"][$keys[$i]]);
                }
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
        }
    }

    include ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Classification/Classification.php");

?>
