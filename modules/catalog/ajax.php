<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
$objScrap->intTableId = $_REQUEST['intTableId'];
$objScrap->getTableData();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess){
    case 'updateGrid':
        $jsnData = array('grid'=>'','pagination'=>'','intSqlNumberOfRecords'=>0);
        $objScrap->strGridSqlOrder = $_REQUEST['strSqlOrder'];
        $objScrap->intGridSqlPage = $_REQUEST['intSqlPage'];
        $objScrap->intGridSqlLimit = $_REQUEST['intSqlLimit'];
        $objScrap->updateGrid();
        $jsnData['grid'] = $objScrap->strGrid;
        $jsnData['pagination'] = $objScrap->strGridPagination;
        $jsnData['intSqlNumberOfRecords'] = $objScrap->intGridNumberOfRecords;
        break;
    case 'processRecord':
        $jsnData = array('blnGo'=>true,'strError'=>'','strField'=>'');
        $jsnForm = json_decode($objScrap->arrFormField,true);
        for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
            if($jsnForm[$intIndex]['TBL_DUPLICATE']==0){
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM " . $objScrap->strTableName;
                $strSql .= " WHERE " . $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "'";
                if($_REQUEST['intRecordId']!=0){
                    $strSql .= " AND " . $objScrap->strTableIdField . " <> '" . $_REQUEST['intRecordId'] . "'";
                }
                $rstRecordCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstRecordCount[0]['COUNT']!=0){
                        $jsnData['blnGo'] = false;
                        $jsnData['strError'] = $jsnForm[$intIndex]['TBL_NAME'] . " <b>" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "</b> ya existe";
                        $jsnData['strField'] = $jsnForm[$intIndex]['TBL_FIELD'];
                        $intIndex=count($jsnForm);
                    }
                }else{
                    $jsnData['blnGo'] = false;
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    $intIndex=count($jsnForm);
                }
                unset($rstRecordCount);
            }
        }
        if($jsnData['blnGo']){
            if($_REQUEST['intRecordId']==0){
                $strSql = "INSERT INTO " . $objScrap->strTableName . "(";
                $strInsertFields = '';
                $strInsertValues = '';
                for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
                    $strInsertFields .= $jsnForm[$intIndex]['TBL_FIELD'] . ",";
                    $strInsertValues .= "'" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "',";
                }
                $strSql .= substr($strInsertFields,0,strlen($strInsertFields)-1) . ") VALUES(" . substr($strInsertValues,0,strlen($strInsertValues)-1) . ") RETURNING " . $objScrap->strTableIdField . " INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                $intRecordId = $objScrap->getProperty('intLastInsertId');
                $jsnRelation = json_decode($objScrap->arrTableRelation,true);
                if(count($jsnRelation)!=0){
                    $arrRelations = explode("|",$_REQUEST[$jsnRelation[0]['TBL_NAME']]);
                    array_splice($arrRelations,count($arrRelations)-1);
                    for($intIndex=0;$intIndex<count($arrRelations);$intIndex++){
                        $strSql = "INSERT INTO " . $jsnRelation[0]['TBL_TABLE'] . "(" . $jsnRelation[0]['TBL_TARGET'] . ", " . $jsnRelation[0]['TBL_SOURCE'] . ") VALUES(" . $intRecordId . "," . $arrRelations[$intIndex] . ")";
                        $objScrap->dbInsert($strSql);
                    }
                }
                unset($jsnRelation);
            }else{
                $strSql = "UPDATE " . $objScrap->strTableName . " SET ";
                for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
                    $strSql .= $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "', ";
                }
                $strSql = substr($strSql,0,strlen($strSql)-2) . " WHERE " . $objScrap->strTableIdField . " = " . $_REQUEST['intRecordId'];
                $objScrap->dbUpdate($strSql);
                $jsnRelation = json_decode($objScrap->arrTableRelation,true);
                if(count($jsnRelation)!=0){
                    $strSql = "DELETE FROM " . $jsnRelation[0]['TBL_TABLE'] . " WHERE " . $jsnRelation[0]['TBL_TARGET'] . " = " . $_REQUEST['intRecordId'];
                    $objScrap->dbDelete($strSql);
                    $arrRelations = explode("|",$_REQUEST[$jsnRelation[0]['TBL_NAME']]);
                    array_splice($arrRelations,count($arrRelations)-1);
                    for($intIndex=0;$intIndex<count($arrRelations);$intIndex++){
                        $strSql = "INSERT INTO " . $jsnRelation[0]['TBL_TABLE'] . "(" . $jsnRelation[0]['TBL_TARGET'] . ", " . $jsnRelation[0]['TBL_SOURCE'] . ") VALUES(" . $_REQUEST['intRecordId'] . "," . $arrRelations[$intIndex] . ")";
                        $objScrap->dbInsert($strSql);
                    }
                }
                unset($jsnRelation);
            }
            if($objScrap->getProperty('strDBError')!=''){
                $jsnData['blnGo'] = false;
                $jsnData['strError'] = $objScrap->getProperty('strDBError');
            }
        }
        break;
    case 'deactivateRecord':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $strSql = "UPDATE " . $objScrap->strTableName . " SET " . $objScrap->strTableStatusField . " = " . $_REQUEST['intStatus'] . " WHERE " . $objScrap->strTableIdField . " = " . $_REQUEST['intRecordId'];
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = false;
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getRelation':
        $jsnData = array('strRelation'=>'','arrRelation'=>array(),'strSql'=>'');
        $jsnRelation = json_decode($objScrap->arrTableRelation,true);
        $strSql = "SELECT " . $jsnRelation[0]['TBL_SOURCE_ID_FIELD'] . ", " . $jsnRelation[0]['TBL_SOURCE_DISPLAY_FIELD'] . " ";
        $strSql .= "FROM " . $jsnRelation[0]['TBL_SOURCE_TABLE'] . " ";
        $strSql .= "WHERE " . $jsnRelation[0]['TBL_SOURCE_STATUS_FIELD'] . " = 1 ";
        $strSql .= "ORDER BY " . $jsnRelation[0]['TBL_SOURCE_DISPLAY_FIELD'] . ", " . $jsnRelation[0]['TBL_SOURCE_ID_FIELD'];
        $jsnData['strSql'] = $strSql;
        $rstRelation = $objScrap->dbQuery($strSql);
        $jsnData['strRelation'] = '';
        if($_REQUEST['intRecordId']==0){
            if($jsnRelation[0]['TBL_MULTIPLE']==0){
                $jsnData['strRelation'] .= '<option value="-1" selected="selected">- Selecciona -</option>';
            }
        }
        if($objScrap->getProperty('intAffectedRows')!=0){
            foreach($rstRelation as $objRelation){
                $blnSelected = false;
                if($_REQUEST['intRecordId']!=0){
                    $strSql = 'SELECT COUNT(*) AS "COUNT" FROM ' . $jsnRelation[0]['TBL_TABLE'] . ' WHERE ' . $jsnRelation[0]['TBL_TARGET'] . ' = ' . $_REQUEST['intRecordId'] . ' AND ' . $jsnRelation[0]['TBL_SOURCE'] . ' = ' . $objRelation[$jsnRelation[0]['TBL_SOURCE_ID_FIELD']];
                    $rstCount = $objScrap->dbQuery($strSql);
                    if($rstCount[0]['COUNT']!=0){
                        $blnSelected = true;
                    }
                    unset($rstCount);
                }
                switch($jsnRelation[0]['TBL_MULTIPLE']){
                    case 0:
                        $jsnData['strRelation'] .= '<option value="' . $objRelation[$jsnRelation[0]['TBL_SOURCE_ID_FIELD']] . '"';
                        if($blnSelected){
                            $jsnData['strRelation'] .= ' selected="selected"';
                        }
                        $jsnData['strRelation'] .= '>' . $objRelation[$jsnRelation[0]['TBL_SOURCE_DISPLAY_FIELD']] . '</option>';
                        break;
                    case 1;
                        array_push($jsnData['arrRelation'],$objRelation[$jsnRelation[0]['TBL_SOURCE_ID_FIELD']]);
                        $jsnData['strRelation'] .= '<tr>';
                        $jsnData['strRelation'] .= '<td id="tdRelation_' . $jsnRelation[0]['TBL_NAME'] . '_' . $objRelation[$jsnRelation[0]['TBL_SOURCE_ID_FIELD']] . '" onclick="switchSelected(' . "'" . $jsnRelation[0]['TBL_NAME'] . "'," . $objRelation[$jsnRelation[0]['TBL_SOURCE_ID_FIELD']] . ')" class="td';
                        if($blnSelected){
                            $jsnData['strRelation'] .= 'Active">&#10004';
                        }else{
                            $jsnData['strRelation'] .= 'NonActive">&#10006';
                        }
                        $jsnData['strRelation'] .= '</td><td>' . $objRelation[$jsnRelation[0]['TBL_SOURCE_DISPLAY_FIELD']] . '</td>';
                        $jsnData['strRelation'] .= '</tr>';
                        break;
                }
            }
            unset($objRelation);
        }
        unset($rstRelation);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>