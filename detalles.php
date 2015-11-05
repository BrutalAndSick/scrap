<?php session_name("loginUsuario"); 
      session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/style_main.css" rel="stylesheet" type="text/css">

<script>
function regresar() {
	form_1.action = '?op=autorizaciones';
	form_1.submit();
}
function validar_cancelar() {
	if(form_1.comentario.value=='') {
		alert('Debe ingresar el comentario');
		form_1.comentario.focus();
		return; }
	else { 
		form_1.submit();
	}		
}
</script>


<?php include('conexion_db.php');

switch($op) {
	case "ver_boleta"		:	ver_boleta($folio,$buscar); break; 
	case "historial"		:	historial($folio,$buscar); break;
	case "ver_mail"			:	ver_mail($id_,$leido); break;
	case "autorizaciones"	:	autorizaciones($folio); break;
	case "status"			:	status($folio,$tipo); autorizaciones($folio); break;
	case "autorizaciones2"	:	autorizaciones2($folio); break;
	case "autorizaciones3"	:	autorizaciones3($folio,$anio); break;
	case "cancelar"			:	cancelar($folio); break;
	case "cancelar_g"		:	cancelar_g($folio,$comentario); break;
}


function autorizaciones3($folio,$anio) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">FIRMAS REQUERIDAS PARA EL FOLIO <?php echo $folio;?></td>
</tr>
</table></div><hr><br>

<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Depto.</td>
	<td align="center" width="250">Empleado</td>
	<td align="center" width="50">Estado</td>
	<td align="center" width="80">Fecha</td>
    <td align="center" width="80">Hora</td>
	<td align="center" width="200">Comentario</td>
    <td align="center" width="150">Motivo</td>
</tr>	
</thead>
<tbody>
<?php $s_2 = "select * from ".$anio."_autorizaciones where no_folio = '$folio' order by depto";
	  $r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;
	<?php 	switch($d_2['depto']) {
			case "lo"	:	echo "LO"; break;
			case "loa"	:	echo "LO Almacén"; break;
			case "lpl"	:	echo "LPL"; break;
			case "ffm"	:	echo "FFM"; break;
			case "ffc"	:	echo "FFC"; break;
			case "prod"	:	echo "Producción"; break;
			case "sqm"	:	echo "SQM"; break;
			case "fin"	:	echo "Finanzas"; break;
			case "esp_1":	echo "Especial"; break;
			case "esp_2":	echo "Especial"; break;
			case "inv"	:	echo "Inventarios"; break;
			case "oes"	:	echo "OES"; break;
	} ?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['empleado'];?></td>
	<td align="center">
	<?php	switch($d_2['status']) {
			case "0"	:	echo "<img src='imagenes/flag_orange.gif'>"; break;
			case "1"	:	echo "<img src='imagenes/flag_green.gif'>"; break;
			case "2"	:	echo "<img src='imagenes/flag_red.gif'>"; break;
			case "3"	:	echo "<img src='imagenes/cross.png'>"; break;
	} ?></td>
	<?php $data = get_data_bitacora_old($folio,$d_2['depto'],$d_2['id_emp'],$anio) ;?>
	<td align="center"><?php echo $data['fecha'];?></td>
    <td align="center"><?php echo $data['hora'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $data['coment'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $data['motivo'];?></td>
</tr><?php } ?>	
</tbody>
</table>
<?php }


function autorizaciones2($folio) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">FIRMAS REQUERIDAS PARA EL FOLIO <?php echo $folio;?></td>
</tr>
</table></div><hr><br>

<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Depto.</td>
	<td align="center" width="250">Empleado</td>
	<td align="center" width="50">Estado</td>
	<td align="center" width="80">Fecha</td>
    <td align="center" width="80">Hora</td>
	<td align="center" width="200">Comentario</td>
    <td align="center" width="150">Motivo</td>
