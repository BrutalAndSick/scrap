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
                $strSql .= substr($strInsertFields,0,strlen($strInsertFields)-1) . ") VALUES(" . substr($strInsertValues,0,strlen($strInsertValues)-1) . ")";
                $objScrap->dbInsert($strSql);
            }else{
                $strSql = "UPDATE " . $objScrap->strTableName . " SET "; //CAS_NAME = '" . $strCause . "' WHERE CAS_ID = " . $intCauseId;
                for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
                    $strSql .= $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "', ";
                }
                $strSql = substr($strSql,0,strlen($strSql)-2) . " WHERE " . $objScrap->strTableIdField . " = " . $_REQUEST['intRecordId'];
                $objScrap->dbUpdate($strSql);
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
};
unset($objScrap);
echo json_encode($jsnData);
?>