<?php session_name("loginUsuario"); 
   session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
include('../conexion_db.php');

menu();
switch($op) {
	case "ver_reporte_1"		:	if($grafica=='cantidades' || $grafica=='costos') {
										ver_reporte_barras($defecha,$afecha,$grafica,$tipo,$parte,$reason); }
									else { 
										ver_reporte_series($desemana,$asemana,$deanio,$aanio,$grafica,$parte,$reason); }
									break;			
	case "ver_reporte_2"		:	ver_reporte_defectos($defecha,$afecha,$grafica,$ventas,$top); break;
	
	case "ver_reporte_barras"	:	ver_reporte_barras($defecha,$afecha,$grafica,$tipo,$parte,$reason); break;		
	case "ver_reporte_series"	:	ver_reporte_series($desemana,$asemana,$deanio,$aanio,$grafica,$parte,$reason); break;		
	}	



function menu() { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo" align="center">REPORTE GRÁFICO DE SCRAP</td>
</tr>
</table><hr>
<?php } 	


function ver_reporte_barras($defecha,$afecha,$grafica,$tipo,$parte,$reason) { 
	$tabla1 = "scrap_folios as folios, scrap_partes as partes";
	$s_gen  = "select * from scrap_folios as folios, scrap_partes as partes ";
	
	//Ver si se selecciona por division, área, estación ó por número de parte
	if($tipo=='division') {
		$titulo_eje_x = "Divisiones";
		$s_cant  = "select sum(partes.cantidad) as total, folios.division as eje_x, folios.id_division from $tabla1 ";
		$s_costo = "select sum(partes.total) as total, folios.division as eje_x, folios.id_division from $tabla1 "; }
	if($tipo=='proyecto') {
		$titulo_eje_x = "Proyectos";
		$s_cant  = "select sum(partes.cantidad) as total, folios.proyecto as eje_x, folios.id_proyecto from $tabla1 ";
		$s_costo = "select sum(partes.total) as total, folios.proyecto as eje_x, folios.id_proyecto from $tabla1 "; }
	if($tipo=='area') {
		$titulo_eje_x = "Areas";
		$s_cant  = "select sum(partes.cantidad) as total, folios.area as eje_x from $tabla1  ";
		$s_costo = "select sum(partes.total) as total, folios.area as eje_x from $tabla1  "; }	
	if($tipo=='estacion') {
		$titulo_eje_x = "Tecnologias";
		$s_cant  = "select sum(partes.cantidad) as total, folios.estacion as eje_x from $tabla1  ";
		$s_costo = "select sum(partes.total) as total, folios.estacion as eje_x from $tabla1  "; }	
	if($tipo=='modelo') {
		$titulo_eje_x = "Partes";
		$s_cant  = "select sum(partes.cantidad) as total, partes.no_parte as eje_x from $tabla1  ";
		$s_costo = "select sum(partes.total) as total, partes.no_parte as eje_x from $tabla1  "; }			
		
	$filtros  = "where folios.fecha>='$defecha' and folios.fecha<='$afecha' and folios.status='1' and folios.activo='1' and ";
	$filtros .= "folios.no_folio = partes.no_folio and partes.docto_sap!='0' ";
		
	//Aplicar todos los filtros seleccionados
	//Plantas
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='plantas'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_planta='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Divisiones
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='divisiones'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_division='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Proyectos
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='proyectos'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_proyecto='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Áreas
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='areas'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_area='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Estaciones
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='estaciones'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_estacion='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }

		$s_cant  .= $filtros;
		$s_costo .= $filtros;
		$s_gen   .= $filtros;
	
	//Si seleccioné algún número de parte en concreto
	if($parte!='') { 
		$s_cant  .= " and partes.no_parte like '$parte%' ";
		$s_costo .= " and partes.no_parte like '$parte%' ";
		$s_gen   .= " and partes.no_parte like '$parte%' "; }
		
	//Agrupar por división, estación o por número de parte
	if($tipo=='division') {
		$s_cant  .= " group by folios.id_division order by total desc";
		$s_costo .= " group by folios.id_division order by total desc"; }	
	if($tipo=='proyecto') {
		$s_cant  .= " group by folios.id_proyecto order by total desc";
		$s_costo .= " group by folios.id_proyecto order by total desc"; }	
	if($tipo=='area') {
		$s_cant  .= " group by folios.id_area order by total desc";
		$s_costo .= " group by folios.id_area order by total desc"; }	
	if($tipo=='estacion') {
		$s_cant  .= " group by folios.id_estacion order by total desc";
		$s_costo .= " group by folios.id_estacion order by total desc"; }		
	if($tipo=='modelo') {	
		$s_cant  .= " group by partes.no_parte order by total desc";
		$s_costo .= " group by partes.no_parte order by total desc"; }				
		
		$s_gen   .= "order by folios.fecha desc";
	
	if($grafica=='cantidades') { 
		$r_1 = mysql_query($s_cant); $i=0; $total=0;
		$titulo_eje_y = "Cantidad";
	if(mysql_num_rows($r_1)>0) {
	while($d_1 = mysql_fetch_array($r_1)) {
		$x_label[$i]  = $d_1['eje_x'];
		$eje_y[$i]	  = $d_1['total'];
		$i++; $total = $total + $d_1['total']; } }
	else { 	
		$titulo_eje_y = "Cantidad";
		$x_label[$i]  = '0';
		$eje_y[$i]	  = '0';
		$pareto[$i]   = '0'; } }
		
	if($grafica=='costos') { 
		$r_1 = mysql_query($s_costo); $i=0; $total=0;
		$titulo_eje_y = "Costo";
	if(mysql_num_rows($r_1)>0) {
	while($d_1 = mysql_fetch_array($r_1)) {
		$x_label[$i]  = $d_1['eje_x'];
		$eje_y[$i]	  = $d_1['total'];
		$i++; $total = $total + $d_1['total']; } }
	else { 	
		$titulo_eje_y = "Cantidad";
		$x_label[$i]  = '0';
		$eje_y[$i]	  = '0';
		$pareto[$i]   = '0'; } }		
		
	//Obtener el porcentaje acumulado
	if($total>0) {
	for($i=0;$i<count($eje_y);$i++) {
		$porcentaje = $porcentaje + ($eje_y[$i]*100)/$total;
		$pareto[$i] = $porcentaje; } }		
		
	$gnum = rand(0,100);
	$chart_name="chart".strval($gnum).".png";

	if($grafica=='cantidades') { 
		graficar_cantidades($eje_y, $x_label, $pareto, $titulo_eje_x, $titulo_eje_y, "SCRAP por Piezas", $chart_name); }
	if($grafica=='costos') {	
		graficar_cantidades($eje_y, $x_label, $pareto, $titulo_eje_x, $titulo_eje_y, "SCRAP por Montos", $chart_name); }
	echo"<br><br><div align='center'><img src='charts/$chart_name' align='center'></div><br>"; 
	$r_2 = mysql_query($s_gen);
	$n_2 = mysql_num_rows($r_2);
	if($n_2<=0) { echo "<div align='center' class='naranja'><h4><b>La consulta no arrojó ningún resultado. No hay datos.</h4></div>"; } ?>

<div align="center">
<form method="post" action="excel_reportes.php?op=grafico">	
<input type="hidden" name="consulta" value="<?php echo $s_gen;?>">
<input type="hidden" name="reason" value="<?php echo $reason;?>">
<input type="submit" value="Exportar Reporte a Excel" class="texto">
</form>	
</div>
<form method="post" action="?op=ver_reporte_barras">	
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="grafica" value="<?php echo $grafica;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="50" rowspan="2">Folio</td>
	<td align="center" width="70" rowspan="2">Fecha</td>
	<td align="center" width="70" rowspan="2">Planta</td>
	<td align="center" width="70" rowspan="2">División</td>
	<td align="center" width="70" rowspan="2">Segmento</td>
	<td align="center" width="80" rowspan="2">P.C.</td>
	<td align="center" width="60" rowspan="2">APD</td>
	<td align="center" width="100" rowspan="2">Proyecto</td>
	<td align="center" width="180" rowspan="2">Área</td>
	<td align="center" width="70" rowspan="2">Tecnología</td>
	<td align="center" width="120" rowspan="2">Línea</td>
    <td align="center" width="120" rowspan="2">Defecto</td>
	<?php if($reason!=1){?><td align="center" width="70" rowspan="2">Cod.Scrap</td><?php } ?>
    <?php if($reason==1){?><td align="center" width="70" rowspan="2">Reason Code</td><?php } ?>
    <td align="center" colspan="3">Causa Original</td>
	<td align="center" width="100" rowspan="2">No.Parte</td>
	<td align="center" width="50" rowspan="2">Cant</td>
	<td align="center" width="80" rowspan="2">Costo</td>
</tr>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="150">Defecto</td>
	<td align="center" width="120">Tecnología</td>
	<td align="center" width="100">Código</td>
</tr>
</thead>
<tbody>
<?php $r_2 = mysql_query($s_gen);
   while($d_2 = mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $d_2['no_folio'];?></td>
	<td align="center"><?php echo $d_2['fecha'];?></td>
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
	<?php if($reason!=1){?><td align="center"><?php echo $d_2['codigo_scrap'];?></td><?php } ?>
    <?php if($reason==1){?><td align="center"><?php echo $d_2['reason_code'];?></td><?php } ?>
	<?php $original = data_codigo_original($d_2['no_folio'],$d_2['financiero']); ?>
    <td><?php echo $original['defecto']; ?></td>    
    <td><?php echo $original['estacion']; ?></td>    
    <td><?php echo $original['codigo']; ?></td>    
	<td><?php echo $d_2['no_parte'];?></td>
	<td align="center"><?php echo $d_2['cantidad'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_2['total'],2);?></td>
</tr>	
<?php } ?>	
</tbody>
</table><br><br><br>		
</form>	
<?php } 


