$arrSegment = [];

function showModal($intProfitCenterId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblSegment tr').remove();
        if($intProfitCenterId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
            $strQueryString = "strProcess=getSegment";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrSegment = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrSegment.push($objJson[$intIndex].intSegment);
                        $('#tblSegment').append($objJson[$intIndex].strHtml);
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#lblEdit_' + $intProfitCenterId).attr('profitcentername'));
            $('#txtName').attr('intProfitCenterId',$intProfitCenterId);
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
            $strQueryString = "strProcess=getSegmentProfitCenter&intProfitCenterId=" + $intProfitCenterId;
            console.log("localhost/scrap/modules/profitcenter/ajax.php?" + $strQueryString);
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrSegment = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrSegment.push($objJson[$intIndex].intSegment);
                        $('#tblSegment').append($objJson[$intIndex].strHtml);
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

function switchSelected($intSegment){
    if($('#tdSegment_' + $intSegment).attr('class')=='tdNonActive'){
        $('#tdSegment_' + $intSegment).removeClass('tdNonActive');
        $('#tdSegment_' + $intSegment).addClass('tdActive');
        $('#tdSegment_' + $intSegment).html('&#10004');
    }else{
        $('#tdSegment_' + $intSegment).removeClass('tdActive');
        $('#tdSegment_' + $intSegment).addClass('tdNonActive');
        $('#tdSegment_' + $intSegment).html('&#10006');
    }
}

function addProfitCenter(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del centro de costos');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedSegment = false;
        $strSelectedSegment = '';
        for($intIndex=0;$intIndex<$arrSegment.length;$intIndex++){
            if($('#tdSegment_' + $arrSegment[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedSegment = true;
                $strSelectedSegment += $arrSegment[$intIndex] + "|";
            }
        }
        if(!$blnSelectedSegment){
            showModalError('Selecciona al menos un segmento');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertProfitCenter&strProfitCenter=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedSegment=" + $strSelectedSegment;
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

function deactivateProfitCenter($intProfitCenterId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intProfitCenterId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el centro de costos?";
    }else{
        $strQuestion = "¿Deseas desactivar el centro de costos?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateProfitCenter&intProfitCenterId=" + $intProfitCenterId + "&intStatus=";
            if($('#lblDeactivate_' + $intProfitCenterId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intProfitCenterId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intProfitCenterId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intProfitCenterId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intProfitCenterId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intProfitCenterId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intProfitCenterId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intProfitCenterId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intProfitCenterId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intProfitCenterId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

function editProfitCenter(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del centro de costos');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedSegment = false;
        $strSelectedSegment = '';
        for($intIndex=0;$intIndex<$arrSegment.length;$intIndex++){
            if($('#tdSegment_' + $arrSegment[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedSegment = true;
                $strSelectedSegment += $arrSegment[$intIndex] + "|";
            }
        }
        if(!$blnSelectedSegment){
            showModalError('Selecciona al menos un segmento');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateProfitCenter&intProfitCenterId=" + $('#txtName').attr('intProfitCenterId') + "&strProfitCenter=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedSegment=" + $strSelectedSegment;
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
    $jsnGridData.strSql = "SELECT PRT_ID, PRT_NAME, PRT_STATUS FROM PRT_PROFITCENTER WHERE PRT_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "PRT_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})