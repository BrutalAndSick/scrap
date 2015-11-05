<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   include("../menu_capturista.php"); 
   include("../conexion_db.php");
   include("../generales.php");
   conectame_db('scrap_gdl'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../pop_Calendar/calendar.css">
<link rel="stylesheet" href="../autocomplete/jquery.autocomplete.css" type="text/css">   
<!--------------------------Calendario---------------------------->
<script language="JavaScript" src="../pop_Calendar/GCappearance.js"></script>
<script language="JavaScript" src="../pop_Calendar/GurtCalendar.js"></script>
<!--------------------------Tooltips---------------------------->
<script language="JavaScript" src="../css/boxover.js"></script>
<!--------------------------Autocompletar---------------------------->
<script src="../autocomplete/jquery-latest.js"></script>
<script type="text/javascript" src="../autocomplete/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="../autocomplete/jquery.autocomplete.js"></script> 
<script>
function showMenu(which,tf) {
	if (tf==true) {
	which.style.display="block"; }
	if (tf==false) {
	which.style.display="none";  }
}

function solo_numeros(evt){
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57));
}

function soloLetras(evt){ 
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key > 13 && (key < 48 || key > 57));
}

function cancelar() {
	form2.action = '?op=cancelar';
	form2.submit();
}

function validar() {
	var extension, file_name;
	if(form1.partes.value=='') {
		alert('Es necesario adjuntar el archivo de partes');
		form1.partes.focus(); return; }	
	file_name = form1.partes.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='csv') {
		alert('Utilice solamente archivos .csv');
		form1.partes.focus(); return; } 			
	
	form1.action='?op=guardar_1'; 
	form1.submit();		
}

function exportar() {
	form2.action = 'excel_ayudas.php?op=merma_35';
	form2.submit();
	form2.target = '_self';
	form2.action = 'scrap_archivo_35.php?op=guardar';
}
</script>
</head>
<?php include('funciones.php'); ?>

<body topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" background="../imagenes/fondo.png" style="background-repeat:repeat-x;" bgcolor="#F1F1F1">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" class="aviso">
		<div style="margin-right:30px; margin-top:10px;"><?php echo $_SESSION['USER']."  [ ".$_SESSION['TYPE']." ]";?></div></td>
	<td rowspan="2"><img src="../imagenes/app_titulo.png" width="285" height="104" /></td>
  </tr>
  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_capturae'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_capturae','b_capturat'); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td background="../imagenes/borde_izq_tabla.png">&nbsp;</td>
    <td>&nbsp;
		<!--Todo el contenido de cada página--->
		<?php switch($op) {
			case "nuevo"	:	menu_scrap(); nuevo($comentario,$accion_correctiva); break;
			case "guardar_1":	menu_scrap();
								guardar_1($partes,$partes_name,$comentario,$accion_correctiva); break;
			
			case "cancelar"	:	$s_ = "delete from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_); 
								$s_ = "delete from scrap_codigos_tmp where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_); 
								$s_ = "delete from scrap_partes_35 where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_);
								nuevo($comentario,$accion_correctiva); break;					
			case "guardar"	:	guardar(); 
								$s_ = "delete from scrap_partes_35 where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_);
								$s_ = "delete from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_);
								$s_ = "delete from scrap_codigos_tmp where id_emp='$_SESSION[IDEMP]'";
								$r_ = mysql_query($s_); break;					
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



	
function menu_scrap() { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo" align="left">CAPTURA POR ARCHIVO MANUAL</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
	<span title="header=[&nbsp;&nbsp;Captura de Scrap] body=[Complete todos los campos de cada parte del formulario. Los cuadros de colores indican lo siguiente:<table align='center' border='0' cellspacing='2'><tr bgcolor='#FFFFFF'><td class='obligatorio' width='60'>&nbsp;ROJO</td><td width='100'>&nbsp;Obligatorio</td></tr><tr bgcolor='#FFFFFF'><td class='automatico'>&nbsp;VERDE</td><td>&nbsp;Automático</td></tr><tr bgcolor='#FFFFFF'><td class='opcional'>&nbsp;AZUL</td><td>&nbsp;Opcional</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>
</tr>
</table>
<hr><br>
<?php } 


function nuevo($comentario,$accion_correctiva) { 

	$s_2 = "select * from configuracion where variable='ruta_capturas'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	if(file_exists($d_2['valor']."partes_UID".$_SESSION["IDEMP"].".csv")) { 
		unlink($d_2['valor']."partes_UID".$_SESSION["IDEMP"].".csv"); }

	$s_2  = "select * from configuracion where variable='ruta_evidencias'";
	$r_2  = mysql_query($s_2);
	$d_2  = mysql_fetch_array($r_2);
	$data = glob($d_2['valor']."evidencia_UID".$_SESSION["IDEMP"].".*");
	if(count($data)>0) {
		$pext = getFileExtension($data['0']); 
		unlink($d_2['valor']."evidencia_UID".$_SESSION["IDEMP"].".".$pext); }

	$s_ = "delete from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	$s_ = "delete from scrap_partes_35 where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);	?>

