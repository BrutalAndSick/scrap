var $jsnParts = [];
var $arrSerials = [];
var objAutosuggest;

$(function(){
    $('#txtPartNumber').autoComplete({
        minChars : 3,
        source : function(term,suggest){
            try { objAutosuggest.abort(); } catch(e){}
            $strQueryString = "strProcess=getParts&strPart=" + term.toUpperCase();
            $.ajax({
                url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
                success: function($jsnPhpScriptResponse){
                    suggest($jsnPhpScriptResponse);
                }
            });
        },
        renderItem: function (item, search){
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" style="background-image:url(\'../../images/parts/' + item[2] + '\')" data-numerodeparte="' + item[0] + '" data-val="' + item[0] +'">' + item[0].replace(re, "<b>$1</b>") + '<br /><br /><span>' + item[1] + '<br /></span></div>';
        },
        onSelect: function(e, term, item){}
    });
    $("#txtPartNumber").keypress(function(e){
        if(e.which==13){
            $('#txtQuantity').focus();
            $('#txtQuantity').select();
        }
    });
    $("#txtQuantity").keypress(function(e){
        if(e.which==13){
            $('#txtLocation').focus();
        }
    });
});

$("#txtSerialNumber").keyup(function(e){
    if(e.which==13){
        $('#divModalError').html('');
        $('#divModalError').hide();

        if($('#txtSerialNumber').val().trim()==''){
            $('#divModalError').html('Ingresa el serial a capturar');
            $('#divModalError').show();
            $('#txtSerialNumber').focus();
            $('#txtSerialNumber').select();
        }else{
            if($arrSerials.indexOf($('#txtSerialNumber').val())!=-1){
                $('#divModalError').html('Ya se encuentra capturado el serial <b>' + $('#txtSerialNumber').val() + '<b/>');
                $('#divModalError').show();
                $('#txtSerialNumber').focus();
                $('#txtSerialNumber').select();
            }else{
                $arrSerials.push($('#txtSerialNumber').val());
                $strDivSerial = '<div id="divSerial_' + $('#txtSerialNumber').val() + '"><label onclick="removeSerial(\'' + $('#txtSerialNumber').val() + '\');" style="color: #FF2828;text-shadow: 0 1px 0 #EB1414;cursor: pointer;font-size: 12pt; margin-right: 10px;">&#10006</label>' + $('#txtSerialNumber').val() +'</div>';
                $('#divSerialesContainer').append($strDivSerial);
                $('#lblSerialesContador').html(parseInt($('#lblSerialesContador').html()) + 1);
                $('#txtSerialNumber').val('');
                if(parseInt($('#lblSerialesContador').html())==parseInt($('#lblSerialesCantidad').html())){
                    $('#lblSerialesContador').css('color',"#282828");
                    $('#btnCaptureSerials').show();
                    $('#txtSerialNumber').prop('disabled',true);
                }else{
                    $('#lblSerialesContador').css('color',"#FF0000");
                    $('#btnCaptureSerials').hide();
                    $('#txtSerialNumber').prop('disabled',false);
                    $('#txtSerialNumber').focus();
                };
            }
        }
    }
})

function removeSerial($strSerial){
    console.clear;
    $arrSerials.splice($arrSerials.indexOf($strSerial),1);
    $('#divSerial_' + $strSerial).remove();
    $('#lblSerialesContador').html(parseInt($('#lblSerialesContador').html()) - 1);
    if(parseInt($('#lblSerialesContador').html())==parseInt($('#lblSerialesCantidad').html())){
        $('#lblSerialesContador').css('color',"#282828");
        $('#btnCaptureSerials').show();
        $('#txtSerialNumber').prop('disabled',true);
    }else{
        $('#lblSerialesContador').css('color',"#FF0000");
        $('#btnCaptureSerials').hide();
        $('#txtSerialNumber').prop('disabled',false);
        $('#txtSerialNumber').focus();
    }
}

function printLabel() {
    console.clear;
    $('#divScrapLabel').css('background-color', '#FFFFFF');
    $('#divScrapLabel').css('color', '#000000');
    $('#divScrapLabel').printThis();
    $('#btnLastFinish').show();
}

function getData($strOption,$blnCommon) {
    console.clear;
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast', function () {
        $strQueryString = "strProcess=" + $strOption;
        switch ($strOption) {
            case 'Ship':
                $strNextLoad = 'Area';
                break;
            case 'Area':
                $('#selArea').find('option').remove().end();
                $('#divArea').hide();
                $('#selStation').find('option').remove().end();
                $('#divStation').hide();
                $('#selLine').find('option').remove().end();
                $('#divLine').hide();
                $('#tdSLine').html('');
                $('#selFault').find('option').remove().end();
                $('#divFault').hide();
                $('#selCause').find('option').remove().end();
                $('#divCause').hide();
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intShip=" + encodeURIComponent($('#selShip').val()) + "&strShip=" + encodeURIComponent($('#selShip option:selected').text());
                $strNextLoad = 'Station';
                break;
            case 'Station':
                $('#selStation').find('option').remove().end();
                $('#divStation').hide();
                $('#selLine').find('option').remove().end();
                $('#divLine').hide();
                $('#tdSLine').html('');
                $('#selFault').find('option').remove().end();
                $('#divFault').hide();
                $('#selCause').find('option').remove().end();
                $('#divCause').hide();
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intArea=" + encodeURIComponent($('#selArea').val()) + "&strArea=" + encodeURIComponent($('#selArea option:selected').text());
                $strNextLoad = 'Line';
                break;
            case 'Line':
                $('#selLine').find('option').remove().end();
                $('#divLine').hide();
                $('#tdSLine').html('');
                $('#selFault').find('option').remove().end();
                $('#divFault').hide();
                $('#selCause').find('option').remove().end();
                $('#divCause').hide();
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intStation=" + encodeURIComponent($('#selStation').val()) + "&strStation=" + encodeURIComponent($('#selStation option:selected').text());
                $strNextLoad = 'Fault';
                break;
            case 'Fault':
                $('#selFault').find('option').remove().end();
                $('#divFault').hide();
                $('#selCause').find('option').remove().end();
                $('#divCause').hide();
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intLine=" + encodeURIComponent($('#selLine').val()) + "&strLine=" + encodeURIComponent($('#selLine option:selected').text());
                $strNextLoad = 'Cause';
                break;
            case 'Cause':
                $('#selCause').find('option').remove().end();
                $('#divCause').hide();
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intFault=" + encodeURIComponent($('#selFault').val()) + "&strFault=" + encodeURIComponent($('#selFault option:selected').text());
                $strNextLoad = 'ScrapCode';
                break;
            case 'ScrapCode':
                $('#selScrapCode').find('option').remove().end();
                $('#divScrapCode').hide();
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intCause=" + encodeURIComponent($('#selCause').val()) + "&strCause=" + encodeURIComponent($('#selCause option:selected').text());
                $strNextLoad = 'Project';
                break;
            case 'Project':
                $('#selProject').find('option').remove().end();
                $('#divProject').hide();
                $('#divParts').hide();
                $('#tdProject').html('');
                $('#trCaptureParts').hide();
                $strQueryString += "&intScrapCode=" + encodeURIComponent($('#selScrapCode').val()) + "&strScrapCode=" + encodeURIComponent($('#selScrapCode option:selected').text());
                $strNextLoad = 'Part';
                break;
            case 'Part':
                $('#trCaptureParts').hide();
                $strQueryString += "&intProject=" + encodeURIComponent($('#selProject').val()) + "&strProject=" + encodeURIComponent($('#selProject option:selected').text());
                break;
        }
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function ($jsnPhpScriptResponse) {
                switch($strOption){
                    case 'Part':
                        $('#txtPartNumber').val('');
                        $('#txtQuantity').val(1);
                        $('#txtLocation').val('');
                        $('#tblParts tr').remove();
                        $arrPartes = [];
                        $('#divParts').show('fast',function(){
                            $('#txtPartNumber').focus();
                            $('#txtPartNumber').select();
                        });
                        break;
                    default:
                        $('#sel' + $strOption).find('option').remove().end();
                        $('#sel' + $strOption).append($jsnPhpScriptResponse.strResponse);
                        //if($jsnPhpScriptResponse.intRecordCount !=1){
                            $('#div' + $strOption).show();
                            $('#sel' + $strOption).focus();
                        //}
                        if(typeof($blnCommon)!='undefined') {
                            getCommonCapture($strOption);
                        }else{
                            if($jsnPhpScriptResponse.intRecordCount==1){
                                getData($strNextLoad);
                            }
                        };
                        break;
                }
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
}

$('document').ready(function () {
    getData('Ship',true);
});

function getCommonCapture($strOption){
    console.clear;
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast', function () {
        $strQueryString = "strProcess=getCommon" + $strOption;
        switch ($strOption) {
            case 'Ship':
                $strNextLoad = 'Area';
                break;
            case 'Area':
                $strNextLoad = 'Station';
                break;
            case 'Station':
                $strNextLoad = 'Line';
                break;
            case 'Line':
                $strNextLoad = 'Fault';
                break;
            case 'Fault':
                $strNextLoad = 'Cause';
                break;
            case 'Cause':
                $strNextLoad = 'ScrapCode';
                break;
            case 'ScrapCode':
                $strNextLoad = 'Project';
                break;
            case 'Project':
                //$strNextLoad = '';
                break;
        }
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function ($jsnPhpScriptResponse) {
                if($jsnPhpScriptResponse.intCommon!=-1){
                    $('#sel' + $strOption + ' option[value="' + $jsnPhpScriptResponse.intCommon + '"]').prop('selected',true);
                    getData($strNextLoad,true);
                }
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
}

function addParte(){
    console.clear;
    $('#lblPartError').html('');
    $('#lblPartError').hide();
    if($('#txtPartNumber').val()!='' && $('#txtQuantity').val()!=''){
        $strQueryString = "strProcess=PartData&strPart=" + encodeURIComponent($('#txtPartNumber').val().toUpperCase().trim()) + "&intQty=" + encodeURIComponent($('#txtQuantity').val().toUpperCase().trim()) + "&strLocation=" + encodeURIComponent($('#txtLocation').val().toUpperCase().trim()) + "&strSerials=";
        $strSerials = '';
        if($arrSerials.length>0){
            $arrSerials.forEach(function($strSerial){
                $strSerials += $strSerial + '|%|';
            })
        };
        $strQueryString += $strSerials;
        $arrSerials = [];
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                if($jsnPhpScriptResponse.intPartId==0){
                    $('#lblPartError').html('Número de parte no encontrada, verifique');
                    $('#lblPartError').show();
                }else{
                    if($jsnParts.indexOf($jsnPhpScriptResponse.intPartId)!=-1){
                        removePart($jsnPhpScriptResponse.intPartId);
                    }
                    $jsnParts.push($jsnPhpScriptResponse.intPartId);
                    $('#tblParts > tbody:last-child').append($jsnPhpScriptResponse.strResponse);
                    $('#txtPartNumber').val('');
                    $('#txtQuantity').val(1);
                    $('#txtLocation').val('');
                    $('#txtPartNumber').focus();
                    $('#btnNext').show();
                }
            }
        });
    }else{
        $('#lblPartError').html('Ingresa número de parte y cantidad');
        $('#lblPartError').show();
    };
}

