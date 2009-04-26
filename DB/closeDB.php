<?php

     /*
     * Author: William Murad
     * Function responsible for closing connection with DB
     */

    mysql_close($conn) or die('Error closing DB');

?>
