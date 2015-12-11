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
$conn = oci_connect('legion', '123', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$strSql = "SELECT CTRY_NAME, CTRY_CODE FROM CTRY_COUNTRY";
$rstData = oci_parse($conn, $strSql);
oci_execute($rstData);
while($objData = oci_fetch_assoc($rstData)){
    echo "Name: " . $objData['CTRY_NAME'] . ", Code: " . $objData['CTRY_CODE'] . "<br />";
}
unset($objData);
oci_free_statement($rstData);
unset($rstData);
oci_close($conn);
unset($conn);
?>
</body>
</html>
