<?php

    /*
     * Author: William Murad
     */

    //Tuple class
    require_once $_SERVER['DOCUMENT_ROOT']."/CSC869Project/Classes/Tuple.php";

    //function responsible for saving data onto the database
    function saveTuple($tuple){

        $entry = new Tuple($tuple);

        $query = "INSERT INTO tuple (A01, A02, A03, A04, A05, A06, A07, A08, A09, A10, A11, A12, A13, A14, A15, A16 )
                    VALUES ('".$entry->getA01()."','".$entry->getA02()."','".$entry->getA03()."'".
                    ",'".$entry->getA04()."','".$entry->getA05()."','".$entry->getA06()."'".
                    ",'".$entry->getA07()."','".$entry->getA08()."','".$entry->getA09()."'".
                    ",'".$entry->getA10()."','".$entry->getA11()."','".$entry->getA12()."'".
                    ",'".$entry->getA13()."','".$entry->getA14()."','".$entry->getA15()."'".
                    ",'".$entry->getA16()."')";

        //open connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

        //execute connection with DB
        mysql_query($query) or die('Error Inserting data');

        //close connection with DB
        include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";

    }

?>
