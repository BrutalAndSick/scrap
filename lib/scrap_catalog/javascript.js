$arrFormField=[];

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
                break;
            case 'S':
                break;
        }
    }
    if($blnFilledForm){
        $strQueryString = "intTableId=" + $jsnGridData.intTableId + "&strProcess=processRecord&intRecordId=" + $intRecordId + $strQueryString;
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