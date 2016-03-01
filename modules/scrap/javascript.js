// ###### GRID ######
$jsnGridData = {
    "intDateFrom":0,
    "intDateTo":0,
    "strSqlWhere":"",
    "intSqlScrapNumber":'',
    "intSqlSerial":'',
    "strSqlOrder":"",
    "intSqlPage":1,
    "intSqlLimit":100,
    "intSqlNumberOfRecords":0,
    "intSqlNumberOfColumns":0,
    "strAjaxUrl":"ajax.php",
    "strAjaxProcess":"updateGrid"
};

function gridFilter(){
    $intFrom = '';
    $intFrom += $('#txtFromYear').val();
    if($('#txtFromMonth').val()<10){
        $intFrom += "0";
    }
    $intFrom += $('#txtFromMonth').val();
    if($('#txtFromDay').val()<10){
        $intFrom += "0";
    }
    $intFrom += $('#txtFromDay').val();
    $jsnGridData.intDateFrom = $intFrom;
    $intTo = '';
    $intTo += $('#txtToYear').val();
    if($('#txtToMonth').val()<10){
        $intTo += "0";
    }
    $intTo += $('#txtToMonth').val();
    if($('#txtToDay').val()<10){
        $intTo += "0";
    }
    $intTo += $('#txtToDay').val();
    $jsnGridData.intDateTo= $intTo;
    $arrSels = ['selCNT','selPLN','selSHP','selDVS','selSGM','selPRF','selAPD','selARE','selSTT','selLIN','selFLT','selCAS','selSCD','selPRJ'];
    $arrFields = ['SCR_COUNTRY','SCR_PLANT','SCR_SHIP','SCR_DIVISION','SCR_SEGMENT','SCR_PROFITCENTER','SCR_APD','SCR_AREA','SCR_STATION','SCR_LINE','SCR_FAULT','SCR_CAUSE','SCR_SCRAPCODE','SCR_PROJECT'];
    $strWhere = '';
    for($intIndex=0;$intIndex<$arrSels.length;$intIndex++){
        if($('#' + $arrSels[$intIndex]).val()!=-1){if($strWhere!=''){$strWhere += "AND ";}else{$strWhere += "WHERE ";} $strWhere += $arrFields[$intIndex] + " = '" + $('#' + $arrSels[$intIndex]).val() + "' ";}
    }
    $jsnGridData.strSqlWhere = $strWhere;
    if($('#txtScrapNumber').val().trim()!=''){
        $jsnGridData.intSqlScrapNumber = $('#txtScrapNumber').val();
    }else{
        $jsnGridData.intSqlScrapNumber = '';
    };
    if($('#txtSerial').val().trim()!=''){
        $jsnGridData.intSqlSerial = $('#txtSerial').val();
    }else{
        $jsnGridData.intSqlSerial = '';
    };



    gridUpdate();
}

function gridPagination($intPage){
    $jsnGridData.intSqlPage = $intPage;
    gridUpdate();
}

function gridSort($strSort){
    console.clear();
    console.log($strSort);
    if($jsnGridData.intSqlNumberOfRecords!=0){
        $jsnGridData.strSqlOrder= $strSort;
        gridUpdate();
    }
}

function gridRecords($intRecords){
    $jsnGridData.intSqlLimit = $intRecords;
    $jsnGridData.intSqlPage = 1;
    gridUpdate();
}

function gridUpdate(){
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#tbodyGrid tr').remove();
        console.log($jsnGridData.strAjaxUrl + "?" + gridBuildQueryString());
        $.ajax({url : $jsnGridData.strAjaxUrl, data : gridBuildQueryString(), type : "POST", dataType : "json",
            success : function($objJson){
                $('#tbodyGrid').append($objJson['grid']);
                $jsnGridData.intSqlNumberOfRecords = $objJson['intSqlNumberOfRecords'];
                gridAdjustColumnsWidth();
                $('#divPagination').html($objJson['pagination'])
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
}

function gridAdjustColumnsWidth(){
    if($jsnGridData.intSqlNumberOfRecords==0){
        $intColumnWidth = $('#theadGrid tr:first').width() - ($jsnGridData.intSqlNumberOfColumns * 3) - 2;
        $('#tbodyGrid tr:first td:eq(0)').css('width',$intColumnWidth + 'px')
    }else{
        for($intIndex=0;$intIndex<$jsnGridData.intSqlNumberOfColumns;$intIndex++){
            $intColumnWidth = 0;
            $intColumnWidthHeader = $('#theadGrid tr:first th:eq(' + $intIndex + ')').width();
            $intColumnWidthContent = $('#tbodyGrid tr:last td:eq(' + $intIndex + ')').width();
            if($intColumnWidthHeader>=$intColumnWidthContent){
                $intColumnWidth = $intColumnWidthHeader;
            }else{
                $intColumnWidth = $intColumnWidthContent;
            }
            $('#tbodyGrid tr:last td:eq(' + $intIndex + ')').css('width',$intColumnWidth + 'px')
            $('#tbodyGrid tr:last td:eq(' + $intIndex + ')').css('min-width',$intColumnWidth + 'px');
            $('#theadGrid tr:first th:eq(' + $intIndex + ')').css('width',$intColumnWidth + 'px');
            $('#theadGrid tr:first th:eq(' + $intIndex + ')').css('min-width',$intColumnWidth + 'px');
        }
    }
}

function gridBuildQueryString() {
    $strQueryString = "strProcess=" + encodeURIComponent($jsnGridData.strAjaxProcess);
    $strQueryString += "&intDateFrom=" + encodeURIComponent($jsnGridData.intDateFrom);
    $strQueryString += "&intDateTo=" + encodeURIComponent($jsnGridData.intDateTo);
    $strQueryString += "&strSqlWhere=" + encodeURIComponent($jsnGridData.strSqlWhere);
    $strQueryString += "&intSqlScrapNumber=" + encodeURIComponent($jsnGridData.intSqlScrapNumber);
    $strQueryString += "&intSqlSerial=" + encodeURIComponent($jsnGridData.intSqlSerial);

    $strQueryString += "&strSqlOrder=" + encodeURIComponent($jsnGridData.strSqlOrder);
    $strQueryString += "&intSqlPage=" + encodeURIComponent($jsnGridData.intSqlPage);
    $strQueryString += "&intSqlLimit=" + encodeURIComponent($jsnGridData.intSqlLimit);
    return $strQueryString;
}