</tr>	
</thead>
<tbody>
<?php $s_2 = "select * from autorizaciones where no_folio = '$folio' order by depto";
	  $r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;
	<?php 	switch($d_2['depto']) {
			case "lo"	:	echo "LO"; break;
			case "loa"	:	echo "LO Almacén"; break;
			case "lpl"	:	echo "LPL"; break;
			case "ffm"	:	echo "FFM"; break;
			case "ffc"	:	echo "FFC"; break;
			case "prod"	:	echo "Producción"; break;
			case "sqm"	:	echo "SQM"; break;
			case "fin"	:	echo "Finanzas"; break;
			case "esp_1":	echo "Especial"; break;
			case "esp_2":	echo "Especial"; break;
			case "inv"	:	echo "Inventarios"; break;
			case "oes"	:	echo "OES"; break;
	} ?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['empleado'];?></td>
	<td align="center">
	<?php	switch($d_2['status']) {
			case "0"	:	echo "<img src='imagenes/flag_orange.gif'>"; break;
			case "1"	:	echo "<img src='imagenes/flag_green.gif'>"; break;
			case "2"	:	echo "<img src='imagenes/flag_red.gif'>"; break;
			case "3"	:	echo "<img src='imagenes/cross.png'>"; break;
	} ?></td>
	<?php $data = get_data_bitacora($folio,$d_2['depto'],$d_2['id_emp']) ;?>
	<td align="center"><?php echo $data['fecha'];?></td>
    <td align="center"><?php echo $data['hora'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $data['coment'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $data['motivo'];?></td>
</tr><?php } ?>	
</tbody>
</table>
<?php }


function autorizaciones($folio) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">FIRMAS REQUERIDAS PARA EL FOLIO <?php echo $folio;?></td>
</tr>
</table></div><hr><br>

<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Depto.</td>
	<td align="center" width="250">Empleado</td>
	<td align="center" width="50">Estado</td>
	<td align="center" width="80">Fecha</td>
    <td align="center" width="80">Hora</td>
	<td align="center" width="200">Comentario</td>
    <td align="center" width="150">Motivo</td>
