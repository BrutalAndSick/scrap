<?php session_name("loginUsuario"); 
   session_start(); 
$file_name=$op."_".date("Ymd").".xls";
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$file_name"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php include('../conexion_db.php');
      include('../generales.php');

switch($op) { 
	
	case "empleados"	:	empleados($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
	case "supervisores"	:	supervisores($f_division); break;
	case "capturistas"	:	capturistas($f_division); break;
	case "capt_merma"	:	capt_merma(); break;
	case "aut_lpl"		:	aut_lpl($f_division,$f_prce,$f_proyecto,$f_tipo); break;
	case "aut_prod"		:	aut_prod($f_division,$f_area,$f_proyecto); break;
	case "aut_inv"		:	aut_inv($f_division); break;
	case "aut_loa"		:	aut_loa($f_planta,$depto_f,$division_f); break;
	case "aut_esp"		:	aut_esp($f_tipo,$f_proyecto); break;
	case "aut_lpl2"		:	aut_lpl2($f_proyecto); break;
	case "vendors"		:	vendors(); break;
	
	case "oi_especial"	:	oi_especial($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo); break;
	case "lineas"		:	lineas($f_area,$f_estacion,$f_linea); break;
	case "tecnologias"	:	tecnologias($f_area,$f_estacion); break;
	case "defectos"		:	defectos($f_area,$f_estacion,$f_defecto); break;
	case "proyectos"	:	proyectos($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial); break;

	case "por_plantas"	:	reporte_por_plantas(); break;
	case "por_areas"	:	reporte_por_areas($division,$proyecto); break;
	case "partes_padre"	:	partes_padre(); break;
	case "modelos"		:	modelos($tipo,$tabla,$orden,$buscar); break;
	
	case "codigo_proy"	:	codigo_proy($id_); break;
	case "batch_id"		:	batch_id($buscar); break;
}
	

function lineas($f_area,$f_estacion,$f_linea) {
	$s_1 = "select areas.nombre as area, estaciones.nombre as estacion, lineas.*, proyectos.nombre as proyecto ";
	$s_1.= "from areas, estaciones, lineas, lineas_proy, proyectos where lineas.activo!='2' and areas.activo='1' and estaciones.activo='1' and ";
	$s_1.= "lineas.id_estacion = estaciones.id and lineas.id_area = areas.id and lineas.id_area like '$f_area' and lineas.id_estacion like '$f_estacion' and ";
	$s_1.= "lineas.nombre like '$f_linea' and lineas_proy.id_linea = lineas.id and proyectos.id = lineas_proy.id_proyecto ";
	$s_1.= "order by activo DESC, area, estacion, nombre, proyecto"; ?>
    
<table align="center" border="1">
<thead>
	<tr>
    	<td align="center" bgcolor="#FFCC00"><b>#</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Área</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Tecnología</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Línea</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
	</tr>
</thead>
<tbody>
<?php $r_1 = mysql_query($s_1); $i=1;
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php echo $i;?></td>
    <td align="center">
	<?php if($d_1['activo']=='1') { echo "Activo";   } 
		  if($d_1['activo']=='0') { echo "Inactivo"; } ?></td>
	<td align="left"><?php echo $d_1['area'];?></td>
	<td align="left"><?php echo $d_1['estacion'];?></td>
	<td><?php echo $d_1['nombre'];?></td>
    <td><?php echo $d_1['proyecto'];?></td>
</tr>
<?php $i++; } ?>
</tbody>   
<?php }	
	
