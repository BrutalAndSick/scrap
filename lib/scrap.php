<?php

class clsScrap
{
    private $objCon;

    public $intAffectedRows = 0;
    public $intLastInsertId;
    public $strDBError;

    public $intTableId;
    public $strTableName;
    public $arrTableField = array();
    public $arrTableRelation = array();
    public $strGrid;
    public $strGridPagination;
    public $strGridTitle;
    public $strGridSql;
    public $strGridSqlOrder;
    public $intGridSqlPage;
    public $intGridSqlLimit;
    public $intScrollPosition;
    public $intGridNumberOfColumns;
    public $intGridNumberOfRecords;
    public $strGridHeader;
    public $strGridForm;
    public $strTableIdField;
    public $strTableStatusField;
    public $arrFormField = array();
    public $strIncludeJS;

    function __construct()
    {
        $this->objCon = oci_connect(DB_USER, DB_USER_PASS, DB_SERVER, DB_CHARSET);
        if (!$this->objCon) {
            $e = oci_error();
            header('Location: ./503.php?intError=101&strError=' . htmlentities($e['message'], ENT_QUOTES));
        }
    }

    function __destruct()
    {
        oci_close($this->objCon);
        unset($this->objCon);
    }

    function getWSUserData($strUser,$blnWindowsUser){
        $arrWSUserData = array('strPersonalNumber'=>'','strFullName'=>'','strCostCenter'=>'','strFacility'=>'','strWindowsUserId'=>'','strError'=>'');
        if(ENVIRONMENT=='DEV'){
            $arrWSUserData['strPersonalNumber'] = '32435443';
            $arrWSUserData['strFullName'] = 'GONZALO MORALES';
            $arrWSUserData['strCostCenter'] = '901-41799';
            $arrWSUserData['strFacility'] = 'TIJERA';
            $arrWSUserData['strWindowsUserId'] = 'uidp6375';
        }else{
            $objSoapClient = new SoapClient(SOAP_URL);
            if($blnWindowsUser){
                $arrAuthenticationTypeParams = array("AuthenticationType"=>"UID","UserId"=>$strUser,"Password"=>" ");
            }else{
                $arrAuthenticationTypeParams = array("AuthenticationType"=>"SAP","UserId"=>$strUser,"Password"=>" ");
            }
            $objUserAuthenticationResult = $objSoapClient->UserAuthentication($arrAuthenticationTypeParams);
            $arrWSUserData['strPersonalNumber'] = trim($objUserAuthenticationResult->UserAuthenticationResult->Number);
            $arrWSUserData['strFullName'] = trim(strtoupper($objUserAuthenticationResult->UserAuthenticationResult->FullName));
            $arrWSUserData['strCostCenter'] = trim($objUserAuthenticationResult->UserAuthenticationResult->CostCenter);
            $arrWSUserData['strFacility'] = trim(strtoupper($objUserAuthenticationResult->UserAuthenticationResult->Facility));
            $arrWSUserData['strWindowsUserId'] = trim(strtolower($objUserAuthenticationResult->UserAuthenticationResult->WindowsUserId));
            unset($objUserAuthenticationResult);
            unset($objSoapClient);
        }
        return $arrWSUserData;
    }

    function getADUserData($strWindowsUserId,$strWindowsPassword){
        $arrADUserData = array('blnValid'=>true,'strError'=>'');
        if(ENVIRONMENT!='DEV'){
            $objLdapConn = ldap_connect(AD_SERVER_PRIMARY);
            $objLdapBind = @(ldap_bind($objLdapConn, $strWindowsUserId . AD_USER_DOMAIN, $strWindowsPassword));
            if(!$objLdapBind){
                $arrADUserData['blnValid'] = false;
                $arrADUserData['strError'] = 'UID o contraseña incorrrectos, verifica';
            }
            ldap_unbind($objLdapConn);
            unset($objLdapBind);
            unset($objLdapConn);
        }
        return $arrADUserData;
    }


