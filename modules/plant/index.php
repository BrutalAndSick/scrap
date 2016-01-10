<?php
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
        <link rel="stylesheet" type="text/css" href="../../lib/scrap_grid/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="../../lib/scrap_modal/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="../../lib/scrap_form/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
        <div class=" divTitles ">
            Catálogo de Plantas
        </div>
        <div class="divActions">
            <input id="btnProfile" type="button" class="buttons button_orange" value="insertar" onclick="showModal(0);">
        </div>
        <div class="divGrid" id="divGrid">
            <table class="tblGrid">
                <!-- ##### HEADERS DEL GRID ##### -->
                <thead id="theadGrid" class="theadGrid">
                <?php
                require_once('../../lib/scrap_grid/class.php');
                $objGrid = new clsGrid();
                $arrHeaders = array();
                array_push($arrHeaders,array('strLabel'=>'Id','strSortColumn'=>'PLN_ID'));
                array_push($arrHeaders,array('strLabel'=>'Planta','strSortColumn'=>'PLN_NAME'));
                array_push($arrHeaders,array('strLabel'=>'Pais','strSortColumn'=>'PLN_COUNTRY'));
                array_push($arrHeaders,array('strLabel'=>'Número','strSortColumn'=>'PLN_NUMBER'));
                array_push($arrHeaders,array('strLabel'=>'Estatus','strSortColumn'=>'PLN_STATUS'));
                array_push($arrHeaders,array('strLabel'=>'Editar','strSortColumn'=>''));
                echo $objGrid->gridHeaders($arrHeaders);
                unset($objGrid);
                ?>
                </thead>
                <!-- ##### HEADERS DEL GRID ##### -->
                <tbody id="tbodyGrid" class="tbodyGrid">
                </tbody>
            </table>
        </div>
        <div id="divPagination" class="divPagination">
        </div>
        <div id="divModalBackground">
            <div id="divModalMain" style="width: 300px;">
                <div id="divModalClose"><label id="lblModalClose" onclick="closeModal();">&#10006</label></div>
                <div id="divModalTitle"></div>
                <!-- ##### FORMULARIO A APLICAR ##### -->
                <div id="divModalForm">
                    <table>
                        <tr>
                            <td><label for="txtName" class="form_label" style="width: 88px;">Planta</label></td>
                            <td><input type="text" id="txtName" class="form_input_text" style="width: 150px;" value="" /></td>
                        </tr>
                        <tr>
                            <td><label for="selCountry" class="form_label" style="width: 88px;">Pais</label></td>
                            <td>
                                <select id="selCountry" class="form_input_select" style="width: 150px;"></select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="numNumber" class="form_label" style="width: 88px;">Número</label></td>
                            <td><input type="number" id="numNumber" min="1" max="99" class="form_input_text" size="2" /></td>
                        </tr>
                    </table>
                </div>
                <!-- ##### FORMULARIO A APLICAR ##### -->
                <div id="divModalError"></div>
                <div id="divModalButtons">
                    <input id="btnModalAdd" type="button" value="insertar" onclick="addPlant();" class="buttons button_green">
                    <input id="btnModalEdit" type="button" value="editar" onclick="updatePlant();" class="buttons button_green">
                    <input type="button" value="cancelar" onclick="closeModal();" class="buttons button_red">
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
        <script src="../../lib/scrap_grid/javascript.js"></script>
        <script src="javascript.js"></script>
    </body>
</html>
<?php
unset($objScrap);
?>