function tecnologias($f_area,$f_estacion){
	$s_1 = "select areas.nombre as area, estaciones.*, proyectos.nombre as proyecto ";
	$s_1.= "from areas, estaciones,  est_proyecto, proyectos where estaciones.activo!='2' and areas.activo='1' and ";
	$s_1.= "estaciones.id_area = areas.id and estaciones.id_area like '$f_area' and estaciones.nombre like '$f_estacion' ";
	$s_1.= "and est_proyecto.id_tecnologia = estaciones.id and proyectos.id = est_proyecto.id_proyecto ";
	$s_1.= "order by activo DESC, area, nombre, proyecto";?>
    
<table align="center" border="1">
<thead>
	<tr>
    	<td align="center" bgcolor="#FFCC00"><b>#</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Área</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Tecnología</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
	</tr>
</thead>
<tbody>
<?php $r_1 = mysql_query($s_1); $i=1;
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php echo $i;?></td>
    <td align="center">
	<?php if($d_1['activo']=='1') { echo "Activo";   } 
		  if($d_1['activo']=='0') { echo "Inactivo"; } ?></td>
	<td align="left"><?php echo $d_1['area'];?></td>
	<td align="left"><?php echo $d_1['nombre'];?></td>
    <td><?php echo $d_1['proyecto'];?></td>
</tr>
<?php $i++; } ?>
</tbody>   
<?php }

function defectos($f_area,$f_estacion,$f_defecto){
	$s_1 = "select areas.nombre as area, estaciones.nombre as estacion, proyectos.nombre as proyecto, defectos.*, causas.nombre as causa ";
	$s_1.= "from areas, estaciones, defectos, proyectos, def_proyecto, defecto_causa, causas where defectos.id_area = areas.id and ";
	$s_1.= "defectos.id_estacion = estaciones.id and defectos.id = def_proyecto.id_defecto and proyectos.id = def_proyecto.id_proyecto ";
	$s_1.= "and defecto_causa.id_defecto = defectos.id and defecto_causa.id_causa = causas.id  and defectos.activo!='2' and ";
	$s_1.= "defectos.nombre like '$f_defecto' and defectos.id_estacion like '$f_estacion' and defectos.id_area like '$f_area' ";
	$s_1.= "order by activo DESC, area, nombre, proyecto"; ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <td align="center" bgcolor="#FFCC00"><b>#</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Área</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Tecnología</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Defecto</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Relacionado a</b></td>
            </tr>
        </thead>
    <tbody>
    <?php $r_1 = mysql_query($s_1); $i=1;
          while($d_1=mysql_fetch_array($r_1)) { ?>
    <tr>
        <td align="center"><?php echo $i;?></td>
        <td align="center">
        <?php if($d_1['activo']=='1') { echo "Activo";   } 
              if($d_1['activo']=='0') { echo "Inactivo"; } ?></td>
        <td align="left"><?php echo $d_1['area'];?></td>
        <td align="left"><?php echo $d_1['estacion'];?></td>
        <td align="left"><?php echo $d_1['nombre'];?></td>
        <td><?php echo $d_1['proyecto'];?></td>
        <td><?php echo $d_1['causa'];?></td>
    </tr>
    <?php $i++; } ?>
    </tbody>   
<?php }

