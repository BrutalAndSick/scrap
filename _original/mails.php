<?php 
/**/function enviar_aviso_ffm($folio) {
	$hoy  = date("Y-m-d");
	$hora = date("H:i:s");  
	
	$sheader = $sheader."From: Sistema Scrap\n";
	$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
	$sheader = $sheader."Mime-Version: 1.0\n";    
	$sheader = $sheader."Content-Type: text/html";		
	
	$s_ = "select no_folio, id_planta, id_division, id_pc, id_proyecto, id_area from scrap_folios where no_folio='$folio'"; 
	$r_ = mysql_query($s_);
	$d_= mysql_fetch_array($r_);
	//Obtener autorizador(es) que aplica(n)
		$s_a = "select empleados.id, empleados.nombre, empleados.apellidos, empleados.mail, empleados.usuario from empleados, autorizadores where empleados.id = ";
		$s_a.= "autorizadores.id_emp and empleados.autorizador='ffm' and empleados.activo='1' and autorizadores.id_division='$d_[id_division]' and (id_pc = ";
		$s_a.= "'$d_[id_pc]' or id_pc = '%') and (id_proyecto = '$d_[id_proyecto]' or id_proyecto = '%') and empleados.mail!='' order by empleados.apellidos"; 
		$r_a = mysql_query($s_a);
		while($d_a=mysql_fetch_array($r_a)) {

			$para_mail = $user['mail'];
			$para_name = $user['nombre']." ".$user['apellidos'];
			$para_user = $user['usuario'];

			$msj = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">
			<tr height=20>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Estimado:</td>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
				<b>&nbsp;$para_name</b></td></tr>
			<tr height=20>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Mail:</td>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
				<b>&nbsp;$para_mail</b></td></tr>		
			<tr height=20>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
				&nbsp;Se ha creado una boleta de scrap por un monto igual o superior a $100,000</td>
			</tr>
			<tr height=20>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
				&nbsp;Es necesaria su aprobacion para continuar con el proceso de validaci&oacute;n</td></tr>	
			<tr>
				<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"center\">
				<a href='".get_config('link_sistema')."'>Verificar y aprobar boleta de Scrap</a></td>
			</tr>	
			</table><br><br>";
			$msj = htmlentities(utf8_encode($msj),ENT_QUOTES,"UTF-8");
			$sub = utf8_encode("Scrap superior a $100,000 - $folio");
		
			$s_m = "insert into mails values('','$d_a[id]', '$para_name', '$para_mail', '$sub', '$msj', '$hoy', '$hora', '', '')";
			$r_m.= mysql_query($s_m); 
			//echo $para_mail."-->".$sub."<br>".$msj."<br><br>";
			mail($para_mail,$sub,html_entity_decode($msj),$sheader);	
		}	
}

/**/function enviar_aviso_autorizador($folio,$empleado) {
	$hoy  = date("Y-m-d");
	$hora = date("H:i:s");  
	
	$sheader = $sheader."From: Sistema Scrap\n";
	$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
	$sheader = $sheader."Mime-Version: 1.0\n";    
	$sheader = $sheader."Content-Type: text/html";		
	
	$d_g  = get_ausencia($empleado);
	$user = get_data_user($d_g);

	$para_mail = $user['mail'];
	$para_name = $user['nombre']." ".$user['apellidos'];
	$para_user = $user['usuario'];

	$msj = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Estimado:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$para_name</b></td></tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Mail:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$para_mail</b></td></tr>		
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Se ha editado la boleta de scrap rechazada con el no. $folio</td>
	</tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Revise que la edici&oacute;n sea correcta y apruebe el folio para continuar con el proceso de validaci&oacute;n</td></tr>	
	<tr>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"center\">
		<a href='".get_config('link_sistema')."'>Verificar y aprobar boleta rechazada de Scrap</a></td>
	</tr>	
	</table><br><br>";
	$msj = htmlentities(utf8_encode($msj),ENT_QUOTES,"UTF-8");
	$sub = utf8_encode("Boleta de scrap editada - $folio");

	$s_m = "insert into mails values('','$d_g', '$para_name', '$para_mail', '$sub', '$msj', '$hoy', '$hora', '', '')";
	$r_m.= mysql_query($s_m); 
	mail($para_mail,$sub,html_entity_decode($msj),$sheader);	
}


