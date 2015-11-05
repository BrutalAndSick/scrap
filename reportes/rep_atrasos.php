<?php include("../header.php"); 
      include("funciones.php"); ?>
<script>
function validar(destino) {
	if(destino=='excel') { 
		form1.action='excel_reportes.php?op=previo_sap';
		form1.submit();	
		form1.action='?op=listado';	}
	else { 
		form1.action='?op=reporte';	
		form1.submit(); }	
}

function exportar() {
		form1.action='excel_reportes.php?op=atrasos';
		form1.submit();	
		form1.action='?op=listado';	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','atrasos'); ?></td>
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
			case "listado"		:	listado($buscar,$filtros,$pagina); break; 
			default				:	listado($buscar,$filtros,$pagina); break;						
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { 
	$s_ = "select valor from configuracion where variable='dias_atraso'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_); $limite = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$d_['valor'],date("Y"))); ?>

<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="280">SCRAP ANTES DE: <?php echo $limite;?></td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbsp;Autorizaciones con Atraso] body=[Estas son las autorizaciones pendientes de firmar por cualquiera de los departamentos. Estas son las boletas que tienen un atraso mayor a la fecha límite establecida por el administrador. Usted puede revisar el estado de boleta de scrap en base a los colores y las banderas:<br><br><table align='center' border='0' cellspacing='2'><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_orange.gif'></td><td width=200>&nbsp;Firmas que están pendientes</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_green.gif'></td><td>&nbsp;Firmas aprobadas</td></tr><tr bgcolor='#FFFFFF'><td><img src='../imagenes/flag_red.gif'></td><td>&nbsp;Firmas rechazadas</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>		
</tr>
</table></div><hr>
<?php } 


function listado($buscar,$filtros,$pagina) {
	if(!$pagina) $pagina = 1; 
	
	$s_ = "select valor from configuracion where variable='dias_atraso'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_); $limite = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$d_['valor'],date("Y")));

    $s_f = "select folios.*, sum(partes.cantidad) as cantidad_total, sum(partes.total) as costo_total from scrap_partes as partes, scrap_folios as folios, ";
	$s_f.= "autorizaciones where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and folios.status='0' ";
	$s_f.= "and folios.activo='1' and folios.fecha<='$limite' ";
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($_SESSION["TYPE"]!='administrador') {
	$s_ = "select * from divisiones where jefe='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { 
		$s_f.= "and (";
	while($d_=mysql_fetch_array($r_)) {
		$s_f.= "id_division = '$d_[id]' or "; }
	$s_f = substr($s_f,0,-3)." ) "; } 		

	$s_ = "select * from plantas where jefe='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { 
		$s_f.= "and (";
	while($d_=mysql_fetch_array($r_)) {
		$s_f.= "id_planta = '$d_[id]' or "; }
	$s_f = substr($s_f,0,-3)." ) "; } }

	$s_f.= " group by folios.no_folio order by folios.no_folio asc "; 
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1); ?>
    
<div align="center" class="aviso">Utilice los filtros para generar búsquedas. Al cambiar un filtro, haga clic en el botón Buscar para refrescar la información. El reporte muestra toda la información sobre folios pendientes de autorizar.</div>	
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>" />
<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where atrasos='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where atrasos='1 order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where atrasos='1' order by nombre"; 
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
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="exportar();">
</div></form>

<table align="center" border="0" class="texto">
<tr>
	<td width="20" align="center"><img src="../imagenes/zoom.png" /></td>
   	<td width="60" align="left">Ver Boleta</td> 
    <td width="20" align="center">|</td>
	<td width="20" align="center"><img src="../imagenes/information.png" /></td>
   	<td width="40" align="left">Historial</td>      
    <td width="20" align="center">|</td>  
	<td width="20" align="center"><img src="../imagenes/ayuda.gif" /></td>
   	<td width="40" align="left">Detalles</td> 
</tr>
</table><br>

<?php $pags = ceil($tot/100);
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
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="25"><img src="../imagenes/zoom.png" /></td>
	<td align="center" width="25"><img src="../imagenes/information.png" /></td>
	<td align="center" width="30">No.</td>
    <td align="center" width="60">Folio</td>
	<td align="center" width="90">Fecha</td>
    <td align="center" width="90">Cod.Scrap</td>
    <td align="center" width="90">Reason Code</td>
	<td align="center" width="90" colspan="2">Cod. Causa Original</td>    
	<td align="center" width="60">Qty</td>
	<td align="center" width="80">Total</td>	
	<td align="center" width="250">Números de Parte</td>
    <td align="center" width="50">APD</td>
    <td align="center" width="100">Capturista</td>
    <td align="center" width="30">LO</td>
    <td align="center" width="30">LO-A</td>
    <td align="center" width="30">LPL</td>
    <td align="center" width="30">FFM</td>
    <td align="center" width="30">FFc</td>
    <td align="center" width="30">Prod</td>
    <td align="center" width="30">SQM</td>
    <td align="center" width="30">Finanzas</td>
    <td align="center" width="30">ESP</td>
    <td align="center" width="30">Inv</td>
</tr>
</thead>
<tbody>
<?php $i=$ini_+1; $qty=$cost=0; 
	  $s_f.= " limit $ini_,100";  
	  $r_1 = mysql_query($s_f);
	  while($d_1=mysql_fetch_array($r_1)) { 
	  $qty  = $qty+$d_1['cantidad_total']; 
	  $cost = $cost+$d_1['costo_total']; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/zoom.png" border="0"></a></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/information.png" border="0"></a></td>		
	<td align="center"><?php echo $i;?></td>
    <td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>
	<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
	<td align="center"><?php echo $d_1['reason_code'];?></td>
	<td align="center" width="70">
		<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>      
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;</td>
	<td align="center">
	<?php if($d_1['carga_masiva']=='0'){ 
    	echo "<table align='center' border='0' cellpadding='0' cellspacing='2' style='border:#CCCCCC solid 1px;' width='250'>";
			  $s_2 = "select * from scrap_partes where no_folio='$d_1[no_folio]' order by no_parte";
		      $r_2 = mysql_query($s_2);
		   while($d_2=mysql_fetch_array($r_2)) { 
		echo "<tr bgcolor='#EEEEEE'>";
		   echo "<td align='left' width='100'>&nbsp;$d_2[no_parte]</td>";
           echo "<td align='left' width='150'>&nbsp;$d_2[descripcion]</td>"; 
		echo "</tr>";   
		} echo "</table>"; }
    	if($d_1['carga_masiva']=='1') { 
   			echo "<a href='../excel_reportes.php?op=ver_modelos&folio=$d_1[no_folio]' class='menuLink'>";
			echo "Archivo de modelos<br>(carga masiva)</a>"; } ?>
	</td>	
    <td align="center"><?php echo $d_1['apd'];?></td>
    <td align="center"><?php echo $d_1['empleado'];?></td>	
    <td align="center"><?php echo get_bandera("lo",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("loa",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("lpl",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffc",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("prod",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("sqm",$d_1['no_folio']);?></td>
    <td align="center"><?php echo get_bandera("fin",$d_1['no_folio']);?></td>
    <td align="center"><?php echo get_bandera("esp",$d_1['no_folio']);?></td>
   	<td align="center"><?php echo get_bandera("inv",$d_1['no_folio']);?></td>
</tr>
<?php $i++; } ?>
<tr bgcolor="#E6E6E6">
	<td colspan="9" align="right" class="naranja"><b>Totales</b>&nbsp;&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b>&nbsp;</td>
	<td colspan="14">&nbsp;</td>
</tr>
</tbody>
</table><br>
<?php } ?>