</tr>	
</thead>
<tbody>
<?php $s_2 = "select * from autorizaciones where no_folio = '$folio' order by depto";
	  $r_2 = mysql_query($s_2); 
	while($d_2=mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;
	<?php 	switch($d_2['depto']) {
			case "lo"	:	echo "LO"; break;
			case "loa"	:	echo "LO Almacén"; break;
			case "lpl"	:	echo "LPL"; break;
			case "ffm"	:	echo "FFM"; break;
			case "ffc"	:	echo "FFC"; break;
			case "prod"	:	echo "Producción"; break;
			case "sqm"	:	echo "SQM"; break;
			case "fin"	:	echo "Finanzas"; break;
			case "esp_1":	echo "Especial"; break;
			case "esp_2":	echo "Especial"; break;
			case "inv"	:	echo "Inventarios"; break;
			case "oes"	:	echo "OES"; break;
	} ?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['empleado'];?></td>
	<td align="center">
	<?php	switch($d_2['status']) {
			case "0"	:	echo "<img src='imagenes/flag_orange.gif'>"; break;
			case "1"	:	echo "<img src='imagenes/flag_green.gif'>"; break;
			case "2"	:	echo "<img src='imagenes/flag_red.gif'>"; break;
			case "3"	:	echo "<img src='imagenes/cross.png'>"; break;
	} ?></td>
	<?php $data = get_data_bitacora($folio,$d_2['depto'],$d_2['id_emp']) ;?>
	<td align="center"><?php echo $data['fecha'];?></td>
    <td align="center"><?php echo $data['hora'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $data['coment'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $data['motivo'];?></td>
</tr><?php } ?>	
</tbody>
</table>
<?php if($_SESSION["DEPTO"]=="inv") { 
      $s_ = "select * from autorizaciones, scrap_folios as folios where folios.no_folio='$folio' and autorizaciones.depto = '$_SESSION[DEPTO]' and ";
      $s_.= "autorizaciones.status='1' and autorizaciones.no_folio = folios.no_folio and folios.activo='1' and autorizaciones.id_emp='$_SESSION[IDEMP]'";
	  $r_ = mysql_query($s_);
	 if(mysql_num_rows($r_)>0) { ?>
<div align="center">
<form method="post" action="?op=cancelar">
<input type="hidden" name="folio" value="<?php echo $folio;?>">	
	<input type="submit" class="submit" value="Cancelar Folio">
</form></div>	
<?php } } 
      $s_ = "select * from autorizaciones, scrap_folios as folios where folios.no_folio='$folio' and autorizaciones.depto = ";
      $s_.= "'$_SESSION[DEPTO]' and autorizaciones.status='2' and autorizaciones.no_folio = folios.no_folio and folios.activo='1'";
	  $r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { ?>
<div align="center">
<form method="post" action="?op=status">
<input type="hidden" name="folio" value="<?php echo $folio;?>">	
<input type="hidden" name="tipo" value="rechazado">	
	<input type="submit" class="submit" value="Cambiar Estado" onclick="return confirm('¿Remover estado rechazado?')">
</form></div>	
<?php } 
     $s_ = "select * from autorizaciones, scrap_folios as folios where folios.no_folio='$folio' and autorizaciones.depto = ";
     $s_.= "'$_SESSION[DEPTO]' and autorizaciones.status='3' and autorizaciones.no_folio = folios.no_folio and folios.activo='2'";
	 $r_ = mysql_query($s_);
	 if(mysql_num_rows($r_)>0) { ?>
<div align="center">
<form method="post" action="?op=status">
<input type="hidden" name="folio" value="<?php echo $folio;?>">	
<input type="hidden" name="tipo" value="cancelado">	
	<input type="submit" class="submit" value="Quitar Cancelación" onclick="return confirm('¿Quitar cancelación del folio?')">
</form></div>	
<?php } }


function get_data_bitacora_old($folio,$depto,$id_emp,$anio) {
	$s_ = "select * from ".$anio."_aut_bitacora where no_folio='$folio' and id_emp='$id_emp' ";
	if($depto=='esp_1' || $depto=='esp_2') { $s_.= "and depto='esp' "; } 
	else { $s_.= "and depto='$depto' "; }
	$s_.= "order by fecha desc, hora desc";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	$data['fecha']  = $d_['fecha'];
	$data['hora']   = $d_['hora'];
	$data['coment'] = $d_['comentario'];
	$data['motivo'] = $d_['motivo'];
	return $data;	
}


function get_data_bitacora($folio,$depto,$id_emp) {
	$s_ = "select * from aut_bitacora where no_folio='$folio' and id_emp='$id_emp' ";
	if($depto=='esp_1' || $depto=='esp_2') { $s_.= "and depto='esp' "; } 
	else { $s_.= "and depto='$depto' "; }
	$s_.= "order by fecha desc, hora desc";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	$data['fecha']  = $d_['fecha'];
	$data['hora']   = $d_['hora'];
	$data['coment'] = $d_['comentario'];
	$data['motivo'] = $d_['motivo'];
	return $data;	
}


function get_usuario($id_emp) {
	if($id_emp!='%') {
		$s_ = "select * from empleados where id='$id_emp'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['usuario']; }
	else {
		return ""; }	
}


function status($folio,$tipo) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	$timer = date("YmdHis");

if($tipo=='rechazado') { 
	if($_SESSION['DEPTO']!='inv') { 
		$s_1 = "update autorizaciones set status='1', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$folio' ";
		$s_1.= "and depto='$_SESSION[DEPTO]'";	
		$r_1 = mysql_query($s_1);
		$s_1 = "update scrap_folios set status='0', timer='$timer' where no_folio='$folio'";
		$r_1 = mysql_query($s_1); 
		$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', ";
		$s_2.= "'$fecha', '$hora', '$comentario', '')";
		$r_2 = mysql_query($s_2); }
	else { 
		$s_1 = "select * from scrap_folios where no_folio='$folio'";
		$r_1 = mysql_query($s_1);
		$d_1 = mysql_fetch_array($r_1);		
		//Vuelvo a crear todas las autorizaciones necesarias en cero y se recomienza el envío de correos
		crea_autorizaciones($folio,$d_1['id_planta'],$d_1['codigo_scrap'],$d_1['id_division'],$d_1['id_pc'],$d_1['id_area']);
		$s_1 = "update scrap_folios set status='0', timer='$timer' where no_folio='$folio'";
		$r_1 = mysql_query($s_1); 
		$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', ";
		$s_2.= "'$fecha', '$hora', '$comentario', '')";
		$r_2 = mysql_query($s_2);		
	}
}		
		
if($tipo=='cancelado') { 		 
	$s_1 = "update scrap_folios set activo='1', timer='$timer' where no_folio='$folio'";
	$r_1 = mysql_query($s_1);
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '4', '$fecha', ";
	$s_2.= "'$hora', '', '')";
	$r_2 = mysql_query($s_2);
	$s_3 = "update autorizaciones set status='0', id_emp='%', empleado='' where no_folio='$folio' and depto='inv'";
	$r_3 = mysql_query($s_3); }	
} 


function cancelar($folio) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CANCELAR EL FOLIO: <?php echo $folio;?></td>
</tr>
</table></div><hr><br>
<form method="post" action="?op=cancelar_g" name="form_1">
<input type="hidden" name="folio" value="<?php echo $folio;?>">
<table align="center" class="tabla">
<caption>Debe especificar la razón para cancelar el folio</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150">Comentario:</td>
		<td align="center" width="150">
			<textarea name="comentario" class="texto" cols="80" rows="2"></textarea>
		</td>
	</tr>
</thead>
</table>
<br><div align="center">
<input type="button" value="Regresar" onclick="regresar();" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar_cancelar();" class="submit">
</div>
</form>
<?php } 

