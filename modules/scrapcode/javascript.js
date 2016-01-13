$arrCause = [];

function showModal($intScrapCodeId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblCause tr').remove();
        if($intScrapCodeId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
            $strQueryString = "strProcess=getCause";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    console.log($objJson);
                    $arrCause = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrCause.push($objJson[$intIndex].intCause);
                        $('#tblCause').append($objJson[$intIndex].strHtml);
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#lblEdit_' + $intScrapCodeId).attr('divisionname'));
            $('#txtName').attr('intScrapCodeId',$intScrapCodeId);
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
            $strQueryString = "strProcess=getScrapCodeCause&intScrapCodeId=" + $intScrapCodeId;
            console.log("ajax.php?" + $strQueryString);
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    console.log($objJson);
                    $arrCause = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrCause.push($objJson[$intIndex].intCause);
                        $('#tblCause').append($objJson[$intIndex].strHtml);
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        };
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
};

function switchSelected($intCause){
    if($('#tdCause_' + $intCause).attr('class')=='tdNonActive'){
        $('#tdCause_' + $intCause).removeClass('tdNonActive');
        $('#tdCause_' + $intCause).addClass('tdActive');
        $('#tdCause_' + $intCause).html('&#10004');
    }else{
        $('#tdCause_' + $intCause).removeClass('tdActive');
        $('#tdCause_' + $intCause).addClass('tdNonActive');
        $('#tdCause_' + $intCause).html('&#10006');
    }
}

function addScrapCode(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa la descripción del código de scrap');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedCause = false;
        $strSelectedCause = '';
        for($intIndex=0;$intIndex<$arrCause.length;$intIndex++){
            if($('#tdCause_' + $arrCause[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedCause = true;
                $strSelectedCause += $arrCause[$intIndex] + "|";
            }
        }
        if(!$blnSelectedCause){
            showModalError('Selecciona al menos una causa');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertScrapCode&strScrapCode=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedCause=" + $strSelectedCause;
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                    if($objJson.blnGo=='false'){
                        showModalError($objJson.strError);
                    }else{
                        closeModal();
                        gridUpdate();
                    }
                }
            });
        }
    }
}

function deactivateScrapCode($intScrapCodeId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intScrapCodeId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el registro?";
    }else{
        $strQuestion = "¿Deseas desactivar el registro?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateScrapCode&intScrapCodeId=" + $intScrapCodeId + "&intStatus=";
            if($('#lblDeactivate_' + $intScrapCodeId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intScrapCodeId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intScrapCodeId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intScrapCodeId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intScrapCodeId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intScrapCodeId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intScrapCodeId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intScrapCodeId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intScrapCodeId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intScrapCodeId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

function editScrapCode(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa la descripción del código de scrap');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedCause = false;
        $strSelectedCause = '';
        for($intIndex=0;$intIndex<$arrCause.length;$intIndex++){
            if($('#tdCause_' + $arrCause[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedCause = true;
                $strSelectedCause += $arrCause[$intIndex] + "|";
            }
        }
        if(!$blnSelectedCause){
            showModalError('Selecciona al menos una causa');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateScrapCode&intScrapCodeId=" + $('#txtName').attr('intScrapCodeId') + "&strScrapCode=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedCause=" + $strSelectedCause;
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                    if($objJson.blnGo=='false'){
                        showModalError($objJson.strError);
                    }else{
                        closeModal();
                        gridUpdate();
                    }
                }
            });
        }
    }
};

$('document').ready(function(){
    $('#divModalMain').css('height',($('body').css('height').replace('px','').replace(' ','') - 100) + "px");
    $('#divModalForm').css('height',($('#divModalMain').css('height').replace('px','').replace(' ','') - 170) + "px");
    $('#tbodyGrid').css('height',($('#divGrid').css('height').replace('px','').replace(' ','') - 40) + "px");
    $jsnGridData.strSql = "SELECT SCD_ID, SCD_NAME, SCD_CODE, SCD_REASON_CODE, SCD_STATUS FROM SCD_SCRAP_CODE WHERE SCD_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "SCD_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})