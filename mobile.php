<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div style="background-color: #EB1414; color:#FFFFFF" id="divTest">
    asdasdasdas
</div>
<script src="js/jquery-1.11.3.min.js"></script>
<script>

    $('document').ready(function(){
        $intW =  - 100;
        $('#divTest').css('width', $intW  + 'px');
        $('#divTest').css('min-width', $intW + 'px');
        $('#divTest').css('max-width', $intW + 'px');
        $('#divTest').css('background-color', '#00ff00');

        //alert(window.innerWidth);
        //alert(window.innerHeight);
    })

</script>
</body>
</html>