function proyectos($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial){
	if(!$f_planta) 		 $f_planta	 = '%';
	if(!$f_proyecto) 	 $f_proyecto = '%';
	if(!$f_division)	 $f_division = '%';
	if(!$f_segmento)  	 $f_segmento = '%'; 
	if(!$f_prce)  		 $f_prce 	 = '%'; 
    if(!$f_apr_especial) $f_apr_especial = '%';
	$s_1 = "select plantas.nombre as planta, divisiones.nombre as division, profit_center.nombre as profit_center, segmentos.nombre as segmento, proyectos.* ";
	$s_1.= "from segmentos, plantas, divisiones, profit_center, proyectos where proyectos.nombre like '$f_proyecto' and proyectos.activo!='2' and ";
	$s_1.= "proyectos.id_segmento like '$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' ";
	$s_1.= "and proyectos.id_planta like '$f_planta' and proyectos.id_pc = profit_center.id and proyectos.id_segmento = segmentos.id ";
	$s_1.= "and proyectos.id_division = divisiones.id and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and ";
	$s_1.= "plantas.activo='1' and divisiones.id_planta = plantas.id and proyectos.apr_especial like '$f_apr_especial' order by activo DESC, ";
	$s_1.= "division, segmento, profit_center, nombre ASC";  ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <td align="center" bgcolor="#FFCC00"><b>#</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Planta</b></td>
                <td align="center" bgcolor="#FFCC00"><b>División</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Segmento</b></td>
                <td align="center" bgcolor="#FFCC00"><b>P.C</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
                <td align="center" bgcolor="#FFCC00"><b>LSR</b></td>
                <td align="center" bgcolor="#FFCC00"><b>Apr. Especial</b></td>
            </tr>
        </thead>
    <tbody>
    <?php $r_1 = mysql_query($s_1); $i=1;
          while($d_1=mysql_fetch_array($r_1)) { ?>
    <tr>
        <td align="center"><?php echo $i;?></td>
        <td align="center">
        <?php if($d_1['activo']=='1') { echo "Activo";   } 
              if($d_1['activo']=='0') { echo "Inactivo"; } ?></td>
        <td align="left"><?php echo $d_1['planta'];?></td>
        <td align="left"><?php echo $d_1['division'];?></td>
        <td align="left"><?php echo $d_1['segmento'];?></td>
        <td align="left"><?php echo $d_1['profit_center'];?></td>
        <td align="left"><?php echo $d_1['nombre'];?></td>
        <td align="left"><?php echo $d_1['lsr'];?></td>
        <td align="left"><?php if($d_1['apr_especial']=='si') { echo "SI"; } else { echo "NO"; } ?></td>
    </tr>
    <?php $i++; } ?>
    </tbody>   	
<?php }

