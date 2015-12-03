<?php
ini_set('display_errors',1);
session_start();

if(!isset($_SESSION["intUser"]))
{
    header("location: index.php");
}

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
                <div id="LabeltoPrint" style="text-align: left;display: inline-block; width: 139mm; height: 76mm; padding: 2mm 2mm 2mm 2mm; background-color:#ff4646; box-shadow: 0px 1px 0px #000000; border-radius: 5px">
                    <table style="border-spacing: 0mm; border-collapse: collapse; width: 100%">
                        <tr>
                            <td rowspan="2" style="width: 36mm; "><img src="/scrap/dummy/img/continental_black.png" style="height: 8mm" /></td>
                            <td style=" height: 4mm; font-size: 9pt; color:#000000; width: 37mm; font-weight: bold; text-align: center;">Scrap</td>
                            <td rowspan="2" id="tdBarCode" style="width: 66mm; text-align: center" align="center"></td>
                        </tr>
                        <tr>
                            <td style="height: 4mm; font-size: 9pt; color:#000000; text-align: center"><b>Folio</b><span style="margin-left: 5mm; ">000000</span></td>
                        </tr>
                    </table>
                    <table style="border-spacing: 0mm; border-collapse: collapse;">
                        <tr style="height: 5mm;">
                            <td style="width: 10%; font-size: 9pt; color:#000000; font-weight: bold; ">Costo</td>
                            <td style="text-align: right; width: 18%; font-size: 9pt; color:#000000; font-weight: normal;" id="tdCosto">0.00</td>
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
                            <td class="tdbold" colspan="2">Parte(s) (Cantidad - Ubicacion)</td>
                        </tr>
                        <tr>
                            <td class="tdnormal" id="tdPartes" colspan="2" style="text-align: left; height: 13mm"></td>
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
                <div id="divPartes" class="divother2" style="margin: 0px auto 0px auto; display: none; width: 1000px; ">
                    <div class="divclass">Numero(s) de Parte</div>
                    <div class="divclass" style="font-size: 9pt">
                        <label for="txtNumerodeParte" style="width: auto; padding-right: 5px;">Número de Parte</label><input id="txtNumerodeParte" type="text" style="width: 160px; text-align: center" value="" strParte="">
                        <label for="txtCantidad" style="width: auto; padding-right: 5px;">Cantidad</label><input type="text" id="txtCantidad" value="" style="width: 50px; text-align: center">
                        <label for="selUbicacion" style="width: auto; padding-right: 5px;">Ubicación</label><select id="selUbicacion" style="width: 150px;"></select>
                        <input type="button" value="seriales" onclick="showSeriales();" style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; ">
                        <input type="button" value="agregar" onclick="addParte();" style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; ">
                    </div>
                    <div id="lblErrors" class="lblErrorWarnings" style="  background-image: url('img/error.png')"></div>
                    <div id="divPartesContainer" >
                        <div id="divPartesHeader" >
                            <div class="divPartesGrid divPartesCantidad divPartesHeader">Cantidad</div>
                            <div class="divPartesGrid divPartesNoParte divPartesHeader">No. Parte</div>
                            <div class="divPartesGrid divPartesDescripcion divPartesHeader">Descripción</div>
                            <div class="divPartesGrid divPartesCostoU divPartesHeader">Costo U.</div>
                            <div class="divPartesGrid divPartesTipo divPartesHeader">Tipo</div>
                            <div class="divPartesGrid divPartesSubTipo divPartesHeader">SubTipo</div>
                            <div class="divPartesGrid divPartesNoSerial divPartesHeader">No. Serial</div>
                            <div class="divPartesGrid divPartesUbicacion divPartesHeader">Ubicación</div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div id="divModal" style="background-color: rgba(0,0,0,.8); position: absolute; width: 100%; height: 100%; z-index: 10000000; top: 0px; left: 0px; display: none ">
        <div id="divSeriales" style=" text-align: center; width: 500px; height: 315px; padding: 10px 10px 10px 10px; border:1px #EAEAEA solid; border-radius: 5px; box-shadow: 0px 1px 0px #FFFFFF; background-color: #FFFFFF; top:0; bottom: 0; left: 0; right: 0; margin: auto auto auto auto; position: absolute; display: none ">
            <div style=" background-color: #EFEFEF; text-align: center; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px;">
                Ingresar Seriales
            </div>
            <div style=" background-color: #EFEFEF; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px;">
                Número de Parte <label id="lblSerialesNoParte" style="display: inline; font-weight: bold;"></label><br />
                <label id="lblSerialesContador" style="display: inline; margin: 0px auto 0px auto; font-weight: bold; "></label>
                <label id="lblSerialesCantidad" style="display: inline; margin: 0px auto 0px auto; "></label>
                <input id="txtSerial" type="text" value="" style="width: 180px; text-align: center">
            </div>
            <div id="divSerialesContainer" style="background-color: #EFEFEF; text-align: center; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px; height: 100px; overflow: auto;">

            </div>
            <div id="lblSerialesErrors" class="lblErrorWarnings" style="padding: 0px 50px 0px 50px;  background-image: url('img/error.png')"></div>
            <input type="button" value="agregar" onclick="hideSeriales();" style=" font-size: 9pt; background-color: #FFA500; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0px 1px 0px #B95F00; border-radius: 11px; padding: 2px 20px 0px 20px; ">
        </div>
    </div>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/jquery.auto-complete.js"></script>
    <script src="js/jquery.numeric.js"></script>
    <script src="js/jquery-barcode.min.js"></script>
    <script src="js/printThis.js"></script>
    <script src="js/scrapcapture.js"></script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>