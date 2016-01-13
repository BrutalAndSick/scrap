function showModal($intRecordId) {
    $("body").css('overflow', 'hidden');
    showModalError('');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#btnModalSubmitRecord').attr('intRecordId',$intRecordId);
        if($intRecordId==0){
            $('#divModalTitle').html('Insertar');
            $('#btnModalSubmitRecord').val('insertar')
            $("#divModalForm :input").each(function() {
                if($(this).attr("type")=='text'){
                    $('#' + $(this).attr("id")).val('');
                }
            });
        }else{
            $('#divModalTitle').html('Editar');
            $('#btnModalSubmitRecord').val('editar')
            for($intIndex=0;$intIndex<$jsnGridData.arrFormField.length;$intIndex++){
                $('#txt' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD).val($('#td' + $jsnGridData.arrFormField[$intIndex].TBL_FIELD + '_' + $intRecordId).html());
            }
        };
        $('#divModalMain').slideDown('fast', function(){
            $('#divModalForm :input:enabled:visible:first').focus();
            $('#divModalForm :input:enabled:visible:first').select();
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
}