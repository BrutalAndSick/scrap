$("#txtCantidad").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });

$arrDivs = Array('APD','Area','Tecnologia','Linea','Defecto','Causa','CodigoScrap');

function cleanSelect($strSelect){

    for($intIx=$.inArray($strSelect,$arrDivs);$intIx<$arrDivs.length;$intIx++){
        $('#div' + $arrDivs[$intIx]).hide();
        $('#sel' + $arrDivs[$intIx])
            .find('option')
            .remove()
            .end();
//        $('#sel' + $arrDivs[$intIx]).append('<option value="-1">- Seleccione -</option>');
//        $('#sel' + $arrDivs[$intIx]).val(-1);
        $('#td' + $arrDivs[$intIx]).html('');
    }
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
                    if($jsnData.arrData.length<2) {
                        getArea();
                    }
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
                if($jsnData.arrData.length<2) {
                    addParts();
                }
            });
        }
    });
}

function addParts(){
    $('#divPartes').hide();
    $('#selUbicacion')
        .find('option')
        .remove()
        .end();
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
            $('#divPartes').slideDown('fast');
        }
    });
}

var objAutosuggest;
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
            return '<div class="autocomplete-suggestion" data-numerodeparte="'+item+'" data-val="'+item+'"><img src="img/'+item+'.jpg" style="width:100px;"> '+item.replace(re, "<b>$1</b>")+'</div>';
        },
        onSelect: function(e, term, item){
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

function setNumerodeParte(){
    if($('#txtNumerodeParte').attr('strParte')==''){
        $('#txtNumerodeParte').attr('strParte',$('#txtNumerodeParte').val());
    };
}

function addParte(){
//    if($('#txtNumerodeParte').attr('strParte')!='' && $('#txtCantidad').val()!='' && $('#selUbicacion').val()!=-1){
    if($('#txtNumerodeParte').attr('strParte')!='' && $('#txtCantidad').val()!=''){
        $.getJSON('getpartes.php', { strNumerodeParte : $('#txtNumerodeParte').attr('strParte'), intProc : 1 }, function(data){
            console.log(data);
            $strDiv = '<div id="divPartes_' + data[0].intNumerodeParte + '">';
            $strDiv += '    <div class="divPartesGrid divPartesCantidad">' + $('#txtCantidad').val() + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesNoParte">' + data[0].strNumerodeParte + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesDescripcion">' + data[0].strDescripcionParte + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesTipo">' + data[0].strTipoParte + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesSubTipo">' + ' ' + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesNoSerial">' + $('#txtSerial').val() + '</div>';
            $strDiv += '    <div class="divPartesGrid divPartesUbicacion">' + $('#selUbicacion option:selected').text(); + '</div>';
            $strDiv += '</div>';

            $('#divPartes').append($strDiv);

            $('#txtCantidad').val(1);
            $('#txtNumerodeParte').val('');
            $('#txtNumerodeParte').attr('strParte','');
            $('#selUbicacion').val(-1);
        });
    }else{
        console.log('no hara nada hasta que se seleccione todo');
    };
}