<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.division.value=='') {
		alert('Debe seleccionar la división');
		form1.division.focus(); 
		return; }
	if(form1.area.value=='') {
		alert('Debe seleccionar el área');
		form1.area.focus(); 
		return; }		
	if(form1.proyecto.value=='') {
		alert('Debe seleccionar el proyecto');
		form1.proyecto.focus(); 
		return; }
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }

form1.action='?op=guardar';
form1.submit();
}

function exportar() {
	form2.action='excel.php?op=aut_prod';
	form2.submit();	
	form2.action='aut_prod.php?op=listado';
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_usuarios'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_usuarios','autorizadores'); ?></td>
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
		case "listado"	:	listado($f_division,$f_area,$f_proyecto,$division,$area,$pagina); break;
		case "guardar"	:	guardar($f_division,$f_area,$f_proyecto,$division,$area,$proyecto,$usuario);
							listado($f_division,$f_area,$f_proyecto,$division,$area,$pagina); break;
		case "borrar"	:	borrar($f_division,$f_area,$f_proyecto,$id_); 
							listado($f_division,$f_area,$f_proyecto,$division,$area,$pagina); break;
		default			:	listado($f_division,$f_area,$f_proyecto,$division,$area,$pagina); break;
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
	<td class="titulo" width="200">AUTORIZADORES</td>
	<td><a href="aut_lpl.php" class="menuLink">LPL/FFC/FFM</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_prod.php" class="naranja2">De Producción</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_inv.php" class="menuLink">De Inventarios</a></td>		
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_loa.php" class="menuLink">LO/LOA/SQM/Finanzas</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_esp.php" class="menuLink">Aprobación Especial</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_lpl2.php" class="menuLink">LPL Automático</a></td>    
</tr>
</table></div><hr>
<?php } 		

		

function listado($f_division,$f_area,$f_proyecto,$division,$area,$pagina) {
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; } 

$ruta = "&f_division=$f_division&f_area=$f_area&f_proyecto=$f_proyecto";
if(!$pagina) 	 $pagina	 = '1'; 
if(!$f_division) $f_division = '%'; 
if(!$f_area)     $f_area     = '%'; 
if(!$f_proyecto) $f_proyecto = '%'; ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al autorizador en esta lista, no podrá autorizar scrap.</div><br>

<?php if($admin=='2'){ ?>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<input type="hidden" name="f_area" value="<?php echo $f_area;?>">
<input type="hidden" name="f_proyecto" value="<?php echo $f_proyecto;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>	
	<td width="60">División:</td>
	<td><select name="division" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <?php $s_ = "select * from divisiones where activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($division==$d_['id']){?> selected="selected"<?php } ?>>
	  <?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Área:</td>
	<td><select name="area" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <option value="%" <?php if($area=="%"){?> selected="selected"<?php } ?>>TODAS</option> 
	  <?php $s_ = "select * from areas where activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($area==$d_['id']){?> selected="selected"<?php } ?>>
	  <?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Proyecto:</td>
	<td><select name="proyecto" style="width:300px;" class="texto">
      <option value=""></option>
	  <option value="%" <?php if($proyecto=="%"){?> selected="selected"<?php } ?>>TODOS</option>  
	  <?php $s_ = "select * from proyectos where id_division='$division' and activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($proyecto==$d_['id']){?> selected="selected"<?php } ?>>
	  <?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Usuario:</td>
	<td><select name="usuario" style="width:300px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from empleados where autorizador='prod' and activo='1' order by apellidos, usuario";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($usuario==$d_['id']){?> selected="selected"<?php } ?>>
	  	<?php if($d_['apellidos']!='') { echo $d_['apellidos']." ".$d_['nombre']; } else { echo $d_['usuario']; } ?></option>
	  <?php } ?>
	</select></td>
</tr>
</table>
</div>
<br><div align="center">
	<input type="button" value="Agregar" onclick="validar();" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" class="submit" value="Exportar" onclick="exportar();">
</div>
</form>