function get_day_week( $week, $year = '' , $day = '1') {
   $week_en = array('Sunday','Monday','Twesday','Wednesday','Thursday','Friday','Saturday');
   if (empty($year)) $year = date('Y');
   $first_day_year = strtotime("first $week_en[$day]",mktime(0,0,0,1,1,$year));
   return date('Y-m-d', strtotime('+'.--$week.' week',$first_day_year));
}


function ver_reporte_series($desemana,$asemana,$deanio,$aanio,$grafica,$parte,$reason) { 

$defecha = get_day_week($desemana, $deanio, '1');
$afecha  = get_day_week($asemana+1, $aanio, '0');

//Obtengo el total de semanas que se van a graficar
if($deanio!=$aanio) { 
	$anios = $aanio-$deanio;
	for($i=$desemana;$i<=52;$i++) { 
		$tot++; } 
		$tot = $tot + (52*($anios-1)); 
		$tot = $tot + $asemana; }
else {
	$tot = $asemana-$desemana; } 

$sem = $desemana; $j=0;	$i = $deanio; $k=0;
while($i<=$aanio && $k<=$tot) {
	if($sem<=52) { 
		$anios_[$j]   = $i;
		$semanas_[$j] = $sem; $j++; }
	else { 
		$sem = 0; $i++; }
	$sem++; $k++;				
} 	

for($i=0;$i<count($semanas_);$i++) { 
	if($grafica=='series1') {
		$s_1  = "select sum(partes.cantidad) as total,folios.semana,folios.anio from scrap_folios as folios,scrap_partes as partes "; }
	if($grafica=='series2') {
		$s_1  = "select sum(partes.total) as total,folios.semana,folios.anio from scrap_folios as folios,scrap_partes as partes "; }	
	if($grafica=='series3') {		
		$s_1  = "select sum(partes.cantidad) as total,folios.semana,folios.anio from scrap_folios as folios,scrap_partes as partes "; }
	
	$s_1.= "where folios.semana='$semanas_[$i]' and folios.anio='$anios_[$i]' and folios.status='1' and folios.activo='1' and ";
	$s_1.= "folios.no_folio = partes.no_folio and partes.docto_sap!='0' ";
	
	$s_gen  = "select * from scrap_folios as folios,scrap_partes as partes where folios.fecha>='$defecha' and folios.fecha<='$afecha'";
	$s_gen .= " and folios.status='1' and folios.activo='1' and folios.no_folio=partes.no_folio and partes.docto_sap!='0' ";
	
//Aplicar todos los filtros seleccionados
	//Plantas
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='plantas'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_planta='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Divisiones
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='divisiones'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_division='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Proyectos
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='proyectos'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_proyecto='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Áreas
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='areas'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_area='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Estaciones
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='estaciones'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.id_estacion='$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }	

	//Si seleccioné algún número de parte en concreto
	if($parte!='') { 
		$s_1   .= " and partes.no_parte like '$parte%' ";
		$s_gen .= " and partes.no_parte like '$parte%' "; }
	
	$s_gen .= $filtros." order by folios.semana, folios.anio";
	$s_1   .= $filtros."group by folios.semana, folios.anio";
	$r_1 = mysql_query($s_1); 
	if(mysql_num_rows($r_1)<=0) { 
		$x_label[$i] = $semanas_[$i]."/".$anios_[$i];
		$eje_y[$i]	 = 0; }
	else { 
		$d_1 = mysql_fetch_array($r_1);
		$x_label[$i] = $semanas_[$i]."/".$anios_[$i];
		$eje_y[$i]	 = $d_1['total'];
		$total		 = $total+$d_1['total']; }
}
	//Ver si se selecciona por division, estación o por número de parte
	if($grafica=='series1') {
		$titulo = "Series de tiempo por piezas";
		$titulo_eje_y = "Cantidad";
		$titulo_eje_x = "Semana"; }
	if($grafica=='series2') {
		$titulo = "Series de tiempo por montos";
		$titulo_eje_y = "Costo";
		$titulo_eje_x = "Semana"; }		
	if($grafica=='series3') {
		$titulo = "Series de tiempo por porcentajes";
		$titulo_eje_y = "Porcentajes";
		$titulo_eje_x = "Semana"; }
		
	if($grafica=='series3') {
		for($i=0;$i<count($eje_y);$i++) {
		$eje_y[$i] = ($eje_y[$i]*100)/$total;} }		
		
	$gnum = rand(0,100);
	$chart_name="chart".strval($gnum).".png";
	graficar_series($eje_y, $x_label, $titulo_eje_x, $titulo_eje_y, $titulo, $chart_name); 
	echo"<br><br><div align='center'><img src='charts/$chart_name' align='center'></div><br>";
	$r_2 = mysql_query($s_gen);
	$n_2 = mysql_num_rows($r_2);
	if($n_2<=0) { echo "<div align='center' class='naranja'><h4><b>La consulta no arrojó ningún resultado. No hay datos.</h4></div>"; } ?>	
	
<div align="center">
<form method="post" action="excel_reportes.php?op=grafico">	
<input type="hidden" name="consulta" value="<?php echo $s_gen;?>">
<input type="submit" value="Exportar Reporte a Excel" class="texto">
</form>	
</div><br>	
<form method="post" action="?op=ver_reporte_series">	
<input type="hidden" name="desemana" value="<?php echo $desemana;?>">
<input type="hidden" name="asemana" value="<?php echo $asemana;?>">
<input type="hidden" name="deanio" value="<?php echo $deanio;?>">
<input type="hidden" name="aanio" value="<?php echo $aanio;?>">
<input type="hidden" name="grafica" value="<?php echo $grafica;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="50" rowspan="2">Folio</td>
	<td align="center" width="60" rowspan="2">Semana</td>
	<td align="center" width="80" rowspan="2">Planta</td>
	<td align="center" width="80" rowspan="2">División</td>
	<td align="center" width="50" rowspan="2">Segmento</td>
	<td align="center" width="70" rowspan="2">P.C.</td>
	<td align="center" width="60" rowspan="2">APD</td>
	<td align="center" width="120" rowspan="2">Proyecto</td>
	<td align="center" width="170" rowspan="2">Área</td>
	<td align="center" width="70" rowspan="2">Tecnología</td>
	<td align="center" width="100" rowspan="2">Línea</td>
    <td align="center" width="120" rowspan="2">Defecto</td>
	<?php if($reason!=1){?><td align="center" width="70" rowspan="2">Cod.Scrap</td><?php } ?>
    <?php if($reason==1){?><td align="center" width="70" rowspan="2">Reason Code</td><?php } ?>
    <td align="center" colspan="3">Cod. Causa Original</td>
	<td align="center" width="90" rowspan="2">No.Parte</td>
	<td align="center" width="50" rowspan="2">Cant</td>
	<td align="center" width="70" rowspan="2">Costo</td>
</tr>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="150">Defecto</td>
	<td align="center" width="120">Tecnología</td>
	<td align="center" width="100">Código</td>
</tr>
</thead>
<tbody>
<?php 
   $r_2 = mysql_query($s_gen);
   while($d_2 = mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $d_2['no_folio'];?></td>
	<td align="center"><?php echo $d_2['semana']."-".$d_2['anio'];?></td>
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
	<?php if($reason!=1){?><td><?php echo $d_2['codigo_scrap'];?></td><?php } ?>
    <?php if($reason==1){?><td><?php echo $d_2['reason_code'];?></td><?php } ?>
	<?php $original = data_codigo_original($d_2['no_folio'],$d_2['financiero']); ?>
    <td><?php echo $original['defecto']; ?></td>    
    <td><?php echo $original['estacion']; ?></td>    
    <td><?php echo $original['codigo']; ?></td>     
	<td><?php echo $d_2['no_parte'];?></td>
	<td align="center"><?php echo $d_2['cantidad'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_2['total'],2);?></td>
</tr>	
<?php } ?>	
</tbody>
</table>		
</form><br><br>	
<?php } 


