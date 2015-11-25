<?php 
function personalizar($reporte) {
	$s_ = "select * from encabezados where ver_reportes='1' and not exists (select * from reportes where id_emp='$_SESSION[IDEMP]' and reporte='$reporte' and ";
	$s_.= "reportes.campo = encabezados.campo) order by col_excel";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) {
		$s_2 = "insert into reportes values('','$_SESSION[IDEMP]','$reporte','$d_[campo]','$d_[col_excel]','0')";
		$r_2 = mysql_query($s_2);
		if($d_['campo']=='no_folio')    { $s_2 = "update reportes set pantalla='1' where id_emp='$_SESSION[IDEMP]' and campo='no_folio'";	$r_2 = mysql_query($s_2); }
		if($d_['campo']=='fecha')       { $s_2 = "update reportes set pantalla='2' where id_emp='$_SESSION[IDEMP]' and campo='fecha'"; 		$r_2 = mysql_query($s_2); }
		if($d_['campo']=='no_parte')    { $s_2 = "update reportes set pantalla='3' where id_emp='$_SESSION[IDEMP]' and campo='no_parte'"; 	$r_2 = mysql_query($s_2); }
		if($d_['campo']=='cantidad')    { $s_2 = "update reportes set pantalla='4' where id_emp='$_SESSION[IDEMP]' and campo='cantidad'"; 	$r_2 = mysql_query($s_2); } 
		if($d_['campo']=='codigo_scrap'){ $s_2 = "update reportes set pantalla='5' where id_emp='$_SESSION[IDEMP]' and campo='codigo_scrap'"; $r_2 = mysql_query($s_2); }
		if($d_['campo']=='costo_total') { $s_2 = "update reportes set pantalla='6' where id_emp='$_SESSION[IDEMP]' and campo='costo_total'";  $r_2 = mysql_query($s_2); }
		if($d_['campo']=='defecto') 	{ $s_2 = "update reportes set pantalla='7' where id_emp='$_SESSION[IDEMP]' and campo='defecto'"; 	$r_2 = mysql_query($s_2); }
		if($d_['campo']=='docto_sap')   { $s_2 = "update reportes set pantalla='8' where id_emp='$_SESSION[IDEMP]' and campo='docto_sap'"; 	$r_2 = mysql_query($s_2); }
		if($d_['campo']=='empleado')    { $s_2 = "update reportes set pantalla='9' where id_emp='$_SESSION[IDEMP]' and campo='empleado'"; 	$r_2 = mysql_query($s_2); }
		if($d_['campo']=='proyecto')    { $s_2 = "update reportes set pantalla='10' where id_emp='$_SESSION[IDEMP]' and campo='proyecto'"; 	$r_2 = mysql_query($s_2); }	
	}
}


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


/**/function get_fecha_semana() {
	switch(date("D")){
		case "Mon"	:	$dia_i = date("d");   $dia_f = date("d")+6; break;
		case "Tue"	:	$dia_i = date("d")-1; $dia_f = date("d")+5; break;
		case "Wed"	:	$dia_i = date("d")-2; $dia_f = date("d")+4; break;
		case "Thu"	:	$dia_i = date("d")-3; $dia_f = date("d")+3; break;
		case "Fri"	:	$dia_i = date("d")-4; $dia_f = date("d")+2; break;
		case "Sat"	:	$dia_i = date("d")-5; $dia_f = date("d")+1; break;
		case "Sun"	:	$dia_i = date("d")-6; $dia_f = date("d");   break;
	}	

$data[0] = date("Y-m-d", mktime(0,0,0,date("m"),$dia_i,date("Y")));
$data[1] = date("Y-m-d", mktime(0,0,0,date("m"),$dia_f,date("Y")));

return $data;						
}


/**/function get_bandera($depto,$folio) { 
	$s_ = "select status from autorizaciones where no_folio='$folio' and ";
	if($depto=='esp') { $s_.= "(depto='esp_1' or depto='esp_2') "; }
	else { $s_.= "depto='$depto' "; } $s_.= "order by status desc";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	switch($d_['status']) {	
			case "0"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones2&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_orange.gif' style='cursor:hand'; border=0></a>"; break;			
			case "1"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones2&folio=$folio'>";
			  				$img.= "<img src='../imagenes/flag_green.gif' style='cursor:hand'; border=0></a>"; break;			
			case "2"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones2&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_red.gif' style='cursor:hand'; border=0></a>"; break;
			case "3"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones2&folio=$folio'>";
							$img.= "<img src='../imagenes/cross.png' style='cursor:hand'; border=0></a>"; break;				
			case "NA"	:	$img = "NA"; break;	
			default		:	$img = "NA"; break;
	}
	return $img;	
}


