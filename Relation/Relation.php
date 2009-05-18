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
        <form action="classify_text.php" method="post">
        <table id="relationshiptable" align="center" width="800">
            <tr>
                <td align="left" width="150">
                    Abstract:
                </td>
            </tr>
            <tr>
                <td align="left" width="600">
                    <textarea rows="20" cols="200" id="relationshipText" name="abstractText">
                    <?echo $relationshipText;?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td align="left" width="50">
                    <input type="submit" name="submit" value="CLASSIFY"/>
                </td>
            </tr>
        </table>
        <br><br>
        <table id="message" align="center" width="800">
            <tr>
                <td align="center">
                    <font color="red">
                        <?php echo $message;?>
                    </font>
                </td>
            </tr>
        </table>
        </form>
    </body>
</html>