function ver_reporte_defectos($defecha,$afecha,$grafica,$ventas,$top) { 

	$filtros = "where folios.fecha>='$defecha' and folios.fecha<='$afecha' and folios.activo='1' and folios.no_folio = partes.no_folio ";
	//Divisiones
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='divisiones'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.division like '$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Proyectos
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='proyectos'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.proyecto like '$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }
	//Defectos
		$s_ = "select * from filtros where id_emp='$_SESSION[IDEMP]' and filtro='defectos'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) {
			$filtros .= "and (";
		while($d_=mysql_fetch_array($r_)) {
		$filtros.= "folios.defecto like '$d_[valor]' or "; } 
		$filtros = substr($filtros,0,-4).") "; }

	$s_1   = "select sum(partes.total) as total, folios.defecto as eje_x, folios.id_defecto from scrap_folios as folios, scrap_partes as partes ";
	$s_g   = "select * from scrap_folios as folios, scrap_partes as partes ".$filtros." order by folios.no_folio desc";
	$s_1  .= $filtros." group by folios.defecto order by total desc limit 0, ".$top;
	$i     = 0;
	$total = 0;
	$r_1   = mysql_query($s_1);
	$n_1   = mysql_num_rows($r_1); 
	
	if(mysql_num_rows($r_1)>0) {
	while($d_1 = mysql_fetch_array($r_1)) {
		$x_label[$i]  = substr(utf8_decode($d_1['eje_x']),0,20);
		$eje_y[$i]	  = $d_1['total'];
		$pareto[$i]   = $ventas;
		$i++; $total = $total + $d_1['total']/1000; } }
	else { 	
		$x_label[$i]  = '0';
		$eje_y[$i]	  = '0';
		$pareto[$i]   = '0';
		$pareto[$i]   = '0'; } 		
		
		$titulo_eje_y = "Monto";
		$titulo_eje_x = "Defectos";	
		
	$gnum = rand(0,100);
	$chart_name="chart".strval($gnum).".png";
	graficar_defectos($eje_y, $x_label, $pareto, $titulo_eje_x, $titulo_eje_y, "Defectos por Montos", $chart_name);
	echo"<br><br><div align='center'><img src='charts/$chart_name' align='center'></div><br>"; 
	if($n_1<=0) { echo "<div align='center' class='naranja'><h4><b>La consulta no arrojó ningún resultado. No hay datos.</h4></div>"; } ?>

