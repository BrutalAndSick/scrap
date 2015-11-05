<?php function general() { ?>
<table align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../capturista/inicio.php"><img src="../imagenes/email.png" alt="E-mails" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center"><img src="../imagenes/archivos.png" alt="Archivos" border="0"onmouseover="showMenu(menu1,true)" onmouseout="showMenu(menu1,false)"></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../capturista/ayuda.php"><img src="../imagenes/ayuda.png" alt="Ayuda" border="0"></a></td>
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
        <td width="150" align="center"><?php if($boton!="b_capturai") { ?><a href="../capturista/scrap_captura.php?op=nuevo" onMouseOver="if(document.images) document.b_capturai.src='../imagenes/botones/b_capturai_on.png';" onMouseOut="if(document.images) document.b_capturai.src='../imagenes/botones/b_capturai_off.png';"><img name="b_capturai" src="../imagenes/botones/b_capturai_off.png" border="0" alt="Captura Individual de Scrap"></a><?php } else { ?><a href="../capturista/scrap_captura.php?op=nuevo"><img name="b_capturai" src="../imagenes/botones/b_capturai_on.png" border="0" alt="Captura Individual de Scrap"></a><?php } ?></td>
        <td width="160" align="center"><?php if($boton!="b_capturam") { ?><a href="../capturista/scrap_masiva.php?op=nuevo" onMouseOver="if(document.images) document.b_capturam.src='../imagenes/botones/b_capturam_on.png';" onMouseOut="if(document.images) document.b_capturam.src='../imagenes/botones/b_capturam_off.png';"><img name="b_capturam" src="../imagenes/botones/b_capturam_off.png" border="0" alt="Captura Masiva de Scrap"></a><?php } else { ?><a href="../capturista/scrap_masiva.php?op=nuevo"><img name="b_capturam" src="../imagenes/botones/b_capturam_on.png" border="0" alt="Captura Masiva de Scrap"></a><?php } ?></td>		
        <td width="140" align="center"><?php if($boton!="b_capturae") { ?><a href="../capturista/scrap_archivo.php?op=nuevo" onMouseOver="if(document.images) document.b_capturae.src='../imagenes/botones/b_capturae_on.png';" onMouseOut="if(document.images) document.b_capturae.src='../imagenes/botones/b_capturae_off.png';"><img name="b_capturae" src="../imagenes/botones/b_capturae_off.png" border="0" alt="Capturas Especiales"></a><?php } else { ?><a href="../capturista/scrap_archivo.php?op=nuevo"><img name="b_capturae" src="../imagenes/botones/b_capturae_on.png" border="0" alt="Capturas Especiales"></a><?php } ?></td>   
        <td width="110" align="center"><?php if($boton!="b_consulta") { ?><a href="../capturista/consultas.php?tipo=proceso" onMouseOver="if(document.images) document.b_consulta.src='../imagenes/botones/b_consulta_on.png';" onMouseOut="if(document.images) document.b_consulta.src='../imagenes/botones/b_consulta_off.png';"><img name="b_consulta" src="../imagenes/botones/b_consulta_off.png" border="0" alt="Consulta de Scrap"></a><?php } else { ?><a href="../capturista/consultas.php?tipo=proceso"><img name="b_capturam" src="../imagenes/botones/b_consulta_on.png" border="0" alt="Consulta de Scrap"></a><?php } ?></td> 
        <td width="110" align="center"><?php if($boton!="b_reportes") { ?><a href="../reportes/rep_preliminar.php" onMouseOver="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_on.png';" onMouseOut="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_off.png';"><img name="b_reportes" src="../imagenes/botones/b_reportes_off.png" border="0" alt="Reportes"></a><?php } else { ?><a href="../reportes/rep_preliminar.php"><img name="b_reportes" src="../imagenes/botones/b_reportes_on.png" border="0" alt="Reportes"></a><?php } ?></td>			
      </tr>
    </table>
<?php } 


function submenu($boton,$sub) { 
	switch($boton) {
		case "b_consulta"	:	boton_consulta($sub);   break; 
		case "b_capturae"	:	boton_capturae($sub);   break; 
		case "b_reportes"	:	boton_reportes($sub);   break; 
} }


