<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function exportar(tipo) {
	form1.action='excel_reportes.php?op='+tipo;
	form1.submit();	
	form1.action='?op=listado';
}

function search(tipo) {
	form1.action='?op='+tipo+'&boton=1';
	form1.submit();	
}

function filtro_oes(){
	form1.action='?op=listado';
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_extras'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_extras',$op); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td>&nbsp;
		<!--Todo el contenido de cada página--->
		<?php menu_interno($op);
		   switch($op) {
			case "muestras"		:	muestras($fechai,$fechaf,$pagina,$boton); break;							
			case "corto"		:	corto($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina,$boton); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno($op) { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<?php if($op=="corto"){?>
		<td class="titulo" width="310">REPORTE PRELIMINAR CORTO</td>
		<td align="left">&nbsp;
		<span title="header=[&nbsp;&nbspReporte Corto] body=[Este es el reporte preliminar corto donde se muestra 
        el estado de las validaciones de inventarios hechas en el día seleccionado por cada número de parte.<br><br>
        Puede imprimir este reporte exportándolo directamente a excel.<br><br>Usted puede revisar el estado de cada nivel 
        requerido en base a las banderas:<br><br><table align='center' border='0' cellspacing='2'>
        <tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_orange.gif'></td><td width=200>&nbsp;Firmas que están pendientes</td>
        </tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_green.gif'></td><td>&nbsp;Firmas aprobadas</td></tr>
        <tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_red.gif'></td><td>&nbsp;Firmas rechazadas</td></tr></table><br>]">
        <img src="../imagenes/question.gif" width="20" height="20"></span>	
		</td>	
	<?php } else { ?>
    	<td class="titulo" width="700">REPORTE CORTO MUESTRAS Y/O PRUEBAS (Cod. 023-2, 022-2, 050-3, 050-4)</td>
		<td align="left">&nbsp;
		<span title="header=[&nbsp;&nbspReporte Corto Muestras y/o Pruebas] body=[Este es el reporte corto donde se muestran
        todos los folios que contengan los códigos (023-2, 022-2, 050-3, 050-4) o en los comentarios que tenga Muestra(s), Prueba(s), Destructiv@(s).<br><br>
        Puede imprimir este reporte exportándolo directamente a excel.<br>]">
        <img src="../imagenes/question.gif" width="20" height="20"></span>	
		</td>
    <?php } ?>
</tr>
</table></div><hr>
<?php } 	

function corto($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina,$boton) { 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");
	if(!$pagina) $pagina = 1; 
	if(count($proy_add)>0) {
		for($i=0;$i<count($proy_add);$i++) {
		$s_1 = "insert into filtros values('','proyectos','$proy_add[$i]','$_SESSION[IDEMP]')";
		$r_1 = mysql_query($s_1); } }
	if(count($proy_del)>0) {
		for($i=0;$i<count($proy_del);$i++) {
		$s_1 = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del[$i]'"; 
		$r_1 = mysql_query($s_1); }	} ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables. Puede personalizar este reporte y conservar los cambios siempre que inicie sesión.</div>
<form action="?op=corto" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
    </tr>
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
	<td align="center">
    <table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr><td align="left">
	<?php $r_1 = mysql_query(get_proyectos_out()); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_add[]" multiple="multiple" class="texto" id="proy_add_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center"><input type="button" value="<< >>" onclick="submit()" style="width:50px;" class="submit"></td>
	<td align="left">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del[]" multiple="multiple" class="texto" id="proy_del_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
    </tr></table></td>
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[0]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[0]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr> 
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['1'];?>" name="buscar[1]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[1]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[1]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>
	</tr>    
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['2'];?>" name="buscar[2]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[2]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[2]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr>         
</table><br>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="center" valign="middle">
    	<input type="button" value="Buscar" class="submit" name="boton" onclick="search('corto');" <?php if($boton==1){?> disabled='disabled' <?php } ?>></td>
    <td width="150" align="center" valign="middle">
		<input type="button" value="Exportar" class="submit" onclick="exportar('corto');"></td>
</tr>
</table></form>

<?php 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");

    $s_f = "select folios.*, partes.no_parte, partes.cantidad, partes.padre, aut_bitacora.fecha, aut_bitacora.hora from scrap_partes as partes, scrap_folios as folios, ";
	$s_f.= "autorizaciones, numeros_parte, aut_bitacora where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and ";
	$s_f.= "partes.no_parte = numeros_parte.nombre and aut_bitacora.no_folio = folios.no_folio and aut_bitacora.depto='inv' and aut_bitacora.status='1' "; 
	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (folios.fecha>='$fechai' and folios.fecha<='$fechaf') "; } 
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); }
	$s_f.= "group by partes.id order by folios.no_folio asc"; 
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1);
	
      $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
	  $ruta.= "&buscar[1]=$buscar[1]&filtros[1]=$filtros[1]&buscar[2]=$buscar[2]&filtros[2]=$filtros[2]";
	  