<form action="?op=nuevo" method="post" name="form1" enctype="multipart/form-data">
<table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr><td colspan="10" class="naranja" align="left">
	PASO 1: Seleccione el archivo con los números de parte e información que se cargará:&nbsp;&nbsp;
	<a href="../admin/archivos/ejemplo_manual.csv" class="menuLink">Decargar Ejemplo</a></td></tr> 
<tr><td colspan="10" align="left">Archivo carga manual:&nbsp;&nbsp;<input type="file" name="partes" class="texto" size="50"></td></tr>  
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr height="35">
	<td colspan="10"><a href="popup_series.php?op=proyectos" target="_blank" onClick="javascript:window.open(this.href, this.target,'height=600,width=800,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes'); return false;"><span class="link_paginas"><img src="../imagenes/information.png" border="0">&nbsp;&nbsp; Si tiene duda en los nombres de plantas, divisiones, segmentos y/o profit center en base al proyecto, puede consultar esta ayuda.</span></a></td>
</tr>    
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="10">
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
	<tr>
		<td align="center"><img src="../imagenes/cuadro_azul.gif">&nbsp;Comentarios</td>
		<td width="10" rowspan="2">&nbsp;</td>
		<td align="center"><img src="../imagenes/cuadro_azul.gif">&nbsp;Acci&oacute;n Correctiva</td>
	</tr>
	<tr>
		<td align="center">
			<textarea name="comentario" cols="60" rows="2" class="texto" tabindex="24"><?php echo $comentario;?></textarea></td>
		<td align="center">
			<textarea name="accion_correctiva" cols="60" rows="2" class="texto" tabindex="25"><?php echo $accion_correctiva;?></textarea></td>
	</table></td>
</tr>

</table>
<br><div align="center" class="naranja">&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(is_bloqueado()=='SI') { 
	echo "El administrador está modificando el sistema.<br>Espere un momento y actualice<br><br>"; $dis = 'disabled'; } ?>
	<input type="button" value="Siguiente" onClick="validar();" class="submit" tabindex="27" <?php echo $dis;?>>
</div>
</form>
<?php } 


function guardar_1($partes,$partes_name,$comentario,$accion_correctiva) {

	$s_ = "select * from configuracion where variable='ruta_capturas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server   = $d_['valor'];
	$pext       = getFileExtension($partes_name);
	$nombre_    = "partes_UID".$_SESSION["IDEMP"].".".$pext;	
	$nom_final_ = $r_server.$nombre_;
	if (is_uploaded_file($partes)) {
		if (!copy($partes,"$nom_final_")) {
			echo "<script>alert('Error al subir el archivo de partes: $nom_final_');</script>";
			nuevo($comentario,$accion_correctiva); exit;
		}	
	} insert_csv($nombre_,$comentario,$accion_correctiva);
} 


