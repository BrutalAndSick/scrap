<?php
class clsGrid
{
    public function gridHeaders($arrHeaders){
        $strHeaders = '<tr>';
        for($intIndex=0;$intIndex<count($arrHeaders);$intIndex++) {
            $strHeaders .= '<th class="thGrid">';
            if($arrHeaders[$intIndex]['strSortColumn']!=''){
                $strHeaders .= '<div class="divSort">';
                $strHeaders .= '<div class="divSortContainer divSortLabel">' . $arrHeaders[$intIndex]['strLabel'] . '</div>';
                $strHeaders .= '<div class="divSortContainer">';
                $strHeaders .= '<div class="divSortOrder" title="' . $arrHeaders[$intIndex]['strLabel'] . ' ASC" onclick="gridSort(\'' . $arrHeaders[$intIndex]['strSortColumn'] . ' ASC\')">&#9650;</div>';
                $strHeaders .= '<div class="divSortOrder" title="' . $arrHeaders[$intIndex]['strLabel'] . ' DESC" onclick="gridSort(\'' . $arrHeaders[$intIndex]['strSortColumn'] . ' DESC\')">&#9660;</div>';
                $strHeaders .= '</div>';
                $strHeaders .= '</div>';
            }else{
                $strHeaders .= $arrHeaders[$intIndex]['strLabel'];
            }
            $strHeaders .= '</th>';
        }
        $strHeaders .= '</tr>';
        return $strHeaders;
    }

    public function gridPagination($intSqlPage, $intPages, $intSqlNumberOfRecords,$intSqlLimit){
        $strPagination = '<div style="margin-bottom: 2px;">';
        if ($intSqlPage != 1) {
            $strPagination .= '<label class="labelPagination" onclick="gridPagination(1)" title="Inicio">&#8920;</label>';
            $strPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($intSqlPage - 1) . ')" title="Anterior">&#8810</label>';
        }else{
            $strPagination .= '<label class="labelPagination labelPaginationDisabled" title="Inicio">&#8920;</label>';
            $strPagination .= '<label class="labelPagination labelPaginationDisabled" title="Anterior">&#8810</label>';
        }
        for ($intPage = 1; $intPage <= $intPages; $intPage++) {
            if ($intPage == $intSqlPage) {
                $strPagination .= '<label class="labelPagination labelPaginationCurrent">' . $intPage . '</label>';
            } else {
                $strPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPage . ')">' . $intPage . '</label>';
            }
        }
        if ($intSqlPage != $intPages) {
            $strPagination .= '<label class="labelPagination" onclick="gridPagination(' . ($intSqlPage + 1) . ')" title="Siguiente">&#8811</label>';
            $strPagination .= '<label class="labelPagination" onclick="gridPagination(' . $intPages . ')" title="Final">&#8921</label>';
        }else{
            $strPagination .= '<label class="labelPagination labelPaginationDisabled" title="Siguiente">&#8811</label>';
            $strPagination .= '<label class="labelPagination labelPaginationDisabled" title="Final">&#8921</label>';
        }

        $strPagination .= '</div>';
        $strPagination .= '<b>' . $intSqlNumberOfRecords . '</b> Registro';
        if($intSqlNumberOfRecords>1){
            $strPagination .= 's';
        }
        $strPagination .= ' - ';
        $strPagination .= '<b>' . $intPages . '</b> Página';
        if($intSqlNumberOfRecords>1){
            $strPagination .= 's';
        }
        $strPagination .= ' - ';
        if($intSqlNumberOfRecords!=0) {
            $strPagination .= '<select onchange="gridRecords(this.value);">';
            for ($intPageCount = 10; $intPageCount <= 50; $intPageCount = $intPageCount + 10) {
                $strPagination .= '<option value="' . $intPageCount . '"';
                if ($intSqlLimit == $intPageCount) {
                    $strPagination .= ' selected="selected"';
                }
                $strPagination .= '>' . $intPageCount . '</option>';
            }
            $strPagination .= '</select>';
        }else{
            $strPagination .= '<select">';
            $strPagination .= '<option value="0">0</option>';
            $strPagination .= '</select>';
        }
        $strPagination .= ' Registros por página';
        return $strPagination;

    }
}