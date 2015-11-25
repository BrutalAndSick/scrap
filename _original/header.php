<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   switch($_SESSION['TYPE']) {
   		case "administrador"	:	include("../menu_admin.php"); break; 
		case "capturista"		:	include("../menu_capturista.php"); break;
		case "autorizador"		:	include("../menu_autorizador.php"); break; 
		case "reportes"			:	include("../menu_reportes.php"); break; 
		case "general"			:	include("../menu_reportes.php"); break; 
		case "materiales"		:	include("../menu_admin.php"); break; 
	}
   include("../conexion_db.php");
   include("../generales.php"); ?>
   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../pop_Calendar/calendar.css">
<link media="screen" rel="stylesheet" href="../colorbox/colorbox.css" />
<link rel="stylesheet" type="text/css" href="../select_checks/ui.dropdownchecklist.standalone.css">
<!--------------------------Calendario---------------------------->
<script language="JavaScript" src="../pop_Calendar/GCappearance.js"></script>
<script language="JavaScript" src="../pop_Calendar/GurtCalendar.js"></script>

<script src="../colorbox/jquery.min.js"></script>
<script type="text/javascript" src="../select_checks/jquery-1.6.1.min.js"></script>
<script src="../colorbox/jquery.colorbox.js"></script>
<script language="JavaScript" src="../css/boxover.js"></script>
<script type="text/javascript" src="../select_checks/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="../select_checks/ui.dropdownchecklist-1.4-min.js"></script>  

<script type="text/javascript">
	$(document).ready(function() {
		$("#proy_add_").dropdownchecklist( { emptyText: "Agregar Proyectos", firstItemChecksAll: true, maxDropHeight: 150, width: 220 } );
		$("#proy_del_").dropdownchecklist( { emptyText: "Quitar Proyectos", firstItemChecksAll: true, maxDropHeight: 150, width: 220 } );
	});
</script>
<script>
	$(document).ready(function(){
	$(".frame_editar_boleta").colorbox({width:"70%", height:"80%", iframe:true });
	$(".frame_autorizacion").colorbox({width:"60%", height:"60%", iframe:true });
	$(".frame_ver_boleta").colorbox({width:"70%", height:"80%", iframe:true });
	$(".frame_ver_mail").colorbox({width:"55%", height:"80%", iframe:true });
	$(".frame_cod_scrap").colorbox({width:"40%", height:"75%", iframe:true });
	$(".frame_cod_emp").colorbox({width:"50%", height:"75%", iframe:true });
	$(".frame_cod_proy").colorbox({width:"60%", height:"75%", iframe:true });
	$(".frame_relacionado").colorbox({width:"40%", height:"80%", iframe:true });
	$(".frame_relacionado_grande").colorbox({width:"70%", height:"80%", iframe:true });
	$(".frame_defectos").colorbox({width:"40%", height:"80%", iframe:true });
	$(".frame_proyectos").colorbox({width:"50%", height:"80%", iframe:true });
	$(".frame_batch_ids").colorbox({width:"50%", height:"80%", iframe:true });
	$(".frame_ausencia").colorbox({width:"50%", height:"40%", iframe:true });
	$(".frame_autorizador").colorbox({width:"50%", height:"70%", iframe:true });
	$(".personalizar").colorbox({width:"50%", height:"70%", iframe:true });
	});

function showMenu(which,tf) {
	if (tf==true) {
	which.style.display="block"; }
	if (tf==false) {
	which.style.display="none";  }
}
</script>
</head>

<body topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" background="../imagenes/fondo.png" style="background-repeat:repeat-x;" bgcolor="#F1F1F1">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" class="aviso">
		<div style="margin-right:30px; margin-top:10px;">
			<?php echo $_SESSION['NAME']."&nbsp;&nbsp;&nbsp;[ ".$_SESSION['TYPE']." ]";?></div></td>
	<td rowspan="2"><img src="../imagenes/app_titulo.png" width="285" height="104" /></td>
  </tr>