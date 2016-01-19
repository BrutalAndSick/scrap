<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        table tr:nth-child(odd) {
            background-color: #D0D0D0;
        }

        table tr:nth-child(even) {
            background-color: #EEEEEE;
        }

    </style>
</head>
<body style="font-family: Arial; font-size: 18pt;">
<table>
    <tr></tr>
<?php
$intCells = 0;
for($intIx=0;$intIx<80001;$intIx++){
    echo '<td style="color:#F9A11B">' . $intIx . '</td><td style="text-align: center; ">&#' . $intIx . '</td>';
    $intCells++;
    if($intCells==10){
        echo "</tr><tr>";
        $intCells = 0;
    };
}
?>
</table>
</body>
</html>

