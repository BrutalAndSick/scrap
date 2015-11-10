<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>:: CONTINENTAL :: SCRAP ::</title>
    <link rel="stylesheet" type="text/css" href="css/scrap.css">
    <script src="js/jquery-1.11.3.min.js"></script>
</head>
<body>
<?php include_once('inc/header.php'); ?>
<div id="divMenubar">
    Bienvenido - Welcome - Willkommen
</div>
<div id="divWorkarea">
    <div id="divLoginbypersonal" class=" divLogin ">
        <div style=" background-color: #F1F1F1; padding: 8px 0px 8px 0px; border-radius: 5px; color:#FFA500; text-shadow: 0px 1px 0px #B95F00; font-size: 13pt; font-weight: normal; margin-bottom: 10px; text-align: center;">Captura de Scrap</div>
        <div style=" color:#282828; text-shadow: 0px 1px 0px #D0D0D0; font-size: 11pt; font-weight: normal; margin-bottom: 10px; text-align: center;">Número de Personal</div>
        <input style=" display: block; margin: 0px auto 0px auto; width: 100px; font-size: 16pt; text-align: center; padding: 8px 0px 4px 0px; margin-bottom: 10px; cursor: pointer;" type="text" value="" id="txtPersonal" />
        <input style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; " id="btnLoginpersonal" type="button" value="ingresar" />
        <div id="divSeparator" style="width: 300px;"></div>
        <div style="text-align: right;">
            <a onclick="loginSwitch('bycredentials');" class="link">&rarr;acceso a sistema</a>
        </div>
    </div>
    <div id="divLoginbycredentials" class=" divLogin " style="display: none">
        <div style=" background-color: #F1F1F1; padding: 8px 0px 8px 0px; border-radius: 5px; color:#FFA500; text-shadow: 0px 1px 0px #B95F00; font-size: 13pt; font-weight: normal; margin-bottom: 10px; text-align: center;">Ingreso a Sistema</div>
        <label style="width: 90px; display: inline-block;" for="txtUsr">Usuario</label>
        <input style=" margin: 0px auto 0px auto; width: 100px; font-size: 11pt; text-align: center; padding: 4px 0px 2px 0px; margin-bottom: 10px; cursor: pointer;" type="text" id="txtUsr" value="" />
        <br />
        <label style="width: 90px; display: inline-block;" for="txtPwd">Contraseña</label>
        <input style=" margin: 0px auto 0px auto; width: 100px; font-size: 11pt; text-align: center; padding: 4px 0px 2px 0px; margin-bottom: 10px; cursor: pointer;" type="password" id="txtPwd" value="" />
        <br />
        <input style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; " id="btnLogincredentials" type="button" value="ingresar" />
        <div id="divSeparator" style="width: 300px;"></div>
        <div style="text-align: right;">
            <a onclick="loginSwitch('bypersonal');" class="link">&rarr;captura de scrap</a>
        </div>
    </div>
</div>
<script>

    $("#txtPersonal").keyup(function(e) {
        console.log("Handler for .keypress() called." + e.which);
    });

    function loginSwitch($strScreen){
        $('#divLoginbypersonal').hide();
        $('#txtPersonal').val('');
        $('#divLoginbycredentials').hide();
        $('#txtUsr').val('');
        $('#txtPwd').val('');

        $('#divLogin' + $strScreen).slideDown('slow');
        return false;
    }

</script>
</body>
</html>