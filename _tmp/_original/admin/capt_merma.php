<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.usuario.value=='') {
		alert('Debe seleccionar el usuario');
		form1.usuario.focus(); 
		return; }

form1.action='?op=agregar';
form1.submit();
}

function exportar() {
	form2.action='excel.php?op=capt_merma';
	form2.submit();	
	form2.action='capt_merma.php?op=listado';
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_usuarios'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_usuarios','cap_merma'); ?></td>
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
		case "listado"			:	listado($pagina); break;
		case "agregar"			:	agregar($usuario); listado($pagina); break;
		case "eliminar"			:	eliminar($id_); listado($pagina); break;
		default					:	listado($pagina); break;
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
	<td class="titulo" width="250">CAPTURISTAS MERMA</td>
</tr>
</table></div><hr>
<?php } 	


function listado($pagina) { 
//Revisar si es administrador o super administrador del sistema
$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
$r_ = mysql_query($s_); 
$d_ = mysql_fetch_array($r_);
if($d_['super_admin']=='1')   { $admin = 2; }
if($d_['administrador']=='1') { $admin = 1; } 

if(!$pagina) $pagina = '1'; ?>
<div align="center" class="aviso">Estos usuarios al momento de capturar un folio con merma que sólo pedirá autorización de inventarios.</div><br>

<?php if($admin=='2') { ?>
    <form action="?op=listado" method="post" name="form1">
    <table align="center" class="tabla" cellpadding="0" cellspacing="5">
        <tr>		
            <td valign="top">Usuario:</td>
            <td>
            <select name="usuario" style="width:350px;" class="texto">
              <option value=""></option>
              <?php $s_ = "select * from empleados where capturista='1' and activo='1' order by apellidos, usuario";
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
	$s_ = "select empleados.usuario, empleados.nombre, empleados.apellidos, capt_merma.* from capt_merma, empleados ";
	$s_.= "where empleados.id = capt_merma.id_emp and empleados.capturista='1' order by apellidos, nombre";
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

<form action="?op=por_division" method="post" name="form2">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
<tr>
	<td>
	<table align="center" class="tabla">
	<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="40" align="center">No.</td>
		<td width="150" align="center">User ID</td>
		<td width="400" align="center">Empleado</td>
		<td width="40" align="center">Borrar</td>
	</tr>	
	</thead>
	<tbody>
	<?php $s_.= " limit $ini_,$fin_"; $i=$ini_+1;
	      $r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $i;?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['usuario'];?></td>
		<td align="left">&nbsp;&nbsp;<?php echo $d_['apellidos']." ".$d_['nombre'];?></td>
		<td align="center">
			<?php if($admin=='2') { ?>
            <a href="?op=eliminar&id_=<?php echo $d_['id'];?>"><img src="../imagenes/delete.gif" border="0"></a><?php } else { ?>
            <img src="../imagenes/delete_gris.gif" border="0"><?php } ?></td>
	</tr><?php $i++; } ?>	
	</tbody>	
	</table>
	</td>
</tr>		
</table><br>
<?php echo "<br><br><br>"; }


function agregar($usuario) {
	$s_ = "select * from capt_merma where id_emp='$usuario'"; 
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		$s_1 = "Insert into capt_merma values('', '$usuario')";
		$r_1 = mysql_query($s_1); }
	else { 
		echo "<script>alert('El usuario seleccionado ya está asignado');</script>"; }
}


function eliminar($id_) {
	$s_ = "delete from capt_merma where id='$id_'";
	$r_ = mysql_query($s_);
}?>