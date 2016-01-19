/**
 * Created by legion on 1/17/16.
 */

function printLabel(){
    $('#divScrapLabel').css('background-color','#FFFFFF');
    $('#divScrapLabel').css('color','#000000');
    $('#divScrapLabel').printThis();
}

function getData($strOption){
    $("body").css('overflow', 'hidden');
    $('#divWorkingBackground').fadeIn('fast',function(){
        $strQueryString = "strProcess=" + $strOption;
        switch ($strOption){
            case 'Project':

                break;
        }
        //$('#divWorkingBackground').fadeOut();
        //$("body").css('overflow', 'auto');
    });
}

$('document').ready(function(){
    $('#tdLabelBarcode').barcode("000000","code39",{barHeight:22,barWidth:2,showHRI:true,fontSize:8,addQuietZone: false,bgColor:'transparent',output:'css'});
    getData('Project')
});