    function dbTestInsert($strSql)
    {
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon, $strSql);
        $intInsertedId = 0;
        if(strpos($strSql," RETURNING ")!=0){
            oci_bind_by_name($rstData, ":intInsertedID", $intInsertedId, 18);
        }
        if (!oci_execute($rstData,OCI_NO_AUTO_COMMIT)) {
            oci_rollback($this->objCon);
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
        } else {
            oci_commit($this->objCon);
            $this->intLastInsertId = $intInsertedId;
            oci_free_statement($rstData);
        }
        unset($rstData);
    }


    function dbInsert($strSql)
    {
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon, $strSql);
        $intInsertedId = 0;
        if(strpos($strSql," RETURNING ")!=0){
            oci_bind_by_name($rstData, ":intInsertedID", $intInsertedId, 18);
        }
        if (!oci_execute($rstData)) {
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
        } else {
            $this->intLastInsertId = $intInsertedId;
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function dbQuery($strSql)
    {
        $arrRows = array();
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon, $strSql);
        if (!oci_execute($rstData)) {
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
        } else {
            while ($objData = oci_fetch_assoc($rstData)) {
                $arrRows[] = $objData;
            }
            $this->intAffectedRows = count($arrRows);
            unset($objData);
            oci_free_statement($rstData);
        };
        unset($rstData);
        return $arrRows;
    }

    function dbUpdate($strSql)
    {
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon, $strSql);
        if (!oci_execute($rstData)) {
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
            unset($arrError);
        } else {
            $this->intAffectedRows = oci_num_rows($rstData);
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function dbDelete($strSql)
    {
        $this->cleanProperties();
        $rstData = oci_parse($this->objCon, $strSql);
        if (!oci_execute($rstData,OCI_NO_AUTO_COMMIT)) {
            oci_rollback($rstData);
            $arrError = oci_error($rstData);
            $this->strDBError = $arrError['message'];
            unset($arrError);
        } else {
            oci_commit($rstData);
            $this->intAffectedRows = oci_num_rows($rstData);
            oci_free_statement($rstData);
        }
        unset($rstData);
    }

    function getProperty($strProperty)
    {
        return $this->$strProperty;
    }

    private function cleanProperties()
    {
        $this->intAffectedRows = 0;
        $this->intLastInsertId = 0;
        $this->strDBError = "";
    }

    function getTableData()
    {
        $strSql = "SELECT * FROM TBL_TABLE WHERE TBL_ID = " . $this->intTableId;
        $rstTable = $this->dbQuery($strSql);
        $this->strGridTitle = $rstTable[0]['TBL_DISPLAY'];
        $strTableSqlFrom = ' FROM ' . $rstTable[0]['TBL_NAME'];
        $this->strTableName = $rstTable[0]['TBL_NAME'];
        $this->strIncludeJS = $rstTable[0]['TBL_INCLUDE_JS'];
        unset($rstTable);
        $strSql = 'SELECT * FROM TBL_TABLE_FIELD WHERE TBL_TABLE = ' . $this->intTableId . ' ORDER BY TBL_ORDER';
        $rstField = $this->dbQuery($strSql);
        $this->intGridNumberOfColumns = $this->intAffectedRows + 1;
        $strTableSqlSelect = 'SELECT ';
        $strTableSqlWhere = ' WHERE ';
        $strTableSqlOrder = '';
        $this->strGridHeader = '<tr>';
        $this->strGridForm = '<table class="form_main_table">';
        foreach ($rstField as $objField) {
            array_push($this->arrTableField, array(
                    'TBL_FIELD' => $objField['TBL_FIELD'],
                    'TBL_NAME' => $objField['TBL_NAME'],
                    'TBL_TYPE' => $objField['TBL_TYPE'],
                    'TBL_DISPLAY' => $objField['TBL_DISPLAY'],
                    'TBL_ALIGN' => $objField['TBL_ALIGN'],
                    'TBL_SORT' => $objField['TBL_SORT'],
                    'TBL_FUNCTION' => $objField['TBL_FUNCTION'],
                    'TBL_ORDER' => $objField['TBL_ORDER'],
                    'TBL_STATUS_FIELD' => $objField['TBL_STATUS_FIELD'],
                    'TBL_ID_FIELD' => $objField['TBL_ID_FIELD'],
                    'TBL_EDIT' => $objField['TBL_EDIT'],
                    'TBL_DUPLICATE' => $objField['TBL_DUPLICATE'],
                    'TBL_LENGTH' => $objField['TBL_LENGTH']
                )
            );
            if ($objField['TBL_ID_FIELD'] == 1) {
                $strTableSqlOrder = $objField['TBL_FIELD'];
                $this->strTableIdField = $objField['TBL_FIELD'];
            }
            if ($objField['TBL_STATUS_FIELD'] == 1) {
                $strTableSqlWhere .= $objField['TBL_FIELD'];
                $this->strTableStatusField = $objField['TBL_FIELD'];
            }
            $strTableSqlSelect .= $objField['TBL_FIELD'] . ', ';
            if ($objField['TBL_DISPLAY'] == 1) {
                $this->strGridHeader .= '<th class="thGrid">';
                if ($objField['TBL_SORT'] == 1) {
                    $this->strGridHeader .= '<div class="divSort">';
                    $this->strGridHeader .= '<div class="divSortContainer divSortLabel">' . $objField['TBL_NAME'] . '</div>';
                    $this->strGridHeader .= '<div class="divSortContainer">';
                    $this->strGridHeader .= '<div class="divSortOrder" title="' . $objField['TBL_NAME'] . ' ASC" onclick="gridSort(\'' . $objField['TBL_FIELD'] . ' ASC\')">&#9650;</div>';
                    $this->strGridHeader .= '<div class="divSortOrder" title="' . $objField['TBL_NAME'] . ' DESC" onclick="gridSort(\'' . $objField['TBL_FIELD'] . ' DESC\')">&#9660;</div>';
                    $this->strGridHeader .= '</div>';
                    $this->strGridHeader .= '</div>';
                } else {
                    $this->strGridHeader .= $objField['TBL_NAME'];
                }
            }
            $this->strGridHeader .= '</th>';
            if ($objField['TBL_EDIT'] == 1) {
                $this->strGridForm .= '<tr>';
                switch ($objField['TBL_TYPE']) {
                    case 'N':
                    case 'D4':
                        array_push($this->arrFormField, array(
                            'TBL_FIELD' => $objField['TBL_FIELD'],
                            'TBL_NAME' => $objField['TBL_NAME'],
                            'TBL_TYPE' => $objField['TBL_TYPE'],
                            'TBL_DUPLICATE' => $objField['TBL_DUPLICATE'],
                            'TBL_LENGTH' => $objField['TBL_LENGTH']
                        ));
                        $this->strGridForm .= '<td class="form_main_td_title"><label for="txt' . $objField['TBL_FIELD'] . '" class="form_label">' . $objField['TBL_NAME'] . '</label></td>';
                        $this->strGridForm .= '<td class="form_main_td_data"><input type="number" min="1" max="999999999" id="txt' . $objField['TBL_FIELD'] . '" class="form_input_text" style="width: 150px;" value="" /></td>';
                        break;
                    case 'T':
                        array_push($this->arrFormField, array(
                            'TBL_FIELD' => $objField['TBL_FIELD'],
                            'TBL_NAME' => $objField['TBL_NAME'],
                            'TBL_TYPE' => $objField['TBL_TYPE'],
                            'TBL_DUPLICATE' => $objField['TBL_DUPLICATE'],
                            'TBL_LENGTH' => $objField['TBL_LENGTH']
                        ));
                        $this->strGridForm .= '<td class="form_main_td_title"><label for="txt' . $objField['TBL_FIELD'] . '" class="form_label">' . $objField['TBL_NAME'] . '</label></td>';
                        $this->strGridForm .= '<td class="form_main_td_data"><input type="text" id="txt' . $objField['TBL_FIELD'] . '" class="form_input_text" style="width: 150px;" value="" /></td>';
                        break;
                    case 'S':
                        break;
                    case 'I':
                        array_push($this->arrFormField, array(
                            'TBL_FIELD' => $objField['TBL_FIELD'],
                            'TBL_NAME' => $objField['TBL_NAME'],
                            'TBL_TYPE' => $objField['TBL_TYPE'],
                            'TBL_DUPLICATE' => $objField['TBL_DUPLICATE'],
                            'TBL_LENGTH' => $objField['TBL_LENGTH']
                        ));
                        $this->strGridForm .= '<td class="form_main_td_title"><label for="fle' . $objField['TBL_FIELD'] . '" class="form_label">' . $objField['TBL_NAME'] . '</label></td>';
                        $this->strGridForm .= '<td class="form_main_td_data"><input type="file" accept=".jpg" id="fle' . $objField['TBL_FIELD'] . '" class="form_input_text" style="width: 283px;" value="" onchange="photoUpload(\'' . $objField['TBL_FIELD'] . '\');" /><br>';
                        $this->strGridForm .= '<img src="/images/parts/no_photo.png" id="img' . $objField['TBL_FIELD'] . '" blnchanged="false" strfile="no_photo.png" style="max-height: 120px; max-width: 120px; display: inline-block;">';
                        $this->strGridForm .= '</td>';
                        break;
                        break;
                }
                $this->strGridForm .= '</tr>';
            }
        }
        unset($objField);
        unset($rstField);
        $strSql = 'SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TABLE = ' . $this->intTableId . ' ORDER BY TBL_ORDER';
        $rstRelation = $this->dbQuery($strSql);
        if ($this->intAffectedRows != 0) {
            foreach ($rstRelation as $objRelation) {
                array_push($this->arrTableRelation, array(
                    'TBL_ID' => $objRelation['TBL_ID'],
                    'TBL_NAME' => $objRelation['TBL_NAME'],
                    'TBL_TABLE' => $objRelation['TBL_TABLE'],
                    'TBL_DISPLAY' => $objRelation['TBL_DISPLAY'],
                    'TBL_ORDER' => $objRelation['TBL_ORDER']
                ));
                $this->strGridForm .= '<tr>';
                $this->strGridForm .= '<td class="form_main_td_title"><label class="form_label">' . $objRelation['TBL_DISPLAY'] . '</label></td>';
                $this->strGridForm .= '<td id="tdRelationContainer_' . $objRelation['TBL_NAME'] . '" class="form_main_td_data">';
                $this->strGridForm .= '<table id="tblRelation_' . $objRelation['TBL_NAME'] . '" class="form_table_relation"></tr></table>';
                $this->strGridForm .= '</td>';
                $this->strGridForm .= '</tr>';
            }
            unset($objRelation);
        }
        unset($rstRelation);
        $this->arrFormField = json_encode($this->arrFormField);
        $this->arrTableRelation = json_encode($this->arrTableRelation);
        $this->strGridHeader .= '<th class="thGrid">Editar</th>';
        $this->strGridHeader .= '</tr>';
        $this->strGridForm .= '</table>';
        $strTableSqlSelect = substr($strTableSqlSelect, 0, strlen($strTableSqlSelect) - 2);
        $strTableSqlWhere .= " IN (0,1) ";
        $this->strGridSql = $strTableSqlSelect . $strTableSqlFrom . $strTableSqlWhere . " ORDER BY ";
        $this->strGridSqlOrder = $strTableSqlOrder . " ASC";

    }

    function updateGrid()
    {
        $rstData = $this->dbQuery($this->strGridSql . $this->strGridSqlOrder);
        $this->intGridNumberOfRecords = $this->intAffectedRows;
        if ($this->intGridNumberOfRecords != 0) {
            $intPages = ceil($this->intGridNumberOfRecords / $this->intGridSqlLimit);
        } else {
            $intPages = 1;
        }

        $intFirstRecord = ($this->intGridSqlLimit * $this->intGridSqlPage) - $this->intGridSqlLimit;
        $intLastRecord = $intFirstRecord + $this->intGridSqlLimit - 1;
        $this->strGrid = '';
        if ($this->intGridNumberOfRecords != 0) {
            for ($intIndex = $intFirstRecord; $intIndex <= $intLastRecord; $intIndex++) {
                $this->strGrid .= '<tr id="trGrid_' . $rstData[$intIndex][$this->strTableIdField] . '">';
                for ($intArrayIndex = 0; $intArrayIndex < count($this->arrTableField); $intArrayIndex++) {
                    switch ($this->arrTableField[$intArrayIndex]['TBL_TYPE']) {
                        case 'N':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">' . number_format($rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']],0,'.',',') . '</td>';
                            break;
                        case 'D4':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">' . number_format($rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']],4,'.',',') . '</td>';
                            break;
                        case 'T':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">' . $rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] . '</td>';
                            break;
                        case 'I':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">' . $rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] . '</td>';
                            break;
                        case 'S':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">';
                            if ($rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] == 1) {
                                $this->strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex][$this->strTableIdField] . '" currentValue="' . $rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] . '" onclick="deactivateRecord(' . $rstData[$intIndex][$this->strTableIdField] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                            } else {
                                $this->strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex][$this->strTableIdField] . '" currentValue="' . $rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] . '" onclick="deactivateRecord(' . $rstData[$intIndex][$this->strTableIdField] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                            }
                            $this->strGrid .= '</td>';
                            break;
                    }
                }
                $this->strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $this->strGrid .= '<label id="lblEdit_' . $rstData[$intIndex][$this->strTableIdField] . '" onclick="showModal(' . $rstData[$intIndex][$this->strTableIdField] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $this->strGrid .= '</td>';
                $this->strGrid .= '</tr>';
                if ($intIndex == ($this->intGridNumberOfRecords - 1)) {
                    break;
                }
            };
        } else {
            $this->strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $this->intGridNumberOfColumns . '">No existen registros</td></tr>';
        }
        $this->strGridPagination = '<div style="margin-bottom: 2px; vertical-align: top;">';
        if ($this->intGridSqlPage != 1) {
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(1)" title="Inicio">&#8920;</label>';
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($this->intGridSqlPage - 1) . ')" title="Anterior">&#8810</label>';
        } else {
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Inicio">&#8920;</label>';
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Anterior">&#8810</label>';
        }
        $this->strGridPagination .= '<div id="divPagesScroll" style="display: inline-block; width: 545px; height: 42px; white-space: nowrap; overflow-x: auto; overflow-y: hidden">';
        for ($intPage = 1; $intPage <= $intPages; $intPage++) {
            if ($intPage == $this->intGridSqlPage) {
                $this->strGridPagination .= '<label class="labelPagination labelPaginationCurrent">' . $intPage . '</label>';
            } else {
                $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPage . ')">' . $intPage . '</label>';
            }
        }
        $this->strGridPagination .= '</div>';
        if ($this->intGridSqlPage != $intPages) {
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($this->intGridSqlPage + 1) . ')" title="Siguiente">&#8811</label>';
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPages . ')" title="Final">&#8921</label>';
        } else {
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Siguiente">&#8811</label>';
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Final">&#8921</label>';
        }
        $this->strGridPagination .= '</div>';
        $this->strGridPagination .= '<b>' . $this->intGridNumberOfRecords . '</b> Registro';
        if ($this->intGridNumberOfRecords > 1) {
            $this->strGridPagination .= 's';
        }
        $this->strGridPagination .= ' - ';
        $this->strGridPagination .= '<b>' . $intPages . '</b> Página';
        if ($intPages > 1) {
            $this->strGridPagination .= 's';
        }
        $this->strGridPagination .= ' - ';
        if ($this->intGridNumberOfRecords != 0) {
            $this->strGridPagination .= '<select onchange="gridRecords(this.value);">';
            for ($intPageCount = 25; $intPageCount <= 100; $intPageCount = $intPageCount + 25) {
                $this->strGridPagination .= '<option value="' . $intPageCount . '"';
                if ($this->intGridSqlLimit == $intPageCount) {
                    $this->strGridPagination .= ' selected="selected"';
                }
                $this->strGridPagination .= '>' . $intPageCount . '</option>';
            }
            $this->strGridPagination .= '</select>';
        } else {
            $this->strGridPagination .= '<select>';
            $this->strGridPagination .= '<option value="0">0</option>';
            $this->strGridPagination .= '</select>';
        }
        $this->strGridPagination .= ' Registros por página';
        unset($rstData);
    }
}

?>