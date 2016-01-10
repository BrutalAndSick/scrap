$arrMenu = [];

function showModal($intProfileId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblMenu tr').remove();
        if($intProfileId==0){
            $('#divModalTitle').html('Insertar');
            $('#txtName').val('');
            $('#btnModalEdit').hide();
            $('#btnModalAdd').show();
            $strQueryString = "strProcess=getMenu";
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrMenu = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $('#tblMenu').append('<tr><td colspan="2">' + $objJson[$intIndex].name + '</td></tr>');
                        for($intSubIndex=0;$intSubIndex<$objJson[$intIndex].menu.length;$intSubIndex++){
                            $arrMenu.push($objJson[$intIndex].menu[$intSubIndex].id);
                            $('#tblMenu').append('<tr><td id="tdMenu_' + $objJson[$intIndex].menu[$intSubIndex].id + '" class="tdNonActive" onclick="switchSelected(' + $objJson[$intIndex].menu[$intSubIndex].id + ')">&#10006</td><td>' + $objJson[$intIndex].menu[$intSubIndex].name + '</td></tr>');
                        }
                    }
                    $('#divModalMain').slideDown('fast', function(){
                        $('#txtName').focus();
                    });
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#txtName').val($('#lblEditProfile_' + $intProfileId).attr('profilename'));
            $('#txtName').attr('intProfileId',$intProfileId);
            $('#btnModalAdd').hide();
            $('#btnModalEdit').show();
            $strQueryString = "strProcess=getMenuProfile&intProfileId=" + $intProfileId;
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $arrMenu = [];
                    for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                        $('#tblMenu').append('<tr><td colspan="2">' + $objJson[$intIndex].name + '</td></tr>');
                        for($intSubIndex=0;$intSubIndex<$objJson[$intIndex].menu.length;$intSubIndex++){
                            $arrMenu.push($objJson[$intIndex].menu[$intSubIndex].id);
                            if($objJson[$intIndex].menu[$intSubIndex].selected!=0){
                                $('#tblMenu').append('<tr><td id="tdMenu_' + $objJson[$intIndex].menu[$intSubIndex].id + '" class="tdActive" onclick="switchSelected(' + $objJson[$intIndex].menu[$intSubIndex].id + ')">&#10004</td><td>' + $objJson[$intIndex].menu[$intSubIndex].name + '</td></tr>');
                            }else{
                                $('#tblMenu').append('<tr><td id="tdMenu_' + $objJson[$intIndex].menu[$intSubIndex].id + '" class="tdNonActive" onclick="switchSelected(' + $objJson[$intIndex].menu[$intSubIndex].id + ')">&#10006</td><td>' + $objJson[$intIndex].menu[$intSubIndex].name + '</td></tr>');
                            }
                        }
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

function switchSelected($intMenu){
    if($('#tdMenu_' + $intMenu).attr('class')=='tdNonActive'){
        $('#tdMenu_' + $intMenu).removeClass('tdNonActive');
        $('#tdMenu_' + $intMenu).addClass('tdActive');
        $('#tdMenu_' + $intMenu).html('&#10004');
    }else{
        $('#tdMenu_' + $intMenu).removeClass('tdActive');
        $('#tdMenu_' + $intMenu).addClass('tdNonActive');
        $('#tdMenu_' + $intMenu).html('&#10006');
    }
}

function addProfile(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del perfil');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedMenu = false;
        $strSelectedMenu = '';
        for($intIndex=0;$intIndex<$arrMenu.length;$intIndex++){
            if($('#tdMenu_' + $arrMenu[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedMenu = true;
                $strSelectedMenu += $arrMenu[$intIndex] + "|";
            }
        }
        if(!$blnSelectedMenu){
            showModalError('Selecciona al menos un menu');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=insertProfile&strProfile=" + $('#txtName').val().trim().toUpperCase() + "&strSelectedMenu=" + $strSelectedMenu;
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

function showModalError($strError){
    if($strError==''){
        $('#divModalError').hide();
        $('#divModalError').html('');
    }else{
        $('#divModalError').html('&#8854; ' + $strError);
        $('#divModalError').slideDown('fast',function(){});
    }
};

function deactivateProfile($intProfileId){
    $strQuestion = "";
    if($('#lblDeactivateProfile_' + $intProfileId).attr('currentValue')==0){
        $strQuestion = "¿Deseas activar el perfil?";
    }else{
        $strQuestion = "¿Deseas desactivar el perfil?";
    }
    if(confirm($strQuestion)){
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=deactivateProfile&intProfileId=" + $intProfileId + "&intStatus=";
            if($('#lblDeactivateProfile_' + $intProfileId).attr('currentValue')==0){
                $strQueryString += 1;
            }else{
                $strQueryString += 0;
            }
            $.ajax({url : "ajax.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    if($('#lblDeactivateProfile_' + $intProfileId).attr('currentValue')==0){
                        $('#lblDeactivateProfile_' + $intProfileId).removeClass('labelActionsRed');
                        $('#lblDeactivateProfile_' + $intProfileId).addClass('labelActionsGreen');
                        $('#lblDeactivateProfile_' + $intProfileId).attr('currentValue',1);
                        $('#lblDeactivateProfile_' + $intProfileId).html('&#10004;');
                    }else{
                        $('#lblDeactivateProfile_' + $intProfileId).removeClass('labelActionsGreen');
                        $('#lblDeactivateProfile_' + $intProfileId).addClass('labelActionsRed');
                        $('#lblDeactivateProfile_' + $intProfileId).attr('currentValue',0);
                        $('#lblDeactivateProfile_' + $intProfileId).html('&#10006');
                    }
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                }
            });
        });
    }
};

function editProfile(){
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    if($('#txtName').val().trim()==''){
        $('#txtName').focus();
        showModalError('Ingresa el nombre del perfil');
        $('#divModalWorking').hide();
        $('#divModalButtons').show();
    }else{
        $blnSelectedMenu = false;
        $strSelectedMenu = '';
        for($intIndex=0;$intIndex<$arrMenu.length;$intIndex++){
            if($('#tdMenu_' + $arrMenu[$intIndex]).attr('class')=='tdActive'){
                $blnSelectedMenu = true;
                $strSelectedMenu += $arrMenu[$intIndex] + "|";
            }
        }
        if(!$blnSelectedMenu){
            showModalError('Selecciona al menos un menu');
            $('#divModalWorking').hide();
            $('#divModalButtons').show();
        }else{
            $strQueryString = "strProcess=updateProfile&intProfileId=" + $('#txtName').attr('intProfileId') + "&strProfile=" + $('#txtName').val().trim().toUpperCase() + "&strSelectedMenu=" + $strSelectedMenu;
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
    $jsnGridData.strSql = "SELECT PRF_ID, PRF_NAME, PRF_STATUS FROM PRF_PROFILE WHERE PRF_STATUS IN (0,1) ORDER BY ";
    $jsnGridData.strSqlOrder = "PRF_ID DESC";
    $jsnGridData.intSqlNumberOfColumns = $('#theadGrid tr th').length;
    $jsnGridData.strAjaxProcess = "updateGrid";
    gridUpdate();
})