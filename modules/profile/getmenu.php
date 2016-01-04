<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
$jsnMenu = array();
$intProcess = $_POST['intProcess'];
switch ($intProcess){
    case 0:
        $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = 0 AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
        $rstCategory = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstCategory as $objCategory){
                $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = " . $objCategory['MNU_ID'] . " AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
                $rstMenu = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
                    $arrMenu = array();
                    foreach($rstMenu as $objMenu){
                        array_push($arrMenu,array('id'=>$objMenu['MNU_ID'],'name'=>$objMenu['MNU_NAME']));
                    }
                    array_push($jsnMenu,array('id'=>$objCategory['MNU_ID'],'name'=>$objCategory['MNU_NAME'],'menu'=>$arrMenu));
                    unset($objMenu);
                }
                unset($rstMenu);
            };
            unset($objCategory);
        }
        unset($rstCategory);
        break;
};
unset($objScrap);
echo json_encode($jsnMenu);
?>