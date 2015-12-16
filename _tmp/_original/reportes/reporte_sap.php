<?php 
include('../conexion_db.php');
include('funciones.php');

$s_ = "select * from configuracion where variable='ruta_sap'";
$r_ = mysql_query($s_);
$d_ = mysql_fetch_array($r_);
$ruta = $d_['valor'];

	if(!$division) $division = '%'; 
	if(!$tipo)	   $tipo     = '%';
	if(!$fechai)   $fechai	 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
	if(!$fechaf)   $fechaf	 = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

//Sección 1 reporte de TXS SAP con MB1A con Orden Interna
$table = "<table align=\"center\" border=\"1\">";
	$table.= "<tr height=\"20\">";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Mov.SAP</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>APD</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Reason Code</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Información Obligatoria</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>No.Parte</b></td>";
	$table.= "<td align=\"center\" width=\"90\" bgcolor=\"#FFCC33\"><b>Qty</b></td>";
	$table.= "<td align=\"center\" width=\"100\" bgcolor=\"#FFCC33\"><b>Batch</b></td>";
$table.= "</tr>";
	$s_1 = "select folios.*, partes.padre, partes.batch_id, partes.no_parte, partes.cantidad, partes.descripcion, partes.tipo, ";
	$s_1.= "partes.tipo_sub, partes.total, partes.docto_sap from scrap_partes as partes, scrap_folios as folios where id_division ";
	$s_1.= "like '$division' and fecha>='$fechai' and fecha<='$fechaf' and txs_sap='MB1A' and folios.no_folio = partes.no_folio and ";
	$s_1.= "folios.status='1' and folios.activo='1' and orden_interna!='NA' and partes.docto_sap!='0' ";
	
	if($folio!='') { $s_1.= "and folios.no_folio='$folio' "; }	
		$s_ = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) { $s_1.= "and ("; 
		while($d_ = mysql_fetch_array($r_)) {
			$s_1.= "folios.id_proyecto = '$d_[valor]' or "; }
	$s_1 = substr($s_1,0,-4).") "; }		
	if($parte!='') { $s_1.= " and partes.no_parte like '$parte%' "; }
	$s_1.= "order by mov_sap, codigo_scrap, orden_interna, padre";
 	$r_1 = mysql_query($s_1);  
	$i_1 = mysql_num_rows($r_1);
    while($d_1=mysql_fetch_array($r_1)) {
$table.= "<tr>";
	$table.= "<td align=\"center\">$d_1[mov_sap]</td>";
	$table.= "<td align=\"center\">$d_1[apd]</td>";
	$table.= "<td align=\"center\">$d_1[reason_code]</td>";
	$table.= "<td align=\"center\">$d_1[info_1]$d_1[info_2]</td>";
	$table.= "<td align=\"center\">$d_1[no_parte]</td>";
	$table.= "<td align=\"center\">$d_1[cantidad]</td>";	
	$table.= "<td align=\"center\">";
		if($d_1['tipo']=='FERT'){ $table.="$d_1[batch_id]"; } else { $table.="NA"; } $table.="</td>";
$table.= "</tr>"; }
$table.= "</table>";

$sfile = $ruta."mb1a_sin_io.xls";
$fp    = fopen($sfile,"w"); 
fwrite($fp,$table); 
fclose($fp);  

if($i_1<=0) { echo "<script>alert('El reporte MB1A sin OI no contiene registros');</script>"; }
else { echo "<script>alert('Se ha generado el reporte MB1A sin OI');</script>"; }

//Sección 2 reporte de TXS SAP con MB1A sin Orden Interna
$table = "<table align=\"center\" border=\"1\">";
	$table.= "<tr height=\"20\">";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Mov.SAP</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>APD</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Reason Code</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>OI</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Información Obligatoria</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>No.Parte</b></td>";
	$table.= "<td align=\"center\" width=\"90\" bgcolor=\"#FFCC33\"><b>Qty</b></td>";
	$table.= "<td align=\"center\" width=\"100\" bgcolor=\"#FFCC33\"><b>Batch</b></td>";