/**/function enviar_aviso_generador($folio,$comentario,$depto) {
	$hoy  = date("Y-m-d");
	$hora = date("H:i:s");  
	
	$sheader = $sheader."From: Sistema Scrap\n";
	$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
	$sheader = $sheader."Mime-Version: 1.0\n";    
	$sheader = $sheader."Content-Type: text/html";		
	
	$s_1  = "select id_emp from scrap_folios where no_folio='$folio'";
	$r_1  = mysql_query($s_1);
	$d_1  = mysql_fetch_array($r_1);
	$user = get_data_user($d_1['id_emp']);

	$para_mail = $user['mail'];
	$para_name = $user['nombre']." ".$user['apellidos'];
	$para_user = $user['usuario'];
	
	switch($depto) {
		case "lo"	:	$depto = "LO"; break;
		case "loa"	:	$depto = "LO-Almac&eacute;n"; break;
		case "lpl"	:	$depto = "LPL"; break;
		case "ffm"	:	$depto = "FFM"; break;
		case "ffc"	:	$depto = "FFC"; break;
		case "sqm"	:	$depto = "SQM"; break;
		case "fin"	:	$depto = "Finanzas"; break;
		case "inv"	:	$depto = "Inventarios"; break;
		case "prod"	:	$depto = "Producci&oacute;n"; break; }		

	$msj = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Estimado:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$para_name</b></td></tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Mail:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$para_mail</b></td></tr>		
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;$depto ha rechazado la boleta de scrap no. $folio</td>
	</tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Motivo: $comentario</td>
	</tr>	
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Por favor, proceda con la edici&oacute;n de la misma para continuar con el proceso de validaci&oacute;n</td></tr>	
	<tr>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"center\">
		<a href='".get_config('link_sistema')."'>Editar boleta rechazada de Scrap</a></td>
	</tr>	
	</table><br><br>";
	$msj = htmlentities(utf8_encode($msj),ENT_QUOTES,"UTF-8");
	$sub = utf8_encode("Boleta de scrap rechazada - $folio");

	$s_m = "insert into mails values('','$d_1[id_emp]', '$para_name', '$para_mail', '$sub', '$msj', '$hoy', '$hora', '', '')";
	$r_m.= mysql_query($s_m); 
	mail($para_mail,$sub,html_entity_decode($msj),$sheader);	
}


