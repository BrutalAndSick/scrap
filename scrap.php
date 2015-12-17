<?php
ini_set('display_errors',1);
session_start();

if(!isset($_SESSION["intUser"]))
{
//    header("location: index.php");
}

date_default_timezone_set('America/Mexico_City');

$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

function buildMenu(){
    $arrWChildren = Array();
    $arrWOChildren = Array();
    $strMenu = "";
    $strSql = "SELECT MNU_MENU.* FROM MNU_MENU WHERE MNU_MENU.MNU_PARENT = 0 AND MNU_MENU.MNU_STATUS = 1 ORDER BY MNU_MENU.MNU_ORDER, MNU_MENU.MNU_NAME;";
    $rstMenu = mysqli_query($objCon,$strSql);
    while($objMenu=mysqli_fetch_assoc($rstMenu)){
        $strSql = "SELECT MNU_MENU.* FROM MNU_MENU WHERE MNU_MENU.MNU_PARENT = " . $objMenu['MNU_ID']  . " ORDER BY MNU_MENU.MNU_ORDER, MNU_MENU.MNU_NAME;";
        $rstChildren = mysqli_query($objCon, $rstChildren);
        if(mysqli_num_rows($rstChildren)>0){
            $arrWChildren;
        }else{

        }
        mysqli_free_result($rstChildren);
        unset($rstChildren);
    };
    unset($objMenu);
    mysqli_free_result($rstMenu);
    unset($rstMenu);
};

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



                    <div class=" divMenuCategory " id="divCat_Administracion" onclick="collapseCategory('Administracion')">Administración</div>
                    <div class=" divMenuContentContainer " id="divCont_Administracion">
                        <div class=" divMenuOption ">Usuarios</div>
                        <div class=" divMenuOption ">Vendors</div>
                        <div class=" divMenuOption ">Plantas</div>
                        <div class=" divMenuOption ">Divisiones</div>
                        <div class=" divMenuOption ">Segmentos</div>
                        <div class=" divMenuOption ">C. de Costos</div>
                        <div class=" divMenuOption ">APD</div>
                        <div class=" divMenuOption ">Proyectos</div>
                    </div>
                    <div class=" divMenuCategory " id="divCat_Sap" onclick="collapseCategory('Sap')">SAP</div>
                    <div class=" divMenuContentContainer " id="divCont_Sap">
                        <div class=" divMenuOption ">Opcion 1</div>
                        <div class=" divMenuOption ">Opcion 2</div>
                        <div class=" divMenuOption ">Opcion 3</div>
                        <div class=" divMenuOption ">Opcion 4</div>
                    </div>
                    <div class=" divMenuCategory " id="divCat_Materiales" onclick="collapseCategory('Materiales')">Materiales</div>
                    <div class=" divMenuContentContainer " id="divCont_Materiales">
                        <div class=" divMenuOption ">Opcion 1</div>
                        <div class=" divMenuOption ">Opcion 2</div>
                        <div class=" divMenuOption ">Opcion 3</div>
                        <div class=" divMenuOption ">Opcion 4</div>
                    </div>
                    <div class=" divMenuCategory " id="divCat_Captura" onclick="collapseCategory('Captura')">Captura</div>
                    <div class=" divMenuContentContainer " id="divCont_Captura">
                        <div class=" divMenuOption ">Opcion 1</div>
                        <div class=" divMenuOption ">Opcion 2</div>
                        <div class=" divMenuOption ">Opcion 3</div>
                        <div class=" divMenuOption ">Opcion 4</div>
                    </div>
                    <div class=" divMenuCategory " id="divCat_Reportes" onclick="collapseCategory('Reportes')">Reportes</div>
                    <div class=" divMenuContentContainer " id="divCont_Reportes">
                        <div class=" divMenuOption ">Reportes</div>
                    </div>
                    <div id="divEmpty"></div>
                </div>
            </td>
            <td id="tdCollapse" onclick="collapseMenu();">&#8810;</td>
            <td id="tdWorkArea">
                1
            </td>
        </tr>
        </tbody>
    </table>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script>

        $arrCategories= Array('Administracion','Sap','Materiales','Captura','Reportes');
        $strCurrentCategory = "";

        function collapseCategory($strCategory){
            if($strCurrentCategory==$strCategory){
                $('#divCont_' + $strCategory).slideUp('fast',function(){
                    $('#divEmpty').show();
                })
                $strCurrentCategory="";
            }else{
                if($strCurrentCategory==''){
                    $('#divEmpty').hide('fast',function(){
                        $('#divCont_' + $strCategory).slideDown('fast');
                        $strCurrentCategory = $strCategory;
                    })
                }else{
                    $('#divCont_' + $strCurrentCategory).slideUp('fast',function(){
                        $('#divCont_' + $strCategory).slideDown('fast');
                        $strCurrentCategory = $strCategory;
                    })
                }

            }
        }

        function collapseMenu(){
            if($('#tdMenu').is(":visible")){
                $('#tdMenu').hide();
                $('#tdCollapse').html("&#8811;");
            }else{
                $('#tdMenu').show();
                $('#tdCollapse').html("&#8810;");
            }
        };

    </script>
    </body>
    </html>
<?php
mysqli_close($objCon);
unset($objCon);
?>