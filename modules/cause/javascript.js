function showModal($intCauseId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        if($intCauseId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').attr('intCauseId',$intCauseId);
            $('#txtName').val($('#trGrid_' + $intCauseId + ' td:nth-child(2)').html());
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

function addCause(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la causa');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $strQueryString = "strProcess=insertCause&strCause=" + encodeURIComponent($('#txtName').val().trim().toUpperCase());
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

function updateCause(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la causa');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $strQueryString = "strProcess=updateCause&intCauseId=" + $('#txtName').attr('intCauseId') + "&strCause=" + encodeURIComponent($('#txtName').val().trim().toUpperCase());
        console.log($strQueryString);
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

function deactivateCause($intCauseId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intCauseId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar la causa?";
    }else{
        $strQuestion = "¿Deseas desactivar la causa?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateCause&intCauseId=" + $intCauseId + "&intStatus=";
            if($('#lblDeactivate_' + $intCauseId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intCauseId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intCauseId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intCauseId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intCauseId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intCauseId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intCauseId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intCauseId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intCauseId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intCauseId).html('&#10006');
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
    $jsnGridData.strSql = "SELECT CAS_ID, CAS_NAME, CAS_STATUS FROM CAS_CAUSE WHERE CAS_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "CAS_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})