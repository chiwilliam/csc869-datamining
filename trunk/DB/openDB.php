<?php

    /*
     * Author: William Murad
     * Function responsible for opening connection with DB
     */

           $dbhost = 'localhost';
           $dbuser = 'root';
           $dbpass = '';

           $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');

           $dbname = 'csc869project';
           mysql_select_db($dbname) or die('Error selecting DB');
?>
