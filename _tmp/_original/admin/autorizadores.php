<?php include("../header.php"); ?>

<script>
function validar_division() {
	if(form1.depto.value=='') {
		alert('Debe seleccionar el departamento');
		form1.depto.focus(); 
		return; }
	if(form1.division.value=='') {
		alert('Debe seleccionar la división');
		form1.tipo.focus(); 
		return; }
	if(form1.prce.value=='') {
		alert('Debe seleccionar el profit center');
		form1.tipo.focus(); 
		return; }
	if(form1.proyecto.value=='') {
		alert('Debe seleccionar el proyecto');
		form1.tipo.focus(); 
		return; }
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }

form1.action='?op=add_division';
form1.submit();
}


function validar_produccion() {
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

form1.action='?op=add_produccion';
form1.submit();
}

function validar_inventarios() {
	if(form1.division.value=='') {
		alert('Debe seleccionar la división');
		form1.division.focus(); 
		return; }
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }

form1.submit();
}

function validar_planta() {
	if(form1.planta.value=='') {
		alert('Debe seleccionar la planta');
		form1.planta.focus(); 
		return; }
	if(form1.depto.value=='') {
		alert('Debe seleccionar el departamento');
		form1.depto.focus(); 
		return; }		
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }
form1.action='?op=add_planta';
form1.submit();
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
		case "por_division"		:	por_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce); break;
		case "add_division"		:	add_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce,$proyecto,$usuario);
									por_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce); break;
		case "del_division"		:	del_division($f_division,$f_prce,$f_proyecto,$f_tipo,$id_);
									por_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce); break;							
		
		case "de_produccion"	:	de_produccion($f_division,$f_area,$f_proyecto,$division,$area); break;
		case "add_produccion"	:	add_produccion($f_division,$f_area,$f_proyecto,$division,$area,$proyecto,$usuario);
									de_produccion($f_division,$f_area,$f_proyecto,$division,$area); break;
		case "del_produccion"	:	del_produccion($f_division,$f_area,$f_proyecto,$id_); 
									de_produccion($f_division,$f_area,$f_proyecto,$division,$area); break;

		case "de_inventarios"	:	de_inventarios($f_division); break;
		case "add_inventarios"	:	add_inventarios($f_division,$division,$usuario); de_inventarios($f_division); break;
		case "del_inventarios"	:	del_inventarios($f_division,$id_); de_inventarios($f_division); break;
	
		case "de_planta"		:	de_planta($f_planta,$planta,$depto,$depto_f); break;
		case "add_planta"		:	add_planta($f_planta,$planta,$depto,$usuario); de_planta($f_planta,$planta,$depto); break;
		case "del_planta"		:	del_planta($f_planta,$id_); de_planta($f_planta,$planta,$depto); break;

		default					:	por_division($f_division,$f_prce_,$f_proyecto,$f_tipo,$depto,$division,$prce); break;
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
	<td><a href="?op=por_division" class="menuLink">LPL/FFC/FFM</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=de_produccion" class="menuLink">De Producción</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=de_inventarios" class="menuLink">De Inventarios</a></td>		
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=de_planta" class="menuLink">LO/LOA/SQM</a></td>
</tr>
</table></div><hr>
<?php } 	



function de_inventarios($f_division) { ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al capturista en esta lista, podrá ver todas las divisiones.</div>

<form action="?op=add_inventarios" method="post" name="form1">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>	
	<td>División:</td>
	<td><select name="division" style="width:350px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from divisiones where activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($division==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Usuario:</td>
	<td><select name="usuario" style="width:350px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from empleados where autorizador='inv' and activo='1' order by apellidos, usuario";
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
<input type="button" value="Agregar" onclick="validar_inventarios();" class="submit">
</div>
</form>

<form action="?op=de_inventarios" method="post">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td><b><?php echo $div[$i]['nom'];?></b></td>
</tr>
<tr>
	<td>
	<table align="center" class="tabla" >
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="180" align="center">
		<select name="f_division" class="texto" style="width:180px;" onchange="submit();">
		<option value="">División</option>
		<?php $s_1 = "select * from divisiones where activo='1' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_division==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select>		
		</td>
		<td width="150" align="center">User ID</td>
		<td width="300" align="center">Empleado</td>
		<td width="40" align="center">Borrar</td>
	</tr>	
	</thead>
	<tbody>
	<?php
	   $s_ = "select empleados.usuario, empleados.nombre, empleados.apellidos, autorizadores.* from autorizadores, empleados where ";
	   $s_.= "empleados.id = autorizadores.id_emp and autorizadores.id_division like '$f_division%' and autorizadores.tipo='inv' ";
	   $s_.= "order by apellidos, nombre";
	   $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;&nbsp;<?php echo nombre_division($d_['id_division']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
			<a href="?op=del_inventarios&id_=<?php echo $d_['id'];?>"><img src="../imagenes/delete.gif" border="0"></a></td>
	</tr><?php } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }



function add_inventarios($f_division,$division,$usuario) {
if($division!='%') { 
	$s_ = "select * from autorizadores where id_emp='$usuario' and (id_division='$division' || id_division='%')"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '0', '$division', '0', '0', 'inv', '$usuario')";
		$r_1 = mysql_query($s_1);
		/*LOG SISTEMA*/log_sistema("autorizadores","nuevo",$s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa o todas las divisiones');</script>"; } }
else { 
	$s_ = "delete from autorizadores where id_emp='$usuario'";
	$r_ = mysql_query($s_);		
	$s_ = "Insert into autorizadores values('', '0', '$division', '0', '0', 'inv', '$usuario')";
	$r_ = mysql_query($s_); }
}


function del_inventarios($f_division,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
	/*LOG SISTEMA*/log_sistema("autorizadores","borrar",$s_);
}



function de_planta($f_planta,$planta,$depto,$depto_f) { 
if(!$depto_f) $depto_f = '%'; ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al capturista en esta lista, podrá ver todas las divisiones.</div>

<form action="?op=de_planta" method="post" name="form1">
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
	</select></td>
</tr>
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
<input type="button" value="Agregar" onclick="validar_planta();" class="submit">
</div>
</form>

<form action="?op=de_planta" method="post">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td><b><?php echo $div[$i]['nom'];?></b></td>
</tr>
<tr>
	<td>
	<table align="center" class="tabla" >
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
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
		</select></td>
    	<td width="150" align="center">User ID</td>
		<td width="300" align="center">Empleado</td>
        <td width="40" align="center">Borrar</td>
	</tr>	
	</thead>
	<tbody>
	<?php
	   $s_ = "select empleados.usuario, empleados.nombre, empleados.apellidos, autorizadores.* from autorizadores, empleados where ";
	   $s_.= "empleados.id = autorizadores.id_emp and autorizadores.id_area like '$f_planta%' and (autorizadores.tipo='lo' or ";
	   $s_.= "autorizadores.tipo='loa' or autorizadores.tipo='sqm') and autorizadores.tipo like '$depto_f' order by apellidos,nombre";
	   $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;&nbsp;<?php echo nombre_planta($d_['id_area']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo strtoupper($d_['tipo']);?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
			<a href="?op=del_planta&id_=<?php echo $d_['id'];?>"><img src="../imagenes/delete.gif" border="0"></a></td>
	</tr><?php } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function nombre_planta($id_) {
	$s_ = "select * from plantas where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}

function add_planta($f_planta,$planta,$depto,$usuario) {
	$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='$depto' and id_area='$planta'"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '$planta', '0', '0', '0', '$depto', '$usuario')";
		$r_1 = mysql_query($s_1);
		/*LOG SISTEMA*/log_sistema("autorizadores","nuevo",$s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa planta');</script>"; } 
}


function del_planta($f_planta,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
	/*LOG SISTEMA*/log_sistema("autorizadores","borrar",$s_);
}



function por_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce) { 
if(!$f_division) $f_division = '%'; 
if(!$f_prce)     $f_prce = '%'; 
if(!$f_proyecto) $f_proyecto = '%';
if(!$f_tipo)     $f_tipo = '%'; ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al capturista en esta lista, podrá ver todas las divisiones.</div>

<form action="?op=por_division" method="post" name="form1">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<input type="hidden" name="f_prce" value="<?php echo $f_prce;?>">
<input type="hidden" name="f_proyecto" value="<?php echo $f_proyecto;?>">
<input type="hidden" name="f_tipo" value="<?php echo $f_tipo;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td width="80">Departamento:</td>
	<td><select name="depto" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <option value="lpl" <?php if($depto=='lpl'){?> selected="selected"<?php } ?>>LPL</option>
	  <option value="ffm" <?php if($depto=='ffm'){?> selected="selected"<?php } ?>>FFM</option>
	  <option value="ffc" <?php if($depto=='ffc'){?> selected="selected"<?php } ?>>FFC</option>
	</select></td>
</tr>
<tr>	
	<td>División:</td>
	<td><select name="division" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <?php $s_ = "select * from divisiones where activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($division==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Profit Center:</td>
	<td><select name="prce" style="width:300px;" class="texto" onchange="submit();">
      <option value=""></option>
	  <option value="%" <?php if($prce=='%'){?> selected="selected"<?php } ?>>TODOS</option>
	  <?php $s_ = "select * from profit_center where id_division='$division' and activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($prce==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Proyecto:</td>
	<td><select name="proyecto" style="width:300px;" class="texto">
      <option value=""></option>
	  <option value="%">TODOS</option>
	  <?php $s_ = "select * from proyectos where id_division='$division' and id_pc like '$prce' and activo='1' order by nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($prce==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre'];?></option>
	  <?php } ?>
	</select></td>
</tr>
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
<input type="button" value="Agregar" onclick="validar_division();" class="submit">
</div>
</form>

<form action="?op=por_division" method="post">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td><b><?php echo $div[$i]['nom'];?></b></td>
</tr>
<tr>
	<td>
	<table align="center" class="tabla" >
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="120" align="center">
		<select name="f_division" class="texto" style="width:120px;" onchange="submit();">
		<option value="">División</option>
		<?php $s_1 = "select * from divisiones where activo='1' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_division==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select>		
		</td>
		<td width="120" align="center">
		<select name="f_prce" class="texto" style="width:120px;" onchange="submit();">
		<option value="">Profit Center</option>
		<?php $s_1 = "select * from profit_center where activo='1' and id_division='$f_division' order by nombre";
    	   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_prce==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select>		
		</td>
		<td width="120" align="center">
		<select name="f_proyecto" class="texto" style="width:120px;" onchange="submit();">
		<option value="">Proyecto</option>
		<?php $s_1 = "select * from proyectos where activo='1' and id_division='$f_division' and id_pc like '$f_prce' order by nombre";
		   $r_1 = mysql_query($s_1);
		   while($d_1=mysql_fetch_array($r_1)) { ?>
		   <option value="<?php echo $d_1['id'];?>" <?php if($f_proyecto==$d_1['id']){?> selected="selected"<?php } ?>>
		   <?php echo $d_1['nombre'];?></option>
		   <?php } ?>
		</select>		
		</td>		
		<td width="120" align="center">
		<select name="f_tipo" class="texto" style="width:120px;" onchange="submit();">
			<option value="">Departamento</option>
			<option value="lpl" <?php if($f_tipo=='lpl'){?> selected="selected"<?php } ?>>LPL</option>
			<option value="ffm" <?php if($f_tipo=='ffm'){?> selected="selected"<?php } ?>>FFM</option>
			<option value="ffc" <?php if($f_tipo=='ffc'){?> selected="selected"<?php } ?>>FFC</option>
		</select>		
		</td>
		<td width="150" align="center">User ID</td>
		<td width="300" align="center">Empleado</td>
		<td width="40" align="center">Borrar</td>
	</tr>	
	</thead>
	<tbody>
	<?php $ruta = "&f_division=$f_division&f_prce=$f_prce_&f_tipo=$f_tipo&f_proyecto=$f_proyecto";
	   $s_   = "select divisiones.nombre as division, empleados.usuario, empleados.nombre, empleados.apellidos, autorizadores.* from ";
	   $s_  .= "divisiones, autorizadores, empleados where autorizadores.id_division = divisiones.id and empleados.id = ";
	   $s_  .= "autorizadores.id_emp and autorizadores.id_division like '$f_division' and autorizadores.id_pc like '$f_prce' and ";
	   $s_  .= "autorizadores.id_proyecto like '$f_proyecto' and autorizadores.tipo like '$f_tipo' and autorizadores.tipo!='prod' ";
	   $s_  .= "and tipo!='inv' order by division, tipo, apellidos";
	   $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_['division'];?></td>
		<td align="left">&nbsp;<?php echo nombre_pc($d_['id_pc']);?></td>
		<td align="left">&nbsp;<?php echo nombre_proy($d_['id_proyecto']);?></td>
		<td align="left">&nbsp;<?php echo strtoupper($d_['tipo']);?></td>
		<td align="left">&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
		<?php if(tiene_registros($d_['id_emp'])<=0) { ?>
			<a href="?op=del_division&id_=<?php echo $d_['id'];?><?php echo $ruta;?>"><img src="../imagenes/delete.gif" border="0"></a><?php } else { ?><img src="../imagenes/delete_gris.gif" alt="No puede borrar este registro. Aún tiene autorizaciones pendientes" border="0"><?php } ?></td>
	</tr><?php } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function add_division($f_division,$f_prce,$f_proyecto,$f_tipo,$depto,$division,$prce,$proyecto,$usuario) {
$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='$depto' and id_division='$division' and (id_proyecto='$proyecto' ";
$s_.= "or id_proyecto='%')"; 
$r_ = mysql_query($s_);
if(mysql_num_rows($r_)<=0) { 
	$s_1 = "Insert into autorizadores values('', '0', '$division', '$prce', '$proyecto', '$depto', '$usuario')"; 
	$r_1 = mysql_query($s_1);
	/*LOG SISTEMA*/log_sistema("autorizadores","nuevo",$s_1); }
else { 
	echo "<script>alert('El usuario ya está asignado a ese o todos los proyectos de la división');</script>";	}
}


function del_division($f_division,$f_prce,$f_proyecto,$f_tipo,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
	/*LOG SISTEMA*/log_sistema("autorizadores","borrar",$s_);
}

		
function tiene_registros($id_emp) {
	$s_ = "select * from autorizaciones where id_emp='$id_emp' and status!='1'";
	$r_ = mysql_query($s_);
	return mysql_num_rows($r_);	
}		
		
	
function nombre_pc($id_pc) {
if($id_pc=='%') { return "TODOS"; } 
else {
	$s_2 = "select nombre from profit_center where id='$id_pc'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
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
}


function nombre_division($id_division) {
if($id_division=='%') { return "TODAS"; } 
else {
	$s_2 = "select nombre from divisiones where id='$id_division'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
}


function de_produccion($f_division,$f_area,$f_proyecto,$division,$area) { ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al capturista en esta lista, podrá ver todas las divisiones.</div>
<form action="?op=de_produccion" method="post" name="form1">
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
<input type="button" value="Agregar" onclick="validar_produccion();" class="submit">
</div>
</form>

<form action="?op=de_produccion" method="post">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td><b><?php echo $div[$i]['nom'];?></b></td>
</tr>
<tr>
	<td>
	<table align="center" class="tabla" >
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
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
		<td width="120" align="center">
		<select name="f_proyecto" class="texto" style="width:120px;" onchange="submit();">
		<option value="">Proyecto</option>
		<?php $s_1 = "select * from proyectos where activo='1' and id_division='$f_division' order by nombre";
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
	<?php $ruta = "&f_division=$f_division&f_area=$f_area";
	   $s_   = "select divisiones.nombre as division, empleados.usuario, empleados.nombre, empleados.apellidos, autorizadores.* from ";
	   $s_	.= "divisiones, autorizadores, empleados where autorizadores.id_division = divisiones.id and empleados.id = ";
	   $s_  .= "autorizadores.id_emp and autorizadores.id_division like '$f_division%' and autorizadores.id_area like '$f_area%' and ";
	   $s_  .= "autorizadores.id_proyecto like '$f_proyecto%' and autorizadores.tipo='prod' order by division, tipo, apellidos";
	   $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;&nbsp;<?php echo $d_['division'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo nombre_area($d_['id_area']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo nombre_proy($d_['id_proyecto']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
		<?php if(tiene_registros($d_['id_emp'])<=0) { ?>
			<a href="?op=del_produccion&id_=<?php echo $d_['id'];?><?php echo $ruta;?>"><img src="../imagenes/delete.gif" border="0"></a><?php } else { ?><img src="../imagenes/delete_gris.gif" alt="No puede borrar este registro. Aún tiene autorizaciones pendientes" border="0"><?php } ?></td>
	</tr><?php } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function add_produccion($f_division,$f_area,$f_proyecto,$division,$area,$proyecto,$usuario) {
if($area!='%') { 
	$s_ = "select * from autorizadores where id_emp='$usuario' and tipo='prod' and id_division='$division' and id_area='$area' and ";
	$s_.= "(id_proyecto='$proyecto' or id_proyecto='%')"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '$area', '$division', '0', '$proyecto', 'prod', '$usuario')";
		$r_1 = mysql_query($s_1); 
		/*LOG SISTEMA*/log_sistema("autorizadores","nuevo",$s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado a esa división, área y proyecto');</script>";	}
} else {
	$s_ = "Insert into autorizadores values('', '$area', '$division', '0', '$proyecto', 'prod', '$usuario')";
	$r_ = mysql_query($s_); }			
}


function del_produccion($f_division,$f_area,$f_proyecto,$id_) {
	$s_ = "delete from autorizadores where id='$id_'";
	$r_ = mysql_query($s_);
	/*LOG SISTEMA*/log_sistema("autorizadores","borrar",$s_);
}


function nombre_usuario($id_emp) {
	$s_2 = "select usuario from empleados where id='$id_emp'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['usuario'];
}

?>