/**/function enviar_mails_auto() { 
	$hoy  = date("Y-m-d");
	$hora = date("H:i:s");

	$s_1 = "select * from configuracion where variable = 'dias_atraso'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	$limite = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$d_1['valor'],date("Y"))); 

	$s_1 = "select * from configuracion where variable = 'dias_aviso'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	$avisos = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$d_1['valor'],date("Y")));
		
	$sheader = $sheader."From: Sistema Scrap\n";
	$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
	$sheader = $sheader."Mime-Version: 1.0\n";    
	$sheader = $sheader."Content-Type: text/html";	
	
	$s_g = "CREATE OR REPLACE VIEW vw_mails as (SELECT folios.no_folio, folios.fecha, folios.id_planta, folios.planta, folios.id_area, folios.area, ";
	$s_g.= "folios.id_division, folios.division, folios.id_pc, folios.profit_center, folios.id_proyecto, folios.proyecto, aut.depto, aut2.id_emp, emp.nombre, ";
	$s_g.= "emp.apellidos, emp.mail ";
	$s_g.= "FROM scrap_folios folios, autorizaciones aut, autorizadores aut2, empleados emp ";
	$s_g.= "WHERE folios.fecha<='$limite' and folios.status='0' and folios.activo='1' and aut.status='0' and aut.aviso<='$avisos' and depto='$_SESSION[DEPTO]' ";
	$s_g.= "and aut2.tipo='$_SESSION[DEPTO]' and folios.id_planta!='0' and folios.id_planta!='' and folios.id_area!='0' and folios.id_area!='' and ";
	$s_g.= "folios.id_division!='0' and folios.id_division!='' and folios.id_pc!='0' and folios.id_pc!='' and folios.id_proyecto!='0' and folios.proyecto!='' and ";
	$s_g.= "folios.no_folio = aut.no_folio and emp.id = aut2.id_emp and emp.autorizador = '$_SESSION[DEPTO]' and emp.activo='1' ";

	if($_SESSION["DEPTO"]=='inv') {
		$s_g.= " and aut2.id_division=folios.id_division "; }
	elseif($_SESSION["DEPTO"]=='lpl' ||	$_SESSION["DEPTO"]=='ffc' || $_SESSION["DEPTO"]=='ffm') {
		$s_g.= " and aut2.id_division=folios.id_division and (aut2.id_pc='%' or aut2.id_pc=folios.id_pc) and (aut2.id_proyecto='%' or ";
		$s_g.= "aut2.id_proyecto=folios.id_proyecto)"; }
	elseif($_SESSION["DEPTO"]=='lo' ||	$_SESSION["DEPTO"]=='loa' || $_SESSION["DEPTO"]=='sqm' || $_SESSION["DEPTO"]=='inv') {		
		$s_g.= " and aut2.id_area=folios.id_area "; }
	else { 
		$s_g.= " and aut2.id_division=folios.id_division and (aut2.id_area='%' or aut2.id_area=folios.id_area) and (aut2.id_proyecto='%' or ";
		$s_g.= "aut2.id_proyecto=folios.id_proyecto)"; }			
	$s_g.= " order by id_emp, no_folio desc)";
	$r_g = mysql_query($s_g);
	
	
	$s_a = "SELECT count(no_folio) as total, vw_mails.id_planta, planta, id_division, division, vw_mails.nombre, vw_mails.apellidos, vw_mails.mail, vw_mails.id_emp, ";					
	$s_a.= "depto, tabla.jefe, emp.nombre as nombre2, emp.apellidos as apellidos2, emp.mail as mail2 FROM vw_mails, empleados emp, ";
	switch($_SESSION["DEPTO"]) {
		case "lpl"	:	$s_a.= "divisiones as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_division, id_pc, id_proyecto order by id_division, id_pc, id_proyecto, id_emp"; break;
		case "ffc"	:	$s_a.= "divisiones as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_division, id_pc, id_proyecto order by id_division, id_pc, id_proyecto, id_emp"; break;
		case "ffm"	:	$s_a.= "divisiones as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_division, id_pc, id_proyecto order by id_division, id_pc, id_proyecto, id_emp"; break;
		case "lo"	:	$s_a.= "plantas as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_planta order by id_planta, id_emp"; break;
		case "loa"	:	$s_a.= "plantas as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_planta order by id_planta, id_emp"; break;
		case "sqm"	:	$s_a.= "plantas as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_planta order by id_planta, id_emp"; break;
		case "fin"	:	$s_a.= "plantas as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_planta order by id_planta, id_emp"; break;
		case "prod"	:	$s_a.= "divisiones as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_division, id_area, id_proyecto order by id_division, id_area, id_proyecto, id_emp"; break;
		case "inv"	:	$s_a.= "divisiones as tabla where tabla.id = vw_mails.id_division and emp.id = tabla.jefe ";
						$s_a.= "group by id_division, id_emp order by id_division, id_emp"; break;
	}
	//Enviar los correos que corresponda a los autorizadores
	$r_a = mysql_query($s_a); $i=0;
	while($d_a=mysql_fetch_array($r_a)) {	
			$id_e_p   = $d_a['id_emp'];	
			$mail_p   = $d_a['mail'];
			$nombre_p = $d_a['nombre']." ".$d_a['apellidos'];
			$id_e_c   = $d_a['jefe'];
			$mail_c   = $d_a['mail2'];					
			$nombre_c = $d_a['nombre2']." ".$d_a['apellidos2'];
			$subject  = utf8_encode("Scrap en espera de autorizaci&oacute;n - $d_a[depto]");	
		$msj = aviso($mail_c,$mail_p,$nombre_p,$d_a['total'],$d_a['planta'],$d_a['division'],$limite);
		$s_m = "insert into mails values('','$empleado', '$nombre', '$mail', '$subject', '$msj', '$hoy', '$hora', '', '')";
		$r_m.= mysql_query($s_m);
		//echo html_entity_decode($msj)."<br>";
		if($mail_p!='') { 
			//mail($mail_p,$subject,html_entity_decode($msj),$sheader);
			$s_m = "insert into mails values('','$id_e_p', '$nombre_p', '$mail_p', '$subject', '$msj', '$hoy', '$hora', '', '')";
			$r_m.= mysql_query($s_m); }
		if($mail_c!='') { 
			//mail($mail_c,$subject,html_entity_decode($msj),$sheader);
			$s_m = "insert into mails values('','$id_e_c', '$nombre_c', '$mail_c', '$subject', '$msj', '$hoy', '$hora', '', '')";
			$r_m.= mysql_query($s_m); }
	}
	$s_ = "update autorizaciones set aviso='$hoy' where status='0' and depto='$_SESSION[DEPTO]'";
	$r_ = mysql_query($s_);
}


