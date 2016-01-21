<?php
ini_set('display_errors',1);
date_default_timezone_set('America/Mexico_City');
session_start();

if(!isset($_SESSION["intUser"]))
{
//    header("location: index.php");
}

require_once('lib/scrap.php');
$objScrap = new clsScrap();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>:: CONTINENTAL :: SCRAP ::</title>
        <link rel="stylesheet" type="text/css" href="css/scrap.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
    </head>
    <body>
    <?php include_once('inc/header.php'); ?>
    <?php include_once('inc/menu.php'); ?>
    <table>
        <tbody>
        <tr>
            <td id="tdMenu">
                <div class=" divMenuMain ">
                    <?php
                    $strMenu = "";
                    $intTotalCategories = 0;
                    $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = 0 AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
                    $rstCategories = $objScrap->dbQuery($strSql);
                    if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
                        foreach($rstCategories as $objCategories){
                            $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = " . $objCategories['MNU_ID'] . " AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
                            $rstMenus = $objScrap->dbQuery($strSql);
                            if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
                                $strMenu .= '<div class=" divMenuCategory " id="divCat_' . $objCategories['MNU_ID'] . '" onclick="collapseCategory(\'' . $objCategories['MNU_ID'] . '\')">' . $objCategories['MNU_NAME'] . '</div>';
                                $strMenu .= '<div class=" divMenuContentContainer " style=" height: calc( 100% - #####px );" id="divCont_' . $objCategories['MNU_ID'] . '">';
                                foreach($rstMenus as $objMenus){
                                    if($objMenus['MNU_NAME']=='--separator--'){
                                        $strMenu .= '<div class=" divMenuSeparator " ></div>';
                                    }else{
                                        $strMenu .= '<div class=" divMenuOption " onclick="handleTab(\'' . $objMenus['MNU_ID'] . '\',\'' . $objMenus['MNU_NAME'] . '\',\'' . $objMenus['MNU_URL'] . '\')">' . $objMenus['MNU_NAME'] . '</div>';
                                    }

                                }
                                unset($objMenus);
                                $intTotalCategories++;
                                $strMenu .= '</div>';
                            }
                            unset($rstMenus);
                        }
                        unset($objCategories);
                        $strMenu .= '<div id="divEmpty" style="height: calc( 100% - #####px);"></div>';
                        $strMenu = str_replace("#####",intval(($intTotalCategories * 22) + 0),$strMenu);
                    }
                    unset($rstCategories);
                    echo $strMenu;
                    ?>

                </div>
            </td>
            <td id="tdCollapse" onclick="collapseMenu();">&#9668;</td>
            <td id="tdWorkArea" style="vertical-align: top">
                <div id="divTabs" class=" divTabs "></div>
                <div id="divSheets" class=" divSheets "></div>
            </td>
        </tr>
        </tbody>
    </table>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script>

        $intCurrentCategory = "";
        $arrTabs = Array();
        $intCurrentTab = "";
        function collapseCategory($intCategory){
            if($intCurrentCategory==$intCategory){
                $('#divCont_' + $intCategory).slideUp('fast',function(){
                    $('#divEmpty').show();
                })
                $intCurrentCategory="";
            }else{
                if($intCurrentCategory==''){
                    $('#divEmpty').hide('fast',function(){
                        $('#divCont_' + $intCategory).slideDown('fast');
                        $intCurrentCategory = $intCategory;
                    })
                }else{
                    $('#divCont_' + $intCurrentCategory).slideUp('fast',function(){
                        $('#divCont_' + $intCategory).slideDown('fast');
                        $intCurrentCategory = $intCategory;
                    })
                }

            }
        }

        function collapseMenu(){
            if($('#tdMenu').is(":visible")){
                $('#tdMenu').hide();
                $('#tdCollapse').html("&#9658;");
            }else{
                $('#tdMenu').show();
                $('#tdCollapse').html("&#9668;");
            }
        };

        function handleTab($intTab, $strName, $strUrl){
            $strDivToAppend = "";
            $strIframeToAppend = "";
            if($arrTabs.indexOf($intTab)==-1){
                $arrTabs.push($intTab);
                $('#divTab_' + $intCurrentTab).removeClass('divTabSelected');
                $('#divCloseTab_' + $intCurrentTab).removeClass('divCloseTabSelected');
                $('#iframeTab_' + $intCurrentTab).hide();

                $strDivToAppend += '<div id="divTab_' + $intTab + '" class=" divTab " onclick="handleTab(\'' + $intTab + '\')">' + $strName + '</div>';
                $strDivToAppend += '<div id="divCloseTab_' + $intTab + '" class=" divCloseTab " onclick="closeTab(\'' + $intTab + '\');">&#10005;</div>';
                $('#divTabs').append($strDivToAppend);
                $strIframeToAppend = '<iframe id="iframeTab_' + $intTab + '" class=" iframeSheets " src="' + $strUrl + '"></iframe>';
                $('#divSheets').append($strIframeToAppend);
                $intCurrentTab = $intTab;
                $('#divTab_' + $intTab).addClass('divTabSelected');
                $('#divCloseTab_' + $intTab).addClass('divCloseTabSelected');
            }else{
                if($intCurrentTab!=$intTab){
                    $('#divTab_' + $intCurrentTab).removeClass('divTabSelected');
                    $('#divCloseTab_' + $intCurrentTab).removeClass('divCloseTabSelected');
                    $('#iframeTab_' + $intCurrentTab).hide();
                    $intCurrentTab = $intTab;
                    $('#divTab_' + $intTab).addClass('divTabSelected');
                    $('#divCloseTab_' + $intTab).addClass('divCloseTabSelected');
                    $('#iframeTab_' + $intTab).show();

                }
            }
        }

        function closeTab($intTab){
            $('#divTab_' + $intTab).remove();
            $('#divCloseTab_' + $intTab).remove();
            $('#iframeTab_' + $intTab).remove();
            if($intCurrentTab==$intTab){
                if($arrTabs.indexOf($intTab)!=0){
                    handleTab($arrTabs[$arrTabs.indexOf($intTab) - 1]);
                }else{
                    if(typeof $arrTabs[$arrTabs.indexOf($intTab) + 1]!='undefined'){
                        handleTab($arrTabs[$arrTabs.indexOf($intTab) + 1]);
                    }else{
                        $intCurrentTab = "";
                    }
                }
            }
            $arrTabs.splice($arrTabs.indexOf($intTab),1);
        }

    </script>
    </body>
    </html>
<?php
unset($objScrap);
?>