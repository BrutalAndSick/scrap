<?php include("../header.php"); 
      include("funciones.php"); ?>
<script>
function validar(destino) {
var lleno = 0; 
	if(form1.fechai.value!='' && form1.fechaf.value!='') { lleno++; }
	if(form1.buscar[0].value!='' && form1.filtros[0].value!='') { lleno++; }
	if(form1.buscar[1].value!='' && form1.filtros[1].value!='') { lleno++; }
	if(form1.buscar[2].value!='' && form1.filtros[2].value!='') { lleno++; }
	if(lleno<=0) { 
		alert('Debe seleccionar al menos un filtro o un rango de fechas'); 
		return; }
	else { 
		if(destino=='excel') { 
			form1.action='excel_reportes.php?op=general';
			form1.submit();	
			form1.action='?op=listado';	}
		else { 
			form1.action='?op=reporte';	
			form1.submit(); }	
	}		
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','general'); ?></td>
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
		<?php menu_interno();

		   switch($op) {
			case "listado"		:	listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina); break;							
			default				:	personalizar("general");
									listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="310">REPORTE GENERAL DE SCRAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte Preliminar de Scrap] body=[Este es el reporte preliminar donde se muestra todo el estado de las validaciones hechas en el día seleccionado por cada uno de los departamentos.<br><br>Puede imprimir este reporte exportándolo directamente a excel.<br><br>Usted puede revisar el estado de cada nivel requerido en base a las banderas:<br><br><table align='center' border='0' cellspacing='2'><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_orange.gif'></td><td width=200>&nbsp;Firmas que están pendientes</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_green.gif'></td><td>&nbsp;Firmas aprobadas</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_red.gif'></td><td>&nbsp;Firmas rechazadas</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	

function listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina) { 
	if(!$fechai) $fechai = date("Y-m-d");
	if(!$fechaf) $fechaf = date("Y-m-d");
	if(!$pagina) $pagina = 1; 
	if($proy_add!='') {
		$s_1 = "insert into filtros values('','proyectos_','$proy_add','$_SESSION[IDEMP]')";
		$r_1 = mysql_query($s_1); }
	if($proy_del!='') {
		if($proy_del=='del_all') { 
			$s_1 = "delete from filtros where filtro='proyectos_' and id_emp='$_SESSION[IDEMP]'"; 
			$r_1 = mysql_query($s_1); }
		else { 	
			$s_1 = "delete from filtros where filtro='proyectos_' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; 
			$r_1 = mysql_query($s_1); }
	}			

	$s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, ";
	$s_f.= "partes.docto_sap, partes.deficit, partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, numeros_parte where folios.no_folio = partes.no_folio and partes.no_parte = numeros_parte.nombre "; 
	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	
	$s_ = "select valor from filtros where id_emp='$_SESSION[IDEMP]' and filtro='proyectos_'";
	$r_ = mysql_query($s_); 
	if(mysql_num_rows($r_)>0) { $s_f .= "and ( "; 
	while($d_=mysql_fetch_array($r_)) {
		$s_f.= "folios.id_proyecto = '$d_[valor]' or "; }
	$s_f = substr($s_f,0,-3)." ) ";	}
  	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1); ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables. Puede personalizar este reporte y conservar los cambios siempre que inicie sesión.</div>
<form action="?op=listado" method="post" name="form1">
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
    <tr><td align="center">
	<?php $r_1 = mysql_query(get_proyectos_out($division)); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_add" class="texto" style="width:180px;" onchange="submit();">
		<option value="">Sin filtro (<?php echo $n_1;?>)</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del" class="texto" style="width:180px;" onchange="submit();">
	  	<option value="">En filtro (<?php echo $n_1;?>)</option>	
        <option value="del_all" class="quitar">Quitar Todos</option>
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
    	<input type="button" value="Buscar" class="submit" onclick="validar('reporte');"></td>
    <td width="150" align="center" valign="middle">
 		<input type="button" value="Exportar" class="submit" onclick="validar('excel');"></td>
    <td width="150" align="center" valign="middle">   
	 <a class='personalizar' href='personalizar.php?op=personalizar&reporte=general'>
     <img src="../imagenes/personalizar.png" border="0" width="120"></a></td>
</tr>
</table></form>

