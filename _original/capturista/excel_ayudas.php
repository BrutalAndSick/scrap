<?php session_name("loginUsuario"); 
      session_start(); 
$file_name="auxiliar_scrap_".date("Ymd").".xls";
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$file_name"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php switch($op) {
		case "proyectos"		:	proyectos($f_division,$f_segmento,$f_prce,$f_proyecto); break;
		case "proyectos_095"	:	proyectos_095($f_division,$f_segmento,$f_prce,$f_proyecto); break;
		case "merma_35"			:	merma_35(); break;
		case "codigo_095"		:	codigo_095(); break;
		case "batch_id"			:	batch_id($f_parte,$f_batch); break;		
} 

function merma_35() {?>
<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" colspan="5" align="center" bgcolor="#E6E6E6">DETALLES DE ERROR EN CARGA DE MERMA</td>
</tr>
</table> 
<br><table align="center" class="texto" cellpadding="0" cellspacing="0" border="1">
<tr bgcolor="#CCCCCC">
	<td width="40" align="center"><b>No.</b></td>
    <td width="120" align="center"><b>No.Parte</b></td>
	<td width="40" align="center"><b>Cantidad</b></td>
	<td width="40" align="center"><b>Tipo</b></td>
	<td width="90" align="center"><b>SubTipo</b></td>
	<td width="90" align="center"><b>Batch ID</b></td>
	<td width="200" align="center"><b>Descripción</b></td>
	<td width="100" align="center"><b>No.Serial</b></td>
	<td width="70" align="center"><b>Ubicación</b></td>
	<td width="120" align="center"><b>Parte Padre</b></td>
    <td width="400" align="center"><b>Error</b></td>
</tr>	
<?php include('../conexion_db.php');
      $s_ = "select * from scrap_partes_35 where id_emp='$_SESSION[IDEMP]' order by id asc";
      $r_ = mysql_query($s_); $items = mysql_num_rows($r_); $i=1;
      while($d_=mysql_fetch_array($r_)) { 
	  if($d_['error']!='') { $color = '#FF0000'; } else { $color = '#333333'; } ?>
<tr>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $i;?></td>
    <td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['no_parte'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['cantidad'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['tipo'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['tipo_sub'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['batch_id'];?></td>
	<td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['descripcion'];?></td>
	<td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['serial_unidad'];?></td>
	<td align="center" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;"><?php echo $d_['ubicacion'];?></td>
	<td align="left" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['padre'];?></td>
    <td align="left" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['error'];?></td>
</tr>
<?php $i++; } ?>
</table>
<?php }

function codigo_095() {?>
<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" colspan="5" align="center" bgcolor="#E6E6E6">DETALLES DE ERROR EN CARGA DE CÓDIGO 095</td>
</tr>
</table> 
<br><table align="center" class="texto" cellpadding="0" cellspacing="0" border="1">
<tr bgcolor="#CCCCCC">
	<td width="40" align="center"><b>No.</b></td>
    <td width="120" align="center"><b>No.Parte</b></td>
	<td width="40" align="center"><b>Cantidad</b></td>
	<td width="40" align="center"><b>Tipo</b></td>
	<td width="90" align="center"><b>SubTipo</b></td>
	<td width="90" align="center"><b>Batch ID</b></td>
	<td width="200" align="center"><b>Descripción</b></td>
	<td width="100" align="center"><b>No.Serial</b></td>
	<td width="70" align="center"><b>Ubicación</b></td>
	<td width="120" align="center"><b>Parte Padre</b></td>
    <td width="400" align="center"><b>Error</b></td>
</tr>	
<?php include('../conexion_db.php');
      $s_ = "select * from scrap_partes_095 where id_emp='$_SESSION[IDEMP]' order by id asc";
      $r_ = mysql_query($s_); $items = mysql_num_rows($r_); $i=1;
      while($d_=mysql_fetch_array($r_)) { 
	  if($d_['error']!='') { $color = '#FF0000'; } else { $color = '#333333'; } ?>
<tr>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $i;?></td>
    <td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['no_parte'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['cantidad'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['tipo'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['tipo_sub'];?></td>
	<td align="center" valign="top" style="color:<?=$color;?>;"><?php echo $d_['batch_id'];?></td>
	<td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['descripcion'];?></td>
	<td align="left" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['serial_unidad'];?></td>
	<td align="center" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;"><?php echo $d_['ubicacion'];?></td>
	<td align="left" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['padre'];?></td>
    <td align="left" rowspan="<?php echo $total;?>" valign="top" style="color:<?=$color;?>;">&nbsp;<?php echo $d_['error'];?></td>
</tr>
<?php $i++; } ?>
</table>
<?php }

function proyectos($f_division,$f_segmento,$f_prce,$f_proyecto) { 
	if(!$f_proyecto) 	$f_proyecto	= '%';
	if(!$f_division)	$f_division = '%';
	if(!$f_segmento)  	$f_segmento = '%'; 
	if(!$f_prce)  		$f_prce 	= '%'; ?>

<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" colspan="4" align="center" bgcolor="#E6E6E6">CATÁLOGOS DE AYUDA: PROYECTOS</td>
</tr>
</table>  
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">División</td>
		<td width="100" align="center">Segmento</td>
		<td width="100" align="center">Profit Center</td>
		<td width="200" align="center">Proyecto</td>
	</tr>
</thead>
<?php 
   include('../conexion_db.php');
   $s_1 = "select plantas.nombre as planta, divisiones.nombre as division, profit_center.nombre as profit_center, segmentos.nombre as segmento, proyectos.* from segmentos,";
   $s_1.= "plantas, divisiones, profit_center, proyectos where proyectos.nombre like '$f_proyecto' and proyectos.activo='1' and proyectos.id_segmento like '$f_segmento' ";
   $s_1.= "and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' and divisiones.id_planta = plantas.id and proyectos.id_pc = profit_center.id ";
   $s_1.= "and proyectos.id_segmento = segmentos.id and proyectos.id_division = divisiones.id and divisiones.activo='1' and segmentos.activo='1' and ";
   $s_1.= "profit_center.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id order by activo DESC, division, segmento, profit_center, nombre ASC"; 
   $r_1 = mysql_query($s_1);  ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['profit_center'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } 

function proyectos_095($f_division,$f_segmento,$f_prce,$f_proyecto) { 
	if(!$f_proyecto) 	$f_proyecto	= '%';
	if(!$f_division)	$f_division = '%';
	if(!$f_segmento)  	$f_segmento = '%'; 
	if(!$f_prce)  		$f_prce 	= '%'; ?>

<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" colspan="4" align="center" bgcolor="#E6E6E6">CATÁLOGOS DE AYUDA: PROYECTOS</td>
</tr>
</table>  
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">División</td>
		<td width="100" align="center">Segmento</td>
		<td width="100" align="center">Profit Center</td>
		<td width="200" align="center">Proyecto</td>
	</tr>
</thead>
<?php 
   include('../conexion_db.php');
  $s_1 = "select divisiones.nombre as division, segmentos.nombre as segmento, proyectos.nombre as proyecto, ";
  $s_1.= "profit_center.nombre as profit_center, proyectos.* from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap where ";
  $s_1.= "codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo and ";
  $s_1.= "divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc and divisiones.activo='1' and ";
  $s_1.= "segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' and proyectos.nombre like '$f_proyecto' ";
  $s_1.= "and proyectos.id_segmento like '$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' order by activo DESC, division, ";
  $s_1.= "segmento, profit_center, nombre ASC"; 
  $r_1 = mysql_query($s_1);  ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['profit_center'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } 

function batch_id($f_parte) { 
	if(!$f_parte) 	$f_parte	= '%';?>

<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" colspan="2" align="center" bgcolor="#E6E6E6">CATÁLOGOS DE AYUDA: BATCH ID</td>
</tr>
</table>  
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="200" align="center">No. Parte</td>
		<td width="100" align="center">Batch ID</td>
	</tr>
</thead>
<?php 
   include('../conexion_db.php');
   $s_1 = "select * from batch_ids where parte like '$f_parte' order by batch_id"; 
   $r_1 = mysql_query($s_1);  ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['parte'];?></td>
	<td align="center">&nbsp;&nbsp;<?php echo get_nombre_batch($d_1['batch_id']);?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } 

function get_nombre_batch($id) {
	$s_1 = "select batch_id from batch_id where id='$id'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['batch_id']; }?>
