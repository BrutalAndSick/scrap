<?php 
function filtros_autorizador() {
	if($_SESSION['DEPTO']=='lpl' || $_SESSION['DEPTO']=='ffc' || $_SESSION['DEPTO']=='ffm') { 
		$s_f1 = "select id_division, id_pc, id_proyecto from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]'";
		$r_f1 = mysql_query($s_f1); 
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			$s_1.= "( id_division='$d_f1[id_division]' ";
			if($d_f1['id_pc']!='%' && $d_f1['id_pc']!='0') { $s_1.= "and id_pc='$d_f1[id_pc]' "; }
			if($d_f1['id_proyecto']!='%' && $d_f1['id_proyecto']!='0') { $s_1.= "and id_proyecto='$d_f1[id_proyecto]' "; }
			$s_1.= ") or "; }
		$s_1 = substr($s_1,0,-4).") "; } 
	} 
	
	if($_SESSION['DEPTO']=='prod') { 
		$s_f1 = "select id_division, id_area, id_proyecto from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]'";
		$r_f1 = mysql_query($s_f1);
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			if($d_f1['id_division']!='%' && $d_f1['id_division']!='0') { 
				$s_1.= "(id_division='$d_f1[id_division]' and id_area like '$d_f1[id_area]' and id_proyecto like '$d_f1[id_proyecto]') or "; } }
		$s_1 = substr($s_1,0,-4).") "; } 	
	}

	if($_SESSION['DEPTO']=='inv' || $_SESSION['DEPTO']=='sqm' || $_SESSION['DEPTO']=='fin') { 
		$s_f1 = "select id_division from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]'";
		$r_f1 = mysql_query($s_f1);
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			if($d_f1['id_division']!='%' && $d_f1['id_division']!='0') { $s_1.= "(id_division='$d_f1[id_division]') or "; } }
		$s_1 = substr($s_1,0,-4).") "; } 
	}

	if($_SESSION['DEPTO']=='lo' || $_SESSION['DEPTO']=='loa') { 
		$s_f1 = "select id_area from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]'";
		$r_f1 = mysql_query($s_f1);
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			if($d_f1['id_area']!='%' && $d_f1['id_area']!='0') { $s_1.= "(id_planta='$d_f1[id_area]') or "; } }
		$s_1 = substr($s_1,0,-4).") "; } 
	}

	if($_SESSION['DEPTO']=='esp') { 
		$s_f1 = "select id_proyecto, tipo from autorizadores where id_emp='$_SESSION[IDEMP]' and (tipo='esp_1' or tipo='esp_2')";
		$r_f1 = mysql_query($s_f1);
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			if($d_f1['id_proyecto']!='%' && $d_f1['id_proyecto']!='0') { $s_1.= "(id_proyecto='$d_f1[id_proyecto]' and "; } 
			if($d_f1['id_proyecto']=='%' || $d_f1['id_proyecto']=='0') { $s_1.= "(id_proyecto like '%' and "; } 
			if($d_f1['tipo']=='esp_1') { $s_1.= " autorizaciones.depto='esp_1') or "; } 
			if($d_f1['tipo']=='esp_2') { $s_1.= " autorizaciones.depto='esp_2') or "; } 
		}
		$s_1 = substr($s_1,0,-4).") "; }
	}	
	
	$s_f1 = get_proyectos_in();
	$r_f1 = mysql_query($s_f1);
		if(mysql_num_rows($r_f1)>0) {
			$s_1.= "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			$s_1.= "( id_proyecto='$d_f1[valor]' ) or "; }
		$s_1 = substr($s_1,0,-4).") "; }	
	return $s_1; 
}


function get_proyectos_out() {
	if($_SESSION['DEPTO']!='esp') { 
	$s_2 = "select id, nombre from proyectos where not exists (select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
	$s_2.= "and proyectos.id = filtros.valor) and activo!='2' ";
	
	$s_1 = "select id_area, id_division, id_pc, id_proyecto from autorizadores where id_emp='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1); $i=0;
	if(mysql_num_rows($r_1)>0) { 
		$s_2.= "and ((";
	while($d_1=mysql_fetch_array($r_1)) { 
		if($d_1['id_area']!='%' && $d_1['id_area']!='0') { $s_2.= "id_planta='$d_1[id_area]' and "; $i++; } 
		if($d_1['id_division']!='%' && $d_1['id_division']!='0') { $s_2.= "id_division='$d_1[id_division]' and "; $i++; }
		if($d_1['id_pc']!='%' && $d_1['id_pc']!='0') { $s_2.= "id_pc='$d_1[id_pc]' and "; $i++; }
		if($d_1['id_proyecto']!='%' && $d_1['id_proyecto']!='0') { $s_2.= "id='$d_1[id_proyecto]' and "; $i++; }
		$s_2 = substr($s_2,0,-5).") or ("; }
	$s_2 = substr($s_2,0,-5).") "; } 
	if($i<=0) { $s_2 = substr($s_2,0,-5); } $s_2.= " order by nombre"; 
	return $s_2; } 
	
	else { 
		$s_2 = "select id, nombre from proyectos where not exists (select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
		$s_2.= "and proyectos.id = filtros.valor) and apr_especial='si' and activo!='2' ";	
	
		$s_1 = "select id_proyecto from autorizadores where id_emp='$_SESSION[IDEMP]' and (tipo='esp_1' or tipo='esp_2')";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) { 
			$s_2.= "and ((";
		while($d_1=mysql_fetch_array($r_1)) { 
			if($d_1['id_proyecto']!='%' && $d_1['id_proyecto']!='0') { $s_2.= "id='$d_1[id_proyecto]' and "; }
			if($d_1['id_proyecto']=='%' || $d_1['id_proyecto']=='0') { $s_2.= "id like '%' and "; }
			$s_2 = substr($s_2,0,-5).") or ("; }
		$s_2 = substr($s_2,0,-5).") "; } $s_2.= " order by nombre";	
		return $s_2; }
}


function get_proyectos_in() {
	$s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.id = filtros.valor and filtros.filtro = ";
	$s_1.= "'proyectos' and filtros.id_emp='$_SESSION[IDEMP]' order by nombre";	
	return $s_1;	
} ?>		