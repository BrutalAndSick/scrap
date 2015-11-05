<?php function general() { ?>
<table align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<?php if($_SESSION["TYPE"]=='administrador') { ?>
        <td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../admin/inicio.php"><img src="../imagenes/email.png" alt="E-Mails" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center"><img src="../imagenes/archivos.png" alt="Archivos" border="0" onmouseover="showMenu(menu1,true)" onmouseout="showMenu(menu1,false)"></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../admin/ayuda.php"><img src="../imagenes/ayuda.png" alt="Ayuda" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../admin/config.php"><img src="../imagenes/gear.png" alt="Configuraci&oacute;n" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td>
		<td width="50" align="center">
			<a href="../logout.php"><img src="../imagenes/logout.png" alt="Salir" border="0"></a></td>
		<td><img src="../imagenes/division.png"></td><?php } ?>
        
        <?php if($_SESSION["TYPE"]=='materiales') { ?>
		<td width="250" align="right">
			<a href="../logout.php"><img src="../imagenes/logout.png" alt="Salir" border="0"></a></td>
        <?php } ?>        	
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
        <?php if($_SESSION["TYPE"]=='administrador') { ?>
        <td width="110" align="center"><?php if($boton!="b_usuarios") { ?><a href="../admin/empleados.php" onMouseOver="if(document.images) document.b_usuarios.src='../imagenes/botones/b_usuarios_on.png';" onMouseOut="if(document.images) document.b_usuarios.src='../imagenes/botones/b_usuarios_off.png';"><img name="b_usuarios" src="../imagenes/botones/b_usuarios_off.png" border="0" alt="Empleados y Privilegios"></a><?php } else { ?><a href="../admin/empleados.php"><img name="b_usuarios" src="../imagenes/botones/b_usuarios_on.png" border="0" alt="Empleados y Privilegios"></a><?php } ?></td>
		<td width="100" align="center"><?php if($boton!="b_plantas") { ?><a href="../admin/plantas.php" onMouseOver="if(document.images) document.b_plantas.src='../imagenes/botones/b_plantas_on.png';" onMouseOut="if(document.images) document.b_plantas.src='../imagenes/botones/b_plantas_off.png';"><img name="b_plantas" src="../imagenes/botones/b_plantas_off.png" border="0" alt="Divisiones de Plantas"></a><?php } else {?><a href="../admin/plantas.php"><img name="b_plantas" src="../imagenes/botones/b_plantas_on.png" border="0" alt="Divisiones de Plantas"></a><?php } ?></td>
        <td width="100" align="center"><?php if($boton!="b_areas") { ?><a href="../admin/areas.php" onMouseOver="if(document.images) document.b_areas.src='../imagenes/botones/b_areas_on.png';" onMouseOut="if(document.images) document.b_areas.src='../imagenes/botones/b_areas_off.png';"><img name="b_areas" src="../imagenes/botones/b_areas_off.png" border="0" alt="Divisiones de &Aacute;reas"></a><?php } else { ?><a href="../admin/areas.php"><img name="b_areas" src="../imagenes/botones/b_areas_on.png" border="0" alt="Divisiones de &Aacute;reas"></a><?php } ?></td><?php } ?>
        <?php if($_SESSION["TYPE"]=='administrador' || $_SESSION["TYPE"]=='materiales') { ?>
        <td width="220" align="center"><?php if($boton!="b_materiales") { ?><a href="../admin/modelos.php?tabla=roh" onMouseOver="if(document.images) document.b_materiales.src='../imagenes/botones/b_materiales_on.png';" onMouseOut="if(document.images) document.b_materiales.src='../imagenes/botones/b_materiales_off.png';"><img name="b_materiales" src="../imagenes/botones/b_materiales_off.png" border="0" alt="N&uacute;meros de Parte"></a><?php } else { ?><a href="../admin/modelos.php?tabla=roh"><img name="b_materiales" src="../imagenes/botones/b_materiales_on.png" border="0" alt="N&uacute;meros de Parte"></a><?php } ?></td><?php } ?>
        <?php if($_SESSION["TYPE"]=='administrador') { ?>
        <td width="110" align="center"><?php if($boton!="b_reportes") { ?><a href="../reportes/rep_preliminar.php" onMouseOver="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_on.png';" onMouseOut="if(document.images) document.b_reportes.src='../imagenes/botones/b_reportes_off.png';"><img name="b_reportes" src="../imagenes/botones/b_reportes_off.png" border="0" alt="Reportes"></a><?php } else { ?><a href="../reportes/rep_preliminar.php"><img name="b_reportes" src="../imagenes/botones/b_reportes_on.png" border="0" alt="Reportes"></a><?php } ?></td>		
        <td width="90" align="center"><?php if($boton!="b_mails") { ?><a href="../admin/mails.php?op=listado" onMouseOver="if(document.images) document.b_mails.src='../imagenes/botones/b_mails_on.png';" onMouseOut="if(document.images) document.b_mails.src='../imagenes/botones/b_mails_off.png';"><img name="b_mails" src="../imagenes/botones/b_mails_off.png" border="0" alt="Mails"></a><?php } else { ?><a href="../admin/mails.php?op=listado"><img name="b_mails" src="../imagenes/botones/b_mails_on.png" border="0" alt="Mails"></a><?php } ?></td>
		<td width="110" align="center"><?php if($boton!="b_extras") { ?><a href="../reportes/rep_corto.php?op=corto" onMouseOver="if(document.images) document.b_extras.src='../imagenes/botones/b_extras_on.png';" onMouseOut="if(document.images) document.b_extras.src='../imagenes/botones/b_extras_off.png';"><img name="b_extras" src="../imagenes/botones/b_extras_off.png" border="0" alt="Extras"></a><?php } else { ?><a href="../reportes/rep_corto.php?op=corto"><img name="b_extras" src="../imagenes/botones/b_extras_on.png" border="0" alt="Extras"></a><?php } ?></td><?php } ?>	
      </tr>	  
    </table>
<?php } 


