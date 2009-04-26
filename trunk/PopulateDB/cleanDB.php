<?php

    /*
     * Author: William Murad
     * Function responsible for cleaning missing values
     * In this case, rows with missing values represent only 5% of the total data
     * Therefore they can be deleted
     */

    $query = "DELETE FROM TUPLE WHERE (A01 = '?' OR CAST(A02 AS CHAR) = '?' OR CAST(A03 AS CHAR) = '?' OR A04 = '?' OR A05 = '?'".
    " OR A06 = '?' OR A07 = '?' OR CAST(A08 AS CHAR) = '?' OR A09 = '?' OR A10 = '?' OR CAST(A11 AS CHAR) = '?' OR A12 = '?'".
    " OR A13 = '?' OR CAST(A14 AS CHAR) = '?' OR CAST(A15 AS CHAR) = '?' OR A16 = '?')";

    include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/openDB.php";

    mysql_query($query) or die('Error Deleting data');

    include $_SERVER['DOCUMENT_ROOT']."/CSC869Project/DB/closeDB.php";

?>