/**/function get_datos_old($depto,$folio,$anio) { 

	$s_ = "select * from ".$anio."_autorizaciones where no_folio='$folio' and "; 
	if($depto=='esp') { $s_.= "(depto='esp_1' or depto='esp_2') "; }
	else { $s_.= "depto='$depto' "; }
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { 
		while($d_=mysql_fetch_array($r_)){
			$tabla.= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>";
			switch($d_['status']) {
				case "0"	:	$tabla.= "<tr><td bgcolor='#F3F781' align='center'>".$d_['empleado']."</td></tr>"; break;
				case "1"	:	$tabla.= "<tr><td bgcolor='#BEF781' align='center'>".$d_['empleado']."</td></tr>"; break;
				case "2"	:	$tabla.= "<tr><td bgcolor='#F78181' align='center'>".$d_['empleado']."</td></tr>"; break; }	
			$tabla.= "</table>"; } 
	} else { 
		$tabla.= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>";
		$tabla.= "<tr><td bgcolor='#D8D8D8' align='center'>NA</td></tr>";
		$tabla.= "</table>"; }	

	return $tabla;	
}


/**/function get_datos($depto,$folio) { 

	$s_ = "select * from autorizaciones where no_folio='$folio' and "; 
	if($depto=='esp') { $s_.= "(depto='esp_1' or depto='esp_2') "; }
	else { $s_.= "depto='$depto' "; }
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { 
		while($d_=mysql_fetch_array($r_)){
			$tabla.= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>";
			switch($d_['status']) {
				case "0"	:	$tabla.= "<tr><td bgcolor='#F3F781' align='center'>".$d_['empleado']."</td></tr>"; break;
				case "1"	:	$tabla.= "<tr><td bgcolor='#BEF781' align='center'>".$d_['empleado']."</td></tr>"; break;
				case "2"	:	$tabla.= "<tr><td bgcolor='#F78181' align='center'>".$d_['empleado']."</td></tr>"; break; }	
			$tabla.= "</table>"; } 
	} else { 
		$tabla.= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>";
		$tabla.= "<tr><td bgcolor='#D8D8D8' align='center'>NA</td></tr>";
		$tabla.= "</table>"; }	

	return $tabla;	
}


/**/function data_codigo_original($folio,$financiero) {
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
	
	
/**/function detalles_codigo_original($folio) {
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


/**/function get_autorizadores($depto,$query) {
	$s_1 = "select autorizaciones.empleado from autorizaciones, (".$query.") as general where autorizaciones.no_folio = general.no_folio and autorizaciones.depto='$depto' ";
	$s_1.= "and autorizaciones.status='1' group by empleado order by empleado";

	$s_1 = "select no_folio from (".$query.") as tabla_1 group by no_folio order by no_folio";
	$r_1 = mysql_query($s_1);
	while($d_1 = mysql_fetch_array($r_1)) {
		$s_ = "select empleado from autorizaciones where depto='$depto' and no_folio='$d_1[no_folio]' and status='1'";
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)) {
			$user = $d_['empleado']; 
			$data[$user]++; }
	}
	if(count($data)>0) { 
	foreach ($data as $usuario => $valor) {
		if($usuario!='') { 
    	echo $usuario."<br>"; $i++; }
	} }	
}


/**/function get_fecha($depto,$folio) {
	$s_ = "select fecha, hora from aut_bitacora where depto='$depto' and id_emp='$id_emp' and no_folio='$folio' order by fecha ";
	$s_.= "desc, hora desc";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	$data['0'] = $d_['fecha'];
	$data['1'] = $d_['hora'];
	return $data;
} 


/**/function get_usuario($id_emp) {
	$s_1 = "select usuario from empleados where id='$id_emp'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) {
		$d_1 = mysql_fetch_array($r_1);
		return $d_1['usuario']; }
	else { return ""; }	
}
 
 
/**/function get_global_pc($parte){
	$s_1 = "select global_pc from numeros_parte where nombre='$parte'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['global_pc'];
}