function submenu($boton,$sub) { 
	switch($boton) {
		case "b_usuarios"	:	boton_usuarios($sub);   break; 
		case "b_plantas"	:	boton_plantas($sub);    break;
		case "b_areas"		:	boton_areas($sub);      break; 	
		case "b_materiales"	:	boton_materiales($sub); break;
		case "b_reportes"	:	boton_reportes($sub);   break; 
		case "b_extras"		:	boton_extras($sub);  	break; 
} }


function boton_usuarios($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="100" align="center"><?php if($sub!='empleados'){?><a href="empleados.php" onMouseOver="if(document.images) document.empleados.src='../imagenes/botones/empleados_on.png';" onMouseOut="if(document.images) document.empleados.src='../imagenes/botones/empleados_off.png';"><img name="empleados" src="../imagenes/botones/empleados_off.png" border="0" alt="Empleados"></a><?php } else { ?><a href="empleados.php"><img name="empleados" src="../imagenes/botones/empleados_on.png" border="0" alt="Empleados"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="100" align="center"><?php if($sub!='supervisores'){?><a href="supervisores.php" onMouseOver="if(document.images) document.supervisores.src='../imagenes/botones/supervisores_on.png';" onMouseOut="if(document.images) document.supervisores.src='../imagenes/botones/supervisores_off.png';"><img name="supervisores" src="../imagenes/botones/supervisores_off.png" border="0" alt="Supervisores"></a><?php } else { ?><a href="supervisores.php"><img name="supervisores" src="../imagenes/botones/supervisores_on.png" border="0" alt="Supervisores"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="100" align="center"><?php if($sub!='capturistas'){?><a href="capturistas.php" onMouseOver="if(document.images) document.capturistas.src='../imagenes/botones/capturistas_on.png';" onMouseOut="if(document.images) document.capturistas.src='../imagenes/botones/capturistas_off.png';"><img name="capturistas" src="../imagenes/botones/capturistas_off.png" border="0" alt="Capturistas"></a><?php } else { ?><a href="capturistas.php"><img name="capturistas" src="../imagenes/botones/capturistas_on.png" border="0" alt="Capturistas"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="100" align="center"><?php if($sub!='autorizadores'){?><a href="aut_lpl.php" onMouseOver="if(document.images) document.autorizadores.src='../imagenes/botones/autorizadores_on.png';" onMouseOut="if(document.images) document.autorizadores.src='../imagenes/botones/autorizadores_off.png';"><img name="autorizadores" src="../imagenes/botones/autorizadores_off.png" border="0" alt="Autorizadores"></a><?php } else { ?><a href="aut_lpl.php"><img name="autorizadores" src="../imagenes/botones/autorizadores_on.png" border="0" alt="Autorizadores"></a><?php } ?></td>
 		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="70" align="center"><?php if($sub!='vendors'){?><a href="vendors.php" onMouseOver="if(document.images) document.vendors.src='../imagenes/botones/vendors_on.png';" onMouseOut="if(document.images) document.vendors.src='../imagenes/botones/vendors_off.png';"><img name="vendors" src="../imagenes/botones/vendors_off.png" border="0" alt="Vendors"></a><?php } else { ?><a href="vendors.php"><img name="vendors" src="../imagenes/botones/vendors_on.png" border="0" alt="Vendors"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="70" align="center"><?php if($sub!='cap_merma'){?><a href="capt_merma.php" onMouseOver="if(document.images) document.cap_merma.src='../imagenes/botones/capt_merma_on.png';" onMouseOut="if(document.images) document.capt_merma.src='../imagenes/botones/capt_merma_off.png';"><img name="capt_merma" src="../imagenes/botones/capt_merma_off.png" border="0" alt="Capturistas Merma"></a><?php } else { ?><a href="capt_merma.php"><img name="capt_merma" src="../imagenes/botones/capt_merma_on.png" border="0" alt="Capturistas Merma"></a><?php } ?></td>
	</tr>
</table></div>
<?php } 


function boton_plantas($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="80" align="center"><?php if($sub!='plantas') { ?><a href="plantas.php" onMouseOver="if(document.images) document.plantas.src='../imagenes/botones/plantas_on.png';" onMouseOut="if(document.images) document.plantas.src='../imagenes/botones/plantas_off.png';"><img name="plantas" src="../imagenes/botones/plantas_off.png" border="0" alt="Plantas"></a><?php } else { ?><a href="plantas.php"><img name="plantas" src="../imagenes/botones/plantas_on.png" border="0" alt="Plantas"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="90" align="center"><?php if($sub!='divisiones'){?><a href="divisiones.php" onMouseOver="if(document.images) document.divisiones.src='../imagenes/botones/divisiones_on.png';" onMouseOut="if(document.images) document.divisiones.src='../imagenes/botones/divisiones_off.png';"><img name="divisiones" src="../imagenes/botones/divisiones_off.png" border="0" alt="Divisiones"></a><?php } else { ?><a href="divisiones.php"><img name="divisiones" src="../imagenes/botones/divisiones_on.png" border="0" alt="Divisiones"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="90" align="center"><?php if($sub!='segmentos'){?><a href="segmentos.php" onMouseOver="if(document.images) document.segmentos.src='../imagenes/botones/segmentos_on.png';" onMouseOut="if(document.images) document.segmentos.src='../imagenes/botones/segmentos_off.png';"><img name="segmentos" src="../imagenes/botones/segmentos_off.png" border="0" alt="Segmentos"></a><?php } else { ?><a href="segmentos.php"><img name="segmentos" src="../imagenes/botones/segmentos_on.png" border="0" alt="Segmentos"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="110" align="center"><?php if($sub!='profit_center'){?><a href="profit_center.php" onMouseOver="if(document.images) document.profit_center.src='../imagenes/botones/profit_center_on.png';" onMouseOut="if(document.images) document.profit_center.src='../imagenes/botones/profit_center_off.png';"><img name="profit_center" src="../imagenes/botones/profit_center_off.png" border="0" alt="Profit Centers"></a><?php } else {?><a href="profit_center.php"><img name="profit_center" src="../imagenes/botones/profit_center_on.png" border="0" alt="Profit Centers"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td> 
		<td width="60" align="center"><?php if($sub!='apd'){?><a href="apd.php" onMouseOver="if(document.images) document.apd.src='../imagenes/botones/apd_on.png';" onMouseOut="if(document.images) document.apd.src='../imagenes/botones/apd_off.png';"><img name="apd" src="../imagenes/botones/apd_off.png" border="0" alt="APD"></a><?php } else { ?><a href="apd.php"><img name="apd" src="../imagenes/botones/apd_on.png" border="0" alt="APD"></a><?php } ?></td>		
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="80" align="center"><?php if($sub!='proyectos'){?><a href="proyectos.php" onMouseOver="if(document.images) document.proyectos.src='../imagenes/botones/proyectos_on.png';" onMouseOut="if(document.images) document.proyectos.src='../imagenes/botones/proyectos_off.png';"><img name="proyectos" src="../imagenes/botones/proyectos_off.png" border="0" alt="Proyectos"></a><?php } else { ?><a href="proyectos.php"><img name="proyectos" src="../imagenes/botones/proyectos_on.png" border="0" alt="Proyectos"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="80" align="center"><?php if($sub!='motivos_sap'){?><a href="motivos_sap.php" onMouseOver="if(document.images) document.motivos_sap.src='../imagenes/botones/motivos_sap_on.png';" onMouseOut="if(document.images) document.motivos_sap.src='../imagenes/botones/motivos_sap_off.png';"><img name="motivos_sap" src="../imagenes/botones/motivos_sap_off.png" border="0" alt="Motivos SAP"></a><?php } else { ?><a href="motivos_sap.php"><img name="motivos_sap" src="../imagenes/botones/motivos_sap_on.png" border="0" alt="Motivos SAP"></a><?php } ?></td>        
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="80" align="center"><?php if($sub!='motivos_cancel'){?><a href="motivos_cancel.php" onMouseOver="if(document.images) document.motivos_cancel.src='../imagenes/botones/motivos_cancel_on.png';" onMouseOut="if(document.images) document.motivos_cancel.src='../imagenes/botones/motivos_cancel_off.png';"><img name="motivos_cancel" src="../imagenes/botones/motivos_cancel_off.png" border="0" alt="Motivos Cancelar"></a><?php } else { ?><a href="motivos_cancel.php"><img name="motivos_cancel" src="../imagenes/botones/motivos_cancel_on.png" border="0" alt="Motivos Cancelar"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png"></td>
        <td width="80" align="center"><?php if($sub!='exportar'){?><a href="exportar_todo.php?op=por_plantas" onMouseOver="if(document.images) document.exportar.src='../imagenes/botones/exportar_on.png';" onMouseOut="if(document.images) document.exportar.src='../imagenes/botones/exportar_off.png';"><img name="exportar" src="../imagenes/botones/exportar_off.png" border="0" alt="Exportar a Excel"></a><?php } else { ?><a href="exportar_todo.php?op=por_plantas"><img name="exportar" src="../imagenes/botones/exportar_on.png" border="0" alt="Exportar a Excel"></a><?php } ?></td>		
	</tr>
</table></div>
<?php }


function boton_areas($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="70" align="center"><?php if($sub!='areas'){?><a href="areas.php" onMouseOver="if(document.images) document.areas.src='../imagenes/botones/areas_on.png';" onMouseOut="if(document.images) document.areas.src='../imagenes/botones/areas_off.png';"><img name="areas" src="../imagenes/botones/areas_off.png" border="0" alt="&Aacute;reas"></a><?php } else { ?><a href="areas.php"><img name="areas" src="../imagenes/botones/areas_on.png" border="0" alt="&Aacute;reas"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="95" align="center"><?php if($sub!='tecnologia'){?><a href="tecnologia.php" onMouseOver="if(document.images) document.tecnologia.src='../imagenes/botones/tecnologia_on.png';" onMouseOut="if(document.images) document.tecnologia.src='../imagenes/botones/tecnologia_off.png';"><img name="tecnologia" src="../imagenes/botones/tecnologia_off.png" border="0" alt="Tecnolog&iacute;a"></a><?php } else { ?><a href="tecnologia.php"><img name="tecnologia" src="../imagenes/botones/tecnologia_on.png" border="0" alt="Tecnolog&iacute;a"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="75" align="center"><?php if($sub!='lineas'){?><a href="lineas.php" onMouseOver="if(document.images) document.lineas.src='../imagenes/botones/lineas_on.png';" onMouseOut="if(document.images) document.lineas.src='../imagenes/botones/lineas_off.png';"><img name="lineas" src="../imagenes/botones/lineas_off.png" border="0" alt="L&iacute;neas"></a><?php } else { ?><a href="lineas.php"><img name="lineas" src="../imagenes/botones/lineas_on.png" border="0" alt="L&iacute;neas"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="110" align="center"><?php if($sub!='codigo_scrap'){?><a href="codigo_scrap.php" onMouseOver="if(document.images) document.cod_scrap.src='../imagenes/botones/cod_scrap_on.png';" onMouseOut="if(document.images) document.cod_scrap.src='../imagenes/botones/cod_scrap_off.png';"><img name="cod_scrap" src="../imagenes/botones/cod_scrap_off.png" border="0" alt="C&oacute;digo SCRAP"></a><?php } else { ?><a href="codigo_scrap.php"><img name="cod_scrap" src="../imagenes/botones/cod_scrap_on.png" border="0" alt="C&oacute;digo SCRAP"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="110" align="center"><?php if($sub!='oi_especial'){?><a href="oi_especial.php" onMouseOver="if(document.images) document.oi_especial.src='../imagenes/botones/oi_especial_on.png';" onMouseOut="if(document.images) document.oi_especial.src='../imagenes/botones/oi_especial_off.png';"><img name="oi_especial" src="../imagenes/botones/oi_especial_off.png" border="0" alt="O.I. Especial"></a><?php } else { ?><a href="oi_especial.php"><img name="oi_especial" src="../imagenes/botones/oi_especial_on.png" border="0" alt="O.I. Especial"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>	
		<td width="110" align="center"><?php if($sub!='relacionado_a'){?><a href="relacionado_a.php" onMouseOver="if(document.images) document.relacionado.src='../imagenes/botones/relacionado_on.png';" onMouseOut="if(document.images) document.relacionado.src='../imagenes/botones/relacionado_off.png';"><img name="relacionado" src="../imagenes/botones/relacionado_off.png" border="0" alt="Relacionado a"></a><?php } else { ?><a href="relacionado_a.php"><img name="relacionado" src="../imagenes/botones/relacionado_on.png" border="0" alt="Relacionado a"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
		<td width="80" align="center"><?php if($sub!='defectos'){?><a href="defectos.php" onMouseOver="if(document.images) document.defectos.src='../imagenes/botones/defectos_on.png';" onMouseOut="if(document.images) document.defectos.src='../imagenes/botones/defectos_off.png';"><img name="defectos" src="../imagenes/botones/defectos_off.png" border="0" alt="Defectos"></a><?php } else { ?><a href="defectos.php"><img name="defectos" src="../imagenes/botones/defectos_on.png" border="0" alt="Defectos"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="80" align="center"><?php if($sub!='exportar'){?><a href="exportar_todo.php?op=por_areas" onMouseOver="if(document.images) document.exportar.src='../imagenes/botones/exportar_on.png';" onMouseOut="if(document.images) document.exportar.src='../imagenes/botones/exportar_off.png';"><img name="exportar" src="../imagenes/botones/exportar_off.png" border="0" alt="Exportar a Excel"></a><?php } else { ?><a href="exportar_todo.php?op=por_areas"><img name="exportar" src="../imagenes/botones/exportar_on.png" border="0" alt="Exportar a Excel"></a><?php } ?></td>		
	</tr>	
</table></div>
<?php } 


function boton_materiales($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
        <td width="110" align="center"><?php if($sub!='roh'){?><a href="modelos.php?tabla=roh" onMouseOver="if(document.images) document.raw.src='../imagenes/botones/raw_on.png';" onMouseOut="if(document.images) document.raw.src='../imagenes/botones/raw_off.png';"><img name="raw" src="../imagenes/botones/raw_off.png" border="0" alt="Raw Material"></a><?php } else { ?><a href="modelos.php?tabla=roh"><img name="raw" src="../imagenes/botones/raw_on.png" border="0" alt="Raw Material"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="120" align="center"><?php if($sub!='halb'){?><a href="modelos.php?tabla=halb" onMouseOver="if(document.images) document.subensamble.src='../imagenes/botones/subensamble_on.png';" onMouseOut="if(document.images) document.subensamble.src='../imagenes/botones/subensamble_off.png';"><img name="subensamble" src="../imagenes/botones/subensamble_off.png" border="0" alt="Subensamble"></a><?php } else { ?><a href="modelos.php?tabla=halb"><img name="subensamble" src="../imagenes/botones/subensamble_on.png" border="0" alt="Subensamble"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="160" align="center"><?php if($sub!='fert'){?><a href="modelos.php?tabla=fert" onMouseOver="if(document.images) document.prod_ter.src='../imagenes/botones/prod_ter_on.png';" onMouseOut="if(document.images) document.prod_ter.src='../imagenes/botones/prod_ter_off.png';"><img name="prod_ter" src="../imagenes/botones/prod_ter_off.png" border="0" alt="Producto Terminado"></a><?php } else { ?><a href="modelos.php?tabla=fert"><img name="prod_ter" src="../imagenes/botones/prod_ter_on.png" border="0" alt="Producto Terminado"></a><?php } ?></td>
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="115" align="center"><?php if($sub!='partes_padre'){?><a href="partes_padre.php" onMouseOver="if(document.images) document.partes_padre.src='../imagenes/botones/partes_padre_on.png';" onMouseOut="if(document.images) document.partes_padre.src='../imagenes/botones/partes_padre_off.png';"><img name="partes_padre" src="../imagenes/botones/partes_padre_off.png" border="0" alt="Partes Padre"></a><?php } else { ?><a href="partes_padre.php"><img name="partes_padre" src="../imagenes/botones/partes_padre_on.png" border="0" alt="Partes Padre"></a><?php } ?></td>
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="115" align="center"><?php if($sub!='batch_id'){?><a href="batch_id.php" onMouseOver="if(document.images) document.batch_id.src='../imagenes/botones/batch_id_on.png';" onMouseOut="if(document.images) document.batch_id.src='../imagenes/botones/batch_id_off.png';"><img name="batch_id" src="../imagenes/botones/batch_id_off.png" border="0" alt="Batch Id"></a><?php } else { ?><a href="batch_id.php"><img name="batch_id" src="../imagenes/botones/batch_id_on.png" border="0" alt="Batch Id"></a><?php } ?></td>	
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
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>		
		<td width="90" align="center"><?php if($sub!='previo_sap') { ?><a href="../reportes/rep_previo_sap.php" onMouseOver="if(document.images) document.previo_sap.src='../imagenes/botones/previo_sap_on.png';" onMouseOut="if(document.images) document.previo_sap.src='../imagenes/botones/previo_sap_off.png';"><img name="previo_sap" src="../imagenes/botones/previo_sap_off.png" border="0" alt="Previo a SAP"></a><?php } else { ?><a href="../reportes/rep_previo_sap.php"><img name="previo_sap" src="../imagenes/botones/previo_sap_on.png" border="0" alt="Previo a SAP"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>		
		<td width="90" align="center"><?php if($sub!='reporte_sap') { ?><a href="../reportes/reporte_sap_2.php" onMouseOver="if(document.images) document.reporte_sap.src='../imagenes/botones/reporte_sap_on.png';" onMouseOut="if(document.images) document.reporte_sap.src='../imagenes/botones/reporte_sap_off.png';"><img name="reporte_sap" src="../imagenes/botones/reporte_sap_off.png" border="0" alt="Reporte SAP"></a><?php } else { ?><a href="../reportes/reporte_sap_2.php"><img name="reporte_sap" src="../imagenes/botones/reporte_sap_on.png" border="0" alt="Reporte SAP"></a><?php } ?></td>	
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>		
		<td width="90" align="center"><?php if($sub!='historial') { ?><a href="../reportes/historial.php" onMouseOver="if(document.images) document.historial.src='../imagenes/botones/historial_on.png';" onMouseOut="if(document.images) document.historial.src='../imagenes/botones/historial_off.png';"><img name="historial" src="../imagenes/botones/historial_off.png" border="0" alt="Historial"></a><?php } else { ?><a href="../reportes/historial.php"><img name="historial" src="../imagenes/botones/historial_on.png" border="0" alt="Historial"></a><?php } ?></td> 
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>		
		<td width="90" align="center"><?php if($sub!='definitivo') { ?><a href="../reportes/rep_definitivo.php" onMouseOver="if(document.images) document.definitivo.src='../imagenes/botones/rep_definitivo_on.png';" onMouseOut="if(document.images) document.definitivo.src='../imagenes/botones/rep_definitivo_off.png';"><img name="definitivo" src="../imagenes/botones/rep_definitivo_off.png" border="0" alt="Definitivo"></a><?php } else { ?><a href="../reportes/rep_definitivo.php"><img name="definitivo" src="../imagenes/botones/rep_definitivo_on.png" border="0" alt="Definitivo"></a><?php } ?></td>   
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
		<td width="90" align="center"><?php if($sub!='atrasos') { ?><a href="../reportes/rep_atrasos.php" onMouseOver="if(document.images) document.atrasos.src='../imagenes/botones/rep_atrasos_on.png';" onMouseOut="if(document.images) document.atrasos.src='../imagenes/botones/rep_atrasos_off.png';"><img name="atrasos" src="../imagenes/botones/rep_atrasos_off.png" border="0" alt="Atrasos"></a><?php } else { ?><a href="../reportes/rep_atrasos.php"><img name="atrasos" src="../imagenes/botones/rep_atrasos_on.png" border="0" alt="Atrasos"></a><?php } ?></td>   
		<td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>  
        <td width="100" align="center"><?php if($sub!='log_sistema') { ?><a href="../reportes/rep_log.php" onMouseOver="if(document.images) document.log.src='../imagenes/botones/rep_log_on.png';" onMouseOut="if(document.images) document.log.src='../imagenes/botones/rep_log_off.png';"><img name="log" src="../imagenes/botones/rep_log_off.png" border="0" alt="Log Sistema"></a><?php } else { ?><a href="../reportes/rep_log.php"><img name="log" src="../imagenes/botones/rep_log_on.png" border="0" alt="Log Sistema"></a><?php } ?></td>   
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>    
	</tr>
</table></div>
<?php }

function boton_extras($sub) { ?>
<div style="margin-top:5px; margin-left:50px;">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr valign="middle">
    	<td width="100" align="center"><?php if($sub!='turnos') { ?><a href="../reportes/rep_turnos.php" onMouseOver="if(document.images) document.turnos.src='../imagenes/botones/rep_turnos_on.png';" onMouseOut="if(document.images) document.turnos.src='../imagenes/botones/rep_turnos_off.png';"><img name="turnos" src="../imagenes/botones/rep_turnos_off.png" border="0" alt="Turnos"></a><?php } else { ?><a href="../reportes/rep_turnos.php"><img name="turnos" src="../imagenes/botones/rep_turnos_on.png" border="0" alt="Turnos"></a><?php } ?></td>   
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td> 
        <td width="100" align="center"><?php if($sub!='corto') { ?><a href="../reportes/rep_corto.php?op=corto" onMouseOver="if(document.images) document.corto.src='../imagenes/botones/rep_corto_on.png';" onMouseOut="if(document.images) document.corto.src='../imagenes/botones/rep_corto_off.png';"><img name="corto" src="../imagenes/botones/rep_corto_off.png" border="0" alt="Corto"></a><?php } else { ?><a href="../reportes/rep_corto.php?op=corto"><img name="corto" src="../imagenes/botones/rep_corto_on.png" border="0" alt="Corto"></a><?php } ?></td>   
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>
        <td width="100" align="center"><?php if($sub!='corto_muestra') { ?><a href="../reportes/rep_corto.php?op=muestras" onMouseOver="if(document.images) document.muestras.src='../imagenes/botones/rep_muestras_on.png';" onMouseOut="if(document.images) document.muestras.src='../imagenes/botones/rep_muestras_off.png';"><img name="muestras" src="../imagenes/botones/rep_muestras_off.png" border="0" alt="Muestras"></a><?php } else { ?><a href="../reportes/rep_corto.php?op=muestras"><img name="muestras" src="../imagenes/botones/rep_muestras_on.png" border="0" alt="Muestras"></a><?php } ?></td>   
        <td width="10" align="center"><img src="../imagenes/division_menu.png" /></td>        
	</tr>
</table></div>
<?php } ?>
