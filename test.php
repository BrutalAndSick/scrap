<?php
ini_set("display_errors",1);

require_once('lib/scrap.class');

$objScrap = new scrap();

$strSQL = "INSERT INTO USR_USER (USR_USER,USR_AD,USR_NAME,USR_LAST_NAME,USR_STATUS) VALUES ('73544999',0,'GONZALO','MORALES RAMIREZ',1) RETURNING USR_ID INTO :intInsertedID";
$objScrap->dbInsert($strSQL);
if($objScrap->getProperty('strDBError')==''){
    echo "Lines: " . $objScrap->getProperty('intLastInsertId');
}else{
    echo $objScrap->getProperty('strDBError');
}

echo "<br /><br />";

$strSql = "SELECT * FROM USR_USER";
$rstData = $objScrap->dbQuery($strSql);
if($objScrap->getProperty('strDBError')==''){
    echo "Lineas: " . $objScrap->getProperty('intAffectedRows') . "<br /><br />";
    foreach($rstData as $objData){
        echo $objData['USR_USER'];
        echo "<br /><br />";
    }
}else{
    echo "Error: " . $objScrap->getProperty('strDBError');
};
unset($rstData);


unset($objScrap);

?>