<div align="center">
<form method="post" action="excel_reportes.php?op=grafico">	
<input type="hidden" name="consulta" value="<?php echo $s_g;?>">
<input type="submit" value="Exportar Reporte a Excel" class="texto">
</form>	
</div>
<form method="post" action="?op=ver_reporte_defectos">	
<input type="hidden" name="grafica" value="<?php echo $grafica;?>">
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="ventas" value="<?php echo $ventas;?>">
<input type="hidden" name="top" value="<?php echo $top;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="50" rowspan="2">Folio</td>
	<td align="center" width="70" rowspan="2">Fecha</td>
	<td align="center" width="70" rowspan="2">Planta</td>
	<td align="center" width="70" rowspan="2">División</td>
	<td align="center" width="70" rowspan="2">Segmento</td>
	<td align="center" width="80" rowspan="2">P.C.</td>
	<td align="center" width="60" rowspan="2">APD</td>
	<td align="center" width="100" rowspan="2">Proyecto</td>
	<td align="center" width="180" rowspan="2">Área</td>
	<td align="center" width="70" rowspan="2">Tecnología</td>
	<td align="center" width="120" rowspan="2">Línea</td>
    <td align="center" width="120" rowspan="2">Defecto</td>
	<td align="center" width="70" rowspan="2">Cod.Scrap</td>
    <td align="center" width="70" rowspan="2">Reason Code</td>
    <td align="center" colspan="3">Causa Original</td>
	<td align="center" width="100" rowspan="2">No.Parte</td>
	<td align="center" width="50" rowspan="2">Cant</td>

	<td align="center" width="80" rowspan="2">Costo</td>
