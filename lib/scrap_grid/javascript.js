$jsnGridData = {
    "strSql":"",
    "strSqlOrder":"",
    "intSqlPage":1,
    "intSqlLimit":10,
    "intSqlNumberOfRecords":0,
    "intSqlNumberOfColumns":0,
    "strAjaxUrl":"ajax.php",
    "strAjaxProcess":""
};

function gridPagination($intPage){
    $jsnGridData.intSqlPage = $intPage;
    gridUpdate();
}

function gridSort($strSort){
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
        $inttbodyHeight = $('#tbodyGrid').height();
        $('#tbodyGrid tr').remove();
        $.ajax({url : $jsnGridData.strAjaxUrl, data : gridBuildQueryString(), type : "POST", dataType : "json",
            success : function($objJson){
                $('#tbodyGrid').append($objJson['grid']);
                $('#tbodyGrid').css('height',$inttbodyHeight + 'px')
                $('#tbodyGrid').css('min-height',$inttbodyHeight + 'px');
                $jsnGridData.intSqlNumberOfRecords = $objJson['intSqlNumberOfRecords'];
                gridAdjustColumnsWidth();
                $('#divPagination').html($objJson['pagination'])
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            },
            error : function(){
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
                alert('algo paso');
                console.log($jsnGridData.strAjaxUrl + "?" + gridBuildQueryString());
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
            $('#theadGrid tr:first th:eq(' + $intIndex + ')').css('width',$intColumnWidth + 'px');
        }
    }
}

function gridBuildQueryString() {
    $strQueryString = "strProcess=" + $jsnGridData.strAjaxProcess;
    $strQueryString += "&strSql=" + $jsnGridData.strSql;
    $strQueryString += "&strSqlOrder=" + $jsnGridData.strSqlOrder;
    $strQueryString += "&intSqlPage=" + $jsnGridData.intSqlPage;
    $strQueryString += "&intSqlLimit=" + $jsnGridData.intSqlLimit;
    $strQueryString += "&intSqlNumberOfColumns=" + $jsnGridData.intSqlNumberOfColumns;
    return $strQueryString;
}

function gridFormatPage(){
    $('#divModalMain').css('height',($('body').css('height').replace('px','').replace(' ','') - 100) + "px");
    $('#divModalForm').css('height',($('#divModalMain').css('height').replace('px','').replace(' ','') - 170) + "px");
    $('#tbodyGrid').css('height',($('#divGrid').css('height').replace('px','').replace(' ','') - 40) + "px");
}