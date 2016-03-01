$("#txtCantidad").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });

$arrDivs = Array('APD','Area','Tecnologia','Linea','Defecto','Causa','CodigoScrap','Next');
var objAutosuggest;
$arrPartes = Array();

function cleanSelect($strSelect){

    $('#tdCosto').html('0.00');
    for($intIx=$.inArray($strSelect,$arrDivs);$intIx<$arrDivs.length;$intIx++){
        $('#div' + $arrDivs[$intIx]).hide();
        $('#sel' + $arrDivs[$intIx])
            .find('option')
            .remove()
            .end();
        $('#td' + $arrDivs[$intIx]).html('');
    }
    $('#divPartes').hide();
    $('#tdPartes').html('');
    $('#tdComentario').html('');
    $('#txtCantidad').val(1);
    $('#txtNumerodeParte').val('');
    $('#txtNumerodeParte').attr('strParte','');
    $('#selUbicacion').val($('#selUbicacion option:first').val());
    $arrPartes = Array();
    $strCleanParts = '';
    $('#divPartesContainer').html($strCleanParts);

};

function getAPD(){
    cleanSelect('APD');
    if($('#selProyecto').val()!=-1){
        $arrProyecto = $('#selProyecto').val().split('|');
        $('#tdSegmento').html($arrProyecto[3]);
        $('#tdProfitCenter').html($arrProyecto[5]);
        $strQueryString = "intProc=0&intProyecto=" + $arrProyecto[0] + "&strProyecto=" + $arrProyecto[1] + "&intSegmento=" + $arrProyecto[2] + "&strSegmento=" + $arrProyecto[3] + "&intProfitCenter=" + $arrProyecto[4] + "&strProfitCenter=" + $arrProyecto[5];
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                if($jsnData.arrData.length>1){
                    $('#selAPD').append('<option value="-1">- Seleccione -</option>');
                    $('#selAPD').val(-1);
                };
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selAPD').append('<option value="' + $jsnData.arrData[$intIx].intAPD + '">' + $jsnData.arrData[$intIx].strAPD + '</option>');
                }
                $('#divAPD').slideDown('fast',function(){
                    $('#selAPD').focus();
                    if($jsnData.arrData.length<2) {
                        getArea();
                    }
                    //###QUITAR!!!###
                    //addParts();
                    //###QUITAR!!!###
                });
            }
        });
    };
}

function getArea(){
    cleanSelect('Area');
    $intAPD = $('#selAPD').val();
    $strAPD = $('#selAPD option:selected').text();
    $strQueryString = "intProc=1&intAPD=" + $intAPD + "&strAPD=" + $strAPD;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selArea').append('<option value="-1">- Seleccione -</option>');
                $('#selArea').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selArea').append('<option value="' + $jsnData.arrData[$intIx].intArea + '">' + $jsnData.arrData[$intIx].strArea + '</option>');
            }
            $('#divArea').slideDown('fast');
            $('#divArea').slideDown('fast',function(){
                $('#selArea').focus();
                if($jsnData.arrData.length<2) {
                    getTecnologia();
                }
            });

        }
    });
}

function getTecnologia(){
    cleanSelect('Tecnologia');
    $intArea = $('#selArea').val();
    $strArea = $("#selArea option:selected").text();
    $('#tdArea').html($strArea);
    $strQueryString = "intProc=2&intArea=" + $intArea + "&strArea=" + $strArea;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selTecnologia').append('<option value="-1">- Seleccione -</option>');
                $('#selTecnologia').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selTecnologia').append('<option value="' + $jsnData.arrData[$intIx].intTecnologia + '">' + $jsnData.arrData[$intIx].strTecnologia + '</option>');
            }
            $('#divTecnologia').slideDown('fast',function(){
                $('#selTecnologia').focus();
                if($jsnData.arrData.length<2) {
                    getLinea();
                }
            });
        }
    });
}

