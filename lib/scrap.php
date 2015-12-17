<?php
class scrap
{
    private $objCon;

    private $strDBUsr = "scrap";
    private $strDBPwd = "Scrap8956";
    private $strDBHost = "localhost/XE";

    private $intAffectedRows;
    private $intLastInsertId;
    private $strDBError;

    function __construct(){
        $this->objCon = oci_connect($this->strDBUsr, $this->strDBPwd, $this->strDBHost);
        if(!$this->objCon){
            //TODO "manejador de errores y log"
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
    }

    function __destruct(){
        oci_close($this->objCon);
    }

    function dbInsert($strSql){
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon,$strSql);
        oci_bind_by_name($rstData, ":intInsertedID", $intInsertedId, 18);
        if(!oci_execute($rstData)){
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
        }else{
            $this->intLastInsertId = $intInsertedId;
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function dbQuery($strSql){
        $arrRows = array();
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon,$strSql);
        if(!oci_execute($rstData)){
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
        }else{
            while($objData = oci_fetch_assoc($rstData)){
                $arrRows[] = $objData;
            }
            $this->intAffectedRows = count($arrRows);
            unset($objData);
            oci_free_statement($rstData);
        };
        unset($rstData);
        return $arrRows;
    }

    function dbUpdate($strSql){
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon,$strSql);
        if(!oci_execute($rstData)){
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
            unset($arrError);
        }else{
            $this->intAffectedRows = oci_num_rows($rstData);
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function dbDelete($strSql){
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon,$strSql);
        if(!oci_execute($rstData)){
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
            unset($arrError);
        }else{
            $this->intAffectedRows = oci_num_rows($rstData);
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function getProperty($strProperty){
        return $this->$strProperty;
    }

    private function cleanProperties(){
        $this->intAffectedRows= 0;
        $this->intLastInsertId = 0;
        $this->strDBError = "";
    }
}
?>