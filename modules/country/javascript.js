function showModal($intCountryId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        if($intCountryId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#txtCode').val('');
            $('#txtNumber').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#trGrid_' + $intCountryId + ' td:nth-child(2)').html());
            $('#txtName').attr('intCountryId',$intCountryId);
            $('#txtCode').val($('#trGrid_' + $intCountryId + ' td:nth-child(3)').html());
            $('#txtNumber').val($('#trGrid_' + $intCountryId + ' td:nth-child(4)').html());
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

function addCountry(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre del pais');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#txtCode').val().trim()=='') {
            $('#txtCode').focus();
            showModalError('Ingresa el código del pais');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            if($('#txtNumber').val().trim()=='') {
                $('#txtNumber').focus();
                showModalError('Ingresa el número del pais');
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
            }else{
                $strQueryString = "strProcess=insertCountry&strCountry=" + $('#txtName').val().trim().toUpperCase() + "&strCode=" + $('#txtCode').val().trim().toUpperCase() + "&strNumber=" + $('#txtNumber').val().trim();
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
}

function updateCountry(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre del pais');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#txtCode').val().trim()=='') {
            $('#txtCode').focus();
            showModalError('Ingresa el código del pais');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            if($('#txtNumber').val().trim()=='') {
                $('#txtNumber').focus();
                showModalError('Ingresa el número del pais');
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
            }else{
                $strQueryString = "strProcess=updateCountry&intCountryId=" + $('#txtName').attr('intCountryId') + "&strCountry=" + $('#txtName').val().trim().toUpperCase() + "&strCode=" + $('#txtCode').val().trim().toUpperCase() + "&strNumber=" + $('#txtNumber').val().trim();
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
}

function deactivateCountry($intCountryId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intCountryId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el pais?";
    }else{
        $strQuestion = "¿Deseas desactivar el pais?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateCountry&intCountryId=" + $intCountryId + "&intStatus=";
            if($('#lblDeactivate_' + $intCountryId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intCountryId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intCountryId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intCountryId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intCountryId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intCountryId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intCountryId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intCountryId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intCountryId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intCountryId).html('&#10006');
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
    $jsnGridData.strSql = "SELECT CNT_ID, CNT_NAME, CNT_CODE, CNT_NUMBER, CNT_STATUS FROM CNT_COUNTRY WHERE CNT_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "CNT_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})