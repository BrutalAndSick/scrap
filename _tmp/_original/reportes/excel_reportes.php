<?php session_name("loginUsuario"); 
      session_start(); 
$file_name="reporte_scrap_".date("Ymd").".xls";
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$file_name"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php include('../conexion_db.php');
	  include('funciones.php');
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }

switch($op) { 

	case "reporte_sap"	:	reporte_sap($fechai,$fechaf); break; 
	case "previo_sap"	:	previo_sap($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$stock,$f_material,$f_sub); break; 
	case "preliminar"	:	preliminar($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$reporte); break;
	case "general"		:	general($fechai,$fechaf,$buscar,$filtros,$reporte); break;
	case "definitivo"	:	definitivo($fechai,$fechaf,$aplica_oes,$tipo,$division,$buscar,$filtros); break;
	case "historial_1"	:	historial_1($anio,$fechai,$fechaf,$tipo,$division,$buscar,$filtros); break;
	case "historial_2"	:	historial_2($anio,$fechai,$fechaf,$buscar,$filtros,$reporte); break;
	case "atrasos"		:	atrasos($buscar,$filtros); break;
	case "grafico"		:	grafico($consulta,$reason); break;
	case "corto"		:	corto($fechai,$fechaf,$buscar,$filtros); break;
	case "muestras"		:	muestras($fechai,$fechaf); break;
	case "turnos"		:	turnos($fechai,$fechaf,$buscar,$filtros); break;
} 
	
	
function reporte_sap($fechai,$fechaf) { ?>
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="6" align="center"><b>Reporte de SCRAP capturado</b></td>
	</tr>
</thead>	
</table><br>
<table align="center" border="1">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" bgcolor="#FFCC33" width="90"><b>Fecha</b></td>
    <td align="center" bgcolor="#FFCC33" width="80"><b>Hora</b></td>
	<td align="center" bgcolor="#FFCC33" width="120"><b>Usuario</b></td>
	<td align="center" bgcolor="#FFCC33" width="250"><b>Empleado</b></td>
	<td align="center" bgcolor="#FFCC33" width="150"><b>Folio</b></td>
    <td align="center" bgcolor="#FFCC33" width="150"><b>Partes</b></td>
</tr>
</thead>
<tbody>
<?php $s_f = "select *, count(*) as total from (";
	  $s_f.= "select empleados.usuario, empleados.nombre, empleados.apellidos, aut_bitacora.* from aut_bitacora, empleados where fecha>='$fechai' and fecha<='$fechaf' ";
      $s_f.= "and empleados.id = aut_bitacora.id_emp and comentario like 'CAPTURA DE SAP:%' order by id desc) as general group by no_folio order by fecha desc"; 
	  $r_1 = mysql_query($s_f); 
	  $total = mysql_num_rows($r_1);
	  while($d_1=mysql_fetch_array($r_1)) {?>
<tr>      
    <td align="center"><?php echo $d_1['fecha'];?></td>
    <td align="center"><?php echo $d_1['hora'];?></td>
    <td align="left"><?php echo $d_1['usuario']; ?></td>    
    <td align="left"><?php echo $d_1['apellidos']." ".$d_1['nombre']; ?></td>
    <td align="center"><?php echo $d_1['no_folio'];?></td>
    <td align="center"><?php echo $d_1['total'];?></td>
</tr>	
<?php } ?>
</tbody>
</table>	
<?php }
	
