<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.proyecto.value=='') {
		alert('Debe seleccionar el proyecto');
		form1.proyecto.focus(); 
		return; }
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }

form1.submit();
}

function exportar() {
	form2.action='excel.php?op=aut_lpl2';
	form2.submit();	
	form2.action='aut_lpl2.php?op=listado';
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
		case "listado"	:	listado($f_proyecto,$pagina); break;
		case "guardar"	:	guardar($f_proyecto,$proyecto,$usuario); listado($f_tipo,$f_proyecto,$pagina); break;
		case "borrar"	:	borrar($f_proyecto,$id_); listado($f_tipo,$f_proyecto,$pagina); break;
		default			:	listado($f_proyecto,$pagina); break;
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
	<td><a href="aut_loa.php" class="menuLink">LO/LOA/SQM/Finanzas</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_esp.php" class="menuLink">Aprobación Especial</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="aut_lpl2.php" class="naranja2">LPL Automático</a></td>
</tr>
</table></div><hr>
<?php } 		



function listado($f_proyecto,$pagina) {

	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; }

if(!$pagina) $pagina = '1'; ?>
<div align="center" class="aviso">Los filtros son únicamente con fines de restricción, no de permisos. Si no agrega al autorizador en esta lista, no podrá autorizar scrap.</div><br>

<?php if($admin=='2') { ?>
<form action="?op=guardar" method="post" name="form1">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>	
	<td>Proyecto:</td>
	<td><select name="proyecto" style="width:350px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select divisiones.nombre as division, proyectos.* from proyectos, divisiones where divisiones.activo='1' and proyectos.activo='1' and divisiones.id = ";
	        $s_.= "proyectos.id_division order by proyectos.nombre";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($proyecto==$d_['id']){?> selected="selected"<?php } ?>><?php echo $d_['nombre']." (".$d_['division'].")"; ?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>		
	<td>Usuario:</td>
	<td><select name="usuario" style="width:350px;" class="texto">
      <option value=""></option>
	  <?php $s_ = "select * from empleados where autorizador='lpl' and activo='1' order by apellidos, usuario";
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
	$s_.= "autorizadores.id_emp = empleados.id and autorizadores.tipo = 'lpl_auto' ";
	if($f_proyecto!='') { $s_.= "and id_proyecto like '$f_proyecto' "; }
	$s_1.= " order by tipo, apellidos";
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
			echo "<a href='?op=listado&f_division=$f_division&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>

<form action="?op=listado" method="post" name="form2">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td>
	<table align="center" class="tabla">
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="40" align="center">No.</td>
        <td width="180" align="center">
        <select name="f_proyecto" style="width:250px;" class="texto" onchange="submit();">
          <option value="">Proyecto</option>
		  <?php $s_1 = "select divisiones.nombre as division, proyectos.* from proyectos, divisiones where divisiones.activo='1' and proyectos.activo='1' and ";
                $s_1.= "divisiones.id = proyectos.id_division order by proyectos.nombre";
             $r_1 = mysql_query($s_1);
             while($d_1=mysql_fetch_array($r_1)) { ?>
          <option value="<?php echo $d_1['id'];?>" <?php if($f_proyecto==$d_1['id']){?> selected="selected"<?php } ?>>
		  	<?php echo $d_1['nombre']." (".$d_1['division'].")"; ?></option>
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
        <td align="left">&nbsp;&nbsp;<?php echo nombre_proyecto($d_['id_proyecto']);?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo trim($d_['apellidos']." ".$d_['nombre']);?></td>
		<td align="center">
        	<?php if($admin=='2') { ?>
			<a href="?op=borrar&id_=<?php echo $d_['id'];?>"><img src="../imagenes/delete.gif" border="0"></a>
            <?php } else { ?><img src="../imagenes/delete_gris.gif" border="0"><?php } ?></td>
	</tr><?php $i++; } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function nombre_proyecto($id_) {
if($id_!='%') { 
	$s_ = "select divisiones.nombre as division, proyectos.nombre from divisiones, proyectos where divisiones.id = proyectos.id_division and proyectos.activo='1' and ";
	$s_.= "proyectos.activo='1' and proyectos.id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre']." (".$d_['division'].")"; }
else { return "TODOS"; } 	
}


function guardar($f_proyecto,$proyecto,$usuario) {
	$s_ = "select * from autorizadores where id_proyecto='$proyecto' and tipo='lpl_auto'"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into autorizadores values('', '0', '0', '0', '$proyecto', 'lpl_auto', '$usuario')";
		$r_1 = mysql_query($s_1); }
	else { 
		echo "<script>alert('El proyecto ya ha sido asignado a un autorizador');</script>"; }
}


function borrar($f_proyecto,$id_) {
	$s_ = "delete from autorizadores where id='$id_'"; 
	$r_ = mysql_query($s_);
} ?>