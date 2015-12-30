<?php
ini_set("display_errors",1);
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('lib/scrap.php');
$objScrap = new clsScrap();
$jsnProfile = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
$intProcess = $_POST['intProcess'];
switch ($intProcess){
    case 0:
        $strProfile = $_POST['strProfile'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PRF_PROFILE WHERE PRF_NAME = '" . $strProfile . "'";
        $rstProfileCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstProfileCount[0]['COUNT']!=0){
                $jsnProfile['blnGo'] = 'false';
                $jsnProfile['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnProfile['strError'] = "El perfil " . $strProfile . " ya existe";
            }else{
                $strMenu = $_POST['strSelectedMenu'];
                $arrMenu = explode("|",$strMenu);
                array_splice($arrMenu,count($arrMenu)-1);
                $strSql = "INSERT INTO PRF_PROFILE (PRF_NAME, PRF_STATUS) VALUES ('" . $strProfile . "',1) RETURNING PRF_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intProfileId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrMenu);$intIndex++){
                        $strSql = "INSERT INTO PRF_PROFILE_MENU (PRF_PROFILE,PRF_MENU) VALUES (" . $intProfileId . "," . $arrMenu[$intIndex] . ") RETURNING PRF_ID INTO :intInsertedID";
                        $objScrap->dbInsert($strSql);
                    }
                }else{
                    $jsnProfile['blnGo'] = 'false';
                    $jsnProfile['strError'] = $objScrap->getProperty('strDBError');
                }
            }
        }else{
            $jsnProfile['blnGo'] = 'false';
            $jsnProfile['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstProfileCount);
        break;
};
unset($objScrap);
echo json_encode($jsnProfile);
?>