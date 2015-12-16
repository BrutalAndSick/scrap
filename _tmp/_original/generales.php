<?php 

function log_sistema($seccion,$accion,$folio,$query){
if(get_config("log_sistema")=="SI") { 
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	$query = htmlentities($query,ENT_QUOTES,"UTF-8");
	$s_1   = "insert into log_sistema values('','$seccion','$accion','$_SESSION[IDEMP]','$fecha','$hora','$folio','$query')";
	$r_1   = mysql_query($s_1); }
}


function bloquear_sistema() {
	$s_1 = "update configuracion set valor='SI' where variable='bloqueado'";
	$r_1 = mysql_query($s_1);
	/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$s_1); }


function desbloquear_sistema() {
	$s_1 = "update configuracion set valor='NO' where variable='bloqueado'";
	$r_1 = mysql_query($s_1); 
	/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$s_1); }
	
	
function get_config($variable) {
	$s_2 = "select valor from configuracion where variable='$variable'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['valor'];
}	

function mails() {
	$dias_c  = get_config('dias_correos');
	$fecha_c = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-15,date("Y")));
	$s_ = "delete from mails where enviado_fecha<='$fecha_c'";
	$r_ = mysql_query($s_);
	//enviar_mails_auto(); 
} 

function del_log() {
	$fecha_c = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$s_ = "delete from log_sistema where fecha<='$fecha_c'";
	$r_ = mysql_query($s_);
}

function fecha_dmy($fecha) {
	list($anio,$mes,$dia) = split("-",$fecha);
	switch($mes) {
		case "01"	:	$mes = "Ene"; break;
		case "02"	:	$mes = "Feb"; break;
		case "03"	:	$mes = "Mar"; break;
		case "04"	:	$mes = "Abr"; break;
		case "05"	:	$mes = "May"; break;
		case "06"	:	$mes = "Jun"; break;
		case "07"	:	$mes = "Jul"; break;
		case "08"	:	$mes = "Ago"; break;
		case "09"	:	$mes = "Sep"; break;
		case "10"	:	$mes = "Oct"; break;
		case "11"	:	$mes = "Nov"; break;
		case "12"	:	$mes = "Dic"; break;
	}
	return $dia."/".$mes."/".$anio;
} 

