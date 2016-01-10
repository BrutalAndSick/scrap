function showModal($intAreaId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        if($intAreaId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').attr('intAreaId',$intAreaId);
            $('#txtName').val($('#trGrid_' + $intAreaId + ' td:nth-child(2)').html());
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

function addArea(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre del área');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $strQueryString = "strProcess=insertArea&strArea=" + encodeURIComponent($('#txtName').val().trim().toUpperCase());
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

function updateArea(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre del área');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $strQueryString = "strProcess=updateArea&intAreaId=" + $('#txtName').attr('intAreaId') + "&strArea=" + encodeURIComponent($('#txtName').val().trim().toUpperCase());
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

function deactivateArea($intAreaId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intAreaId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el área?";
    }else{
        $strQuestion = "¿Deseas desactivar el área?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateArea&intAreaId=" + $intAreaId + "&intStatus=";
            if($('#lblDeactivate_' + $intAreaId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intAreaId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intAreaId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intAreaId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intAreaId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intAreaId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intAreaId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intAreaId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intAreaId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intAreaId).html('&#10006');
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
    $jsnGridData.strSql = "SELECT ARE_ID, ARE_NAME, ARE_STATUS FROM ARE_AREA WHERE ARE_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "ARE_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})