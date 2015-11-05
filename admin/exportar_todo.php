<?php include("../header.php"); 
	if($op=='por_plantas') { $boton = 'b_plantas'; }
	if($op=='por_areas')   { $boton = 'b_areas'; } ?>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu($boton); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu($boton,'exportar'); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<script>
function excel_plantas() {
	form1.action='excel.php?op=por_plantas';
	form1.submit();
	form1.action='?op=por_plantas';
}

function excel_areas(division,proyecto) {
	form1.action='excel.php?op=por_areas&division='+division+'&proyecto='+proyecto;
	form1.submit();
	form1.action='?op=por_areas';
}

</script>


<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td background="../imagenes/borde_izq_tabla.png">&nbsp;</td>
    <td>&nbsp;
	<!--Todo el contenido de cada página--->
	<?php switch($op) {
		case "por_plantas"		:	menu_plantas($planta,$division,$segmento,$pc,$apd,$proyecto); 
									if(($planta!='' && $division!='')) {
										reporte_por_plantas($planta,$division,$segmento,$pc,$apd,$proyecto); } break;
		case "por_areas"		:	menu_areas($division,$proyecto,$area,$tecnologia,$linea,$codigo,$causa,$defecto);
									if(($division!='' && $proyecto!='')) {
										reporte_por_areas($division,$proyecto,$area,$tecnologia,$linea,$codigo,$causa,$defecto);}
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_plantas($planta,$division,$segmento,$pc,$apd,$proyecto) { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="400">EXPORTAR	CATÁLOGOS DE PLANTAS</td>
</tr>
</table></div><hr>

<div align="center" class="aviso">Utilice los campos siguientes antes de generar el archivo.<br>Debe seleccionar la planta y la división  para ver los datos.</div>
<form action="?op=por_plantas" method="post" name="form1">
<table align="center" class="tabla">
	<tr bgcolor="#E6E6E6" height="20">
		<td align="left" width="100">&nbsp;&nbsp;Plantas</td>
        <td>
		<?php $r_1 = mysql_query("select * from plantas where activo='1' order by nombre"); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="planta" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($planta==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>
    </tr>  
    <tr bgcolor="#E6E6E6" height="20">  
		<td align="left">&nbsp;&nbsp;Divisiones</td>
        <td>
		<?php $r_1 = mysql_query("select * from divisiones where activo='1' and id_planta='$planta%' order by nombre"); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="division" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Segmentos</td>
        <td>
		<?php $s_1 = "select * from segmentos where activo='1' and id_planta='$planta' and id_division='$division' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="segmento" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($segmento==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>             
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;P.C.</td>
        <td>
		<?php $s_1 = "select * from profit_center where activo='1' and id_planta='$planta' and id_division='$division' and  ";
			  $s_1.= "id_segmento='$segmento' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="pc" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($pc==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>         
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;APD</td>
        <td>
		<?php $s_1 = "select * from apd where activo='1' and id_division='$division' and id_segmento='$segmento' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="apd" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($apd==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
	</tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Proyectos</td>
        <td>
		<?php $s_1 = "select * from proyectos where activo='1' and id_planta='$planta' and id_division='$division' and id_pc='$pc' ";
			  $s_1.= "and id_segmento='$segmento' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="proyecto" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
	</tr>    
</table>
<div align="center"><br>
	<input type="submit" value="Buscar" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Exportar" class="submit" onclick="excel_plantas();">
</div>
</form>
<?php }


function menu_areas($division,$proyecto,$area,$tecnologia,$linea,$codigo,$causa,$defecto) { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="400">EXPORTAR	CATÁLOGOS DE ÁREAS</td>
</tr>
</table></div><hr>

<div align="center" class="aviso">Si desea filtrar la información, utilice los campos siguientes antes de generar el archivo.</div>
<form action="?op=por_areas" method="post" name="form1">
<table align="center" class="tabla">
    <tr bgcolor="#E6E6E6" height="20">  
		<td align="left">&nbsp;&nbsp;Divisiones</td>
        <td>
		<?php $r_1 = mysql_query("select * from divisiones where activo='1' order by nombre"); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="division" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Proyectos</td>
        <td>
		<?php $s_1 = "select * from proyectos where activo='1' and id_division='$division' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="proyecto" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
	</tr>   
	<tr bgcolor="#E6E6E6" height="20">
		<td align="left" width="100">&nbsp;&nbsp;Áreas</td>
        <td>
		<?php $r_1 = mysql_query("select * from areas where activo='1' order by nombre"); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="area" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($area==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>
    </tr>  
    <tr bgcolor="#E6E6E6" height="20">  
		<td align="left">&nbsp;&nbsp;Tecnología</td>
        <td>
		<?php $r_1 = mysql_query("select * from estaciones where activo='1' and id_area='$area' order by nombre"); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="tecnologia" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($tecnologia==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Líneas</td>
        <td>
		<?php $s_1 = "select * from lineas where activo='1' and id_area='$area' and id_estacion='$tecnologia' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="linea" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($linea==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>             
    </tr>
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Defectos</td>
        <td>
		<?php $s_1 = "select * from defectos where activo='1' and id_area='$area' and id_estacion='$tecnologia' order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="defecto" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($defecto==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
	</tr> 
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Causas</td>
        <td>
		<?php $s_1 = "select causas.id, causas.nombre from causas, defecto_causa where activo='1' and id_defecto='$defecto' and ";
			  $s_1.= "id_causa=causas.id order by nombre";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="causa" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($causa==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>        
	</tr>     
    <tr bgcolor="#E6E6E6" height="20">    
		<td align="left">&nbsp;&nbsp;Códigos Scrap</td>
        <td>
		<?php $s_1 = "select codigo_scrap.id, codigo_scrap.codigo from codigo_scrap, causa_codigo where activo='1' and id_causa = ";
			  $s_1.= "'$causa' and id_codigo=codigo_scrap.id order by codigo";
		      $r_1 = mysql_query($s_1); 
	   	      $n_1 = mysql_num_rows($r_1); ?>
		<select name="codigo" class="texto" style="width:250px;" onchange="submit();">
			<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($codigo==$d_1['id']){?> selected="selected" <?php } ?>>
				<?php echo $d_1['codigo'];?></option><?php } ?>
		</select></td>         
    </tr>
</table>
<div align="center"><br>
	<input type="submit" value="Buscar" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Exportar" class="submit" onclick="excel_areas('<?=$division;?>','<?=$proyecto;?>');">
</div>
</form>
<?php } 	


function reporte_por_plantas($planta,$division,$segmento,$pc,$apd,$proyecto) { 
	$s_1 = "select plantas.nombre as planta, divisiones.nombre as division, segmentos.nombre as segmento, profit_center.nombre ";
	$s_1.= "as prce, apd.nombre as apd, proyectos.nombre as proyecto from plantas, divisiones, segmentos, profit_center, apd, ";
	$s_1.= "proyectos where divisiones.id_planta = plantas.id and segmentos.id_division = divisiones.id and profit_center.id_segmento";
	$s_1.= " = segmentos.id and apd.id_segmento = segmentos.id and proyectos.id_pc = profit_center.id and plantas.activo='1' and ";
	$s_1.= "divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and apd.activo='1' and proyectos.activo='1' ";
	$s_1.= "and plantas.id like '$planta%' and divisiones.id like '$division%' and segmentos.id like '$segmento%' and ";
	$s_1.= "profit_center.id like '$pc%' and apd.id like '$apd%' and proyectos.id like '$proyecto%' ";	
	$s_1.= "order by plantas.nombre, divisiones.nombre, segmentos.nombre, profit_center.nombre, apd.nombre, proyectos.nombre"; 
	$_SESSION['CONSULTA'] = $s_1; 
	$r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1); ?>

<div align="center" class="texto"><b><?=$n_1;?> Registros Encontrados</b></div>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="50">No.</td>
        <td align="center" width="150">Planta</td>
		<td align="center" width="150">División</th>
		<td align="center" width="120">Segmento</th>
		<td align="center" width="120">Profit Center</th>
		<td align="center" width="120">APD</th>
		<td align="center" width="150">Proyecto</th>
	</tr>
</thead>
<tbody>
<?php  $r_1 = mysql_query($s_1); $i=1;
	while($d_1=mysql_fetch_array($r_1)) { ?>
<tr bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['prce'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['apd'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
<?php } 


function reporte_por_areas($division,$proyecto,$area,$tecnologia,$linea,$codigo,$causa,$defecto) { 

$div = get_division_name($division);
$pry = get_proyecto_name($proyecto);

	$s_1 = "select areas.nombre as area, estaciones.nombre as estacion, lineas.nombre as linea, defectos.nombre as defecto, ";
	$s_1.= "causas.nombre as causa, codigo_scrap.codigo as codigo, codigo_scrap.orden_interna as orden from areas, estaciones, ";
	$s_1.= "lineas, defectos, defecto_causa, causas, causa_codigo, codigo_scrap, def_proyecto, est_proyecto, lineas_proy where ";
	$s_1.= "estaciones.id_area = areas.id and lineas.id_estacion = estaciones.id and defectos.id_estacion = estaciones.id and ";
	$s_1.= "defecto_causa.id_defecto = defectos.id and defecto_causa.id_causa = causas.id and causa_codigo.id_causa = causas.id and ";
	$s_1.= "causa_codigo.id_codigo = codigo_scrap.id and defectos.id = def_proyecto.id_defecto and est_proyecto.id_tecnologia = ";
	$s_1.= "estaciones.id and lineas_proy.id_linea = lineas.id and areas.activo='1' and estaciones.activo='1' and lineas.activo='1' ";
	$s_1.= "and defectos.activo='1' and causas.activo='1' and codigo_scrap.activo='1' and areas.id like '$area%' and estaciones.id ";
	$s_1.= "like '$tecnologia%' and lineas.id like '$linea%' and codigo_scrap.id like '$codigo%' and causas.id like '$causa%' and ";
	$s_1.= "defectos.id like '$defecto%' and def_proyecto.id_proyecto = '$proyecto' and est_proyecto.id_proyecto = '$proyecto' and ";
	$s_1.= "lineas_proy.id_proyecto like '$proyecto' order by areas.nombre, estaciones.nombre, lineas.nombre, defectos.nombre, ";
	$s_1.= "causas.nombre, codigo_scrap.codigo";
	$_SESSION['CONSULTA'] = $s_1;
	$r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1); ?>
    
<div align="center" class="texto"><b><?=$n_1;?> Registros Encontrados</b></div>    
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="50">No.</td>
        <td align="center" width="100">División</td>
        <td align="center" width="100">Proyecto</td>
        <td align="center" width="180">Área</td>
		<td align="center" width="150">Tecnología</td>
		<td align="center" width="90">Línea</td>
		<td align="center" width="90">Defecto</td>
		<td align="center" width="90">Relacionado a</td>
		<td align="center" width="90">Código Scrap</td>
        <td align="center" width="90">Orden Interna</td>
	</tr>
</thead>
<tbody>
<?php  $r_1 = mysql_query($s_1); $i=1; 
	while($d_1=mysql_fetch_array($r_1)) { ?>
<tr bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
	<td align="left"><?php echo $div;?></td>
	<td align="left"><?php echo $pry;?></td>
	<td align="left"><?php echo $d_1['area'];?></td>
	<td align="left"><?php echo $d_1['estacion'];?></td>
	<td align="left"><?php echo $d_1['linea'];?></td>
	<td align="left"><?php echo $d_1['defecto'];?></td>
	<td align="left"><?php echo $d_1['causa'];?></td>
	<td align="center"><?php echo $d_1['codigo'];?></td>
	<td align="center"><?php echo $d_1['orden'];?></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
<?php } 


function get_division_name($id_) {
	$s_ = "select * from divisiones where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}


function get_proyecto_name($id_) {
	$s_ = "select * from proyectos where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
} ?>