function mantto_turnos(){
	/*PRIMER DÍA AÑO 2014 = 01-01-2014 al 04-01-2014 = TURNO 6 y 7
	SECUENCIA:
		PRIMERA SEMANA: 				4 días (6 y 7)
		SEGUNDA SEMANA: 4 días (4 y 5), 3 días (6 y 7)
		TERCERA SEMANA: 3 días (4 y 5) */
	
	//CREAR NUEVO
	$hoy = date("Y-m-d");
	$fecha_ultima = "0000-00-00";
	//$fecha_i = "2014-09-07"; $fecha_f = "2014-09-09";
	/*$fecha_i = "2014-09-21"; $fecha_f = "2014-09-23";
	echo $hoy."<br>";
	if(!($hoy>=$fecha_i && $hoy<=$fecha_f)){ echo "HOLA"; } else { echo "AQUI"; }
	echo "<br>";
	if($hoy>=$fecha_i && $hoy<=$fecha_f){ echo "VACIO"; } else { echo "HAGO CODIGO"; }
	exit;*/
	do{
		$s_ = "select tmp_turnos.*, DATEDIFF(fecha_2,fecha_1) as diferencia from tmp_turnos where anio = YEAR(CURDATE()) order by id desc";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		$fecha_i = $d_['fecha_1'];
		$fecha_f = $d_['fecha_2'];
		$turno 	 = $d_['turno_1'];
		$diferencia = $d_['diferencia'];
		$r_1 = mysql_query($s_1);
		if(!($hoy>=$fecha_i && $hoy<=$fecha_f)){
			//**//
			//DIFERENCIA 3 = 4 días, DIFERENCIA 2 = 3 días
			if($diferencia=='2'){
				if($turno=='4'){
					//SIGUIENTE SEMANA TURNO 6 y 7 / FECHA 4 días
					$fecha_1 = date("Y-m-d",strtotime("+1 day",strtotime($fecha_f)));
					$fecha_2 = date("Y-m-d",strtotime("+4 day",strtotime($fecha_f)));
					$turno_1 = '6'; $turno_2 = '7';
				}
				if($turno=='6'){
					//SIGUIENTE SEMANA TURNO 4 y 5 / FECHA 3 días
					$fecha_1 = date("Y-m-d",strtotime("+1 day",strtotime($fecha_f)));
					$fecha_2 = date("Y-m-d",strtotime("+3 day",strtotime($fecha_f)));
					$turno_1 = '4'; $turno_2 = '5';
				}
			} 
			if($diferencia=='3'){
				if($turno=='4'){
					//SIGUIENTE SEMANA TURNO 6 y 7 / FECHA 3 días
					$fecha_1 = date("Y-m-d",strtotime("+1 day",strtotime($fecha_f)));
					$fecha_2 = date("Y-m-d",strtotime("+3 day",strtotime($fecha_f)));
					$turno_1 = '6'; $turno_2 = '7';
				}
				if($turno=='6'){
					//SIGUIENTE SEMANA TURNO 4 y 5 / FECHA 4 días
					$fecha_1 = date("Y-m-d",strtotime("+1 day",strtotime($fecha_f)));
					$fecha_2 = date("Y-m-d",strtotime("+4 day",strtotime($fecha_f)));
					$turno_1 = '4'; $turno_2 = '5';
				}
			}
			$s_ = "insert into tmp_turnos values ('','$fecha_1','$fecha_2','$turno_1','$turno_2',YEAR(CURDATE()))";
			$r_ = mysql_query($s_);
		}
		$fecha_ultima = date("Y-m-d",strtotime("+1 day",strtotime($fecha_2)));
	} while($fecha_ultima<=$hoy);	

	//ELIMINO LOS TMP ANTERIORES DE AÑOS ANTERIORES
	$s_ = "select * from tmp_turnos where anio<YEAR(CURDATE())";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0){
		$s_2 = "truncate tmp_turnos";
		$r_2 = mysql_query($s_2);
	} 
	$s_2 = "update configuracion set valor=CURDATE() where variable='mantto_turnos'"; 
	$r_2 = mysql_query($s_2);
}?>