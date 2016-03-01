<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
$objScrap->intTableId = $_REQUEST['intTableId'];
$objScrap->getTableData();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess) {
    case 'updateGrid':
        $jsnData = array('grid' => '', 'pagination' => '', 'intSqlNumberOfRecords' => 0);
        $objScrap->strGridSqlOrder = $_REQUEST['strSqlOrder'];
        $objScrap->intGridSqlPage = $_REQUEST['intSqlPage'];
        $objScrap->intGridSqlLimit = $_REQUEST['intSqlLimit'];
        $objScrap->updateGrid();
        $jsnData['grid'] = $objScrap->strGrid;
        $jsnData['pagination'] = $objScrap->strGridPagination;
        $jsnData['intSqlNumberOfRecords'] = $objScrap->intGridNumberOfRecords;
        break;
    case 'processRecord':
        $jsnData = array('blnGo' => true, 'strError' => '', 'strField' => '');
        $jsnForm = json_decode($objScrap->arrFormField, true);
        for ($intIndex = 0; $intIndex < count($jsnForm); $intIndex++) {
            if ($jsnForm[$intIndex]['TBL_DUPLICATE'] == 0) {
                $strSql = "SELECT COUNT(*) AS COUNT FROM " . $objScrap->strTableName;
                $strSql .= " WHERE " . $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "'";
                if ($_REQUEST['intRecordId'] != 0) {
                    $strSql .= " AND " . $objScrap->strTableIdField . " <> '" . $_REQUEST['intRecordId'] . "'";
                }
                $rstRecordCount = $objScrap->dbQuery($strSql);
                if ($objScrap->getProperty('strDBError') == '') {
                    if ($rstRecordCount[0]['COUNT'] != 0) {
                        $jsnData['blnGo'] = false;
                        $jsnData['strError'] = $jsnForm[$intIndex]['TBL_NAME'] . " <b>" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "</b> ya existe";
                        $jsnData['strField'] = $jsnForm[$intIndex]['TBL_FIELD'];
                        $intIndex = count($jsnForm);
                    }
                } else {
                    $jsnData['blnGo'] = false;
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    $intIndex = count($jsnForm);
                }
                unset($rstRecordCount);
            }
        }
        if ($jsnData['blnGo']) {
            if ($_REQUEST['intRecordId'] == 0) {
                $strSql = "INSERT INTO " . $objScrap->strTableName . "(";
                $strInsertFields = '';
                $strInsertValues = '';
                for ($intIndex = 0; $intIndex < count($jsnForm); $intIndex++) {
                    $strInsertFields .= $jsnForm[$intIndex]['TBL_FIELD'] . ",";
                    if ($jsnForm[$intIndex]['TBL_TYPE'] == 'I') {
                        $strInsertValues .= "'" . $_REQUEST['PRT_NUMBER'] . ".jpg',";
                        rename("c:\\wamp\\www\\images\\parts\\" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']],"c:\\wamp\\www\\images\\parts\\" . $_REQUEST['PRT_NUMBER'] . ".jpg");
                    } else {
                        $strInsertValues .= "'" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "',";
                    }
                }
                $strSql .= substr($strInsertFields, 0, strlen($strInsertFields) - 1) . ") VALUES(" . substr($strInsertValues, 0, strlen($strInsertValues) - 1) . ") RETURNING " . $objScrap->strTableIdField . " INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                $intRecordId = $objScrap->getProperty('intLastInsertId');
            } else {
                $intRecordId = $_REQUEST['intRecordId'];
                $strSql = "UPDATE " . $objScrap->strTableName . " SET ";
                for ($intIndex = 0; $intIndex < count($jsnForm); $intIndex++) {
                    $strSql .= $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "', ";
                }
                $strSql = substr($strSql, 0, strlen($strSql) - 2) . " WHERE " . $objScrap->strTableIdField . " = " . $intRecordId;
                $objScrap->dbUpdate($strSql);
            };
            $jsnRelation = json_decode($objScrap->arrTableRelation, true);
            foreach($jsnRelation as $intRelationIndex=>$objRelation){
                $strSql = relationTable($objRelation['TBL_NAME'],"UPDATE ||strTable|| SET ||strField_Status|| = 0 WHERE ||strField_0|| = " . $intRecordId);
                $objScrap->dbUpdate($strSql);
                $arrRelationIds = explode("|",$_REQUEST[$objRelation['TBL_NAME']]);
                array_splice($arrRelationIds, count($arrRelationIds) - 1);
                foreach($arrRelationIds as $objRelationIds){
                    $strSql = relationTable($objRelation['TBL_NAME'],"INSERT INTO ||strTable||(||strField_0||,||strField_1||) VALUES(" . $intRecordId . "," . $objRelationIds . ")");
                    $objScrap->dbInsert($strSql);
                    $strSql = relationTable($objRelation['TBL_NAME'],"UPDATE ||strTable|| SET ||strField_Status|| = 1 WHERE ||strField_0|| = " . $intRecordId . " AND ||strField_1|| = " . $objRelationIds);
                    $objScrap->dbUpdate($strSql);
                }
                unset($objRelationIds);
            }
            unset($objRelation);
            unset($intRelationIndex);
            unset($jsnRelation);
        }
        break;
    case 'deactivateRecord':
        $jsnData = array('blnGo' => 'true', 'strError' => '');
        $strSql = "UPDATE " . $objScrap->strTableName . " SET " . $objScrap->strTableStatusField . " = " . $_REQUEST['intStatus'] . " WHERE " . $objScrap->strTableIdField . " = " . $_REQUEST['intRecordId'];
        $objScrap->dbUpdate($strSql);
        if ($objScrap->getProperty('strDBError') != '') {
            $jsnData['blnGo'] = false;
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'generateImportTemplate':
        $jsnData = array('strTemplateFile'=>'');
        require_once('../../lib/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $strFileName = 'template/Scrap_' . stripAccents($objScrap->strGridTitle) . '.xlsx';
        $arrStyle = array('font'=>array('bold'=>true));
        if(file_exists($strFileName)){
            unlink($strFileName);
        }
        $arrFields = json_decode($objScrap->arrFormField,true);
        foreach($arrFields as $intFieldIndex=>$objField){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intFieldIndex,1, $objField['TBL_NAME']);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($arrStyle);
        $intFieldIndex = $intFieldIndex;
        unset($objField);
        unset($arrFields);
        $objPHPExcel->getActiveSheet()->setTitle($objScrap->strGridTitle);
        $arrRelation = json_decode($objScrap->arrTableRelation, true);
        if(count($arrRelation)>0){
            foreach($arrRelation as $intRelationIndex=>$objRelation){
                $intFieldIndex = $intFieldIndex + 1;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intFieldIndex,1, $objRelation['TBL_DISPLAY']);
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(0,1, $objRelation['TBL_DISPLAY']);
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($arrStyle);
                switch($objRelation['TBL_NAME']){
                    case 'PLANT_COUNTRY':
                        $strRelationSql = "SELECT CNT_COUNTRY.CNT_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME AS FIELD_NAME FROM CNT_COUNTRY WHERE CNT_STATUS = 1";
                        break;
                    case 'SHIP_PLANT':
                        $strRelationSql = "SELECT PLN_PLANT_RELATION.PLN_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME AS FIELD_NAME FROM PLN_PLANT, CNT_COUNTRY, PLN_PLANT_RELATION WHERE PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'DIVISION_SHIP':
                        $strRelationSql = "SELECT SHP_SHIP_RELATION.SHP_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME AS FIELD_NAME FROM SHP_SHIP_RELATION, PLN_PLANT_RELATION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'SEGMENT_DIVISION':
                        $strRelationSql = "SELECT DVS_DIVISION_RELATION.DVS_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME AS FIELD_NAME FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'PROFITCENTER_SEGMENT':
                        $strRelationSql = "SELECT SGM_SEGMENT_RELATION.SGM_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME AS FIELD_NAME FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'APD_SEGMENT':
                        $strRelationSql = "SELECT SGM_SEGMENT_RELATION.SGM_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME AS FIELD_NAME FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'COSTCENTER_DIVISION':
                        $strRelationSql = "SELECT DVS_DIVISION_RELATION.DVS_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME AS FIELD_NAME FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'STATION_AREA':
                        $strRelationSql = "SELECT ARE_AREA.ARE_ID AS FIELD_ID, ARE_AREA.ARE_NAME AS FIELD_NAME FROM ARE_AREA WHERE ARE_STATUS = 1";
                        break;
                    case 'LINE_STATION':
                        $strRelationSql = "SELECT STT_STATION_RELATION.STT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME AS FIELD_NAME FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'FAULT_STATION':
                        $strRelationSql = "SELECT STT_STATION_RELATION.STT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME AS FIELD_NAME FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'CAUSE_FAULT':
                        $strRelationSql = "SELECT FLT_FAULT_RELATION.FLT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME||' - '||FLT_FAULT.FLT_NAME AS FIELD_NAME FROM FLT_FAULT_RELATION, STT_STATION_RELATION, FLT_FAULT, STT_STATION, ARE_AREA WHERE FLT_FAULT_RELATION.FLT_STATION = STT_STATION_RELATION.STT_ID AND FLT_FAULT_RELATION.FLT_FAULT = FLT_FAULT.FLT_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND FLT_FAULT_RELATION.FLT_STATUS = 1 AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                        break;
                    case 'SCRAPCODE_CAUSE':
                        $strRelationSql = "SELECT CAS_CAUSE.CAS_ID AS FIELD_ID, CAS_NAME AS FIELD_NAME FROM CAS_CAUSE WHERE CAS_STATUS = 1";
                        break;
                }
                $rstRelationData = $objScrap->dbQuery($strRelationSql);
                $intNumRows = $objScrap->intAffectedRows;
                if($intNumRows!=0){
                    foreach($rstRelationData as $intRelationDataIndex=>$objRelationData){
                        $objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(0,$intRelationDataIndex + 2, $objRelationData['FIELD_NAME']);
                    }
                    unset($objRelationData);
                }
                unset($rstRelationData);
                $objPHPExcel->getActiveSheet()->setTitle($objRelation['TBL_DISPLAY']);
            }
        }
        unset($arrRelation);
        unset($objRelation);
        unset($intRelationIndex);
        unset($arrRelation);
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($strFileName);
        unset($objWriter);
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        $jsnData['strTemplateFile'] = $strFileName;
        break;
    case 'getRelation':
        $jsnData = array();
        $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TABLE = " . $_REQUEST['intTableId'] . " ORDER BY TBL_ORDER";
        $rstRelation = $objScrap->dbQuery($strSql);
        foreach ($rstRelation as $intRelationIndex=>$objRelation) {
            $jsnData[$intRelationIndex]=array('strRelationName'=>$objRelation['TBL_NAME'],'arrRelationIds'=>array(),'arrRelationRows'=>array());
            switch($objRelation['TBL_NAME']){
                case 'PLANT_COUNTRY':
                    $strRelationSql = "SELECT CNT_COUNTRY.CNT_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME AS FIELD_NAME FROM CNT_COUNTRY WHERE CNT_STATUS = 1";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_REQUEST['intRecordId'] . " AND PLN_COUNTRY = ";
                    break;
                case 'SHIP_PLANT':
                    $strRelationSql = "SELECT PLN_PLANT_RELATION.PLN_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME AS FIELD_NAME FROM PLN_PLANT, CNT_COUNTRY, PLN_PLANT_RELATION WHERE PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_REQUEST['intRecordId'] . " AND SHP_PLANT = ";
                    break;
                case 'DIVISION_SHIP':
                    $strRelationSql = "SELECT SHP_SHIP_RELATION.SHP_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME AS FIELD_NAME FROM SHP_SHIP_RELATION, PLN_PLANT_RELATION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_REQUEST['intRecordId'] . " AND DVS_SHIP = ";
                    break;
                case 'SEGMENT_DIVISION':
                    $strRelationSql = "SELECT DVS_DIVISION_RELATION.DVS_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME AS FIELD_NAME FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_SEGMENT = " . $_REQUEST['intRecordId'] . " AND SGM_DIVISION = ";
                    break;
                case 'PROFITCENTER_SEGMENT':
                    $strRelationSql = "SELECT SGM_SEGMENT_RELATION.SGM_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME AS FIELD_NAME FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_PROFITCENTER = " . $_REQUEST['intRecordId'] . " AND PRF_SEGMENT = ";
                    break;
                case 'APD_SEGMENT':
                    $strRelationSql = "SELECT SGM_SEGMENT_RELATION.SGM_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME AS FIELD_NAME FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM APD_APD_RELATION WHERE APD_STATUS = 1 AND APD_APD = " . $_REQUEST['intRecordId'] . " AND APD_SEGMENT = ";
                    break;
                case 'COSTCENTER_DIVISION':
                    $strRelationSql = "SELECT DVS_DIVISION_RELATION.DVS_ID AS FIELD_ID, CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME AS FIELD_NAME FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM CST_COSTCENTER_RELATION WHERE CST_STATUS = 1 AND CST_COSTCENTER = " . $_REQUEST['intRecordId'] . " AND CST_DIVISION = ";
                    break;
                case 'STATION_AREA':
                    $strRelationSql = "SELECT ARE_AREA.ARE_ID AS FIELD_ID, ARE_AREA.ARE_NAME AS FIELD_NAME FROM ARE_AREA WHERE ARE_STATUS = 1";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM STT_STATION_RELATION WHERE STT_STATUS = 1 AND STT_STATION = " . $_REQUEST['intRecordId'] . " AND STT_AREA = ";
                    break;
                case 'LINE_STATION':
                    $strRelationSql = "SELECT STT_STATION_RELATION.STT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME AS FIELD_NAME FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM LIN_LINE_RELATION WHERE LIN_STATUS = 1 AND LIN_LINE = " . $_REQUEST['intRecordId'] . " AND LIN_STATION = ";
                    break;
                case 'FAULT_STATION':
                    $strRelationSql = "SELECT STT_STATION_RELATION.STT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME AS FIELD_NAME FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM FLT_FAULT_RELATION WHERE FLT_STATUS = 1 AND FLT_FAULT = " . $_REQUEST['intRecordId'] . " AND FLT_STATION = ";
                    break;
                case 'CAUSE_FAULT':
                    $strRelationSql = "SELECT FLT_FAULT_RELATION.FLT_ID AS FIELD_ID, ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME||' - '||FLT_FAULT.FLT_NAME AS FIELD_NAME FROM FLT_FAULT_RELATION, STT_STATION_RELATION, FLT_FAULT, STT_STATION, ARE_AREA WHERE FLT_FAULT_RELATION.FLT_STATION = STT_STATION_RELATION.STT_ID AND FLT_FAULT_RELATION.FLT_FAULT = FLT_FAULT.FLT_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND FLT_FAULT_RELATION.FLT_STATUS = 1 AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM CAS_CAUSE_RELATION WHERE CAS_STATUS = 1 AND CAS_CAUSE = " . $_REQUEST['intRecordId'] . " AND CAS_FAULT = ";
                    break;
                case 'SCRAPCODE_CAUSE':
                    $strRelationSql = "SELECT CAS_CAUSE.CAS_ID AS FIELD_ID, CAS_NAME AS FIELD_NAME FROM CAS_CAUSE WHERE CAS_STATUS = 1";
                    $strCountSql = "SELECT COUNT(*) AS COUNT FROM SCD_SCRAPCODE_RELATION WHERE SCD_STATUS = 1 AND SCD_SCRAPCODE = " . $_REQUEST['intRecordId'] . " AND SCD_CAUSE = ";
                    break;
            }
            $rstRelationData = $objScrap->dbQuery($strRelationSql);
            $intNumRows = $objScrap->intAffectedRows;
            if($intNumRows!=0){
                foreach($rstRelationData as $intRelationDataIndex=>$objRelationData){
                    $jsnData[$intRelationIndex]['arrRelationIds'][$intRelationDataIndex]=$objRelationData['FIELD_ID'];
                    $blnSelected = false;
                    if ($_REQUEST['intRecordId'] != 0) {
                        $strExistSql = $strCountSql . $objRelationData['FIELD_ID'];
                        $rstCount = $objScrap->dbQuery($strExistSql);
                        if ($rstCount[0]['COUNT'] != 0) {
                            $blnSelected = true;
                        }
                        unset($rstCount);
                    } else {
                        if ($intNumRows == 1) {
                            $blnSelected = true;
                        }
                    }
                    $strRelationRows = '<tr>';
                    $strRelationRows .= '<td id="tdRelation_' . $objRelation['TBL_NAME'] . '_' . $objRelationData['FIELD_ID'] . '"';
                    $strRelationRows .= ' onclick="switchSelected(' . "'" . $objRelation['TBL_NAME'] . "'," . $objRelationData['FIELD_ID'] . ')"';
                    $strRelationRows .= ' class="td';
                    if ($blnSelected) {
                        $strRelationRows .= 'Active">&#10004';
                    } else {
                        $strRelationRows .= 'NonActive">&#10006';
                    }
                    $strRelationRows .= '</td><td>' . $objRelationData['FIELD_NAME'] . '</td>';
                    $strRelationRows .= '</tr>';
                    $jsnData[$intRelationIndex]['arrRelationRows'][$intRelationDataIndex]=$strRelationRows;
                }
                unset($objRelationData);
            }
            unset($rstRelationData);
        }
        unset($objRelation);
        unset($rstRelation);
        break;
    case 'excelUpload':
        $jsnData = array('strFileName'=>'','arrResult'=>'','strError'=>'');
        if (0 < $_FILES['file']['error']){
            $jsnData['strError'] = $_FILES['file']['error'];
        }else{
            $strPath = 'c:\wamp\www\modules\catalog\upload\\';
            $strTempFileName = $strPath . "tmp_" . $_REQUEST['intTableId'] . '_' . date('YmdHisu') . $_REQUEST['strFileExtension'];
            move_uploaded_file($_FILES['file']['tmp_name'], $strTempFileName);
            require_once('../../lib/PHPExcel.php');
            $objPHPExcel = new PHPExcel();
            $blnValidFile = false;
            $arrFileTypes = array('Excel2007', 'Excel5');
            $strExcelVersion = '';
            foreach ($arrFileTypes as $objFileType) {
                $objPHPExcelReader = PHPExcel_IOFactory::createReader($objFileType);
                if ($objPHPExcelReader->canRead($strTempFileName)) {
                    $blnValidFile = true;
                    $strExcelVersion = $objFileType;
                    unset($objPHPExcelReader);
                    break;
                }
            }
            if(!$blnValidFile){
                $jsnData['strError'] = '<span style="color: #FF2828;">&#10006 archivo corruputo o de tipo invalido</span>';
                unlink($strTempFileName);
            }else{
                $objPHPExcelReader = PHPExcel_IOFactory::createReader($strExcelVersion);
                $objPHPExcelReader = PHPExcel_IOFactory::load($strTempFileName);
                $objPHPExcelReader->setActiveSheetIndex(0);
                $arrExcelRows = $objPHPExcelReader->getActiveSheet()->toArray(null,true,true,false);
                if(count($arrExcelRows)<2){
                    $jsnData['strError'] = '<span style="color: #FF2828;">&#10006 archivo vacio</span>';
                    unlink($strTempFileName);
                }else{
                    $arrFormField = json_decode($objScrap->arrFormField, true);
                    $arrTableRelation = json_decode($objScrap->arrTableRelation, true);
                    $intNumberOfColumns = count($arrFormField) + count($arrTableRelation);
                    $arrRecord = array();
                    $intErrorCount = 0;
                    foreach($arrExcelRows as $intRowIndex=>$objRow){
                        if($intRowIndex>0){
                            $blnCellValueError = false;
                            foreach($objRow as $intColIndex=>$objCell){
                                if($intColIndex<$intNumberOfColumns){
                                    $varValue = trim(strtoupper($objCell));
                                    if($varValue=='') {
                                        $blnCellValueError = true;
                                    }
                                }
                            }
                            unset($intColIndex);
                            unset($objCell);
                            if($blnCellValueError){
                                $intErrorCount++;
                                $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">&#10006</span> Registro ' . $intRowIndex . ' </b>contiene columnas vacias, verificar<br />';
                            }else{
                                $blnCellValueError = false;
                                $strCellName = '';
                                foreach($objRow as $intColIndex=>$objCell){
                                    if($intColIndex<$intNumberOfColumns){
                                        $varValue = trim(strtoupper($objCell));
                                        if ($arrFormField[$intColIndex]['TBL_TYPE']=='N'){
                                            if(!is_numeric($varValue)){
                                                $strCellName .= $arrFormField[$intColIndex]['TBL_NAME'] . ", ";
                                                $blnCellValueError = true;
                                            }
                                        }
                                    }
                                }
                                unset($intColIndex);
                                unset($objCell);
                                if($blnCellValueError){
                                    $intErrorCount++;
                                    $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">&#10006</span> Registro ' . $intRowIndex . ' </b>columna(s) <b>' . substr($strCellName,0,strlen($strCellName) - 2) . '</b> debe(n) contener solo números, verificar<br />';
                                }else {
                                    foreach($objRow as $intColIndex=>$objCell) {
                                        $varValue = trim(strtoupper($objCell));
                                        if ($intColIndex < count($arrFormField)) {
                                            if($arrFormField[$intColIndex]['TBL_DUPLICATE']==0){
                                                $strCountSql = "SELECT " . $objScrap->strTableIdField . " FROM " . $objScrap->strTableName . " WHERE " . $arrFormField[$intColIndex]['TBL_FIELD'] . " = '" . $varValue . "' AND " . $objScrap->strTableStatusField . " IN (0,1)";
                                                $rstCount = $objScrap->dbQuery($strCountSql);
                                                if($objScrap->intAffectedRows!=0){
                                                    if(count($arrFormField)<2){
                                                        $intErrorCount++;
                                                        $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">&#10006</span> Registro ' . $intRowIndex . ' </b>ya existe, será ignorado<br />';
                                                    }
                                                }
                                            }
                                        }else{
                                            if($intColIndex<$intNumberOfColumns){
                                                switch($arrTableRelation[$intColIndex - count($arrFormField)]['TBL_NAME']){
                                                    case 'PLANT_COUNTRY':
                                                        $strRelationSql = "SELECT COUNT(CNT_COUNTRY.CNT_ID) AS COUNT FROM CNT_COUNTRY WHERE CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'SHIP_PLANT':
                                                        $strRelationSql = "SELECT COUNT(PLN_PLANT_RELATION.PLN_ID) AS COUNT FROM PLN_PLANT, CNT_COUNTRY, PLN_PLANT_RELATION WHERE PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'DIVISION_SHIP':
                                                        $strRelationSql = "SELECT COUNT(SHP_SHIP_RELATION.SHP_ID) AS COUNT FROM SHP_SHIP_RELATION, PLN_PLANT_RELATION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'SEGMENT_DIVISION':
                                                        $strRelationSql = "SELECT COUNT(DVS_DIVISION_RELATION.DVS_ID) AS COUNT FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'PROFITCENTER_SEGMENT':
                                                        $strRelationSql = "SELECT COUNT(SGM_SEGMENT_RELATION.SGM_ID) AS COUNT FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME  = '" . $varValue . "'";
                                                        break;
                                                    case 'APD_SEGMENT':
                                                        $strRelationSql = "SELECT COUNT(SGM_SEGMENT_RELATION.SGM_ID) AS COUNT FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'COSTCENTER_DIVISION':
                                                        $strRelationSql = "SELECT COUNT(DVS_DIVISION_RELATION.DVS_ID) AS COUNT FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'STATION_AREA':
                                                        $strRelationSql = "SELECT COUNT(AREA_ARA.ARE_ID) AS COUNT WHERE AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'LINE_STATION':
                                                        $strRelationSql = "SELECT COUNT(STT_STATION_RELATION.STT_ID) AS COUNT FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'FAULT_STATION':
                                                        $strRelationSql = "SELECT COUNT(STT_STATION_RELATION.STT_ID) AS COUNT FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'CAUSE_FAULT':
                                                        $strRelationSql = "SELECT COUNT(FLT_FAULT_RELATION.FLT_ID) AS COUNT FROM FLT_FAULT_RELATION, STT_STATION_RELATION, FLT_FAULT, STT_STATION, ARE_AREA WHERE FLT_FAULT_RELATION.FLT_STATION = STT_STATION_RELATION.STT_ID AND FLT_FAULT_RELATION.FLT_FAULT = FLT_FAULT.FLT_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND FLT_FAULT_RELATION.FLT_STATUS = 1 AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME||' - '||FLT_FAULT.FLT_NAME = '" . $varValue . "'";
                                                        break;
                                                    case 'SCRAPCODE_CAUSE':
                                                        $strRelationSql = "SELECT COUNT(CAS_CAUSE.CAS_ID) AS COUNT FROM CAS_CAUSE WHERE CAS_STATUS = 1 AND CAS_NAME = '" . $varValue . "'";
                                                        break;
                                                }
                                                $rstExcelRelation = $objScrap->dbQuery($strRelationSql);
                                                if($rstExcelRelation[0]['COUNT']==0){
                                                    $intErrorCount++;
                                                    $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">&#10006</span> Registro ' . $intRowIndex . ' </b>el valor <b>' . $varValue . '</b> para la relación <b>' . $arrTableRelation[$intColIndex - count($arrFormField)]['TBL_DISPLAY'] . '</b> no existe, verificar<br />';
                                                }
                                                unset($rstExcelRelation);
                                            }else{
                                                break;
                                            }
                                        }
                                    }
                                    unset($intColIndex);
                                    unset($objCell);
                                }
                            }
                        }
                    }
                    $jsnData['arrResult'] .= "<br /><br />";
                    if($intErrorCount!=0){
                        $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">' . $intErrorCount . ' errores en el archivo, importar el resto?</span></b>';
                        $jsnData['arrResult'] .= "<br /><br />";
                    }
                    $jsnData['arrResult'] .= '<input id="btnImportSubmit" type="button" value="importar" onclick="importExcel();" tempfile="' . $strTempFileName . '" class="buttons button_green">';
                    $jsnData['strFileName'] = $strTempFileName;
                }
                unset($objPHPExcelReader);
            }
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
        }
        break;
    case 'removeTempFile':
        unlink($_REQUEST['strTempFile']);
        break;
    case 'importExcel':
        $jsnData = array('strFileName'=>'','arrResult'=>'','strError'=>'');
        $strTempFileName = $_REQUEST['strTempFile'];
        require_once('../../lib/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $arrFileTypes = array('Excel2007', 'Excel5');
        $strExcelVersion = '';
        foreach ($arrFileTypes as $objFileType) {
            $objPHPExcelReader = PHPExcel_IOFactory::createReader($objFileType);
            if ($objPHPExcelReader->canRead($strTempFileName)) {
                $strExcelVersion = $objFileType;
                unset($objPHPExcelReader);
                break;
            }
        }
        $objPHPExcelReader = PHPExcel_IOFactory::createReader($strExcelVersion);
        $objPHPExcelReader = PHPExcel_IOFactory::load($strTempFileName);
        $objPHPExcelReader->setActiveSheetIndex(0);
        $arrExcelRows = $objPHPExcelReader->getActiveSheet()->toArray(null,true,true,false);
        if(count($arrExcelRows)>=2){
            $arrFormField = json_decode($objScrap->arrFormField, true);
            $arrTableRelation = json_decode($objScrap->arrTableRelation, true);
            $intNumberOfColumns = count($arrFormField) + count($arrTableRelation);
            $arrRecord = array();
            foreach($arrExcelRows as $intRowIndex=>$objRow){
                if($intRowIndex>0){
                    $blnCellValueError = false;
                    foreach($objRow as $intColIndex=>$objCell){
                        if($intColIndex<$intNumberOfColumns){
                            $varValue = trim(strtoupper($objCell));
                            if($varValue=='') {
                                $blnCellValueError = true;
                            }
                        }
                    }
                    unset($intColIndex);
                    unset($objCell);
                    if($blnCellValueError){
                        $blnCellValueError = false;
                        $strCellName = '';
                        foreach($objRow as $intColIndex=>$objCell){
                            if($intColIndex<$intNumberOfColumns){
                                $varValue = trim(strtoupper($objCell));
                                if ($arrFormField[$intColIndex]['TBL_TYPE']=='N'){
                                    if(!is_numeric($varValue)){
                                        $strCellName .= $arrFormField[$intColIndex]['TBL_NAME'] . ", ";
                                        $blnCellValueError = true;
                                    }
                                }
                            }
                        }
                        unset($intColIndex);
                        unset($objCell);
                        if($blnCellValueError){
                            $blnUpdate = false;
                            $strUpdateWhere = "";
                            //$strInsertFields
                                foreach($objRow as $intColIndex=>$objCell) {
                                    $varValue = trim(strtoupper($objCell));
                                    if ($intColIndex < count($arrFormField)) {
                                        if($arrFormField[$intColIndex]['TBL_DUPLICATE']==0){
                                            $strCountSql = "SELECT " . $objScrap->strTableIdField . " FROM " . $objScrap->strTableName . " WHERE " . $arrFormField[$intColIndex]['TBL_FIELD'] . " = '" . $varValue . "' AND " . $objScrap->strTableStatusField . " IN (0,1)";
                                            $rstCount = $objScrap->dbQuery($strCountSql);
                                            if($objScrap->intAffectedRows!=0){
                                                if(count($arrFormField)>=2){
                                                    $blnUpdate = true;
                                                    $strUpdateWhere = "WHERE " . $objScrap->strTableIdField . " = '" . $varValue . "'";
                                                }
                                            }else{

                                            }
                                        }else{

                                        }
                                    }else{
                                        if($intColIndex<$intNumberOfColumns){
                                            switch($arrTableRelation[$intColIndex - count($arrFormField)]['TBL_NAME']){
                                                case 'PLANT_COUNTRY':
                                                    $strRelationSql = "SELECT COUNT(CNT_COUNTRY.CNT_ID) AS FIELD_ID FROM CNT_COUNTRY WHERE CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'SHIP_PLANT':
                                                    $strRelationSql = "SELECT COUNT(PLN_PLANT_RELATION.PLN_ID) AS FIELD_ID FROM PLN_PLANT, CNT_COUNTRY, PLN_PLANT_RELATION WHERE PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'DIVISION_SHIP':
                                                    $strRelationSql = "SELECT COUNT(SHP_SHIP_RELATION.SHP_ID) AS FIELD_ID FROM SHP_SHIP_RELATION, PLN_PLANT_RELATION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'SEGMENT_DIVISION':
                                                    $strRelationSql = "SELECT COUNT(DVS_DIVISION_RELATION.DVS_ID) AS FIELD_ID FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'PROFITCENTER_SEGMENT':
                                                    $strRelationSql = "SELECT COUNT(SGM_SEGMENT_RELATION.SGM_ID) AS FIELD_ID FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME  = '" . $varValue . "'";
                                                    break;
                                                case 'APD_SEGMENT':
                                                    $strRelationSql = "SELECT COUNT(SGM_SEGMENT_RELATION.SGM_ID) AS FIELD_ID FROM SGM_SEGMENT_RELATION, DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, SGM_SEGMENT, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE SGM_SEGMENT_RELATION.SGM_DIVISION = DVS_DIVISION_RELATION.DVS_ID AND DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SGM_SEGMENT_RELATION.SGM_SEGMENT = SGM_SEGMENT.SGM_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND SGM_SEGMENT_RELATION.SGM_STATUS = 1 AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND SGM_SEGMENT.SGM_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME||' - '||SGM_SEGMENT.SGM_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'COSTCENTER_DIVISION':
                                                    $strRelationSql = "SELECT COUNT(DVS_DIVISION_RELATION.DVS_ID) AS FIELD_ID FROM DVS_DIVISION_RELATION, SHP_SHIP_RELATION, PLN_PLANT_RELATION, DVS_DIVISION, SHP_SHIP, PLN_PLANT, CNT_COUNTRY WHERE DVS_DIVISION_RELATION.DVS_SHIP = SHP_SHIP_RELATION.SHP_ID AND SHP_SHIP_RELATION.SHP_PLANT = PLN_PLANT_RELATION.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND SHP_SHIP_RELATION.SHP_SHIP = SHP_SHIP.SHP_ID AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_COUNTRY = CNT_COUNTRY.CNT_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND SHP_SHIP_RELATION.SHP_STATUS = 1 AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1 AND SHP_SHIP.SHP_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1 AND CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_NAME||' - '||PLN_PLANT.PLN_NAME||' - '||SHP_SHIP.SHP_NAME||' - '||DVS_DIVISION.DVS_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'STATION_AREA':
                                                    $strRelationSql = "SELECT COUNT(ARE_AREA.ARE_ID) AS FIELD_ID WHERE ARE_STATUS = 1 AND ARE_AREA.ARE_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'LINE_STATION':
                                                    $strRelationSql = "SELECT COUNT(STT_STATION_RELATION.STT_ID) AS FIELD_ID FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'FAULT_STATION':
                                                    $strRelationSql = "SELECT COUNT(STT_STATION_RELATION.STT_ID) AS FIELD_ID FROM STT_STATION, ARE_AREA, STT_STATION_RELATION WHERE STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'CAUSE_FAULT':
                                                    $strRelationSql = "SELECT COUNT(FLT_FAULT_RELATION.FLT_ID) AS FIELD_ID FROM FLT_FAULT_RELATION, STT_STATION_RELATION, FLT_FAULT, STT_STATION, ARE_AREA WHERE FLT_FAULT_RELATION.FLT_STATION = STT_STATION_RELATION.STT_ID AND FLT_FAULT_RELATION.FLT_FAULT = FLT_FAULT.FLT_ID AND STT_STATION_RELATION.STT_STATION = STT_STATION.STT_ID AND STT_STATION_RELATION.STT_AREA = ARE_AREA.ARE_ID AND FLT_FAULT_RELATION.FLT_STATUS = 1 AND STT_STATION_RELATION.STT_STATUS = 1 AND STT_STATION.STT_STATUS = 1 AND ARE_AREA.ARE_STATUS = 1 AND ARE_AREA.ARE_NAME||' - '||STT_STATION.STT_NAME||' - '||FLT_FAULT.FLT_NAME = '" . $varValue . "'";
                                                    break;
                                                case 'SCRAPCODE_CAUSE':
                                                    $strRelationSql = "SELECT COUNT(CAS_CAUSE.CAS_ID) AS FIELD_ID FROM CAS_CAUSE WHERE CAS_STATUS = 1 AND CAS_NAME = '" . $varValue . "'";
                                                    break;
                                            }
                                            $rstExcelRelation = $objScrap->dbQuery($strRelationSql);
                                            if($rstExcelRelation[0]['COUNT']==0){
                                                $intErrorCount++;
                                                $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">&#10006</span> Registro ' . $intRowIndex . ' </b>el valor <b>' . $varValue . '</b> para la relación <b>' . $arrTableRelation[$intColIndex - count($arrFormField)]['TBL_DISPLAY'] . '</b> no existe, verificar<br />';
                                            }
                                        }else{
                                            break;
                                        }
                                    }
                                }




                                unset($intColIndex);
                                unset($objCell);
                            }
                    }
                }
            }
            $jsnData['arrResult'] .= "<br /><br />";
            if($intErrorCount!=0){
                $jsnData['arrResult'] .= '<b><span style="color: #FF2828;">' . $intErrorCount . ' errores en el archivo, importar el resto?</span></b>';
                $jsnData['arrResult'] .= "<br /><br />";
            }
            $jsnData['arrResult'] .= '<input id="btnImportSubmit" type="button" value="importar" onclick="importExcel();" tempfile="' . $strTempFileName . '" class="buttons button_green">';
            $jsnData['strFileName'] = $strTempFileName;
        }
        unset($objPHPExcelReader);
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        break;
};
unset($objScrap);
echo json_encode($jsnData);

function relationTable($strRelation,$strSql){
    switch($strRelation){
        case 'PLANT_COUNTRY':
            $strSql = str_replace("||strTable||","PLN_PLANT_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","PLN_ID",$strSql);
            $strSql = str_replace("||strField_0||","PLN_PLANT",$strSql);
            $strSql = str_replace("||strField_1||","PLN_COUNTRY",$strSql);
            $strSql = str_replace("||strField_Status||","PLN_STATUS",$strSql);
            return $strSql;
            break;
        case 'SHIP_PLANT':
            $strSql = str_replace("||strTable||","SHP_SHIP_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","SHP_ID",$strSql);
            $strSql = str_replace("||strField_0||","SHP_SHIP",$strSql);
            $strSql = str_replace("||strField_1||","SHP_PLANT",$strSql);
            $strSql = str_replace("||strField_Status||","SHP_STATUS",$strSql);
            return $strSql;
            break;
        case 'DIVISION_SHIP':
            $strSql = str_replace("||strTable||","DVS_DIVISION_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","DVS_ID",$strSql);
            $strSql = str_replace("||strField_0||","DVS_DIVISION",$strSql);
            $strSql = str_replace("||strField_1||","DVS_SHIP",$strSql);
            $strSql = str_replace("||strField_Status||","DVS
            _STATUS",$strSql);
            return $strSql;
            break;
        case 'SEGMENT_DIVISION':
            $strSql = str_replace("||strTable||","SGM_SEGMENT_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","SGM_ID",$strSql);
            $strSql = str_replace("||strField_0||","SGM_SEGMENT",$strSql);
            $strSql = str_replace("||strField_1||","SGM_DIVISION",$strSql);
            $strSql = str_replace("||strField_Status||","SGM_STATUS",$strSql);
            break;
        case 'PROFITCENTER_SEGMENT':
            $strSql = str_replace("||strTable||","PRF_PROFITCENTER_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","PRF_ID",$strSql);
            $strSql = str_replace("||strField_0||","PRF_PROFITCENTER",$strSql);
            $strSql = str_replace("||strField_1||","PRF_SEGMENT",$strSql);
            $strSql = str_replace("||strField_Status||","PRF_STATUS",$strSql);
            break;
        case 'APD_SEGMENT':
            $strSql = str_replace("||strTable||","APD_APD_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","APD_ID",$strSql);
            $strSql = str_replace("||strField_0||","APD_APD",$strSql);
            $strSql = str_replace("||strField_1||","APD_SEGMENT",$strSql);
            $strSql = str_replace("||strField_Status||","APD_STATUS",$strSql);
            break;
        case 'COSTCENTER_DIVISION':
            $strSql = str_replace("||strTable||","CST_COSTCENTER_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","CST_ID",$strSql);
            $strSql = str_replace("||strField_0||","CST_COSTCENTER",$strSql);
            $strSql = str_replace("||strField_1||","CST_DIVISION",$strSql);
            $strSql = str_replace("||strField_Status||","CST_STATUS",$strSql);
            break;
        case 'STATION_AREA':
            $strSql = str_replace("||strTable||","STT_STATION_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","STT_ID",$strSql);
            $strSql = str_replace("||strField_0||","STT_STATION",$strSql);
            $strSql = str_replace("||strField_1||","STT_AREA",$strSql);
            $strSql = str_replace("||strField_Status||","STT_STATUS",$strSql);
            return $strSql;
            break;
        case 'LINE_STATION':
            $strSql = str_replace("||strTable||","LIN_LINE_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","LIN_ID",$strSql);
            $strSql = str_replace("||strField_0||","LIN_LINE",$strSql);
            $strSql = str_replace("||strField_1||","LIN_STATION",$strSql);
            $strSql = str_replace("||strField_Status||","LIN_STATUS",$strSql);
            return $strSql;
            break;
        case 'FAULT_STATION':
            $strSql = str_replace("||strTable||","FLT_FAULT_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","FLT_ID",$strSql);
            $strSql = str_replace("||strField_0||","FLT_FAULT",$strSql);
            $strSql = str_replace("||strField_1||","FLT_STATION",$strSql);
            $strSql = str_replace("||strField_Status||","FLT_STATUS",$strSql);
            return $strSql;
            break;
        case 'CAUSE_FAULT':
            $strSql = str_replace("||strTable||","CAS_CAUSE_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","CAS_ID",$strSql);
            $strSql = str_replace("||strField_0||","CAS_CAUSE",$strSql);
            $strSql = str_replace("||strField_1||","CAS_FAULT",$strSql);
            $strSql = str_replace("||strField_Status||","CAS_STATUS",$strSql);
            return $strSql;
            break;
        case 'SCRAPCODE_CAUSE':
            $strSql = str_replace("||strTable||","SCD_SCRAPCODE_RELATION",$strSql);
            $strSql = str_replace("||strField_Id||","SCD_ID",$strSql);
            $strSql = str_replace("||strField_0||","SCD_SCRAPCODE",$strSql);
            $strSql = str_replace("||strField_1||","SCD_CAUSE",$strSql);
            $strSql = str_replace("||strField_Status||","SCD_STATUS",$strSql);
            return $strSql;
            break;
    }
    return $strSql;
}

function stripAccents($strString) {
    return strtr(utf8_decode($strString), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}
?>