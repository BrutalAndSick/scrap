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
    <meta charset="utf-8">
    <title>:: CONTINENTAL :: SCRAP ::</title>
    <link rel="stylesheet" type="text/css" href="css/scrap.css">
    <link rel="stylesheet" type="text/css" href="css/scrapcapture.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.auto-complete.css">
    <style>
    </style>
</head>
<body>
    <?php include_once('inc/header.php'); ?>
    <?php include_once('inc/menu.php'); ?>
    <table width="100%" style="border-collapse: collapse; border-spacing: 0px; margin-top: 10px; margin-bottom: 500px;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top; ">
                <div class="divother">
                    <div class="divclass"><label for="selProyecto">Proyecto</label><select id="selProyecto" onchange="getAPD();" tabindex="1">
                            <option value="-1" selected="selected">- Seleccione -</option>
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
                        </select></div>
                    <div class="divclass" style="display: none" id="divAPD"><label for="selAPD">APD</label><select id="selAPD" onchange="getArea();" tabindex="2"></select></div>
                    <div class="divclass" style="display: none" id="divArea"><label for="selArea">Area</label><select id="selArea" onchange="getTecnologia();" tabindex="3"></select></div>
                    <div class="divclass" style="display: none;" id="divTecnologia"><label for="selTecnologia">Tecnología</label><select id="selTecnologia" onchange="getLinea();" tabindex="4"></select></div>
                    <div class="divclass" style="display: none;" id="divLinea"><label for="selLinea">Linea</label><select id="selLinea" onchange="getDefecto();" tabindex="5"></select></div>
                    <div class="divclass" style="display: none;" id="divDefecto"><label for="selDefecto">Defecto</label><select id="selDefecto" onchange="getCausa();" tabindex="6"></select></div>
                    <div class="divclass" style="display: none;" id="divCausa"><label for="lblCausa">Relacionado a</label><select id="selCausa" onchange="getCodigoScrap();" tabindex="7"></select></div>
                    <div class="divclass" style="display: none;" id="divCodigoScrap"><label for="selCodigoScrap">Código Scrap</label><select id="selCodigoScrap" onchange="addParts();" tabindex="8"></select></div>
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top; ">
                <div style="text-align: left;display: inline-block; width: 139mm; height: 76mm; padding: 2mm 2mm 2mm 2mm; background-color:#ff4646; box-shadow: 2px 2px 0px #000000">
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td rowspan="2" style="width: 50%"><img src="img/continental_black.png" style="height: 8mm" /></td>
                            <td style="height: 4mm; font-size: 9pt; color:#000000; width: 50%; font-weight: bold; text-align: right;">Scrap</td>
                        </tr>
                        <tr>
                            <td style="height: 4mm; font-size: 9pt; color:#000000; text-align: right"><b>Folio</b><span style="margin-left: 5mm; min-width: 20mm ">0663546</span></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse;">
                        <tr style="height: 5mm;">
                            <td style="width: 10%; font-size: 9pt; color:#000000; font-weight: bold; ">Costo</td>
                            <td style="text-align: right; width: 18%; font-size: 9pt; color:#000000; font-weight: normal;">0.00</td>
                            <td style="width: 14%; font-size: 9pt; color:#000000; font-weight: bold; ">MXN</td>
                            <td style="width: 16%; font-size: 9pt; color:#000000; font-weight: bold;">Fecha&nbsp;de&nbsp;captura</td>
                            <td style="width: 14%; font-size: 9pt; color:#000000; font-weight: normal; "><?php echo date("d/m/y") ?></td>
                            <td style="text-align: right; width: 14%; font-size: 9pt; color:#000000; font-weight: bold;">Hora</td>
                            <td style="text-align: right; width: 14%; font-size: 9pt; color:#000000; font-weight: normal; "><?php echo date("h:i") ?></td>
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
                            <td class="tdnormal" id="tdNumeroParte"></td>
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
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="divPartes" class="divother2" style="margin: 0px auto 0px auto; display: none; width: 800px; ">
                    <div class="divclass">Numero(s) de Parte</div>
                    <div class="divclass">
                        <label for="txtCantidad">Cantidad</label><input type="text" id="txtCantidad" value="" style="width: 50px; text-align: center">
                        <label for="txtNumerodeParte">Número de Parte</label><input id="txtNumerodeParte" type="text" style="width: 160px; text-align: center">
                        <label for="selUbicacion">Ubicación</label><select id="selUbicacion" style="width: 100px;"></select>
                        <input type="button" value="agregar" style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; ">
                        <input type="hidden" id="lblNumerodeParte" value="">
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/jquery.auto-complete.js"></script>
    <script src="js/jquery.numeric.js"></script>
    <script src="js/scrapcapture.js"></script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>