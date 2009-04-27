<?php

    /*
     * Author: William Murad
     */

    //Tuple class
    require_once $_SERVER['DOCUMENT_ROOT']."/CSC869Project/Classes/AbstractData.php";

    function deleteOldRecords(){

        //open connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

        $query = "DELETE FROM ABSTRACTS";
        mysql_query($query) or die('Error Deleting old Abstract records');
        $query = "DELETE FROM ENTITIES";
        mysql_query($query) or die('Error Deleting old Entity records');

        //close connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";

    }

    //function responsible for saving data onto the database
    function saveAbstract($abstractNumber, $abstract){

        $entry = new AbstractData($abstractNumber, $abstract);

        //parse abstract content to identify relationships
        $abstractArray = explode("}", $abstract);
        for($i=0;$i<count($abstractArray);$i++){
            $abstractArray[$i] = substr($abstractArray[$i],strpos($abstractArray[$i],"{\\"));
        }

        $foodsArray = extractFoods($abstractArray);
        $foodsArray = indexData($abstract,$foodsArray);
        $entry->__set("foods", $foodsArray);

        $relationshipsArray = extractRelationships($abstractArray);
        $relationshipsArray = indexData($abstract,$relationshipsArray);
        $entry->__set("relationships", $relationshipsArray);

        $cancersArray = extractCancers($abstractArray);
        $cancersArray = indexData($abstract,$cancersArray);
        $entry->__set("cancers", $cancersArray);

        saveToDB($entry);

    }

    function saveToDB($entry){

        //open connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

        $query = "INSERT INTO ABSTRACTS (number, content) VALUES (
                  '".$entry->__get("abstractNumber")."','".$entry->__get("content")."')";

        //execute connection with DB
        mysql_query($query) or die('Error Inserting abstract');

        $query = "SELECT ABSTRACTID FROM ABSTRACTS WHERE NUMBER = ".$entry->__get("abstractNumber");

        //execute connection with DB
        $result = mysql_query($query) or die('Error Selecting AbstractID');

        while($row = mysql_fetch_array($result))
        {
            $abstractID = $row['ABSTRACTID'];
        }

        $foods = $entry->__get("foods");
        for($i=0;$i<count($foods);$i++){
            $query = "INSERT INTO ENTITIES (abstractid, type, phrase, position, weight)
                      VALUES ('".$abstractID."','foods','".$foods[$i][0]."','".$foods[$i][1]."','0')";
            mysql_query($query) or die('Error Inserting Food Entity');
        }

        $relationships = $entry->__get("relationships");
        for($i=0;$i<count($relationships);$i++){

            $text = substr($relationships[$i][0],strpos($relationships[$i][0]," ")+1);
            $weight = substr($relationships[$i][0],0,strpos($relationships[$i][0]," "));
            $query = "INSERT INTO ENTITIES (abstractid, type, phrase, position, weight)
                      VALUES ('".$abstractID."','relationships','".$text."','".$relationships[$i][1]."','".$weight."')";
            mysql_query($query) or die('Error Inserting Relationship Entity');
        }

        $cancers = $entry->__get("cancers");
        for($i=0;$i<count($cancers);$i++){
                $query = "INSERT INTO ENTITIES (abstractid, type, phrase, position, weight)
                      VALUES ('".$abstractID."','cancers','".$cancers[$i][0]."','".$cancers[$i][1]."','0')";
            mysql_query($query) or die('Error Inserting Cancer Entity');
        }

        //close connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";
    }

    function extractFoods($abstract){

        $foods = array();
        $counter = 0;

        for($i=0;$i<count($abstract);$i++){
            if(strpos($abstract[$i],"{\\food") === false){
                //unset($abstract[$i]);
            }
            else{
                $foods[$counter] = array(substr($abstract[$i],7),0);
                $counter++;
            }
        }

        return $foods;
    }

    function extractRelationships($abstract){

        $relationships = array();
        $counter = 0;

        for($i=0;$i<count($abstract);$i++){
            if(strpos($abstract[$i],"{\\relationship") === false){
                //unset($abstract[$i]);
            }
            else{
                $relationships[$counter] = array(substr($abstract[$i],15),0);
                $counter++;
            }
        }

        return $relationships;
    }

    function extractCancers($abstract){

        $cancers = array();
        $counter = 0;

        for($i=0;$i<count($abstract);$i++){
            if(strpos($abstract[$i],"{\\disease") === false){
                //unset($abstract[$i]);
            }
            else{
                $cancers[$counter] = array(substr($abstract[$i],10),0);
                $counter++;
            }
        }

        return $cancers;
    }

    function indexData($abstract,$array){

        for($i=0;$i<count($array);$i++){
            $array[$i][1] = strpos($abstract,$array[$i][0]);
        }
        return $array;
    }

?>
