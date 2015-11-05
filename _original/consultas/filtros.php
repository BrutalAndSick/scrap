<?php 

function filtros_autorizador($division) {
	//REVISAR SI TIENE FILTROS POR PROYECTOS
	$s_2 = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$filtros[$i] = $d_2['valor'];
			$i++; } 
		}	
	//Si tiene filtros por proyecto, no aplica el filtro por cualquiera de los otros campos
	if(count($filtros)>0) {
  	 	$s_1.= "and (";
  	 	for($i=0;$i<count($filtros);$i++) { 
	 		$s_1.= "id_proyecto = '$filtros[$i]' or "; }
	 		$s_1 = substr($s_1,0,-4).") "; } 
	//Si no tiene filtros por proyecto, aplico todos los otros filtros		
		if($division=='') { 
			$div = filtro_divisiones($division); }
		else { $div['0'] = $division; }	 
		if(count($div)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($div);$i++) { 
			$s_1.= "id_division = '$div[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }			
		
		$planta = filtro_plantas();
		if(count($planta)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($planta);$i++) { 
			$s_1.= "id_planta = '$planta[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }	

		$area = filtro_areas();
		if(count($area)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($area);$i++) { 
			$s_1.= "id_area = '$area[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }		
		
		$pc = filtro_profit();
		if(count($pc)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($pc);$i++) { 
			$s_1.= "id_pc = '$pc[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }

		$proy = filtro_proyectos();
		if(count($proy)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($proy);$i++) { 
			$s_1.= "id_proyecto = '$proy[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }
	
	return $s_1;
}

function filtros_capturista($division) {
	//REVISAR SI TIENE FILTROS POR PROYECTOS
	$s_2 = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$filtros[$i] = $d_2['valor'];
			$i++; } 
		}	
	//Si tiene filtros por proyecto, no aplica el filtro por división
	if(count($filtros)>0) {
  	 	$s_1.= "and (";
  	 	for($i=0;$i<count($filtros);$i++) { 
	 		$s_1.= "id_proyecto = '$filtros[$i]' or "; }
	 		$s_1 = substr($s_1,0,-4).") "; } 
	//Si no tiene filtros por proyecto, aplico el de la división		
		if($division=='') { 
			$div = filtro_divisiones($division); }
		else { $div['0'] = $division; }	 
		if(count($div)>0 && count($filtros)<=0) { 
	 	$s_1.= "and (";
	 	for($i=0;$i<count($div);$i++) { 
			$s_1.= "id_division = '$div[$i]' or "; }
			$s_1 = substr($s_1,0,-4).") "; }			
	return $s_1;
}

function get_divisiones() {
 $s_1 = "select * from divisiones where activo!='2' ";	   
 $pla = filtro_plantas();
 $div = filtro_divisiones();

 if(count($pla)>0) {
	 $s_1.= "and (";
	 for($i=0;$i<count($pla);$i++) { 
	 	$s_1.= "id_planta = '$pla[$i]' or "; }
	 	$s_1 = substr($s_1,0,-4).") "; 
 } 
 if(count($div)>0) {
	 $s_1.= "and (";
	 for($i=0;$i<count($div);$i++) { 
	 	$s_1.= "id = '$div[$i]' or "; }
	 	$s_1 = substr($s_1,0,-4).") "; 
 } $s_1.= "order by nombre";
 return $s_1;
}

function get_proyectos_out($division) {
	$s_1 = "select * from proyectos where not exists (select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and proyectos.id = filtros.valor) ";
	$s_1.= "and activo!='2' ";		  
	$div = filtro_divisiones();
	$pro = filtro_proyectos();
	$pla = filtro_plantas();
		if(count($div)>0 && $division=='') {
	   	 	$s_1.= "and (";
	   	 	for($i=0;$i<count($div);$i++) { 
		 		$s_1.= "id_division = '$div[$i]' or "; }
		 		$s_1 = substr($s_1,0,-4).") "; }
			if($division!='') { 
			$s_1.= "and id_division = '$division' "; }

		if(count($pla)>0) {	
	   	 	$s_1.= "and (";
	   	 	for($i=0;$i<count($pla);$i++) { 
		 		$s_1.= "id_planta = '$pla[$i]' or "; }
		 		$s_1 = substr($s_1,0,-4).") "; }	
		
		if(count($pro)>0) {	
	   	 	$s_1.= "and (";
	   	 	for($i=0;$i<count($pro);$i++) { 
		 		$s_1.= "id = '$pro[$i]' or "; }
		 		$s_1 = substr($s_1,0,-4).") "; }			
	$s_1.= "order by nombre";
	return $s_1;
}

function get_proyectos_in() {
	$s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.id = filtros.valor and filtros.filtro = 'proyectos' and ";
	$s_1.= "filtros.id_emp='$_SESSION[IDEMP]' order by nombre";	
	return $s_1;	
}		
		
function filtro_divisiones() {
	//REVISAR SI TIENE RESTRICCIONES DE DIVISIONES
	if($_SESSION["TYPE"]=='capturista') { 
		$s_2 = "select * from capturistas where id_emp='$_SESSION[IDEMP]' and id_division!='%' and id_division!='0'"; }
	elseif($_SESSION["TYPE"]=='autorizador') { 	
		$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]' and id_division!='%' and id_division!='0'"; }
	else { $s_2 = "select id as id_division from divisiones where activo!='2' order by nombre"; }	
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$divs[$i] = $d_2['id_division'];
			$i++; } 
		}	
	return $divs;
}
	
function filtro_plantas() {
	//REVISAR SI TIENE RESTRICCIONES DE ÁREAS
	$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo!='prod' and id_area!='%' and id_area!='0'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$plantas[$i] = $d_2['id_area'];
			$i++; } 
		}	
	return $plantas;
}		
	
function filtro_areas() {
	//REVISAR SI TIENE RESTRICCIONES DE ÁREAS
	$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='prod' and id_area!='%' and id_area!='0'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$area[$i] = $d_2['id_area'];
			$i++; } 
		}	
	return $area;
}

function filtro_profit() {
	//REVISAR SI TIENE RESTRICCIONES DE PROYECTOS
	$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]' and id_pc!='%' and id_pc!='0'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$prof[$i] = $d_2['id_pc'];
			$i++; } 
		}	
	return $prof;
}

function filtro_proyectos() {
	//REVISAR SI TIENE RESTRICCIONES DE PROYECTOS
	$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='$_SESSION[DEPTO]' and id_proyecto!='%' and id_proyecto!='0'";
	$r_2 = mysql_query($s_2); $i=0;
	if(mysql_num_rows($r_2)>0) {
		while($d_2=mysql_fetch_array($r_2)) { 
			$proy[$i] = $d_2['id_proyecto'];
			$i++; } 
		}	
	return $proy;
} ?>		