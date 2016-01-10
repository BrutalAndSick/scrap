function showModal($intUnitId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        if($intUnitId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#txtCode').val('');
            $('#txtNumber').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#trGrid_' + $intUnitId + ' td:nth-child(2)').html());
            $('#txtName').attr('intUnitId',$intUnitId);
            $('#txtCode').val($('#trGrid_' + $intUnitId + ' td:nth-child(3)').html());
            $('#txtNumber').val($('#trGrid_' + $intUnitId + ' td:nth-child(4)').html());
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
        };
        $('#divModalMain').slideDown('fast', function(){
            $('#txtName').focus();
        });
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

function addUnit(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la unidad');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#txtCode').val().trim()=='') {
            $('#txtCode').focus();
            showModalError('Ingresa el símbolo de la unidad');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertUnit&strUnit=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strCode=" + encodeURIComponent($('#txtCode').val().trim().toUpperCase());
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

function updateUnit(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la unidad');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#txtCode').val().trim()=='') {
            $('#txtCode').focus();
            showModalError('Ingresa el símbolo de la unidad');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateUnit&intUnitId=" + encodeURIComponent($('#txtName').attr('intUnitId')) + "&strUnit=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strCode=" + encodeURIComponent($('#txtCode').val().trim().toUpperCase());
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

function deactivateUnit($intUnitId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intUnitId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar la unidad?";
    }else{
        $strQuestion = "¿Deseas desactivar la unidad?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateUnit&intUnitId=" + $intUnitId + "&intStatus=";
            if($('#lblDeactivate_' + $intUnitId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intUnitId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intUnitId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intUnitId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intUnitId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intUnitId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intUnitId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intUnitId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intUnitId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intUnitId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

$('document').ready(function(){
    $('#divModalMain').css('height',($('body').css('height').replace('px','').replace(' ','') - 280) + "px");
    $('#divModalForm').css('height',($('#divModalMain').css('height').replace('px','').replace(' ','') - 170) + "px");
    $('#tbodyGrid').css('height',($('#divGrid').css('height').replace('px','').replace(' ','') - 40) + "px");
    $jsnGridData.strSql = "SELECT UNT_ID, UNT_NAME, UNT_CODE, UNT_STATUS FROM UNT_UNIT WHERE UNT_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "UNT_ID DESC";


    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})