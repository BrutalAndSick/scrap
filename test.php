<?php
require_once('include/config.php');
require_once ('lib/scrap.php');
ini_set('display_errors',1);
$objScrap = new clsScrap();
?>
<!DOCTYPE html>
<html lang="en">
<body>
<?php
/*
$ldaprdn  = "uidp6375@cw01.contiwan.com";
$ldappass = "Someone8956";
if(!$ldapconn = ldap_connect('tq2c101a.cw01.contiwan.com',389)){
    echo "no furula";
}else{
    if($ldapconn){
        $ldapbind = @(ldap_bind($ldapconn, $ldaprdn, $ldappass));
        if($ldapbind){
            echo "ok";
        }else{
            echo "error";
        }
    }
}
ldap_close($ldapconn);
unset($ldapconn);
*/


//$strEmployeeNumber = '32000024';
//$strEmployeeNumber = 'uid85411';

//$objSoapClient = new SoapClient(SOAP_URL);
//$arrParams = array("AuthenticationType"=>"SAP","UserId"=>$strEmployeeNumber,"Password"=>" ");
//$arrParams = array("AuthenticationType"=>"UID","UserId"=>$strEmployeeNumber,"Password"=>" ");
//$objResponse = $objSoapClient->UserAuthentication($arrParams);
//var_dump($objResponse);
//echo "ScannedNumber: " . $strEmployeeNumber . "<br />";
//echo "Number: |" . $objResponse->UserAuthenticationResult->Number . "|<br />";
//echo "FullName: |" . $objResponse->UserAuthenticationResult->FullName . "|<br />";
//echo "CostCenter: |" . $objResponse->UserAuthenticationResult->CostCenter . "|<br />";
//echo "Facility: |" . $objResponse->UserAuthenticationResult->Facility . "|<br />";
//echo "WindowsUserId: |" . $objResponse->UserAuthenticationResult->WindowsUserId . "|<br />";

//unset($objResponse);
//unset($objSoapClient);


//$strSql = "INSERT INTO CNT_COUNTRY (CNT_NAME,CNT_CODE,CNT_NUMBER) VALUES ('MÉXICO6','MX',3)";

//$objScrap->dbTestInsert($strSql);

//echo "ERROR: " . $objScrap->strDBError;
//echo "<br /><br />";
//echo "intLastInsertId: " . $objScrap->intLastInsertId;

//$ldaprdn  = "uidv3408@cw01.contiwan.com";
//$ldappass = "DenebAlgedi15";
//if(!$ldapconn = ldap_connect('tq2c101a.cw01.contiwan.com',389)){
//    echo "no furula";
//}else{
//    if($ldapconn){
//        $ldapbind = @(ldap_bind($ldapconn, $ldaprdn, $ldappass));
//        if($ldapbind){
//            echo "ok";
//        }else{
//            echo "error";
//        }
//
//}
//ldap_close($ldapconn);
//unset($ldapconn);
//
unset($objScrap);

var_dump($_SESSION);
?>
</body>
</html>


