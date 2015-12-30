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
        <td id="tdForms">
            <div style="display: inline-block;">
            <div id="divLoginbypersonal" class="divLogin">
                <div class="divTitle">Captura de Scrap</div>
                <label class="lblLogin" for="txtPersonal">Número de Personal</label>
                <input class="txtLogin" type="text" value="" id="txtPersonal" />
                <br /><br />
                <input class=" buttons button_orange " id="btnLoginpersonal" type="button" value="ingresar" onclick="goToCapture();" />
                <div id="divSeparator" style="width: 300px;"></div>
                <div style="text-align: right;">
                    <a onclick="loginSwitch('bycredentials');" class="link">&#9632; acceso a sistema</a>
                </div>
            </div>
            <div id="divLoginbycredentials" class="divLogin" style=" display: none">
                <div class="divTitle"">Ingreso a Sistema</div>
                <label class="lblLogin" for="txtUsr">Usuario</label>
                <input class="txtLogin" type="text" id="txtUsr" value="" />
                <br /><br />
                <label class="lblLogin" for="txtPwd">Contraseña</label>
                <input class="txtLogin" type="password" id="txtPwd" value="" />
                <br /><br />
                <input class=" buttons button_orange " id="btnLogincredentials" type="button" value="ingresar" />
                <div id="divSeparator" style="width: 300px;"></div>
                <div style="text-align: right;">
                    <a onclick="loginSwitch('bypersonal');" class="link">&#9632; captura de scrap</a>
                </div>
            </div>
            <div id="divLoginerror" class="divLoginError"></div>
            </div>
        </td>
    </tr>
    <tr id="trFooter">
        <td colspan="2" id="tdFooter">
            Continental Automotive Guadalajara México, S.A. de C.V.
        </td>
    </tr>
</table>
<script src="js/jquery-1.11.3.min.js"></script>
<script>

    function loginSwitch($strScreen){
        //$arrDivs = ['bypersonal', 'bycredentials', 'error'];
        $arrDivs = ['bypersonal', 'bycredentials'];
        for($intIx=0;$intIx<$arrDivs.length;$intIx++){
            $('#divLogin' + $arrDivs[$intIx]).hide();
        }
        $('#txtPersonal').val('');
        $('#txtUsr').val('');
        $('#txtPwd').val('');
        $('#divLogin' + $strScreen).slideDown('slow');
    }

    function goToCapture(){
        $('#divLoginerror').hide();
        $('#divLoginerror').html('');
        if($('#txtPersonal').val().trim()!=''){
            $strQueryString = "strUser=" + $('#txtPersonal').val() + "&intProc=0";
            console.log('getuser.php?' + $strQueryString);
            $.ajax({
                data : $strQueryString,
                type : "POST",
                dataType : "json",
                url : "getuser.php",
                success : function($jsnData){
                    if($jsnData.blnGo=='false'){
                        $('#divLoginerror').html("&#8854; " + $jsnData.strError);
                        $('#divLoginerror').show();
                    }else{
                        window.location = 'scrapcapture.php';
                    }
                }
            });
        }else{
            $('#divLoginerror').html('&#8854; Ingresa tu número de personal');
            $('#divLoginerror').show();
        }
    }


</script>
</body>
</html>