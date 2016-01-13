<?php
class clsScrap
{
    private $objCon;

    private $strDBUsr = "scrap";
    private $strDBPwd = "Scrap8956";
    private $strDBHost = "localhost/XE";

    private $intAffectedRows = 0;
    private $intLastInsertId;
    private $strDBError;

    public $intTableId;
    public $strTableName;
    public $arrTableField = array();
    public $strGrid;
    public $strGridPagination;
    public $strGridTitle;
    public $strGridSql;
    public $strGridSqlOrder;
    public $intGridSqlPage;
    public $intGridSqlLimit;
    public $intGridNumberOfColumns;
    public $intGridNumberOfRecords;
    public $strGridHeader;
    public $strGridForm;
    public $strTableIdField;
    public $strTableStatusField;
    public $arrFormField = array();

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
        unset($this->objCon);
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

    function getTableData(){
        $strSql = "SELECT * FROM TBL_TABLE WHERE TBL_ID = " . $this->intTableId;
        $rstTable = $this->dbQuery($strSql);
        $this->strGridTitle = $rstTable[0]['TBL_DISPLAY'];
        $strTableSqlFrom = ' FROM ' . $rstTable[0]['TBL_NAME'];
        $this->strTableName = $rstTable[0]['TBL_NAME'];
        unset($rstTable);
        $strSql = 'SELECT * FROM TBL_TABLE_FIELD WHERE TBL_TABLE = ' . $this->intTableId . ' ORDER BY TBL_ORDER';
        $rstField = $this->dbQuery($strSql);
        $this->intGridNumberOfColumns = $this->intAffectedRows + 1;
        $strTableSqlSelect = 'SELECT ';
        $strTableSqlWhere = ' WHERE ';
        $strTableSqlOrder = '';
        $this->strGridHeader = '<tr>';
        $this->strGridForm = '<table>';
        foreach($rstField as $objField){
            array_push($this->arrTableField,array(
                    'TBL_FIELD'=>$objField['TBL_FIELD'],
                    'TBL_NAME'=>$objField['TBL_NAME'],
                    'TBL_TYPE'=>$objField['TBL_TYPE'],
                    'TBL_DISPLAY'=>$objField['TBL_DISPLAY'],
                    'TBL_ALIGN'=>$objField['TBL_ALIGN'],
                    'TBL_SORT'=>$objField['TBL_SORT'],
                    'TBL_FUNCTION'=>$objField['TBL_FUNCTION'],
                    'TBL_ORDER'=>$objField['TBL_ORDER'],
                    'TBL_STATUS_FIELD'=>$objField['TBL_STATUS_FIELD'],
                    'TBL_ID_FIELD'=>$objField['TBL_ID_FIELD'],
                    'TBL_EDIT'=>$objField['TBL_EDIT'],
                    'TBL_DUPLICATE'=>$objField['TBL_DUPLICATE']
                )
            );

            if($objField['TBL_ID_FIELD']==1){
                $strTableSqlOrder = $objField['TBL_FIELD'];
                $this->strTableIdField = $objField['TBL_FIELD'];
            }
            if($objField['TBL_STATUS_FIELD']==1){
                $strTableSqlWhere .= $objField['TBL_FIELD'];
                $this->strTableStatusField = $objField['TBL_FIELD'];
            }
            $strTableSqlSelect .= $objField['TBL_FIELD'] . ', ';

            $this->strGridHeader .= '<th class="thGrid">';
            if($objField['TBL_SORT']==1){
                $this->strGridHeader .= '<div class="divSort">';
                $this->strGridHeader .= '<div class="divSortContainer divSortLabel">' . $objField['TBL_NAME'] . '</div>';
                $this->strGridHeader .= '<div class="divSortContainer">';
                $this->strGridHeader .= '<div class="divSortOrder" title="' . $objField['TBL_NAME'] . ' ASC" onclick="gridSort(\'' . $objField['TBL_FIELD'] . ' ASC\')">&#9650;</div>';
                $this->strGridHeader .= '<div class="divSortOrder" title="' . $objField['TBL_NAME'] . ' DESC" onclick="gridSort(\'' . $objField['TBL_FIELD'] . ' DESC\')">&#9660;</div>';
                $this->strGridHeader .= '</div>';
                $this->strGridHeader .= '</div>';
            }else{
                $this->strGridHeader .= $objField['TBL_NAME'];
            }
            $this->strGridHeader .= '</th>';

            if($objField['TBL_EDIT']==1){
                $this->strGridForm .= '<tr>';
                switch($objField['TBL_TYPE']){
                    case 'N':
                        array_push($this->arrFormField,array(
                            'TBL_FIELD'=>$objField['TBL_FIELD'],
                            'TBL_NAME'=>$objField['TBL_NAME'],
                            'TBL_TYPE'=>$objField['TBL_TYPE'],
                            'TBL_DUPLICATE'=>$objField['TBL_DUPLICATE']
                        ));
                        $this->strGridForm .= '<td><label for="txt' . $objField['TBL_FIELD'] . '" class="form_label" style="width: 88px;">' . $objField['TBL_NAME'] . '</label></td>';
                        $this->strGridForm .= '<td><input type="text" id="txt' . $objField['TBL_FIELD'] . '" class="form_input_text" style="width: 150px;" value="" /></td>';
                        break;
                    case 'T':
                        array_push($this->arrFormField,array(
                            'TBL_FIELD'=>$objField['TBL_FIELD'],
                            'TBL_NAME'=>$objField['TBL_NAME'],
                            'TBL_TYPE'=>$objField['TBL_TYPE'],
                            'TBL_DUPLICATE'=>$objField['TBL_DUPLICATE']
                        ));
                        $this->strGridForm .= '<td><label for="txt' . $objField['TBL_FIELD'] . '" class="form_label" style="width: 88px;">' . $objField['TBL_NAME'] . '</label></td>';
                        $this->strGridForm .= '<td><input type="text" id="txt' . $objField['TBL_FIELD'] . '" class="form_input_text" style="width: 150px;" value="" /></td>';
                        break;
                    case 'S':
                        break;
                }
                $this->strGridForm .= '</tr>';
            }
        }
        $this->arrFormField = json_encode($this->arrFormField);
        $this->strGridHeader .= '<th class="thGrid">Editar</th>';
        $this->strGridHeader .= '</tr>';
        $this->strGridForm .= '</table>';
        $strTableSqlSelect = substr($strTableSqlSelect,0,strlen($strTableSqlSelect)-2);
        $strTableSqlWhere .= " IN (0,1) ";
        $this->strGridSql = $strTableSqlSelect . $strTableSqlFrom . $strTableSqlWhere . " ORDER BY ";
        $this->strGridSqlOrder = $strTableSqlOrder . " DESC";
        unset($objField);
    }

