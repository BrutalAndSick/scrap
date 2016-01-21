// ###### GRID ######
$jsnGridData = {
    "strSql":"",
    "strSqlOrder":"",
    "intSqlPage":1,
    "intSqlLimit":25,
    "intSqlNumberOfRecords":0,
    "intSqlNumberOfColumns":0,
    "intTableId":0,
    "strAjaxUrl":"ajax.php",
    "strAjaxProcess":"updateGrid",
    "arrFormField":"",
    "arrTableRelation":"",
    "arrRelation":""
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
        $('#tbodyGrid tr').remove();
        $.ajax({url : $jsnGridData.strAjaxUrl, data : gridBuildQueryString(), type : "POST", dataType : "json",
            success : function($objJson){
                $('#tbodyGrid').append($objJson['grid']);
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
    $strQueryString = "intTableId=" + $jsnGridData.intTableId;
    $strQueryString += "&strProcess=" + $jsnGridData.strAjaxProcess;
    $strQueryString += "&strSqlOrder=" + $jsnGridData.strSqlOrder;
    $strQueryString += "&intSqlPage=" + $jsnGridData.intSqlPage;
    $strQueryString += "&intSqlLimit=" + $jsnGridData.intSqlLimit;
    return $strQueryString;
}
// ###### MODAL ######

function showModal($intRecordId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#btnModalSubmitRecord').attr('intRecordId',$intRecordId);
        if($jsnGridData.arrTableRelation.length>0) {
            $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=getRelation&intRecordId=" + $intRecordId;
            console.clear();
            console.log($jsnGridData.strAjaxUrl + "?" + $strQueryString);
            $.ajax({
                url: $jsnGridData.strAjaxUrl, data: $strQueryString, type: "POST", dataType: "json",
                success: function ($objJson) {

                    console.clear();
                    console.log($objJson);

                    $jsnGridData.arrRelation = $objJson;
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $('#tblRelation_' + $objJson[$intIndex].strTable + ' tr').remove();
                        $('#tblRelation_' + $objJson[$intIndex].strTable).append($objJson[$intIndex].strRows);
                    }
                    if($intRecordId==0){
                        $('#divModalTitle').html('Insertar');
                        $('#btnModalSubmitRecord').val('insertar')
                        $("#divModalForm :input").each(function() {
                            if($(this).attr("type")=='text'){
                                $('#' + $(this).attr("id")).val('');
                            }
                        });
                    }else{
                        $('#divModalTitle').html('Editar');
                        $('#btnModalSubmitRecord').val('editar')
                        for($intIndex=0;$intIndex<$jsnGridData.arrFormField.length;$intIndex++){
                            $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val($('#td' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + '_' + $intRecordId).html());
                        }
                    };
                    $('#divModalMain').slideDown('fast', function(){
                        $('#divModalForm :input:enabled:visible:first').focus();
                        $('#divModalForm :input:enabled:visible:first').select();
                    });
                }
            });
        }else{
            if($intRecordId==0){
                $('#divModalTitle').html('Insertar');
                $('#btnModalSubmitRecord').val('insertar')
                $("#divModalForm :input").each(function() {
                    if($(this).attr("type")=='text'){
                        $('#' + $(this).attr("id")).val('');
                    }
                });
            }else{
                $('#divModalTitle').html('Editar');
                $('#btnModalSubmitRecord').val('editar')
                for($intIndex=0;$intIndex<$jsnGridData.arrFormField.length;$intIndex++){
                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val($('#td' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + '_' + $intRecordId).html());
                }
            };
            $('#divModalMain').slideDown('fast', function(){
                $('#divModalForm :input:enabled:visible:first').focus();
                $('#divModalForm :input:enabled:visible:first').select();
            });
        }
    });
}

function closeModal(){
    $('#divModalMain').slideUp('fast',function(){
        $('#divModalBackground').fadeOut('fast', function(){
            $("body").css('overflow', 'auto');
        })
    })
}

function showModalError($strError){
    if($strError==''){
        $('#divModalError').hide();
        $('#divModalError').html('');
    }else{
        $('#divModalError').html('&#8854; ' + $strError);
        $('#divModalError').slideDown('fast',function(){});
    }
}

