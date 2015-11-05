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

/**/function get_tipo($parte) {
	//Si el material es ROH, entonces obtengo el tipo como informativo (HAWA, ROH, HIBE, VERP)
	//Si el material es HALB, entonces el tipo es la tabla y el subtipo es el tipo (S.Real, S.Real/AutoBF)
	//Si el material es FERT, entonces el tipo es la tabla
	$s_1 = "select numeros_parte.*, unidades.decimales from numeros_parte, unidades where numeros_parte.nombre='$parte' and ";
	$s_1.= "numeros_parte.unidad = unidades.unidad and numeros_parte.activo='1' ";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	switch($d_1['tabla']) {
		case "roh"	:	
			$data['tipo']  	   = $d_1['tipo'];
			$data['subt']  	   = ""; 
			$data['desc']  	   = $d_1['descripcion'];
			$data['costo'] 	   = $d_1['costo'];
			$data['decimales'] = $d_1['decimales'];
			$data['global_pc'] = $d_1['global_pc'];
		break;	
		case "halb"	:	
			$data['tipo']  = "HALB";
			$data['subt']  = $d_1['tipo']; 
			$data['desc']  = $d_1['descripcion'];
			$data['costo'] = $d_1['costo'];
			$data['decimales'] = $d_1['decimales'];
			$data['global_pc'] = $d_1['global_pc'];
		break;
		case "fert"	:	
			$data['tipo']  = $d_1['tipo']; 
			$data['subt']  = ""; 
			$data['desc']  = $d_1['descripcion'];
			$data['costo'] = $d_1['costo'];
			$data['decimales'] = $d_1['decimales'];
			$data['global_pc'] = $d_1['global_pc'];
		break;
	}
	return $data;
}


