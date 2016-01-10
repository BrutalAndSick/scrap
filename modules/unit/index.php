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
            Catálogo de Unidades
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
                array_push($arrHeaders,array('strLabel'=>'Id','strSortColumn'=>'UNT_ID'));
                array_push($arrHeaders,array('strLabel'=>'Unidad','strSortColumn'=>'UNT_NAME'));
                array_push($arrHeaders,array('strLabel'=>'Símbolo','strSortColumn'=>'UNT_SYMBOL'));
                array_push($arrHeaders,array('strLabel'=>'Estatus','strSortColumn'=>'UNT_STATUS'));
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
                            <td><label for="txtName" class="form_label" style="width: 88px;">Unidad</label></td>
                            <td><input type="text" id="txtName" class="form_input_text" style="width: 150px;" value="" /></td>
                        </tr>
                        <tr>
                            <td><label for="txtCode" class="form_label" style="width: 88px;">Símbolo</label></td>
                            <td><input type="text" id="txtCode" class="form_input_text" style="width: 150px;" value="" /></td>
                        </tr>
                    </table>
                </div>
                <!-- ##### FORMULARIO A APLICAR ##### -->
                <div id="divModalError"></div>
                <div id="divModalButtons">
                    <input id="btnModalAdd" type="button" value="insertar" onclick="addUnit();" class="buttons button_green">
                    <input id="btnModalEdit" type="button" value="editar" onclick="updateUnit();" class="buttons button_green">
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