<?php session_name("loginUsuario"); 
      session_start(); 
$file_name="reporte_scrap_".date("Ymd").".xls";
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$file_name"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php include('../conexion_db.php');
      include('funciones.php');
      include('filtros.php'); ?>
      
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="30" align="center"><b>Reporte de SCRAP
			<?php switch($tipo) {
				case "proceso"		:	echo "en Proceso"; break;
				case "rechazados"	:	echo "Rechazado";  break; 
				case "cancelados"	:	echo "Cancelado";  break;
				case "aprobados"	:	echo "Aprobado";   break; } ?></b></td>
	</tr>
</thead>	
</table><br>

<table align="center" border="1">
<thead>
	<tr height="20">
	<td align="center" width="50" bgcolor="#FFCC33" rowspan="2"><b>No.Item</b></td>
	<td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Fecha</b></td>
	<td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>No.Parte</b></td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2"><b>Descripción</b></td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2"><b>Qty</b></td>
	<td align="center" width="150" bgcolor="#FFCC33" rowspan="2"><b>Turno</b></td>
	<?php if($reason!=1){ ?><td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Código Scrap</b></td><?php } ?>
	<?php if($reason==1){ ?><td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Reason Code</b></td><?php } ?>
	<td align="center" width="100" bgcolor="#FFCC33" rowspan="2"><b>Valor</b></td>
	<td align="center" width="100" bgcolor="#FFCC33" rowspan="2"><b>Proceso</b></td>
	<td align="center" width="200" bgcolor="#FFCC33" rowspan="2"><b>Proyecto</b></td>
    <td align="center" width="200" bgcolor="#FFCC33" rowspan="2"><b>Defecto</b></td>    
    <td align="center" bgcolor="#FFCC33" colspan="6"><b>Cod.Causa Original</b></td>  
	<td align="center" width="120" bgcolor="#FFCC33" rowspan="2"><b>Posición</b></td>
	<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Información Obligatoria</b></td>
	<td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Supervisor</b></td>
    <?php if($tipo=='cancelados') {?>
    <td align="center" width="90" bgcolor="#FFCC33" rowspan="2"><b>Motivo</b></td><?php } ?>
	<td align="center" colspan="9" bgcolor="#FFCC33"><b>Autorizaciones</b></td>
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
	<td align="center" width="90" bgcolor="#FFCC33"><b>PROD</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>SQM</b></td>
    <td align="center" width="90" bgcolor="#FFCC33"><b>Finanzas</b></td>
	<td align="center" width="90" bgcolor="#FFCC33"><b>INV</b></td>