/**/function is_bloqueado() {
	$s_ = "select valor from configuracion where variable='bloqueado'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['valor']; }



/**/function get_batch($parte) {
	$s_1 = "select batch_id from batch_ids where parte='$parte' and saved='1'";
	$r_1 = mysql_query($s_1); $i=0;
	while($d_1 = mysql_fetch_array($r_1)) { 
		$data[$i] = $d_1['batch_id']; 
		$i++; 
	}
	return $data; }
	
/**/function get_nombre_batch($id) {
	$s_1 = "select batch_id from batch_id where id='$id'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['batch_id']; }


/**/function get_datos_proyecto($proyecto) {
	if($proyecto) { 
	$s_1 = "select plantas.nombre as planta, plantas.id as id_planta, divisiones.nombre as division, divisiones.id as id_division, ";
	$s_1.= "segmentos.nombre as segmento, segmentos.id as id_segmento, proyectos.id as id_pr, proyectos.nombre as proyecto from ";
	$s_1.= "plantas, divisiones, segmentos, proyectos where proyectos.id_segmento = segmentos.id and segmentos.id_division = ";
	$s_1.= "divisiones.id and divisiones.id_planta = plantas.id and proyectos.id='$proyecto' and divisiones.activo='1' and ";
	$s_1.= "plantas.activo='1' and segmentos.activo='1' and proyectos.activo='1'";
	$r_1 = mysql_query($s_1);
	while($d_1 = mysql_fetch_array($r_1)) {
		$datos['nom']	= $d_1['nombre'];
		$datos['id_p']  = $d_1['id_planta'];
		$datos['nom_p'] = $d_1['planta'];
		$datos['id_d']  = $d_1['id_division'];
		$datos['nom_d'] = $d_1['division'];
		$datos['id_s']  = $d_1['id_segmento'];
		$datos['nom_s'] = $d_1['segmento'];
		$datos['id_pr']  = $d_1['id_pr'];
		$datos['nom_pr'] = $d_1['proyecto'];		
	}
	$s_1 = "select profit_center.id, profit_center.nombre from profit_center, proyectos where profit_center.id_planta='$datos[id_p]' and profit_center.id_division='$datos[id_d]' and profit_center.id_segmento='$datos[id_s]' and profit_center.activo='1' and profit_center.id = proyectos.id_pc and proyectos.id = '$proyecto'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
		$datos['id_pc'] = $d_1['id'];
		$datos['nom_pc']= $d_1['nombre'];
	
	return $datos;
} }


/**/function get_supervisor($id_) {
	$s_1 = "select nombre, apellidos from empleados where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return trim($d_1['nombre']." ".$d_1['apellidos']); 
}


/**/function data_cod_scrap($prce, $codigo_scrap) {
	$s_1 = "select * from codigo_scrap where codigo='$codigo_scrap' and activo='1'"; 
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	if($d_1['orden_interna']=='1') {
		$len = strlen($codigo_scrap);
		//$ini = $len-1;
		$data['oi'] = "B".$prce.substr($codigo_scrap,1,2); }
	else { 
		$data['oi'] = "NA"; }
	
	if($d_1['info_ad']=='1') {
		$data['info'] = "SI"; }
	else { $data['info'] = "NO"; }
	
	$data['rc']  = $d_1['reason_code'];
	$data['txs'] = $d_1['txs_sap'];
	$data['mov'] = $d_1['mov_sap'];
	$data['fin'] = $d_1['financiero'];
	
	return $data;
}
		 
/**/function data_codigo_original($folio) {
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
		
/**/function get_folio() {
	$s_1 = "select * from configuracion where variable='folio'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['valor']; }
	
	
/**/function aumenta_folio() {
	$s_1 = "Update configuracion set valor = valor + 1 where variable='folio'";
	$r_1 = mysql_query($s_1); }		

	
/**/function get_dato($campo,$id,$tabla) {
	$s_1 = "select $campo as campo from $tabla where id='$id'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['campo']; }
	
/**/function aplica_lo_loa($planta,$codigo_scrap,$division,$prce,$area,$proyecto) {
	//Obtener los departamentos que deben autorizar en base al código de scrap
	//No aplica el archivo en el caso de código de scrap: 064-2, 025-4 y 062-4
	if($codigo_scrap!='064-2' && $codigo_scrap!='025-4' && $codigo_scrap!='062-4') {
	$s_1 = "select * from codigo_scrap_depto where codigo_scrap='$codigo_scrap' and id_planta='$planta' and (tipo='lo' or tipo='loa')";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) {
		return "SI"; } else { return "NO"; } 
	} else { return "NO"; }	
}		
	
/**/function validar_autorizadores($planta,$codigo_scrap,$division,$prce,$area,$proyecto) {
	$mensaj_1 = 'Hay un error con los autorizadores asignados al código de scrap seleccionado. Contacte al administrador del sistema.';
	$alerta_1 = utf8_encode($mensaj_1);
	$mensaj_2 = 'Hay un error con los autorizadores especiales de monto mayor o menor a 50,000. Contacte al administrador del sistema.';
	$alerta_2 = utf8_encode($mensaj_2);

	if($planta!='' && $codigo_scrap!='' && $division!='' && $prce!='' && $area!='') {

	//Si el proyecto no requiere autorización especial:
	$s_1 = "select apr_especial from proyectos where id='$proyecto'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); $apr_especial = $d_1['apr_especial'];
	
	if($apr_especial!='si') { 
	//1.Obtener los departamentos que deben autorizar en base al código de scrap que no sean LPL
	$s_1 = "select * from codigo_scrap_depto where codigo_scrap='$codigo_scrap' and id_planta='$planta' and (id_proyecto='0' or id_proyecto='$proyecto') order by tipo";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) { 
		while($d_1=mysql_fetch_array($r_1)) {
		//Revisar si existen personas asignadas a cada nivel de autorización
		if($d_1['tipo']=='lo' || $d_1['tipo']=='loa' || $d_1['tipo']=='sqm' || $d_1['tipo']=='fin' || $d_1['tipo']=='inv') {
			$s_ = "select * from empleados where autorizador='$d_1[tipo]' and activo='1'";
			$r_ = mysql_query($s_);
			if(mysql_num_rows($r_)<=0) { $error++; }
		}	
		if($d_1['tipo']=='lpl' || $d_1['tipo']=='ffm' || $d_1['tipo']=='ffc') { 
			//Debe existir un responsable general y/o uno por centro de costos al menos
			$s_ = "select autorizadores.* from autorizadores, empleados where tipo='$d_1[tipo]' and id_division = '$division' and ";
			$s_.= "(id_pc='%' or id_pc='$prce') and (id_proyecto='%' or id_proyecto='$proyecto') and autorizadores.id_emp = "; 
			$s_.= "empleados.id and empleados.activo='1'"; //echo $s_."<br>";
			$r_ = mysql_query($s_);
			if(mysql_num_rows($r_)<=0) { $error++; }
		}
		if($d_1['tipo']=='prod') { 
			//Debe existir un responsable para producción por área y división
			$s_ = "select autorizadores.* from autorizadores, empleados where tipo='$d_1[tipo]' and id_division='$division' and ";
			$s_.= "(id_area='$area' or id_area='%') and (id_proyecto='%' or id_proyecto='$proyecto') and autorizadores.id_emp = ";
			$s_.= "empleados.id and empleados.activo='1'"; //echo $s_."<br>";
			$r_ = mysql_query($s_);
			if(mysql_num_rows($r_)<=0) { $error++; }
		} }
		if($error>0) {
		 echo"<script>form1.codigo_scrap.value='';</script>";
	 	 echo"<script>alert('$alerta_1');</script>"; }
	} else { 		 
	echo"<script>form1.codigo_scrap.value='';</script>";
	echo"<script>alert('$alerta_1');</script>";	
	} 
	
	} elseif($apr_especial=='si') {  
		//Revisar si existen personas asignadas a cada nivel de autorización (menor a 50000 y mayor a 5000) y a este proyecto (o todos) aún no sabemos el monto total
		$s_ = "select empleados.* from empleados, autorizadores where empleados.autorizador='esp' and empleados.id = autorizadores.id_emp and autorizadores.tipo='esp_1' ";
		$s_.= "and empleados.activo='1' and (id_proyecto='%' or id_proyecto='$proyecto')"; 
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { $error++; }

		$s_ = "select empleados.* from empleados, autorizadores where empleados.autorizador='esp' and empleados.id = autorizadores.id_emp and autorizadores.tipo='esp_2' ";
		$s_.= "and empleados.activo='1' and (id_proyecto='%' or id_proyecto='$proyecto')";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { $error++; }		
		if($error>0) {
		 echo"<script>form1.codigo_scrap.value='';</script>";
	 	 echo"<script>alert('$alerta_2');</script>"; }
	
	} else { 
	 echo"<script>form1.codigo_scrap.value='';</script>";
 	 echo"<script>alert('$alerta_1');</script>"; } }
}		 


/**/function autorizaciones($folio,$planta,$codigo_scrap,$proyecto,$total) {
	$sqm = 0; 
	
	//Borrar autorizaciones no aprobadas
	$s_1 = "delete from autorizaciones where no_folio='$folio' and status='0' and depto!='inv'";
	$r_1 = mysql_query($s_1); 
	
	//Revisar si el folio requiere de autorización OES (si algún global pc del material termina con 056)
	$s_1 = "select * from scrap_partes, numeros_parte where scrap_partes.no_folio='$folio' and scrap_partes.no_parte = numeros_parte.nombre and numeros_parte.activo!='2' ";
	$s_1.= "and (numeros_parte.tipo = scrap_partes.tipo or numeros_parte.tipo = scrap_partes.tipo_sub) and numeros_parte.global_pc like '%-056'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) {
		$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='oes'";
		$r_3 = mysql_query($s_3);
		if(mysql_num_rows($r_3)<=0) { 
			$s_2 = "insert into autorizaciones values('', '$folio', 'oes', '%', '', '0', '')";
			$r_2 = mysql_query($s_2);
			/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
		} 
	}			
	
	//Si el proyecto no requiere autorización especial:
	$s_1 = "select apr_especial from proyectos where id='$proyecto'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); $apr_especial = $d_1['apr_especial'];

	if($apr_especial!='si') {  
		$s_1 = "select * from codigo_scrap_depto where codigo_scrap='$codigo_scrap' and id_planta='$planta' and (id_proyecto='0' or id_proyecto='$proyecto') order by tipo";
		$r_1 = mysql_query($s_1); 
		while($d_1=mysql_fetch_array($r_1)) {
			$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='$d_1[tipo]'";
			$r_3 = mysql_query($s_3);
			if(mysql_num_rows($r_3)<=0) { 
				$s_2 = "insert into autorizaciones values('', '$folio', '$d_1[tipo]', '%', '', '0', '')";
				$r_2 = mysql_query($s_2);
				if($d_1['tipo']=='sqm') { $sqm++; }
				/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
			}
		}
		
		/*Si no se ha insertado SQM y el código de scrap es 10-3, 10-4, 10-5, 10-6, 12-1 ó 13-1 se requiere SQM obligatorio*/
		if(($codigo_scrap=='010-3' || $codigo_scrap=='010-4' || $codigo_scrap=='010-5' || $codigo_scrap=='010-6' || $codigo_scrap=='012-1' || $codigo_scrap=='013-1')
		&& $sqm==0){
			$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='sqm'";
			$r_3 = mysql_query($s_3);
			if(mysql_num_rows($r_3)<=0) { 
				$s_2 = "insert into autorizaciones values('', '$folio', 'sqm', '%', '', '0', '')";
				$r_2 = mysql_query($s_2);
				/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
			} 
		}

		$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='inv'";
		$r_3 = mysql_query($s_3);
		if(mysql_num_rows($r_3)<=0) { 
			$s_2 = "insert into autorizaciones values('', '$folio', 'inv', '%', '', '0', '')";
			$r_2 = mysql_query($s_2); 
			/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
		}
		
		/*Si el monto es mayor o igual a $100,000 requiere autorización de FFM*/
		if($total>=100000) {
			$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='ffm'";
			$r_3 = mysql_query($s_3);
			if(mysql_num_rows($r_3)<=0) { 
				$s_2 = "insert into autorizaciones values('', '$folio', 'ffm', '%', '', '0', '')";
				$r_2 = mysql_query($s_2); 
				enviar_aviso_ffm($folio);
				/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
			} 
		} 
	} elseif($apr_especial=='si') { 
		if($total<=50000) { 
			$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='esp_1'";
			$r_3 = mysql_query($s_3);
			if(mysql_num_rows($r_3)<=0) { 
				$s_2 = "insert into autorizaciones values('', '$folio', 'esp_1', '%', '', '0', '')";
				$r_2 = mysql_query($s_2);
				/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2); 
			} 
		}
		if($total>50000) { 
			$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='esp_2'";
			$r_3 = mysql_query($s_3);
			if(mysql_num_rows($r_3)<=0) { 
				$s_2 = "insert into autorizaciones values('', '$folio', 'esp_2', '%', '', '0', '')";
				$r_2 = mysql_query($s_2);
				/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2);		
			} 
		}		
		/*$s_3 = "select * from autorizaciones where no_folio='$folio' and depto='esp_2'";
		$r_3 = mysql_query($s_3);
		if(mysql_num_rows($r_3)<=0) { */
		$s_2 = "delete autorizaciones where no_folio='$folio' and (depto!='inv' and depto!='esp_1' and depto!='esp_2') ";
		$r_2 = mysql_query($s_2);
		$s_2 = "insert into autorizaciones values('', '$folio', 'inv', '%', '', '0', '')";
		$r_2 = mysql_query($s_2); 
		/*LOG SISTEMA*/log_sistema("autorizaciones","nuevo",$folio,$s_2);
		//} 
	}
}				


/**/function get_status_folio($folio) {
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


/**/function get_bandera($depto,$folio) { 
$data['t'] = $data['p'] = $data['a'] = $data['r'] = 0;

if($depto=='lpl' || $depto=='ffc' || $depto=='ffm') {
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

if($depto=='lo' || $depto=='loa' || $depto=='prod' || $depto=='sqm' || $depto=='fin' || $depto=='inv') {	
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
			case "NA"	:	$img = "NA"; break;				
		}
	return $img;	
} 

function capt_merma($id_emp){
	$s_ = "select * from capt_merma where id_emp='$id_emp'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0){ return "SI"; } else { return "NO"; }
}?>