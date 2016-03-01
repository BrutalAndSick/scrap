$blnIncludeJS=true;
function switchSelectedUnique($strRelation,$intRecordId){
    for($intIndex=0;$intIndex<$jsnGridData.arrRelation.length;$intIndex++){
        if($jsnGridData.arrRelation[$intIndex].strRelationName==$strRelation){
            console.log($jsnGridData.arrRelation[$intIndex].arrRelationIds);
            $arrIds = $jsnGridData.arrRelation[$intIndex].arrRelationIds;
            $jsnGridData.arrRelation[$intIndex].arrRelationIds.forEach(function($objIds){
                $('#tdRelation_' + $strRelation + '_' + $objIds).removeClass('tdActive');
                $('#tdRelation_' + $strRelation + '_' + $objIds).addClass('tdNonActive');
                $('#tdRelation_' + $strRelation + '_' + $objIds).html('&#10006');
            });
            $('#tdRelation_' + $strRelation + '_' + $intRecordId).removeClass('tdNonActive');
            $('#tdRelation_' + $strRelation + '_' + $intRecordId).addClass('tdActive');
            $('#tdRelation_' + $strRelation + '_' + $intRecordId).html('&#10004');
            $intIndex=$jsnGridData.arrRelation.length;
        }
    }
}