function insert_csv($nombre_,$comentario,$accion_correctiva) {
	$s_ = "select * from configuracion where variable='ruta_capturas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_); 
	
	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d"); $e=0;
	$fd       = fopen ($r_server."partes_UID".$_SESSION["IDEMP"].".csv", "r");

	while ( !feof($fd) ) 
 	{
		$buffer   = fgets($fd);
		$campos   = split (",", $buffer);
		$insertar = 0; 	

		if($campos['0']!='' && $campos['0']!='Turno') {

    		$fecha	 = date("Y-m-d");
			$anio	 = date("Y"); $i=0;
			list($anio,$mes,$dia) = split("-",$fecha);
    		$semana  = date('W',mktime(0,0,0,$mes,$dia,$anio));
	
			$folios[0] = $_SESSION['IDEMP']; 
			$folios[1] = $_SESSION['NAME'];
			$folios[2] = $fecha; 
			$folios[3] = $semana;
			$folios[4] = $anio;
			$folios[5] = trim($campos[0]); //Turno
		//Validar que la planta exista
		$s_1 = "Select id, nombre from plantas where nombre like '".trim(utf8_encode($campos[1]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {	
		$d_1 = mysql_fetch_array($r_1);	
			$folios[8]  = $d_1['id']; //ID Planta
			$folios[9]  = $d_1['nombre']; //Nombre Planta 
		} else { $insertar++; $error[$e].= "El nombre de la planta no existe: $campos[1].<br>"; }	
		//Validar que la división exista
		$s_1 = "Select id, nombre from divisiones where nombre like '".trim(utf8_encode($campos[2]))."' and id_planta='$folios[8]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {	
		$d_1 = mysql_fetch_array($r_1);		
			$folios[10] = $d_1['id']; //ID División
			$folios[11] = $d_1['nombre']; //Nombre División
		} else { $insertar++; $error[$e].= "El nombre de la división no existe para la planta: $campos[2] ($campos[1]).<br>"; }		
		//Validar que el segmento exista
		$s_1 = "Select id, nombre from segmentos where nombre like '".trim(utf8_encode($campos[3]))."' and id_planta='$folios[8]' and id_division='$folios[10]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {	
		$d_1 = mysql_fetch_array($r_1);		
			$folios[12] = $d_1['id']; //ID Segmento
			$folios[13] = $d_1['nombre']; //Nombre Segmento
		} else { $insertar++; $error[$e].= "El nombre del segmento no existe para la división y planta: $campos[3] ($campos[1], $campos[2]).<br>"; }		
		//Validar que el profit center exista
		$s_1 = "Select id, nombre from profit_center where nombre like '".trim(utf8_encode($campos[4]))."' and id_planta='$folios[8]' and id_division='$folios[10]' and ";
		$s_1.= "id_segmento='$folios[12]' and activo='1'";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {	
		$d_1 = mysql_fetch_array($r_1);	
			$folios[14] = $d_1['id']; //ID ceco
			$folios[15] = $d_1['nombre']; //Nombre ceco
		} else { $insertar++; 
				 $error[$e].= "El nombre del profit center no existe para la planta, división y segmento: $campos[4] ($campos[1], $campos[2], $campos[3]).<br>"; }	
		//Validar que el proyecto exista
		$s_1 = "Select id, nombre from proyectos where nombre like '".trim(utf8_encode($campos[5]))."' and id_planta='$folios[8]' and id_division='$folios[10]' and ";
		$s_1.= "id_segmento='$folios[12]' and id_pc='$folios[14]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {	
		$d_1 = mysql_fetch_array($r_1);		
			$folios[6] = $d_1['id']; //ID proyecto
			$folios[7] = $d_1['nombre']; //Nombre proyecto
		} else { $insertar++; 
				 $error[$e].= "El nombre del proyecto no existe para la planta, división, segmento y PC: $campos[5] ($campos[1], $campos[2], $campos[3], $campos[4]).<br>";}	
		//Validar que el APD exista
		$apd = str_pad(trim($campos[6]),"3","0",STR_PAD_LEFT);
		$s_1 = "Select id, nombre from apd where nombre like '$apd' and id_division='$folios[10]' and id_segmento='$folios[12]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {		
		$d_1 = mysql_fetch_array($r_1);		
			$folios[16] = $d_1['id']; //ID APD
			$folios[17] = $d_1['nombre']; //APD
		} else { $insertar++; $error[$e].= "El nombre del APD no existe para la división y segmento: $apd ($campos[2], $campos[3]).<br>"; }
		//Validar que el área exista
		$s_1 = "Select id, nombre from areas where nombre like '".trim(utf8_encode($campos[7]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {		
		$d_1 = mysql_fetch_array($r_1);		
			$folios[18] = $d_1['id']; //ID Área
			$folios[19] = $d_1['nombre']; //Área
		} else { $insertar++; $error[$e].= "El nombre del área no existe: $campos[7].<br>"; }
		//Validar que la tecnología exista
		$s_1 = "Select id, nombre from estaciones where nombre like '".trim(utf8_encode($campos[8]))."' and id_area like '$folios[18]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {		
		$d_1 = mysql_fetch_array($r_1);		
			$folios[20] = $d_1['id']; //ID Tecnología
			$folios[21] = $d_1['nombre']; //Tecnología
		} else { $insertar++; $error[$e].= "El nombre de la tecnología no existe para el área: $campos[8] ($campos[7]).<br>"; }
		//Validar que la línea exista
		$s_1 = "Select id, nombre from lineas where nombre like '".trim(utf8_encode($campos[9]))."' and id_area like '$folios[18]' and id_estacion like ";
		$s_1.= "'$folios[20]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);				
			$folios[22] = $d_1['id']; //ID Línea
			$folios[23] = $d_1['nombre']; //Línea
		} else { $insertar++; $error[$e].= "El nombre de la línea no existe para el área y estación: $campos[9] ($campos[7], $campos[8]).<br>"; }
		//Validar que el defecto exista
		$s_1 = "Select id, nombre from defectos where nombre like '".trim(utf8_encode($campos[10]))."' and id_area like '$folios[18]' and id_estacion like '$folios[20]' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);				
			$folios[24] = $d_1['id']; //ID Defecto	
			$folios[25] = $d_1['nombre']; //Defecto
		} else { $insertar++; $error[$e].= "El nombre del defecto no existe para el área y estación: $campos[10] ($campos[7], $campos[8]).<br>"; }
		//Validar que la causa exista
		$s_1 = "Select id, nombre from causas where nombre like '".trim(utf8_encode($campos[11]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);				
			$folios[26] = $d_1['id']; //ID Causa
			$folios[27] = $d_1['nombre']; //Causa
		} else { $insertar++; $error[$e].= "El nombre de la causa no existe: $campos[11].<br>"; }
		//Validar que el código de scrap exista
		$s_1 = "Select * from codigo_scrap where codigo like '".trim(utf8_encode($campos[12]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);				
			$folios[28] = $d_1['codigo']; //Coódigo SCRAP
			$folios[29] = $d_1['financiero']; //Financiero
			$folios[30] = $d_1['reason_code']; //Reason Code
			$folios[31] = $d_1['orden_interna']; //Orden Interna
			$folios[32] = $d_1['txs_sap']; //TXS SAP
			$folios[33] = $d_1['mov_sap']; //Mov. SAP
		} else { $insertar++; $error[$e].= "El nombre del código de scrap no existe: $campos[12].<br>"; }
		//Validar que el supervisor exista
		$s_1 = "Select id, nombre, apellidos from empleados where usuario like '".trim(utf8_encode($campos[17]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);					
			$folios[34] = $d_1['id']; //ID Supervisor
			$folios[35] = $d_1['nombre']." ".$d_1['apellidos']; //Supervisor
		} else { $insertar++; $error[$e].= "El nombre del supervisor no existe: $campos[17].<br>"; }
		$folios[36] = trim($campos[18]); //Operador
		$folios[37] = trim($campos[19]); //No.Personal		
		$folios[38] = "NA";//Info_1
		$folios[39] = "NA";//Info_2
		$folios[40] = "0"; //O.Mantto.
		$folios[41] = ''; //Archivo
			$comentario = str_replace("/","",$comentario);
		$folios[42] = htmlentities($comentario,ENT_QUOTES,"UTF-8"); //Comentario	
			$accion_correctiva = str_replace("/","",$accion_correctiva);
		$folios[43] = htmlentities($accion_correctiva,ENT_QUOTES,"UTF-8"); //Acción Correctiva
		//Validar que el número de parte exista
			$tipos 			 = get_tipo(trim($campos[14])); //Obtener los tipos					
			$partes['num_p'] = trim(strtoupper($campos[14])); //Número de parte
			$partes['tipo']  = $tipos['tipo']; //Tipo
			$partes['subt']  = $tipos['subt']; //Subtipo
			$partes['desc']  = $tipos['desc']; //Descripción
			$partes['cost']  = $tipos['costo']; //Costo
			$partes['cant']  = trim($campos['15']); //Cantidad
			$partes['total'] = $tipos['costo']*$partes['cant']; //Total
			$partes['serial']= trim($campos['16']); //Seriales 
			$partes['ubic']  = trim($campos['20']); //Ubicación 
		$s_1 = "Select * from numeros_parte where nombre = '".trim(utf8_encode($campos[14]))."' and activo='1'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {
			$insertar++; $error[$e].= "El número de parte no existe: $campos[14].<br>"; }	
		
		$partes['papa'] = trim(strtoupper($campos[13])); //Parte Padre del archivo

		$s_1 = "select * from partes_padre where material='$partes[num_p]' and activo!='2' and apd like '$folios[17]'";
		$r_1 = mysql_query($s_1); 
		if(mysql_num_rows($r_1)<=0) {
			$insertar++; $error[$e].= "El número de parte ($partes[num_p]) no existe para el APD ($folios[17]) seleccionado.<br>"; } 

			//Inserto la información en la boleta de folios temporal
			$s_1 = "insert into scrap_folios_tmp values ('',";
			for($i=0;$i<count($folios);$i++) {
				$s_1 = $s_1."'".$folios[$i]."',"; }
			$s_1 = substr($s_1,0,-1).")";
			$r_1 = mysql_query($s_1); 
			//Inserto la información en la tabla de números de parte
			$query  = "INSERT into scrap_partes_35 values('', '$partes[papa]', '$partes[num_p]', '$partes[tipo]', '$partes[subt]', ";
			$query .= "'$partes[desc]', '$partes[cant]', '$partes[cost]', '$partes[total]', 'NA', '$partes[serial]', '$partes[ubic]', '$folios[6]', ";
			$query .= "'$folios[22]', '$folios[5]', '$folios[34]', '$folios[36]', '$folios[16]', '$_SESSION[IDEMP]','$error[$e]')"; 
			$result = mysql_query($query); 
		/*} else { 
			echo "<div align='center' class='rojo'>$error</div>"; 
			fclose ($fd); unlink($r_server."partes_UID".$_SESSION["IDEMP"].".csv");	exit; }	*/			
		$e++; }			
	}     
	fclose ($fd); 
	unlink($r_server."partes_UID".$_SESSION["IDEMP"].".csv"); 
	listado_temporal();
}



