<?php include("../header.php"); 
      include('funciones.php');
	  include('filtros.php');?>
 
<script>
function validar(destino) {
	if(destino=='excel') { 
		form1.action='excel_reportes.php?op=consulta&tipo=aprobado';
		form1.submit();	
		form1.action='?op=listado';	}
	else { 
		form1.action='?op=reporte';	
		form1.submit(); }	
}
</script>	  
   
  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_consulta'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_consulta','aprobados'); ?></td>
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
				case "listado"	:	listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$pagina);
									break;
				case "reporte"	:	reporte($fechai,$fechaf,$buscar,$filtros);
									listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,'1'); break;
				default			:	$s_ = "DROP VIEW vw_reportes_".$_SESSION["IDEMP"];
									$r_ = mysql_query($s_);
									reporte($fechai,$fechaf,$buscar,$filtros);
									listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$pagina); break;
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
	<td class="titulo" width="190">SCRAP APROBADO</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbsp;Autorizaciones en Proceso] body=[Estas son sus autorizaciones pendientes de firmar por cualquiera de los departamentos. Usted puede revisar el estado de boleta de scrap en base a los colores y las banderas:<br><br><table align='center' border='0' cellspacing='2'><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_orange.gif'></td><td width=200>&nbsp;Firmas que están pendientes</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_green.gif'></td><td>&nbsp;Firmas aprobadas</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_red.gif'></td><td>&nbsp;Firmas rechazadas</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>		
</tr>
</table></div><hr>
<?php } 	


function reporte($fechai,$fechaf,$buscar,$filtros) { 
$reportar=0;

if($fechai!='' && $fechaf!='') { $reportar=1;}
if($buscar[0]!='' && $filtros[0]!='') { $reportar=1; }  
if($buscar[1]!='' && $filtros[1]!='') { $reportar=1; }  
if($buscar[2]!='' && $filtros[2]!='') { $reportar=1; }  

if($reportar=='1') { 
	$s_ = "DROP VIEW vw_reportes_".$_SESSION["IDEMP"];
	$r_ = mysql_query($s_);

    $s_1 = "select folios.no_folio, folios.fecha, sum(partes.cantidad) as cantidad_total, folios.planta, folios.division, folios.proyecto, ";
    $s_1.= "folios.profit_center, folios.area, folios.codigo_scrap, folios.reason_code, sum(partes.total) as costo_total, ";
	$s_1.= "folios.financiero, folios.archivo, folios.info_1, folios.info_2 from scrap_partes as partes, scrap_folios as folios where folios.activo='1' and ";
	$s_1.= "folios.status='1' and folios.no_folio = partes.no_folio ";	

	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_1 .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_1.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_1 = substr($s_1,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') { $s_1.= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	$s_1.= filtros_autorizador($division); $s_1.= " group by folios.no_folio order by no_folio DESC"; 
	$s_ = "CREATE OR REPLACE VIEW vw_reportes_".$_SESSION["IDEMP"]." AS ".$s_1;
	$r_ = mysql_query($s_); }
}


function listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$pagina) { 
if(!$pagina) $pagina=1;
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
}			

	$s_1 = "select count(*) as total from vw_reportes_".$_SESSION["IDEMP"];
  	if(mysql_query($s_1)) {
  	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);	$total = $d_1['total']; }?>
				   
<div align="center" class="aviso">Para ver los folios aprobados utilice al menos un filtro o rango de fechas. Haga clic en las banderas para ver detalles de autorización.</div>	
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>" />
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Incio Captura</td>
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
        <td><?php $s_1 = "select campo, nombre from encabezados where a_aprobados='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where a_aprobados='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where a_aprobados='1' order by nombre"; 
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
	<input type="button" value="Buscar" class="submit" onclick="validar('reporte');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="validar('excel');" <?php if($total<=0){?> disabled <?php } ?>>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="reason" value="1" <?php if($reason==1) {?> checked="checked"<?php } ?>>
	Mostrar Reason code
</div><br></form>

	<table align="center" border="0" class="texto">
	<tr>
		<td width="20" align="center"><img src="../imagenes/zoom.png" /></td>
    	<td width="60" align="left">Ver Boleta</td> 
        <td width="20" align="center">|</td>
		<td width="20" align="center"><img src="../imagenes/information.png" /></td>
    	<td width="40" align="left">Historial</td>      
        <td width="20" align="center">|</td>  
		<td width="20" align="center"><img src="../imagenes/attach.png" /></td>
    	<td width="50" align="left">Evidencias</td> 
        <td width="20" align="center">|</td>  
		<td width="20" align="center"><img src="../imagenes/ayuda.gif" /></td>
    	<td width="40" align="left">Detalles</td> 
        <td width="20" align="center">|</td>  
		<td width="20" align="center"><img src="../imagenes/flag_orange.gif" /></td>
    	<td width="50" align="left">Pendiente</td>  
        <td width="20" align="center">|</td>   
		<td width="20" align="center"><img src="../imagenes/flag_green.gif" /></td>
    	<td width="50" align="left">Aprobado</td>     
        <td width="20" align="center">|</td>   
		<td width="20" align="center"><img src="../imagenes/flag_red.gif" /></td>
    	<td width="50" align="left">Rechazado</td>                         
	</tr>
	</table><br> 
    
