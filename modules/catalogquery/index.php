<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <style>
        </style>
    </head>
    <body>
        <div class=" divTitles ">Consulta de Catálogos</div>
        <div class="divFilters" id="divCatalogs">
            <input type="button" onclick="loadQueryFilters(1)" value="Países" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(2)" value="Plantas" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(3)" value="Naves" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(4)" value="Divisiones" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(5)" value="Segmentos" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(6)" value="Profit Center" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(7)" value="APD" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(8)" value="Centros de Costos" class="buttons_small button_orange">
            <br />
            <input type="button" onclick="loadQueryFilters(9)" value="Áreas" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(10)" value="Estaciones" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(11)" value="Líneas" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(12)" value="Defectos" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(13)" value="Causas" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(14)" value="Códigos de Scrap" class="buttons_small button_orange">
            <br />
            <input type="button" onclick="loadQueryFilters(16)" value="Unidades" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(17)" value="Tipos de Parte" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(18)" value="Partes" class="buttons_small button_orange">
            <input type="button" onclick="loadQueryFilters(15)" value="Proyectos" class="buttons_small button_orange">
        </div>

        <div class="divFilters" id="divFilters" style="display: none">
            filtros
        </div>

        <div class="divGrid" id="divGrid">
            <table class="tblGrid">
                <!-- ##### BEGIN: GRID HEADER ##### -->
                <thead id="theadGrid" class="theadGrid"></thead>
                <!-- ##### END: GRID HEADER ##### -->
                <!-- ##### BEGIN: GRID BODY ##### -->
                <tbody id="tbodyGrid" class="tbodyGrid"></tbody>
                <!-- ##### END: GRID BODY ##### -->
            </table>
        </div>
        <div id="divPagination" class="divPagination">
        </div>
        <div id="divWorkingBackground">
            <div id="divWorking">
                <img src="../../images/wait_64.gif" />
            </div>
        </div>
        <script src="../../js/jquery-1.11.3.min.js"></script>
        <script src="javascript.js"></script>
    </body>
</html>
<?php
unset($objScrap);
?>