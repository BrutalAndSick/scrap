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
    "intScrollPosition":0,
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
    $jsnGridData.intScrollPosition = $('#divPagesScroll').scrollLeft();
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#tbodyGrid tr').remove();
        $.ajax({url : $jsnGridData.strAjaxUrl, data : gridBuildQueryString(), type : "POST", dataType : "json",
            success : function($jsnPhpScriptResponse){
                $('#tbodyGrid').append($jsnPhpScriptResponse['grid']);
                $jsnGridData.intSqlNumberOfRecords = $jsnPhpScriptResponse['intSqlNumberOfRecords'];
                gridAdjustColumnsWidth();
                $('#divPagination').html($jsnPhpScriptResponse['pagination'])
                $('#divPagesScroll').scrollLeft($jsnPhpScriptResponse['intScrollPosition'])
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
    $strQueryString = "intTableId=" + $jsnGridData.intTableId;
    $strQueryString += "&strProcess=" + $jsnGridData.strAjaxProcess;
    $strQueryString += "&strSqlOrder=" + $jsnGridData.strSqlOrder;
    $strQueryString += "&intSqlPage=" + $jsnGridData.intSqlPage;
    $strQueryString += "&intSqlLimit=" + $jsnGridData.intSqlLimit;
    $strQueryString += "&intScrollPosition=" + $jsnGridData.intScrollPosition;
    return $strQueryString;
}

// ###### IMPORT ######
function importExcel(){
    console.clear();
    $strTempFile = $('#btnImportSubmit').attr('tempfile');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#divImportResults').html('Procesando archivo ...');
        $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=importExcel&strTempFile=" + encodeURIComponent($strTempFile);
        console.log('#####');
        console.log($jsnGridData.strAjaxUrl + "?" + $strQueryString);
        console.log('#####');
        $.ajax({url: $jsnGridData.strAjaxUrl, datda: $strQueryString, type: "POST", dataType: "json",
            success: function($objPhpScriptResponse){
                console.log('------');
                console.log($objPhpScriptResponse);
                console.log('------');
                $('#divWorkingBackground').fadeOut('fast',function(){
                    $('#divImportBackground').fadeOut('fast',function(){
                        gridUpdate();
                    });
                });
            }
        });
    });
}

function validateExcelFile(){
    console.clear();
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#divImportResults').html('Subiendo y validando archivo ...');
        $objFileData = $('#fleImportFile').prop('files')[0];
        $strFileExtension = $objFileData.name.substr($objFileData.name.length - 5);
        if($strFileExtension.substr(0,1)!='.'){
            $strFileExtension = $objFileData.name.substr($objFileData.name.length - 4);
        }
        $objFormData = new FormData();
        $objFormData.append('file',$objFileData);
        $objFormData.append('strProcess','excelUpload');
        $objFormData.append('intTableId',$jsnGridData.intTableId);
        $objFormData.append('strFileExtension',$strFileExtension);
        $.ajax({
            url: $jsnGridData.strAjaxUrl, dataType: 'json', cache: false, contentType: false, processData: false, data: $objFormData, type: 'post',
            success: function($objPhpScriptResponse){
                console.log($objPhpScriptResponse);
                $('#fleImportFile').val('');
                if($objPhpScriptResponse.strError!=''){
                    $('#divImportResults').html($objPhpScriptResponse.strError);
                }else{
                    $('#divImportResults').html($objPhpScriptResponse.arrResult);
                    $('#divImportResults').attr
                }
                $('#divWorkingBackground').fadeOut('fast');
            }
        });
    });
}

function showImport(){
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divImportBackground').fadeIn('fast',function(){
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=generateImportTemplate";
            $.ajax({
                url: $jsnGridData.strAjaxUrl, data: $strQueryString, type: "POST", dataType: "json",
                success: function ($jsnPhpScriptResponse) {
                    $('#ancImportTemplate').attr('href',$jsnPhpScriptResponse.strTemplateFile);
                    $('#ancImportTemplate').html($jsnPhpScriptResponse.strTemplateFile);
                    $('#divImportMain').slideDown('fast',function(){
                        $('#divImportResults').html('');
                        $('#divWorkingBackground').hide();
                    });
                }
            });
        })
    });
}