function cancelar_g($folio,$comentario) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
	$s_1 = "update scrap_folios set activo='2' where no_folio='$folio'";
	$r_1 = mysql_query($s_1); 

	$s_1 = "update autorizaciones set status='3', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where depto='inv' and no_folio='$folio'";
	$r_1 = mysql_query($s_1); 
	
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '3', '$fecha', '$hora', '$comentario', '')";
	$r_2 = mysql_query($s_2);
		
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='imagenes/rechazado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>El folio $folio ha sido cancelado</strong><br>";
			echo"<br><strong class=texto>Recuerde que debe actualizar el reporte para ver los cambios</strong><br>";
		echo"</td></tr></table>";
		echo"<form name='form_1' method='post'>";
		echo"<input type='hidden' name='folio' value='".$folio."'>";
		echo"<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
		echo"</form>";
}


/**/function crea_autorizaciones($folio,$planta,$codigo_scrap,$id_div,$prce,$area) {

	$s_2 = "delete from autorizaciones where no_folio='$folio'";
	$r_2 = mysql_query($s_2);		

	$s_1 = "select * from codigo_scrap_depto where codigo_scrap='$codigo_scrap' and id_planta='$planta' order by tipo";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) {
			$s_2 = "insert into autorizaciones values('', '$folio', '$d_1[tipo]', '%', '', '0', '')";
			$r_2 = mysql_query($s_2); 
	} 
	$s_2 = "insert into autorizaciones values('', '$folio', 'inv', '%', '', '0', '')";
	$r_2 = mysql_query($s_2); 
}	


