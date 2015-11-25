<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.planta.value=='') {
		alert('Debe seleccionar la planta');
		form1.planta.focus(); 
		return; }
	if(form1.depto.value=='') {
		alert('Debe seleccionar el departamento');
		form1.depto.focus(); 
		return; }		
	if(form1.depto.value=='sqm' || form1.depto.value=='fin') {
	if(form1.division.value=='') {
		alert('Debe seleccionar la división');
		form1.division.focus(); 
		return; } }	
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }
form1.action='?op=guardar';
form1.submit();
}

function exportar() {
	form2.action='excel.php?op=aut_loa';
	form2.submit();	
	form2.action='aut_loa.php?op=listado';
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
		case "listado"		:	listado($f_planta,$planta,$depto,$division,$depto_f,$division_f,$pagina); break;
		case "guardar"		:	guardar($f_planta,$division_f,$planta,$depto,$division,$usuario); listado($f_planta,$planta,$depto,$division,$depto_f,$division_f,$pagina);
							 	break;
		case "borrar"		:	borrar($f_planta,$id_); listado($f_planta,$planta,$depto,$division,$depto_f,$division_f,$pagina); break;
		default				:	listado($f_planta,$planta,$depto,$division,$depto_f,$division_f,$pagina); break;
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
	<td><a href="aut_prod.php" class="menuLink">De Producción</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_inv.php" class="menuLink">De Inventarios</a></td>		
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_loa.php" class="naranja2">LO/LOA/SQM/Finanzas</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_esp.php" class="menuLink">Aprobación Especial</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_lpl2.php" class="menuLink">LPL Automático</a></td>
</tr>
</table></div><hr>
<?php } 	



function listado($f_planta,$planta,$depto,$division,$depto_f,$division_f,$pagina) { 
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; }

 $ruta = "&f_planta=$f_planta&depto_f=$depto_f";
if(!$pagina)   $pagina = '1';
if(!$depto_f)  $depto_f  = '%';
if(!$division_f)  $division_f  = '%';
if(!$f_planta) $f_planta = '%'; ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al autorizador en esta lista, no podrá autorizar scrap. <br>Seleccione un departamento y una planta para ver el listado.</div><br>

<?php if($admin=='2') { ?>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="f_planta" value="<?php echo $f_planta;?>">
<input type="hidden" name="depto_f" value="<?php echo $depto_f;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>	
	<td>Planta:</td>
	<td><select name="planta" style="width:300px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from plantas where activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($planta==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>
	<td width="80">Departamento:</td>
	<td><select name="depto" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <option value="lo" <?php if($depto=='lo'){?> selected="selected"<?php } ?>>LO</option>
	  <option value="loa" <?php if($depto=='loa'){?> selected="selected"<?php } ?>>LO Almacén</option>
	  <option value="sqm" <?php if($depto=='sqm'){?> selected="selected"<?php } ?>>SQM</option>
      <option value="fin" <?php if($depto=='fin'){?> selected="selected"<?php } ?>>Finanzas</option>
	</select></td>
</tr>
<?php if($depto=='sqm' || $depto=='fin') {
	$s_1 = "select * from divisiones where activo='1' order by nombre";
	$r_1 = mysql_query($s_1); ?>
<tr>
	<td width="80">División:</td>
	<td><select name="division" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	  <option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>
</tr><?php } ?>
<tr>		
	<td>Usuario:</td>
	<td><select name="usuario" style="width:300px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from empleados where autorizador='$depto' and activo='1' order by apellidos, usuario";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($usuario==$d_['id']){?> selected="selected"<?php } ?>>
	  	<?php if(trim($d_['apellidos'])!='') { echo $d_['apellidos']." ".$d_['nombre']; } else { echo $d_['usuario']; } ?></option>
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
    $s_ = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario from autorizadores, empleados where ";
	$s_.= "autorizadores.id_emp = empleados.id and id_area like '$f_planta' and (tipo='lo' or tipo='loa' or tipo='sqm' or tipo='fin') and ";
	$s_.= "tipo like '$depto_f' and id_division like '$division_f' order by tipo, apellidos"; 
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
        <td width="180" align="center">
		<select name="f_planta" class="texto" style="width:180px;" onchange="submit();">
		<option value="">Planta</option>
		<?php $s_1 = "select * from plantas where activo='1' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_planta==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select>		
		</td>
		<td><select name="depto_f" style="width:120px;" class="texto" onchange="submit();">
     	 <option value="">Departamento</option>
	 	 <option value="lo" <?php if($depto_f=='lo'){?> selected="selected"<?php } ?>>LO</option>
	 	 <option value="loa" <?php if($depto_f=='loa'){?> selected="selected"<?php } ?>>LOA</option>
         <option value="sqm" <?php if($depto_f=='sqm'){?> selected="selected"<?php } ?>>SQM</option>
         <option value="fin" <?php if($depto_f=='fin'){?> selected="selected"<?php } ?>>Finanzas</option>
		</select></td>
		<?php $s_1 = "select * from divisiones where activo='1' order by nombre"; 
		      $r_1 = mysql_query($s_1); ?>
		<td><select name="division_f" style="width:150px;" class="texto" onchange="submit();">
     	 <option value="">División</option>
		 <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	 	 <option value="<?php echo $d_1['id'];?>" <?php if($division_f==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option><?php } ?>
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
        <td align="left">&nbsp;&nbsp;<?php echo nombre_planta($d_['id_area']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo strtoupper($d_['tipo']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo nombre_division($d_['id_division']);?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
			<?php if($admin=='2') {?>
            <a href="?op=borrar&id_=<?php echo $d_['id'];?>"><img src="../imagenes/delete.gif" border="0"></a><?php } else { ?>
            <img src="../imagenes/delete_gris.gif" border="0"><?php } ?></td>
	</tr><?php } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function nombre_division($id_) {
	if($id_!='0') { 
		$s_ = "select * from divisiones where id='$id_'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']; }
	else { return "NA"; } 	
}

function nombre_planta($id_) {
	$s_ = "select * from plantas where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}

function guardar($f_planta,$division_f,$planta,$depto,$division,$usuario) {
	if($depto=='lo' || $depto=='loa') { 
	$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='$depto' and id_area='$planta'"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '$planta', '0', '0', '0', '$depto', '$usuario')"; 
		$r_1 = mysql_query($s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa planta');</script>"; } 
	}
	
	if($depto=='sqm' || $depto=='fin') { 
	$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='$depto' and id_area='$planta' and id_division='$division'"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '$planta', '$division', '0', '0', '$depto', '$usuario')"; 
		$r_1 = mysql_query($s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa división');</script>"; } 
	}	
}


function borrar($f_planta,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
} ?>