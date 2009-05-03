<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php

            require_once ($_SERVER['DOCUMENT_ROOT']."/zoomsumer/sphinx/api/sphinxapi.php");

            $data = new SphinxClient();
            $data->SetServer("localhost", 3312);
            $data->SetMatchMode(SPH_MATCH_ALL);

            $result = $data->Query('greenbrae');

            if ( $result === false ) {
                echo "Query failed: " . $data->GetLastError() . "<br/>";
            }
            else {
                if ( $data->GetLastWarning() ) {
                    echo "WARNING: " . $data->GetLastWarning() . "";
                }

                if ( ! empty($result["matches"]) ) {
                    foreach ( $result["matches"] as $index ) {
                          echo "$doc <br/>";
                    }
                }
            }
  
        ?>
    </body>
</html>