function oi_especial($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo) { 
    $s_1 = "select * from oi_especial where activo!='2' ";
	if($f_division!='%') { $s_1.= "and oi_especial.id_division like '$f_division' ";  }
	if($f_pc!='%')       { $s_1.= "and oi_especial.id_pc like '$f_pc' "; }
	if($f_segmento!='%') { $s_1.= "and oi_especial.id_segmento like '$f_segmento' ";  }
	if($f_proyecto!='%') { $s_1.= "and oi_especial.id_proyecto like '$f_proyecto' ";  }
	if($f_codigo!='%')   { $s_1.= "and oi_especial.codigo_scrap like '$f_codigo' ";   }	
	$s_1.= " order by activo desc, codigo_scrap asc"; ?>
    
<table align="center" border="1">
<thead>
	<tr>
    	<td align="center" bgcolor="#FFCC00"><b>#</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
        <td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Segmento</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Profit Center</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Código Scrap</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Orden Interna</b></td>
	</tr>
</thead>
<tbody>
<?php $r_1 = mysql_query($s_1); $i=1;
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php echo $i;?></td>
    <td align="center">
	<?php if($d_1['activo']=='1') { echo "Activo";   } 
		  if($d_1['activo']=='0') { echo "Inactivo"; } ?></td>
    <?php if($d_1['id_division']!='na') { 
	      $s_2 = "select nombre from divisiones where id='$d_1[id_division]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; } ?>
	<td align="left"><?php echo $nombre;?></td>
    <?php if($d_1['id_segmento']!='na') {
		  $s_2 = "select nombre from segmentos where id='$d_1[id_segmento]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; }  ?>
    <td align="left"><?php echo $nombre;?></td>
    <?php if($d_1['id_pc']!='na') {
	      $s_2 = "select nombre from profit_center where id='$d_1[id_pc]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; }  ?>
    <td align="left"><?php echo $nombre;?></td>
    <?php if($d_1['id_proyecto']!='todos') {
	      $s_2 = "select nombre from proyectos where id='$d_1[id_proyecto]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'Todos'; }  ?>
    <td align="left"><?php echo $nombre;?></td>
    <td align="left"><?php echo $d_1['codigo_scrap'];?></td>
	<td align="left"><?php echo $d_1['orden_interna'];?></td>
</tr>
<?php $i++; } ?>
</tbody>   
<?php }


function vendors() {?>
<table align="center" border="1">
<thead>
	<tr>
        <td align="center" bgcolor="#FFCC00"><b>Vendor</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Nombre</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select * from vendors where activo!='2' order by activo desc, vendor asc"; 
      $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
		<td align="left"><?php echo $d_1['vendor'];?></td>
		<td align="left"><?php echo $d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>   
<?php }		


function aut_lpl2($f_proyecto) { ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>Tipo de Aprobación</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario from autorizadores, empleados where ";
	  $s_1.= "autorizadores.id_emp = empleados.id and autorizadores.tipo = 'lpl_auto' ";
	  if($f_proyecto!='') { $s_1.= "and id_proyecto like '$f_proyecto' "; }
	  $s_1.= " order by tipo, apellidos"; 
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
        <td align="left"><?php if($d_1['tipo']=="esp_1"){ echo "Menor a 50,000"; } else { echo "Mayor a 50,000"; } ?></td>
        <td align="left"><?php echo nombre_proy($d_1['id_proyecto']);?></td>
		<td align="left"><?php echo $d_1['usuario'];?></td>
		<td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	


function aut_esp($f_tipo,$f_proyecto) {
if(!$f_tipo) $f_tipo = '%'; ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>Tipo de Aprobación</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario from autorizadores, empleados where ";
	  $s_1.= "autorizadores.id_emp = empleados.id and (autorizadores.tipo = 'esp_1' or autorizadores.tipo = 'esp_2') and tipo like '$f_tipo' ";
	  if($f_proyecto!='') { $s_1.= "and id_proyecto='$f_proyecto' "; }
	  $s_1.= " order by tipo, apellidos"; 
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
        <td align="left"><?php if($d_1['tipo']=="esp_1"){ echo "Menor a 50,000"; } else { echo "Mayor a 50,000"; } ?></td>
        <td align="left"><?php echo nombre_proy($d_1['id_proyecto']);?></td>
		<td align="left"><?php echo $d_1['usuario'];?></td>
		<td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	


function aut_loa($f_planta,$depto_f,$division_f) { 
if(!$f_planta) $f_planta = '%'; 
if(!$depto_f) $depto_f = '%'; 
if(!$division_f) $division_f = '%'; ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>Planta</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Departamento</b></td>
        <td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario from autorizadores, empleados where ";
	  $s_1.= "autorizadores.id_emp = empleados.id and id_area like '$f_planta' and (tipo='lo' or tipo='loa' or tipo='sqm' or tipo='fin') and ";
	  $s_1.= "tipo like '$depto_f' and id_division like '$division_f' order by tipo, apellidos"; 
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo nombre_planta($d_1['id_area']);?></td>
    <td align="left"><?php echo strtoupper($d_1['tipo']);?></td>
    <td align="left"><?php echo get_div_name($d_1['id_division']);?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	
	

function aut_inv($f_division) { 
if(!$f_division) $f_division = '%'; ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario, divisiones.nombre as division from autorizadores, empleados, divisiones ";
	  $s_1.= "where autorizadores.id_emp = empleados.id and autorizadores.id_division = divisiones.id and id_division like '$f_division' and tipo='inv' order by division, ";
	  $s_1.= "tipo, apellidos";
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo $d_1['division'];?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }		
	

function aut_prod($f_division,$f_area,$f_proyecto) { 
if(!$f_division) $f_division = '%'; 
if(!$f_area)     $f_area     = '%'; 
if(!$f_proyecto) $f_proyecto = '%'; ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Área</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario, divisiones.nombre as division from autorizadores, empleados, divisiones ";
	  $s_1.= "where autorizadores.id_emp = empleados.id and autorizadores.id_division = divisiones.id and id_division like '$f_division' and id_area like '$f_area' and ";
	  $s_1.= "id_proyecto like '$f_proyecto' and tipo='prod' order by division, tipo, apellidos";  
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo $d_1['division'];?></td>
    <td align="left"><?php echo nombre_area($d_1['id_area']);?></td>
    <td align="left"><?php echo nombre_proy($d_1['id_proyecto']);?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }		
	

function aut_lpl($f_division,$f_prce,$f_proyecto,$f_tipo) { 
if(!$f_division) $f_division = '%'; 
if(!$f_tipo)     $f_tipo     = '%'; 
if(!$f_prce)     $f_prce     = '%'; 
if(!$f_proyecto) $f_proyecto = '%'; ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Profit Center</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Departamento</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario, divisiones.nombre as division from autorizadores, empleados, divisiones ";
	  $s_1.= "where autorizadores.id_emp = empleados.id and autorizadores.id_division = divisiones.id and id_division like '$f_division%' and id_pc like '$f_prce' and ";
	  $s_1.= "id_proyecto like '$f_proyecto' and tipo like '$f_tipo' and tipo!='prod' and tipo!='inv' order by division, tipo, apellidos";
      $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo $d_1['division'];?></td>
    <td align="left"><?php echo nombre_pc($d_1['id_pc']);?></td>
    <td align="left"><?php echo nombre_proy($d_1['id_proyecto']);?></td>
    <td align="left"><?php echo strtoupper($d_1['tipo']);?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo trim($d_1['apellidos']." ".$d_1['nombre']);?></td>
</tr>
<?php } ?>
</tbody>   
<?php }		
	

function capturistas($f_division) { ?>

<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select empleados.usuario, empleados.nombre, empleados.apellidos, capturistas.* from capturistas, empleados ";
	  $s_1.= "where empleados.id = capturistas.id_emp and capturistas.id_division like '$f_division%' and empleados.capturista='1' ";
	  $s_1.= "order by apellidos, nombre";
      $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo get_div_name($d_1['id_division']);?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo $d_1['apellidos']." ".$d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	

function capt_merma() { ?>

<table align="center" border="1">
<thead>
	<tr>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select empleados.usuario, empleados.nombre, empleados.apellidos, capt_merma.* from capt_merma, empleados ";
	  $s_1.= "where empleados.id = capt_merma.id_emp and empleados.capturista='1' order by apellidos, nombre";
      $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo $d_1['apellidos']." ".$d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	

	
function supervisores($f_division) { ?>

<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select empleados.usuario, empleados.nombre, empleados.apellidos, supervisores.* from supervisores, empleados ";
	  $s_1.= "where empleados.id = supervisores.id_emp and supervisores.id_division like '$f_division%' and ";
	  $s_1.= "empleados.autorizador='prod' order by apellidos, nombre";
      $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
    <td align="left"><?php echo get_div_name($d_1['id_division']);?></td>
    <td align="left"><?php echo $d_1['usuario'];?></td>
    <td align="left"><?php echo $d_1['apellidos']." ".$d_['nombre'];?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	


function nombre_planta($id_) {
	$s_ = "select * from plantas where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}

function get_div_name($id_) {
	if($id_=='0') { return "NA"; } 
	else { 
	$s_1 = "select nombre from divisiones where id='$id_'"; 
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);}
	return $d_1['nombre']; 
}

function nombre_pc($id_pc) {
if($id_pc=='%') { return "TODOS"; } 
else {
	$s_2 = "select nombre from profit_center where id='$id_pc'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
}


function nombre_proy($id_proy) {
if($id_proy=='%') { return "TODOS"; } 
else {
	$s_2 = "select nombre from proyectos where id='$id_proy'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
}	


function nombre_area($id_area) {
if($id_area=='%') { return "TODAS"; } 
else {
	$s_2 = "select nombre from areas where id='$id_area'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['nombre']; }
}	
	
function empleados($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo) {
	if(!$f_acdi)   $f_acdi   = '%';
	if(!$f_depto)  $f_depto  = '%'; 
	if(!$f_pagina) $f_pagina = '1';

	$s_1 = "select * from empleados where (usuario like '%$f_userid%' or nombre like '$f_userid%' or apellidos like '$f_userid%') and active_directory like '$f_acdi' ";
	$s_1.= "and autorizador like '$f_depto' and activo='1' ";
	
	if($f_tipo!='') { 
		switch($f_tipo) { 
			case "super_admin"		:	$s_1.= "and super_admin='1' "; break;
			case "administrador"	:	$s_1.= "and administrador='1' "; break;
			case "autorizador"		:	$s_1.= "and autorizador!='0' "; break;
			case "capturista"		:	$s_1.= "and capturista='1' "; break;
			case "reportes"			:	$s_1.= "and reportes='1' "; break;
		}
	}	
	$s_1.= "order by apellidos, nombre "; ?>

<table align="center" border="1">
<thead>
	<tr>
		<td align="center" bgcolor="#FFCC00"><b>Estado</b></td>
        <td align="center" bgcolor="#FFCC00"><b>User ID</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Nombre</b></td>
        <td align="center" bgcolor="#FFCC00"><b>A.D.</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Súper Admin.</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Admin.</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Capturista</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Reportes</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Materiales</b></td>
        <td align="center" bgcolor="#FFCC00"><b>Autorizador</b></td>
	</tr>
</thead>
<tbody>
<?php $r_1 = mysql_query($s_1);
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php if($d_1['activo']=='1') { echo "SI"; } else { echo "NO"; } ?></td>
	<td align="left"><?php echo $d_1['usuario'];?></td>
	<td align="left"><?php echo $d_1['nombre']." ".$d_1['apellidos'];?></td>
    <td align="left"><?php echo $d_1['active_directory'];?></td>    
    <td align="center"><?php if($d_1['super_admin']=="1") { echo "SI"; } else { echo "NO"; } ?></td>
    <td align="center"><?php if($d_1['administrador']=="1") { echo "SI"; } else { echo "NO"; } ?></td>
    <td align="center"><?php if($d_1['capturista']=="1") { echo "SI"; } else { echo "NO"; } ?></td>
    <td align="center"><?php if($d_1['reportes']=="1") { echo "SI"; } else { echo "NO"; } ?></td>
    <td align="center"><?php if($d_1['materiales']=="1") { echo "SI"; } else { echo "NO"; } ?></td>
	<td align="center">	
	<?php switch($d_1['autorizador']) {
		case "0"	:	echo ""; 		    break;
		case "esp"	:	echo "Especial";    break;
		case "oes"	:	echo "OES";   	    break;
		case "ffc"	:	echo "FFC"; 	    break;
		case "ffm"	:	echo "FFM"; 	    break;
		case "sqm"	:	echo "SQM"; 	    break;
		case "fin"	:	echo "Finanzas";    break;
		case "lo"	:	echo "LO"; 		    break;
		case "loa"	:	echo "LO Almacén";  break;
		case "lpl"	:	echo "LPL"; 	    break;
		case "prod"	:	echo "Producción";  break;
		case "inv"	:	echo "Inventarios"; break;
		default		:	echo "";		    break;		
	} ?></td>
</tr>
<?php } ?>
</tbody>   
<?php }	
	

function modelos($tipo,$tabla,$orden,$buscar) { 
   if(!$tipo) $tipo = '%';	
   $s_1 = "select * from numeros_parte where tipo like '$tipo' and activo!='2' and tabla='$tabla' and nombre like '$buscar%' order by $orden asc"; 
   $r_1 = mysql_query($s_1); ?>
<table align="center" border="1">
<thead>
	<tr>
		<td align="center" width="100" bgcolor="#FFCC00"><b>Estado</b></td>
		<td align="center" width="150" bgcolor="#FFCC00"><b>Tipo</b></th>
        <td align="center" width="250" bgcolor="#FFCC00"><b>No.Parte</b></th>
		<td align="center" width="550" bgcolor="#FFCC00"><b>Descripción</b></th>
        <td align="center" width="120" bgcolor="#FFCC00"><b>Precio</b></th>
		<td align="center" width="120" bgcolor="#FFCC00"><b>Unidad</b></th>
		<td align="center" width="120" bgcolor="#FFCC00"><b>Global P.C.</b></th>
	</tr>
</thead>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php if($d_1['activo']=='1') { echo "SI"; } else { echo "NO"; } ?></td>
	<td align="left"><?php echo $d_1['tipo'];?></td>
    <td align="left" style="mso-number-format:'0';"><?php echo $d_1['nombre'];?></td>
	<td align="left"><?php echo $d_1['descripcion'];?></td>
	<td align="left"><?php echo $d_1['costo'];?></td>
	<td align="left"><?php echo $d_1['unidad'];?></td>
	<td align="left"><?php echo $d_1['global_pc'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }	
	
	

function partes_padre() { ?>
<table align="center" border="1">
<thead>
	<tr>
    	<td align="center" width="100" bgcolor="#FFCC00"><b>Estado</b></td>
		<td align="center" width="200" bgcolor="#FFCC00"><b>Parte Padre</b></td>
		<td align="center" width="250" bgcolor="#FFCC00"><b>Componente</b></th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>Nivel</b></th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>APD</b></th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>Tipo</b></th>
	</tr>
</thead>
<tbody>
<?php $s_1 = "select * from vw_padre order by padre, material"; 
      $r_1 = mysql_query($s_1); 
	  while($d_1=mysql_fetch_array($r_1)) { ?>
<tr>
	<td align="center"><?php if($d_1['activo']=='1') { echo "SI"; } else { echo "NO"; } ?></td>
	<td align="left" style="mso-number-format:'0';"><?php echo $d_1['padre'];?></td>
	<td align="left" style="mso-number-format:'0';"><?php echo $d_1['material'];?></td>
	<td align="left"><?php echo $d_1['nivel'];?></td>
	<td align="left"><?php echo $d_1['apd'];?></td>
	<td align="left"><?php echo $d_1['type'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }

	
function reporte_por_plantas() { ?>
<table align="center" border="1">
<caption><b>Catálogos de Plantas</b></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150" bgcolor="#FFCC00"><b>Planta</td>
		<td align="center" width="150" bgcolor="#FFCC00"><b>División</th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>Segmento</th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>Profit Center</th>
		<td align="center" width="100" bgcolor="#FFCC00"><b>APD</th>
		<td align="center" width="150" bgcolor="#FFCC00"><b>Proyecto</th>
	</tr>
</thead>
<tbody>
<?php  $r_1 = mysql_query($_SESSION['CONSULTA']); 
	while($d_1=mysql_fetch_array($r_1)) { ?>
<tr bgcolor="#F7F7F7" height="20">
	<td align="left"><?php echo $d_1['planta'];?></td>
	<td align="left"><?php echo $d_1['division'];?></td>
	<td align="left"><?php echo $d_1['segmento'];?></td>
	<td align="left"><?php echo $d_1['prce'];?></td>
	<td align="left"><?php echo $d_1['apd'];?></td>
	<td align="left"><?php echo $d_1['proyecto'];?></td>
</tr>
<?php } ?>

</tbody>
</table>
<?php }
	

function reporte_por_areas($division,$proyecto) { 
$div = get_division_name($division);
$pry = get_proyecto_name($proyecto); ?>
<table align="center" border="1">
<caption><b>Catálogos de Áreas</b></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="180" bgcolor="#FFCC00"><b>División</b></td>
        <td align="center" width="180" bgcolor="#FFCC00"><b>Proyecto</b></td>
        <td align="center" width="180" bgcolor="#FFCC00"><b>Área</b></td>
		<td align="center" width="150" bgcolor="#FFCC00"><b>Tecnología</b></td>
		<td align="center" width="90" bgcolor="#FFCC00"><b>Línea</b></td>
		<td align="center" width="90" bgcolor="#FFCC00"><b>Dfecto</b></td>
		<td align="center" width="90" bgcolor="#FFCC00"><b>Relacionado a</b></td>
		<td align="center" width="90" bgcolor="#FFCC00"><b>Código Scrap</b></td>
		<td align="center" width="90" bgcolor="#FFCC00"><b>Orden Interna</b></td>
</thead>
<tbody>
<?php  $r_1 = mysql_query($_SESSION['CONSULTA']); 
	while($d_1=mysql_fetch_array($r_1)) { ?>
<tr bgcolor="#F7F7F7" height="20">
	<td align="left"><?php echo $div;?></td>
	<td align="left"><?php echo $pry;?></td>
	<td align="left"><?php echo $d_1['area'];?></td>
	<td align="left"><?php echo $d_1['estacion'];?></td>
	<td align="left"><?php echo $d_1['linea'];?></td>
	<td align="left"><?php echo $d_1['defecto'];?></td>
	<td align="left"><?php echo $d_1['causa'];?></td>
	<td align="left"><?php echo $d_1['codigo'];?></td>
	<td align="left"><?php echo $d_1['orden'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } 

function get_division_name($id_) {
	$s_ = "select * from divisiones where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}


function get_proyecto_name($id_) {
	$s_ = "select * from proyectos where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
} 

function codigo_proy($id_){?>
<table align="center" border="1">
<caption><b>Proyectos del Código <?php echo get_codigo_scrap($id_);?></b></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center" bgcolor="#FFCC00">División</td>	
        <td width="150" align="center" bgcolor="#FFCC00">Segmento</td>	
        <td width="150" align="center" bgcolor="#FFCC00">Profit Center</td>	
        <td width="150" align="center" bgcolor="#FFCC00">Proyecto</td>	
	</tr>
</thead>
	<?php $s_1 = "select divisiones.nombre as division, segmentos.nombre as segmento, proyectos.nombre as proyecto, profit_center.nombre as profit_center, ";
	      $s_1.= "codigo_scrap_proy.* from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap where codigo_scrap_proy.id_proy = ";
		  $s_1.= "proyectos.id and codigo_scrap.id = '$id_' and codigo_scrap.codigo = codigo_scrap_proy.codigo and divisiones.id = proyectos.id_division and ";
		  $s_1.= "segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc and divisiones.activo='1' and segmentos.activo='1' and ";
		  $s_1.= "profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1'"; 
	      $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
            <tr bgcolor="#F7F7F7" height="20">
                <td align="left">&nbsp;<?php echo $d_1['division'];?></td>
                <td align="left">&nbsp;<?php echo $d_1['segmento'];?></td>
                <td align="left">&nbsp;<?php echo $d_1['profit_center'];?></td>
                <td align="left">&nbsp;<?php echo $d_1['proyecto'];?></td>
            </tr>	
        <?php }
}

function get_codigo_scrap($id){
	$s_ = "select * from codigo_scrap where id='$id'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['codigo'];	
}

function batch_id($buscar) { 
   $s_1 = "select * from batch_id where activo!='2' and batch_id like '$batch_id%' order by batch_id asc"; 
   $r_1 = mysql_query($s_1); ?>
    <table align="center" border="1">
        <thead>
            <tr>
                <td align="center" width="100" bgcolor="#FFCC00"><b>Estado</b></td>
                <td align="center" width="150" bgcolor="#FFCC00"><b>Batch ID</b></th>
            </tr>
        </thead>
        <tbody>
        <?php while($d_1=mysql_fetch_array($r_1)) { ?>
        <tr>
            <td align="center"><?php if($d_1['activo']=='1') { echo "SI"; } else { echo "NO"; } ?></td>
            <td align="left" style="mso-number-format:'0';"><?php echo $d_1['batch_id'];?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
<?php }	?>