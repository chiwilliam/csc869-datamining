<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <table id="message" align="center" width="900">
            <tr>
                <td align="center" colspan="3">
                    <h3>Please choose feature clicking in one of the links available</h3>
                </td>
            </tr>
            <tr>
                <td align="center"><font color="red"><?php echo $message; ?>
                </font></td>
            </tr>
            <tr>
                <td align="center" width="300">
                    <a target="_blank" href=<?php $_SERVER['DOCUMENT_ROOT']?>"/CSC869Project/Classification/Classification.php">CLASSIFY RELATIONSHIP</a>
                </td>
            </tr>
            <tr>
                <td align="center" width="300">
                    <br>
                </td>
            </tr>
            <tr>
                <td align="center" width="300">
                    <a target="_blank" href=<?php $_SERVER['DOCUMENT_ROOT']?>"/CSC869Project/populateDB/populateDB.php">IMPORT DATA FROM FILE</a>
                </td>
            </tr>
        </table>
    </body>
</html>
