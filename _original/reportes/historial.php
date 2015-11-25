<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function validar(destino) {
var lleno = 0; 
	if(form1.fechai.value!='' && form1.fechaf.value!='') { lleno++; }
	if(form1.buscar[0].value!='' && form1.filtros[0].value!='') { lleno++; }
	if(form1.buscar[1].value!='' && form1.filtros[1].value!='') { lleno++; }
	if(form1.buscar[2].value!='' && form1.filtros[2].value!='') { lleno++; }
	if(lleno<=0) { 
		alert('Seleccione un rango de fechas'); 
		return; }
	else { 
		if(destino=='excel') { 
			form1.action='excel_reportes.php?op=historial_1';
			form1.submit();	
			form1.action='?op=definitivo';	}
		else { 
			form1.action='?op=definitivo';	
			form1.submit(); }	
	}		
}

function exportar() {
	form1.action = "excel_reportes.php?op=historial_2";
	form1.submit();
	form1.action = "?op=preliminar";
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','historial'); ?></td>
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
			case "definitivo"	:	definitivo($anio,$fechai,$fechaf,$proy_add,$proy_del,$division,$tipo,$buscar,$filtros); break;
			case "preliminar"	:	preliminar($anio,$fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina); break;							
			default				:	personalizar("preliminar");
									preliminar($anio,$fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina); break;
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
	<td class="titulo" width="310">REPORTE HISTÓRICO DE SCRAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte Histórico de Scrap] body=[Este es el reporte histórico donde se muestra todo scrap aprobado en el día seleccionado por cada uno de los departamentos para años anteriores.<br><br>Puede imprimir este reporte exportándolo directamente a excel.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
	<td><a href="?op=preliminar" class="menuLink">Reporte Preliminar</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=definitivo" class="menuLink">Reporte Definitivo</a></td>
</tr>
</table></div><hr>
<?php } 	



