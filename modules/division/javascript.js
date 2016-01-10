$arrPlant = [];

function showModal($intDivisionId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblPlant tr').remove();
        if($intDivisionId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
            $strQueryString = "strProcess=getPlant";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    console.log($objJson);
                    $arrPlant = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrPlant.push($objJson[$intIndex].intPlant);
                        $('#tblPlant').append($objJson[$intIndex].strHtml);
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#lblEdit_' + $intDivisionId).attr('divisionname'));
            $('#txtName').attr('intDivisionId',$intDivisionId);
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
            $strQueryString = "strProcess=getPlantDivision&intDivisionId=" + $intDivisionId;
            console.log("ajax.php?" + $strQueryString);
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    console.log($objJson);
                    $arrPlant = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrPlant.push($objJson[$intIndex].intPlant);
                        $('#tblPlant').append($objJson[$intIndex].strHtml);
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

function switchSelected($intPlant){
    if($('#tdPlant_' + $intPlant).attr('class')=='tdNonActive'){
        $('#tdPlant_' + $intPlant).removeClass('tdNonActive');
        $('#tdPlant_' + $intPlant).addClass('tdActive');
        $('#tdPlant_' + $intPlant).html('&#10004');
    }else{
        $('#tdPlant_' + $intPlant).removeClass('tdActive');
        $('#tdPlant_' + $intPlant).addClass('tdNonActive');
        $('#tdPlant_' + $intPlant).html('&#10006');
    }
}

function addDivision(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre de la división');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedPlant = false;
        $strSelectedPlant = '';
        for($intIndex=0;$intIndex<$arrPlant.length;$intIndex++){
            if($('#tdPlant_' + $arrPlant[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedPlant = true;
                $strSelectedPlant += $arrPlant[$intIndex] + "|";
            }
        }
        if(!$blnSelectedPlant){
            showModalError('Selecciona al menos una planta');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertDivision&strDivision=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedPlant=" + $strSelectedPlant;
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

function deactivateDivision($intDivisionId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intDivisionId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar la división?";
    }else{
        $strQuestion = "¿Deseas desactivar la división?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateDivision&intDivisionId=" + $intDivisionId + "&intStatus=";
            if($('#lblDeactivate_' + $intDivisionId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intDivisionId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intDivisionId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intDivisionId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intDivisionId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intDivisionId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intDivisionId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intDivisionId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intDivisionId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intDivisionId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

function editDivision(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del perfil');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedPlant = false;
        $strSelectedPlant = '';
        for($intIndex=0;$intIndex<$arrPlant.length;$intIndex++){
            if($('#tdPlant_' + $arrPlant[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedPlant = true;
                $strSelectedPlant += $arrPlant[$intIndex] + "|";
            }
        }
        if(!$blnSelectedPlant){
            showModalError('Selecciona al menos una planta');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateDivision&intDivisionId=" + $('#txtName').attr('intDivisionId') + "&strDivision=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedPlant=" + $strSelectedPlant;
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
    $jsnGridData.strSql = "SELECT DVS_ID, DVS_NAME, DVS_STATUS FROM DVS_DIVISION WHERE DVS_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "DVS_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})