function goStep2(){
    console.clear;
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast', function () {
        $strQueryString = "strProcess=addParts&intParts=" + $jsnParts.length;
        for($intIndex=0;$intIndex<$jsnParts.length;$intIndex++){
            $strQueryString += "&intPartId_" + $intIndex + "=" + $jsnParts[$intIndex];
            $strQueryString += "&intPartQty_" + $intIndex + "=" + $('#txtPartQty_' + $jsnParts[$intIndex]).val();
            $strQueryString += "&intPartLoc_" + $intIndex + "=" + $('#txtPartLoc_' + $jsnParts[$intIndex]).val();
            $strQueryString += "&intPartSrl_" + $intIndex + "=" + $('#txtPartSrl_' + $jsnParts[$intIndex]).val();
        }
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#trCaptureGeneral').slideUp('fast',function(){
                    $('#trStep2').slideDown('fast',function(){
                        $('#txtComments').focus();
                    });
                });
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });

    });

}

function verifyComments(){
    console.clear;
//    if($('#txtComments').val().trim()==''){
//        $('#btnFinish').hide();
//    }else{
//        $('#btnFinish').show();
//    }
}

function insertScrapRecord(){
    console.clear;
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast', function () {
        $strQueryString = "strProcess=insertScrapRecord";
        $strQueryString += "&strComments=" + encodeURIComponent($('#txtComments').val().trim());
        $strQueryString += "&strWhy1=" + encodeURIComponent($('#txtWhy1').val().trim());
        $strQueryString += "&strWhy2=" + encodeURIComponent($('#txtWhy2').val().trim());
        $strQueryString += "&strWhy3=" + encodeURIComponent($('#txtWhy3').val().trim());
        $strQueryString += "&strWhy4=" + encodeURIComponent($('#txtWhy4').val().trim());
        $strQueryString += "&strWhy5=" + encodeURIComponent($('#txtWhy5').val().trim());
        $strQueryString += "&strAction1=" + encodeURIComponent($('#txtAction1').val().trim());
        $strQueryString += "&strAction2=" + encodeURIComponent($('#txtAction2').val().trim());
        $strQueryString += "&strAction3=" + encodeURIComponent($('#txtAction3').val().trim());
        $strQueryString += "&strAction4=" + encodeURIComponent($('#txtAction4').val().trim());
        $strQueryString += "&strAction5=" + encodeURIComponent($('#txtAction5').val().trim());
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#trStep2').hide('fast',function(){
                    $('#tdLabelBarcode').barcode($jsnPhpScriptResponse.strFolio, "code39", {barHeight: 22, barWidth: 2, showHRI: true, fontSize: 8, addQuietZone: false, bgColor: 'transparent', output: 'css'});
                    $('#tdLabelAmount').html('$ ' + $jsnPhpScriptResponse.intAmount + ' ');
                    $('#tdLabelDate').html($jsnPhpScriptResponse.intDate.substr(0,4) + '-' + $jsnPhpScriptResponse.intDate.substr(4,2) + '-' + $jsnPhpScriptResponse.intDate.substr(6,2));
                    $('#tdLabelTime').html($jsnPhpScriptResponse.intTime.substr(0,2) + ':' + $jsnPhpScriptResponse.intTime.substr(2,2) + ':' + $jsnPhpScriptResponse.intTime.substr(4,2));
                    $('#tdLabelUser').html($jsnPhpScriptResponse.strUser.substr(0,28));
                    $('#tdLabelArea').html($jsnPhpScriptResponse.strArea.substr(0,25));
                    $('#tdLabelStation').html($jsnPhpScriptResponse.strStation.substr(0,35));
                    $('#tdLabelLine').html($jsnPhpScriptResponse.strLine.substr(0,19));
                    $('#tdLabelFault').html($jsnPhpScriptResponse.strFault.substr(0,64));
                    $('#tdLabelCause').html($jsnPhpScriptResponse.strCause.substr(0,19));
                    $('#tdLabelScrapCode').html($jsnPhpScriptResponse.strScrapCode.substr(0,32));
                    $('#tdLabelParts').html($jsnPhpScriptResponse.strParts.substr(0,360));
                    $('#tdLabelCommentsActions').html($jsnPhpScriptResponse.strCommentsActions.substr(0,360));
                    $('#trLabel').show('fast');
                });
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });

    });
}

