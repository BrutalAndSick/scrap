<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
        <link rel="stylesheet" type="text/css" href="../../css/working.css">
        <link rel="stylesheet" type="text/css" href="/modules/capture/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="../../css/scrapcapture.css">
    </head>
<body>
<?php
if (!isset($_GET['source'])) {
    include_once(INCLUDE_PATH . 'header.php');
    ?>
    <div class=" divBlank "></div>
    <?php
} else {
    ?>
    <div class=" divTitles ">Captura de Scrap</div>
    <?php
}
?>
    <table class="capture_main_table">
        <tr id="trCaptureGeneral">
            <td style="vertical-align: top; padding: 0 0 0 0; text-align: center; ">
                <div class="capture_div_container">
                    <div class="capture_div_row" style="" id="divShip"><label for="selShip" class="capture_label">Nave</label><select id="selShip" onchange="getData('Area');" tabindex="0" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divArea"><label for="selArea" class="capture_label">Area</label><select id="selArea" onchange="getData('Station');" tabindex="1" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divStation"><label for="selStation" class="capture_label">Tecnología</label><select id="selStation" onchange="getData('Line');" tabindex="2" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divLine"><label for="selLine" class="capture_label">Linea</label><select id="selLine" onchange="getData('Fault');" tabindex="3" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divFault"><label for="selFault" class="capture_label">Defecto</label><select id="selFault" onchange="getData('Cause');" tabindex="4" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divCause"><label for="selCause" class="capture_label">Causa</label><select id="selCause" onchange="getData('ScrapCode');" tabindex="5" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divScrapCode"><label for="selScrapCode" class="capture_label">CódigoScrap</label><select id="selScrapCode" onchange="getData('Project');" tabindex="6" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divProject"><label for="selProject" class="capture_label">Proyecto</label><select id="selProject" onchange="getData('Part');" tabindex="7" class="capture_input_select"></select></div>
                    <div class="capture_div_row" style="display: none;" id="divParts">
                        <div class="capture_div_row" style="text-align: top">
                            <label for="txtPartNumber" class="capture_label" style="width: 113px !important;">Número de Parte</label><input id="txtPartNumber" type="text" tabindex="8" class="capture_input_text" style="width: 174px; text-align: center" value="" strParte="">
                            <label for="txtQuantity" class="capture_label" style="width: 62px !important;">Cantidad</label><input type="number" min="0" max="9999" id="txtQuantity" tabindex="9" value="" class="capture_input_text" style="width: 60px; text-align: left">
                            <label for="txtLocation" class="capture_label" style="width: 62px !important;">Ubicación</label><input type="text" id="txtLocation" value="" tabindex="10" class="capture_input_text" style="width: 174px; text-align: center">
                            <input type="button" value="seriales" onclick="showSeriales();" class="buttons_small button_orange" tabindex="11">
                            <input type="button" value="agregar" onclick="addParte();" class="buttons_small button_orange" tabindex="12">
                        </div>
                        <table class="tblHeader"><tr><th class="thHeader" style="width: 100px;">Cantidad</th><th class="thHeader" style="width: 150px;">Parte</th><th class="thHeader" style="width: 390px;">Descripción</th><th class="thHeader" style="width: 140px;">Costo</th><th class="thHeader" style="width: 30px;"></th></tr></table>
                        <div id="divPartesContainer" style="background-color: #FFFFFF; height: 155px; overflow-x: hidden; overflow-y: scroll; border-radius: 5px; margin-top: 1px; margin-left: 2px; margin-bottom: 5px; " ><table class="tblGrid" id="tblParts"><tbody></tbody></table></div>
                    </div>
                    <div class="capture_div_row" style="text-align: right;">
                        <label id="lblPartError" style="display: inline-block; margin-right: 5px; width: 766px; padding: 6px 5px 6px 5px; border: 1px #FF2828 solid; border-radius: 3px; color:#FF2828; text-align: left; display: none "></label>
                        <input type="button" class="buttons button_orange" id="btnNext" style="display: none;" value="continuar" tabindex="13" onclick="goStep2();">
                        <input type="button" class="buttons button_red" value="cancelar" id="btnCancel" tabindex="14" onclick="location.reload();">
                    </div>
                </div>
            </td>
        </tr>
        <tr id="trStep2" style="display:none" >
            <td style="vertical-align: top; text-align: center; padding: 0 0 0 0; ">
                <div class="capture_div_container">
                    <div class="capture_div_row" id="divComments"><label for="txtComments" class="capture_label">Observaciones</label><textarea type="text" id="txtComments" tabindex="14" onchange="verifyComments();" class="capture_input_text" style="width: calc(100% - 182px); resize: none; height: 155px;"></textarea></div>
                    <div class="capture_div_row" style="" id="divWhy1"><label for="txtWhy1" class="capture_label">¿Por qué? (1)</label><input type="text" id="txtWhy1" maxlength="254" tabindex="15" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divWhy2"><label for="txtWhy1" class="capture_label">¿Por qué? (2)</label><input type="text" id="txtWhy2" maxlength="254" tabindex="16" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divWhy3"><label for="txtWhy1" class="capture_label">¿Por qué? (3)</label><input type="text" id="txtWhy3" maxlength="254" tabindex="17" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divWhy4"><label for="txtWhy1" class="capture_label">¿Por qué? (4)</label><input type="text" id="txtWhy4" maxlength="254" tabindex="18" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divWhy5"><label for="txtWhy1" class="capture_label">¿Por qué? (5)</label><input type="text" id="txtWhy5" maxlength="254" tabindex="19" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divAction1"><label for="txtAction1" class="capture_label">Acción correctiva (1)</label><input type="text" id="txtAction1" maxlength="254" tabindex="20" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divAction2"><label for="txtAction1" class="capture_label">Acción correctiva (2)</label><input type="text" id="txtAction2" maxlength="254" tabindex="21" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divAction3"><label for="txtAction1" class="capture_label">Acción correctiva (3)</label><input type="text" id="txtAction3" maxlength="254" tabindex="22" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divAction4"><label for="txtAction1" class="capture_label">Acción correctiva (4)</label><input type="text" id="txtAction4" maxlength="254" tabindex="23" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="" id="divAction5"><label for="txtAction1" class="capture_label">Acción correctiva (5)</label><input type="text" id="txtAction5" maxlength="254" tabindex="24" class="capture_input_text" style="width: calc(100% - 182px);" /></div>
                    <div class="capture_div_row" style="text-align: right;">
                        <label id="lblPartError" style="display: inline-block; margin-right: 5px; width: 766px; padding: 6px 5px 6px 5px; border: 1px #FF2828 solid; border-radius: 3px; color:#FF2828; text-align: left; display: none "></label>
                        <input type="button" class="buttons button_green" id="btnFinish" tabindex="25" value="terminar" onclick="insertScrapRecord();">
                        <input type="button" class="buttons button_red" value="cancelar" tabindex="26" id="btnCancel" onclick="location.reload();">
                    </div>
                </div>
            </td>
        </tr>
        <tr id="trLabel" style="display:none">
            <td style="vertical-align: top; text-align: center; padding: 0 0 0 0; ">
                <div class="capture_div_container">
                    <div class="capture_div_row" style="text-align: center;padding-top: 20px; padding-bottom: 20px;">
                        <div id="divScrapLabel" style="background-color: #FF0000; width: 98mm; height: 56mm; margin: 0 auto 0 auto; padding: 1mm 1mm 1mm 1mm; border-radius: 5px; font-size: 7pt; font-weight: bold; color:#000000; font-family: monospace;">
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 10mm">
                                <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 28mm; text-align: center; vertical-align: middle;"><img src="/images/continental_black.png" style="border: 0; height: 6mm; vertical-align: bottom;"/></td>
                                <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 14mm; text-align: center; vertical-align: middle; font-size: 9pt;">Scrap</td>
                                <td style="border: 0; padding: 0 0 0 0; height: 10mm; width: 56mm; text-align: center; vertical-align: middle;" id="tdLabelBarcode"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 3mm">
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 8mm; text-align: left; vertical-align: middle;">Costo</td>
                                <td id="tdLabelAmount" style="font-weight: bold; font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 16mm; text-align: right; vertical-align: middle;"></td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 14mm; text-align: left; vertical-align: middle;">MXN</td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 22mm; text-align: left; vertical-align: middle;">Fecha de Captura</td>
                                <td id="tdLabelDate" style="font-weight: bold; font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 16mm; text-align: left; vertical-align: middle;"></td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 7mm; text-align: left; vertical-align: middle;">Hora</td>
                                <td id="tdLabelTime" style="font-weight: bold; font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 14mm; text-align: left; vertical-align: middle;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;"><tr style="border: 0; height: .5mm"><td style="height: .5mm; background-color: #000000;"></td></tr></table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 3mm">
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 13mm; text-align: left; vertical-align: middle;">Operador</td>
                                <td id="tdLabelUser" style="font-weight: bold;font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 43mm; text-align: left; vertical-align: middle;"></td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 7mm; text-align: left; vertical-align: middle;">Área</td>
                                <td id="tdLabelArea" style="font-weight: bold;font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 35mm; text-align: left; vertical-align: middle;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 3mm">
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 15mm; text-align: left; vertical-align: middle;">Tecnología</td>
                                <td id="tdLabelStation" style="font-weight:bold;font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 49mm; text-align: left; vertical-align: middle;"></td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 8mm; text-align: left; vertical-align: middle;">Línea</td>
                                <td id="tdLabelLine" style="font-weight:bold;font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 26mm; text-align: left; vertical-align: middle;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 3mm">
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 11mm; text-align: left; vertical-align: middle;">Defecto</td>
                                <td id="tdLabelFault" style="font-weight:bold;font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 89mm; text-align: left; vertical-align: middle;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 3mm">
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 9mm; text-align: left; vertical-align: middle;">Causa</td>
                                <td id="tdLabelCause" style="font-weight: bold; font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 27mm; text-align: left; vertical-align: middle;"></td>
                                <td style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 18mm; text-align: left; vertical-align: middle;">Código Scrap</td>
                                <td id="tdLabelScrapCode" style="font-weight: bold; font-size: 7pt; border: 0; padding: 0 0 0 0; height: 3mm; width: 44mm; text-align: left; vertical-align: middle;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 15mm">
                                <td id="tdLabelParts" style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 13mm; width: 98mm; text-align: left; vertical-align: top;"></td>
                            </tr>
                        </table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;"><tr style="border: 0; height: .5mm"><td style="height: .5mm; background-color: #000000;"></td></tr></table>
                        <table style="border: 0; border-collapse: collapse; border-spacing: 0; width: 98mm; margin: 0 0 0 0;">
                            <tr style="border: 0; height: 15mm">
                                <td id="tdLabelCommentsActions" style="font-size: 7pt; border: 0; padding: 0 0 0 0; height: 13mm; width: 100mm; text-align: left; vertical-align: top;"></td>
                            </tr>
                        </table>
                    </div>
                    </div>
                    <div class="capture_div_row" style="text-align: center;">
                        <input type="button" class="buttons button_orange" id="btnPrint" tabindex="27" value="imprimir" onclick="printLabel();">
                        <input type="button" class="buttons button_green" id="btnLastFinish" tabindex="28" style="display: none;" value="terminar" onclick="location.reload();">
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div id="divModalBackground">
        <div id="divModalMain">
            <div id="divModalClose"><label id="lblModalClose" onclick="closeModal(true);">&#10006</label></div>
            <div id="divModalTitle">Captura de Seriales</div>
            <div id="divModalForm">
                <label class="capture_label">Número de Parte</label>
                <label id="lblSerialesNoParte" style="display: inline; font-weight: bold;"></label>
                <br /><br />
                <label class="capture_label">Capturados</label>
                <label id="lblSerialesContador" style="display: inline; margin: 0 auto 0 auto; font-weight: bold; "></label>
                /
                <label id="lblSerialesCantidad" style="display: inline; margin: 0 auto 0 auto; ">10</label>
                <br /><br />
                <input id="txtSerialNumber" type="text" value="" tabindex="0" class="capture_input_text" style="width: calc( 100% - 12px ); text-align: center">
                <br /><br />
                <div id="divSerialesContainer" style="background-color: #FFFFFF; text-align: center; border: 1px #EAEAEA solid; border-radius: 5px; padding: 10px 10px 10px 10px; margin-bottom: 8px; height: 200px; overflow-x: hidden; overflow-y: scroll ; text-align: left"></div>
            </div>
            <div id="divModalError"></div>
            <div id="divModalButtons">
                <input id="btnCaptureSerials" type="button" value="terminar" tabindex="1" onclick="closeModal(false)" class="buttons button_green" style="display: none">
                <input type="button" value="cancelar" onclick="closeModal(true);" tabindex="2" class="buttons button_red">
            </div>
            <div id="divModalWorking">
                <img src="../../images/wait_48.gif" />
            </div>
        </div>
    </div>
    <div id="divWorkingBackground">
        <div id="divWorking">
            <img src="../../images/wait_64.gif"/>
        </div>
    </div>
    <script src="../../js/jquery-1.11.3.min.js"></script>
    <script src="../../js/jquery.auto-complete.js"></script>
    <script src="../../js/jquery.numeric.js"></script>
    <script src="../../js/jquery-barcode.min.js"></script>
    <script src="../../js/printThis.js"></script>
    <script src="javascript.js"></script>
    </body >
</html >
<?php
unset($objScrap);
?>