<?php
ini_set('display_errors',1);
session_start();
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$intUser = $_SESSION['intUser'];
$strUser = $_SESSION['strUser'];
$strUName = $_SESSION['strUName'];
$intPlanta = $_SESSION['intPlanta'];
$strPlanta = $_SESSION['strPlanta'];
$intDivision = $_SESSION['intDivision'];
$strDivision = $_SESSION['strDivision'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>:: CONTINENTAL :: SCRAP ::</title>
    <link rel="stylesheet" type="text/css" href="css/scrap.css">
    <script src="js/jquery-1.11.3.min.js"></script>
    <style>
        label {
            display: inline-block;
            width:120px;
        }
        select {
            width: 350px;
        }

        .tdbold {
            color: #000000;
            font-size: 8pt;
            font-weight: bold;
            height: 4mm;
            vertical-align: top;
        }

        .tdnormal {
            color: #000000;
            font-size: 8pt;
            font-weight: normal;
            height: 4mm;
            vertical-align: top;
        }

        .divclass {
            background-color: #F1F1F1;
            padding: 5px 5px 5px 5px;
            margin-bottom: 7px;
            text-align: left;
            border-radius: 5px;
        }

        .divother {
            background-color: #FFFFFF;
            text-align: left;
            border-radius: 10px;
            border: 1px #EAEAEA solid;
            box-shadow: 0px 1px 0px #FFFFFF;
            padding: 5px 5px 0px 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
<?php include_once('inc/header.php'); ?>
<?php include_once('inc/menu.php'); ?>

    <table width="100%" style="border-collapse: collapse; border-spacing: 0px; margin-top: 10px">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top; ">
                <div class="divother">
                    <div class="divclass">
                        <label for="selProyecto">Proyecto</label>
                        <select id="selProyecto" onchange="getAPD();">
                            <option value="-1" selected>- Seleccione -</option>
                            <?php
                            $strSql = "SELECT proyectos.id AS 'intProyecto', proyectos.nombre AS 'strProyecto', segmentos.id AS 'intSegmento', segmentos.nombre AS 'strSegmento', profit_center.id AS 'intProfitCenter', profit_center.nombre AS 'strProfitCenter' ";
                            $strSql .= "FROM segmentos, profit_center, proyectos ";
                            $strSql .= "WHERE proyectos.activo = 1 ";
                            $strSql .= "AND proyectos.id_planta = " . $intPlanta . " ";
                            $strSql .= "AND proyectos.id_division = " . $intDivision . " ";
                            $strSql .= "AND proyectos.id_segmento = segmentos.id ";
                            $strSql .= "AND segmentos.activo = 1 ";
                            $strSql .= "AND proyectos.id_pc = profit_center.id ";
                            $strSql .= "AND profit_center.activo = 1 ";
                            $strSql .= "ORDER BY proyectos.nombre; ";
                            $rstData = mysqli_query($objCon, $strSql);
                            while($objData = mysqli_fetch_assoc($rstData)){
                                ?>
                                <option value="<?php echo $objData['intProyecto'] . "|" . $objData['strProyecto'] . "|" . $objData['intSegmento'] . "|" . $objData['strSegmento'] . "|" . $objData['intProfitCenter'] . "|" . $objData['strProfitCenter']; ?>"><?php echo $objData['strProyecto']; ?></option>
                                <?php
                            };
                            unset($objData);
                            mysqli_free_result($rstData);
                            unset($rstData);
                            ?>
                        </select>
                    </div>
                    <div class="divclass" style="display: none" id="divAPD">
                        <label for="selAPD">APD</label>
                        <select id="selAPD" onchange="getArea();" ></select>
                    </div>
                    <div class="divclass" style="display: none" id="divArea">
                        <label for="selArea">Area</label>
                        <select id="selArea" onchange="getTecnologia();"></select>
                    </div>
                    <div class="divclass" style="display: none;" id="divTecnologia">
                        <label for="selTecnologia">Tecnología</label>
                        <select id="selTecnologia" onchange="getLinea();" ></select>
                    </div>
                    <div class="divclass" style="display: none;" id="divLinea">
                        <label for="selLinea">Linea</label>
                        <select id="selLinea" onchange=getDefecto();></select>
                    </div>
                    <div class="divclass" style="display: none;" id="divDefecto">
                        <label for="selDefecto">Defecto</label>
                        <select id="selDefecto" onchange=getCausa();></select>
                    </div>
                    <div class="divclass" style="display: none;" id="divCausa">
                        <label for="lblCausa">Relacionado a</label>
                        <select id="selCausa" onchange="getCodigoScrap();" ></select>
                    </div>
                    <div class="divclass" style="display: none;" id="divCodigoScrap">
                        <label for="selCodigoScrap">Código Scrap</label>
                        <select id="selCodigoScrap" onchange="addParts();"></select>
                    </div>
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top; ">
                <div style="text-align: left;display: inline-block; width: 139mm; height: 76mm; padding: 2mm 2mm 2mm 2mm; background-color:#ff4646; box-shadow: 2px 2px 0px #000000">
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td rowspan="2" style="width: 50%">
                                <img src="img/continental_black.png" style="height: 8mm" />
                            </td>
                            <td style="height: 4mm; font-size: 9pt; color:#000000; width: 50%; font-weight: bold; text-align: right;">
                                Scrap
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 4mm; font-size: 9pt; color:#000000; text-align: right">
                                <b>Folio</b><span style="margin-left: 5mm; min-width: 20mm ">0663546</span>
                            </td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse;">
                        <tr style="height: 5mm;">
                            <td style="width: 10%; font-size: 9pt; color:#000000; font-weight: bold; ">
                                Costo
                            </td>
                            <td style="text-align: right; width: 18%; font-size: 9pt; color:#000000; font-weight: normal;">
                                0.00
                            </td>
                            <td style="width: 14%; font-size: 9pt; color:#000000; font-weight: bold; ">
                                MXN
                            </td>
                            <td style="width: 16%; font-size: 9pt; color:#000000; font-weight: bold;">
                                Fecha&nbsp;de&nbsp;captura
                            </td>
                            <td style="width: 14%; font-size: 9pt; color:#000000; font-weight: normal; ">
                                <?php echo date("d/m/y") ?>
                            </td>
                            <td style="text-align: right; width: 14%; font-size: 9pt; color:#000000; font-weight: bold;">
                                Hora
                            </td>
                            <td style="text-align: right; width: 14%; font-size: 9pt; color:#000000; font-weight: normal; ">
                                <?php echo date("h:i") ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="background-color: #000000; height: 1mm;"></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold" style="width: 15mm;">Operador</td>
                            <td class="tdnormal" width="*"><?php echo $strUser . ' ' . $strUName; ?></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold" style="width: 17mm;">Planta</td>
                            <td class="tdnormal" style="width: 18mm;"><?php echo $strPlanta; ?></td>
                            <td class="tdbold" style="width: 17mm;">División</td>
                            <td class="tdnormal" style="width: 18mm;"><?php echo $strDivision; ?></td>
                            <td class="tdbold" style="width: 17mm;">Segmento</td>
                            <td class="tdnormal" style="width: 17mm;" id="tdSegmento">&nbsp;</td>
                            <td class="tdbold" style="width: 17mm;">C. Costos</td>
                            <td class="tdnormal" style="width: 28mm;" id="tdProfitCenter">&nbsp;</td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold" style="width: 8mm;">Área</td>
                            <td class="tdnormal" style="width: 38mm;" id="tdArea"></td>
                            <td class="tdbold" style="width: 17mm;">Tecnología</td>
                            <td class="tdnormal" style="width: 76mm;" id="tdTecnologia"></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold" style="width: 12mm">Línea</td>
                            <td class="tdnormal" style="width: 127mm;" id="tdLinea"></td>
                        </tr>
                        <tr>
                            <td class="tdbold">Defecto</td>
                            <td class="tdnormal" id="tdDefecto"></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold" style="width: 21mm;">Relacionado a</td>
                            <td class="tdnormal" style="width: 25mm;" id="tdCausa"></td>
                            <td class="tdbold" style="width: 25mm;">Código de Scrap</td>
                            <td class="tdnormal" style="width: 72mm" id="tdCodigoScrap"></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td class="tdbold">Parte (Cant.)</td>
                            <td class="tdnormal" id="tdNumeroParte">A2C00000211 (25) / A2C00000211 (32) / A2C00000211 / A2C00000211 / A2C00000211 / A2C00000211</td>
                        </tr>
                        <tr>
                            <td class="tdbold">Ubicación</td>
                            <td class="tdnormal" id="tdUbicacion"></td>
                        </tr>
                        <tr><td colspan="2" style="background-color: #000000; height: 1mm;"></td></tr>
                        <tr>
                            <td class="tdbold">Comentario</td>
                            <td class="tdnormal" style="height: 12mm"></td>
                        </tr>
                        <tr><td colspan="2" style="background-color: #000000; height: 1mm;"></td></tr>
                    </table>
                    <!--
                    Turno: <span style="color:#0033FF; font-weight: bold">[fake data]</span><br />
                    Supervisor: <span style="color:#0033FF; font-weight: bold">[fake data]</span><br />
-->
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #009900">
                <div style="background-color: #FF0000">
                    <input type="text" id="selNumeroParte">
                </div>
            </td>
        </tr>
    </table>



<script>

//    $('#selNumeroParte').autocomplete({
//        source : "getdata"
//    });


    $arrDivs = Array('APD','Area','Tecnologia','Linea','Defecto','Causa','CodigoScrap');

    function cleanSelect($strSelect){

        for($intIx=$.inArray($strSelect,$arrDivs);$intIx<$arrDivs.length;$intIx++){
            $('#div' + $arrDivs[$intIx]).hide();
            $('#sel' + $arrDivs[$intIx])
                .find('option')
                .remove()
                .end();
            $('#sel' + $arrDivs[$intIx]).append('<option value="-1">- Seleccione -</option>');
            $('#sel' + $arrDivs[$intIx]).val(-1);
            $('#td' + $arrDivs[$intIx]).html('');
        }
    };

    function getAPD(){
        cleanSelect('APD');
        if($('#selProyecto').val()!=-1){
            $arrProyecto = $('#selProyecto').val().split('|');
            $('#tdSegmento').html($arrProyecto[3]);
            $('#tdProfitCenter').html($arrProyecto[5]);
            $strQueryString = "intProc=0&intProyecto=" + $arrProyecto[0] + "&strProyecto=" + $arrProyecto[1] + "&intSegmento=" + $arrProyecto[2] + "&strSegmento=" + $arrProyecto[3] + "&intProfitCenter=" + $arrProyecto[4] + "&strProfitCenter=" + $arrProyecto[5];
            $.ajax({
                data : $strQueryString,
                type : "POST",
                dataType : "json",
                url : "getdata.php",
                success : function($jsnData){
                    for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                        $('#selAPD').append('<option value="' + $jsnData.arrData[$intIx].intAPD + '">' + $jsnData.arrData[$intIx].strAPD + '</option>');
                    }
                    $('#divAPD').slideDown('fast');
                }
            });
        };
    }

    function getArea(){
        cleanSelect('Area');
        $intAPD = $('#selAPD').val();
        $strAPD = $('#selAPD option:selected').text();
        $strQueryString = "intProc=1&intAPD=" + $intAPD + "&strAPD=" + $strAPD;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selArea').append('<option value="' + $jsnData.arrData[$intIx].intArea + '">' + $jsnData.arrData[$intIx].strArea + '</option>');
                }
                $('#divArea').slideDown('fast');
            }
        });
    }

    function getTecnologia(){
        cleanSelect('Tecnologia');
        $intArea = $('#selArea').val();
        $strArea = $("#selArea option:selected").text();
        $('#tdArea').html($strArea);
        $strQueryString = "intProc=2&intArea=" + $intArea + "&strArea=" + $strArea;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selTecnologia').append('<option value="' + $jsnData.arrData[$intIx].intTecnologia + '">' + $jsnData.arrData[$intIx].strTecnologia + '</option>');
                }
                $('#divTecnologia').slideDown('fast');
            }
        });
    }

    function getLinea(){
        cleanSelect('Linea');
        $intTecnologia = $('#selTecnologia').val();
        $strTecnologia = $("#selTecnologia option:selected").text();
        $('#tdTecnologia').html($strTecnologia);
        $strQueryString = "intProc=3&intTecnologia=" + $intTecnologia + "&strTecnologia=" + $strTecnologia;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selLinea').append('<option value="' + $jsnData.arrData[$intIx].intLinea + '">' + $jsnData.arrData[$intIx].strLinea + '</option>');
                }
                $('#divLinea').slideDown('fast');
            }
        });
    }

    function getDefecto(){
        cleanSelect('Defecto');
        $intLinea = $('#selLinea').val();
        $strLinea = $("#selLinea option:selected").text();
        $('#tdLinea').html($strLinea);
        $strQueryString = "intProc=4&intLinea=" + $intLinea + "&strLinea=" + $strLinea;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selDefecto').append('<option value="' + $jsnData.arrData[$intIx].intDefecto + '">' + $jsnData.arrData[$intIx].strDefecto + '</option>');
                }
                $('#divDefecto').slideDown('fast');
            }
        });
    }

    function getCausa(){
        cleanSelect('Causa');
        $intDefecto = $('#selDefecto').val();
        $strDefecto = $("#selDefecto option:selected").text();
        $('#tdDefecto').html($strDefecto);
        $strQueryString = "intProc=5&intDefecto=" + $intDefecto + "&strDefecto=" + $strDefecto;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selCausa').append('<option value="' + $jsnData.arrData[$intIx].intCausa + '">' + $jsnData.arrData[$intIx].strCausa + '</option>');
                }
                $('#divCausa').slideDown('fast');
            }
        });
    }

    function getCodigoScrap(){
        cleanSelect('CodigoScrap');
        $intCausa = $('#selCausa').val();
        $strCausa = $("#selCausa option:selected").text();
        $('#tdCausa').html($strCausa);
        $strQueryString = "intProc=6&intCausa=" + $intCausa + "&strCausa=" + $strCausa;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selCodigoScrap').append('<option value="' + $jsnData.arrData[$intIx].intCodigoScrap + '">' + $jsnData.arrData[$intIx].strCodigoScrap + '</option>');
                }
                $('#divCodigoScrap').slideDown('fast');
            }
        });
    }

    function addParts(){
        $intCodigoScrap = $('#CodigoScrap').val();
        $strCodigoScrap = $("#selCodigoScrap option:selected").text();
        $('#tdCodigoScrap').html($strCodigoScrap);
/*        $strQueryString = "intProc=6&intCausa=" + $intCausa + "&strCausa=" + $strCausa;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selCodigoScrap').append('<option value="' + $jsnData.arrData[$intIx].intCodigoScrap + '">' + $jsnData.arrData[$intIx].strCodigoScrap + '</option>');
                }
                $('#divCodigoScrap').slideDown('fast');
            }
        });
*/    }

</script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>