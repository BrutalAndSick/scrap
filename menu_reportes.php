<?php function general() { ?>
<table align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../reportes/inicio.php"><img src="../imagenes/email.png" alt="E-Mails" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center"><img src="../imagenes/archivos.png" alt="Archivos" border="0"onmouseover="showMenu(menu1,true)" onmouseout="showMenu(menu1,false)"></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../reportes/ayuda.php"><img src="../imagenes/ayuda.png" alt="Ayuda" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../logout.php"><img src="../imagenes/logout.png" alt="Logout" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>	
	</tr>
</table>
<div class="menu" id="menu1" onmouseover="showMenu(menu1,true)" onmouseout="showMenu(menu1,false)">
<table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr height="30">
	<?php $s_1 = "select valor from configuracion where variable='archivo_oficial'";
	      $r_1 = mysql_query($s_1);
	      $d_1 = mysql_fetch_array($r_1); ?>
	<td align="left">
    	&nbsp;&nbsp;<a href="<?php echo $d_1['valor'];?>" class="link_blanco" target="_blank">C&oacute;digos de Scrap Oficial</a></td>
</tr>
<tr bgcolor="#666666" height="1"><td></td></tr>    
<tr height="30">
	<?php $s_1 = "select valor from configuracion where variable='dash_board'";
	      $r_1 = mysql_query($s_1);
	      $d_1 = mysql_fetch_array($r_1); ?>
	<td align="left">
    	&nbsp;&nbsp;<a href="<?php echo $d_1['valor'];?>" class="link_blanco" target="_blank">Dash Board</a></td>
</tr>
</table>    
</div>
<?php } ?>


<?php function menu($boton) { ?>
<table border="0" cellpadding="0" cellspacing="0">
      <tr height="20">
        <td width="110" align="center"><?php if($boton!="b_reportes") { ?><a href="../reportes/rep_general.php" onMouseOver="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_on.png';" onMouseOut="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_off.png';"><img name="b_reportes" src="../imagenes/botones/b_reportes_off.png" border="0" alt="Reportes"></a><?php } else { ?><a href="../reportes/rep_general.php"><img name="b_reportes" src="../imagenes/botones/b_reportes_on.png" border="0" alt="Reportes"></a><?php } ?></td>			
      </tr>
    </table>
<?php } 


function submenu($boton,$sub) { 
	switch($boton) {
		case "b_reportes"	:	boton_reportes($sub);   break; 
} }


function boton_reportes($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
        <td width="100" align="center"><?php if($sub!='general') { ?><a href="../reportes/rep_general.php" onMouseOver="if(document.images) document.general.src='../imagenes/botones/rep_general_on.png';" onMouseOut="if(document.images) document.general.src='../imagenes/botones/rep_general_off.png';"><img name="general" src="../imagenes/botones/rep_general_off.png" border="0" alt="General"></a><?php } else { ?><a href="../reportes/rep_general.php"><img name="general" src="../imagenes/botones/rep_general_on.png" border="0" alt="General"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
		<td width="90" align="center"><?php if($sub!='graficos') { ?><a href="../reportes/rep_graficos.php?op=filtros" onMouseOver="if(document.images) document.graficos.src='../imagenes/botones/rep_graficos_on.png';" onMouseOut="if(document.images) document.graficos.src='../imagenes/botones/rep_graficos_off.png';"><img name="graficos" src="../imagenes/botones/rep_graficos_off.png" border="0" alt="Gr&aacute;ficos"></a><?php } else { ?><a href="../reportes/rep_graficos.php?op=filtros"><img name="graficos" src="../imagenes/botones/rep_graficos_on.png" border="0" alt="Gr&aacute;ficos"></a><?php } ?></td>
		<?php if(is_jefe()=='SI') { ?>       
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
		<td width="90" align="center"><?php if($sub!='atrasos') { ?><a href="../reportes/rep_atrasos.php" onMouseOver="if(document.images) document.atrasos.src='../imagenes/botones/rep_atrasos_on.png';" onMouseOut="if(document.images) document.atrasos.src='../imagenes/botones/rep_atrasos_off.png';"><img name="atrasos" src="../imagenes/botones/rep_atrasos_off.png" border="0" alt="Atrasos"></a><?php } else { ?><a href="../reportes/rep_atrasos.php"><img name="atrasos" src="../imagenes/botones/rep_atrasos_on.png" border="0" alt="Atrasos"></a><?php } ?></td><?php } ?>
	</tr>
</table></div>
<?php } 

function is_jefe() {
	$jefe = 'NO';
	$s_ = "select * from divisiones where jefe='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { $jefe = 'SI'; }
	$s_ = "select * from plantas where jefe='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { $jefe = 'SI'; }
	return $jefe;
} ?>
