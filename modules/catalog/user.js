$("#txtUSR_PERSONALNUMBER").attr('max','9999999');
$('#txtUSR_WINDOWSUSER').prop('disabled',true);
$('#txtUSR_WINDOWSUSER').css('background-color','#DCDCDC');
$('#txtUSR_NAME').prop('disabled',true);
$('#txtUSR_NAME').css('background-color','#DCDCDC');

$('#txtUSR_PERSONALNUMBER').change(function(){
    console.clear();
    showModalError('');
    $('#divModalButtons').hide();
    $('#divModalWorking').show();
    $('#divModalBackground').fadeIn('fast', function(){
        $strQueryString = "intTableId=0&strProcess=getWSUserData&strPersonalNumber=" + $('#txtUSR_PERSONALNUMBER').val();
        console.log($jsnGridData.strAjaxUrl + "?" +$strQueryString);
        $.ajax({
            url: $jsnGridData.strAjaxUrl, data: $strQueryString, type: "POST", dataType: "json",
            success: function ($jsnPhpScriptResponse) {
                console.log($jsnPhpScriptResponse);
                if($jsnPhpScriptResponse.strCostCenter==''){
                    showModalError('El número de personal <b>' + $('#txtUSR_PERSONALNUMBER').val() + '</b> no esta registrado en la BD de RH, verifica');
                    $('#txtUSR_PERSONALNUMBER').focus();
                    $('#txtUSR_PERSONALNUMBER').select();
                    $('#txtUSR_WINDOWSUSER').val('');
                    $('#txtUSR_NAME').val('');
                }else{
                    $('#txtUSR_PERSONALNUMBER').val($jsnPhpScriptResponse.strPersonalNumber)
                    $('#txtUSR_WINDOWSUSER').val($jsnPhpScriptResponse.strWindowsUserId);
                    $('#txtUSR_NAME').val($jsnPhpScriptResponse.strFullName);
                }
                $('#divModalWorking').hide();
                $('#divModalButtons').show();
            }
        });
    })
})

$('#txtUSR_PERSONALNUMBER').on('keydown', function(e) {
    if(e.keyCode != 46 && // delete
        e.keyCode != 8 && // backspace
        e.keyCode != 9 && // tab
        (e.keyCode < 48 || e.keyCode > 57) && //numbers above keyboard
        (e.keyCode < 96 || e.keyCode > 105) // numeric keyboard numbers
    ) {
        e.preventDefault();
    }
});