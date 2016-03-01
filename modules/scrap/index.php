<?php
require_once('../../include/config.php');
require_once(LIB_PATH . 'scrap.php');
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
    <div class=" divTitles ">Consulta de Scrap</div>
    <div class=" divFilters ">
        <table style="margin: 0 auto 0 auto; border-spacing: 8px">
            <tr>
                <td style="vertical-align: top; border: 0; border-radius: 5px; padding: 3px 5px 3px 5px; background-color: #EFEFEF;">
                    <table class="tblFileters">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Año</th>
                            <th>-</th>
                            <th>Mes</th>
                            <th>-</th>
                            <th>Día</th>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Desde</td>
                            <td><input type="number" class="form_input_text" min="2011" max="<?php echo date("Y"); ?>"
                                       style="width: 50px" id="txtFromYear" value="<?php echo date("Y"); ?>"></td>
                            <td>-</td>
                            <td><input type="number" class="form_input_text" min="1" max="12" style="width: 35px"
                                       id="txtFromMonth" value="<?php echo date("n"); ?>"></td>
                            <td>-</td>
                            <td><input type="number" class="form_input_text" min="1" max="31" style="width: 35px"
                                       id="txtFromDay" value="<?php echo date("j"); ?>"></td>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Año</th>
                            <th>-</th>
                            <th>Mes</th>
                            <th>-</th>
                            <th>Día</th>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Hasta</td>
                            <td><input type="number" class="form_input_text" min="2011" max="<?php echo date("Y"); ?>"
                                       style="width: 50px" id="txtToYear" value="<?php echo date("Y"); ?>"></td>
                            <td>-</td>
                            <td><input type="number" class="form_input_text" min="1" max="12" style="width: 35px"
                                       id="txtToMonth" value="<?php echo date("n"); ?>"></td>
                            <td>-</td>
                            <td><input type="number" class="form_input_text" min="1" max="31" style="width: 35px"
                                       id="txtToDay" value="<?php echo date("j"); ?>"></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top; border: 0; border-radius: 5px; padding: 3px 5px 3px 5px; background-color: #EFEFEF;">
                    <table class="tblFileters">
                        <tr style="font-weight: bold">
                            <td>País</td>
                            <td>Planta</td>
                            <td>Nave</td>
                            <td>División</td>
                            <td>Segmento</td>
                            <td>Profit Center</td>
                            <td>APD</td>
                        </tr>
                        <tr>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selCNT">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_COUNTRY) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selPLN">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_PLANT) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selSHP">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_SHIP) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selDVS">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_DIVISION) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selSGM">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_SEGMENT) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selPRF">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_PROFITCENTER) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form_input_select" style="width: 100px" id="selAPD">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_APD) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td>Área</td>
                            <td>Estación</td>
                            <td>Línea</td>
                            <td>Defecto</td>
                            <td>Causa</td>
                            <td>Código</td>
                            <td>Proyecto</td>
                        </tr>
                        <tr>
                            <td><select class="form_input_select" style="width: 100px" id="selARE">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_AREA) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selSTT">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_STATION) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selLIN">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_LINE) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selFLT">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_FAULT) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selCAS">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_CAUSE) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selSCD">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_SCRAPCODE) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                            <td><select class="form_input_select" style="width: 100px" id="selPRJ">
                                    <option value="-1">-selecciona-</option>
                                    <?php
                                    $strSql = "SELECT DISTINCT(SCR_PROJECT) AS FIELD FROM SCR_SCRAP ORDER BY FIELD";
                                    $rstFilter = $objScrap->dbQuery($strSql);
                                    foreach ($rstFilter as $objFilter) {
                                        ?>
                                        <option
                                            value="<?php echo $objFilter['FIELD']; ?>"><?php echo $objFilter['FIELD']; ?></option>
                                        <?php
                                    }
                                    unset($rstFilter);
                                    ?>
                                </select></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top; border: 0; border-radius: 5px; padding: 3px 5px 3px 5px; background-color: #EFEFEF;">
                    <table class="tblFileters">
                        <tr>
                            <th>Folio</th>
                        </tr>
                        <tr>
                            <td><input type="text" class="form_input_text" style="width: 100px" id="txtScrapNumber"
                                       value=""></td>
                        </tr>
                        <tr>
                            <th>Serial</th>
                        </tr>
                        <tr>
                            <td><input type="text" class="form_input_text" style="width: 100px" id="txtSerial" value="">
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: middle; border: 0; border-radius: 5px; padding: 3px 5px 3px 5px; background-color: #EFEFEF;">
                    <input type="button" class="buttons button_orange" value="consultar" onclick="gridFilter();">
                </td>
            </tr>
        </table>


    </div>
    <div class="divGrid" id="divGrid">
        <table id="tblGrid" class="tblGrid">
            <!-- ##### BEGIN: GRID HEADER ##### -->
            <thead id="theadGrid" class="theadGrid">

            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Id</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Id ASC" onclick="gridSort('ID ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Id DESC" onclick="gridSort('ID DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Fecha</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Fecha ASC" onclick="gridSort('DTE ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Fecha DESC" onclick="gridSort('DTE DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Hora</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Hora ASC" onclick="gridSort('TME ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Hora DESC" onclick="gridSort('TME DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Costo</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Costo ASC" onclick="gridSort('AMT ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Costo DESC" onclick="gridSort('AMT DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">País</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="País ASC" onclick="gridSort('CNT ASC')">&#9650;</div>
                        <div class="divSortOrder" title="País DESC" onclick="gridSort('CNT DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Planta</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Planta ASC" onclick="gridSort('PLN ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Planta DESC" onclick="gridSort('PLN DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Nave</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Nave ASC" onclick="gridSort('SHP ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Nave DESC" onclick="gridSort('SHP DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Div.</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="División ASC" onclick="gridSort('DVS ASC')">&#9650;</div>
                        <div class="divSortOrder" title="División DESC" onclick="gridSort('DVS DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Seg.</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Segmento ASC" onclick="gridSort('SGM ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Segmento DESC" onclick="gridSort('SGM DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">P. Center</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Profit Center ASC" onclick="gridSort('PRF ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Profit Center DESC" onclick="gridSort('PRF DESC')">
                            &#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">APD</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="APD ASC" onclick="gridSort('APD ASC')">&#9650;</div>
                        <div class="divSortOrder" title="APD DESC" onclick="gridSort('APD DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Área</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Área ASC" onclick="gridSort('ARE ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Área DESC" onclick="gridSort('ARE DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Estación</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Estación ASC" onclick="gridSort('STT ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Estación DESC" onclick="gridSort('STT DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Línea</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Línea ASC" onclick="gridSort('LIN ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Línea DESC" onclick="gridSort('LIN DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Defecto</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Defecto ASC" onclick="gridSort('FLT ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Defecto DESC" onclick="gridSort('FLT DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Causa</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Causa ASC" onclick="gridSort('CAS ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Causa DESC" onclick="gridSort('CAS DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort">
                    <div class="divSortContainer divSortLabel">Código</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Código de Scrap ASC" onclick="gridSort('SCD ASC')">
                            &#9650;</div>
                        <div class="divSortOrder" title="Código de Scrap DESC" onclick="gridSort('SCD DESC')">
                            &#9660;</div>
                    </div>
                </div>
            </th>
            <th class="thGrid">
                <div class="divSort" style="">
                    <div class="divSortContainer divSortLabel">Proyecto</div>
                    <div class="divSortContainer">
                        <div class="divSortOrder" title="Proyecto ASC" onclick="gridSort('PRJ ASC')">&#9650;</div>
                        <div class="divSortOrder" title="Proyecto DESC" onclick="gridSort('PRJ DESC')">&#9660;</div>
                    </div>
                </div>
            </th>
            </thead>
            <!-- ##### END: GRID HEADER ##### -->
            <!-- ##### BEGIN: GRID BODY ##### -->
            <tbody id="tbodyGrid" class="tbodyGrid"></tbody>
            <!-- ##### END: GRID BODY ##### -->
        </table>
    </div>
    <div id="divPagination" class="divPagination">
    </div>
    <div id="divWorkingBackground">
        <div id="divWorking">
            <img src="../../images/wait_64.gif"/>
        </div>
    </div>
    <script src="../../js/jquery-1.11.3.min.js"></script>
    <script src="javascript.js"></script>
    <script>
        $('document').ready(function () {
            $jsnGridData.intDateFrom = <?php echo date("Y") . date("m") . date("d"); ?>;
            $jsnGridData.intDateTo = <?php echo date("Y") . date("m") . date("d"); ?>;
            $jsnGridData.strSqlWhere = "";
            $jsnGridData.strSqlOrder = " ID DESC";
            $jsnGridData.intSqlNumberOfColumns = "18";
            gridUpdate();
        })

        $('#txtFromYear').change(function () {
            if ($('#txtFromYear').val() < 2011) {
                $('#txtFromYear').val(2011);
            }
            if ($('#txtFromYear').val() ><?php echo date("Y"); ?>) {
                $('#txtFromYear').val(<?php echo date("Y"); ?>);
            }
        });

        $('#txtToYear').change(function () {
            if ($('#txtToYear').val() < 2011) {
                $('#txtToYear').val(2011);
            }
            if ($('#txtToYear').val() ><?php echo date("Y"); ?>) {
                $('#txtToYear').val(<?php echo date("Y"); ?>);
            }
        });


    </script>
    </body>
    </html>
<?php
unset($objScrap);
?>