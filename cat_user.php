<?php
require_once('lib/scrap.php');
$objScrap = new clsScrap();
?>
<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/scrap.css">
    </head>
        <div class=" divTitles ">
            Catálogo de Usuarios
        </div>
        <div class=" divGridMain ">
            <div>
                <table id="tblHeader">
                    <tbody>
                    <tr>
                        <th>Usuario</th>
                        <th>¿AD?</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Estatus</th>
                    </tr>
                    <tr>
                        <td><input type="text" value="&#9906;"></td>
                        <td>&#9906;</td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td>&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
            </div><br />
            <div id="divGridContent">
                <table id="tblContent">
                    <tbody>
                <?php
                $strSql = "SELECT * FROM USR_USER";
                $rstData = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('intAffectedRows')==0){
                    ?>
                    <tr><td colspan="5" style="text-align: center;">- No existen usuarios en la Base de Datos -</td></tr>
                    <?php
                }else {
                    foreach ($rstData as $objData) {
                        $strEcho = '<tr>';
                        $strEcho .= '<td>' . $objData['USR_USER'] . '</td>';
                        $strEcho .= '<td id="tdAD_' . $objData['USR_ID'] . '" appval="' . $objData['USR_AD'] . '" onclick="updateAD(' . $objData['USR_ID'] . ')" style="text-align: center; cursor:pointer; color:#';
                        if($objData['USR_AD']==1){
                            $strEcho .= '00FF00;">&#10004</td>';
                        }else{
                            $strEcho .= 'FF0000;">&#10006</td>';
                        }
                        $strEcho .= '<td>' . $objData['USR_NAME'] . '</td>';
                        $strEcho .= '<td>' . $objData['USR_LAST_NAME'] . '</td>';
                        $strEcho .= '<td id="tdStatus_' . $objData['USR_ID'] . '" style="text-align: center; cursor:pointer; color:#';
                        if($objData['USR_STATUS']==1){
                            $strEcho .= '00FF00;">&#10004</td>';
                        }else{
                            $strEcho .= 'FF0000;">&#10006</td>';
                        }
                        $strEcho .= '</tr>';
                        echo $strEcho . "\n";
                    }
                    unset($objData);
                }
                unset($rstData);
                ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="js/jquery-1.11.3.min.js"></script>
        <script src="js/printThis.js"></script>
        <script>
            $(document).ready(function(){
                $('#divGridContent').css('height',($(window).height() - 200) + 'px');
                adjustColumnsWidth(5);
            });

            function adjustColumnsWidth($intNumberOfColumns){
                for($intIx=0;$intIx<$intNumberOfColumns;$intIx++){
                    $intColumnWidth = 0;
                    $intColumnWidthHeader = $('#tblHeader tr:first th:eq(' + $intIx + ')').width();
                    $intColumnWidthContent = $('#tblContent tr:last td:eq(' + $intIx + ')').width();

                    if($intColumnWidthHeader>=$intColumnWidthContent){
                        $intColumnWidth = $intColumnWidthHeader;
                    }else{
                        $intColumnWidth = $intColumnWidthContent;
                    }

                    $('#tblContent tr:last td:eq(' + $intIx + ')').css('width',$intColumnWidth + 'px')
                    $('#tblContent tr:last td:eq(' + $intIx + ')').css('min-width',$intColumnWidth + 'px');
                    $('#tblHeader tr:first th:eq(' + $intIx + ')').css('width',$intColumnWidth + 'px');
                    $('#tblHeader tr:first th:eq(' + $intIx + ')').css('min-width',$intColumnWidth + 'px');
                }
            }

            function updateAD($intUser){
                .getJSON()


                console.log($('#tdAD_' + $intUser).attr('appval'));
                if($('#tdAD_' + $intUser).attr('appval')==0){
                    console.log("valor 0");
                }
                if($('#tdAD_' + $intUser).attr('appval')==1){
                    console.log("valor 1");
                }
            }

        </script>
    </body>
</html>
<?php
unset($objScrap);
?>