function preliminar($anio,$fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$pagina) { 
	if(!$anio)   $anio   = date("Y")-1;
	if(!$fechai) $fechai = date("Y-m-d");
	if(!$fechaf) $fechaf = date("Y-m-d");
	if(!$pagina) $pagina = 1; 
	if($proy_add!='') {
		$s_1 = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')";
		$r_1 = mysql_query($s_1); }
	if($proy_del!='') {
		if($proy_del=='del_all') { 
			$s_1 = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'"; 
			$r_1 = mysql_query($s_1); }
		else { 	
			$s_1 = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; 
			$r_1 = mysql_query($s_1); }
	} ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables. Puede personalizar este reporte y conservar los cambios siempre que inicie sesión.</div>
<form action="?op=preliminar" method="post" name="form1">
<input type="hidden" name="reporte" value="preliminar">
<table align="center" class="tabla">
	<tr height="20">
    	<td align="center" width="80" bgcolor="#E6E6E6">Año</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
    </tr>
    <tr>
	<td align="center">
    	<select name="anio" class="texto" style="width:80px;" onchange="submit();">
        	<option value=""></option>
            <?php for($i=2011;$i<=date("Y")+1;$i++) { ?>
            <option vale="<?php echo $i;?>" <?php if($anio==$i){?> selected="selected"<?php }?>><?php echo $i;?></option>
            <?php } ?>
        </select></td>  
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
	<?php $r_1 = mysql_query(get_proyectos_out()); 
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
    	<input type="button" value="Buscar" class="submit" name="boton" onclick="submit();"></td>
    <td width="150" align="center" valign="middle">
 	<input type="button" value="Exportar" class="submit" onclick="exportar();"></td>
    <td width="150" align="center" valign="middle">   
	 <a class='personalizar' href='personalizar.php?op=personalizar&reporte=preliminar'>
     <img src="../imagenes/personalizar.png" border="0" width="120"></a></td>
</tr>
</table></form>

<?php 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");

    $s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, partes.docto_sap, partes.deficit, ";
	$s_f.= "partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub from ".$anio."_scrap_partes as partes, ".$anio."_scrap_folios as folios, ";
	$s_f.= $anio."_autorizaciones as autorizaciones where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio "; 
	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); }
	$s_f.= "group by partes.id order by folios.no_folio asc"; 
	$r_1 = mysql_query($s_f); 
	$tot = mysql_num_rows($r_1);
	
      $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&anio=$anio&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
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
<?php $original = 0;
      $s_e = "select encabezados.nombre, reportes.campo from encabezados, reportes where reportes.id_emp='$_SESSION[IDEMP]' and ";
      $s_e.= "reportes.reporte = 'preliminar' and reportes.pantalla!='0' and reportes.campo=encabezados.campo ";
	  $s_e.= "order by pantalla, nombre";
      $r_e = mysql_query($s_e); $i=0;
	  while($d_e=mysql_fetch_array($r_e)) { 
		if($d_e['campo']=='cod_original') { $original=1; ?>    
        <td align="center" colspan="3"><?php echo $d_e['nombre'];?></td>
        <?php } else { ?>   
        <td align="center" rowspan="2"><?php echo $d_e['nombre'];?></td><?php } ?>
	  <?php $campos[$i] = $d_e['campo']; $i++; } ?>
	<td align="center" colspan="10">Autorización</td>
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
    <td align="center" width="30">ESP</td>
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
										else { 
											if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo "<td align='left' class='small'>&nbsp;".$d_1['docto_sap']."</td>"; } 
											else { echo "<td align='left' class='small'>&nbsp;</td>"; } } break;	
				case "info"			:	echo "<td align='left' class='small'>&nbsp;".$d_1['info_1'].$d_1['info_2']."</td>"; break;
				default				:	echo "<td align='left' class='small'>&nbsp;".$d_1[$campos[$i]]."</td>"; break;
			}	
		} ?>
	<td align="center" class="small"><?php echo get_bandera_old("lo",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("loa",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("lpl",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("ffm",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("ffc",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("prod",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("sqm",$d_1['no_folio'],$anio);?></td>
    <td align="center" class="small"><?php echo get_bandera_old("fin",$d_1['no_folio'],$anio);?></td>
    <td align="center" class="small"><?php echo get_bandera_old("esp",$d_1['no_folio'],$anio);?></td>
	<td align="center" class="small"><?php echo get_bandera_old("inv",$d_1['no_folio'],$anio);?></td>
</tr>
<?php } ?>
<tr bgcolor="#E6E6E6">
<?php for($i=0;$i<count($campos);$i++) {
   		if($campos[$i]=='costo_total')  { echo "<td align='right' class='naranja'>$ ".number_format($cost,2)."&nbsp;</td>"; } 
   		elseif($campos[$i]=='cantidad') { echo "<td align='right' class='naranja'>$qty&nbsp;</td>"; }
		else { echo "<td align='right' class='small'>&nbsp;</td>"; } 
} ?>
	<td align="center" colspan="11">&nbsp;</td>	 		
</tr>
</tbody>
</table><br><br>
<?php }


function get_bandera_old($depto,$folio,$anio) { 
	$s_ = "select status from ".$anio."_autorizaciones where no_folio='$folio' and ";
	if($depto=='esp') { $s_.= "(depto='esp_1' or depto='esp_2') "; }
	else { $s_.= "depto='$depto' "; } $s_.= "order by status desc";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	switch($d_['status']) {	
			case "0"	:	$s_1 = "select status from ".$anio."_aut_bitacora where no_folio='$folio' and depto='$depto' order by id desc";
							$r_1 = mysql_query($s_1); $d_1 = mysql_fetch_array($r_1);
							$s_2 = "update ".$anio."_autorizaciones set status='$d_1[status]' where no_folio='$folio' and depto='$depto'";
							$r_2 = mysql_query($s_2);
							$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones3&folio=$folio&anio=$anio'>";
							$img.= "<img src='../imagenes/flag_orange.gif' style='cursor:hand'; border=0></a>"; break;			
			case "1"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones3&folio=$folio&anio=$anio'>";
			  				$img.= "<img src='../imagenes/flag_green.gif' style='cursor:hand'; border=0></a>"; break;			
			case "2"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones3&folio=$folio&anio=$anio'>";
							$img.= "<img src='../imagenes/flag_red.gif' style='cursor:hand'; border=0></a>"; break;
			case "3"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones3&folio=$folio&anio=$anio'>";
							$img.= "<img src='../imagenes/cross.png' style='cursor:hand'; border=0></a>"; break;				
			case "NA"	:	$img = "NA"; break;	
			default		:	$img = "NA"; break;
	}
	return $img;	
}


function definitivo($anio,$fechai,$fechaf,$proy_add,$proy_del,$division,$tipo,$buscar,$filtros) {
if(!$anio)	   $anio	 = date("Y")-1;
if(!$fechai)   $fechai   = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-1));
if(!$fechaf)   $fechaf   = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-1));
	
if($proy_add!='') {
	$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')";
	$r_ = mysql_query($s_); }
if($proy_del!='') {
	if($proy_del=='del_all') { 
		$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'"; 
		$r_ = mysql_query($s_); }
	else { 
	$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; 
	$r_ = mysql_query($s_); }	
} ?>
    
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=definitivo" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="80" bgcolor="#E6E6E6">Año</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
        <td align="center" width="100" bgcolor="#E6E6E6">División</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Tipo Reporte</td>
    </tr>
    <tr>
	<td align="center">
    	<select name="anio" class="texto" style="width:80px;" onchange="submit();">
        	<option value=""></option>
            <?php for($i=2011;$i<=date("Y")+1;$i++) { ?>
            <option vale="<?php echo $i;?>" <?php if($anio==$i){?> selected="selected"<?php }?>><?php echo $i;?></option>
            <?php } ?>
        </select></td>            
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
	<?php $r_1 = mysql_query(get_proyectos_out()); 
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
	<td align="center">
	<?php $s_1 = "select divisiones.id, divisiones.nombre from divisiones, proyectos where divisiones.activo!='2' and divisiones.id = proyectos.id_division ";
			$s_2 = "select id_division from autorizadores where id_emp='$_SESSION[IDEMP]'";
			$r_2 = mysql_query($s_2); $i=0;
			if(mysql_num_rows($r_2)>0) {
				$s_1.= "and (";
				while($d_2=mysql_fetch_array($r_2)) { 
					if($d_2['id_division']!='0' && $d_2['id_division']!='%') { 
						$s_1.= "id_division='$d_2[id_division]' or ";
						$divisiones[$i] = $d_2['id_division']; $i++; }
				} $s_1 = substr($s_1,0,-4).")"; }	
		  $s_1.= "group by divisiones.id order by nombre";
	      $r_1 = mysql_query($s_1); ?>
	<select name="division" class="texto" style="width:120px;">
	  	<option value="" <?php if($division==""){?> selected="selected" <?php } ?>></option>
        <option value="%" <?php if($division=="%"){?> selected="selected" <?php } ?>>Todas</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
		<?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>
	<td align="center">
	  <select name="tipo" class="texto" style="width:100px;">
		<option value="" <?php if($tipo==""){?> selected="selected" <?php } ?>></option>
        <option value="%" <?php if($tipo=="%"){?> selected="selected" <?php } ?>>Todos</option>
        <option value="1" <?php if($tipo=="1"){?> selected="selected" <?php } ?>>MB1A con OI</option>
        <option value="2" <?php if($tipo=="2"){?> selected="selected" <?php } ?>>MB1A sin OI</option>
        <option value="3" <?php if($tipo=="3"){?> selected="selected" <?php } ?>>ZSCR</option>        
	</select></td>
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[2]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[2]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr>         
</table>
<div align="center" class="texto"><br>
	<input type="button" value="Buscar" class="submit" onclick="validar('definitivo');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="validar('excel');">
</div><br></form>

<?php if($anio!='' && $fechai!='' && $fechaf!='' && $tipo!='' && $division!='') {
    $s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, partes.docto_sap, partes.deficit, ";
	$s_f.= "partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub from ".$anio."_scrap_partes as partes, ".$anio."_scrap_folios as folios, ";
	$s_f.= $anio."_autorizaciones as autorizaciones where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and folios.activo='1' and "; 
	$s_f.= "folios.status='1' and partes.docto_sap!='0' and partes.docto_sap!='' ";
	if($division=='%') { 
		if(count($divisiones)>0) {
			$s_f.= " and (";
			for($i=0;$i<count($divisiones);$i++) {
				$s_f.= " id_division='$divisiones[$i]' or "; 
			} $s_f = substr($s_f,0,-4).") "; 
		}	
	}
	else { $s_f.= " and id_division='$division' "; } 
	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); } 
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1); ?>
    
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center">No.Item</td>
    <td align="center">Fecha</td>
	<td align="center">Txs SAP</td>
	<td align="center">Mov</td>
	<td align="center">Código Scrap</td>
	<td align="center">Cod. Causa Original</td>        
	<td align="center">O.I.</td>
	<td align="center">Parte Padre</td>
	<td align="center">Batch ID</td>
	<td align="center">No.Parte</td>
	<td align="center">Cantidad</td>
	<td align="center">Reason Code</td>
	<td align="center">Descripción</td>
	<td align="center">Info.Obl.</td>
	<td align="center">No.Docto.SAP</td>
	<td align="center">Tipo Material</td>
	<td align="center">Tipo Sub</td>
	<td align="center">Valor</td>
</tr>
</thead>
<tbody>
<?php 
	if($tipo=='1' || $tipo=='%') { //Sección 1 reporte de TXS SAP con MB1A con Orden Interna
		$s_1 = $s_f." and txs_sap='MB1A' and orden_interna!='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre"; 
 		$r_1 = mysql_query($s_1); 
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"18\"></td></tr>";
    while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo'];?></td>
        <td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB') { echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
    </tr>	
	<?php } } } if($tipo=='2' || $tipo=='%') { //Sección 2 reporte de TXS SAP con MB1A sin Orden Interna
		$s_2 = $s_f." and txs_sap='MB1A' and orden_interna='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_2); 
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"18\"></td></tr>";	
    while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>    	<td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
        <td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB') { echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
	</tr>	
	<?php } } } if($tipo=='3' || $tipo=='%') { //Sección 3 reporte de TXS SAP con ZSCR
		$s_3 = $s_f." and txs_sap='ZSCR' and (orden_interna='NA' or orden_interna='0') group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_3); 
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"18\"></td></tr>";	
	while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>	
        <td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
	</tr>	
	<?php } } } ?>
</tbody>
</table><br><br>
<?php } } ?>