function atrasos($buscar,$filtros) {?>
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="28" align="center"><b>Reporte de SCRAP atrasado</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
	<tr height="20">
	<td align="center" width="50" bgcolor="#FFCC33" rowspan="2"><b>No.Item</b></td>
	<td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Fecha</b></td>
	<td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Cantidad</b></td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2"><b>Costo</b></td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2"><b>Turno</b></td>
	<?php if($reason!=1){?><td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Código Scrap</b></td><?php } ?>
	<?php if($reason==1){?><td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Reason Code</b></td><?php } ?>    
	<td align="center" width="100" bgcolor="#FFCC33" rowspan="2"><b>Valor</b></td>
	<td align="center" width="100" bgcolor="#FFCC33" rowspan="2"><b>Proceso</b></td>
	<td align="center" width="200" bgcolor="#FFCC33" rowspan="2"><b>Defecto</b></td>
    <td align="center" bgcolor="#FFCC33" colspan="6"><b>Cod.Causa Original</b></td>
    <td align="center" width="60" bgcolor="#FFCC33" rowspan="2"><b>APD</b></td>
    <td align="center" width="60" bgcolor="#FFCC33" rowspan="2"><b>Proyecto</b></td>
    <td align="center" width="100" bgcolor="#FFCC33" rowspan="2"><b>Supervisor</b></td>
	<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Información Obligatoria</b></td>
	<td align="center" width="90" bgcolor="#FFCC33" colspan="9"><b>Autorizaciones</b></td>
</tr>
<tr>
	<td align="center" width="180" bgcolor="#FFCC33"><b>Área</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Estación</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Línea</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Defecto</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Causa</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Código Scrap</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>LO</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>LOA</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>LPL</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>FFM</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>FFC</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Prod</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>SQM</b></td>
    <td align="center" width="90" bgcolor="#FFCC33"><b>Finanzas</b></td>
    <td align="center" width="90" bgcolor="#FFCC33"><b>ESP</b></td>
</tr>
</thead>
<tbody>
<?php 
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

	$s_f.= " group by folios.no_folio order by folios.no_folio asc";
	$r_1 = mysql_query($s_f);

    while($d_1 = mysql_fetch_array($r_1)) { 
	$qty = $qty+$d_1['cantidad_total']; $cost = $cost+$d_1['costo_total']; ?>
<tr>
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo $d_1['fecha'];?></td>
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="left"><?php echo $d_1['costo_total'];?></td>
	<td align="center"><?php echo $d_1['turno'];?></td>
	<?php if($reason!=1){?>
    	<td align="center"><?php echo $d_1['codigo_scrap'];?></td><?php } ?>
	<?php if($reason==1){?>
    	<td align="center"><?php echo $d_1['reason_code'];?></td><?php } ?>
	<td align="right" class="small"><?php echo number_format($d_1['costo_total'],2);?></td>
	<td align="left"><?php echo $d_1['estacion'];?></td>
	<td align="left"><?php echo $d_1['defecto'];?></td>
    <?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); ?>
    <td align="left"><?php echo $original['area'];?>&nbsp;</td>
    <td align="left"><?php echo $original['estacion'];?>&nbsp;</td>
    <td align="left"><?php echo $original['linea'];?>&nbsp;</td>
    <td align="left"><?php echo $original['defecto'];?>&nbsp;</td>
    <td align="left"><?php echo $original['causa'];?>&nbsp;</td>
    <td align="center"><?php echo $original['codigo'];?>&nbsp;</td>  
    <td align="left"><?php echo $d_1['apd'];?></td>
    <td align="left"><?php echo $d_1['proyecto'];?></td>
    <td align="left"><?php echo $d_1['supervisor'];?></td>
	<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
	<td align="center"><?php echo get_datos("lo",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("loa",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("lpl",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("ffm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("ffc",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("prod",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("sqm",$d_1['no_folio']);?></td>
    <td align="center"><?php echo get_datos("fin",$d_1['no_folio']);?></td>
    <td align="center"><?php echo get_datos("esp",$d_1['no_folio']);?></td>
</tr>
<?php } ?>
<tr>
	<td align="right" colspan="2"><b>Totales&nbsp;&nbsp;</b></td>
	<td align="center" bgcolor="#FFCC33"><b><?php echo $qty;?></b></td>
	<td align="center" bgcolor="#FFCC33"><b><?php echo number_format($cost,2);?></b></td>
	<td align="center" colspan="24">&nbsp;</td>
</tr>
</tbody>
</table>
<?php } 

	

function previo_sap($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$stock) { ?>    
<table align="center" border="1">
<thead>
<tr height="20">
	<td align="center" bgcolor="#FFCC33">No.Item</td>
    <td align="center" bgcolor="#FFCC33">Fecha</td>
	<td align="center" bgcolor="#FFCC33">Txs SAP</td>
	<td align="center" bgcolor="#FFCC33">Mov</td>
	<td align="center" bgcolor="#FFCC33">Código Scrap</td>
	<td align="center" bgcolor="#FFCC33">O.I.</td>
    <td align="center" bgcolor="#FFCC33">APD</td>
	<td align="center" bgcolor="#FFCC33">Parte Padre</td>
	<td align="center" bgcolor="#FFCC33">Batch ID</td>
	<td align="center" bgcolor="#FFCC33">No.Parte</td>
	<td align="center" bgcolor="#FFCC33">Cantidad</td>
	<td align="center" bgcolor="#FFCC33">Reason Code</td>
	<td align="center" bgcolor="#FFCC33">Descripción</td>
	<td align="center" bgcolor="#FFCC33">Info.Obl.</td>
	<td align="center" bgcolor="#FFCC33">Tipo</td>
    <td align="center" bgcolor="#FFCC33">Tipo Sub</td>
	<td align="center" bgcolor="#FFCC33">Valor</td>
	<td align="center" bgcolor="#FFCC33">Doc. Header Tex</td>
    <td align="center" bgcolor="#FFCC33">Déficit Stock</td>
	<td align="center" bgcolor="#FFCC33">No.Docto.SAP</td>
</tr>
</thead>
<tbody>
<?php    
	$s_f = "select folios.*, partes.id as idp, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, ";
	$s_f.= "partes.ubicacion, partes.docto_sap, partes.deficit, partes.tipo, partes.tipo_sub, partes.padre, partes.batch_id, ";
	$s_f.= "partes.serial_unidad from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio and ";
	$s_f.= "autorizaciones.no_folio = folios.no_folio and folios.status='1' and folios.activo='1' and (partes.docto_sap='0' or partes.docto_sap='') ";
	$s_f.= "and partes.no_parte = numeros_parte.nombre ";
	if($stock=='1') { $s_f.= " and partes.deficit='1' "; }
	if($stock!='1') { $s_f.= " and partes.deficit like '%' "; }
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; } 
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); }
	$s_f.= "group by partes.id order by folios.no_folio asc ";
	$r_1 = mysql_query($s_f); 
	while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php echo $d_1['no_folio'];?></td>
    <td align="center"><?php echo $d_1['fecha'];?></td>
	<td align="center"><?php echo $d_1['txs_sap'];?></td>
	<td align="center"><?php echo $d_1['mov_sap'];?></td>
	<td align="left">
	<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
	      if($d_1['financiero']=='0') { echo $d_1['codigo_scrap']; } else { 
 		  echo $original['codigo']; } ?></td>
	<td align="left"><?php echo $d_1['orden_interna'];?></td>
    <td align="left"><?php echo "'".$d_1['apd'];?></td>
    <td align="left"><?php echo $d_1['padre'];?></td>
	<td align="left"><?php echo $d_1['batch_id'];?></td>
	<td align="left"><?php echo $d_1['no_parte'];?></td>
	<td align="center"><?php echo $d_1['cantidad'];?></td>
	<td align="left"><?php echo $d_1['reason_code'];?></td>
	<td align="left"><?php echo $d_1['descripcion'];?></td>
	<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
	<td align="center"><?php echo $d_1['tipo'];?></td>
    <td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
	<td align="right"><?php echo $d_1['costo_total'];?></td>
	<td align="left"><?php echo get_doc_header($d_1['info_1'],$d_1['info_2'],$d_1['segmento'],$d_1['proyecto'],$d_1['no_folio']);?></td>
    <td align="center"><?php if($d_1['deficit']=='1') { echo "SI"; } else { echo "NO"; } ?></td>
	<td align="center">&nbsp;</td>
</tr>	
<?php } ?>
</tbody>
</table>
<?php }	

function get_doc_header($info_1,$info_2,$segmento,$proyecto,$folio) {
	$s_ = "select empleado from autorizaciones where no_folio='$folio' and depto='lpl'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	$nombre   = $d_['empleado'];
	$palabras = split(" ",$d_['empleado']);
	$inicial  = substr($palabras['0'],0,1);
	
	for($i=0;$i<count($palabras);$i++) {
		$apellido.= $palabras[$i]." "; }

	if($info_1=='QN') {
		$doc_header = substr($info_2."_".$proyecto."_".$inicial.".".$apellido,0,16);
	} else { 
		$doc_header = substr($segmento."_".$proyecto."_".$inicial.".".$apellido,0,23);	
	}	
	return $doc_header;
}
	
function definitivo($fechai,$fechaf,$aplica_oes,$tipo,$division,$buscar,$filtros) { 
    $s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, ";
	$s_f.= "partes.docto_sap, partes.deficit, partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, autorizaciones where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio  "; 
	$s_f.= "and folios.activo='1' and folios.status='1' and partes.docto_sap!='0' and partes.docto_sap!='' ";
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
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; } 
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); } 
	$r_1  = mysql_query($s_f);
	$tot  = mysql_num_rows($r_1); 
	$data = get_encabezado($division,$s_f); ?>
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="32" align="center"><b>Reporte Autorizado de SCRAP</b></td>
	</tr>