    function updateGrid(){
        $rstData = $this->dbQuery($this->strGridSql . $this->strGridSqlOrder );
        $this->intGridNumberOfRecords = $this->intAffectedRows;
        if($this->intGridNumberOfRecords!=0){
            $intPages = ceil($this->intGridNumberOfRecords / $this->intGridSqlLimit);
        }else{
            $intPages = 1;
        }
        $intFirstRecord = ($this->intGridSqlLimit * $this->intGridSqlPage) - $this->intGridSqlLimit;
        $intLastRecord = $intFirstRecord + $this->intGridSqlLimit - 1;
        $this->strGrid = '';
        if($this->intGridNumberOfRecords!=0){
            for ($intIndex = $intFirstRecord; $intIndex <= $intLastRecord; $intIndex++) {
                $this->strGrid .= '<tr id="trGrid_' . $rstData[$intIndex][$this->strTableIdField] . '">';
                for($intArrayIndex=0;$intArrayIndex<count($this->arrTableField);$intArrayIndex++){
                    switch($this->arrTableField[$intArrayIndex]['TBL_TYPE']){
                        case 'N':
                            $this->strGrid .= '<td id="td' . $this->arrTableField[$intArrayIndex]['TBL_FIELD'] . '_' . $rstData[$intIndex][$this->strTableIdField] . '" class="tdGrid" style="text-align: ' . $this->arrTableField[$intArrayIndex]['TBL_ALIGN'] . ';">' . $rstData[$intIndex][$this->arrTableField[$intArrayIndex]['TBL_FIELD']] . '</td>';
                            break;
                        case 'T':
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
        }else{
            $this->strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $this->intGridNumberOfColumns . '">No existen registros</td></tr>';
        }
        $this->strGridPagination = '<div style="margin-bottom: 2px;">';
        if ($this->intGridSqlPage != 1) {
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(1)" title="Inicio">&#8920;</label>';
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($this->intGridSqlPage - 1) . ')" title="Anterior">&#8810</label>';
        }else{
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Inicio">&#8920;</label>';
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Anterior">&#8810</label>';
        }
        for ($intPage = 1; $intPage <= $intPages; $intPage++) {
            if ($intPage == $this->intGridSqlPage) {
                $this->strGridPagination .= '<label class="labelPagination labelPaginationCurrent">' . $intPage . '</label>';
            } else {
                $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPage . ')">' . $intPage . '</label>';
            }
        }
        if ($this->intGridSqlPage != $intPages) {
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($this->intGridSqlPage + 1) . ')" title="Siguiente">&#8811</label>';
            $this->strGridPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPages . ')" title="Final">&#8921</label>';
        }else{
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Siguiente">&#8811</label>';
            $this->strGridPagination .= '<label class="labelPagination labelPaginationDisabled" title="Final">&#8921</label>';
        }
        $this->strGridPagination .= '</div>';
        $this->strGridPagination .= '<b>' . $this->intGridNumberOfRecords . '</b> Registro';
        if($this->intGridNumberOfRecords>1){
            $this->strGridPagination .= 's';
        }
        $this->strGridPagination .= ' - ';
        $this->strGridPagination .= '<b>' . $intPages . '</b> Página';
        if($intPages>1){
            $this->strGridPagination .= 's';
        }
        $this->strGridPagination .= ' - ';
        if($this->intGridNumberOfRecords!=0) {
            $this->strGridPagination .= '<select onchange="gridRecords(this.value);">';
            for ($intPageCount = 10; $intPageCount <= 50; $intPageCount = $intPageCount + 10) {
                $this->strGridPagination .= '<option value="' . $intPageCount . '"';
                if ($this->intGridSqlLimit == $intPageCount) {
                    $this->strGridPagination .= ' selected="selected"';
                }
                $this->strGridPagination .= '>' . $intPageCount . '</option>';
            }
            $this->strGridPagination .= '</select>';
        }else{
            $this->strGridPagination .= '<select>';
            $this->strGridPagination .= '<option value="0">0</option>';
            $this->strGridPagination .= '</select>';
        }
        $this->strGridPagination .= ' Registros por página';
        unset($rstData);
    }
}
?>