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

/**/function get_bandera($depto,$folio) { 
	if($depto!='esp') { 
		$s_ = "select status from autorizaciones where no_folio='$folio' and depto='$depto' group by no_folio, depto order by status asc"; }
	else { 
		$s_ = "select status from autorizaciones where no_folio='$folio' and (depto='esp_1' or depto='esp_2') group by no_folio, depto order by status asc"; }
	$r_ = mysql_query($s_);	
	if(mysql_num_rows($r_)>0) { 
	$d_ = mysql_fetch_array($r_);
	$status = $d_['status']; 
	
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
		} }
	else { $img = "NA"; }	
	return $img;	
}


/**/function mostrar_folio($folio) {
    $s_ = "select * from autorizaciones where depto='$_SESSION[DEPTO]' and no_folio='$folio' ";
	$r_ = mysql_query($s_); 
	if(mysql_num_rows($r_)>0) { return "SI"; } else { return "NO"; }			
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
	return $data; } 	
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


function get_data_ad($id_emp) {
$s_ = "select * from empleados where id='$id_emp'"; 
$r_ = mysql_query($s_);
$d_ = mysql_fetch_array($r_);

if($d_['active_directory']=='SI') { 
   //Comienzo la conexión al servidor para tomar los datos de active directory
   $host      = get_config('host'); 
   $user_     = get_config('usuario');
   $pass_     = get_config('password');  
   $puerto    = get_config('puerto'); 
   $filter	  = "sAMAccountName=".$d_['usuario']."*";
   $attr	  = array("displayname","mail","givenname","sn","useraccountcontrol");
   $dn        = get_config('dn');
   
   $conex = ldap_connect($host,$puerto) or die ("No ha sido posible conectarse al servidor"); 

	if (!ldap_set_option($conex, LDAP_OPT_PROTOCOL_VERSION, 3)) { 
  		 echo "<br>Failed to set protocol version to 3"; 
	} 
	if ($conex) { 
  	 $dominio = get_config("dominio");
  	 $r       = ldap_bind($conex,$user_.$dominio,$pass_); 
   	 if ($r) { 
	   		$result  = ldap_search($conex,$dn,$filter,$attr);
			$entries = ldap_get_entries($conex,$result);
			for($i=0; $i<$entries["count"]; $i++) {
				$data['name'] = $entries[$i]["givenname"][0].' '.$entries[$i]["sn"][0];
				$data['mail'] = $entries[$i]["mail"][0];												
			} 
		}	
  	 } 		
} else { 
	$data['name'] = $d_['nombre']." ".$d_['ap_paterno']." ".$d_['ap_materno'];
	$data['mail'] = $d_['mail'];
} return $data; } ?>