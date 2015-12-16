<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el nombre');
		form1.nombre.focus(); return; }
	if(form1.ubicacion.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.ubicacion.value='';
		alert('Es necesario ingresar la ubicación');
		form1.ubicacion.focus(); return; }	
	if(form1.jefe.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.jefe.value='';
		alert('Es necesario seleccionar al jefe de la planta');
		form1.jefe.focus(); return; }	
	form1.submit();	
}

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_plantas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_plantas','plantas'); ?></td>
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
	<?php menu_interno();
	switch($op) {
		case "nuevo"	:	nuevo(); break;
		case "guardar"	:	guardar($nombre, $ubicacion, $jefe); nuevo(); break;
	
		case "listado"	:	listado($orden,$pagina); break;
		case "estado"	:	estado($id_,$estado,$nombre); listado($orden,$pagina); break;
		case "editar"	:	editar($id_); break;
		case "update"	:	update($id_,$nombre,$ubicacion,$jefe); listado($orden,$pagina); break;
		
		case "borrar"	:	borrar($id_,$nombre); listado($orden,$pagina);	 break;		
		default			:	listado($orden,$pagina); break;
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="150">PLANTAS</td>
	<td><a href="?op=nuevo" class="menuLink">Nueva</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function nuevo() { ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=guardar" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nueva Planta</caption>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45">
	</td>
</tr>
<tr>
	<td valign="top">Ubicación:</td>
	<td><input type="text" name="ubicacion" class="texto" size="45">
	</td>
</tr>
<tr>
	<td valign="top">Jefe:</td>
	<td>
	<select name="jefe" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_1 = "select id, nombre, apellidos from empleados where activo='1' and nombre!='' and apellidos!='' order by apellidos";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>"><?php echo $d_1['apellidos']." ".$d_1['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function guardar($nombre,$ubicacion,$jefe) {
	$nombre = trim($nombre);
	$ubicacion = trim($ubicacion);
	$jefe = trim($jefe);
	$existe = ver_si_existe($nombre);	
	if($existe=='NO') {
		$s_1 = "insert into plantas values('','$nombre','$ubicacion','$jefe','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($nombre) {
	$s_1 = "select * from plantas where nombre='$nombre' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($orden,$pagina) {
   if(!$pagina) $pagina = '1';
   if(!$orden)  $orden  = 'nombre'; ?>
   <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div><br>  

   <?php
	$s_1 = "select * from plantas where activo!='2' order by activo DESC, $orden ASC";
    $r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1);
	$pags = ceil($n_1/50);
    $ini_ = ($pagina-1)*50;
    $fin_ = 50; $i=1;

	if($pags>0) {      
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='texto'>";
		echo "<tr height='25'>";
			echo "<td width='120' align='center' bgcolor='#D8D8D8' style='border:#CCCCCB solid 1px;'>$n_1&nbsp;Registros</td>";
			echo "<td width='3'></td>";		
			while($i<=$pags) {
			if($pagina==$i) { $bg_img = '../imagenes/pag_on.jpg'; } else { $bg_img = '../imagenes/pag_off.jpg'; }
			echo "<td width='25' align='center' background='$bg_img' style='border:#CCCCCB solid 1px;'>";
			echo "<a href='?op=listado&orden=$orden&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>

<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
        <td width="50" align="center">Estado</td>
		<td width="150" align="center">Nombre</td>
		<td width="150" align="center">Ubicación</td>
        <td width="250" align="center">Jefe</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
	<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
	      $r_1 = mysql_query($s_1); 
	while($d_1=mysql_fetch_array($r_1)) { 
	$ruta = "&orden=$orden&nombre=$d_1[nombre]"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
	<?php /*if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'><img src='../imagenes/tick.png' alt='Activo' border='0'>";     } 
	      if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'><img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; }*/ ?>
    <img src='../imagenes/tick_gris.png' alt='No se pude desactivar, consulte al administrador' border='0'>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['ubicacion'];?></td>
    <td>&nbsp;&nbsp;<?php echo get_jefe_name($d_1['jefe']);?></td> 
	<td align="center">
		<!--<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>">
        	<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a>-->
    	<img src="../imagenes/pencil_gris.gif" alt="No se puede editar, consulte al administrador" border="0">
	</td>
	<td align="center">
		<!--<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>-->
        <img src="../imagenes/delete_gris.gif" alt="No se puede borrar, consulte al administrador" border="0">
	</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function get_jefe_name($id_) {
	$s_ = "select nombre, apellidos from empleados where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['apellidos']." ".$d_['nombre'];
} 


function editar($id_) { 
	$s_1 = "Select * from plantas where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1);
	if(ver_si_tiene_pendientes($id_)=='SI') { $dis = 'disabled'; } else { $dis = ''; } ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=update" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Planta</caption>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45" value="<?php echo $d_1['nombre'];?>" <?php echo $dis;?>>
	</td>
</tr>
<tr>
	<td valign="top">Ubicación:</td>
	<td><input type="text" name="ubicacion" class="texto" size="45" value="<?php echo $d_1['ubicacion'];?>" <?php echo $dis;?>>
	</td>
</tr>
<tr>
	<td valign="top">Jefe:</td>
	<td>
	<select name="jefe" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_2 = "select id, nombre, apellidos from empleados where activo='1' and nombre!='' and apellidos!='' order by apellidos";
	   	  $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($d_1['jefe']==$d_2['id']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_2['apellidos']." ".$d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function update($id_,$nombre,$ubicacion,$jefe) {
	$nombre = trim($nombre);
	$ubicacion = trim($ubicacion);
	if($nombre!='' && $ubicacion!='') { 	
		$s_1 = "update plantas set nombre='$nombre', ubicacion='$ubicacion', jefe='$jefe' where id='$id_'"; }
	else { 
		$s_1 = "update plantas set jefe='$jefe' where id='$id_'"; }	
	$r_1 = mysql_query($s_1); 
}	


function borrar($id_,$nombre) {
	$s_1 = "update plantas set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	


function estado($id_,$estado,$nombre) {
	$s_1 = "update plantas set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); }
?>