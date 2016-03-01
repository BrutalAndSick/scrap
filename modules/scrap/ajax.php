<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess) {
    case 'updateGrid':
        $jsnData = array('grid' => '', 'pagination' => '', 'intSqlNumberOfRecords' => 0);
        if($_REQUEST['intSqlScrapNumber']!=''){
            $strSql = "SELECT SCR_SCRAP.SCR_ID AS ID, SCR_SCRAP_STATUS.SCR_DATE AS DTE, SCR_SCRAP_STATUS.SCR_TIME AS TME, SCR_SCRAP.SCR_COST AS AMT, SCR_SCRAP.SCR_COUNTRY AS CNT, SCR_SCRAP.SCR_PLANT AS PLN, SCR_SCRAP.SCR_SHIP AS SHP, SCR_SCRAP.SCR_DIVISION AS DVS, SCR_SCRAP.SCR_SEGMENT AS SGM, SCR_SCRAP.SCR_PROFITCENTER AS PRF, SCR_SCRAP.SCR_APD AS APD, SCR_SCRAP.SCR_COSTCENTER AS CST, SCR_SCRAP.SCR_AREA AS ARE, SCR_SCRAP.SCR_STATION AS STT, SCR_SCRAP.SCR_LINE AS LIN, SCR_SCRAP.SCR_FAULT AS FLT, SCR_SCRAP.SCR_CAUSE AS CAS, SCR_SCRAP.SCR_SCRAPCODE AS SCD, SCR_SCRAP.SCR_PROJECT AS PRJ ";
            $strSql .= "FROM SCR_SCRAP ";
            $strSql .= "JOIN SCR_SCRAP_STATUS ON SCR_SCRAP_STATUS.SCR_SCRAP = SCR_SCRAP.SCR_ID AND SCR_SCRAP_STATUS.SCR_STATUS = 0 ";
            $strSql .= "WHERE SCR_SCRAP.SCR_ID = " . $_REQUEST['intSqlScrapNumber'];
        }else{
            if($_REQUEST['intSqlSerial']!=''){
                $strSql = "SELECT SCR_SCRAP.SCR_ID AS ID, SCR_SCRAP_STATUS.SCR_DATE AS DTE, SCR_SCRAP_STATUS.SCR_TIME AS TME, SCR_SCRAP.SCR_COST AS AMT, SCR_SCRAP.SCR_COUNTRY AS CNT, SCR_SCRAP.SCR_PLANT AS PLN, SCR_SCRAP.SCR_SHIP AS SHP, SCR_SCRAP.SCR_DIVISION AS DVS, SCR_SCRAP.SCR_SEGMENT AS SGM, SCR_SCRAP.SCR_PROFITCENTER AS PRF, SCR_SCRAP.SCR_APD AS APD, SCR_SCRAP.SCR_COSTCENTER AS CST, SCR_SCRAP.SCR_AREA AS ARE, SCR_SCRAP.SCR_STATION AS STT, SCR_SCRAP.SCR_LINE AS LIN, SCR_SCRAP.SCR_FAULT AS FLT, SCR_SCRAP.SCR_CAUSE AS CAS, SCR_SCRAP.SCR_SCRAPCODE AS SCD, SCR_SCRAP.SCR_PROJECT AS PRJ ";
                $strSql .= "FROM SCR_SCRAP ";
                $strSql .= "JOIN SCR_SCRAP_STATUS ON SCR_SCRAP_STATUS.SCR_SCRAP = SCR_SCRAP.SCR_ID AND SCR_SCRAP_STATUS.SCR_STATUS = 0 ";
                $strSql .= "WHERE SCR_SCRAP.SCR_ID IN (SELECT SCR_SCRAP_PART_SERIAL.SCR_SCRAP FROM SCR_SCRAP_PART_SERIAL WHERE UPPER(SCR_SCRAP_PART_SERIAL.SCR_SERIAL) = '" . strtoupper($_REQUEST['intSqlSerial']) . "') ";
                $strSql .= "ORDER BY ID DESC";
            }else{
                $strSql = "SELECT SCR_SCRAP.SCR_ID AS ID, SCR_SCRAP_STATUS.SCR_DATE AS DTE, SCR_SCRAP_STATUS.SCR_TIME AS TME, SCR_SCRAP.SCR_COST AS AMT, SCR_SCRAP.SCR_COUNTRY AS CNT, SCR_SCRAP.SCR_PLANT AS PLN, SCR_SCRAP.SCR_SHIP AS SHP, SCR_SCRAP.SCR_DIVISION AS DVS, SCR_SCRAP.SCR_SEGMENT AS SGM, SCR_SCRAP.SCR_PROFITCENTER AS PRF, SCR_SCRAP.SCR_APD AS APD, SCR_SCRAP.SCR_COSTCENTER AS CST, SCR_SCRAP.SCR_AREA AS ARE, SCR_SCRAP.SCR_STATION AS STT, SCR_SCRAP.SCR_LINE AS LIN, SCR_SCRAP.SCR_FAULT AS FLT, SCR_SCRAP.SCR_CAUSE AS CAS, SCR_SCRAP.SCR_SCRAPCODE AS SCD, SCR_SCRAP.SCR_PROJECT AS PRJ ";
                $strSql .= "FROM SCR_SCRAP ";
                $strSql .= "JOIN SCR_SCRAP_STATUS ON SCR_SCRAP_STATUS.SCR_SCRAP = SCR_SCRAP.SCR_ID AND SCR_SCRAP_STATUS.SCR_STATUS = 0 AND SCR_SCRAP_STATUS.SCR_DATE >= " . $_REQUEST['intDateFrom'] . " AND SCR_SCRAP_STATUS.SCR_DATE <= " . $_REQUEST['intDateTo'] . " ";
                $strSql .= $_REQUEST['strSqlWhere'];
                $strSql .= "ORDER BY " . $_REQUEST['strSqlOrder'];
            }
        }

        $rstData = $objScrap->dbQuery($strSql);
        $intGridNumberOfRecords = $objScrap->intAffectedRows;
        if ($intGridNumberOfRecords != 0) {
            $intPages = ceil($intGridNumberOfRecords / $_REQUEST['intSqlLimit']);
        } else {
            $intPages = 1;
        }
        $intFirstRecord = ($_REQUEST['intSqlLimit'] * $_REQUEST['intSqlPage']) - $_REQUEST['intSqlLimit'];
        $intLastRecord = $intFirstRecord + $_REQUEST['intSqlLimit'] - 1;
        $strGrid = '';
        if ($intGridNumberOfRecords != 0) {
            for ($intIndex = $intFirstRecord; $intIndex <= $intLastRecord; $intIndex++) {
                //$strGrid .= '<tr id="trGrid_' . $rstData[$intIndex][$objScrap->strTableIdField] . '">';
                $strGrid .= '<tr>';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . substr($rstData[$intIndex]['DTE'],6,2) . '-' . substr($rstData[$intIndex]['DTE'],4,2) . '-' . substr($rstData[$intIndex]['DTE'],0,4) . '</td>';

                switch(strlen($rstData[$intIndex]['TME'])){
                    case 4:
                        $strGrid .= '<td class="tdGrid" style="text-align: right;">' . '00' . ':' . substr($rstData[$intIndex]['TME'],0,2) . ':' . substr($rstData[$intIndex]['TME'],2,2) . '</td>';
                        break;
                    case 5:
                        $strGrid .= '<td class="tdGrid" style="text-align: right;">' . '0' . substr($rstData[$intIndex]['TME'],0,1) . ':' . substr($rstData[$intIndex]['TME'],1,2) . ':' . substr($rstData[$intIndex]['TME'],3,2) . '</td>';
                        break;
                    case 6:
                        $strGrid .= '<td class="tdGrid" style="text-align: right;">' . substr($rstData[$intIndex]['TME'],0,2) . ':' . substr($rstData[$intIndex]['TME'],2,2) . ':' . substr($rstData[$intIndex]['TME'],4,2) . '</td>';
                        break;
                }
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . number_format($rstData[$intIndex]['AMT'],2,'.',',') . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['CNT'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['PLN'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['SHP'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['DVS'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['SGM'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['PRF'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . $rstData[$intIndex]['APD'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['ARE'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['STT'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['LIN'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['FLT'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['CAS'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['SCD'],0,12) . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left;">' . substr($rstData[$intIndex]['PRJ'],0,12) . '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intGridNumberOfRecords - 1)) {
                    break;
                }
            };
        } else {
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="19">No existen registros</td></tr>';
        }
        $strGridPagination = '<div style="margin-bottom: 2px;">';
        if ($_REQUEST['intSqlPage'] != 1) {
            $strGridPagination .= '<label class="labelPagination" onclick="gridPagination(1)" title="Inicio">&#8920;</label>';
            $strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($_REQUEST['intSqlPage'] - 1) . ')" title="Anterior">&#8810</label>';
        } else {
            $strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Inicio">&#8920;</label>';
            $strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Anterior">&#8810</label>';
        }
        for ($intPage = 1; $intPage <= $intPages; $intPage++) {
            if ($intPage == $_REQUEST['intSqlPage']) {
                $strGridPagination .= '<label class="labelPagination labelPaginationCurrent">' . $intPage . '</label>';
            } else {
                $strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPage . ')">' . $intPage . '</label>';
            }
        }
        if ($_REQUEST['intSqlPage'] != $intPages) {
            $strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($_REQUEST['intSqlPage'] + 1) . ')" title="Siguiente">&#8811</label>';
            $strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPages . ')" title="Final">&#8921</label>';
        } else {
            $strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Siguiente">&#8811</label>';
            $strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Final">&#8921</label>';
        }
        $strGridPagination .= '</div>';
        $strGridPagination .= '<b>' . $intGridNumberOfRecords . '</b> Registro';
        if ($intGridNumberOfRecords > 1) {
            $strGridPagination .= 's';
        }
        $strGridPagination .= ' - ';
        $strGridPagination .= '<b>' . $intPages . '</b> Página';
        if ($intPages > 1) {
            $strGridPagination .= 's';
        }
        $strGridPagination .= ' - ';
        if ($intGridNumberOfRecords != 0) {
            $strGridPagination .= '<select onchange="gridRecords(this.value);">';
            for ($intPageCount = 100; $intPageCount <= 500; $intPageCount = $intPageCount + 100) {
                $strGridPagination .= '<option value="' . $intPageCount . '"';
                if ($_REQUEST['intSqlLimit'] == $intPageCount) {
                    $strGridPagination .= ' selected="selected"';
                }
                $strGridPagination .= '>' . $intPageCount . '</option>';
            }
            $strGridPagination .= '</select>';
        } else {
            $strGridPagination .= '<select>';
            $strGridPagination .= '<option value="0">0</option>';
            $strGridPagination .= '</select>';
        }
        $strGridPagination .= ' Registros por página';
        unset($rstData);

        $jsnData['grid'] = $strGrid;
        $jsnData['pagination'] = $strGridPagination;
        $jsnData['intSqlNumberOfRecords'] = $intGridNumberOfRecords;
        break;
};
unset($objScrap);
echo json_encode($jsnData);

function stripAccents($strString) {
    return strtr(utf8_decode($strString), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}
?>