<?php
ini_set("display_errors",1);
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('lib/scrap.php');
$objScrap = new clsScrap();
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
    <?php
    $intTableId = 7;
    $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TARGET_TABLE = " . $intTableId . " AND TBL_PARENT = 0 ORDER BY TBL_PARENT";
    $rstRelation = $objScrap->dbQuery($strSql);
    foreach($rstRelation as $objRelation){
        echo "display: " . $objRelation['TBL_DISPLAY'] . "<br />";
        echo "tabla: " . $objRelation['TBL_NAME'] . "<br />";
        $strSql = "SELECT " . $objRelation['TBL_SOURCE_ID_FIELD'] . ", " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . " ";
        $strSql .= "FROM " . $objRelation['TBL_SOURCE_TABLE'] . " ";
        $strSql .= "WHERE " . $objRelation['TBL_SOURCE_STATUS_FIELD'] . " = 1 ";
        $strSql .= "ORDER BY " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . ", " . $objRelation['TBL_SOURCE_ID_FIELD'];
        $rstRelationData = $objScrap->dbQuery($strSql);
        $strRelationIds = "";
        if($objScrap->intAffectedRows!=0){
            foreach($rstRelationData as $objRelationData){
                $strRelationIds .= $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ",";
                echo $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . " - " . $objRelationData[$objRelation['TBL_SOURCE_DISPLAY_FIELD']] . "<br />";
            }
            $strRelationIds = substr($strRelationIds,0,strlen($strRelationIds)-1);
            echo "pues: " . $strRelationIds;
            echo "<br >";
        }
        echo "<br /><br />";
        getRelationbyLevel($intTableId, $objRelation['TBL_ID'], $strRelationIds);
    }
    unset($objRelation);
    unset($rstRelation);

    function getRelationbyLevel($intTableId, $intParent, $strRelationIds){
        global $objScrap;
        $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TARGET_TABLE = " . $intTableId . " AND TBL_PARENT = " . $intParent . " ORDER BY TBL_PARENT";
        $rstRelation = $objScrap->dbQuery($strSql);
        foreach($rstRelation as $objRelation){
            echo "display: " . $objRelation['TBL_DISPLAY'] . "<br />";
            echo "tabla: " . $objRelation['TBL_NAME'] . "<br />";
            $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_ID = " . $objRelation['TBL_RELATION'];
            $rstRelated = $objScrap->dbQuery($strSql);
            $strSql = "SELECT " . $objRelation['TBL_SOURCE_ID_FIELD'] . ", " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . " ";
            $strSql .= "FROM " . $objRelation['TBL_SOURCE_TABLE'] . " ";
            $strSql .= "WHERE " . $objRelation['TBL_SOURCE_ID_FIELD'] . " IN (";
            $strSql .= "SELECT DISTINCT(" . $rstRelated[0]['TBL_SOURCE'] . ") ";
            $strSql .= "FROM " . $rstRelated[0]['TBL_TABLE'] . " ";
            $strSql .= "WHERE " . $rstRelated[0]['TBL_TARGET'] . " IN (" . $strRelationIds . ") ";
            $strSql .= "AND " . $objRelation['TBL_SOURCE_STATUS_FIELD'] . " = 1) ";
            $strSql .= "ORDER BY " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . ", " . $objRelation['TBL_SOURCE_ID_FIELD'];
            unset($rstRelated);
            $rstRelationData = $objScrap->dbQuery($strSql);
            $strRelationIds = "";
            if($objScrap->intAffectedRows!=0){
                foreach($rstRelationData as $objRelationData){
                    $strRelationIds .= $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ",";
                    echo $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . " - " . $objRelationData[$objRelation['TBL_SOURCE_DISPLAY_FIELD']] . "<br />";
                }
                $strRelationIds = substr($strRelationIds,0,strlen($strRelationIds)-1);
                echo "pues: " . $strRelationIds;
                echo "<br >";
            }
            echo "<br /><br />";
            getRelationbyLevel($intTableId, $objRelation['TBL_ID'], $strRelationIds);
        }
        unset($objRelation);
        unset($rstRelation);
    }

    ?>
    </body>
</html>
<?php
unset($objScrap);
?>