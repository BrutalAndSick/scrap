<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
$objScrap->intTableId = $_GET['intTableId'];
$objScrap->getTableData();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
        <div class=" divTitles ">Catálogo de <?php echo $objScrap->strGridTitle; ?></div>
        <div class="divActions">
            <input id="btnInsertRecord" type="button" class="buttons button_orange" value="insertar" onclick="showModal(0);">
            <?php if($objScrap->intTableId!=19 && $objScrap->intTableId!=20) {?><input id="btnInsertRecord" type="button" class="buttons button_excel" value="importar" onclick="showImport();"><?php } ?>
        </div>
        <div class="divGrid" id="divGrid">
            <table class="tblGrid">
                <!-- ##### BEGIN: GRID HEADER ##### -->
                <thead id="theadGrid" class="theadGrid"><?php echo $objScrap->strGridHeader; ?></thead>
                <!-- ##### END: GRID HEADER ##### -->
                <!-- ##### BEGIN: GRID BODY ##### -->
                <tbody id="tbodyGrid" class="tbodyGrid"></tbody>
                <!-- ##### END: GRID BODY ##### -->
            </table>
        </div>
        <div id="divPagination" class="divPagination">
        </div>
        <div id="divModalBackground">
            <div id="divModalMain">
                <div id="divModalClose"><label id="lblModalClose" onclick="closeModal();">&#10006</label></div>
                <div id="divModalTitle"></div>
                <!-- ##### BEGIN: FORMULARIO A APLICAR ##### -->
                <div id="divModalForm"><?php echo $objScrap->strGridForm; ?></div>
                <!-- ##### END: FORMULARIO A APLICAR ##### -->
                <div id="divModalError"></div>
                <div id="divModalButtons">
                    <input id="btnModalSubmitRecord" type="button" value="" onclick="submitRecord();" class="buttons button_green">
                    <input type="button" value="cancelar" onclick="closeModal();" class="buttons button_red">
                </div>
                <div id="divModalWorking">
                    <img src="../../images/wait_48.gif" />
                </div>
            </div>
        </div>
        <div id="divImportBackground">
            <div id="divImportMain">
                <div id="divImportClose"><label id="lblImportClose" onclick="closeImport();">&#10006</label></div>
                <div id="divImportTitle">Importar Catálogo de <?php echo $objScrap->strGridTitle; ?> desde Excel</div>
                <div id="divImportFile">
                    Descargar Plantilla <a id="ancImportTemplate" href="" target="_blank"></a>
                    <br /><br />
                    <input class="form_input_text" type="file" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" id="fleImportFile" onchange="validateExcelFile();">
                </div>
                <div id="divImportResults"></div>
                <div id="divImportButtons">
                    <input type="button" value="cancelar" onclick="closeImport();" class="buttons button_red">
                </div>
                <div id="divImportWorking">
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
        <?php
        if($objScrap->strIncludeJS!=''){
            ?>
            <script src="<?php echo $objScrap->strIncludeJS; ?>"></script>
            <?php
        }
        ?>
        <script src="javascript.js"></script>
        <script>
            $('document').ready(function(){
                $jsnGridData.intTableId = <?php echo $objScrap->intTableId; ?>;
                $jsnGridData.strSql = "<?php echo $objScrap->strGridSql; ?>";
                $jsnGridData.strSqlOrder = "<?php echo $objScrap->strGridSqlOrder; ?>";
                $jsnGridData.intSqlNumberOfColumns = "<?php echo $objScrap->intGridNumberOfColumns; ?>";
                $jsnGridData.arrFormField = <?php echo $objScrap->arrFormField; ?>;
                $jsnGridData.arrTableRelation = <?php echo $objScrap->arrTableRelation; ?>;
                gridUpdate();
            })
        </script>
    </body>
</html>
<?php
unset($objScrap);
?>