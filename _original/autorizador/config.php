<?php include("../header.php"); ?>

<script>
function guardar() {
	form1.action='?op=guardar';
	form1.submit();
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu(''); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('',''); ?></td>
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
			case "delegar"			:	delegar(); break;
			case "save_delegado"	:	save_delegado($ausencia); delegar(); break;
			default					:	delegar(); break;
		} ?>			
		<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="250">DELEGAR EN AUSENCIA</td>
</tr>
</table></div><hr>
<?php } 	



function delegar() {
	$s_1 = "select * from empleados where id='$_SESSION[IDEMP]' and activo='1' and autorizador!='0'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); 
	$ausencia = $d_1['ausencia'];

   $s_1 = "select * from empleados where ausencia='$_SESSION[IDEMP]' and activo='1' and autorizador!='0'";
   $r_1 = mysql_query($s_1);
   if(mysql_num_rows($r_1)<=0) { $dis = ''; } else { $dis = 'disabled'; } ?>

<div align="center" class="aviso">Seleccione a la persona que estará encargada en su ausencia</div><br>
<?php if($ausencia!='0') { echo"<div align=center class=aviso_naranja>1 delegado en ausencia</div>"; }	
   if($ausencia=='0') { echo"<div align=center class=aviso_naranja>0 delegados en ausencia</div>"; } ?>	
<form action="?op=save_delegado" method="post" name="form1">
<table align="center" class="tabla">
<tbody>
<tr>
	<td bgcolor="#E6E6E6" width="80" align="center">En ausencia:</td>
	<td>
	<select name="ausencia" style="width:350px;" class="texto" <?php echo $dis;?>>
      <option value="0"></option>
      <?php $s_2 = "select id, usuario, nombre, apellidos from empleados where activo='1' and usuario!='$_SESSION[USER]' and ";
	        $s_2.= "autorizador!='0' and autorizador  = '$_SESSION[DEPTO]' order by apellidos, nombre";
	        $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
      <option value="<?php echo $d_2['id'];?>" <?php if($ausencia==$d_2['id']){?> selected="selected"<?php } ?>>
	  <?php if($d_2['nombre']!='') { echo $d_2['apellidos']." ".$d_2['nombre']; } else { echo $d_2['usuario']; } ?></option>
      <?php } ?>
    </select></td>
	<td><input type="submit" class="submit" value="Guardar" <?php echo $dis;?>></td>
</tr>
</tbody>
</table>
</form>
<?php if($dis!='') { echo "<div align='center' class='texto'>Si usted es delegado en ausencia, no puede asignar a alguien</div>"; } } 



function save_delegado($ausencia) {

if($ausencia!='0') { 
$s_1 = "select * from empleados where ausencia='$ausencia'";
$r_1 = mysql_query($s_1);
if(mysql_num_rows($r_1)<=0) {
	$s_1 = "update empleados set ausencia='$ausencia' where id='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1); }
else {
	echo "<script>alert('Esa persona ya ha sido delegada en ausencia');</script>"; } }
else {
	$s_1 = "update empleados set ausencia='$ausencia' where id='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1); }				
}	

?>