function getLinea(){
    cleanSelect('Linea');
    $intTecnologia = $('#selTecnologia').val();
    $strTecnologia = $("#selTecnologia option:selected").text();
    $('#tdTecnologia').html($strTecnologia);
    $strQueryString = "intProc=3&intTecnologia=" + $intTecnologia + "&strTecnologia=" + $strTecnologia;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selLinea').append('<option value="-1">- Seleccione -</option>');
                $('#selLinea').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selLinea').append('<option value="' + $jsnData.arrData[$intIx].intLinea + '">' + $jsnData.arrData[$intIx].strLinea + '</option>');
            }
            $('#divLinea').slideDown('fast',function(){
                $('#selLinea').focus();
                if($jsnData.arrData.length<2) {
                    getDefecto();
                }
            });

        }
    });
}

function getDefecto(){
    cleanSelect('Defecto');
    $intLinea = $('#selLinea').val();
    $strLinea = $("#selLinea option:selected").text();
    $('#tdLinea').html($strLinea);
    $strQueryString = "intProc=4&intLinea=" + $intLinea + "&strLinea=" + $strLinea;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selDefecto').append('<option value="-1">- Seleccione -</option>');
                $('#selDefecto').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selDefecto').append('<option value="' + $jsnData.arrData[$intIx].intDefecto + '">' + $jsnData.arrData[$intIx].strDefecto + '</option>');
            }
            $('#divDefecto').slideDown('fast',function(){
                $('#selDefecto').focus();
                if($jsnData.arrData.length<2) {
                    getCausa();
                }
            });
        }
    });
}

function getCausa(){
    cleanSelect('Causa');
    $intDefecto = $('#selDefecto').val();
    $strDefecto = $("#selDefecto option:selected").text();
    $('#tdDefecto').html($strDefecto);
    $strQueryString = "intProc=5&intDefecto=" + $intDefecto + "&strDefecto=" + $strDefecto;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selCausa').append('<option value="-1">- Seleccione -</option>');
                $('#selCausa').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selCausa').append('<option value="' + $jsnData.arrData[$intIx].intCausa + '">' + $jsnData.arrData[$intIx].strCausa + '</option>');
            }
            $('#divCausa').slideDown('fast',function(){
                $('#selCausa').focus();
                if($jsnData.arrData.length<2) {
                    getCodigoScrap();
                }
            });
        }
    });
}

function getCodigoScrap(){
    cleanSelect('CodigoScrap');
    $intCausa = $('#selCausa').val();
    $strCausa = $("#selCausa option:selected").text();
    $('#tdCausa').html($strCausa);
    $strQueryString = "intProc=6&intCausa=" + $intCausa + "&strCausa=" + $strCausa;
    $.ajax({
        data : $strQueryString,
        type : "POST",
        dataType : "json",
        url : "getdata.php",
        success : function($jsnData){
            if($jsnData.arrData.length>1){
                $('#selCodigoScrap').append('<option value="-1">- Seleccione -</option>');
                $('#selCodigoScrap').val(-1);
            };
            for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                $('#selCodigoScrap').append('<option value="' + $jsnData.arrData[$intIx].intCodigoScrap + '">' + $jsnData.arrData[$intIx].strCodigoScrap + '</option>');
            }
            $('#divCodigoScrap').slideDown('fast',function(){
                $('#selCodigoScrap').focus();
                if($jsnData.arrData.length<2) {
                    showNext();
                }
            });
        }
    });
}

function showNext(){
    $('#divNext').slideDown('fast');
    $('#btnNext').focus();
}

function showGenerales(){
    $('#divPartes').slideUp('fast',function(){
        $('#divGenerales').slideDown('slow');
    });
}

function addParts(){
    $('#divGenerales').slideUp('fast',function(){
        $('#divPartes').hide();
        $('#selUbicacion')
            .find('option')
            .remove()
            .end();
        $('#txtCantidad').val('1');
        $('#txtNumerodeParte').val('');

        $('#tdPartes').html('');
        $('#txtNumerodeParte').attr('strParte','');
        $('#selUbicacion').val($('#selUbicacion option:first').val());
        $arrPartes = Array();

        $intCodigoScrap = $('#CodigoScrap').val();
        $strCodigoScrap = $("#selCodigoScrap option:selected").text();
        $('#tdCodigoScrap').html($strCodigoScrap);
        $strQueryString = "intProc=7&intCodigoScrap=" + $intCodigoScrap + "&strCodigoScrap=" + $strCodigoScrap;
        $.ajax({
            data : $strQueryString,
            type : "POST",
            dataType : "json",
            url : "getdata.php",
            success : function($jsnData){
                if($jsnData.arrData.length>1){
                    $('#selUbicacion').append('<option value="-1">- Seleccione -</option>');
                    $('#selUbicacion').val(-1);
                };
                for($intIx=0; $intIx<$jsnData.arrData.length; $intIx++){
                    $('#selUbicacion').append('<option value="' + $jsnData.arrData[$intIx].intUbicacion + '">' + $jsnData.arrData[$intIx].strUbicacion + '</option>');
                }
                $('#divPartes').slideDown('slow',function(){
                    $('#txtNumerodeParte').focus();
                });

            }
        });
    });
}

