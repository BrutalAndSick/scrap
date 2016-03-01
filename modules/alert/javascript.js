$intCurrentAlert = 0;
$intCurrentScrap = 0;

function updateAlert(){
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#tdAlertsContainer').html('');
        $strQueryString = "strProcess=updateAlert&intScrap=" + $intCurrentScrap;
        console.log("ajax.php?" + $strQueryString);
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function(){
                $intCurrentScrap = 0;
                closeAlert();
                getAlertList();
            }
        });
    });
}

function refreshAlerts(){
    getAlertList();
}

function getAlertList(){
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#tdAlertsContainer').html('');
        $strQueryString = "strProcess=getAlertList&intAlert=" + $intCurrentAlert;
        console.log("ajax.php?" + $strQueryString);
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#tdAlertsContainer').html($jsnPhpScriptResponse.strResponse);
                getAlerts($jsnPhpScriptResponse.intFirstAlert);
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
}

function viewAlert($intScrap){
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $('#divAlertForm').html('');
        $strQueryString = "strProcess=viewAlert&intScrap=" + $intScrap;
        console.log("ajax.php?" + $strQueryString);
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#lblScrapFolio').html($intScrap);
                $('#divAlertForm').html($jsnPhpScriptResponse.strResponse);
                $('#divWorkingBackground').fadeOut('fast',function(){
                    $('#divAlertBackground').fadeIn('fast', function(){
                        $('#divAlertMain').slideDown('fast', function(){
                            $intCurrentScrap = $intScrap;
                        });
                    });
                });
            }
        });
    });
}

function closeAlert(){
    $('#divAlertMain').slideUp('fast',function(){
        $('#divAlertBackground').fadeOut('fast', function(){
            $("body").css('overflow', 'auto');
        })
    })
}

function getAlerts($intAlert){
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $intCurrentAlert = $intAlert;
        $('#tbodyAlert tr').remove();
        $('#tdAlertsContainer label').removeClass('label_alert_selected');
        $('#lblAlert_' + $intAlert).addClass('label_alert_selected');
        $strQueryString = "strProcess=getAlerts&intAlert=" + $intCurrentAlert;
        console.log("ajax.php?" + $strQueryString);
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#tbodyAlert').append($jsnPhpScriptResponse.strResponse);
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
};

function showModal() {
    $('#txtAlert').val('');
    $('#txtCost').val(0);
    console.clear();
    $("body").css('overflow', 'hidden');
    $('#divModalBackground').fadeIn('fast', function(){
        $('#divModalMain').slideDown('fast', function(){
            getData('Country');
            $('#txtAlert').focus();
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

function validateCost(){
    if($('#txtCost').val().trim()==''){
        $('#txtCost').val(0);
    }
}

$arrOptionDivs = ['Country','Plant','Ship','Division','Segment','ProfitCenter','APD','Area','Station','Line','Fault','Cause','ScrapCode','Project'];

function cleanSelects($strOption){
    for($intIndex=$arrOptionDivs.indexOf($strOption)+1;$intIndex<$arrOptionDivs.length;$intIndex++){
        $('#sel' + $arrOptionDivs[$intIndex]).find('option').remove().end();
        $('#div' + $arrOptionDivs[$intIndex]).hide();
    };

}

function getData($strOption) {
    cleanSelects($strOption);
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $strQueryString = "strProcess=" + $strOption;
        if($arrOptionDivs.indexOf($strOption)!=0){
            $strQueryString += '&int' + $arrOptionDivs[$arrOptionDivs.indexOf($strOption) - 1] + '=' + encodeURIComponent($('#sel' + $arrOptionDivs[$arrOptionDivs.indexOf($strOption) - 1]).val());
        }
        $strNextLoad = $arrOptionDivs[$arrOptionDivs.indexOf($strOption) + 1];
        console.log($strQueryString);
        console.log($strNextLoad);
        $.ajax({
            url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
            success: function($jsnPhpScriptResponse){
                $('#sel' + $strOption).find('option').remove().end();
                $('#sel' + $strOption).append($jsnPhpScriptResponse.strResponse);
                $('#div' + $strOption).show();
                $('#sel' + $strOption).focus();
                if($jsnPhpScriptResponse.intRecordCount==1){
                    getData($strNextLoad);
                }
                $('#divWorkingBackground').fadeOut();
                $("body").css('overflow', 'auto');
            }
        });
    });
}

function createAlert(){
    console.clear;
    $('#divModalError').hide();
    $('#divModalError').html('');
    if($('#txtAlert').val().trim()==''){
        $('#divModalError').html('Ingresa el nombre de la alerta');
        $('#divModalError').show();
        $('#txtAlert').focus();
        $('#txtAlert').select();
    }else{
        $("body").css('overflow', 'hidden');
        $('#divWorkingBackground').fadeIn('fast',function(){
            $strQueryString = "strProcess=CreateAlert&txtAlert=" + encodeURIComponent($('#txtAlert').val().trim().toUpperCase()) + "&intCost=" + $('#txtCost').val();
            $arrOptionDivs.forEach(function($strSelect){
                $strQueryString += "&int" + $strSelect + "=" + $('#sel' + $strSelect).val();
            })
            console.log($strQueryString );
            $.ajax({
                url: "ajax.php", data: $strQueryString, type: "POST", dataType: "json",
                success: function(){
                    $('#divWorkingBackground').fadeOut();
                    $("body").css('overflow', 'auto');
                    closeModal();
                }
            });
        });
    }
}