</tr>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="150">Defecto</td>
	<td align="center" width="120">Tecnología</td>
	<td align="center" width="100">Código</td>
</tr>
</thead>
<tbody>
<?php $r_2 = mysql_query($s_g);
   while($d_2 = mysql_fetch_array($r_2)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $d_2['no_folio'];?></td>
	<td align="center"><?php echo $d_2['fecha'];?></td>
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
	<td align="center"><?php echo $d_2['codigo_scrap'];?></td>
    <td align="center"><?php echo $d_2['reason_code'];?></td>
    <?php $original = data_codigo_original($d_2['no_folio'],$d_2['financiero']); ?>
    <td><?php echo $original['defecto']; ?></td>    
    <td><?php echo $original['estacion']; ?></td>    
    <td><?php echo $original['codigo']; ?></td>    
	<td><?php echo $d_2['no_parte'];?></td>
	<td align="center"><?php echo $d_2['cantidad'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_2['total'],2);?></td>
</tr>	
<?php } ?>	
</tbody>
</table><br><br><br>		
</form>	
<?php } 


function data_codigo_original($folio,$financiero) {
	if($financiero=='1') { 
		$s_o = "select * from scrap_codigos where no_folio='$folio'";
		$r_o = mysql_query($s_o);
		$d_o = mysql_fetch_array($r_o);
		$data['area'] 	  = $d_o['area'];
		$data['estacion'] = $d_o['estacion'];
		$data['linea']    = $d_o['linea'];
		$data['defecto']  = $d_o['defecto'];
		$data['causa']    = $d_o['causa'];
		$data['codigo']   = $d_o['codigo_scrap'];
	} else {
		$data['area'] 	  = "NA";
		$data['estacion'] = "NA";
		$data['linea']    = "NA";
		$data['defecto']  = "NA";
		$data['causa']    = "NA";
		$data['codigo']   = "NA"; }	
	return $data; 	
}	


