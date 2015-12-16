<?php
session_name("loginUsuario"); 
session_start(); 

include("conexion_db.php");
	$s_ = "delete from acceso where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	$s_ = "DROP VIEW vw_padre_".$_SESSION["IDEMP"];
	$r_ = mysql_query($s_);
	$s_ = "DROP VIEW vw_reportes_".$_SESSION["IDEMP"];
	$r_ = mysql_query($s_);
    session_unregister("TYPE");
	session_unregister("ID");
	session_unregister("USER");
	session_unregister("NAME");
	session_destroy();
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>LOGOUT</title>

<style type="text/css">
<!--
.style2 {color: #666666}
-->
</style>
<link href="css/style_main.css" rel="stylesheet" type="text/css" />
<br><br><br><br><br><br><br>
<table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo2">
	<div align="center"><span class="aviso_naranja">Terminando la sesión...</span><br>
    </div>
	</td>
  </tr>
</table>
</body>