function aviso($mail_c,$mail_p,$nombre_p,$total,$planta,$division,$limite) {
	$msj = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Estimado:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$nombre_p</b></td></tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Mail:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$mail_p</b></td></tr>		
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;C.C.:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$mail_c</b></td></tr>		
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Tiene boletas de SCRAP pendientes por autorizar anteriores a la fecha l&iacute;mite: <b>$limite</b></td>
	</tr>
	<tr height=20><td align=\"center\" colspan=\"2\"><br>";
	
	$msj.= "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">";
	$msj.= "<tr bgcolor=\"#D8D8D8\" height=\"20\">";
		$msj.= "<td width=\"100\" align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\"><b>&nbsp;&nbsp;Total folios:</b></td>";
		$msj.= "<td width=\"350\" align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;$total</td>";
	$msj.= "</tr>";
	$msj.= "<tr bgcolor=\"#D8D8D8\" height=\"20\">";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\"><b>&nbsp;&nbsp;Departamento:</b></td>";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;".strtoupper($_SESSION["DEPTO"])."</td>";
	$msj.= "</tr>";
	$msj.= "<tr bgcolor=\"#D8D8D8\" height=\"20\">";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;<b>Planta:</b></td>";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;$planta</td>";
	$msj.= "</tr>"; 
	if($_SESSION["DEPTO"]!='lo' && $_SESSION["DEPTO"]!='loa' && $_SESSION["DEPTO"]!='sqm' && $_SESSION["DEPTO"]!='fin') { 
	$msj.= "<tr bgcolor=\"#D8D8D8\" height=\"20\">";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;<b>Divisi&oacute;n:</b></td>";
		$msj.= "<td align=\"left\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\">&nbsp;&nbsp;$division</td>";
	$msj.= "</tr>"; }	
	$msj.= "</table><br></td>";
	
	$msj.= "<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Por favor proceda con las autorizaciones pendientes.</td></tr>	
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Se ha enviado copia de este correo a su jefe de divisi&oacute;n / planta.</td></tr>	
	<tr>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"center\">
		<a href='".get_config('link_sistema')."'>Autorizar sus registros pendientes del sistema de Scrap</a></td>
	</tr>	
	</table><br><br>";
	$msj = htmlentities(utf8_encode($msj),ENT_QUOTES,"UTF-8");
	return $msj;
}

/**/ function get_data_user($id_) {
	$s_ = "select empleados.nombre, empleados.apellidos, empleados.mail from empleados where id='$id_emp'";
	$r_ = mysql_query($s_);
	return mysql_fetch_array($r_);
}


/**/function con_copia($id_emp) {
	$s_ = "select empleados.nombre, empleados.apellidos, empleados.mail from empleados where id='$id_emp'";
	$r_ = mysql_query($s_);
	return mysql_fetch_array($r_);
} ?>