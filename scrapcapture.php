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
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 0;
            }
        </style>
    </head>
    <body>
    <?php include_once('inc/header.php'); ?>
    <?php include_once('inc/menu.php'); ?>
    <table width="100%" style="border-collapse: collapse; border-spacing: 0; margin-top: 0; margin-bottom: 500px;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px 10px 10px 10px; ">
                <div class="divother" id="divGenerales" style="">
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
                    <div class="divclass" style="display: none;" id="divCodigoScrap"><label for="selCodigoScrap">Código Scrap</label><select id="selCodigoScrap" onchange="showNext();" tabindex="8"></select></div>
                    <div class="divclass" style="display: none; text-align: right;" id="divNext"><input type="button" value="siguiente&rarr;" id="btnNext" onclick="addParts();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; margin-left: 5px; "></div>
                </div>

                <div id="divPartes" class="divother2" style="margin: 0 auto 0 auto; display: none; width: 710px;">
                    <div class="divclass" style="font-size: 9pt">
                        <label for="txtNumerodeParte" style="width: auto; padding-right: 20px;">Número de Parte</label><input id="txtNumerodeParte" type="text" style="width: 160px; text-align: center" value="" strParte="">
                        <label for="txtCantidad" style="width: auto; padding-right: 20px; padding-left: 23px;">Cantidad</label><input type="text" id="txtCantidad" value="" style="width: 50px; text-align: center">
                        <label for="selUbicacion" style="width: auto; padding-right: 20px; padding-left: 23px;">Ubicación</label><select id="selUbicacion" style="width: 150px;"></select>
                    </div>
                    <div class="divclass" style="font-size: 9pt; text-align: center;">
                        <input type="button" value="seriales" onclick="showSeriales();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; margin-right: 5px; ">
                        <input type="button" value="agregar" onclick="addParte();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; margin-left: 5px; ">
                    </div>
                    <div id="lblErrors" class="lblErrorWarnings" style="  background-image: url('img/error.png')"></div>
                    <div id="divPartesHeader" >
                        <div class="divPartesGrid divPartesCantidad divPartesHeader">Cant.</div>
                        <div class="divPartesGrid divPartesNoParte divPartesHeader">No. Parte</div>
                        <div class="divPartesGrid divPartesDescripcion divPartesHeader">Descripción</div>
                        <div class="divPartesGrid divPartesCostoU divPartesHeader">Costo U.</div>
                        <div class="divPartesGrid divPartesTipo divPartesHeader">Tipo</div>
                        <div class="divPartesGrid divPartesSubTipo divPartesHeader">SubTipo</div>
                    </div>
                    <div id="divPartesContainer" style=" height: 148px; overflow-x: hidden; overflow-y: scroll; margin-bottom: 7px; " ></div>
                    <div class="divclass" style="" id="divNext">
                        <input type="button" value="&larr;anterior" onclick="showGenerales();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; margin-left: 5px; ">
                        <input type="button" value="siguiente&rarr;" onclick="addParts();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; margin-right: 5px; float:right; ">
                        <br style="clear: both;"/>
                    </div>
                </div>

                <div id="divPartes" class="divother2" style="margin: 0 auto 0 0;  width: 710px; display: none;">
                    <div class="divclass" style="">¿Por qué?</div>
                    <div>
                        <div style="margin-bottom: 2px;"><label style="width: 20px;">1</label><input type="text" value="" style="width: 500px"></div>
                        <div style="margin-bottom: 2px;"><label style="width: 20px;">2</label><input type="text" value=""></div>
                        <div style="margin-bottom: 2px;"><label style="width: 20px;">3</label><input type="text" value=""></div>
                        <div style="margin-bottom: 2px;"><label style="width: 20px;">4</label><input type="text" value=""></div>
                        <div style="margin-bottom: 2px;"><label style="width: 20px;">5</label><input type="text" value=""></div>
                    </div>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; background-color: #0000CC; margin-top: 7px">
                        <tr style="border: 0; border-spacing: 0">
                            <td style="border: 0; border-spacing: 0; width: 351px; padding-right: 4px;">
                                <div class="divclass" style="">Comentarios</div>
                                <div>
                                    <textarea style="resize: none"></textarea>
                                </div>
                            </td>
                            <td style="border: 0; border-spacing: 0; width: 351px; padding-left: 4px;">
                                <div class="divclass" style="">Acciones Correctivas</div>
                                <div>
                                    <textarea style="resize: none"></textarea>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
            </td>
            <td style="width: 50%; text-align: center; padding: 10px 10px 10px 10px; vertical-align: top; ">
                <div id="LabeltoPrint" style="text-align: left;display: inline-block; width: 130mm; height: 75.4mm; background-color:#ff4646; border-radius: 5px">
                    <table style="border-spacing: 0; border: 0; border-collapse: collapse;">
                        <tr style="height: 4mm;">
                            <td rowspan="2" style="width: 34mm; padding: 1mm 1mm 0 1mm; "><img src="images/continental_black.png" style="height: 8mm" /></td>
                            <td style=" height: 4mm; font-size: 8pt; padding: 0 0 0 0; color:#000000; width: 38mm; font-weight: bold; text-align: center;">Scrap</td>
                            <td rowspan="2" style="width: 54mm; padding: 1mm 1mm 0 1mm; text-align: center; " align="center">
                                <div id="tdBarCode" style="display: inline-block;" ></div>
                            </td>
                        </tr>
                        <tr style="height: 4mm;">
                            <td style="height: 4mm; font-size: 8pt; padding: 0 0 0 0; color:#000000; text-align: center"><b>Folio</b><span style="margin-left: 5mm; ">000000</span></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border: 0; border-collapse: collapse;">
                        <tr style="height: 4mm;border: 0;">
                            <td style="width: 8mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: bold; ">Costo</td>
                            <td style="width: 31mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: normal; text-align: right; " id="tdCosto">0.00</td>
                            <td style="width: 11mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: bold; ">MXN</td>
                            <td style="width: 29mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: bold;">Fecha&nbsp;de&nbsp;captura</td>
                            <td style="width: 19mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: normal; "><?php echo date("d/m/Y") ?></td>
                            <td style="width: 11mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: bold;">Hora</td>
                            <td style="width: 7mm; border: 0; padding:0 1mm 0 1mm; font-size: 8pt; color:#000000; font-weight: normal; "><?php echo date("H:i") ?></td>
                        </tr>
                        <tr style="height: 1mm;">
                            <td colspan="7" style="background-color: #000000; height: 1mm; border: 0; padding: 0 0 0 0;"></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border:0; border-collapse: collapse;">
                        <tr style="height: 4mm">
                            <td class="tdbold" style=" width: 13mm; ">Operador</td>
                            <td class="tdnormal" style="width: 65mm;  " ><?php echo $strUser . ' ' . $strUName; ?></td>
                            <td class="tdbold" style=" width: 7mm;">Área</td>
                            <td class="tdnormal" style="width: 37mm;" id="tdArea"></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border:0; border-collapse: collapse;">
                        <tr style="height: 4mm">
                            <td class="tdbold" style="width: 15mm;">Tecnología</td>
                            <td class="tdnormal" style="width: 57mm;" id="tdTecnologia"></td>
                            <td class="tdbold" style="width: 8mm">Línea</td>
                            <td class="tdnormal" style="width: 42mm;" id="tdLinea"></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border:0; border-collapse: collapse;">
                        <tr>
                            <td class="tdbold" style="width: 11mm;">Defecto</td>
                            <td class="tdnormal" style="width: 115mm;" id="tdDefecto"></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border:0; border-collapse: collapse;">
                        <tr>
                            <td class="tdbold" style="width: 20mm;">Relacionado a</td>
                            <td class="tdnormal" style="width: 21mm;" id="tdCausa"></td>
                            <td class="tdbold" style="width: 24mm;">Código de Scrap</td>
                            <td class="tdnormal" style="width: 57mm" id="tdCodigoScrap"></td>
                        </tr>
                    </table>

                    <table style="border-spacing: 0; border:0; border-collapse: collapse;">
                        <tr>
                            <td class="tdbold" style="width: 128mm">Parte(s) (Cantidad)</td>
                        </tr>
                        <tr>
                            <td class="tdnormal" id="tdPartes" style="width: 128mm; height: 17mm"></td>
                        </tr>
                        <tr style="height: 1mm;">
                            <td style="background-color: #000000; height: 1mm; border: 0; padding: 0 0 0 0;"></td>
                        </tr>
                        <tr>
                            <td class="tdbold" style="width: 128mm">Comentarios y/o Acciones correctivas</td>
                        </tr>
                        <tr>
                            <td class="tdnormal" id="tdComentario" style="width: 128mm; height: 17mm"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <div id="divModal" style="background-color: rgba(0,0,0,.8); position: absolute; width: 100%; height: 100%; z-index: 10000000; top: 0; left: 0; display: none ">
        <div id="divSeriales" style=" text-align: center; width: 500px; height: 315px; padding: 10px 10px 10px 10px; border:1px #EAEAEA solid; border-radius: 5px; box-shadow: 0 1px 0 #FFFFFF; background-color: #FFFFFF; top:0; bottom: 0; left: 0; right: 0; margin: auto auto auto auto; position: absolute; display: none ">
            <div style=" background-color: #EFEFEF; text-align: center; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px;">
                Ingresar Seriales
            </div>
            <div style=" background-color: #EFEFEF; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px;">
                Número de Parte <label id="lblSerialesNoParte" style="display: inline; font-weight: bold;"></label><br />
                <label id="lblSerialesContador" style="display: inline; margin: 0 auto 0 auto; font-weight: bold; "></label>
                <label id="lblSerialesCantidad" style="display: inline; margin: 0 auto 0 auto; "></label>
                <input id="txtSerial" type="text" value="" style="width: 180px; text-align: center">
            </div>
            <div id="divSerialesContainer" style="background-color: #EFEFEF; text-align: center; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px; height: 100px; overflow: auto;">

            </div>
            <div id="lblSerialesErrors" class="lblErrorWarnings" style="padding: 0 50px 0 50px;  background-image: url('img/error.png')"></div>
            <input type="button" value="agregar" onclick="hideSeriales();" style=" font-size: 9pt; background-color: #F9A11B; border: 1px #000000 solid; color:#000000; cursor: pointer; box-shadow: 0 1px 0 #E58D07; border-radius: 11px; padding: 2px 20px 0 20px; ">
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