function graficar_series($eje_y, $x_label, $titulo_eje_x, $titulo_eje_y, $title, $name) {  

include ("../jpgraph/src/jpgraph.php");
include ("../jpgraph/src/jpgraph_line.php");

$graph = new Graph(820,350);    
$graph->SetScale("textlin");

$graph->SetMarginColor("#FFFFFF");
$graph->img->SetMargin(80,80,60,80);
$graph->yaxis->SetTitleMargin(40);
$graph->SetBackgroundImage("../imagenes/fondo.jpg",BGIMG_FILLFRAME);

$graph->xaxis->SetTickLabels($x_label);
$graph->legend->Pos(0.03,0.3); 

$graph->title->SetColor("#000000"); 
$graph->title->Set( $title ); 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickSide(SIDE_DOWN);
$graph->yaxis->SetTickSide(SIDE_LEFT);

$graph->yaxis->title->Set($titulo_eje_y);
$graph->xaxis->title->Set($titulo_eje_x);

$graph->setcolor("#EEEEEE");
$graph->setshadow("true",3,"#aaaaaa");

	$lplot = new LinePlot($eje_y);
	$lplot->mark->SetType(MARK_FILLEDCIRCLE);
	$lplot->mark->SetFillColor("red");
	$lplot->value->Show();
	$lplot->mark->SetWidth(3);
	$lplot->SetColor("blue");
	$lplot->SetCenter();
	
	$graph->Add($lplot);		
	$graph->Stroke("charts/".$name);
}	