$(function(){
    $('#txtNumerodeParte').autoComplete({
        minChars : 3,
        source : function(term,suggest){
            try { objAutosuggest.abort();} catch(e){}
            $.getJSON('getpartes.php', { strNumerodeParte : term.toUpperCase(), intProc : 0 }, function(data){
                suggest(data);
            });
        },
        renderItem: function (item, search){
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" style="background-image:url(\'img/' + item[0] + '.jpg\')" data-numerodeparte="' + item[0] + '" data-val="' + item[0] +'">' + item[0].replace(re, "<b>$1</b>") + '<br /><br /><span>' + item[1] + '</span></div>';
        },
        onSelect: function(e, term, item){
            setNumerodeParte();
            setNumerodeParte();
        }
    });

    $('#txtNumerodeParte').blur(function() {
        setNumerodeParte();
    });

    $('#txtNumerodeParte').focus(function(){
        $('#txtNumerodeParte').attr('strParte','');
    });
});

$('#txtCantidad').blur(function(){
    if($('#txtCantidad').val()=='' || $('#txtCantidad').val()=='0'){
        $('#txtCantidad').val(1);
    }
});

function setNumerodeParte(){
    if($('#txtNumerodeParte').attr('strParte')==''){
        $('#txtNumerodeParte').attr('strParte',$('#txtNumerodeParte').val());
    };
}

function addParte(){txtCantidad
    if($('#txtNumerodeParte').attr('strParte')!='' && $('#txtCantidad').val()!=''){
        $.getJSON('getpartes.php', { strNumerodeParte : $('#txtNumerodeParte').attr('strParte'), intProc : 1 }, function(data){
            console.log(data);
            $arrPartes.push(data[0].intNumerodeParte);
            $strDiv = '<div id="divPartes_' + data[0].intNumerodeParte + '" class="rowParte">';
            $strDiv += '    <div id="divPartesCantidad_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesCantidad">' + $('#txtCantidad').val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
            $strDiv += '    <div id="divPartesNoParte_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesNoParte">' + data[0].strNumerodeParte + '</div>';
            $strDiv += '    <div id="divPartesDescripcion_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesDescripcion">' + data[0].strDescripcionParte + '</div>';
            $strDiv += '    <div id="divPartesCostoU_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesCostoU">' + data[0].decCostoParte + '</div>';
            $strDiv += '    <div id="divPartesTipo_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesTipo">' + data[0].strTipoParte + '</div>';
            $strDiv += '    <div id="divPartesSubtipo_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesSubTipo">' + ' ' + '</div>';
            if($('#selUbicacion').val()==-1){
                //$strDiv += '    <div id="divPartesUbicacion_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesUbicacion">NA</div>';
            }else{
                //$strDiv += '    <div id="divPartesUbicacion_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesUbicacion">' + $('#selUbicacion option:selected').text() + '</div>';
            }

            $strDiv += '    <div id="divPartesDelete_' + data[0].intNumerodeParte + '" class="divPartesGrid divPartesDelete"><img class="imgDeletePart" src="img/delete.png" onclick="removePart(' + data[0].intNumerodeParte + ')" /></div>';

            $strDiv += '</div>';
            $('#divPartesContainer').append($strDiv);

            $("#divPartesContainer").animate({ scrollTop: $('#divPartesContainer').prop("scrollHeight")}, 1000);

            $strPartes = '';
            $decCosto = 0;
            for($intIx=0;$intIx<$arrPartes.length;$intIx++){
                if($strPartes!=''){
                    $strPartes += ' / ';
                }
                $strPartes += '<b>' + $('#divPartesNoParte_' + $arrPartes[$intIx]).html() + '</b> (' + $('#divPartesCantidad_' + $arrPartes[$intIx]).html() + ' - ' + $('#divPartesUbicacion_' + $arrPartes[$intIx]).html() + ')';

                $decCosto = $decCosto + ($('#divPartesCantidad_' + $arrPartes[$intIx]).html().replace(',','') * $('#divPartesCostoU_' + $arrPartes[$intIx]).html());
                $('#tdCosto').html($decCosto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            };

            $('#tdPartes').html($strPartes);
            $('#txtCantidad').val(1);
            $('#txtNumerodeParte').val('');
            $('#txtNumerodeParte').attr('strParte','');
            $('#selUbicacion').val($('#selUbicacion option:first').val());
            $('#txtNumerodeParte').focus();

        });
    }else{
        console.log('no hara nada hasta que se seleccione todo');
    };
}

function removePart($intNoParte){
    $('#divPartes_' + $intNoParte).remove();
    $arrPartes.splice($arrPartes.indexOf($intNoParte),1);
    $strPartes = '';
    $decCosto = 0;
    for($intIx=0;$intIx<$arrPartes.length;$intIx++){
        if($strPartes!=''){
            $strPartes += ' / ';
        }
        $strPartes += '<b>' + $('#divPartesNoParte_' + $arrPartes[$intIx]).html() + '</b> (' + $('#divPartesCantidad_' + $arrPartes[$intIx]).html() + ' - ' + $('#divPartesUbicacion_' + $arrPartes[$intIx]).html() + ')';
        $decCosto = $decCosto + ($('#divPartesCantidad_' + $arrPartes[$intIx]).html().replace(',','') * $('#divPartesCostoU_' + $arrPartes[$intIx]).html());
        $('#tdCosto').html($decCosto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    };
    $('#tdPartes').html($strPartes);
    $('#txtCantidad').val(1);
    $('#txtNumerodeParte').val('');
    $('#txtNumerodeParte').attr('strParte','');
    $('#selUbicacion').val($('#selUbicacion option:first').val());
    $('#txtNumerodeParte').focus();
}

function showSeriales(){
    $('#lblErrors').hide();
    $('#lblErrors').html('');
    if($('#txtNumerodeParte').val()==''){
        $('#lblErrors').html('Ingresa Número de parte y cantidad');
        $('#lblErrors').slideDown('fast');
        $('#txtNumerodeParte').focus();
    }else{
        $("body").css('overflow','hidden');
        $('#lblSerialesContador').html('0');
        $('#lblSerialesContador').css('color',"#FF0000")
        $('#lblSerialesCantidad').html(' / ' + $('#txtCantidad').val());
        $('#lblSerialesNoParte').html($('#txtNumerodeParte').val());
        $('#divSerialesContainer').html('');
        $('#divModal').fadeIn('fast',function(){
            $('#divSeriales').slideDown('slow',function(){
                $('#txtSerial').focus();
            });
        });
    }

}

function hideSeriales(){
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


$('document').ready(function(){
    $('#selProyecto').focus();

    $('#txtSerial').keyup(function (e){
        if(e.keyCode==13){

            $strDivSerial = '<div style="background-color: #FFFFFF; margin-bottom: 2px; border-radius: 5px; padding: 2px 5px 2px 5px; text-align: left; ">';
            $strDivSerial += $('#txtSerial').val() + '<img class="imgDeletePart" style="right: 0; float: right" src="img/delete.png"><br style="clear: both" />';
            $strDivSerial += '</div>';
            $('#divSerialesContainer').append($strDivSerial);
            $('#lblSerialesContador').html(parseInt($('#lblSerialesContador').html()) + 1);
            $('#txtSerial').val('');

            if(parseInt($('#lblSerialesContador').html())==parseInt($('#lblSerialesCantidad').html().replace(' / ',''))){
                $('#lblSerialesContador').css('color',"#282828");
            }else{
                $('#lblSerialesContador').css('color',"#FF0000");
            };
        }
    })

    $('#tdBarCode').barcode("000000","code39",{barHeight:30,barWidth:2,showHRI:false,addQuietZone: false,bgColor:'transparent',output:'css'});

});