if($tot>0) { ?>     
<table align="center" border="0" class="texto" cellpadding="0" cellspacing="0">
<tr>
	<td width="110" align="center" bgcolor="#BDBDBD" class="link_paginas"><?php echo $tot;?> Registros</td>
	<td align="center" valign="top" bgcolor="#BDBDBD">
    <table align="center" border="0" class="texto" cellpadding="0" cellspacing="2">
	<?php while($i<=$pags) { 
		if($j>=30) { echo "</tr>"; $j=0; }
	    if($j==0)  { echo "<tr height='20'>"; } 
		if($pagina==$i) { $color = '#FFBF00'; } else { $color = '#F2F2F2'; } ?>
    <td width="30" align="center" bgcolor="<?php echo $color;?>">
    	<a href="?op=listado<?php echo $ruta;?>&pagina=<?php echo $i;?>" class="link_paginas"><?php echo $i;?></a></td>
	<?php $i++; $j++; } ?>
    </table>
    </td>
</tr>    
</table><br><?php } ?>
      
<table align="center" class="tabla">
<tr bgcolor="#E6E6E6" height="20">
<td rowspan="2">&nbsp;</td>
	<td align="center" rowspan="2">Folio</td>
    <td align="center" rowspan="2">No. Parte</td>
    <td align="center" rowspan="2">Qty</td>
    <td align="center" rowspan="2">Fecha creación</td>
    <td align="center" rowspan="2">Hora creación</td>
	<td align="center" colspan="3">Autorización</td>
</tr>
<tr bgcolor="#E6E6E6" height="20">	
	<td align="center" width="30">Inv</td>
    <td align="center" width="30">Fecha</td>
    <td align="center" width="30">Hora</td>
</tr>
</thead>
<tbody>
<?php $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  
	  $s_f.= " limit $ini_, 100";
	  $r_1 = mysql_query($s_f); 
      while($d_1=mysql_fetch_array($r_1)) { 
		if($d_1['activo']=='1') { $qty = 0+$qty+$d_1['cantidad'];  }   
		echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">"; ?>
	<td><a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
        	<img src="../imagenes/history.gif" border="0" alt="Historial"></a></td>   
    <td align="center" class="small"><?php echo $d_1['no_folio'];?></td>   
    <td align="left" class="small"><?php echo $d_1['no_parte'];?></td>   
    <td align="center" class="small"><?php echo $d_1['cantidad'];?></td> 
    <?php $a_folio = substr($d_1['timer'],0,4); $m_folio = substr($d_1['timer'],4,2); $d_folio = substr($d_1['timer'],6,2); 
	$h_folio = substr($d_1['timer'],8,2); $min_folio = substr($d_1['timer'],10,2); $seg_folio = substr($d_1['timer'],12,2);
	$fecha_folio = date("d-m-Y",mktime($h_folio,$min_folio,$seg_folio,$m_folio,$d_folio,$a_folio));
	$hora_folio = date("H:i:s",mktime($h_folio,$min_folio,$seg_folio,$m_folio,$d_folio,$a_folio)); ?>
    <td align="center" class="small"><?php echo $fecha_folio;?></td>   
    <td align="center" class="small"><?php echo $hora_folio;?></td>           
	<td align="center" class="small"><?php echo get_bandera("inv",$d_1['no_folio']);?></td>
    <?php $s_i = "select fecha, hora from aut_bitacora where depto='inv' and status='1' and fecha!='0000-00-00' and no_folio='$d_1[no_folio]'";
		  $r_i = mysql_query($s_i);
		  $d_i = mysql_fetch_array($r_i);
		  $fecha_inv = $d_i['fecha']; $hora_inv = $d_i['hora'];?>
    <td align="center"><?php echo $fecha_inv;?></td>
    <td align="center"><?php echo $hora_inv;?></td>
</tr>
<?php } ?>
<tr bgcolor="#E6E6E6">
		<td colspan="3">&nbsp;</td>
   		<td align='right' class='naranja'><?php echo "$qty&nbsp;"; ?></td>
		<td align="center" colspan="5">&nbsp;</td>	 		
	</tr>
</tbody>
</table><br><br>
<?php echo "<script>form1.boton.disabled=false;</script>"; } 

