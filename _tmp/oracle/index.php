<?php
ini_set("display_errors",1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php


$conn = oci_connect('scrap', 'Scrap8956', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$strSql = "INSERT INTO USR_USER (USR_USER,USR_AD,USR_NAME,USR_LAST_NAME,USR_STATUS) VALUES ('735443',0,'GONZALO','MORALES RAMIREZ',1) RETURNING USR_ID INTO :intInsertedID";
$rstData = oci_parse($conn, $strSql);
oci_bind_by_name($rstData, ":p_val", $localvalor, 18);
oci_execute($rstData);

echo "ps dice que hizo algo con valor: " . $localvalor;

//while($objData = oci_fetch_assoc($rstData)){
//    echo "Name: " . $objData['MNU_NAME'] . ", Code: " . $objData['MNU_URL'] . "<br />";
//}
//unset($objData);
//oci_free_statement($rstData);
unset($rstData);
oci_close($conn);
unset($conn);
?>
</body>
</html>
