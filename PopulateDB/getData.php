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

            $abstracts = array();

            //read text file and create array with information
            $abstracts = file($file["tmp_name"], FILE_IGNORE_NEW_LINES | FILE_TEXT | FILE_SKIP_EMPTY_LINES);
            $abstractNumber = 0;

            deleteOldRecords();

            for ($i=0; $i<count($abstracts);$i++){

                $abstract = "";
                if($abstracts[$i] == "ABSTRACT:\r"){
                    $i++;
                    while($abstracts[$i] != "TITLE:\r" && $i < count($abstracts)){
                        $abstract .= $abstracts[$i];
                        $i++;
                    }
                    $abstract = str_replace("'", "", $abstract);
                    //save each entry on database
                    saveAbstract($abstractNumber,$abstract);
                    $abstractNumber++;
                }
            }

            $message = $message."Records imported.<br>Process finished at ".date("H:i:s");

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
