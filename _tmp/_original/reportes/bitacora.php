<?php include("../header.php"); ?>
   
  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','bitacora'); ?></td>
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
			case "listado"		:	listado($fechai,$fechaf,$accion,$usuario,$seccion); break;	
			default				:	listado($fechai,$fechaf,$accion,$usuario,$seccion); break;			
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
	<td class="titulo" width="230">BITÁCORA DE EVENTOS</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspBitácora de Eventos] body=[Este reporte proporciona información sobre todas las acciones efectuadas en el sistema para el rango de tiempo especificado en los filtros.<br><br>Puede filtrar también por cualquiera de los encabezados de la tabla.]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	


function listado($fechai,$fechaf,$accion,$usuario,$seccion) {
	if(!$fechai)		$fechai	= date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
	if(!$fechaf)		$fechaf	= date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	if(!$accion)		$accion = "%";
	if(!$usuario)		$usuario = "%";
	if(!$seccion)		$seccion = '%'; ?>
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
<tr>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechai',
		'valor': '<?php echo $fechai;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechaf',
		'valor': '<?php echo $fechaf;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>	
	<td><input type="submit" value="Buscar" class="submit"></td></tr>
</table>
</form>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="fechai" value="<?php echo $fechai;?>">
<input type="hidden" name="fechaf" value="<?php echo $fechaf;?>">
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="80">Fecha</td>
		<td align="center" width="80">Hora</td>
		<td align="center" width="110">
		<select name="seccion" class="texto" style="width:110px;" onchange="submit();">
			<option value="">Sección</option>
			<option value="apd" <?php if($seccion=='apd'){?> selected="selected" <?php } ?>>APD</option>
			<option value="areas" <?php if($seccion=='areas'){?> selected="selected" <?php } ?>>Áreas</option>
			<option value="autorizadores" <?php if($seccion=='autorizadores'){?> selected="selected" <?php } ?>>Autorizadores</option>
			<option value="codigo_scrap" <?php if($seccion=='codigo_scrap'){?> selected="selected" <?php } ?>>Código SCRAP</option>
			<option value="configuracion" <?php if($seccion=='configuracion'){?> selected="selected" <?php } ?>>Configuración</option>
			<option value="defectos" <?php if($seccion=='defectos'){?> selected="selected" <?php } ?>>Defectos</option>
			<option value="divisiones" <?php if($seccion=='divisiones'){?> selected="selected" <?php } ?>>Divisiones</option>
			<option value="empleados" <?php if($seccion=='empleados'){?> selected="selected" <?php } ?>>Empleados</option>
			<option value="lineas" <?php if($seccion=='lineas'){?> selected="selected" <?php } ?>>Líneas</option>
			<option value="mails" <?php if($seccion=='mails'){?> selected="selected" <?php } ?>>Mails</option>		
			<option value="modelos" <?php if($seccion=='modelos'){?> selected="selected" <?php } ?>>Modelos</option>		
			<option value="partes_padre" <?php if($seccion=='partes_padre'){?> selected="selected" <?php } ?>>Partes Padre</option>
			<option value="plantas" <?php if($seccion=='plantas'){?> selected="selected" <?php } ?>>Plantas</option>
			<option value="profit_center" <?php if($seccion=='profit_center'){?> selected="selected" <?php } ?>>Profit Center</option>
			<option value="proyectos" <?php if($seccion=='proyectos'){?> selected="selected" <?php } ?>>Proyectos</option>
			<option value="relacionado_a" <?php if($seccion=='relacionado_a'){?> selected="selected" <?php } ?>>Relacionado a</option>
			<option value="ubicaciones" <?php if($seccion=='ubicaciones'){?> selected="selected" <?php } ?>>Ubicaciones</option>
			<option value="scrap" <?php if($seccion=='scrap'){?> selected="selected" <?php } ?>>Scrap</option>
			<option value="segmentos" <?php if($seccion=='segmentos'){?> selected="selected" <?php } ?>>Segmentos</option>
			<option value="tecnologia" <?php if($seccion=='tecnologia'){?> selected="selected" <?php } ?>>Tecnología</option>
		</select>			
		</td>
		<td align="center" width="100">
		<select name="accion" class="texto" style="width:100px;" onchange="submit();">
			<option value="">Acción</option>
			<option value="borrar" <?php if($accion=='borrar'){?> selected="selected" <?php } ?>>Borrar</option>
			<option value="editar" <?php if($accion=='editar'){?> selected="selected" <?php } ?>>Editar</option>
			<option value="enviar" <?php if($accion=='enviar'){?> selected="selected" <?php } ?>>Enviar</option>
			<option value="estado" <?php if($accion=='estado'){?> selected="selected" <?php } ?>>Estado</option>
			<option value="nuevo" <?php if($accion=='nuevo'){?> selected="selected" <?php } ?>>Nuevo</option>	
			<option value="upload" <?php if($accion=='upload'){?> selected="selected" <?php } ?>>Upload</option>
		</select>			
		</td>
		<td align="center" width="200">
		<select name="usuario" class="texto" style="width:200px;" onchange="submit();">
		<option value="">Usuario</option>
		<?php $s_1 = "Select * from empleados where activo='1' order by apellidos";
		   $r_1 = mysql_query($s_1);
		   while($d_1 = mysql_fetch_array($r_1)) { ?>
		   	<option value="<?php echo $d_1['usuario'];?>" <?php if($d_1['usuario']==$usuario) {?> selected="selected"<?php }?>>
					<?php echo $d_1['apellidos']." ".$d_1['nombre'];?></option>
		<?php } ?>	
		</select>			
		</td>
		<td align="center" width="400">Query</td>
	</tr>
</thead>	
<?php $s_2 = "select * from log_sistema where fecha>='$fechai' and fecha<='$fechaf' and accion like '$accion' and usuario like ";
      $s_2.= "'$usuario' and seccion like '$seccion' order by fecha desc, hora desc";
      $r_2 = mysql_query($s_2);
   while($d_2=mysql_fetch_array($r_2)) {  ?>
 <tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $d_2['fecha'];?></td>
	<td align="center"><?php echo $d_2['hora'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['seccion'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['accion'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_2['usuario'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo substr(html_entity_decode($d_2['consulta'],ENT_QUOTES,"UTF-8"),0,80);?></td>
</tr>		
<?php } ?>
</tbody>
</table>
<?php } ?>