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

<html>
<body>

<tr id="trGrid_5">
    <td class="tdGrid" style="text-align: right;">5</td>
    <td class="tdGrid" style="text-align: left;">PROVEEDOR</td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="1" onclick="deactivateRecord(5);" class="labelActions labelActionsGreen">✔</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal(5);" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_4">
    <td class="tdGrid" style="text-align: right;">4</td>
    <td class="tdGrid" style="text-align: left;">METODO</td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="1" onclick="deactivateRecord(4);" class="labelActions labelActionsGreen">✔</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal(4);" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_3">
    <td class="tdGrid" style="text-align: right;">3</td>
    <td class="tdGrid" style="text-align: left;">MAQUINA</td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="1" onclick="deactivateRecord(3);" class="labelActions labelActionsGreen">✔</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal(3);" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_2">
    <td class="tdGrid" style="text-align: right;">2</td>
    <td class="tdGrid" style="text-align: left;">MANO DE OBRA</td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="1" onclick="deactivateRecord(2);" class="labelActions labelActionsGreen">✔</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal(2);" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_1">
    <td class="tdGrid" style="text-align: right;">1</td>
    <td class="tdGrid" style="text-align: left;">INTRODUCCION</td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="1" onclick="deactivateRecord(1);" class="labelActions labelActionsGreen">✔</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal(1);" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_">
    <td class="tdGrid" style="text-align: right;"></td>
    <td class="tdGrid" style="text-align: left;"></td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="" onclick="deactivateCause();" class="labelActions labelActionsRed">✖</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal();" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_">
    <td class="tdGrid" style="text-align: right;"></td>
    <td class="tdGrid" style="text-align: left;"></td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="" onclick="deactivateCause();" class="labelActions labelActionsRed">✖</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal();" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_">
    <td class="tdGrid" style="text-align: right;"></td>
    <td class="tdGrid" style="text-align: left;"></td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="" onclick="deactivateCause();" class="labelActions labelActionsRed">✖</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal();" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_">
    <td class="tdGrid" style="text-align: right;"></td>
    <td class="tdGrid" style="text-align: left;"></td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="" onclick="deactivateCause();" class="labelActions labelActionsRed">✖</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal();" class="labelActions labelActionsOrange">✎</label></td>
</tr>
<tr id="trGrid_">
    <td class="tdGrid" style="text-align: right;"></td>
    <td class="tdGrid" style="text-align: left;"></td>
    <td class="tdGrid" style="text-align: center;"><label currentValue="" onclick="deactivateCause();" class="labelActions labelActionsRed">✖</label></td>
    <td class="tdGrid" style="text-align: center;"><label onclick="showModal();" class="labelActions labelActionsOrange">✎</label></td>
</tr>


</body>

</html>