function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}


function listado_temporal() { 
	$s_1 = "select * from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]' group by id_proyecto, id_linea, turno, id_supervisor, operador, id_apd ";
	$s_1.= "order by id_proyecto asc";
	$r_1 = mysql_query($s_1);
	$n_1 = mysql_num_rows($r_1); ?>
<form action="?op=guardar" method="post" name="form2">
<div align="left" style="margin-left:90px;" class="naranja">
	PASO 2: Revise que la información para la carga sea correcta. Sólo se cargará la información que no está en rojo.</div><br>

<br><table align="center" class="texto" cellpadding="2" cellspacing="2" border="0">
<tr bgcolor="#CCCCCC">
	<td width="40" align="center" class="gris"><b>No.</b></td>
    <td width="120" align="center" class="gris"><b>Proyecto</b></td>
    <td width="120" align="center" class="gris"><b>APD</b></td>
    <td width="120" align="center" class="gris"><b>Línea</b></td>
    <td width="120" align="center" class="gris"><b>Turno</b></td>
    <td width="120" align="center" class="gris"><b>Parte Padre</b></td>
    <td width="120" align="center" class="gris"><b>No.Parte</b></td>
	<td width="40" align="center" class="gris"><b>Cantidad</b></td>
	<td width="40" align="center" class="gris"><b>Tipo</b></td>
	<td width="100" align="center" class="gris"><b>No.Serial</b></td>
	<td width="70" align="center" class="gris"><b>Ubicación</b></td>
    <td width="400" align="center" class="gris"><b>Error</b></td>
</tr>	
<?php $s_ = "select * from scrap_partes_35 where id_emp='$_SESSION[IDEMP]' order by id asc";
      $r_ = mysql_query($s_); $items = mysql_num_rows($r_); $i=1;
      while($d_=mysql_fetch_array($r_)) { 
	  if($d_['error']!='') { $color = '#FF0000'; } else { $color = '#333333'; } ?>
<tr bgcolor="#EEEEEE">
	<td align="center" valign="top" style="color:<?php echo $color;?>;"><?php echo $i;?></td>
    <td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo get_proy_name($d_['proyecto']);?></td>
    <td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo get_apd_name($d_['apd']);?></td>
    <td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo get_linea_name($d_['linea']);?></td>
    <td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo $d_['turno'];?></td>
	<td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo $d_['padre'];?></td>
	<td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo $d_['no_parte'];?></td>
	<td align="center" valign="top" style="color:<?php echo $color;?>;"><?php echo $d_['cantidad'];?></td>
	<td align="center" valign="top" style="color:<?php echo $color;?>;"><?php echo $d_['tipo'];?></td>
	<td align="left" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo $d_['serial_unidad'];?></td>
	<td align="center" rowspan="<?php echo $total;?>" valign="top" style="color:<?php echo $color;?>;"><?php echo $d_['ubicacion'];?></td>
    <td align="left" rowspan="<?php echo $total;?>" valign="top" style="color:<?php echo $color;?>;">&nbsp;<?php echo $d_['error'];?></td>
</tr>
<?php $i++; } ?>
</table>
<br><div align="center">
<input type="button" value="Cancelar" class="submit" onclick="cancelar();" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Exportar" class="submit" onclick="exportar();" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="Guardar" class="submit">
</div>
<?php } 