function muestras($fechai,$fechaf,$pagina,$boton) { 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");
	if(!$pagina) $pagina = 1;  ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables. 
Puede personalizar este reporte y conservar los cambios siempre que inicie sesión.</div>
<form action="?op=muestras" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
    </tr>
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
</tr>
</table><br>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="center" valign="middle">
    	<input type="button" value="Buscar" class="submit" name="boton" onclick="search('muestras');" <?php if($boton==1){?> disabled='disabled' <?php } ?>></td>
    <td width="150" align="center" valign="middle">
		<input type="button" value="Exportar" class="submit" onclick="exportar('muestras');"></td>
</tr>
</table></form>

<?php 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");

	$s_f = "select folios.no_folio, folios.comentario, folios.codigo_scrap ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte ";
	$s_f.= "where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and partes.no_parte = numeros_parte.nombre and "; 
	$s_f.= "((codigo_scrap = '023-2' or codigo_scrap = '022-2' or codigo_scrap = '050-3' or codigo_scrap = '050-4') or (comentario like '%muestra%' or ";
	$s_f.= "comentario like '%prueba%' or comentario like '%destructiv%')) ";
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and fecha BETWEEN '$fechai' and '$fechaf' "; }
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; } 
	
	$s_f.= "group by partes.id order by folios.no_folio asc";
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1);
	
	
   /* $s_f = "select no_folio, comentario, codigo_scrap from scrap_folios as folios where (codigo_scrap = '023-2' or codigo_scrap = '022-2' or codigo_scrap = '050-3' or  "; 
	$s_f.= "codigo_scrap = '050-4') or (comentario like '%muestra%' or comentario like '%prueba%' or comentario like '%destructiv%') ";
	$s_f .= "and fecha BETWEEN '$fechai' and '$fechaf' order by no_folio asc"; 
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1);*/
	
      $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf";
	  
if($tot>0) { ?>     
<table align="center" border="0" class="texto" cellpadding="0" cellspacing="0">
<tr>
	<td width="110" align="center" bgcolor="#BDBDBD" class="link_paginas"><?php echo $tot;?> Registros</td>
	<td align="center" valign="top" bgcolor="#BDBDBD">
    <table align="center" border="0" class="texto" cellpadding="0" cellspacing="2">
	<?php while($i<=$pags) { 
		if($j>=30) { echo "</tr>"; $j=0; }
	    if($j==0)  { echo "<tr height='20'>"; } 
		if($pagina==$i) { $color = '#FFBF00'; } else { $color = '#F2F2F2'; } ?>
    <td width="30" align="center" bgcolor="<?php echo $color;?>">
    	<a href="?op=listado<?php echo $ruta;?>&pagina=<?php echo $i;?>" class="link_paginas"><?php echo $i;?></a></td>
	<?php $i++; $j++; } ?>
    </table>
    </td>
</tr>    
</table><br><?php } ?>
      
<table align="center" class="tabla">
<tr bgcolor="#E6E6E6" height="20">
	<td>&nbsp;</td>
	<td align="center">Folio</td>
    <td align="center">Cod. Scrap</td>
    <td align="center">Por qué 1</td>
	<td align="center">Por qué 2</td>
	<td align="center">Por qué 3</td>
	<td align="center">Por qué 4</td>
	<td align="center">Por qué 5</td>
    <td align="center">Comentarios</td>
</tr>
</thead>
<tbody>
<?php $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  
	  $s_f.= " limit $ini_, 100";
	  $r_1 = mysql_query($s_f); 
      while($d_1=mysql_fetch_array($r_1)) { 
		echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">"; ?>
	<td><a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
        	<img src="../imagenes/history.gif" border="0" alt="Historial"></a></td>   
    <td align="center" class="small"><?php echo $d_1['no_folio'];?></td> 
    <td align="center" class="small"><?php echo $d_1['codigo_scrap'];?></td> 
    <?php $s_i = "select * from scrap_porques where no_folio='$d_1[no_folio]'";
	$r_i = mysql_query($s_i);
	$d_i = mysql_fetch_array($r_i); ?>
    <td align="left" class="small" width="120"><?php echo $d_i['porque_1'];?></td>
    <td align="left" class="small" width="120"><?php echo $d_i['porque_2'];?></td>
    <td align="left" class="small" width="120"><?php echo $d_i['porque_3'];?></td>
    <td align="left" class="small" width="120"><?php echo $d_i['porque_4'];?></td>
    <td align="left" class="small" width="120"><?php echo $d_i['porque_5'];?></td>  
    <td align="left"><?php echo $d_1['comentario'];?></td>
</tr>
<?php } ?>
</tbody>
</table><br><br>
<?php echo "<script>form1.boton.disabled=false;</script>"; }?>