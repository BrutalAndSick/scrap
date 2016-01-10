$arrDivision = [];

function showModal($intSegmentId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblDivision tr').remove();
        if($intSegmentId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
            $strQueryString = "strProcess=getDivision";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrDivision = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrDivision.push($objJson[$intIndex].intDivision);
                        $('#tblDivision').append($objJson[$intIndex].strHtml);
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#lblEdit_' + $intSegmentId).attr('segmentname'));
            $('#txtName').attr('intSegmentId',$intSegmentId);
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
            $strQueryString = "strProcess=getDivisionSegment&intSegmentId=" + $intSegmentId;
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrDivision = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $arrDivision.push($objJson[$intIndex].intDivision);
                        $('#tblDivision').append($objJson[$intIndex].strHtml);
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

function switchSelected($intDivision){
    if($('#tdDivision_' + $intDivision).attr('class')=='tdNonActive'){
        $('#tdDivision_' + $intDivision).removeClass('tdNonActive');
        $('#tdDivision_' + $intDivision).addClass('tdActive');
        $('#tdDivision_' + $intDivision).html('&#10004');
    }else{
        $('#tdDivision_' + $intDivision).removeClass('tdActive');
        $('#tdDivision_' + $intDivision).addClass('tdNonActive');
        $('#tdDivision_' + $intDivision).html('&#10006');
    }
}

function addSegment(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del segmento');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedDivision = false;
        $strSelectedDivision = '';
        for($intIndex=0;$intIndex<$arrDivision.length;$intIndex++){
            if($('#tdDivision_' + $arrDivision[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedDivision = true;
                $strSelectedDivision += $arrDivision[$intIndex] + "|";
            }
        }
        if(!$blnSelectedDivision){
            showModalError('Selecciona al menos una división');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertSegment&strSegment=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedDivision=" + $strSelectedDivision;
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

function deactivateSegment($intSegmentId){
    $strQuestion = "";
    if($('#lblDeactivate_' + $intSegmentId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el segmento?";
    }else{
        $strQuestion = "¿Deseas desactivar el segmento?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateSegment&intSegmentId=" + $intSegmentId + "&intStatus=";
            if($('#lblDeactivate_' + $intSegmentId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivate_' + $intSegmentId).attr('currentValue')==0){
                        $('#lblDeactivate_' + $intSegmentId).removeClass('labelActionsRed');
                        $('#lblDeactivate_' + $intSegmentId).addClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intSegmentId).attr('currentValue',1);
                        $('#lblDeactivate_' + $intSegmentId).html('&#10004;');
                    }else{
                        $('#lblDeactivate_' + $intSegmentId).removeClass('labelActionsGreen');
                        $('#lblDeactivate_' + $intSegmentId).addClass('labelActionsRed');
                        $('#lblDeactivate_' + $intSegmentId).attr('currentValue',0);
                        $('#lblDeactivate_' + $intSegmentId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

function editSegment(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del segmento');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedDivision = false;
        $strSelectedDivision = '';
        for($intIndex=0;$intIndex<$arrDivision.length;$intIndex++){
            if($('#tdDivision_' + $arrDivision[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedDivision = true;
                $strSelectedDivision += $arrDivision[$intIndex] + "|";
            }
        }
        if(!$blnSelectedDivision){
            showModalError('Selecciona al menos una division');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateSegment&intSegmentId=" + $('#txtName').attr('intSegmentId') + "&strSegment=" + encodeURIComponent($('#txtName').val().trim().toUpperCase()) + "&strSelectedDivision=" + $strSelectedDivision;
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
    $jsnGridData.strSql = "SELECT SGM_ID, SGM_NAME, SGM_STATUS FROM SGM_SEGMENT WHERE SGM_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "SGM_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})