function switchSelected($strRelation,$intRecordId){
    if($('#tdRelation_' + $strRelation + '_' + $intRecordId).attr('class')=='tdNonActive'){
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).removeClass('tdNonActive');
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).addClass('tdActive');
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).html('&#10004');
    }else{
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).removeClass('tdActive');
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).addClass('tdNonActive');
        $('#tdRelation_' + $strRelation + '_' + $intRecordId).html('&#10006');
    }
}

// ###### CATALOG ######
function submitRecord(){
    $intRecordId = $('#btnModalSubmitRecord').attr('intRecordId');
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    $blnFilledForm = true;
    $strQueryString = '';
    for($intIndex=0;$intIndex<$jsnGridData.arrFormField.length;$intIndex++){
        switch($jsnGridData.arrFormField[$intIndex].TBL_TYPE){
            case 'T':
                if($('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val().trim()==''){
                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).focus();
                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).select();
                    showModalError('El campo <b>' + $jsnGridData.arrFormField[$intIndex].TBL_NAME + '</b> debe ser llenado');
                    $intIndex = $jsnGridData.arrFormField.length;
                    $blnFilledForm = false;
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                }else{
                    $strQueryString += "&" + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + "=" + encodeURIComponent($('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val().trim().toUpperCase());
                }
                break;
            case 'N':
                if($('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val().trim()==''){
                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).focus();
                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).select();
                    showModalError('El campo <b>' + $jsnGridData.arrFormField[$intIndex].TBL_NAME + '</b> debe ser llenado');
                    $intIndex = $jsnGridData.arrFormField.length;
                    $blnFilledForm = false;
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                }else{
                    $strQueryString += "&" + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + "=" + encodeURIComponent($('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val().trim().toUpperCase());
                }
                break;
            case 'S':
                break;
        }
    }
    $strSelectedRelation = '';
    $intRelationCount = 1;
    if($jsnGridData.arrRelation.length>0){
        for($intIndex=0;$intIndex<$jsnGridData.arrRelation.length;$intIndex++){
            $strSelectedRelation += '&' + $jsnGridData.arrRelation[$intIndex].strTable + '=';
            $arrIds = $jsnGridData.arrRelation[$intIndex].strIds.split('|');
            $blnSelected = false;
            $intSelectedRelation = 0;
            $arrIds.forEach(function($objIds){
                if($('#tdRelation_' + $jsnGridData.arrRelation[$intIndex].strTable + '_' + $objIds).attr('class')=='tdActive'){
                    $blnSelected = true;
                    $strSelectedRelation += $objIds + "|";
                    $intSelectedRelation++;
                }
            });
            if(!$blnSelected){
                showModalError('El campo <b>' + $jsnGridData.arrRelation[$intIndex].strDisplay + '</b> debe ser seleccionado al menos una vez');
                $blnFilledForm = false;
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
                $intIndex = $jsnGridData.arrRelation.length;
            }else{
                $intRelationCount = $intRelationCount * $intSelectedRelation;
            }
        }
    }

    if($blnFilledForm){
        if($strSelectedRelation!=''){
            $strQueryString = $strQueryString + "&intRelationCount=" + $intRelationCount + $strSelectedRelation;
        }
        $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=processRecord&intRecordId=" + $intRecordId + $strQueryString;

        console.clear();
        console.log("ajax.php?" + $strQueryString);

        $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
            success : function($objJson){
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
                if(!$objJson.blnGo){
                    showModalError($objJson.strError);
                    if($objJson.strField!=''){
                        $('#txt' + $objJson.strField).focus();
                        $('#txt' + $objJson.strField).select();
                    }
                }else{
                    closeModal();
                    gridUpdate();
                }
            }
        });
    }
}

function deactivateRecord($intRecordId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intRecordId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el registro?";
    }else{
        $strQuestion = "¿Deseas desactivar el registro?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=deactivateRecord&intRecordId=" + $intRecordId + "&intStatus=";
            if($('#lblDeactivate_' + $intRecordId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if(!$objJson.blnGo){
                        $('#divWorkingBackground').fadeOut('fast');
                        $("body").css('overflow', 'auto');
                        alert($objJson.strError);
                    }else{
                        gridUpdate();
                    }
                }
            });
        });
    }
}