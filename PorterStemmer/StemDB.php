<?php

        //Load PorterStemmer API
        require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/PorterStemmer/PorterStemmer.php");

        //open connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

        $query = "SELECT ENTITYID, PHRASE FROM STEMENTITIES";
        
        //execute connection with DB
        $result = mysql_query($query) or die('Error Selecting records');

        $stemmer = new PorterStemmer();

        while($row = mysql_fetch_array($result))
        {
            $id = $row['ENTITYID'];
            $phrase = $row['PHRASE'];

            $tmp = array();
            $tmp = explode(" ", $phrase);

            for($i=0;$i<count($tmp);$i++){
                $tmp[$i] = $stemmer->Stem($tmp[$i]);
            }

            $phrase = implode(" ", $tmp);

            $updateQuery = "UPDATE STEMENTITIES SET PHRASE = '".$phrase.
            "' WHERE ENTITYID = ".$id;

            mysql_query($updateQuery) or die('Error Updating record id = '.$id);
        }

        //close connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";
        
?>
