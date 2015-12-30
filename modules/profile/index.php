<?php
require_once('lib/scrap.php');
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
                    <th>Estatus</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $strSql = "SELECT * FROM PRF_PROFILE ORDER BY PRF_ID";
                $rstProfile = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('intAffectedRows')!=0){
                    foreach($rstProfile as $objProfile){
                        ?>
                        <tr id="tdGrid_<?php echo $objProfile['PRF_ID'] ?>"><td><?php echo $objProfile['PRF_ID']; ?></td><td><?php echo $objProfile['PRF_NAME']; ?></td><td><?php echo $objProfile['PRF_STATUS']; ?></td></tr>
                        <?php
                    };
                    unset($objProfile);
                }else{
                    ?>
                    <tr><td colspan="3">No hay registros</td></tr>
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
            <div id="divModalMain">
                <div id="divModalClose"><label id="lblModalClose" onclick="closeModal();">&#10006</label></div>
                <div id="divModalTitle">Crear Perfil</div>
                <div id="divModalForm">
                    <!-- #### DATOS Y CONTROLES DEL FORMULARIO DENTRO DE LA MODAL -->
                    <label for="txtName" class="form_label">Nombre</label><input type="text" id="txtName" class="form_input_text" style="width: 150px;" value="" /><br />
                    <label for="tblMenu" class="form_label">Menus</label>
                    <table id="tblMenu"></table>
                    <!-- #### DATOS Y CONTROLES DEL FORMULARIO DENTRO DE LA MODAL -->
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

        <script src="../../js/jquery-1.11.3.min.js"></script>
        <script>

            $arrMenu = [];

            function showModal() {
                $("body").css('overflow', 'hidden');
                showModalError('');
                $('#divModalBackground').fadeIn('fast', function(){
                    $('#tblMenu tr').remove();
                    $('#txtName').val('');
                    $strQueryString = "intProcess=0";
                    $.ajax({url : "getmenu.php", data : $strQueryString, type : "POST", dataType : "json",
                        success : function($objJson){
                            $arrMenu = [];
                            for($intIndex=0;$intIndex<$objJson.length;$intIndex++){
                                $('#tblMenu').append('<tr><td colspan="2">' + $objJson[$intIndex].name + '</td></tr>');
                                for($intSubIndex=0;$intSubIndex<$objJson[$intIndex].menu.length;$intSubIndex++){
                                    $arrMenu.push($objJson[$intIndex].menu[$intSubIndex].id);
                                    $('#tblMenu').append('<tr><td id="tdMenu_' + $objJson[$intIndex].menu[$intSubIndex].id + '" class="tdNonActive" onclick="switchSelected(' + $objJson[$intIndex].menu[$intSubIndex].id + ')">&#10006</td><td>' + $objJson[$intIndex].menu[$intSubIndex].name + '</td></tr>');
                                }
                            }
                            $('#divModalMain').slideDown('fast', function(){
                                $('#txtName').focus();
                            });
                        }
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

            function switchSelected($intMenu){
                if($('#tdMenu_' + $intMenu).attr('class')=='tdNonActive'){
                    $('#tdMenu_' + $intMenu).removeClass('tdNonActive');
                    $('#tdMenu_' + $intMenu).addClass('tdActive');
                    $('#tdMenu_' + $intMenu).html('&#10004');
                }else{
                    $('#tdMenu_' + $intMenu).removeClass('tdActive');
                    $('#tdMenu_' + $intMenu).addClass('tdNonActive');
                    $('#tdMenu_' + $intMenu).html('&#10006');
                }
            }

            function addProfile(){
                showModalError('');
                $('#divModalButtons').hide();
                $('#divModalWorking').show();
                if($('#txtName').val().trim()==''){
                    $('#txtName').focus();
                    showModalError('Ingresa el nombre del perfil');
                    $('#divModalWorking').hide();
                    $('#divModalButtons').show();
                }else{
                    $blnSelectedMenu = false;
                    $strSelectedMenu = '';
                    for($intIndex=0;$intIndex<$arrMenu.length;$intIndex++){
                        if($('#tdMenu_' + $arrMenu[$intIndex]).attr('class')=='tdActive'){
                            $blnSelectedMenu = true;
                            $strSelectedMenu += $arrMenu[$intIndex] + "|";
                        }
                    }
                    if(!$blnSelectedMenu){
                        showModalError('Selecciona al menos un menu');
                        $('#divModalWorking').hide();
                        $('#divModalButtons').show();
                    }else{
                        $strQueryString = "intProcess=0&strProfile=" + $('#txtName').val().trim().toUpperCase() + "&strSelectedMenu=" + $strSelectedMenu;
                        $.ajax({url : "getprofile.php", data : $strQueryString, type : "POST", dataType : "json",
                            success : function($objJson){
                                $('#divModalWorking').hide();
                                $('#divModalButtons').show();
                                closeModal();
                                console.log($objJson);
                            }
                        });
                    }
                }
            }

            function showModalError($strError){
                if($strError==''){
                    $('#divModalError').hide();
                    $('#divModalError').html('');
                }else{
                    $('#divModalError').html('&#8854; ' + $strError);
                    $('#divModalError').slideDown('fast',function(){});
                }
            };

            $('document').ready(function(){
                $('#divModalMain').css('height',($('body').css('height').replace('px','').replace(' ','') - 100) + "px");
                $('#divModalForm').css('height',($('#divModalMain').css('height').replace('px','').replace(' ','') - 170) + "px");
            })
        </script>
    </body>
</html>
<?php
unset($objScrap);
?>