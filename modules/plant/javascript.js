function showModal($intPlantId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        if($intPlantId==0){
            $('#divModalTitle').html('Insertar');
            $strQueryString = "strProcess=getCountry";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#txtName').val('');
                    $('#selCountry').append($objJson.strHTML);
                    $('#numNumber').val('1');
                    $('#btnModalEdit').hide();
                    $('#btnModalAdd').show();
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').attr('intPlantId',$intPlantId);
            $strQueryString = "strProcess=getSelectedCountry&intPlant=" + $intPlantId;
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#txtName').val($('#trGrid_' + $intPlantId + ' td:nth-child(2)').html());
                    $('#selCountry').append($objJson.strHTML);
                    $('#numNumber').val($('#trGrid_' + $intPlantId + ' td:nth-child(4)').html());
                    $('#btnModalAdd').hide();
                    $('#btnModalEdit').show();
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
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

function addPlant(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la planta');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#selCountry').val()==-1) {
            $('#txtCode').focus();
            showModalError('Selecciona el pais al que pertenece la planta');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            if($('#numNumber').val().trim()=='') {
                $('#numNumber').focus();
                showModalError('Ingresa el número de planta');
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
            }else{
                $strQueryString = "strProcess=insertPlant&strPlant=" + $('#txtName').val().trim().toUpperCase() + "&intCountry=" + $('#selCountry').val() + "&intNumber=" + $('#numNumber').val();
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

function updatePlant(){
    showModalError('');
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()=='') {
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la planta');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        if($('#numNumber').val().trim()=='') {
            $('#numNumber').focus();
            showModalError('Ingresa el número de planta');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updatePlant&intPlant=" + $('#txtName').attr('intPlantId') +"&strPlant=" + $('#txtName').val().trim().toUpperCase() + "&intCountry=" + $('#selCountry').val() + "&intNumber=" + $('#numNumber').val();
            console.log("localhost/scrap/modules/plant/ajax.php?" + $strQueryString);
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

function deactivatePlant($intPlantId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intPlantId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar la planta?";
    }else{
        $strQuestion = "¿Deseas desactivar la planta?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
                $strQueryString = "strProcess=deactivatePlant&intPlantId=" + $intPlantId + "&intStatus=";
            if($('#lblDeactivate_' + $intPlantId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intPlantId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intPlantId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intPlantId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intPlantId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intPlantId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intPlantId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intPlantId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intPlantId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intPlantId).html('&#10006');
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
    $strSql = "SELECT PLN_ID,PLN_NAME,CNT_CODE,PLN_NUMBER,PLN_STATUS ";
    $strSql += "FROM PLN_PLANT ";
    $strSql += "LEFT JOIN CNT_COUNTRY ON CNT_ID = PLN_COUNTRY ";
    $strSql += "WHERE PLN_STATUS IN (0,1) ";
    $strSql += "ORDER BY ";
    $jsnGridData.strSql = $strSql;
    $jsnGridData.strSqlOrder = "PLN_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})