<?php }
    $s_ = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario, divisiones.nombre as division from autorizadores, empleados, divisiones where ";
	$s_.= "autorizadores.id_emp = empleados.id and autorizadores.id_division = divisiones.id and id_division like '$f_division' and id_area like '$f_area' and id_proyecto ";
	$s_.= "like '$f_proyecto' and tipo='prod' order by division, tipo, apellidos"; 
	$r_ = mysql_query($s_); 
	$n_ = mysql_num_rows($r_);
	$pags = ceil($n_/50);
    $ini_ = ($pagina-1)*50;
    $fin_ = 50; $i=1;

	if($pags>0) {      
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='texto'>";
		echo "<tr height='25'>";
			echo "<td width='120' align='center' bgcolor='#D8D8D8' style='border:#CCCCCB solid 1px;'>$n_&nbsp;Registros</td>";
			echo "<td width='3'></td>";		
			while($i<=$pags) {
			if($pagina==$i) { $bg_img = '../imagenes/pag_on.jpg'; } else { $bg_img = '../imagenes/pag_off.jpg'; }
			echo "<td width='25' align='center' background='$bg_img' style='border:#CCCCCB solid 1px;'>";
			echo "<a href='?op=listado$ruta&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>

<form action="?op=listado" method="post" name="form2">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td><b><?php echo $div[$i]['nom'];?></b></td>
</tr>
<tr>
	<td>
	<table align="center" class="tabla" >
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="40" align="center">No.</td>
        <td width="120" align="center">
		<select name="f_division" class="texto" style="width:120px;" onchange="submit();">
		<option value="">División</option>
		<?php $s_1 = "select * from divisiones where activo='1' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_division==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select></td>
		<td width="200" align="center">
		<select name="f_area" class="texto" style="width:200px;" onchange="submit();">
		<option value="">Área</option>
		<?php $s_1 = "select * from areas where activo='1' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_area==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select></td>
		<td width="170" align="center">
		<select name="f_proyecto" class="texto" style="width:170px;" onchange="submit();">
		<option value="">Proyecto</option>
		<?php $s_1 = "select * from proyectos where activo='1' and id_division like '$f_division' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_proyecto==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select></td>		
		<td width="150" align="center">User ID</td>
		<td width="300" align="center">Empleado</td>
		<td width="40" align="center">Borrar</td>
	</tr>	
	</thead>
	<tbody>
	<?php $s_.= " limit $ini_,$fin_"; $i=$ini_+1;
	      $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $i;?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_['division'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo nombre_area($d_['id_area']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo nombre_proy($d_['id_proyecto']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
			<?php if($admin=='2') { ?>
            <a href="?op=borrar&id_=<?php echo $d_['id'];?><?php echo $ruta;?>"><img src="../imagenes/delete.gif" border="0"></a><?php } else { ?>
            <img src="../imagenes/delete_gris.gif" border="0"><?php } ?></td>
	</tr><?php $i++; } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function guardar($f_division,$f_area,$f_proyecto,$division,$area,$proyecto,$usuario) {
if($area!='%') { 
	$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='prod' and id_division='$division' and id_area='$area' and ";
	$s_.= "(id_proyecto='$proyecto' or id_proyecto='%')"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '$area', '$division', '0', '$proyecto', 'prod', '$usuario')";
		$r_1 = mysql_query($s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa división, área y proyecto');</script>";	}
} else {
	$s_ = "Insert into autorizadores values('', '$area', '$division', '0', '$proyecto', 'prod', '$usuario')";
	$r_ = mysql_query($s_); }			
}


function borrar($f_division,$f_area,$f_proyecto,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
}

function nombre_proy($id_proy) {
if($id_proy=='%') { return "TODOS"; } 
else {
	$s_2 = "select nombre from proyectos where id='$id_proy'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
}


function nombre_area($id_area) {
if($id_area=='%') { return "TODAS"; } 
else {
	$s_2 = "select nombre from areas where id='$id_area'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
} ?>