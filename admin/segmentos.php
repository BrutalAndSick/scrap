<?php include("../header.php"); ?>

<script>
function validar(tipo) {
	if(form1.planta.value=='') {
		alert('Es necesario seleccionar una planta');
		form1.planta.focus(); return; }	
	if(form1.division.value=='') {
		alert('Es necesario seleccionar una división');
		form1.division.focus(); return; }	
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el nombre');
		form1.nombre.focus(); return; }
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update'; }
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
    <td><?php submenu('b_plantas','segmentos'); ?></td>
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
		case "nuevo"	:	nuevo($planta,$division,$ceco); break;
		case "guardar"	:	guardar($planta, $division, $nombre); nuevo($planta,$division,$ceco); break;
		case "listado"	:	listado($f_planta,$f_division,$orden,$pagina); break;
		case "estado"	:	estado($id_,$estado,$nombre,$orden,$f_division); listado($f_planta,$f_division,$orden,$pagina); break;

		case "editar"	:	editar($id_,$planta,$division,$orden,$f_division); break;
		case "update"	:	update($id_,$planta,$division,$nombre,$orden,$f_planta,$f_division); 
							listado($f_planta,$f_division,$orden,$pagina); break;
	
		case "borrar"	:	borrar($id_,$nombre,$orden,$f_planta,$f_division); listado($f_planta,$f_division,$orden,$pagina);	 break;		
		default			:	listado($f_planta,$f_division,$orden,$pagina); break;
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
	<td class="titulo" width="150">SEGMENTOS</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function nuevo($planta,$division) { ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Segmento</caption>
<tr>
	<td valign="top">Planta:</td>
	<td>
	<select name="planta" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_1 = "select * from plantas where activo='1' order by nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>" <?php if($planta==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">División:</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_2 = "select * from divisiones where activo='1' and id_planta='$planta' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(1);" class="submit"></div>
</form>
<?php  } 


function guardar($planta, $division, $nombre) {
	$planta = trim($planta);
	$division = trim($division);
	$nombre = trim($nombre);

	$existe = ver_si_existe($division,$nombre);
	if($existe=='NO') {
		$s_1 = "insert into segmentos values('','$planta','$division','$nombre','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($division,$nombre) {
	$s_1 = "select * from segmentos where nombre='$nombre' and id_division='$division' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($f_planta,$f_division,$orden,$pagina) {
	if(!$pagina)        $pagina	    = '1';
	if(!$f_planta)  	$f_planta   = '%';
	if(!$f_division)  	$f_division = '%'; ?>
   <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar y filtrar los campos</div><br>
	
   <?php
   $s_1 = "select plantas.nombre as planta, divisiones.nombre as division, segmentos.* from segmentos, divisiones, plantas where segmentos.activo!='2' and ";
   $s_1.= "plantas.activo='1' and divisiones.activo='1' and plantas.activo='1' and plantas.id = segmentos.id_planta and divisiones.id = segmentos.id_division and ";
   $s_1.= "segmentos.id_division like '$f_division' and segmentos.id_planta like '$f_planta' order by activo desc, planta, division, nombre asc";
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
			echo "<a href='?op=listado&orden=$orden&f_planta=$f_planta&f_division=$f_division&pagina=$i' class='link_paginas'>$i</a></td>";
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
		<td width="200" align="center">Planta</td>
		<td width="200" align="center">
	<select name="f_division" style="width:200px;" class="texto" onchange="submit();">
		<option value="">División</option>
		<?php $s_3 = "select divisiones.* from divisiones, plantas where divisiones.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id ";
		      $s_3.= "and divisiones.id_planta like '$f_planta' order by divisiones.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="250" align="center">Nombre</th>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
	<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
	      $r_1 = mysql_query($s_1); 
	  while($d_1=mysql_fetch_array($r_1)) { 
      $ruta = "&orden=$orden&nombre=$d_1[nombre]&f_division=$f_division&f_planta=$f_planta"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>">
			<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>		
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function ver_si_tiene_pendientes($segmento) {
	$s_1 = "select * from scrap_folios where id_segmento='$segmento' and status='0'";
	$r_1 = mysql_query($s_1);
	$c_1 = mysql_num_rows($r_1);

	if($c_1>0)   { return "SI"; }
	if($c_1<=0)  { return "NO"; }
}


function editar($id_,$planta,$division,$orden,$f_planta,$f_division) { 
	$s_1 = "Select * from segmentos where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1);
	if(!$planta) $planta=$d_1['id_planta'];
	if(!$division) $division=$d_1['id_division']; ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="f_planta" value="<?php echo $f_planta;?>">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Segmento</caption>
<tr>
	<td valign="top">Planta:</td>
	<td>
	<select name="planta" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_4 = "select * from plantas where activo='1' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($planta==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">División:</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_2 = "select * from divisiones where activo='1' and id_planta='$planta' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45" value="<?php echo $d_1['nombre'];?>">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
</form>
<?php  } 



function update($id_,$planta,$division,$nombre,$orden,$f_planta,$f_division) {
	$planta = trim($planta);
	$division = trim($division);
	$nombre = trim($nombre);

	$s_1 = "update segmentos set id_planta='$planta', id_division='$division', nombre='$nombre' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	



function borrar($id_,$nombre,$orden,$f_planta,$f_division) {
	$s_1 = "update segmentos set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$s_2 = "delete from oi_especial where id_segmento='$id_'";
	$r_2 = mysql_query($s_2); 	
	require_once("mantto.php"); mantto('');
}	



function estado($id_,$estado,$nombre,$orden,$f_planta,$f_division) {
	$s_1 = "update segmentos set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1);  }
?>