<?php $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
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
<?php $s_e = "select encabezados.nombre, reportes.campo from encabezados, reportes where reportes.id_emp='$_SESSION[IDEMP]' and ";
      $s_e.= "reportes.reporte = 'general' and reportes.pantalla!='0' and reportes.campo=encabezados.campo ";
	  $s_e.= "order by pantalla, nombre";
      $r_e = mysql_query($s_e); $i=0;
	  while($d_e=mysql_fetch_array($r_e)) { 
		if($d_e['campo']=='cod_original') { $original=1; ?>    
        <td align="center" colspan="3"><?php echo $d_e['nombre'];?></td>
        <?php } else { ?>   
        <td align="center" rowspan="2"><?php echo $d_e['nombre'];?></td><?php } ?>
	  <?php $campos[$i] = $d_e['campo']; $i++; } ?>
	<td align="center" colspan="9">Autorización</td>
</tr>
<tr bgcolor="#E6E6E6" height="20">
    <?php if($original=='1') { ?>
  	<td align="center" width="150">Defecto</td>
	<td align="center" width="120">Tecnología</td>
	<td align="center" width="70">Código</td>  
    <?php } ?>
	<td align="center" width="30">LO</td>
	<td align="center" width="30">LOA</td>
	<td align="center" width="30">LPL</td>
	<td align="center" width="30">FFM</td>
	<td align="center" width="30">FFC</td>
	<td align="center" width="30">Prod</td>
	<td align="center" width="30">SQM</td>
    <td align="center" width="30">Finanzas</td>
	<td align="center" width="30">Inv</td>
</tr>
</thead>
<tbody>
<?php $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  
	  $s_f.= " limit $ini_, 100";
	  $r_1 = mysql_query($s_f); 
      while($d_1=mysql_fetch_array($r_1)) { 
		if($d_1['activo']=='1') { $qty = 0+$qty+$d_1['cantidad']; $cost = 0+$cost+$d_1['costo_total']; }   
		echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">";
		for($i=0;$i<count($campos);$i++) { 
			switch($campos[$i]) {
				case "costo_total"	:	echo "<td align='right' class='small'>$ ".number_format($d_1['costo_total'],2)."&nbsp;</td>";
										break;
				case "cod_original"	:	$original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
										echo "<td align='left' class='small'>&nbsp;".$original['defecto']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['estacion']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['codigo']."</td>"; break;
				case "docto_sap"	:	if($d_1['deficit']=='1') {
											echo "<td align='left' class='small'>&nbsp;Déficit de Stock</td>"; }
										else { echo "<td align='left' class='small'>&nbsp;".$d_1['docto_sap']."</td>"; } break;	
				case "info"			:	echo "<td align='left' class='small'>&nbsp;".$d_1['info_1'].$d_1['info_2']."</td>"; break;
				default				:	echo "<td align='left' class='small'>&nbsp;".$d_1[$campos[$i]]."</td>"; break;
			}	
		} ?>
	<td align="center" class="small"><?php echo get_bandera("lo",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("loa",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("lpl",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("ffm",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("ffc",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("prod",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("sqm",$d_1['no_folio']);?></td>
    <td align="center" class="small"><?php echo get_bandera("fin",$d_1['no_folio']);?></td>
	<td align="center" class="small"><?php echo get_bandera("inv",$d_1['no_folio']);?></td>
</tr>
<?php } ?>
<tr bgcolor="#E6E6E6">
<?php for($i=0;$i<count($campos);$i++) {
   		if($campos[$i]=='costo_total')  { echo "<td align='right' class='naranja'>$ ".number_format($cost,2)."&nbsp;</td>"; } 
   		elseif($campos[$i]=='cantidad') { echo "<td align='right' class='naranja'>$qty&nbsp;</td>"; }
		else { echo "<td align='right' class='small'>&nbsp;</td>"; } 
} ?>
	<td align="center" colspan="9">&nbsp;</td>	 		
</tr>
</tbody>
</table><br><br>
<?php } 


function get_proyectos_out($division) {
	$s_1 = "select * from proyectos where not exists (select * from filtros where filtro='proyectos_' and ";
	$s_1.= "id_emp='$_SESSION[IDEMP]' and proyectos.id = filtros.valor) and activo!='2' ";		  
	$s_1.= "order by nombre";
	return $s_1;			
}


function get_proyectos_in() {
	$s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.id = filtros.valor and ";
	$s_1.= "filtros.filtro = 'proyectos_' and filtros.id_emp='$_SESSION[IDEMP]' order by nombre";	
	return $s_1;	
} ?>