function ver_mail($id_,$leido) { 
$fecha = date("Y-m-d");
$hora  = date("H:i:s");
 
	if($leido=='1') {
		$s_ = "update mails set leido_fecha='$fecha', leido_hora='$hora' where id='$id_'";
		$r_ = mysql_query($s_); } ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">LEER E-MAIL</td>	
</tr>
</table><hr><br>
<?php $s_ = "select * from mails where id='$id_'";
   $r_ = mysql_query($s_);
   $d_ = mysql_fetch_array($r_); ?> 
<table align="center" class="tabla" width="550" cellpadding="3">
<tr bgcolor="#F2F2F2">
	<td align="left" width="40"><b>Para:</b></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_['para_name'];?></td>
</tr>
<tr bgcolor="#F2F2F2">
	<td align="left"><b>Mail:</b></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_['para_mail'];?></td>
</tr>
</table><br>
<table align="center" border="0" class="tabla" width="550" cellpadding="3">
<tr bgcolor="#F2F2F2">
	<td align="left"><b>Asunto:</b>&nbsp;&nbsp;<?php echo $d_['asunto'];?>&nbsp;</td>
</tr>
<?php $mensaje = str_replace("Autorizar sus registros pendientes del sistema de Scrap"," ",$d_['mensaje']);
      $mensaje = str_replace("Verificar y aprobar boleta rechazada de Scrap"," ",$mensaje);
	  $mensaje = str_replace("Editar boleta rechazada de Scrap"," ",$mensaje); ?> 
<tr>
	<td align="left"><?php echo html_entity_decode($mensaje);?>&nbsp;</td>
</tr>
</table>
<?php }


function historial($folio,$buscar) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">HISTORIAL DE AUTORIZACIONES PARA EL FOLIO <?php echo $folio;?></td>
</tr>
</table></div><hr><br>
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Depto.</td>
	<td align="center" width="250">Usuario</td>
	<td align="center" width="50">Estado</td>
	<td align="center" width="80">Fecha</td>
	<td align="center" width="70">Hora</td>
	<td align="center" width="250">Comentario</td>
</tr>	
</thead>
<tbody>
<?php $s_2 = "select aut_bitacora.empleado, aut_bitacora.depto, aut_bitacora.status, aut_bitacora.fecha, aut_bitacora.hora, ";
	  $s_2.= "aut_bitacora.comentario from aut_bitacora where aut_bitacora.no_folio = '$folio' order by fecha desc, hora desc"; 
	  $r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;
	<?php 	switch($d_2['depto']) {
			case "lo"	:	echo "LO"; break;
			case "loa"	:	echo "LO Almacén"; break;
			case "lpl"	:	echo "LPL"; break;
			case "ffm"	:	echo "FFM"; break;
			case "ffc"	:	echo "FFC"; break;
			case "prod"	:	echo "Producción"; break;
			case "sqm"	:	echo "SQM"; break;
			case "fin"	:	echo "Finanzas"; break;
			case "esp_1":	echo "Especial"; break;
			case "esp_2":	echo "Especial"; break;
			case "inv"	:	echo "Inventarios"; break;
			case "oes"	:	echo "OES"; break;
	} ?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['empleado'];?></td>
	<td align="center">
	<?php	switch($d_2['status']) {
			case "0"	:	echo "<img src='imagenes/flag_orange.gif' alt='Proceso'>"; break;
			case "1"	:	echo "<img src='imagenes/flag_green.gif' alt='Aprobado'>"; break;
			case "2"	:	echo "<img src='imagenes/flag_red.gif' alt='Rechazado'>"; break;
			case "3"	:	echo "<img src='imagenes/cross.png' alt='Cancelado'>"; break;
			case "4"	:	echo "<img src='imagenes/tick.png' alt='Activo'>"; break;
			case "5"	:	echo "<img src='imagenes/pencil.gif' alt='Editado'>"; break;
			case "6"	:	echo "<img src='imagenes/user.png' alt='Creado'>"; break;
			case "7"	:	echo "SAP"; break;
	} ?></td>
	<td align="center"><?php echo $d_2['fecha'];?></td>
	<td align="center"><?php echo $d_2['hora'];?></td>
	<td align=left>&nbsp;&nbsp;<?php echo $d_2['comentario'];?></td>
</tr><?php } ?>	
</tbody>
</table>
<?php }


