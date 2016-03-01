<?php
session_regenerate_id();
require_once('include/config.php');
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
                    <label class="lblLogin" for="strPersonalNumber">Número de Personal</label>
                    <input class="txtLogin" type="text" value="" id="strPersonalNumber" maxlength="8" />
                    <br /><br />
                    <input class=" buttons button_orange " id="btnLoginpersonal" type="button" value="ingresar" onclick="goToCapture();" />
                    <div id="divSeparator" style="width: 300px;"></div>
                    <div style="text-align: right;">
                        <a onclick="loginSwitch('bycredentials');" class="link">&#9632; acceso a sistema</a>
                    </div>
                </div>
                <div id="divLoginbycredentials" class="divLogin" style="display: none">
                    <div class="divTitle">Ingreso a Sistema</div>
                    <label class="lblLogin" for="strWindowsUserId">Usuario</label>
                    <input class="txtLogin" type="text" id="strWindowsUserId" value="" />
                    <br /><br />
                    <label class="lblLogin" for="strWindowsPassword">Contraseña</label>
                    <input class="txtLogin" type="password" id="strWindowsPassword" value="" />
                    <br /><br />
                    <input class=" buttons button_orange " id="btnLogincredentials" type="button" value="ingresar" onclick="goToSystem();" />
                    <div id="divSeparator" style="width: 300px;"></div>
                    <div style="text-align: right;">
                        <a onclick="loginSwitch('bypersonal');" class="link">&#9632; captura de scrap</a>
                    </div>
                </div>
                <div id="divLoginError" class="divLoginError"></div>
                <div id="divLoginWorking" class="divLoginWorking"><img src="images/wait_64.gif" /></div>
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
<script src="js/jquery.numeric.js"></script>
<script>

    $("#strPersonalNumber").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });

    $("#strPersonalNumber").keypress(function(e){
        if(e.which==13){
            goToCapture();
        }
    });

    $("#strWindowsUserId").keypress(function(e){
        if(e.which==13){
            goToSystem()
        }
    });

    $("#strWindowsPassword").keypress(function(e){
        if(e.which==13){
            goToSystem()
        }
    });

    $('document').ready(function(){
        $("#strPersonalNumber").val('');
        $("#strPersonalNumber").focus();
    })

    function loginSwitch($strScreen){
        console.clear();
        $strPreviousScreen = '';
        switch($strScreen){
            case 'bypersonal':
                $strPreviousScreen = 'bycredentials';
                break;
            case 'bycredentials':
                $strPreviousScreen = 'bypersonal';
                break;
        }
        $('#divLoginError').fadeOut('fast',function(){
            $('#divLoginError').html('');
            $('#strPersonalNumber').val('');
            $('#strWindowsUserId').val('');
            $('#strWindowsPassword').val('');
            $('#divLogin' + $strPreviousScreen).fadeOut('fast',function(){
                $('#divLogin' + $strScreen).fadeIn('slow',function(){
                    if($strScreen=='bypersonal'){
                        $('#strPersonalNumber').focus();
                        $('#strPersonalNumber').select();
                    }else{
                        $('#strWindowsUserId').focus();
                        $('#strWindowsUserId').select();
                    }
                });
            });
        });
    }

    function goToCapture(){
        console.clear();
        $('#divLoginError').fadeOut('fast',function(){
            $('#divLoginError').html('');
            $('#divLoginbypersonal').fadeOut('fast',function(){
                $('#divLoginWorking').fadeIn('fast',function(){
                    if($('#strPersonalNumber').val().trim()!=''){
                        $strQueryString = "strProcess=getWSUser&strPersonalNumber=" + encodeURIComponent($('#strPersonalNumber').val().trim());
                        console.log("ajax.php?" + $strQueryString);
                        $.ajax({
                            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
                            success: function ($jsnPhpScriptResponse) {
                                if(!$jsnPhpScriptResponse.blnValidUser){
                                    $('#divLoginError').html("&#8854; " + $jsnPhpScriptResponse.strError);
                                    $('#divLoginWorking').fadeOut('fast',function(){
                                        $('#divLoginError').fadeIn('fast');
                                        $('#divLoginbypersonal').fadeIn('fast',function(){
                                            $('#strPersonalNumber').focus();
                                        });
                                    });
                                }else{
                                    window.location = 'modules/capture/';
                                }
                            }
                        });
                    }else{
                        $('#divLoginError').html('&#8854; Ingresa tu número de personal');
                        $('#divLoginWorking').fadeOut('fast',function(){
                            $('#divLoginError').fadeIn('fast');
                            $('#divLoginbypersonal').fadeIn('fast',function(){
                                $('#strPersonalNumber').focus();
                            });
                        });
                    }
                })
            })
        });
    }

    function goToSystem(){
        console.clear();
        $('#divLoginError').fadeOut('fast',function(){
            $('#divLoginError').html('');
            $('#divLoginbycredentials').fadeOut('fast',function(){
                $('#divLoginWorking').fadeIn('fast',function(){
                    if($('#strWindowsUserId').val().trim()!='' && $('#strWindowsPassword').val().trim()!=''){
                        $strQueryString = "strProcess=getADUser&strWindowsUserId=" + encodeURIComponent($('#strWindowsUserId').val().trim()) + "&strWindowsPassword=" + encodeURIComponent($('#strWindowsPassword').val().trim());
                        console.log("ajax.php?" + $strQueryString);
                        $.ajax({
                            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
                            success: function ($jsnPhpScriptResponse) {
                                if(!$jsnPhpScriptResponse.blnValidUser){
                                    $('#divLoginError').html("&#8854; " + $jsnPhpScriptResponse.strError);
                                    $('#divLoginWorking').fadeOut('fast',function(){
                                        $('#divLoginError').fadeIn('fast');
                                        $('#divLoginbycredentials').fadeIn('fast',function(){
                                            $('#strWindowsUserId').focus();
                                            $('#strWindowsUserId').select();
                                            $('#strWindowsPassword').val('');
                                        });
                                    });
                                }else{
                                    window.location = 'scrap.php';
                                }
                            }
                        });
                    }else{
                        $('#divLoginError').html('&#8854; Ingresa tu UID y Contraseña de Windows');
                        $('#divLoginWorking').fadeOut('fast',function(){
                            $('#divLoginError').fadeIn('fast');
                            $('#divLoginbycredentials').fadeIn('fast',function(){
                                if($('#strWindowsUserId').val().trim()==''){
                                    $('#strWindowsUserId').focus();
                                }else{
                                    $('#strWindowsPassword').focus();
                                }
                            });
                        });
                    }
                })
            })
        });
    }


</script>
</body>
</html>