<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
        <link rel="stylesheet" type="text/css" href="../../css/scrap_grid.css">
        <link rel="stylesheet" type="text/css" href="../../css/scrap_modal.css">
        <link rel="stylesheet" type="text/css" href="../../css/scrap_form.css">
        <link rel="stylesheet" type="text/css" href="css/cat_profile.css">
    </head>
    <body>
        <div class=" divTitles ">
            Catálogo de Perfiles
        </div>
        <div class="divGrid" style="height: calc( 100% - 63px )">
            <table class="tblGrid">
                <thead>
                <tr>
                    <td colspan="3" style="text-align: center; padding-bottom: 10px">
                        <input id="btnProfile" type="button" class="buttons button_orange" value="nuevo perfil" onclick="showModal();">
                    </td>
                </tr>
                <tr>
                    <th>Id</th>
                    <th>Perfil</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $strSql = "SELECT * FROM PRF_PROFILE WHERE PRF_STATUS IN (0,1) ORDER BY PRF_ID";
                $rstProfile = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('intAffectedRows')!=0){
                    foreach($rstProfile as $objProfile){
                        ?>
                        <tr id="tdGrid_<?php echo $objProfile['PRF_ID'] ?>">
                            <td style="text-align: right;"><?php echo $objProfile['PRF_ID']; ?></td>
                            <td style="text-align: left"><?php echo $objProfile['PRF_NAME']; ?></td>
                            <td style="padding: 0 5px 0 5px;">
                                <?php
                                if($objProfile['PRF_STATUS']==1){
                                    ?>
                                    <label id="lblDeactivateProfile_<?php echo $objProfile['PRF_ID']; ?>" currentValue="<?php echo $objProfile['PRF_STATUS']; ?>" onclick="deactivateProfile(<?php echo $objProfile['PRF_ID']; ?>);" class="labelActions labelActionsGreen" onclick="">&#10004;</label>
                                    <?php
                                }else{
                                ?>
                                    <label id="lblDeactivateProfile_<?php echo $objProfile['PRF_ID']; ?>" currentValue="<?php echo $objProfile['PRF_STATUS']; ?>" onclick="deactivateProfile(<?php echo $objProfile['PRF_ID']; ?>);" class="labelActions labelActionsRed">&#10006;</label>
                                <?php
                                }
                                ?>

                                <label id="lblEditProfile_<?php echo $objProfile['PRF_ID']; ?>" onclick="editProfile(<?php echo $objProfile['PRF_ID']; ?>);" class="labelActions labelActionsOrange">&#9998;</label>
                            </td>

                        </tr>
                        <?php
                    };
                    unset($objProfile);
                }else{
                    ?>
                    <tr><td colspan="4">No hay registros</td></tr>
                    <?php
                }
                unset($rstProfile);
                ?>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>

        <div id="divModalBackground">
            <div id="divModalMain" style="width: 300px;">
                <div id="divModalClose"><label id="lblModalClose" onclick="closeModal();">&#10006</label></div>
                <div id="divModalTitle">Crear Perfil</div>
                <div id="divModalForm">
                    <label for="txtName" class="form_label">Nombre</label><input type="text" id="txtName" class="form_input_text" style="width: 150px;" value="" /><br />
                    <label for="tblMenu" class="form_label">Menus</label>
                    <table id="tblMenu"></table>
                </div>
                <div id="divModalError"></div>
                <div id="divModalButtons">
                    <input type="button" value="agregar" onclick="addProfile();" class="buttons button_green">
                    <input type="button" value="cancelar" onclick="closeModal();" class="buttons button_red">
                </div>
                <div id="divModalWorking">
                    wait ...
                </div>
            </div>
        </div>

        <div id="divWorkingBackground">
            <div id="divWorking">
                <img src="../../images/wait_64.gif" />
            </div>
        </div>


        <script src="../../js/jquery-1.11.3.min.js"></script>
        <script src="js/profile.js"></script>
    </body>
</html>
<?php
unset($objScrap);
?>