function closeImport(){
    $('#divImportMain').slideUp('fast',function(){
        $('#divImportBackground').fadeOut('fast', function(){
            if(typeof($('#btnImportSubmit').attr('tempfile'))!='undefined'){
                $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=removeTempFile&strTempFile=" + encodeURIComponent($('#btnImportSubmit').attr('tempfile'));
                $.ajax({url: $jsnGridData.strAjaxUrl, data: $strQueryString, type: "POST", dataType: "json"});
            };
            $("body").css('overflow', 'auto');
        })
    })
}
/*
$('body').keyup(function(e){
    if(e.keyCode == 27){
        if($('#divImportBackground').is(':visible')){
            closeImport();
        };
        if($('#divModalBackground').is(':visible')){
            closeModal();
        };
    }
});
*/
// ###### MODAL ######

function showModal($intRecordId) {
    var $strQueryString;
    var $intIndex;
    console.clear();
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#btnModalSubmitRecord').attr('intRecordId',$intRecordId);
        if($jsnGridData.arrTableRelation.length>0) {
            $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=getRelation&intRecordId=" + $intRecordId;
            console.log($jsnGridData.strAjaxUrl + "?" + $strQueryString);
            $.ajax({
                url: $jsnGridData.strAjaxUrl, data: $strQueryString, type: "POST", dataType: "json",
                success: function ($jsnPhpScriptResponse) {
                    $jsnGridData.arrRelation = $jsnPhpScriptResponse;
                    for($intRelationIndex=0;$intRelationIndex<$jsnGridData.arrRelation.length;$intRelationIndex++) {
                        $('#tblRelation_' + $jsnGridData.arrRelation[$intRelationIndex].strRelationName + ' tr').remove();
                        for($intRowIndex=0;$intRowIndex<$jsnGridData.arrRelation[$intRelationIndex].arrRelationRows.length;$intRowIndex++){
                            $('#tblRelation_' + $jsnGridData.arrRelation[$intRelationIndex].strRelationName).append($jsnGridData.arrRelation[$intRelationIndex].arrRelationRows[$intRowIndex]);
                        }
                        if($intRecordId==0){
                            $('#divModalTitle').html('Insertar');
                            $('#btnModalSubmitRecord').val('insertar')
                            $("#divModalForm :input").each(function() {
                                if($(this).attr("type")=='text' || $(this).attr("type")=='number'){
                                    $('#' + $(this).attr("id")).val('');
                                }
                                if($(this).attr("type")=='file'){
                                    $('#' + $(this).attr("id")).val('');
                                    $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("blnchanged","false");
                                    $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("strfile","no_photo.png");
                                    $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("src","/images/parts/no_photo.png");
                                }
                            });
                        }else{
                            $('#divModalTitle').html('Editar');
                            $('#btnModalSubmitRecord').val('editar')
                            for($intIndex=0;$intIndex<$jsnGridData.arrFormField.length;$intIndex++){
                                if($jsnGridData.arrFormField[$intIndex].TBL_TYPE=='I'){
                                    $('#img' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).attr("src","/images/parts/" + $('#td' + $jsnGridData.arrFormField[4].TBL_FIELD + '_' + $intRecordId).html());
                                }else{
                                    $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val($('#td' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + '_' + $intRecordId).html());
                                }
                            }
                        };
                    }
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
                    if($(this).attr("type")=='text' || $(this).attr("type")=='number'){
                        $('#' + $(this).attr("id")).val('');
                    }
                    if($(this).attr("type")=='file'){
                        $('#' + $(this).attr("id")).val('');
                        $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("blnchanged","false");
                        $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("strfile","no_photo.png");
                        $('#img' + $('#' + $(this).attr("id")).attr("id").replace('fle','')).attr("src","/images/parts/no_photo.png");
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
    for($intRelationIndex=0;$intRelationIndex<$jsnGridData.arrRelation.length;$intRelationIndex++) {
        $('#tblRelation_' + $jsnGridData.arrRelation[$intRelationIndex].strRelationName).scrollTop(0);
    }
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

    if(typeof($blnIncludeJS)!='undefined'){
        switchSelectedUnique($strRelation,$intRecordId);
    }else{
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
}

// ###### CATALOG ######

function photoUpload($strField){
    $objFileData = $('#fle' + $strField).prop('files')[0];
    $objFormData = new FormData();
    $objFormData.append('file',$objFileData);
    $objFormData.append('strProcess','imageupload');
    $.ajax({
        url: 'upload.php',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: $objFormData,
        type: 'post',
        success: function($objPhpScriptResponse){
            if($objPhpScriptResponse.strFileName!=''){
                $('#img' + $strField).attr('src','../../images/parts/' + $objPhpScriptResponse.strFileName);
                $('#img' + $strField).attr('blnchanged','true');
                $('#img' + $strField).attr('strfile',$objPhpScriptResponse.strFileName);
            };
        }
    });
}

function submitRecord(){
    console.clear();
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
            case 'I':
                $strQueryString += "&" + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + "=" + encodeURIComponent($('#img' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).attr('strfile').trim().toUpperCase());
                break;
        }
    }
    $strSelectedRelation = '';
    for($intRelationIndex=0;$intRelationIndex<$jsnGridData.arrRelation.length;$intRelationIndex++){
        $blnSelected = false;
        $strSelectedRelation += '&' + $jsnGridData.arrRelation[$intRelationIndex].strRelationName + '=';
        for($intRelationIdsIndex=0;$intRelationIdsIndex<$jsnGridData.arrRelation[$intRelationIndex].arrRelationIds.length;$intRelationIdsIndex++){
            if($('#tdRelation_' + $jsnGridData.arrRelation[$intRelationIndex].strRelationName + '_' + $jsnGridData.arrRelation[$intRelationIndex].arrRelationIds[$intRelationIdsIndex]).attr('class')=='tdActive'){
                $blnSelected = true;
                $strSelectedRelation += $jsnGridData.arrRelation[$intRelationIndex].arrRelationIds[$intRelationIdsIndex] + "|";
            }
        }
        if(!$blnSelected){
            showModalError('El campo <b>' + $jsnGridData.arrTableRelation[$intRelationIndex].TBL_DISPLAY + '</b> debe ser seleccionado al menos una vez');
            $blnFilledForm = false;
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
            $intRelationIndex = $jsnGridData.arrRelation.length;
        }
    }

    if($blnFilledForm){
        if($strSelectedRelation!=''){
            $strQueryString = $strQueryString + $strSelectedRelation;
        }
        $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=processRecord&intRecordId=" + $intRecordId + $strQueryString;
        console.log($jsnGridData.strAjaxUrl + "?" + $strQueryString);
        $.ajax({url : $jsnGridData.strAjaxUrl, data : $strQueryString, type : "POST", dataType : "json",
            success : function($jsnPhpScriptResponse){
                console.log($jsnPhpScriptResponse);
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
                if(!$jsnPhpScriptResponse.blnGo){
                    showModalError($jsnPhpScriptResponse.strError);
                    if($jsnPhpScriptResponse.strField!=''){
                        $('#txt' + $jsnPhpScriptResponse.strField).focus();
                        $('#txt' + $jsnPhpScriptResponse.strField).select();
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
                success : function($jsnPhpScriptResponse){
                    if(!$jsnPhpScriptResponse.blnGo){
                        $('#divWorkingBackground').fadeOut('fast');
                        $("body").css('overflow', 'auto');
                        alert($jsnPhpScriptResponse.strError);
                    }else{
                        gridUpdate();
                    }
                }
            });
        });
    }
}