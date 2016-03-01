<?php
$jsnData = array('strFileName'=>'','strError'=>'');
if (0 < $_FILES['file']['error']){
    $jsnData['strError'] = $_FILES['file']['error'];
}else{
    $strPath = 'c:\wamp\www\images\parts\\';
    $strTempFileName = $strPath . "tmp_" . date('YmdHisu') . '.jpg';
    move_uploaded_file($_FILES['file']['tmp_name'], $strTempFileName);
    $intWidth = 120;
    $intHeight = 120;
    list($intOriginalWidth, $intOriginalHeight) = getimagesize($strTempFileName);
    $intOriginalRatio = $intOriginalWidth / $intOriginalHeight;
    if ($intWidth / $intHeight > $intOriginalRatio) {
        $intWidth = $intHeight * $intOriginalRatio;
    } else {
        $intHeight = $intWidth / $intOriginalRatio;
    }
    $objImage = imagecreatetruecolor($intWidth, $intHeight);
    $objNewImage = imagecreatefromjpeg($strTempFileName);
    imagecopyresampled($objImage, $objNewImage, 0, 0, 0, 0, $intWidth, $intHeight, $intOriginalWidth, $intOriginalHeight);
    $strFileName = date('YmdHisu') . '.jpg';
    imagejpeg($objImage, $strPath . $strFileName, 75);
    unlink($strTempFileName);
    $jsnData['strFileName'] = $strFileName;
}
echo json_encode($jsnData);
?>