function boton_consulta($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="110" align="center"><?php if($sub!='proceso'){?><a href="consultas.php?tipo=proceso" onMouseOver="if(document.images) document.proceso.src='../imagenes/botones/en_proceso_on.png';" onMouseOut="if(document.images) document.proceso.src='../imagenes/botones/en_proceso_off.png';"><img name="proceso" src="../imagenes/botones/en_proceso_off.png" border="0" alt="Capturas de scrap en proceso"></a><?php } else { ?><a href="consultas.php?tipo=proceso"><img name="proceso" src="../imagenes/botones/en_proceso_on.png" border="0" alt="Capturas de scrap en proceso"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="110" align="center"><?php if($sub!='rechazados'){?><a href="consultas.php?tipo=rechazados" onMouseOver="if(document.images) document.rechazados.src='../imagenes/botones/rechazados_on.png';" onMouseOut="if(document.images) document.rechazados.src='../imagenes/botones/rechazados_off.png';"><img name="rechazados" src="../imagenes/botones/rechazados_off.png" border="0" alt="Capturas de scrap rechazadas"></a><?php } else { ?><a href="consultas.php?tipo=rechazados"><img name="rechazados" src="../imagenes/botones/rechazados_on.png" border="0" alt="Capturas de scrap rechazadas"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
		<td width="110" align="center"><?php if($sub!='cancelados'){?><a href="consultas.php?tipo=cancelados" onMouseOver="if(document.images) document.cancelados.src='../imagenes/botones/cancelados_on.png';" onMouseOut="if(document.images) document.cancelados.src='../imagenes/botones/cancelados_off.png';"><img name="cancelados" src="../imagenes/botones/cancelados_off.png" border="0" alt="Capturas de scrap canceladas"></a><?php } else { ?><a href="consultas.php?tipo=cancelados"><img name="cancelados" src="../imagenes/botones/cancelados_on.png" border="0" alt="Capturas de scrap canceladas"></a><?php } ?></td>		
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
		<td width="110" align="center"><?php if($sub!='aprobados'){?><a href="consultas.php?tipo=aprobados" onMouseOver="if(document.images) document.aprobados.src='../imagenes/botones/aprobados_on.png';" onMouseOut="if(document.images) document.aprobados.src='../imagenes/botones/aprobados_off.png';"><img name="aprobados" src="../imagenes/botones/aprobados_off.png" border="0" alt="Capturas de scrap aprobadas"></a><?php } else { ?><a href="consultas.php?tipo=aprobados"><img name="aprobados" src="../imagenes/botones/aprobados_on.png" border="0" alt="Capturas de scrap aprobadas"></a><?php } ?></td>		
	</tr>
</table></div>
<?php } 


function boton_capturae($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
       <!-- <td width="100" align="center"><?php if($sub!='b_capturaa'){?><a href="scrap_archivo.php?op=nuevo" onMouseOver="if(document.images) document.proceso.src='../imagenes/botones/b_capturaa_on.png';" onMouseOut="if(document.images) document.proceso.src='../imagenes/botones/b_capturaa_off.png';"><img name="proceso" src="../imagenes/botones/b_capturaa_off.png" border="0" alt="Captura de scrap por archivo"></a><?php } else { ?><a href="scrap_archivo.php?op=nuevo"><img name="proceso" src="../imagenes/botones/b_capturaa_on.png" border="0" alt="Captura de scrap por archivo"></a><?php } ?></td>-->
        <?php if($_SESSION["DEPTO"]=='inv') { ?>
        <!--<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>-->
        <td width="100" align="center"><?php if($sub!='b_captura35'){?><a href="scrap_archivo_35.php?op=nuevo" onMouseOver="if(document.images) document.proceso.src='../imagenes/botones/b_captura35_on.png';" onMouseOut="if(document.images) document.proceso.src='../imagenes/botones/b_captura35_off.png';"><img name="proceso" src="../imagenes/botones/b_captura35_off.png" border="0" alt="Captura de Merma"></a><?php } else { ?><a href="scrap_archivo_35.php?op=nuevo"><img name="proceso" src="../imagenes/botones/b_captura35_on.png" border="0" alt="Captura de Merma"></a><?php } ?></td><?php } ?>
        <td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="100" align="center"><?php if($sub!='b_captura095'){?><a href="scrap_archivo_095.php?op=nuevo" onMouseOver="if(document.images) document.proceso.src='../imagenes/botones/codigo_095_on.png';" onMouseOut="if(document.images) document.proceso.src='../imagenes/botones/codigo_095_off.png';"><img name="proceso" src="../imagenes/botones/codigo_095_off.png" border="0" alt="Captura de Código 095"></a><?php } else { ?><a href="scrap_archivo_095.php?op=nuevo"><img name="proceso" src="../imagenes/botones/codigo_095_on.png" border="0" alt="Captura de Código 095"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png"></td>	
        <td width="100" align="center"><?php if($sub!='b_capturat'){?><a href="scrap_manual.php?op=nuevo" onMouseOver="if(document.images) document.proceso.src='../imagenes/botones/b_capturat_on.png';" onMouseOut="if(document.images) document.proceso.src='../imagenes/botones/b_capturat_off.png';"><img name="proceso" src="../imagenes/botones/b_capturat_off.png" border="0" alt="Captura de scrap manual"></a><?php } else { ?><a href="scrap_manual.php?op=nuevo"><img name="proceso" src="../imagenes/botones/b_capturat_on.png" border="0" alt="Captura de scrap manual"></a><?php } ?></td>
	</tr>
</table></div>
<?php } 


function boton_reportes($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="100" align="center"><?php if($sub!='preliminar') { ?><a href="../reportes/rep_preliminar.php" onMouseOver="if(document.images) document.preliminar.src='../imagenes/botones/rep_preliminar_on.png';" onMouseOut="if(document.images) document.preliminar.src='../imagenes/botones/rep_preliminar_off.png';"><img name="preliminar" src="../imagenes/botones/rep_preliminar_off.png" border="0" alt="Preliminar"></a><?php } else { ?><a href="../reportes/rep_preliminar.php"><img name="preliminar" src="../imagenes/botones/rep_preliminar_on.png" border="0" alt="Preliminar"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
		<td width="90" align="center"><?php if($sub!='graficos') { ?><a href="../reportes/rep_graficos.php?op=filtros" onMouseOver="if(document.images) document.graficos.src='../imagenes/botones/rep_graficos_on.png';" onMouseOut="if(document.images) document.graficos.src='../imagenes/botones/rep_graficos_off.png';"><img name="graficos" src="../imagenes/botones/rep_graficos_off.png" border="0" alt="Gr&aacute;ficos"></a><?php } else { ?><a href="../reportes/rep_graficos.php?op=filtros"><img name="graficos" src="../imagenes/botones/rep_graficos_on.png" border="0" alt="Gr&aacute;ficos"></a><?php } ?></td>
		</td>				
	</tr>
</table></div>
<?php } ?>