<?php $pags = ceil($total/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
	  $ruta.= "&buscar[1]=$buscar[1]&filtros[1]=$filtros[1]&buscar[2]=$buscar[2]&filtros[2]=$filtros[2]";
	  
if($total>0) { ?>     
<table align="center" border="0" class="texto" cellpadding="0" cellspacing="0">
<tr>
	<td width="110" align="center" bgcolor="#BDBDBD" class="link_paginas"><?php echo $total;?> Registros</td>
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
	<td align="center" width="30"><img src="../imagenes/zoom.png" /></td>
	<td align="center" width="30"><img src="../imagenes/information.png" /></td>
    <?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { $cols = 4; ?>
    <td align="center" width="25"><img src="../imagenes/attach.png" /></td><?php } else { $cols = 3; } ?>    
	<td align="center" width="50">Folio</td>
	<td align="center" width="40">Qty</td>
    <td align="center" width="100">Proyecto</td>
	<?php if($reason!=1){?><td align="center" width="80">Cod.Scrap</td><?php } ?>
	<?php if($reason==1){?><td align="center" width="80">Reason Code</td><?php } ?>
    <td align="center" width="80" colspan="2">Cod. Causa Original</td>
    <td align="center" width="100">Info.Obligatoria</td>
	<td align="center" width="70">Total</td>
	<td align="center" width="90">Fecha</td>
	<?php  if($_SESSION["DEPTO"]=='lo') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>LO</span></td>"; 
	    if($_SESSION["DEPTO"]=='loa') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>LO-A</span></td>"; 
		if($_SESSION["DEPTO"]=='lpl') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>LPL</span></td>"; 
		if($_SESSION["DEPTO"]=='ffm') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>FFM</span></td>"; 
		if($_SESSION["DEPTO"]=='ffc') { $class = 'naranja'; } 
			else { $class = ''; }echo"<td width='60' align='center'><span class=$class>FFC</span></td>"; 
		if($_SESSION["DEPTO"]=='prod') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>Prod</span></td>";
		if($_SESSION["DEPTO"]=='sqm') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='60' align='center'><span class=$class>SQM</span></td>"; 
		if($_SESSION["DEPTO"]=='inv') { $class = 'naranja'; } else { $class = ''; }
			echo"<td width='70' align='center'><span class=$class>Inventarios</span></td>"; ?>
</tr>
</thead>
<tbody>
<?php if($total>0) { 
	 $s_1 = "select * from vw_reportes_".$_SESSION["IDEMP"]." order by no_folio desc limit $ini_,100";
	 $r_1 = mysql_query($s_1);
	 while($d_1 = mysql_fetch_array($r_1)) {
	 $qty = $qty+$d_1['cantidad_total']; $cost = $cost+$d_1['costo_total']; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/zoom.png" border="0"></a></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/information.png" border="0"></a></td>
	<?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { ?>
    <td align="center">    
	<?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$d_1['archivo']; 		
	     if($d_1['archivo']!='') { ?>
         	<a href="<?php echo $ruta;?>" target="_blank">
         	<img src="../imagenes/attach.png" border="0"></a><?php } ?>
    </td><?php } ?>        
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
	<?php if($reason!=1){?><td align="center"><?php echo $d_1['codigo_scrap'];?></td><?php } ?>
	<?php if($reason==1){?><td align="center"><?php echo $d_1['reason_code'];?></td><?php } ?>
	<td align="center" width="60">
    	<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>    
    <td align="center"><?php if($d_1['info_1']!='NA') { echo $d_1['info_1']."-".$d_1['info_2']; } else { echo "NA"; } ?></td>    
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;&nbsp;</td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>
	<td align="center"><?php echo get_bandera("lo",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("loa",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("lpl",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffc",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("prod",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("sqm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("inv",$d_1['no_folio']);?></td>
</tr>
<?php } if($qty>0) { ?>
<tr onMouseOut="this.style.background='#E6E6E6'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#E6E6E6" height="20">
	<td colspan="<?php echo $cols;?>">&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td colspan="5">&nbsp;</td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b></td>
	<td colspan="9">&nbsp;</td>
</tr><?php } } ?>
</tbody>
</table><br><br><br>
<?php } ?>