</tr>
</thead>
<tbody>
<?php 
	if($tipo!='proceso') { 
	if(!$fechai) { $fechai = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));   }
	if(!$fechaf) { $fechaf = date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); } }

	$s_f = "select * from (select folios.turno, folios.id_emp, fecha, planta, carga_masiva, proyecto, id_division, division, profit_center, area, ";
	$s_f.= "codigo_scrap, partes.*, folios.estacion, folios.defecto, folios.supervisor, reason_code, financiero, archivo, info_1, info_2, (select motivo from ";
	$s_f.= "aut_bitacora where aut_bitacora.no_folio=folios.no_folio and aut_bitacora.status='3' order by id desc limit 0,1) as motivo from scrap_folios as folios, ";
	$s_f.= "scrap_partes as partes, autorizaciones where folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio ";	
	switch($tipo) {
		case "proceso"		:	$s_f.= "and folios.activo='1' and folios.status='0' "; break;
		case "aprobados"	:	$s_f.= "and folios.activo='1' and folios.status='1' "; break;
		case "cancelados"	:	$s_f.= "and folios.activo='2' "; break;
		case "rechazados"	:	$s_f.= "and folios.activo='1' and folios.status='2' "; break;
	}
	if($editada=='1') { $s_f.= " and editada='1' "; }
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; }
	if($aplica_oes!='si') { $s_f.= " and autorizaciones.depto = 'inv' "; }
	if($fechai!='' && $fechaf!='') { $s_f.= " and (fecha>='$fechai' and fecha<='$fechaf') "; }
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 
	}
	$s_f.= filtros_capturista(); 
	$s_f.= " group by partes.id order by folios.no_folio ASC) as general ";
	if($motivo!='') { $s_f.= " where motivo like '$motivo' "; } 
	$r_1 = mysql_query($s_f);
	while($d_1=mysql_fetch_array($r_1)) { 
	 $qty  = $qty+$d_1['cantidad']; 
	 $cost = $cost+$d_1['total']; ?>
<tr>
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo $d_1['fecha'];?></td>
	<td align="left"><?php echo $d_1['no_parte'];?></td>
	<td align="left"><?php echo $d_1['descripcion'];?></td>
	<td align="center"><?php echo $d_1['cantidad'];?></td>
	<td align="center"><?php echo $d_1['turno'];?></td>
	<?php if($reason!=1){ ?><td align="center"><?php echo $d_1['codigo_scrap'];?></td><?php } ?>
	<?php if($reason==1){ ?><td align="center"><?php echo $d_1['reason_code'];?></td><?php } ?>
 	<td align="right" class="small"><?php echo number_format($d_1['total'],2);?></td>
	<td align="left"><?php echo $d_1['estacion'];?></td>
	<td align="left"><?php echo $d_1['proyecto'];?></td>
    <td align="left"><?php echo $d_1['defecto'];?></td>
    	<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); ?>
    <td align="left"><?php echo $original['area'];?>&nbsp;</td>
    <td align="left"><?php echo $original['estacion'];?>&nbsp;</td>
    <td align="left"><?php echo $original['linea'];?>&nbsp;</td>
    <td align="left"><?php echo $original['defecto'];?>&nbsp;</td>
    <td align="left"><?php echo $original['causa'];?>&nbsp;</td>
    <td align="center"><?php echo $original['codigo'];?>&nbsp;</td>  
	<td align="center"><?php echo $d_1['ubicacion'];?></td>
	<td align="center"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
	<td align="left"><?php echo $d_1['supervisor'];?></td>
    <?php if($tipo=='cancelados'){?>
    <td align="left"><?php echo $d_1['motivo'];?></td><?php } ?>
	<td align="center"><?php echo get_datos("lo",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("loa",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("lpl",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("ffm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("ffc",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("prod",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("sqm",$d_1['no_folio']);?></td>
    <td align="center"><?php echo get_datos("fin",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_datos("inv",$d_1['no_folio']);?></td>	
</tr>
<?php } ?>
<tr>
	<td align="right" colspan="4"><b>Totales&nbsp;&nbsp;</b></td>
	<td align="center" bgcolor="#FFCC33"><b><?php echo $qty;?></b></td>
	<td align="center" colspan="2">&nbsp;</td>
	<td align="center" bgcolor="#FFCC33"><b><?php echo number_format($cost,2);?></b></td>
	<td align="center" colspan="22">&nbsp;</td>
</tr>
</tbody>
</table>


<?php function get_datos($depto,$folio) {
	$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {
		$tabla.= "<table align='center' border='0' cellpadding='0' cellspacing='2'>";
		while($d_=mysql_fetch_array($r_)){
		 	if($d_['status']=='0') { $tabla.= "<tr bgcolor='#F3F781'><td>".$d_['empleado']."</td></tr>"; }
			if($d_['status']=='1') { $tabla.= "<tr bgcolor='#BEF781'><td>".$d_['empleado']."</td></tr>"; }
			if($d_['status']=='2') { $tabla.= "<tr bgcolor='#F78181'><td>".$d_['empleado']."</td></tr>"; }
			if($d_['status']=='3') { $tabla.= "<tr bgcolor='#F78181'><td>".$d_['empleado']."</td></tr>"; }
		} $tabla.="</table>";
	} else {
		$tabla = "<table align='center' border='0' cellpadding='0' cellspacing='0'>";
		$tabla.= "<tr bgcolor='#DDDDDD'><td>NA</td></tr></table>"; }
	
	return $tabla;
} ?>