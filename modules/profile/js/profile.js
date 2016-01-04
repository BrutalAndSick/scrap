$arrMenu = [];

function showModal() {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#tblMenu tr').remove();
        $('#txtName').val('');
        $strQueryString = "intProcess=0";
        $.ajax({url : "getmenu.php", data : $strQueryString, type : "POST", dataType : "json",
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
            $strQueryString = "intProcess=0&strProfile=" + $('#txtName').val().trim().toUpperCase() + "&strSelectedMenu=" + $strSelectedMenu;
            $.ajax({url : "getprofile.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                    closeModal();
                    console.log($objJson);
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
            $strQueryString = "intProcess=1&intProfileId=" + $intProfileId;
            $.ajax({url : "getprofile.php", data : $strQueryString, type : "POST", dataType : "json",
                success : function($objJson){
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                    closeModal();
                    console.log($objJson);
                }
            });




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
        });
    }
};

function editProfile($intProfileId){

};

$('document').ready(function(){
    $('#divModalMain').css('height',($('body').css('height').replace('px','').replace(' ','') - 100) + "px");
    $('#divModalForm').css('height',($('#divModalMain').css('height').replace('px','').replace(' ','') - 170) + "px");
})