function ver_boleta($folio,$buscar) { 
	if($buscar=="no") { $dis = 'disabled'; } else { $dis = ''; } ?>	
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">DETALLES PARA EL FOLIO <?php echo $folio;?></td>
</tr>
</table></div><hr>

<?php $s_1 = "select * from scrap_folios where no_folio='$folio'"; 
      $r_1 = mysql_query($s_1);
      $d_1 = mysql_fetch_array($r_1);
	  $s_2 = "select * from scrap_codigos where no_folio='$folio'";
      $r_2 = mysql_query($s_2);
      $d_2 = mysql_fetch_array($r_2); ?>
<form method="post" action="?op=ver_boleta" name="form1"> 
<table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td width="80">&nbsp;Fecha</td>
	<td width="60" align="center">&nbsp;<b><?php echo $d_1['fecha'];?></b></td>	
	<td width="20" align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td width="30">&nbsp;Folio</td>
	<td width="50" align="center">
    	<input type="text" readonly="readonly" value="<?php echo $folio;?>" class="texto" size="10"></td>
	<td rowspan="9" background="imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td width="95">&nbsp;Área</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['area'];?>" class="texto" size="35"></td>
</tr>
<tr>
	<td align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Turno</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['turno'];?>" class="texto" size="35"></td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Tecnología</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['estacion'];?>" class="texto" size="35"></td>
</tr>
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Proyecto</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['proyecto'];?>" class="texto" size="35"></td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Línea</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['linea'];?>" class="texto" size="35"></td>
</tr>	
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Planta</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['planta'];?>" class="texto" size="35"></td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Defecto</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['defecto'];?>" class="texto" size="35"></td>
</tr>
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Divisi&oacute;n</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['division'];?>" class="texto" size="35"></td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Relacionado a</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['causa'];?>" class="texto" size="35"></td>
</tr>
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Segmento</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['segmento'];?>" class="texto" size="35"></td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Cod. Scrap</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['codigo_scrap'];?>" class="texto" size="35"></td>
</tr>	
<tr>
	<td align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Profit Center</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['profit_center'];?>" class="texto" size="35"></td>
	<td align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Orden Interna</td>
	<td><input type="text" class="texto" size="35" value="<?php echo $d_1['orden_interna'];?>" readonly="readonly"></td>	
</tr>
<tr>
	<td align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;APD</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_1['apd'];?>" class="texto" size="35"></td>
	<td align="center"><img src="imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Reason Code</td>
	<td><input type="text" class="texto" size="35" value="<?php echo $d_1['reason_code'];?>" readonly="readonly"></td>		
</tr>

<?php //-----------------------------------------------------------------------------------
	  if($d_1['financiero']=='1') { ?>
<tr><td colspan="10" background="imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="10" class="naranja" align="left">
    Campos para la causa original del código financiero seleccionado:</td></tr>
<tr>
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;&Aacute;rea</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_2['area'];?>" class="texto" size="35"></td>
	<td rowspan="3" background="imagenes/separador_1.png">&nbsp;</td>
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Tecnología</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_2['estacion'];?>" class="texto" size="35"></td>
</tr>	
<tr>	
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Línea</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_2['linea'];?>" class="texto" size="35"></td>	
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Defecto</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_2['defecto'];?>" class="texto" size="35"></td>
</tr>
<tr>
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Relacionado a</td>
	<td colspan="4"><input type="text" readonly="readonly" value="<?php echo $d_2['causa'];?>" class="texto" size="35"></td>
	<td align="center"><img src="imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Cod. SCRAP</td>
    <td><input type="text" readonly="readonly" value="<?php echo $d_2['codigo_scrap'];?>" class="texto" size="35"></td>
</tr>
<?php } //----------------------------------------------------------------------------------- ?>
<tr><td colspan="10" background="imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="6" align="center">
    <?php if($d_1['carga_masiva']=='1') { ?>
   		<a href="excel_reportes.php?op=ver_modelos&folio=<?php echo $folio;?>" class="menuLink">Archivo de modelos (carga masiva)</a>
    <?php } ?> 
    </td>
    <td background="imagenes/separador_1.png" width="10">&nbsp;</td>
    <td colspan="3" align="center">
 	<?php if($_SESSION['TYPE']=='capturista' || $_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { 
	      $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$d_1['archivo'];
		  $ruta = substr($ruta,3,strlen($ruta));		
	      if($d_1['archivo']) { ?><a href="<?php echo $ruta;?>" target="_blank" class="menuLink">Archivo de Evidencias</a><?php } } ?> 
    </td>
</tr>  
<tr><td colspan="10" background="imagenes/separador_2.png">&nbsp;</td></tr>
</table>

<?php if($d_1['carga_masiva']=='0') { ?>
<br><table align="center" class="texto" cellpadding="2" cellspacing="2" border="0">
<tr bgcolor="#CCCCCC">
	<td width="100" align="center" class="gris"><b>No.Parte</b></td>
	<td width="40" align="center" class="gris"><b>Qty.</b></td>
    <td width="70" align="center" class="gris"><b>Costo</b></td>
	<td width="40" align="center" class="gris"><b>Tipo</b></td>
	<td width="70" align="center" class="gris"><b>SubTipo</b></td>
	<td width="80" align="center" class="gris"><b>Batch ID</b></td>
	<td width="90" align="center" class="gris"><b>No.Serial</b></td>
	<td width="60" align="center" class="gris"><b>Ubicación</b></td>
	<td width="100" align="center" class="gris"><b>Parte Padre</b></td>
	<td width="100" align="center" class="gris"><b>Docto.SAP</b></td>
</tr>	
<?php $s_ = "select * from scrap_partes where no_folio='$folio'";
      $r_ = mysql_query($s_); 
   while($d_=mysql_fetch_array($r_)) { ?>
<tr bgcolor="#EEEEEE">
	<td align="center"><?php echo $d_['no_parte'];?></td>
	<td align="center"><?php echo $d_['cantidad'];?></td>
	<td align="center"><?php echo "$ ".number_format($d_['total'],2);?></td>
    <td align="center"><?php echo $d_['tipo'];?></td>
	<td align="center"><?php echo $d_['tipo_sub'];?></td>
	<td align="center"><?php echo $d_['batch_id'];?></td>
	<td align="center"><?php echo $d_['serial_unidad'];?></td>
	<td align="center"><?php echo $d_['ubicacion'];?></td>
	<td align="center"><?php echo $d_['padre'];?></td>
	<td align="center"><?php echo $d_['docto_sap'];?></td>
</tr>
<?php } ?>
</table><?php } ?>

<br><table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="imagenes/cuadro_azul.gif"></td>
	<td width="80">&nbsp;O.Mantto</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['o_mantto'];?>" class="texto" size="35"></td>	
	<td rowspan="3" background="imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td width="95">&nbsp;Supervisor</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $d_1['supervisor'];?>" readonly="readonly"></td>	
</tr>
<tr>
	<td width="20" align="center">&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Operador</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $d_1['operador'];?>" readonly="readonly"></td>		
</tr>
<tr>
	<?php if($d_1['info_1']!="" && $d_1['info_2']!="") { ?>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Info.Obligatoria</td>
	<td><input type="text" readonly="readonly" value="<?php echo $d_1['info_1'].$d_1['info_2'];?>" class="texto" size="35"></td>
	<?php } else { ?><td colspan="3">&nbsp;</td><?php } ?>
	<td width="20" align="center"><img src="imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;No.Personal</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $d_1['no_personal'];?>" readonly="readonly"></td>	
</tr>
<tr>
	<td colspan="7" background="../imagenes/separador_2.png">&nbsp;</td>
</tr>
<tr>
	<td colspan="7">
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
	<tr>
		<td align="center"><img src="imagenes/cuadro_azul.gif">&nbsp;Comentarios</td>
		<td width="10" rowspan="2">&nbsp;</td>
		<td align="center"><img src="imagenes/cuadro_azul.gif">&nbsp;Acci&oacute;n Correctiva</td>
	</tr>
	<tr>
		<td align="center">
			<textarea cols="60" rows="2" class="texto" tabindex="24" readonly="readonly"><?php echo $d_1['comentario'];?></textarea></td>
		<td align="center">
			<textarea cols="60" rows="2" class="texto" tabindex="25" readonly="readonly"><?php echo $d_1['accion_correctiva'];?></textarea></td>
	</table></td>
</tr>
</table>
</td></tr></table>
</form>
<?php } ?>