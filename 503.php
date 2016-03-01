<?php
ini_set("display_errors",1);

ini_set('session.cookie_httponly',1);
session_regenerate_id();
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>:: CONTINENTAL :: SCRAP ::</title>
    <link rel="stylesheet" type="text/css" href="css/scrap.css">
</head>
<body class="body_index">
<table>
    <tr id="trContent">
        <td id="tdLogo">
            <img src="images/continental.png" class="logo" />
            <br /><br />
            SCRAP
        </td>
        <td style="width: 50%; vertical-align: middle">
            <div style="width: 70%; margin: 0 auto 0 auto; text-align: center; background-color: #EAEAEA; border-radius: 5px; border: 0; box-shadow: 0 1px 0 #000000; padding: 35px 35px 35px 35px; display: block;">
                <div style="background-color: #FF2828; box-shadow: 0 1px 0 #EB1414; color:#FFFFFF; text-shadow: 0 1px 0 #EB1414; padding: 8px 0 8px 0; border-radius: 5px; font-size: 13pt; font-weight: normal; margin-bottom: 20px; text-align: center; font-size: 16pt; font-weight: bold">503 - Error</div>
                <?php
                switch($_REQUEST['intError']){
                    case 101:
                        echo "101 - La base de datos no se encuentra disponible </br ></br ><b>" . $_REQUEST['strError'] . "</b>";
                }
                ?>
            </div>
        </td>
    </tr>
    <tr id="trFooter">
        <td colspan="2" id="tdFooter">
            Continental Automotive Guadalajara México, S.A. de C.V.
        </td>
    </tr>
</table>
</body>
</html>