</thead>	
</table><br>
<?php if($tot>0) { ?>	
<table align="center" class="tabla" border="0">
<thead>
	<tr height="20">
		<td colspan="2" align="left"><b>División</b></td>
		<td colspan="4" align="left" style="border-bottom:#666666 solid 1px;"><?php echo $data['division'];?></td>
		<td colspan="2"></td>
		<td colspan="14" align="center" bgcolor="#FFCC33" style="border:#666666 solid 1px;"><b>Autorizaciones de SCRAP</b></td>
	</tr>	
	<tr height="20">
		<td colspan="2" align="left"><b>Proyecto</b></td>
		<td colspan="4" align="left" style="border-bottom:#666666 solid 1px;"><?php echo $data['proyecto'];?></td>	
		<td colspan="2"></td>
		<td colspan="4" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>LPL</b></td>
		<td colspan="2" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>Manufactura</b></td>
		<td colspan="2" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>FFC</b></td>
		<td colspan="2" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>Lo</b></td>
		<td colspan="2" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>Lo Almacén</b></td>
		<td colspan="2" align="center" bgcolor="#E6E6E6" style="border:#666666 solid 1px;"><b>Inventarios</b></td>
	</tr>
	<tr height="20">	
		<td colspan="2" align="left"><b>APD</b></td>
		<td colspan="4" align="left" style="border-bottom:#666666 solid 1px;"><?php echo $data['apd'];?></td>	
		<td colspan="2"></td>
		<td colspan="4" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">
			<?php echo get_autorizadores("lpl",$s_f);?>&nbsp;</td>
		<td colspan="2" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">
			<?php echo get_autorizadores("prod",$s_f);?>&nbsp;</td>
		<td colspan="2" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">
			<?php echo get_autorizadores("ffc",$s_f);?>&nbsp;</td>
		<td colspan="2" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">
			<?php echo get_autorizadores("lo",$s_f);?>&nbsp;</td>
		<td colspan="2" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">	
			<?php echo get_autorizadores("loa",$s_f);?>&nbsp;</td>
		<td colspan="2" align="center" rowspan="3" bgcolor="#F2F2F2" style="border:#666666 solid 1px;">
			<?php echo get_autorizadores("inv",$s_f);?>&nbsp;</td>
	</tr>
	<tr height="20">	
		<td colspan="2" align="left"><b>P.C.</b></td>
		<td colspan="4" align="left" style="border-bottom:#666666 solid 1px;"><?php echo $data['profit_center'];?></td>	
		<td colspan="2"></td>
	</tr>
	<tr height="20">	
		<td colspan="2" align="left"><b>Fecha/Hora</b></td>
		<td colspan="4" align="left" style="border-bottom:#666666 solid 1px;"><?php echo date("Y-m-d")." / ".date("H:i:s");?></td>	
		<td colspan="2"></td>
	</tr>				
</thead>	
</table><br><br>

<table align="center" border="1">
<thead>
	<tr height="20">
	<td align="center" width="50" bgcolor="#FFCC33"><b>No.Item</b></td>
    <td align="center" width="50" bgcolor="#FFCC33"><b>Fecha</b></td>
    <td align="center" width="120" bgcolor="#FFCC33" colspan="3"><b>Última Autorización</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Txs SAP</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Mov</b></td>
	<td align="center" width="150" bgcolor="#FFCC33"><b>Código Scrap</b></td>
    <td align="center" width="150" bgcolor="#FFCC33"><b>Cod. Causa Original</b></td>   
	<td align="center" width="150" bgcolor="#FFCC33"><b>O.I.</b></td>
	<td align="center" width="150" bgcolor="#FFCC33"><b>Parte Padre</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Batch ID</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>No.Parte</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Cantidad</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Reason Code</b></td>
	<td align="center" width="200" bgcolor="#FFCC33"><b>Descripción</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Info.Obligatoria</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Tipo Material</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Tipo Sub</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Valor</b></td>
    <td align="center" width="150" bgcolor="#FFCC33"><b>LPL</b></td>
    <td align="center" width="150" bgcolor="#FFCC33"><b>Proyecto</b></td>
    <td align="center" width="150" bgcolor="#FFCC33"><b>Segmento</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Doc.Material Scrap</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Doc.Mat.Reclasificación</b></td>
	<td align="center" colspan="2" bgcolor="#FFCC33"><b>Inventarios</b></td>
	<td align="center" bgcolor="#FFCC33"><b>Por qué 1</b></td>
	<td align="center" bgcolor="#FFCC33"><b>Por qué 2</b></td>
	<td align="center" bgcolor="#FFCC33"><b>Por qué 3</b></td>
	<td align="center" bgcolor="#FFCC33"><b>Por qué 4</b></td>
	<td align="center" bgcolor="#FFCC33"><b>Por qué 5</b></td>
</tr>
</thead>
<tbody>
<?php //Sección 1 reporte de TXS SAP con MB1A con Orden Interna
	if($tipo=='1' || $tipo=='%') { 
		$s_1 = $s_f." and txs_sap='MB1A' and orden_interna!='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre"; 
 		$r_1 = mysql_query($s_1); 
	if(mysql_num_rows($r_1)>0) { 
    while($d_1=mysql_fetch_array($r_1)) { 
		$s_a = "select empleado from autorizaciones where no_folio='$d_1[no_folio]' and depto='lpl'";
		$r_a = mysql_query($s_a);
		$d_a = mysql_fetch_array($r_a); ?>
	<tr>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
        <?php $s_au = "select * from aut_bitacora where no_folio='$d_1[no_folio]' and depto!='inv' order by fecha desc";
		$r_au = mysql_query($s_au);
		$d_au = mysql_fetch_array($r_au);
		$fecha_aut = $d_au['fecha'];
		$hora_aut = $d_au['hora'];
		$emp_aut = $d_au['empleado'];?>
        <td align="center"><?php echo $fecha_aut;?></td>
        <td align="center"><?php echo $hora_aut;?></td>
        <td align="center"><?php echo $emp_aut;?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>
        <td align="left"><?php echo $d_a['empleado']; ?></td>
        <td align="left"><?php echo $d_1['proyecto']; ?></td>
        <td align="left"><?php echo $d_1['segmento']; ?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>
        <?php $s_i = "select fecha, hora from aut_bitacora where depto='inv' and status='1' and fecha!='0000-00-00' and no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['fecha'];?></td>
        <td align="center" width="120"><?php echo $d_i['hora'];?></td>      
        <?php $s_i = "select * from scrap_porques where no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['porque_1'];?></td>
        <td align="center" width="120"><?php echo $d_i['porque_2'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_3'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_4'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_5'];?></td>
	</tr>
	<?php } } } //Sección 2 reporte de TXS SAP con MB1A sin Orden Interna
		if($tipo=='2' || $tipo=='%') { 
		$s_2 = $s_f." and txs_sap='MB1A' and orden_interna='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_2); 
	if(mysql_num_rows($r_1)>0) { 
    while($d_1=mysql_fetch_array($r_1)) {  ?>
	<tr>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>
        <td align="left"><?php echo $d_a['empleado']; ?></td>
        <td align="left"><?php echo $d_1['proyecto']; ?></td>
        <td align="left"><?php echo $d_1['segmento']; ?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>	
        <?php $s_i = "select fecha, hora from aut_bitacora where depto='inv' and status='1' and fecha!='0000-00-00' and no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['fecha'];?></td>
        <td align="center" width="120"><?php echo $d_i['hora'];?></td>      
        <?php $s_i = "select * from scrap_porques where no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['porque_1'];?></td>
        <td align="center" width="120"><?php echo $d_i['porque_2'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_3'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_4'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_5'];?></td>
	</tr>	
	<?php } } } //Sección 3 reporte de TXS SAP con ZSCR y sin orden interna
	if($tipo=='3' || $tipo=='%') { 
		$s_3 = $s_f." and txs_sap='ZSCR' and (orden_interna='NA' or orden_interna='0') group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
		$r_1 = mysql_query($s_3);
	if(mysql_num_rows($r_1)>0) { 
	while($d_1=mysql_fetch_array($r_1)) {  ?>
	<tr>	
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']);echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>	
        <td align="left"><?php echo $d_a['empleado']; ?></td>
        <td align="left"><?php echo $d_1['proyecto']; ?></td>
        <td align="left"><?php echo $d_1['segmento']; ?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>		
        <?php $s_i = "select fecha, hora from aut_bitacora where depto='inv' and status='1' and fecha!='0000-00-00' and no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['fecha'];?></td>
        <td align="center" width="120"><?php echo $d_i['hora'];?></td>      
        <?php $s_i = "select * from scrap_porques where no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120"><?php echo $d_i['porque_1'];?></td>
        <td align="center" width="120"><?php echo $d_i['porque_2'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_3'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_4'];?></td>
		<td align="center" width="120"><?php echo $d_i['porque_5'];?></td>
	</tr>
	<?php } } } ?>
</tbody>
</table>
<?php }	}	


function get_encabezado($division,$s_f) {
	$s_d = "select * from divisiones where id='$division'";
	$r_d = mysql_query($s_d);
	$d_d = mysql_fetch_array($r_d);
	$data['division'] = $d_d['nombre'];

	$s_p = "select tabla1.proyecto from (".$s_f.") as tabla1 group by tabla1.proyecto order by tabla1.proyecto";
	$r_p = mysql_query($s_p);
	while($d_p = mysql_fetch_array($r_p)) {
		$data['proyecto'].= $d_p['proyecto'].", "; }
	$data['proyecto'] = substr($data['proyecto'],0,-2);	

	$s_a = "select tabla1.apd from (".$s_f.") as tabla1 group by tabla1.apd order by tabla1.apd";
	$r_a = mysql_query($s_a);
	while($d_a = mysql_fetch_array($r_a)) {
		$data['apd'].= $d_a['apd'].", "; }
	$data['apd'] = substr($data['apd'],0,-2);		

	$s_c = "select tabla1.profit_center from (".$s_f.") as tabla1 group by tabla1.profit_center order by tabla1.profit_center";
	$r_c = mysql_query($s_c);
	while($d_c = mysql_fetch_array($r_c)) {
		$data['profit_center'].= $d_c['profit_center'].", "; }
	$data['profit_center'] = substr($data['profit_center'],0,-2);		
		
	return $data;
}


function historial_1($anio,$fechai,$fechaf,$tipo,$division,$buscar,$filtros) { 
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
	$r_1  = mysql_query($s_f);
	$tot  = mysql_num_rows($r_1); ?>
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="22" align="center"><b>REPORTE HISTÓRICO DEFINITIVO DE SCRAP</b></td>
	</tr>
</thead>	
</table>
<?php if($tot>0) { ?>	
<table align="center" border="1">
<thead>
	<tr height="20">
	<td align="center" width="50" bgcolor="#FFCC33"><b>No.Item</b></td>
    <td align="center" width="50" bgcolor="#FFCC33"><b>Fecha</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Txs SAP</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Mov</b></td>
	<td align="center" width="150" bgcolor="#FFCC33"><b>Código Scrap</b></td>
    <td align="center" width="150" bgcolor="#FFCC33"><b>Cod. Causa Original</b></td>   
	<td align="center" width="150" bgcolor="#FFCC33"><b>O.I.</b></td>
	<td align="center" width="150" bgcolor="#FFCC33"><b>Parte Padre</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Batch ID</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>No.Parte</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Cantidad</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Reason Code</b></td>
	<td align="center" width="200" bgcolor="#FFCC33"><b>Descripción</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Info.Obligatoria</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Tipo Material</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Tipo Sub</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Valor</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>LPL</b></td>
    <td align="center" width="100" bgcolor="#FFCC33"><b>Proyecto</b></td>
    <td align="center" width="100" bgcolor="#FFCC33"><b>Segmento</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Doc.Material Scrap</b></td>
	<td align="center" width="100" bgcolor="#FFCC33"><b>Doc.Mat.Reclasificación</b></td>
</tr>
</thead>
<tbody>
<?php //Sección 1 reporte de TXS SAP con MB1A con Orden Interna
	if($tipo=='1' || $tipo=='%') { 
		$s_1 = $s_f." and txs_sap='MB1A' and orden_interna!='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre"; 
 		$r_1 = mysql_query($s_1); 
	if(mysql_num_rows($r_1)>0) { 
    while($d_1=mysql_fetch_array($r_1)) { 
		$s_a = "select empleado from ".$anio."_autorizaciones where no_folio='$d_1[no_folio]' and depto='lpl'";
		$r_a = mysql_query($s_a);
		$d_a = mysql_fetch_array($r_a); ?>	
	<tr>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>
        <td align="left"><?php echo $d_a['empleado'];?></td>
        <td align="center"><?php echo $d_1['proyecto'];?></td>
        <td align="center"><?php echo $d_1['segmento'];?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>
	</tr>
	<?php } } } //Sección 2 reporte de TXS SAP con MB1A sin Orden Interna
		if($tipo=='2' || $tipo=='%') { 
		$s_2 = $s_f." and txs_sap='MB1A' and orden_interna='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_2); 
	if(mysql_num_rows($r_1)>0) { 
    while($d_1=mysql_fetch_array($r_1)) {
		$s_a = "select empleado from ".$anio."_autorizaciones where no_folio='$d_1[no_folio]' and depto='lpl'";
		$r_a = mysql_query($s_a);
		$d_a = mysql_fetch_array($r_a); ?>
	<tr>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>
        <td align="left"><?php echo $d_a['empleado'];?></td>
        <td align="center"><?php echo $d_1['proyecto'];?></td>
        <td align="center"><?php echo $d_1['segmento'];?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>	
	</tr>	
	<?php } } } //Sección 3 reporte de TXS SAP con ZSCR y sin orden interna
	if($tipo=='3' || $tipo=='%') { 
		$s_3 = $s_f." and txs_sap='ZSCR' and (orden_interna='NA' or orden_interna='0') group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
		$r_1 = mysql_query($s_3);
	if(mysql_num_rows($r_1)>0) { 
	while($d_1=mysql_fetch_array($r_1)) {  
		$s_a = "select empleado from ".$anio."_autorizaciones where no_folio='$d_1[no_folio]' and depto='lpl'";
		$r_a = mysql_query($s_a);
		$d_a = mysql_fetch_array($r_a); ?>	
	<tr>	
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']);echo $original['codigo']; ?></td>
		<td align="center"><?php echo $d_1['orden_interna']; ?></td>
		<td align="center"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="center"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="center"><?php echo $d_1['costo_total'];?></td>	
        <td align="left"><?php echo $d_a['empleado'];?></td>
        <td align="center"><?php echo $d_1['proyecto'];?></td>
        <td align="center"><?php echo $d_1['segmento'];?></td>
		<td align="center"><?php if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo "&nbsp;"; }?></td>
		<td align="center"><?php echo $d_1['o_mantto'];?>&nbsp;</td>		
	</tr>
	<?php } } } ?>
</tbody>
</table>
<?php }	}	


