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
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
    <div class=" divTitles ">Alertas</div>
    <div class="divActions">
        <input id="btnInsertRecord" type="button" class="buttons button_orange" value="crear alerta" onclick="showModal();">
        <input id="btnInsertRecord" type="button" class="buttons button_orange" value="refrescar" onclick="refreshAlerts();">
    </div>
    <table style="width: calc( 100% - 10px); height: calc( 100% - 92px ); background-color: #EB1414; border-collapse: collapse; border-spacing: 0; margin: 0 5px 0 5px">
        <tr style="height: 100%">
            <td style="background-color: #DCDCDC; padding: 10px 10px 10px 10px; width: 200px; vertical-align: top; height: 100%; overflow-x: hidden; overflow-y: scroll " id="tdAlertsContainer">
            </td>
            <td style="background-color: #FFFFFF; padding: 10px 10px 10px 10px; width: calc(100% - 200px); vertical-align: top; height: 100%; overflow-x: hidden; overflow-y: scroll " id="tdAlertsList">
                <table id="tblAlertHeader" class="tblAlertHeader">
                    <thead class="theadAlert">
                    <tr>
                        <th class="thAlert" style="width:50px">Scrap</th>
                        <th class="thAlert" style="width:66px">Fecha</th>
                        <th class="thAlert" style="width:50px">Costo</th>
                        <th class="thAlert" style="width:150px">Proyecto</th>
                        <th class="thAlert" style="width:49px">Pa&iacute;s</th>
                        <th class="thAlert" style="width:80px">Planta</th>
                        <th class="thAlert" style="width:33px">Nave</th>
                        <th class="thAlert" style="width:49px">Divisi&oacute;n</th>
                        <th class="thAlert" style="width:61px">Segmento</th>
                        <th class="thAlert" style="width:76px">Profit Center</th>
                        <th class="thAlert" style="width:30px">APD</th>
                        <th class="thAlert" style="width:120px">&Aacute;rea</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyAlert" class="tbodyAlert"></tbody>
                </table>
            </td>
        </tr>
    </table>
    <div id="divModalBackground">
        <div id="divModalMain">
            <div id="divModalClose"><label id="lblModalClose" onclick="closeModal();">&#10006</label></div>
            <div id="divModalTitle">Crear alertas</div>
            <div id="divModalForm">
                <div class="capture_div_row" style="" id="divAlert"><label for="txtAlert" class="capture_label">Alerta</label><input type="text" id="txtAlert" tabindex="0" class="capture_input_text" value="" /></div>
                <div class="capture_div_row" style="" id="divCost"><label for="txtCost" class="capture_label">Costo</label><input type="number" id="txtCost" tabindex="1" min="0" class="capture_input_text" value="0" onchange="validateCost();" /></div>
                <div class="capture_div_row" style="" id="divCountry"><label for="selCountry" class="capture_label">País</label><select id="selCountry" onchange="getData('Plant');" tabindex="2" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divPlant"><label for="selPlant" class="capture_label">Planta</label><select id="selPlant" onchange="getData('Ship');" tabindex="3" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divShip"><label for="selShip" class="capture_label">Nave</label><select id="selShip" onchange="getData('Division');" tabindex="4" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divDivision"><label for="selDivision" class="capture_label">División</label><select id="selDivision" onchange="getData('Segment');" tabindex="5" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divSegment"><label for="selSegment" class="capture_label">Segmento</label><select id="selSegment" onchange="getData('ProfitCenter');" tabindex="6" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divProfitCenter"><label for="selProfitCenter" class="capture_label">Profit Center</label><select id="selProfitCenter" onchange="getData('APD');" tabindex="7" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divAPD"><label for="selAPD" class="capture_label">APD</label><select id="selAPD" onchange="getData('Area');" tabindex="8" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divArea"><label for="selArea" class="capture_label">Área</label><select id="selArea" onchange="getData('Station');" tabindex="9" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divStation"><label for="selStation" class="capture_label">Tecnología</label><select id="selStation" onchange="getData('Line');" tabindex="10" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divLine"><label for="selLine" class="capture_label">Línea</label><select id="selLine" onchange="getData('Fault');" tabindex="11" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divFault"><label for="selFault" class="capture_label">Defecto</label><select id="selFault" onchange="getData('Cause');" tabindex="12" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divCause"><label for="selCause" class="capture_label">Causa</label><select id="selCause" onchange="getData('ScrapCode');" tabindex="13" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divScrapCode"><label for="selScrapCode" class="capture_label">Código de Scrap</label><select id="selScrapCode" onchange="getData('Project');" tabindex="14" class="capture_input_select"></select></div>
                <div class="capture_div_row" style="display:none" id="divProject"><label for="selProject" class="capture_label">Proyecto</label><select id="selProject" tabindex="15" class="capture_input_select"></select></div>
            </div>
            <div id="divModalError"></div>
            <div id="divModalButtons">
                <input id="btnModalSubmitRecord" type="button" value="crear" onclick="createAlert();" class="buttons button_green" tabindex="16">
                <input type="button" value="cancelar" onclick="closeModal();" class="buttons button_red" tabindex="17">
            </div>
            <div id="divModalWorking">
                <img src="../../images/wait_48.gif" />
            </div>
        </div>
    </div>
    <div id="divAlertBackground">
        <div id="divAlertMain">
            <div id="divAlertClose"><label id="lblModalClose" onclick="closeAlert();">&#10006</label></div>
            <div id="divAlertTitle">Scrap <label id="lblScrapFolio"></label></div>
            <div id="divAlertForm"></div>
            <div id="divAlertError"></div>
            <div id="divAlertButtons">
                <input id="btnAlertSubmitRecord" type="button" value="remover de alertas" onclick="updateAlert();" class="buttons button_green" tabindex="16">
                <input type="button" value="cerrar" onclick="closeAlert();" class="buttons button_red" tabindex="17">
            </div>
            <div id="divModalWorking">
                <img src="../../images/wait_48.gif" />
            </div>
        </div>
    </div>
    <div id="divWorkingBackground">
        <div id="divWorking">
            <img src="../../images/wait_64.gif" />
        </div>
    </div>
    <script src="../../js/jquery-1.11.3.min.js"></script>
    <script src="javascript.js"></script>
    <script>
        $('document').ready(function(){
            $intCurrentAlert = -1;
            getAlertList();
        })
    </script>
    </body>
    </html>
<?php
unset($objScrap);
?>