$table.= "</tr>";
    $s_1 = "select folios.*, partes.padre, partes.batch_id, partes.no_parte, partes.cantidad, partes.descripcion, partes.tipo, ";
	$s_1.= "partes.tipo_sub, partes.total, partes.docto_sap from scrap_partes as partes, scrap_folios as folios where id_division ";
	$s_1.= "like '$division' and fecha>='$fechai' and fecha<='$fechaf' and txs_sap='MB1A' and folios.no_folio = partes.no_folio and ";
	$s_1.= "folios.status='1' and folios.activo='1' and partes.docto_sap!='0' and orden_interna='NA' ";
	
	if($folio!='') { $s_1.= "and folios.no_folio='$folio' "; }	
		$s_ = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) { $s_1.= "and ("; 
		while($d_ = mysql_fetch_array($r_)) {
			$s_1.= "folios.id_proyecto = '$d_[valor]' or "; }
	$s_1 = substr($s_1,0,-4).") "; }	
	if($parte!='') { $s_1.= " and partes.no_parte like '$parte%' "; }
	$s_1.= "order by mov_sap, codigo_scrap, orden_interna, padre";
 	$r_1 = mysql_query($s_1);  
	$i_1 = mysql_num_rows($r_1);
    while($d_1=mysql_fetch_array($r_1)) {
$table.= "<tr>";
	$table.= "<td align=\"center\">$d_1[mov_sap]</td>";
	$table.= "<td align=\"center\">$d_1[apd]</td>";
	$table.= "<td align=\"center\">$d_1[reason_code]</td>";
	if($d_1['txs_sap']=='MB1A' && $d_1['orden_interna']!='NA'){ $table.="$d_1[orden_interna]"; } else { $table.="NA"; } $table.="</td>";
	
	$table.= "<td align=\"center\">$d_1[info_1]$d_1[info_2]</td>";
	$table.= "<td align=\"center\">$d_1[no_parte]</td>";
	$table.= "<td align=\"center\">$d_1[cantidad]</td>";	
	$table.= "<td align=\"center\">";
		if($d_1['tipo']=='FERT'){ $table.="$d_1[batch_id]"; } else { $table.="NA"; } $table.="</td>";
$table.= "</tr>"; }
$table.= "</table>";

$sfile = $ruta."mb1a_con_io.xls";
$fp    = fopen($sfile,"w"); 
fwrite($fp,$table); 
fclose($fp);  

if($i_1<=0) { echo "<script>alert('El reporte MB1A con OI no contiene registros');</script>"; }
else { echo "<script>alert('Se ha generado el reporte MB1A con OI');</script>"; }


//Sección 3 reporte de TXS SAP con MB1A sin Orden Interna
$table = "<table align=\"center\" border=\"1\">";
	$table.= "<tr height=\"20\">";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Parte Padre</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Batch</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Información Obligatoria</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>No.Parte</b></td>";
	$table.= "<td align=\"center\" width=\"90\" bgcolor=\"#FFCC33\"><b>Qty</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Planta</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>APD</b></td>";
	$table.= "<td align=\"center\" width=\"120\" bgcolor=\"#FFCC33\"><b>Reason Code</b></td>";
$table.= "</tr>";
    $s_1 = "select folios.*, partes.padre, partes.batch_id, partes.no_parte, partes.cantidad, partes.descripcion, partes.tipo, ";
	$s_1.= "partes.tipo_sub, partes.total, partes.docto_sap from scrap_partes as partes, scrap_folios as folios where id_division ";
	$s_1.= "like '$division' and fecha>='$fechai' and fecha<='$fechaf' and txs_sap='ZSCR' and folios.no_folio = partes.no_folio ";
	$s_1.= "and folios.status='1' and folios.activo='1' and partes.docto_sap!='0' ";
	
	if($folio!='') { $s_1.= "and folios.no_folio='$folio' "; }	
		$s_ = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0) { $s_1.= "and ("; 
		while($d_ = mysql_fetch_array($r_)) {
			$s_1.= "folios.id_proyecto = '$d_[valor]' or "; }
	$s_1 = substr($s_1,0,-4).") "; }	
	if($parte!='') { $s_1.= " and partes.no_parte like '$parte%' "; }
	$s_1.= "order by mov_sap, codigo_scrap, orden_interna, padre"; 
 	$r_1 = mysql_query($s_1);  
	$i_1 = mysql_num_rows($r_1);
    while($d_1=mysql_fetch_array($r_1)) {
$table.= "<tr>";
	$table.= "<td align=\"center\">$d_1[padre]</td>";
	$table.= "<td align=\"center\">";
		if($d_1['tipo']=='FERT'){ $table.="$d_1[batch_id]"; } else { $table.="NA"; } $table.="</td>";	
	$table.= "<td align=\"center\">$d_1[info_1]$d_1[info_2]</td>";
	$table.= "<td align=\"center\">$d_1[no_parte]</td>";
	$table.= "<td align=\"center\">$d_1[cantidad]</td>";
	$table.= "<td align=\"center\">$d_1[planta]</td>";	
	$table.= "<td align=\"center\">$d_1[apd]</td>";
	$table.= "<td align=\"center\">$d_1[reason_code]</td>";
$table.= "</tr>"; }
$table.= "</table>";

$sfile = $ruta."zscr.xls";
$fp    = fopen($sfile,"w"); 
fwrite($fp,$table); 
fclose($fp);  

if($i_1<=0) { echo "<script>alert('El reporte ZSCR no contiene registros');</script>"; }
else { echo "<script>alert('Se ha generado el reporte ZSCR');</script>"; } 

echo "<script>window.close();</script>"; ?>		