function graficar_cantidades($eje_y, $x_label, $pareto, $titulo_eje_x, $titulo_eje_y, $title, $name) {  

include ("../jpgraph/src/jpgraph.php");
include ("../jpgraph/src/jpgraph_line.php");
include ("../jpgraph/src/jpgraph_bar.php");

$graph = new Graph(820,350);    
$graph->SetScale("textlin");

$graph->SetMarginColor("#FFFFFF");
$graph->img->SetMargin(80,80,60,100);
$graph->yaxis->SetTitleMargin(40);
$graph->SetBackgroundImage("../imagenes/fondo.jpg",BGIMG_FILLFRAME);

$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetTickLabels($x_label);
$graph->legend->Pos(0.03,0.3); 

$graph->title->SetColor("#000000"); 
$graph->title->Set( $title ); 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->Set($titulo_eje_y);
$graph->xaxis->title->Set($titulo_eje_x);

$graph->setcolor("#EEEEEE");
$graph->setshadow("true",3,"#aaaaaa");

// Crear la gráfica de barras
	$bplot1 = new BarPlot($eje_y);
	$bplot1->SetWidth(0.3);
	$bplot1->SetYMin(0.02);
	$bplot1->SetFillColor("orange@0.2");
	$bplot1->SetShadow('darkgray');
	$bplot1->value->SetColor("darkred");
	$bplot1->value->Show();
	
// Crear la gráfica de líneas
	$lplot = new LinePlot($pareto);
	$lplot->mark->SetType(MARK_FILLEDCIRCLE);
	$lplot->mark->SetFillColor("red");
	$lplot->mark->SetWidth(3);
	$lplot->SetColor("blue");
	$lplot->SetCSIMTargets($targ,$alt);
	$lplot->SetBarCenter();
	
	$graph->Add($bplot1);		
	$graph->Stroke("charts/".$name);
} 


function graficar_defectos($eje_y, $x_label, $pareto, $titulo_eje_x, $titulo_eje_y, $title, $name) {  

include ("../jpgraph/src/jpgraph.php");
include ("../jpgraph/src/jpgraph_line.php");
include ("../jpgraph/src/jpgraph_bar.php");

$graph = new Graph(820,350);    
$graph->SetScale("textlin");

$graph->SetMarginColor("#FFFFFF");
$graph->img->SetMargin(80,80,60,140);
$graph->yaxis->SetTitleMargin(40);
$graph->SetBackgroundImage("../imagenes/fondo.jpg",BGIMG_FILLFRAME);

$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetTickLabels($x_label);
$graph->legend->Pos(0.03,0.3); 

$graph->title->SetColor("#000000"); 
$graph->title->Set( $title ); 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

//$graph->yaxis->title->Set($titulo_eje_y);
//$graph->xaxis->title->Set($titulo_eje_x);
$graph->setcolor("#EEEEEE");
$graph->setshadow("true",3,"#aaaaaa");

// Crear la gráfica de barras
	$bplot1 = new BarPlot($eje_y);
	$bplot1->SetWidth(0.3);
	$bplot1->SetYMin(0.02);
	$bplot1->SetFillColor("orange@0.2");
	$bplot1->SetShadow('darkgray');
	$bplot1->value->SetFormat("%0.1f");
	$bplot1->value->SetColor("darkred");
	$bplot1->value->Show();
	
// Crear la gráfica de líneas
	$lplot = new LinePlot($pareto);
	$lplot->mark->SetType(MARK_FILLEDCIRCLE);
	$lplot->mark->SetFillColor("red");
	$lplot->mark->SetWidth(3);
	$lplot->SetColor("blue");
	$lplot->SetBarCenter();
	
	$graph->Add($bplot1);
	$graph->Add($lplot);		
	$graph->Stroke("charts/".$name);

} ?>
</body>
</html>