function get_proy_name($id_) {
	$s_ = "select nombre from proyectos where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre']; 
}

function get_apd_name($id_) {
	$s_ = "select nombre from apd where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre']; 
}

function get_linea_name($id_) {
	$s_ = "select nombre from lineas where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}


function guardar() {
	$fecha	 = date("Y-m-d");
	$hora	 = date("H:i:s");
	$error   = 0; $i = 0;

$s_ = "select id_proyecto, id_linea, turno, id_supervisor, operador, id_apd from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]' ";
$s_.= "group by id_proyecto, id_linea, turno, id_supervisor, operador, id_apd order by id_proyecto asc";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) {

$s_3 = "select * from scrap_partes_35 where proyecto='$d_[id_proyecto]' and linea='$d_[id_linea]' and apd='$d_[id_apd]' and ";
$s_3.= "turno='$d_[turno]' and supervisor='$d_[id_supervisor]' and operador='$d_[operador]' and error=''";
$r_3 = mysql_query($s_3);
if(mysql_num_rows($r_3)>0) {

		$folio = get_folio();
		aumenta_folio();
		$folios[$i] = $folio; 

		while($d_3=mysql_fetch_array($r_3)) {
			$s_4 = "insert into scrap_partes values('', '$folio', '$d_3[padre]', '$d_3[no_parte]', '$d_3[tipo]', '$d_3[tipo_sub]',";
			$s_4.= "'$d_3[descripcion]', '$d_3[cantidad]', '$d_3[costo]', '$d_3[total]', '$d_3[batch_id]', '$d_3[serial_unidad]', ";
			$s_4.= "'$d_3[ubicacion]', '0', '0', '0')";
			$r_4 = mysql_query($s_4);
			/*LOG SISTEMA*/log_sistema("scrap_partes","nuevo",$folio,$s_4);
		}

	$s_1 = "select * from scrap_folios_tmp where id_proyecto='$d_[id_proyecto]' and id_linea='$d_[id_linea]' and turno='$d_[turno]'";
	$s_1.= " and id_supervisor='$d_[id_supervisor]' and operador='$d_[operador]' and id_apd='$d_[id_apd]' group by id_proyecto, ";
	$s_1.= "id_linea, turno, id_supervisor, operador, id_apd";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); 
	
	$s_2 = "insert into scrap_folios values('', '$d_1[id_emp]', '$d_1[empleado]', '$folio', '$d_1[fecha]', '$hora', '$d_1[semana]', ";
	$s_2.= "'$d_1[anio]', '$d_1[turno]', '$d_1[id_proyecto]', '$d_1[proyecto]', '$d_1[id_planta]', '$d_1[planta]', ";
	$s_2.= "'$d_1[id_division]', '$d_1[division]', '$d_1[id_segmento]', '$d_1[segmento]', '$d_1[id_pc]', '$d_1[profit_center]', ";
	$s_2.= "'$d_1[id_apd]', '$d_1[apd]', '$d_1[id_area]', '$d_1[area]', '$d_1[id_estacion]', '$d_1[estacion]', '$d_1[id_linea]', ";
	$s_2.= "'$d_1[linea]', '$d_1[id_defecto]', '$d_1[defecto]', '$d_1[id_causa]', '$d_1[causa]', '$d_1[codigo_scrap]', ";
	$s_2.= "'$d_1[financiero]', '$d_1[reason_code]', '$d_1[orden_interna]', '$d_1[txs_sap]', '$d_1[mov_sap]', '$d_1[id_supervisor]', ";
	$s_2.= "'$d_1[supervisor]','$d_1[operador]', '$d_1[no_personal]', '$d_1[info1]', '$d_1[info2]', '$d_1[o_mantto]', ";
	$s_2.= "'$d_1[archivo]', '$d_1[comentario]', '$d_1[accion_correctiva]', '0', '0', '1', '0')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap_folios","nuevo",$folio,$s_2);

	autorizaciones($folio,$d_1['id_planta'],$d_1['codigo_scrap'],$d_1['id_proyecto']);
	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '6', ";
	$s_2.= "'$fecha', '$hora', 'Creaci&oacute;n de la boleta')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("aut_bitacora","nuevo",$folio,$s_2);

	//Validar que la boleta se haya guardado correctamente
	$s_6 = "select * from scrap_folios where no_folio='$folio'";
	$r_6 = mysql_query($s_6);
	if(mysql_num_rows($r_6)<=0) { $movimiento = "La boleta no se guardo en scrap_folios."; $error++; }
	$s_6 = "select * from scrap_partes where no_folio='$folio'";
	$r_6 = mysql_query($s_6);
	if(mysql_num_rows($r_6)<=0) { $movimiento = "La boleta no se guardo en scrap_partes."; $error++; }	
	$s_6 = "select * from aut_bitacora where no_folio='$folio'";
	$r_6 = mysql_query($s_6);
	if(mysql_num_rows($r_6)<=0) { $movimiento = "La boleta no se guardo en aut_bitacora."; $error++; }		
	$s_6 = "select * from autorizaciones where no_folio='$folio'";
	$r_6 = mysql_query($s_6);
	if(mysql_num_rows($r_6)<=0) { $movimiento = "La boleta no se guardo en autorizaciones."; $error++; }	

	if($error>0) { 
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/exclamation.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=rojo><b>Error en la captura del folio:</strong><br><br>";
			echo"<span style='color:#FF6600; font-size:20px;'><b>$folio</b><br><br>";
		echo"<tr><td align=center>";
			echo"<br><strong class=rojo><b>Contacte al administrador del sistema!</b><br><br>";	
		echo"</td></tr></table>";
		echo"<form method='post' action='?op=nuevo'>";
		echo "<div align=center><input type='submit' value='Regresar' class='submit'></div>";
		echo"</form>";	
		$s_ = "update configuracion set valor='SI' where variable='bloqueado'";
		$r_ = mysql_query($s_); 
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$s_);
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$movimiento); exit;
	} else { $i++; $error=0; }
} }	
	echo"<br><br>";
	echo"<table align=center width=500 bgcolor=#FFFFFF>";
		echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
		echo"<tr><td align=center>";
		echo"<br><strong class=texto>Boletas almacenadas con los folios:</strong><br><br>";
		for($i=0;$i<count($folios);$i++) { 
			echo"<span style='color:#FF6600; font-size:20px;'><b>$folios[$i]</b><br>"; }
	echo"</td></tr></table>";
	echo"<form method='post' action='?op=nuevo$ruta'>";
	echo "<div align=center><input type='submit' value='Continuar' class='submit'></div>";
	echo"</form>";
} ?>
