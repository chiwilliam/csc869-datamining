<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form id="dataform" enctype="multipart/form-data" method="post" action="getData.php">
            <table id="table1" align="left" width="700">
                <tr>
                    <td colspan="4" align="left"><font color="red"><?php echo $message; ?>
                    </font></td>
                </tr>
                <tr>
                    <td align="left" width="170"><b>Select File:</b></td>
                    <td colspan="3">
                        <input type="file" size="70" name="file" title="Select File">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <input type="submit" value="Submit" name="submit" title="Submit">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
