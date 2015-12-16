<?php include("../header.php");
      include('funciones.php');
      include('filtros.php'); ?>
 
<script>
function validar(destino) {
	if(destino=='excel') { 
		form1.action='excel_reportes.php?reporte=capturista';
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
    <td><?php submenu('b_consulta',$op); ?></td>
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
		<?php filtros($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$editados,$pagina,$op); 
			  listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$editados,$pagina,$op); ?>
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



function filtros($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$editados,$pagina,$op) { 
	if($op=='aprobados') {
		if(!$fechai) { $fechai = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));   }
		if(!$fechaf) { $fechaf = date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); }
	}
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
			$r_ = mysql_query($s_); } } ?>
            
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200">
	<?php switch($op) {
		case "proceso"		:	$titulo = "SCRAP EN PROCESO"; break;
		case "rechazados"	:	$titulo = "SCRAP RECHAZADO"; break;
		case "cancelados"	:	$titulo = "SCRAP CANCELADO"; break;
		case "aprobados"	:	$titulo = "SCRAP APROBADO"; break; } echo $titulo; ?>
	</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbsp;<?php echo $titulo;?>] body=[Esta es la consulta de scrap en diferentes estados. 
	Usted puede revisar el estado de boleta de scrap en base a los colores y las banderas:<br><br><table align='center' border='0' cellspacing='2'>
	<tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_orange.gif'></td><td width=200>&nbsp;Firmas que están pendientes</td></tr><tr bgcolor='#FFFFFF'><td>
	<img src='../imagenes/flag_green.gif'></td><td>&nbsp;Firmas aprobadas</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_red.gif'></td>
	<td>&nbsp;Firmas rechazadas</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>		
	</td>	
</tr>
</table></div><hr>
<div align="center" class="aviso">Utilice los filtros para generar búsquedas. Al cambiar un filtro, haga clic en el botón Buscar para refrescar la información. El reporte muestra toda la información sobre folios en proceso.</div>	
<form action="capturistas.php" method="post" name="form1">
<input type="hidden" name="op" value="<?php echo $op;?>">
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
        <td><?php $s_1 = "select campo, nombre from encabezados where c_proceso='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where c_proceso='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where c_proceso='1' order by nombre"; 
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
	<input type="button" value="Exportar" class="submit" onclick="validar('excel');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="reason" value="1" <?php if($reason==1) {?> checked="checked"<?php } ?>>
	Mostrar Reason code
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="editados" value="1" <?php if($editados==1) {?> checked="checked"<?php } ?>>
	Sólo folios editados  
</div><br></form>
<?php } 	

function listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$reason,$editados,$pagina,$op) { 
if(!$pagina) $pagina=1;  
	if($op=='aprobados') {
		if(!$fechai) { $fechai = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y"))); }
		if(!$fechaf) { $fechaf = date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); }
	} ?>
  	<table align="center" border="0" class="texto">
	<tr>
		<td width="20" align="center"><img src="../imagenes/zoom.png" /></td>
    	<td width="60" align="left">Ver Boleta</td> 
        <td width="20" align="center">|</td>
		<td width="20" align="center"><img src="../imagenes/pencil.gif" /></td>
    	<td width="70" align="left">Editar Boleta</td> 
        <td width="20" align="center">|</td>
		<td width="20" align="center"><img src="../imagenes/tick.png" /></td>
    	<td width="40" align="left">Editada</td> 
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
    <?php 
	$s_1 = "select no_folio, id_emp, fecha, sum(cantidad) as cantidad_total, planta, carga_masiva, proyecto, id_division, division, profit_center, area, codigo_scrap, ";
	$s_1.= "reason_code, sum(total) as costo_total, financiero, archivo, info_1, info_2, editado from ";
	switch($op){
		case "proceso" 		:	$s_1.= "vw_pendientes "; break;
		case "rechazados"	:	$s_1.= "vw_rechazados "; break;
		case "cancelados"	:	$s_1.= "vw_cancelados "; break; 
		case "aprobados"	:	$s_1.= "vw_aprobados ";  break; }
	$s_1.= "where no_folio!='0' ";	
	if($fechai!='' && $fechaf!='') { $s_1.= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($editados=='1') { $s_1.= "and editado='1' "; } 
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_1 .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_1.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_1 = substr($s_1,0,-3)." ) "; } 	
	}
	$s_1.= filtros_capturista($division);
	$s_1.= " group by no_folio";
	$r_1 = mysql_query($s_1);
	$total = mysql_num_rows($r_1);
		
	  $pags = ceil($total/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&op=$op&fechai=$fechai&fechaf=$fechaf&reason=$reason&tipo=$tipo&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
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
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="40" rowspan="2">&nbsp;</td>
	<td align="center" width="30" rowspan="2"><img src="../imagenes/zoom.png"></td>
	<td align="center" width="30" rowspan="2"><img src="../imagenes/information.png" /></td>
    <td align="center" width="30" rowspan="2"><img src="../imagenes/attach.png" /></td>
    <td align="center" width="30" rowspan="2"><img src="../imagenes/tick.png" /></td>
	<td align="center" width="50" rowspan="2">Folio</td>
	<td align="center" width="80" rowspan="2">Fecha</td>
	<td align="center" width="80" rowspan="2">Planta</td>
    <td align="center" width="80" rowspan="2">Division</td>
    <td align="center" width="80" rowspan="2">Proyecto</td>
	<td align="center" width="70" rowspan="2">P.C.</td>
	<?php if($reason!=1) { ?><td align="center" width="70" rowspan="2">Cod.Scrap</td><?php }?>
	<?php if($reason==1) { ?><td align="center" width="70" rowspan="2">Reason Code</td><?php }?>
	<td align="center" width="90" rowspan="2" colspan="2">Cod.Causa Original</td>
    <td align="center" width="90" rowspan="2">Info.Obligatoria</td>
    <td align="center" width="60" rowspan="2">Cantidad</td>
	<td align="center" width="100" rowspan="2">Total</td>
	<td align="center" colspan="4">Estado Actual</td>
</tr>
<tr>
	<td width="40" align="center" bgcolor="#F3F781">
		<span title='header=[&nbsp;&nbsp;Pendientes] body=[Resúmen de firmas pendientes]'>
		<img src="../imagenes/flag_orange.gif" style="cursor: hand;"></span></td>
	<td width="40" align="center" bgcolor="#BEF781">
		<span title='header=[&nbsp;&nbsp;Aprobados] body=[Resúmen de firmas aprobadas]'>
		<img src="../imagenes/flag_green.gif" style="cursor: hand;"></span></td>
	<td width="40" align="center" bgcolor="#F78181">
		<span title='header=[&nbsp;&nbsp;Rechazados] body=[Resúmen de firmas rechazadas]'>
		<img src="../imagenes/flag_red.gif" style="cursor: hand;"></span></td>			
	<td width="60" align="center" bgcolor="#CCCCCC">
		<span title='header=[&nbsp;&nbsp;Porcentaje] body=[Porcentaje de avance en el proceso de aprobación]'>
		<img src="../imagenes/flag_white.gif" style="cursor: hand;"></span></td>
</tr>
</thead>
<tbody>
<?php if($total>0) { 
  	 $s_1.= " order by no_folio desc limit $ini_,100";
	 $r_1 = mysql_query($s_1); $i=$ini_+1;
	 while($d_1=mysql_fetch_array($r_1)) { 
	  $qty  = $qty+$d_1['cantidad_total']; 
	  $cost = $cost+$d_1['costo_total']; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
	<td align="center">
		<?php if($_SESSION["IDEMP"]==$d_1['id_emp'] || division_valida($d_1['id_division'])=='SI') { ?>
        <?php if($d_1['carga_masiva']=='1') { ?>        
        	<a class="frame_editar_boleta" href="../capturista/scrap_edicion_2.php?op=editar_1&folio=<?php echo $d_1['no_folio'];?>&from=proceso">
        	<img src="../imagenes/pencil.gif" border="0" style="cursor:hand;"></a>
		<?php } else { ?>
        	<a class="frame_editar_boleta" href="../capturista/scrap_edicion_1.php?op=editar_1&folio=<?php echo $d_1['no_folio'];?>&from=proceso">
            <img src="../imagenes/pencil.gif" border="0" style="cursor:hand;"></a><?php } ?>		
		<?php } else { ?><a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>">
		<img src="../imagenes/zoom.png" border="0"></a><?php } ?></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
		<img src="../imagenes/information.png" border="0"></a></td>
    <td align="center">
    <?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$d_1['archivo']; 		
	      if($d_1['archivo']!='') { ?>
         	<a href="<?php echo $ruta;?>" target="_blank">
            <img src="../imagenes/attach.png" border="0"></a><?php } ?></td> 
    <td align="center">
 	<?php if($d_1['editado']=='1') { ?><img src="../imagenes/tick.png" border="0"><?php } else { echo "&nbsp;"; } ?></td>   
    <td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
    <td align="center"><?php echo $d_1['profit_center'];?></td>
	<?php if($reason!=1) { ?><td align="center"><?php echo $d_1['codigo_scrap'];?></td><?php } ?>
    <?php if($reason==1) { ?><td align="center"><?php echo $d_1['reason_code'];?></td><?php } ?>
	<td align="center" width="70">
		<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
		      echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>       
    <td align="left">&nbsp;&nbsp;<?php if($d_1['info_1']!='NA') { echo $d_1['info_1']."-".$d_1['info_2']; } else { echo "NA"; } ?></td>
    <td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;&nbsp;</td>
	<?php $datos = get_status_folio($d_1['no_folio']);?>
	<td width="40" align="center" bgcolor="#F3F781"><?php echo $datos['p'];?></td>
	<td width="40" align="center" bgcolor="#BEF781"><?php echo $datos['a'];?></td>
	<td width="40" align="center" bgcolor="#F78181"><?php echo $datos['r'];?></td>
	<?php $porcentaje = ($datos['a']*100)/$datos['t']; ?> 
	<td width="60" align="center" bgcolor="#CCCCCC"><?php echo number_format($porcentaje,2).'%';?></td>
</tr>
<?php $i++; } if($qty>0) { ?>
<tr onMouseOut="this.style.background='#E6E6E6'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#E6E6E6" height="20">
	<td colspan="15" align="right" class="naranja">&nbsp;&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b>&nbsp;&nbsp;</td>
	<td colspan="4">&nbsp;</td>
</tr><?php } } ?>
</tbody>
</table>
<?php }


function division_valida($id_division) {
	$s_2 = "select * from capturistas where id_emp='$_SESSION[IDEMP]' and (id_division='%' or id_division='$id_division')";
	$r_2 = mysql_query($s_2); 
	if(mysql_num_rows($r_2)>0) {
		return "SI"; }
	else { return "NO"; }		
} ?>