<?php include("../header.php");
   include("../mails.php"); ?>

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
			default			:	manuales(); break;
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
	<td class="titulo" width="250">MANUALES DE AYUDA</td>
</tr>
</table></div><hr>
<?php } 


function get_ruta($tipo) {
	$s_1 = "select * from configuracion where variable='$tipo'";
    $r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); 
	return $d_1['valor'];
}


function manuales() { ?>
<div align="center" class="aviso">Haga clic el el ícono para abrir cualquiera de los manuales disponibles</div><br>
<table align="center" class="tabla">
<caption>Manuales del Sistema</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="350" align="center">Nombre</td>
		<td width="50" align="center">Abrir</td>
	</tr>
</thead>
<tbody>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;Manual de Administrador</td>
	<td align="center">
    	<?php $manual=get_ruta('manual_admin'); if($manual!='') { ?>
   		<a href="<?php echo $manual;?>"><img src="../imagenes/attach.png" border="0"></a><?php } ?>&nbsp;</td>
</tr>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;Manual de Capturista</td>
	<td align="center">
    	<?php $manual=get_ruta('manual_capturista'); if($manual!='') { ?>
   		<a href="<?php echo $manual;?>"><img src="../imagenes/attach.png" border="0"></a><?php } ?>&nbsp;</td>
</tr>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;Manual de Autorizador</td>
	<td align="center">
    	<?php $manual=get_ruta('manual_autorizador'); if($manual!='') { ?>
   		<a href="<?php echo $manual;?>"><img src="../imagenes/attach.png" border="0"></a><?php } ?>&nbsp;</td>
</tr>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;Manual de Reportes</td>
	<td align="center">
    	<?php $manual=get_ruta('manual_reportes'); if($manual!='') { ?>
   		<a href="<?php echo $manual;?>"><img src="../imagenes/attach.png" border="0"></a><?php } ?>&nbsp;</td>
</tr>
</tbody>
</table>
<br><br><br>
<?php } ?>