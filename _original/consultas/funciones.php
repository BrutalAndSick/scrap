<?php 
function get_operador($campo,$buscar) {
 $s_ = "select valor, operador from encabezados where campo='$campo'";
 $r_ = mysql_query($s_);
 $d_ = mysql_fetch_array($r_);
 if($d_['valor']=='info') {
 	$operador = "concat(folios.info_1,folios.info_2) like '".$buscar."%'"; }
 else { 
 if($d_['operador']=='like') {
 	$operador = $d_['valor']." like '".$buscar."%'"; }
 else { 
 	$operador = $d_['valor']." = '".$buscar."'"; } }
	return $operador;
}
		 
function data_codigo_original($folio) {
		$s_o = "select * from scrap_codigos where no_folio='$folio'";
		$r_o = mysql_query($s_o);
		$d_o = mysql_fetch_array($r_o);
		$data['area'] 	  = $d_o['area'];
		$data['estacion'] = $d_o['estacion'];
		$data['linea']    = $d_o['linea'];
		$data['defecto']  = $d_o['defecto'];
		$data['causa']    = $d_o['causa'];
		$data['codigo']   = $d_o['codigo_scrap'];
	return $data; 	
}		 
		
function detalles_codigo_original($folio) {
		$s_o = "select * from scrap_codigos where no_folio='$folio'";
		$r_o = mysql_query($s_o);
		$d_o = mysql_fetch_array($r_o);
		$tabla.= "<b>&Aacute;rea:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$d_o[area]<br>";
		$tabla.= "<b>Estaci&oacute;n:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$d_o[estacion]<br>";
		$tabla.= "<b>L&iacute;nea:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$d_o[linea]<br>";
		$tabla.= "<b>Defecto:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$d_o[defecto]<br>";
		$tabla.= "<b>Causa:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$d_o[causa]<br>";
		$tabla.= "<b>Cod. Scrap:</b>&nbsp;&nbsp;$d_o[codigo_scrap]<br>";
	return $tabla;		
}			

function get_status_folio($folio) {
	$s_ = "select * from autorizaciones where no_folio='$folio' and status='0'";
	$r_ = mysql_query($s_);
	$data['p'] = mysql_num_rows($r_);
	
	$s_ = "select * from autorizaciones where no_folio='$folio' and status='1'";
	$r_ = mysql_query($s_);
	$data['a'] = mysql_num_rows($r_);

	$s_ = "select * from autorizaciones where no_folio='$folio' and status='2'";
	$r_ = mysql_query($s_);
	$data['r'] = mysql_num_rows($r_);		
	
	$data['t'] = $data['p']+$data['a']+$data['r'];
	return $data; 	
} 

function get_bandera($depto,$folio) { 
$data['t'] = $data['p'] = $data['a'] = $data['r'] = 0;

if($depto=='lpl' || $depto=='ffc' || $depto=='ffm' || $depto=='prod') {
	$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto'"; 
	$r_ = mysql_query($s_);
	$t_ = mysql_num_rows($r_);
	if($t_>0) { 
		$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto' and status='1'"; 
		$r_ = mysql_query($s_);
		$a_ = mysql_num_rows($r_);

		$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto' and status='2'"; 
		$r_ = mysql_query($s_);
		$r_ = mysql_num_rows($r_);

		if($r_>0) { $status = '2'; }
		elseif($a_==$t_) { $status = '1'; }
		else { $status = '0'; }
	}
	else {	$status = "NA"; }
}	

if($depto=='lo' || $depto=='loa' || $depto=='sqm' || $depto=='inv') {	
	$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto'"; 
	$r_ = mysql_query($s_);	
	if(mysql_num_rows($r_)>0) { 
		$d_ = mysql_fetch_array($r_);
		$status = $d_['status']; }
	else { 
		$status = "NA"; }	
}
	switch($status) {	
			case "0"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_orange.gif' style='cursor:hand'; border=0></a>"; break;			
			case "1"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
			  				$img.= "<img src='../imagenes/flag_green.gif' style='cursor:hand'; border=0></a>"; break;			
			case "2"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_red.gif' style='cursor:hand'; border=0></a>"; break;
			case "3"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/cross.png' style='cursor:hand'; border=0></a>"; break;					
			case "NA"	:	$img = "NA"; break;				
		}
	return $img;	
} ?>