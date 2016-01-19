<?php
ini_set("display_errors",1);
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
        <link rel="stylesheet" type="text/css" href="../../css/working.css">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
    <?php
    if(!isset($_GET['source'])) {
        include_once('../../inc/header.php');
        include_once('../../inc/menu.php');
    }else{
        ?>
        <div class=" divTitles ">Captura de Scrap</div>
        <?php
    }
    ?>
    <table class="capture_main_table">
        <tr>
            <td class="capture_td_workarea">
                <div class="capture_div_container">
                    <div class="capture_div_row" id="divProject"><label for="selProject" class="capture_label">Proyecto</label><select id="selProject" onchange="getData('APD');" tabindex="1" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divAPD"><label for="selAPD" class="capture_label">APD</label><select id="selAPD" onchange="getData('Area');" tabindex="2" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divArea"><label for="selArea" class="capture_label">Area</label><select id="selArea" onchange="getData('Station');" tabindex="3" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divStation"><label for="selStation" class="capture_label">Tecnología</label><select id="selStation" onchange="getData('Line');" tabindex="4" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divLine"><label for="selLine" class="capture_label">Linea</label><select id="selLine" onchange="getData('Fault');" tabindex="5" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divFault"><label for="selFault" class="capture_label">Defecto</label><select id="selFault" onchange="getData('Cause');" tabindex="6" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divCause"><label for="selCause" class="capture_label">Causa</label><select id="selCause" onchange="getData('ScrapCode');" tabindex="7" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_row" style="display: none;" id="divScrapCode"><label for="selScrapCode" class="capture_label">Código Scrap</label><select id="selScrapCode" onchange="getData('Part');" tabindex="8" class="capture_input_select"><option value="-1" selected="selected">- Selecciona -</option></select></div>
                    <div class="capture_div_buttons" style="text-align: right;">
                        <input type="button" class="buttons button_red" value="cancelar" id="btnNext" onclick="addParts();">
                    </div>
                </div>
            </td>
            <td id="tdScrapLabel" class="capture_td_label">
                <div id="divScrapLabel" style="background-color: #CC0000; width: 98mm; height: 58mm; margin: 0 0 0 0; padding: 0 1mm 0 1mm; border-radius: 5px; font-size: 7pt; font-weight: bold; color:#000000; font-family: Arial;">
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 10mm">
                            <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 28mm; text-align: center; vertical-align: middle;"><img src="/scrap/images/continental_black.png" style="border: 0; height: 6mm; vertical-align: bottom;" /></td>
                            <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 14mm; text-align: center; vertical-align: middle; font-size: 9pt;">Scrap</td>
                            <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 56mm; text-align: center; vertical-align: middle;" id="tdLabelBarcode"></td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 8mm; text-align: left; vertical-align: middle;">Costo</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 16mm; text-align: right; vertical-align: middle;">9,999,999.00</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 18mm; text-align: left; vertical-align: middle;">MXN</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 22mm; text-align: left; vertical-align: middle;">Fecha de Captura</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 19mm; text-align: left; vertical-align: middle;">18/01/2016</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 7mm; text-align: left; vertical-align: middle;">Hora</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 7mm; text-align: left; vertical-align: middle;">12:02</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;"><tr style="border: 0; height: .5mm"><td style="height: .5mm; background-color: #000000;"></td></tr></table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 13mm; text-align: left; vertical-align: middle;">Operador</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 43mm; text-align: left; vertical-align: middle;">Luis Gonzalo Morales Ramirez</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 7mm; text-align: left; vertical-align: middle;">Área</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 35mm; text-align: left; vertical-align: middle;">BACKEND/ENSAMBLE FINAL</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 15mm; text-align: left; vertical-align: middle;">Tecnología</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 49mm; text-align: left; vertical-align: middle;">INSERCION Y VERIFICACION DE CON...</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 8mm; text-align: left; vertical-align: middle;">Línea</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 26mm; text-align: left; vertical-align: middle;">LASER A NAVE 2 B&S</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 11mm; text-align: left; vertical-align: middle;">Defecto</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 89mm; text-align: left; vertical-align: middle;">MATERIAL FUERA DE ESPECIFICACION (SOLO CON FIRMA DE INGENIE...</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 9mm; text-align: left; vertical-align: middle;">Causa</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 27mm; text-align: left; vertical-align: middle;">PROVEEDOR NUEVO</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 18mm; text-align: left; vertical-align: middle;">Código Scrap</td>
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 44mm; text-align: left; vertical-align: middle;">022-2 PRUEB DESTRUCTIVAS, DE I...</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 98mm; text-align: left; vertical-align: middle;">Parte(s) [Cantidad]</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 100mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 13mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 13mm; width: 98mm; text-align: left; vertical-align: top;">HYUNDAI_BOM_DUMMY [9,999], WEBASTO_BOM_DUMMY [9,999], DUMMYBACANNANAVE2 [9,999], A2C53350221_SMD [9,999], A2C53374468BOTT [9,999], A2C830078009201 [9,999], A2C830078009301 [9,999], OCE 2007 NISSAN [9,999], OCE 2007 SUZUKI [9,999], GMBCM_BOM_DUMMY [9,999]</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;"><tr style="border: 0; height: .5mm"><td style="height: .5mm; background-color: #000000;"></td></tr></table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 3mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 98mm; text-align: left; vertical-align: middle;">Comentarios y/o Acciones Correctivas</td>
                        </tr>
                    </table>
                    <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 100mm; margin: 0 0 0 0;">
                        <tr style="border: 0; height: 13mm">
                            <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 13mm; width: 100mm; text-align: left; vertical-align: top;">1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 1234567890 </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <div id="divWorkingBackground">
        <div id="divWorking">
            <img src="../../images/wait_64.gif" />
        </div>
    </div>
    <script src="../../js/jquery-1.11.3.min.js"></script>
    <script src="../../js/jquery.auto-complete.js"></script>
    <script src="../../js/jquery.numeric.js"></script>
    <script src="../../js/jquery-barcode.min.js"></script>
    <script src="../../js/printThis.js"></script>
    <script src="javascript.js"></script-->
    <script>
    </script>
    </body>
    </html>
<?php
unset($objScrap);
?>