function removePart($intPartNumber){
    console.clear;
    $('#trPart_' + $intPartNumber).remove();
    $jsnParts.splice($jsnParts.indexOf($intPartNumber),1);
    if($jsnParts.length==0){
        $('#btnNext').hide();
    };
}

function editPart($intPartNumber){
    console.clear;
    $('#txtPartNumber').val($('#txtPartPrt_' + $intPartNumber).val());
    $('#txtQuantity').val($('#txtPartQty_' + $intPartNumber).val());
    $('#txtLocation').val($('#txtPartLoc_' + $intPartNumber).val());
}

function showSeriales(){
    console.clear;
    $('#divModalError').hide();
    $('#divModalError').html('');
    if($('#txtPartNumber').val()==''){
        $('#lblPartError').html('Ingresa Número de parte y cantidad');
        $('#lblPartError').show();
        $('#txtPartNumber').focus();
    }else{
        $("body").css('overflow','hidden');
        $('#divModalBackground').fadeIn('fast',function(){
            $('#lblSerialesContador').html('0');
            $('#lblSerialesContador').css('color',"#FF0000")
            $('#lblSerialesCantidad').html($('#txtQuantity').val());
            $('#lblSerialesNoParte').html($('#txtPartNumber').val());
            $('#txtSerialNumber').prop('disabled',false);
            $arrSerials = [];
            $('#divSerialesContainer').html('');
            $('#txtSerialNumber').val('');
            $('#divModalMain').slideDown('fast',function(){
                $('#txtSerialNumber').focus();
            })
        })
    }

}

function hideSeriales(){
    console.clear;
    $('#lblSerialesErrors').hide();
    $('#lblSerialesErrors').html('');
    if($('#lblSerialesContador').css('color')=='rgb(255, 0, 0)'){
        $('#lblSerialesErrors').html('El total de seriales capturados no coincide con la cantidad indicada, por favor verifica');
        $('#lblSerialesErrors').slideDown('fast');
    }else{
        $('#divSeriales').slideUp('slow',function(){
            $('#divModal').fadeOut('fast',function(){
                addParte();
                $("body").css('overflow','visible');
            })
        })
    }
}

function closeModal($blnClean){
    if($blnClean){
        $arrSerials = [];
    }
    $('#divModalMain').slideUp('fast',function(){
        $('#divModalBackground').fadeOut('fast', function(){
            $("body").css('overflow', 'auto');
        })
    })
}

