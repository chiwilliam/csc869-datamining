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
    //William's files
    if(isset($_FILES['fileWilliam'])){

        $file = $_FILES['fileWilliam'];

        //if file contains data
        if($file['size'] > 0){

            $abstracts = array();

            //read text file and create array with information
            $abstracts = file($file["tmp_name"], FILE_IGNORE_NEW_LINES | FILE_TEXT | FILE_SKIP_EMPTY_LINES);
            $abstractNumber = 0;

            //delete old database records
            deleteOldRecords();

            //for each abstract
            for ($i=0; $i<count($abstracts);$i++){

                $abstract = "";
                //build abstract from file
                if($abstracts[$i] == "ABSTRACT:\r"){
                    $i++;
                    while($abstracts[$i] != "TITLE:\r" && $i < count($abstracts)){
                        $abstract .= $abstracts[$i];
                        $i++;
                    }
                    //handle apostrophe exception
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
        $message = "";
    }

    //check if file was selected
    //Kleber's files
    if(isset($_FILES['fileKleber'])){

        //read ZIP file
        $zipFile = $_FILES["fileKleber"];
        $zip = zip_open($zipFile["tmp_name"]);
        $resourceID = 0;
        if($zip){
            while($zip_entry = zip_read($zip)){
                if(zip_entry_open($zip, $zip_entry)){
                    $data = zip_entry_read($zip_entry);

                    //build abstract from file data
                    $data = substr($data,strpos($data,"ABSTRACT:")+10);
                    $data = str_replace("'", "", $data);
                    //handle exception
                    $data .= " ";

                    //save each entry on database
                    if(strpos($data,"relationship") === false){
                        $resourceID++;
                    }
                    else{
                        saveAbstract($resourceID, $data);
                        $resourceID++;
                    }
                }
            }
        }
        else{
            $message = "Please select a valid file. File was not selected or is empty.";
        }
    }
    else{
        $message = "";
    }

    include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/PopulateDB/populateDB.php";

?>