function historial_2($anio,$fechai,$fechaf,$buscar,$filtros,$reporte) { 
 $s_e = "select encabezados.nombre, reportes.campo, encabezados.ancho from reportes, encabezados where reportes.id_emp = ";
 $s_e.= "'$_SESSION[IDEMP]' and reportes.reporte='$reporte' and reportes.excel!='0' and reportes.campo=encabezados.campo ";
 $s_e.= "order by reportes.excel, encabezados.nombre"; 
 $r_e = mysql_query($s_e); $i=0;
 $n_e = mysql_num_rows($r_e);
 while($d_e=mysql_fetch_array($r_e)) { 
	if($d_e['campo']=='cod_original') { $n_e=$n_e+2; }
	$campos[$i] = $d_e['campo'];
	$nombre[$i] = $d_e['nombre']; 
	$ancho[$i]  = $d_e['ancho'];
	$i++; } $original = 0; $n_e = $n_e + 9; ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="<?php echo $n_e;?>" align="center">
        	<b>REPORTE HISTÓRICO PRELIMINAR DE SCRAP</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
	<?php for($i=0;$i<count($nombre);$i++) { 
		if($campos[$i]=='cod_original') { $original='1'; $rowspan='2' ?>
        <td align="center" bgcolor="#FFCC33" colspan="3"><b><?php echo $nombre[$i];?></b></td>
        <?php } else { $original='0'; $rowspan='1'; ?>
        <td align="center" width="<?php echo $ancho[$i];?>" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b><?php echo $nombre[$i];?></b></td><?php } } ?>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LO</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LOA</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LPL</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>FFM</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>FFC</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Prod</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>SQM</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Finanzas</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>ESP</b></td>
</tr>
<?php if($original=='1') { ?>
<tr height="20">
  	<td align="center" width="150" bgcolor="#FFCC33"><b>Defecto</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Tecnología</b></td>
	<td align="center" width="70" bgcolor="#FFCC33"><b>Código</b></td>  
</tr><?php } ?>
</thead>
<tbody>
<?php     
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
	
    while($d_1=mysql_fetch_array($r_1)) { 
	   if($d_1['activo']=='1') { $qty = $qty + $d_1['cantidad']; $cost = $cost + $d_1['costo_total']; }
	   echo "<tr>";
	   for($i=0;$i<count($campos);$i++) { 
			switch($campos[$i]) {
				case "cod_original"	:	$original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
										echo "<td align='left' class='small'>&nbsp;".$original['defecto']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['estacion']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['codigo']."</td>"; break;
				case "docto_sap"	:	if($d_1['deficit']=='1') {
											echo "<td align='left' class='small'>&nbsp;Déficit de Stock</td>"; }
										else { 
											if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo "<td align='left' class='small'>&nbsp;".$d_1['docto_sap']."</td>"; } 
											else { echo "<td align='left' class='small'>&nbsp;</td>"; } } break;	
				case "info"			:	echo "<td align='left'>".$d_1['info_1'].$d_1['info_2']."</td>"; break;
				case "status"		:	if($d_1['activo']=='1') { 
										if($d_1['status']=='0') { echo "<td align='center' bgcolor='#FFCC00'>Proceso</td>";   }
										if($d_1['status']=='1') { echo "<td align='center' bgcolor='#BEF781'>Aprobado</td>";  }
										if($d_1['status']=='2') { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } }
										else { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } break;
				case "serial_unidad":	echo "<td align='left'>'".$d_1[$campos[$i]]."</td>"; break;
				default				:	echo "<td align='left'>".$d_1[$campos[$i]]."</td>"; break;
	   } } ?>
		<td align="center"><?php echo get_datos_old("lo",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("loa",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("lpl",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("ffm",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("ffc",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("prod",$d_1['no_folio'],$anio);?></td>
		<td align="center"><?php echo get_datos_old("sqm",$d_1['no_folio'],$anio);?></td>	   
        <td align="center"><?php echo get_datos_old("fin",$d_1['no_folio'],$anio);?></td>	   
        <td align="center"><?php echo get_datos_old("esp",$d_1['no_folio'],$anio);?></td>	   
	<?php echo "</tr>"; }
	echo "<tr>";	
	for($i=0;$i<count($campos);$i++) { 
		if($campos[$i]=='cod_original') { echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>"; }
		elseif($campos[$i]=='costo_total')  { echo "<td align='center' bgcolor='#FFCC33'><b>".number_format($cost,2)."</b></td>"; }
		elseif($campos[$i]=='cantidad') { echo "<td align='center' bgcolor='#FFCC33'><b>$qty</b></td>"; }
		else { echo "<td>&nbsp;</td>"; }
	} 
	echo "</tr>"; ?>
</tbody>
</table>
<?php }			
			


function preliminar($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$reporte) { 
 $s_e = "select encabezados.nombre, reportes.campo, encabezados.ancho from reportes, encabezados where reportes.id_emp = ";
 $s_e.= "'$_SESSION[IDEMP]' and reportes.reporte='$reporte' and reportes.excel!='0' and reportes.campo=encabezados.campo ";
 $s_e.= "order by reportes.excel, encabezados.nombre"; 
 $r_e = mysql_query($s_e); $i=0;
 $n_e = mysql_num_rows($r_e);
 while($d_e=mysql_fetch_array($r_e)) { 
	if($d_e['campo']=='cod_original') { $n_e=$n_e+2; }
	$campos[$i] = $d_e['campo'];
	$nombre[$i] = $d_e['nombre']; 
	$ancho[$i]  = $d_e['ancho'];
	$i++; } $original = 0; $n_e = $n_e + 17; ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="<?php echo $n_e;?>" align="center">
        	<b>REPORTE <?php echo strtoupper($reporte);?> DE SCRAP</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
	<?php for($i=0;$i<count($nombre);$i++) { 
		if($campos[$i]=='cod_original') { $original='1'; $rowspan='2' ?>
        <td align="center" bgcolor="#FFCC33" colspan="3"><b><?php echo $nombre[$i];?></b></td>
        <?php } else { $original='0'; $rowspan='1'; ?>
        <td align="center" width="<?php echo $ancho[$i];?>" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b><?php echo $nombre[$i];?></b></td><?php } } ?>
		<td align="center" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b>Por qué 1</b></td>
		<td align="center" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b>Por qué 2</b></td>
		<td align="center" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b>Por qué 3</b></td>
		<td align="center" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b>Por qué 4</b></td>
		<td align="center" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b>Por qué 5</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LO</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LOA</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>LPL</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>FFM</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>FFC</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Prod</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>SQM</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Finanzas</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>ESP</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>OES</b></td>
        <td align="center" bgcolor="#FFCC33" colspan="2"><b>INVENTARIOS</b></td>
</tr>
<?php if($original=='1') { ?>
<tr height="20">
  	<td align="center" width="150" bgcolor="#FFCC33"><b>Defecto</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Tecnología</b></td>
	<td align="center" width="70" bgcolor="#FFCC33"><b>Código</b></td>  
</tr><?php } ?>
</thead>
<tbody>
<?php     
	$s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, ";
	$s_f.= "partes.docto_sap, partes.deficit, partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio and  ";
	$s_f.= "autorizaciones.no_folio = folios.no_folio and partes.no_parte = numeros_parte.nombre "; 
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; } 
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); }
	$s_f.= "group by partes.id order by folios.no_folio asc";
	$r_1 = mysql_query($s_f);
	
    while($d_1=mysql_fetch_array($r_1)) { 
	   if($d_1['activo']=='1') { $qty = $qty + $d_1['cantidad']; $cost = $cost + $d_1['costo_total']; }
	   echo "<tr>";
	   for($i=0;$i<count($campos);$i++) { 
			switch($campos[$i]) {
				case "cod_original"	:	$original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
										echo "<td align='left' class='small'>&nbsp;".$original['defecto']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['estacion']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['codigo']."</td>"; break;
				case "docto_sap"	:	if($d_1['deficit']=='1') {
											echo "<td align='left' class='small'>&nbsp;Déficit de Stock</td>"; }
										else { 
											if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo "<td align='left' class='small'>&nbsp;".$d_1['docto_sap']."</td>"; } 
											else { echo "<td align='left' class='small'>&nbsp;</td>"; } } break;	
				case "info"			:	echo "<td align='left'>".$d_1['info_1'].$d_1['info_2']."</td>"; break;
				case "status"		:	if($d_1['activo']=='1') { 
										if($d_1['status']=='0') { echo "<td align='center' bgcolor='#FFCC00'>Proceso</td>";   }
										if($d_1['status']=='1') { echo "<td align='center' bgcolor='#BEF781'>Aprobado</td>";  }
										if($d_1['status']=='2') { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } }
										else { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } break;
				case "serial_unidad":	echo "<td align='left'>'".$d_1[$campos[$i]]."</td>"; break;
				case "profit_center"	:	echo "<td align='left' class='small'>&nbsp;".get_global_pc($d_1['no_parte'])."</td>";
											break;
				default				:	echo "<td align='left'>".$d_1[$campos[$i]]."</td>"; break;
	   } } $s_p = "select * from scrap_porques where no_folio='$d_1[no_folio]'"; 
	       $r_p = mysql_query($s_p); 
		   $d_p = mysql_fetch_array($r_p); ?>
		<td align="left"><?php echo $d_p['porque_1'];?></td>
		<td align="left"><?php echo $d_p['porque_2'];?></td>
		<td align="left"><?php echo $d_p['porque_3'];?></td>
		<td align="left"><?php echo $d_p['porque_4'];?></td>
		<td align="left"><?php echo $d_p['porque_5'];?></td>
		<td align="center"><?php echo get_datos("lo",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("loa",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("lpl",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("ffm",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("ffc",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("prod",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_datos("sqm",$d_1['no_folio']);?></td>	   
        <td align="center"><?php echo get_datos("fin",$d_1['no_folio']);?></td>	   
        <td align="center"><?php echo get_datos("esp",$d_1['no_folio']);?></td>	   
		<td align="center"><?php echo get_datos("oes",$d_1['no_folio']);?></td>	   
        <?php $s_i = "select fecha, hora from aut_bitacora where depto='inv' and status='1' and fecha!='0000-00-00' and no_folio='$d_1[no_folio]'";
		      $r_i = mysql_query($s_i);
			  if(mysql_num_rows($r_i)>0) { $color = "#BEF781"; } else { $color = ""; } 
			  $d_i = mysql_fetch_array($r_i); ?>
        <td align="center" width="120" bgcolor="<?php echo $color;?>"><?php echo $d_i['fecha'];?></td>
        <td align="center" width="120" bgcolor="<?php echo $color;?>"><?php echo $d_i['hora'];?></td> 
	<?php echo "</tr>"; }
	echo "<tr>";	
	for($i=0;$i<count($campos);$i++) { 
		if($campos[$i]=='cod_original') { echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>"; }
		elseif($campos[$i]=='costo_total')  { echo "<td align='center' bgcolor='#FFCC33'><b>".number_format($cost,2)."</b></td>"; }
		elseif($campos[$i]=='cantidad') { echo "<td align='center' bgcolor='#FFCC33'><b>$qty</b></td>"; }
		else { echo "<td>&nbsp;</td>"; }
	} 
	echo "</tr>"; ?>
</tbody>
</table>
<?php }			
		

		
function general($fechai,$fechaf,$buscar,$filtros,$reporte) { 
 $s_e = "select encabezados.nombre, reportes.campo, encabezados.ancho from reportes, encabezados where reportes.id_emp = ";
 $s_e.= "'$_SESSION[IDEMP]' and reportes.reporte='general' and reportes.excel!='0' and reportes.campo=encabezados.campo ";
 $s_e.= "order by reportes.excel, encabezados.nombre"; 
 $r_e = mysql_query($s_e); $i=0;
 $n_e = mysql_num_rows($r_e);
 while($d_e=mysql_fetch_array($r_e)) { 
	if($d_e['campo']=='cod_original') { $n_e=$n_e+2; }
	$campos[$i] = $d_e['campo'];
	$nombre[$i] = $d_e['nombre']; 
	$ancho[$i]  = $d_e['ancho'];
	$i++; } $original = 0; ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="<?php echo $n_e;?>" align="center">
        	<b>REPORTE <?php echo strtoupper($reporte);?> DE SCRAP</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
	<?php for($i=0;$i<count($nombre);$i++) { 
		if($campos[$i]=='cod_original') { $original='1'; $rowspan='2' ?>
        <td align="center" bgcolor="#FFCC33" colspan="3"><b><?php echo $nombre[$i];?></b></td>
        <?php } else { $original='0'; $rowspan='1'; ?>
        <td align="center" width="<?php echo $ancho[$i];?>" bgcolor="#FFCC33" rowspan="<?php echo $rowspan;?>"><b><?php echo $nombre[$i];?></b></td><?php } } ?>
</tr>
<?php if($original=='1') { ?>
<tr height="20">
  	<td align="center" width="150" bgcolor="#FFCC33"><b>Defecto</b></td>
	<td align="center" width="120" bgcolor="#FFCC33"><b>Tecnología</b></td>
	<td align="center" width="70" bgcolor="#FFCC33"><b>Código</b></td>  
</tr><?php } ?>
</thead>
<tbody>
<?php     
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
	$tot = mysql_num_rows($r_1); 
	
    while($d_1=mysql_fetch_array($r_1)) { 
	   if($d_1['activo']=='1') { $qty = $qty + $d_1['cantidad']; $cost = $cost + $d_1['costo_total']; }
	   echo "<tr>";
	   for($i=0;$i<count($campos);$i++) { 
			switch($campos[$i]) {
				case "cod_original"	:	$original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
										echo "<td align='left' class='small'>&nbsp;".$original['defecto']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['estacion']."</td>";
										echo "<td align='left' class='small'>&nbsp;".$original['codigo']."</td>"; break;
				case "docto_sap"	:	if($d_1['deficit']=='1') {
											echo "<td align='left' class='small'>&nbsp;Déficit de Stock</td>"; }
										else { 
											if($d_1['docto_sap']!='0' && $d_1['docto_sap']!='') { echo "<td align='left' class='small'>&nbsp;".$d_1['docto_sap']."</td>"; } 
											else { echo "<td align='left' class='small'>&nbsp;</td>"; } } break;	
				case "info"			:	echo "<td align='left'>".$d_1['info_1'].$d_1['info_2']."</td>"; break;
				case "status"		:	if($d_1['activo']=='1') { 
										if($d_1['status']=='0') { echo "<td align='center' bgcolor='#FFCC00'>Proceso</td>";   }
										if($d_1['status']=='1') { echo "<td align='center' bgcolor='#BEF781'>Aprobado</td>";  }
										if($d_1['status']=='2') { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } }
										else { echo "<td align='center' bgcolor='#F78181'>Cancelado</td>"; } break;
				case "serial_unidad":	echo "<td align='left'>'".$d_1[$campos[$i]]."</td>"; break;
				default				:	echo "<td align='left'>".$d_1[$campos[$i]]."</td>"; break;
	   } }
	   echo "</tr>"; }
	echo "<tr>";	
	for($i=0;$i<count($campos);$i++) { 
		if($campos[$i]=='cod_original') { echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>"; }
		elseif($campos[$i]=='costo_total')  { echo "<td align='center' bgcolor='#FFCC33'><b>".number_format($cost,2)."</b></td>"; }
		elseif($campos[$i]=='cantidad') { echo "<td align='center' bgcolor='#FFCC33'><b>$qty</b></td>"; }
		else { echo "<td>&nbsp;</td>"; }
	} 
	echo "</tr>"; ?>
</tbody>
</table>
<?php }			
		
	
function grafico($consulta,$reason) { ?>
<table align="center" border="1">
<thead>
<tr height="20">
	<td align="center" width="50" bgcolor="#FFCC33" rowspan="2">Folio</td>
	<td align="center" width="80" bgcolor="#FFCC33" rowspan="2">Fecha</td>
	<td align="center" width="60" bgcolor="#FFCC33" rowspan="2">Semana</td>
	<td align="center" width="60" bgcolor="#FFCC33" rowspan="2">Año</td>
	<td align="center" width="100" bgcolor="#FFCC33" rowspan="2">Planta</td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2">División</td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Segmento</td>
	<td align="center" width="180" bgcolor="#FFCC33" rowspan="2">P.C.</td>
	<td align="center" width="60" bgcolor="#FFCC33" rowspan="2">APD</td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Proyecto</td>
	<td align="center" width="180" bgcolor="#FFCC33" rowspan="2">Área</td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Tecnología</td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Línea</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Defecto</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Causa</td>
	<?php if($reason!=1){?><td align="center" width="100" bgcolor="#FFCC33" rowspan="2">Cod.Scrap</td><?php } ?>
    <?php if($reason==1){?><td align="center" width="100" bgcolor="#FFCC33" rowspan="2">Reason Code</td><?php } ?>
	<td align="center" width="120" bgcolor="#FFCC33" colspan="3">Causa Original</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Tipo</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">No.Parte</td>
	<td align="center" width="50" bgcolor="#FFCC33" rowspan="2">Cant</td>
	<td align="center" width="90" bgcolor="#FFCC33" rowspan="2">Costo</td>
	<td align="center" width="300" bgcolor="#FFCC33" rowspan="2">Descripción</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">No.SAP</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Supervisor</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Operador</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Info.Obligatoria</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Comentario</td>
    <td align="center" width="150" bgcolor="#FFCC33" rowspan="2">Acción Correctiva</td>
</tr>
<tr height="20">
	<td align="center" width="150" bgcolor="#FFCC33">Defecto</td>
	<td align="center" width="120" bgcolor="#FFCC33">Tenología</td>
	<td align="center" width="100" bgcolor="#FFCC33">Código</td>
</tr>    
</thead>
<tbody>
<?php  $consulta = stripslashes($consulta);
	$r_2 = mysql_query($consulta);
    while($d_2 = mysql_fetch_array($r_2)) { ?>
<tbody>	
<tr>
	<td align="center"><?php echo $d_2['no_folio'];?></td>
	<td align="center"><?php echo $d_2['fecha'];?></td>
	<td align="center"><?php echo $d_2['semana'];?></td>
	<td align="center"><?php echo $d_2['anio'];?></td>
	<td><?php echo $d_2['planta'];?></td>
	<td><?php echo $d_2['division'];?></td>
	<td><?php echo $d_2['segmento'];?></td>
	<td><?php echo $d_2['profit_center'];?></td>
	<td><?php echo $d_2['apd'];?></td>
	<td><?php echo $d_2['proyecto'];?></td>
	<td><?php echo $d_2['area'];?></td>
	<td><?php echo $d_2['estacion'];?></td>
	<td><?php echo $d_2['linea'];?></td>
    <td><?php echo $d_2['defecto'];?></td>
    <td><?php echo $d_2['causa'];?></td>
	<?php if($reason!=1){?><td><?php echo $d_2['codigo_scrap'];?></td><?php } ?>
    <?php if($reason==1){?><td><?php echo $d_2['reason_code'];?></td><?php } ?>
	<?php $original = data_codigo_original($d_2['no_folio'],$d_2['financiero']); ?>
	<td><?php echo $original['defecto']; ?></td>
    <td><?php echo $original['estacion']; ?></td>
    <td><?php echo $original['codigo']; ?></td>		
	<td><?php echo $d_2['tipo'];?></td>
    <td><?php echo $d_2['no_parte'];?></td>
	<td align="center"><?php echo $d_2['cantidad'];?></td>
	<td align="right"><?php echo number_format($d_2['total'],2);?></td>
	<td><?php echo $d_2['descripcion'];?></td>
    <td align="center"><?php echo $d_2['docto_sap'];?></td>
    <td><?php echo $d_2['supervisor'];?></td>
    <td><?php echo $d_2['operador'];?></td>
    <td><?php echo $d_2['info_1'].$d_2['info_2'];?></td>
    <td><?php echo $d_2['comentario'];?></td>
    <td><?php echo $d_2['accion_correctiva'];?></td>
</tr>	
<?php } ?>	
</tbody>
</table>
<?php } 

function corto($fechai,$fechaf,$buscar,$filtros) { ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="8" align="center"><b>REPORTE PRELIMINAR CORTO DE SCRAP</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
		<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Folio</b></td>
		<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>No. Parte</b></td>
		<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Qty</b></td>
		<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Fecha creación</b></td>
		<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Hora creación</b></td>
        <td align="center" bgcolor="#FFCC33" colspan="3"><b>Inventarios</b></td>
</tr>
<tr height="20">
	<td align="center" width="90" bgcolor="#FFCC33"><b>Status</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Fecha</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>Hora</b></td>
</tr>
</thead>
<tbody>
<?php     
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
	
    while($d_1=mysql_fetch_array($r_1)) { 
	   if($d_1['activo']=='1') { $qty = $qty + $d_1['cantidad']; }?>
	  	<tr>
            <td align="center"><?php echo $d_1['no_folio'];?></td>
            <td align="left" style="mso-number-format:'0';"><?php echo $d_1['no_parte'];?></td>
            <td align="center"><?php echo $d_1['cantidad'];?></td>
            <?php $a_folio = substr($d_1['timer'],0,4); $m_folio = substr($d_1['timer'],4,2); $d_folio = substr($d_1['timer'],6,2); 
			$h_folio = substr($d_1['timer'],8,2); $min_folio = substr($d_1['timer'],10,2); $seg_folio = substr($d_1['timer'],12,2);
			$fecha_folio = date("d-m-Y",mktime($h_folio,$min_folio,$seg_folio,$m_folio,$d_folio,$a_folio));
			$hora_folio = date("H:i:s",mktime($h_folio,$min_folio,$seg_folio,$m_folio,$d_folio,$a_folio)); ?>
            <td align="center"><?php echo $fecha_folio;?></td>
            <td align="center"><?php echo $hora_folio;?></td> 
            <td align="center"><?php echo get_datos("inv",$d_1['no_folio']);?></td>	   
            <td align="center"><?php echo $d_1['fecha'];?></td>
            <td align="center"><?php echo $d_1['hora'];?></td> 
		</tr> <?php } ?>
		<tr>	
			<td colspan="2">&nbsp;</td>
   			<td align='right' bgcolor='#FFCC33'><?php echo "$qty&nbsp;"; ?></td>
			<td align="center" colspan="5">&nbsp;</td>	
		</tr>
</tbody>
</table>
<?php }	

function turnos($fechai,$fechaf,$buscar,$filtros) { ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="6" align="center"><b>REPORTE POR TURNOS</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
		<td align="center" width="90" bgcolor="#FFCC33"><b>Folio</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Fecha</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Hora</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Turno</b></td>
		<td align="center" width="150" bgcolor="#FFCC33"><b>Empleado</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>APD</b></td>
</tr>
</thead>
<tbody>
<?php     
	$s_f = "select * from reporte_turnos where emp='$_SESSION[IDEMP]' ";
	$r_1 = mysql_query($s_f);
    while($d_1=mysql_fetch_array($r_1)) { ?>
	  	<tr>
            <td align="center"><?php echo $d_1['folio'];?></td>
            <td align="center"><?php echo $d_1['fecha'];?></td>
            <td align="center"><?php echo $d_1['hora'];?></td>
            <td align="center"><?php echo $d_1['turno'];?></td>
            <td align="left"><?php echo $d_1['empleado'];?></td>
            <td align="center" style="mso-number-format:'0';"><?php echo $d_1['apd'];?></td>
		</tr>
   <?php } ?>
</tbody>
</table>
<?php }	

function muestras($fechai,$fechaf) { ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="8" align="center"><b>REPORTE CORTO MUESTRAS</b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
<tr height="20">
		<td align="center" width="90" bgcolor="#FFCC33"><b>Folio</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Cod. Scrap</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Por qué 1</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Por qué 2</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Por qué 3</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Por qué 4</b></td>
        <td align="center" width="90" bgcolor="#FFCC33"><b>Por qué 5</b></td>
		<td align="center" width="90" bgcolor="#FFCC33"><b>Comentarios</b></td>
</tr>
</thead>
<tbody>
<?php     
	/*$s_f = "select no_folio, comentario, codigo_scrap from scrap_folios as folios where (codigo_scrap = '023-2' or codigo_scrap = '022-2' or codigo_scrap = '050-3' or  "; 
	$s_f.= "codigo_scrap = '050-4') or (comentario like '%muestra%' or comentario like '%prueba%' or comentario like '%destructiv%') ";
	$s_f.= "and fecha BETWEEN '$fechai' and '$fechaf' order by no_folio asc"; 
	$r_1 = mysql_query($s_f);*/
	$s_f = "select folios.no_folio, folios.comentario, folios.codigo_scrap ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte ";
	$s_f.= "where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and partes.no_parte = numeros_parte.nombre and "; 
	$s_f.= "((codigo_scrap = '023-2' or codigo_scrap = '022-2' or codigo_scrap = '050-3' or codigo_scrap = '050-4') or (comentario like '%muestra%' or ";
	$s_f.= "comentario like '%prueba%' or comentario like '%destructiv%')) ";
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and fecha BETWEEN '$fechai' and '$fechaf' "; }
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; }
	
	$s_f.= "group by partes.id order by folios.no_folio asc";
	$r_1 = mysql_query($s_f);
	
    while($d_1=mysql_fetch_array($r_1)) {?>
	  	<tr>
            <td align="center"><?php echo $d_1['no_folio'];?></td>
            <td align="left" style="mso-number-format:'0';"><?php echo $d_1['codigo_scrap'];?></td>
            <?php $s_i = "select * from scrap_porques where no_folio='$d_1[no_folio]'";
			$r_i = mysql_query($s_i);
			$d_i = mysql_fetch_array($r_i); ?>
			<td align="left"><?php echo $d_i['porque_1'];?></td>
			<td align="left"><?php echo $d_i['porque_2'];?></td>
			<td align="left"><?php echo $d_i['porque_3'];?></td>
			<td align="left"><?php echo $d_i['porque_4'];?></td>
			<td align="left"><?php echo $d_i['porque_5'];?></td>  
			<td align="left"><?php echo $d_1['comentario'];?></td>
		</tr> <?php } ?>
</tbody>
</table>
<?php }	?>