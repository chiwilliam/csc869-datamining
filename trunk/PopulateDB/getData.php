<?php

    /*
     * Author: William Murad
     * Main file responsible for inserting all the data from the crx.data file
     * into a MySQL database
     */

    //function responsible to save data
    require_once $_SERVER['DOCUMENT_ROOT']."/CSC869Project/PopulateDB/saveData.php";

    //message to be displayed
    $message = "";

    //check if file was selected
    if(isset($_FILES['file'])){

        $file = $_FILES['file'];

        if($file['size'] > 0){

            $tuples = array();

            //read text file and create array with information
            $tuples = file($file["tmp_name"], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            for ($i=0; $i<count($tuples);$i++){

                //convert string with all information from a specific entry to an array
                $tuples[$i] = split(',',$tuples[$i]);

                //save each entry on database
                saveTuple($tuples[$i]);
            }

            $message = $message."Imported ".count($tuples)." records.<br>Process finished at ".date("H:i:s");

        }
        else{
            $message = "Please select a valid file. File was not selected or is empty.";
        }
    }
    else{
        $message = "Please select a textfile (.TXT) which contains category names.".
        "The names should be entered one per row.";
    }

    include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/PopulateDB/populateDB.php";

?>
