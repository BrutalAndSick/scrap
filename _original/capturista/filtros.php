<?php 

function filtros_capturista() {
		$s_f1 = "select id_division from capturistas where id_emp='$_SESSION[IDEMP]'";
		$r_f1 = mysql_query($s_f1); 
		if(mysql_num_rows($r_f1)>0) {
			$s_1 = "and (";
		while($d_f1=mysql_fetch_array($r_f1)) {
			if($d_f1['id_division']!='%' && $d_f1['id_division']!='0') {$s_1.= "( id_division='$d_f1[id_division]' "; }
			$s_1.= ") or "; }
		$s_1 = substr($s_1,0,-4).") "; } 	
	
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
	$s_2 = "select id, nombre from proyectos where not exists (select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' ";
	$s_2.= "and proyectos.id = filtros.valor) and activo!='2' ";
	
	$s_1 = "select id_division from capturistas where id_emp='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) { 
		$s_2.= "and ((";
	while($d_1=mysql_fetch_array($r_1)) { 
		if($d_1['id_division']!='%' && $d_1['id_division']!='0') { $s_2.= "id_division='$d_1[id_division]' and "; }
		$s_2 = substr($s_2,0,-5).") or ("; }
	$s_2 = substr($s_2,0,-5).") "; } $s_2.= " order by nombre"; 
	return $s_2;
}


function get_proyectos_in() {
	$s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.id = filtros.valor and filtros.filtro = ";
	$s_1.= "'proyectos' and filtros.id_emp='$_SESSION[IDEMP]' order by nombre";	
	return $s_1;	
}	?>		