function aprobacion_auto() {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
	$s_ = "select autorizaciones.no_folio, autorizaciones.depto, autorizaciones.status as estado, scrap_folios.status, scrap_folios.timer from autorizaciones, ";
	$s_.= "scrap_folios where autorizaciones.no_folio=scrap_folios.no_folio and autorizaciones.depto='inv' and autorizaciones.status='0' and scrap_folios.status='1' ";
	$s_.= "and scrap_folios.activo='1'"; 
	$r_ = mysql_query($s_); 
	while($d_=mysql_fetch_array($r_)) {
		$s_1 = "update scrap_folios set status='0' where no_folio='$d_[no_folio]'";
		$r_1 = mysql_query($s_1); }

	$s_ = "select no_folio, fecha from scrap_folios where timer=''"; 
	$r_ = mysql_query($s_);
	while($d_=mysql_fetch_array($r_)) {
		list($anio,$mes,$dia) = split("-",$d_['fecha']);
		$timer = date("YmdHis",mktime(0,0,0,$mes,$dia,$anio));
		$s_1 = "update scrap_folios set timer='$timer' where no_folio='$d_[no_folio]'";
		$r_1 = mysql_query($s_1);
	}	
	//$horas_auto  = get_config('aprobacion_auto');
	$s_h = "select configuracion.nombre, configuracion.valor from configuracion, divisiones where variable='aprobacion_auto' and valor!='0' and valor!='' and ";
	$s_h.= "divisiones.id = configuracion.nombre and divisiones.activo!='2'";
	$r_h = mysql_query($s_h);
	while($d_h=mysql_fetch_array($r_h)) { $horas_auto = $d_h['valor']; $id_div = $d_h['nombre'];
			
		$mensaje     = "EXCEDIO LIMITE DE ".$horas_auto." HORAS.";
		$timer_limit = date("YmdHis",mktime(date("H")-$horas_auto,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$s_ = "select folios.no_folio, folios.status as estado, folios.timer, autorizaciones.depto, autorizaciones.status, folios.id_planta, folios.id_division, ";
		$s_.= "folios.id_pc, folios.id_proyecto, folios.id_area, (select sum(total) from scrap_partes where scrap_partes.no_folio = folios.no_folio group by ";
		$s_.= "scrap_partes.no_folio) as total from scrap_folios as folios, autorizaciones where folios.no_folio = autorizaciones.no_folio and  ";
		$s_.= "folios.timer<='$timer_limit' and folios.activo='1' and folios.status='0' and folios.id_division='$id_div' and autorizaciones.status='0' and "; 
		$s_.= "(depto!='inv' and depto!='esp_1' and depto!='esp_2') order by folios.no_folio desc ";
		$r_ = mysql_query($s_);
		while($d_= mysql_fetch_array($r_)) {
			//Obtener autorizador que aplique
			if($d_['depto']=='ffm' || $d_['depto']=='ffc') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "empleados.autorizador='$d_[depto]' and empleados.activo='1' and autorizadores.id_division='$d_[id_division]' and (id_pc = '$d_[id_pc]' or id_pc ";
				$s_a.= "= '%') and (id_proyecto = '$d_[id_proyecto]' or id_proyecto = '%') and empleados.nombre!='' order by empleados.apellidos"; }
			if($d_['depto']=='lpl') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "autorizadores.id_proyecto='$d_[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'"; }
			if($d_['depto']=='prod') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "empleados.autorizador='$d_[depto]' and empleados.activo='1' and autorizadores.id_division='$d_[id_division]' and (id_area = '$d_[id_pc]' or ";
				$s_a.= "id_area = '%') and (id_proyecto = '$d_[id_proyecto]' or id_proyecto = '%') and empleados.nombre!='' order by empleados.apellidos"; }		
			if($d_['depto']=='lo' || $d_['depto']=='loa' || $d_['depto']=='sqm') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados where empleados.autorizador='$d_[depto]' and empleados.activo='1' ";
				$s_a.= "and empleados.nombre!='' order by empleados.apellidos"; }	
			$r_a = mysql_query($s_a); 
			$d_a = mysql_fetch_array($r_a);
	
			if($d_['depto']=='ffm') {
				if($d_['total']<100000) { $aplicar = 'si'; } 
				else { $aplicar = 'no'; } }
			else { $aplicar = 'si'; } 
			
			if($d_['depto']=='lo' || $d_['depto']=='loa'){
				$aplicar = "no";	
			}
 			
			if($aplicar=='si') { 
				$emp = $d_a['nombre']." ".$d_a['apellidos'];
				$s_1 = "update autorizaciones set status='1', id_emp='$d_a[id]', empleado='$emp (APROBACION AUTOMATICA)' where no_folio='$d_[no_folio]' and ";
				$s_1.= "depto='$d_[depto]' and depto!='inv'";
				$r_1 = mysql_query($s_1);
				$s_1 = "insert into aut_bitacora values('','$d_[no_folio]','$d_[depto]','$d_a[id]','$emp','1','$fecha','$hora','$mensaje','')";
				$r_1 = mysql_query($s_1); 
			}
		}
	}
	
	//======================= APROBACIONES POR PROYECTO (TABLA: AUTORIZACIONES_AUTO)
	$s_ = "select * from autorizaciones_auto where activo='1'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)){
		$horas_auto = $d_['horas'];
		$id_proyecto = $d_['id_proyecto'];
		$mensaje     = "EXCEDIO LIMITE DE ".$horas_auto." HORAS.";
		$timer_limit = date("YmdHis",mktime(date("H")-$horas_auto,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$s_1 = "select folios.no_folio, folios.status as estado, folios.timer, autorizaciones.depto, autorizaciones.status, folios.id_planta, folios.id_division, ";
		$s_1.= "folios.id_pc, folios.id_proyecto, folios.id_area, (select sum(total) from scrap_partes where scrap_partes.no_folio = folios.no_folio group by ";
		$s_1.= "scrap_partes.no_folio) as total from scrap_folios as folios, autorizaciones where folios.no_folio = autorizaciones.no_folio and  ";
		$s_1.= "folios.timer<='$timer_limit' and folios.activo='1' and folios.status='0' and folios.id_proyecto='$id_proyecto' and autorizaciones.status='0' and "; 
		$s_1.= "(depto!='inv' and depto!='esp_1' and depto!='esp_2') order by folios.no_folio desc ";
		$r_1 = mysql_query($s_1);
		while($d_1 = mysql_fetch_array($r_1)){
			//Obtener autorizador que aplique
			if($d_1['depto']=='ffm' || $d_1['depto']=='ffc') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "empleados.autorizador='$d_1[depto]' and empleados.activo='1' and autorizadores.id_division='$d_1[id_division]' and (id_pc = '$d_1[id_pc]' or id_pc ";
				$s_a.= "= '%') and (id_proyecto = '$d_1[id_proyecto]' or id_proyecto = '%') and empleados.nombre!='' order by empleados.apellidos"; }
			if($d_1['depto']=='lpl') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'"; }
			if($d_1['depto']=='prod') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "empleados.autorizador='$d_1[depto]' and empleados.activo='1' and autorizadores.id_division='$d_1[id_division]' and (id_area = '$d_1[id_pc]' or ";
				$s_a.= "id_area = '%') and (id_proyecto = '$d_1[id_proyecto]' or id_proyecto = '%') and empleados.nombre!='' order by empleados.apellidos"; }		
			if($d_1['depto']=='lo' || $d_1['depto']=='loa' || $d_1['depto']=='sqm') { 
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados where empleados.autorizador='$d_1[depto]' and empleados.activo='1' ";
				$s_a.= "and empleados.nombre!='' order by empleados.apellidos"; }	
			$r_a = mysql_query($s_a); 
			$d_a = mysql_fetch_array($r_a);
				
			$emp = $d_a['nombre']." ".$d_a['apellidos'];
			$s_2 = "update autorizaciones set status='1', id_emp='$d_a[id]', empleado='$emp (APROBACION AUTOMATICA)' where no_folio='$d_1[no_folio]' and ";
			$s_2.= "depto='$d_1[depto]' and depto!='inv'";
			$r_2 = mysql_query($s_2);
			$s_2 = "insert into aut_bitacora values('','$d_1[no_folio]','$d_1[depto]','$d_a[id]','$emp','1','$fecha','$hora','$mensaje','')";
			$r_2 = mysql_query($s_2);
		}
	}
} ?>