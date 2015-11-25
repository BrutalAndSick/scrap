<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   include("../conexion_db.php");
   conectame_db('scrap_gdl'); ?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>		
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../pop_Calendar/calendar.css">
<link rel="stylesheet" href="../autocomplete/jquery.autocomplete.css" type="text/css">   
<!--------------------------Calendario---------------------------->
<script language="JavaScript" src="../pop_Calendar/GCappearance.js"></script>
<script language="JavaScript" src="../pop_Calendar/GurtCalendar.js"></script>
<!--------------------------Tooltips---------------------------->
<script language="JavaScript" src="../css/boxover.js"></script>
<!--------------------------Autocompletar---------------------------->
<script src="../autocomplete/jquery-latest.js"></script>
<script type="text/javascript" src="../autocomplete/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="../autocomplete/jquery.autocomplete.js"></script> 
<script>
/*$(document).ready(function(){
if(form1.parte.value.length>=6) {
	var data = ' '; 
  	<?php //$s_ = "select * from numeros_parte where activo='1' and nombre like 'A2C533%' order by nombre asc";
	   //$r_ = mysql_query($s_);
	   //while($d_=mysql_fetch_array($r_)) { ?>
      	 data = data + "<?php echo $d_['nombre']." ";?>";
	   <?php //} ?>
   	   data = data.split(" ")
    $("#modelos").autocomplete(data); } 
});*/

function solo_numeros(evt){
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57));
}

function soloLetras(evt){ 
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key > 13 && (key < 48 || key > 57));
}
</script>

<?php include('funciones.php');
   include('validaciones.php');
   include('../mails.php');
   if(!$campo) $campo='turno'; ?>
   
<body topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" onLoad="poner_foco('<?php echo $campo;?>');">   

<?php menu_scrap($folio);
   switch($op) {
	case "editar_1"	:   $s_1 = "delete from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
						$r_1 = mysql_query($s_1);
						$s_ = "select * from scrap_partes where no_folio='$folio'";
						$r_ = mysql_query($s_);	
						while($d_=mysql_fetch_array($r_)) {
							$s_1 = "insert into scrap_partes_tmp values('','$d_[padre]','$d_[no_parte]','$d_[tipo]','$d_[tipo_sub]', ";
							$s_1.= "'$d_[descripcion]', '$d_[cantidad]', '$d_[costo]', '$d_[total]', '$d_[batch_id]', ";
							$s_1.= "'$d_[serial_unidad]', '$d_[ubicacion]', '$_SESSION[IDEMP]')";
						 	$r_1 = mysql_query($s_1); } 
						editar($folio,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
						$from,'',''); break;
	case "editar"	:	editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$padre,$supervisor,$operador,
						$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,
						$archivo_name);	break;	
							
	case "add_temp"	:	add_temp($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,
						$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$ubicacion,$padre,
						$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,
						$from,$archivo,$archivo_name);
						editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$codigo_scrap_2,'','','','','',$supervisor,$operador,$no_personal,$apd,$o_mantto,
						$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name); break;				
	case "del_temp"	:	del_temp($id_borrar,$folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,
						$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$ubicacion,
						$padre,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,
						$accion_correctiva,$from,$archivo,$archivo_name);
						editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$codigo_scrap_2,'','','','','',$supervisor,$operador,$no_personal,$apd,$o_mantto,
						$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name); break;							
										
	case "update"	:	update($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$codigo_scrap_2,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,
						$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name); 
						$s_1 = "delete from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
						$r_1 = mysql_query($s_1);
						$s_ = "select * from scrap_partes where no_folio='$folio'";
						$r_ = mysql_query($s_);	
						while($d_=mysql_fetch_array($r_)) {
							$s_1 = "insert into scrap_partes_tmp values('','$d_[padre]','$d_[no_parte]','$d_[tipo]','$d_[tipo_sub]', ";
							$s_1.= "'$d_[descripcion]', '$d_[cantidad]', '$d_[costo]', '$d_[total]', '$d_[batch_id]', ";
							$s_1.= "'$d_[serial_unidad]', '$d_[ubicacion]', '$_SESSION[IDEMP]')";
						 	$r_1 = mysql_query($s_1); } 
						editar($folio,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
						$from,'',''); break;
	}	
	

	
function menu_scrap($folio) { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo" align="left">EDITAR BOLETA FOLIO <?php echo $folio;?></td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
	<span title="header=[&nbsp;&nbsp;Captura de Scrap] body=[Complete todos los campos de cada parte del formulario. Los cuadros de colores indican lo siguiente:<table align='center' border='0' cellspacing='2' class='texto'><tr bgcolor='#FFFFFF'><td class='obligatorio' width='60'>&nbsp;ROJO</td><td width='100'>&nbsp;Obligatorio</td></tr><tr bgcolor='#FFFFFF'><td class='automatico'>&nbsp;VERDE</td><td>&nbsp;Automático</td></tr><tr bgcolor='#FFFFFF'><td class='opcional'>&nbsp;AZUL</td><td>&nbsp;Opcional</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>
</tr>
</table>
<hr><br>
<?php } 


function editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$padre,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name){ 

$s_1 = "select * from scrap_folios where no_folio='$folio' group by no_folio";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);

$s_2 = "select * from scrap_codigos where no_folio='$folio' group by no_folio";
$r_2 = mysql_query($s_2);
$d_2 = mysql_fetch_array($r_2);

	if(!$turno)	 		 		$turno=$d_1['turno'];
	if(!$proyecto)	 			$proyecto=$d_1['id_proyecto']; 
	if(!$area)			 		$area=$d_1['id_area'];
	if(!$estacion)	 			$estacion=$d_1['id_estacion'];
	if(!$linea)		 	 		$linea=$d_1['id_linea'];
	if(!$defecto)    	 		$defecto=$d_1['id_defecto'];
	if(!$causa)		 	 		$causa=$d_1['id_causa'];
	if(!$codigo_scrap)			$codigo_scrap=$d_1['codigo_scrap'];
	if(!$supervisor)			$supervisor=$d_1['supervisor'];	
	if(!$operador)				$operador=$d_1['operador'];
	if(!$no_personal)			$no_personal=$d_1['no_personal'];
	if(!$apd)					$apd=$d_1['id_apd'];
	if(!$o_mantto)				$o_mantto=$d_1['o_mantto'];
	if(!$docto_sap)				$docto_sap=$d_1['docto_sap'];
	if(!$info_1)				$info_1 = $d_1['info_1'];
	if(!$info_2)				$info_2 = $d_1['info_2'];
	if(!$comentario)			$comentario=$d_1['comentario'];
	if(!$accion_correctiva)		$accion_correctiva=$d_1['accion_correctiva'];
	if(!$archivo)				$archivo=$d_1['archivo'];
	if(!$campo) 	 			$campo='turno'; 
	
	if(!$area_2)		 		$area_2=$d_2['id_area'];
	if(!$estacion_2)			$estacion_2=$d_2['id_estacion'];
	if(!$linea_2)			 	$linea_2=$d_2['id_linea'];
	if(!$defecto_2)    			$defecto_2=$d_2['id_defecto'];
	if(!$causa_2)		 		$causa_2=$d_2['id_causa'];
	if(!$codigo_scrap_2)		$codigo_scrap_2=$d_2['codigo_scrap']; ?>	
	
<form action="?op=editar" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="folio" value="<?php echo $folio;?>">
<input type="hidden" name="from" value="<?php echo $from;?>">
<table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td width="80">&nbsp;Fecha</td>
	<td width="70" align="center">&nbsp;<b><?php echo date("d-m-Y");?></b></td>	
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td width="30">&nbsp;Folio</td>
	<td width="50" align="center"><b><?php echo $folio;?></b></td>
	<td rowspan="9" background="../imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td width="95">&nbsp;Área</td>
	<td>
	<select name="area" style="width:200px;" class="texto" onChange="cambio_foco('editar','area');" tabindex="4">
		<option value=""></option>
	<?php $s_3 = "select * from areas where activo='1' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($area==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>	
</tr><tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Turno</td>
	<td colspan="4">	
		<select name="turno" style="width:215px;" class="texto" tabindex="1">
		<option value=""></option>
		<option value="1" <?php if($turno=='1'){?> selected="selected"<?php } ?>>1</option>
		<option value="2" <?php if($turno=='2'){?> selected="selected"<?php } ?>>2</option>
		<option value="3" <?php if($turno=='3'){?> selected="selected"<?php } ?>>3</option>
		<option value="4" <?php if($turno=='4'){?> selected="selected"<?php } ?>>4</option>
		<option value="5" <?php if($turno=='5'){?> selected="selected"<?php } ?>>5</option>
		<option value="6" <?php if($turno=='6'){?> selected="selected"<?php } ?>>6</option>
		<option value="7" <?php if($turno=='7'){?> selected="selected"<?php } ?>>7</option>
		</select></td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Tecnología</td>
	<td><?php $s_4 = "select estaciones.* from estaciones, est_proyecto where estaciones.activo='1' and estaciones.id_area='$area' ";
		      $s_4.= "and est_proyecto.id_tecnologia = estaciones.id and est_proyecto.id_proyecto = '$proyecto' order by nombre";
			  $r_4 = mysql_query($s_4); ?>
	<select name="estacion" style="width:200px;" class="texto" onChange="cambio_foco('editar','estacion');" tabindex="5">
	   <option value=""></option>
	   <?php while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($estacion==$d_4['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select></td>	
</tr>
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Proyecto</td>
	<td colspan="4">
	<?php $s_1 = "select proyectos.* from plantas, divisiones, segmentos, profit_center, proyectos, capturistas where ";
	   $s_1.= "proyectos.activo='1' and plantas.activo='1' and divisiones.activo='1' and segmentos.activo='1' and ";
	   $s_1.= "profit_center.activo='1' and proyectos.id_planta = plantas.id and proyectos.id_division = divisiones.id and ";
	   $s_1.= "proyectos.id_segmento = segmentos.id and proyectos.id_pc = profit_center.id and capturistas.id_division = ";
	   $s_1.= "proyectos.id_division and capturistas.id_emp='$_SESSION[IDEMP]' order by proyectos.nombre";
	   $r_1 = mysql_query($s_1); ?>
	<select name="proyecto" style="width:215px;" class="texto" onchange="cambio_foco('editar','proyecto');" tabindex="2">
	   <option value=""></option>
	   <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_1['nombre'];?></option>
	   <?php } ?>
	</select>
	<?php $data_proy = get_datos_proyecto($proyecto);?></td>	
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Línea</td>
	<td><select name="linea" style="width:200px;" class="texto" onChange="cambio_foco('editar','linea');" tabindex="6">
		<option value=""></option>
		<?php $s_6 = "select lineas.* from lineas, lineas_proy where lineas.activo='1' and lineas.id_area='$area' and ";
		      $s_6.= "lineas.id_estacion='$estacion' and lineas_proy.id_linea = lineas.id and lineas_proy.id_proyecto = '$proyecto' ";
			  $s_6.= "order by nombre";
	   	  	  $r_6 = mysql_query($s_6);
	   	while($d_6=mysql_fetch_array($r_6)) { ?>
	    <option value="<?php echo $d_6['id'];?>" <?php if($linea==$d_6['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_6['nombre'];?></option>
	    <?php } ?>
	</select></td>	
</tr>	
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Planta</td>
	<td colspan="4"><input name="plant" type="text" readonly="readonly" value="<?php echo $data_proy['nom_p'];?>" class="texto" size="37"></td>
	<?php $data_proy = get_datos_proyecto($proyecto);?></td>	
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Defecto</td>
	<td><select name="defecto" style="width:200px;" class="texto" onChange="cambio_foco('editar','defecto');" tabindex="7">
	   <option value=""></option>
		<?php $s_7 = "select defectos.* from defectos, def_proyecto where defectos.activo='1' and defectos.id_area='$area' and ";
		      $s_7.= "defectos.id_estacion='$estacion' and def_proyecto.id_defecto = defectos.id and def_proyecto.id_proyecto = ";
			  $s_7.= "'$proyecto' order by nombre";
  		      $r_7 = mysql_query($s_7);
	   	   while($d_7=mysql_fetch_array($r_7)) { ?>
	   <option value="<?php echo $d_7['id'];?>" <?php if($defecto==$d_7['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_7['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Divisi&oacute;n</td>
	<td colspan="4"><input name="division" type="text" readonly="readonly" value="<?php echo $data_proy['nom_d'];?>" class="texto" size="37"></td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Relacionado a</td>
	<td><select name="causa" style="width:200px;" class="texto" onChange="cambio_foco('editar','causa');" tabindex="8">
		<option value=""></option>
	<?php $s_8 = "select causas.id, causas.nombre from causas, defecto_causa where causas.activo='1' and ".
			  "defecto_causa.id_defecto='$defecto' and defecto_causa.id_causa =  causas.id order by causas.nombre";
	   $r_8 = mysql_query($s_8);
	   while($d_8=mysql_fetch_array($r_8)) { ?>
	   <option value="<?php echo $d_8['id'];?>" <?php if($causa==$d_8['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_8['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Segmento</td>
	<td colspan="4"><input name="segmento" type="text" readonly="readonly" value="<?php echo $data_proy['nom_s'];?>" class="texto" size="37"></td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Cod. Scrap</td>
	<td><select name="codigo_scrap" style="width:200px;" class="texto" onChange="cambio_foco('editar','codigo_scrap');" tabindex="9" id="codigo_scrap">
	<option value=""></option>
		<?php $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap, causa_codigo where ";
		   $s_10.= "codigo_scrap.activo='1' and causa_codigo.id_causa='$causa' and causa_codigo.id_codigo =  codigo_scrap.id order ";
		   $s_10.= "by codigo_scrap.codigo";
	  	   $r_10 = mysql_query($s_10);
	   while($d_10=mysql_fetch_array($r_10)) { ?>
	   <option value="<?php echo $d_10['codigo'];?>" <?php if($codigo_scrap==$d_10['codigo']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_10['codigo']." (".$d_10['descripcion'].")";?></option><?php } ?>
	</select></td>
	<?php $cod_scr = data_cod_scrap($data_proy['nom_pc'], $codigo_scrap); ?>
</tr>		
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Profit Center</td>
	<td colspan="4"><input name="prce" type="text" readonly="readonly" value="<?php echo $data_proy['nom_pc'];?>" class="texto" size="37"></td>
	<td align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Orden Interna</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $cod_scr['oi'];?>" readonly="readonly" name="orden_interna"></td>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;APD</td>
	<?php $s_ = "select * from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
       $r_ = mysql_query($s_); $items = mysql_num_rows($r_);	
	   if($items<=0) { ?>	
	<td colspan="4"><select name="apd" style="width:215px;" class="texto" tabindex="3" onChange="cambio_foco('editar','apd');">
		<option value=""></option>
		<?php $s_3 = "select * from apd where id_division='$data_proy[id_d]' and id_segmento='$data_proy[id_s]' and activo='1' ";
		   $s_3.= "order by nombre";
	  	   $r_3 = mysql_query($s_3);
	   	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($apd==$d_3['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td><?php } else { 
		$s_3 = "select * from apd where id='$apd'";
		$s_3.= "order by nombre";
	  	$r_3 = mysql_query($s_3);
		$d_3=mysql_fetch_array($r_3); ?>
	<td colspan="4">	
		<input type="hidden" name="apd" value="<?php echo $apd;?>">
		<input type="text" class="texto" size="37" value="<?php echo $d_3['nombre'];?>" readonly="readonly">
	</td><?php } ?>	
	<td align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Reason Code</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $cod_scr['rc'];?>" readonly="readonly" name="reason_code"></td>		
</tr>
<?php //-----------------------------------------------------------------------------------
	  if($cod_scr['fin']=='1') { ?>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="10" class="naranja" align="left">
    Llene los siguientes campos para la causa original del código financiero seleccionado:</td></tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;&Aacute;rea</td>
	<td colspan="4">
    <select name="area_2" style="width:215px;" class="texto" onchange="cambio_foco('editar','area_2');" tabindex="10">
		<option value=""></option>
		<?php $s_4 = "select * from areas where activo='1' order by nombre";
	  	   $r_4 = mysql_query($s_4);
	   	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($area_2==$d_4['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td rowspan="3" background="../imagenes/separador_1.png">&nbsp;</td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Tecnología</td>
	<td>
	<?php $s_5 = "select estaciones.* from estaciones, est_proyecto where estaciones.activo='1' and estaciones.id_area='$area_2' ";
	      $s_5.= "and est_proyecto.id_tecnologia = estaciones.id and est_proyecto.id_proyecto = '$proyecto' order by nombre";
	      $r_5 = mysql_query($s_5); ?>	
	<select name="estacion_2" style="width:200px;" class="texto" onchange="cambio_foco('editar','estacion_2');" tabindex="11">
		<option value=""></option>
		<?php while($d_5=mysql_fetch_array($r_5)) { ?>
	    <option value="<?php echo $d_5['id'];?>" <?php if($estacion_2==$d_5['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_5['nombre'];?></option>
	    <?php } ?>
	</select></td>
</tr>	
<tr>	
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Línea</td>
	<td colspan="4">
    <select name="linea_2" style="width:215px;" class="texto" onchange="cambio_foco('editar','linea_2');" tabindex="12">
		<option value=""></option>
		<?php $s_6 = "select lineas.* from lineas, lineas_proy where lineas.activo='1' and lineas.id_area='$area_2' and ";
		      $s_6.= "lineas.id_estacion='$estacion_2' and lineas_proy.id_linea = lineas.id and lineas_proy.id_proyecto='$proyecto' ";
			  $s_6.= "order by nombre";
	   	      $r_6 = mysql_query($s_6);
	   	while($d_6=mysql_fetch_array($r_6)) { ?>
	    <option value="<?php echo $d_6['id'];?>" <?php if($linea_2==$d_6['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_6['nombre'];?></option>
	    <?php } ?>
	</select></td>	
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Defecto</td>
	<td>
    <select name="defecto_2" style="width:200px;" class="texto" onchange="cambio_foco('editar','defecto_2');" tabindex="13">
	   <option value=""></option>
		<?php $s_7 = "select defectos.* from defectos, def_proyecto where defectos.activo='1' and defectos.id_area='$area_2' and ";
		      $s_7.= "defectos.id_estacion='$estacion_2' and def_proyecto.id_defecto = defectos.id and def_proyecto.id_proyecto = ";
			  $s_7.= "'$proyecto' order by nombre";
	   	      $r_7 = mysql_query($s_7);
	   	   while($d_7=mysql_fetch_array($r_7)) { ?>
	   <option value="<?php echo $d_7['id'];?>" <?php if($defecto_2==$d_7['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_7['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Relacionado a</td>
	<td colspan="4">
    <select name="causa_2" style="width:215px;" class="texto" onchange="cambio_foco('editar','causa_2');" tabindex="14">
		<option value=""></option>
	<?php $s_8 = "select causas.id, causas.nombre from causas, defecto_causa where causas.activo='1' and ".
			  "defecto_causa.id_defecto='$defecto_2' and defecto_causa.id_causa =  causas.id order by causas.nombre";
	   $r_8 = mysql_query($s_8);
	   while($d_8=mysql_fetch_array($r_8)) { ?>
	   <option value="<?php echo $d_8['id'];?>" <?php if($causa_2==$d_8['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_8['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Cod. SCRAP</td>
    <td><select name="codigo_scrap_2" style="width:200px;" class="texto" tabindex="15">
		<option value=""></option>
		<?php $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap, causa_codigo where ";
		   $s_10.= "codigo_scrap.activo='1' and causa_codigo.id_causa='$causa_2' and causa_codigo.id_codigo =  codigo_scrap.id and ";
		   $s_10.= "codigo_scrap.financiero='0' order by codigo_scrap.codigo";
	  	   $r_10 = mysql_query($s_10);
	   while($d_10=mysql_fetch_array($r_10)) { ?>
	   <option value="<?php echo $d_10['codigo'];?>" <?php if($codigo_scrap_2==$d_10['codigo']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_10['codigo']." (".$d_10['descripcion'].")";?></option><?php } ?>
	</select></td>
</tr>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<?php } //----------------------------------------------------------------------------------- ?>
</table>
<?php $tipos = get_tipo($parte); $batchs = get_batch($parte); $apd_nom = get_dato("nombre",$apd,"apd"); ?>
<br><table align="center" class="texto" cellpadding="2" cellspacing="2" border="0">
<tr bgcolor="#CCCCCC">
	<td width="120" align="center" class="gris"><b>No.Parte</b></td>
	<td width="40" align="center" class="gris"><b>Cantidad</b></td>
	<td width="40" align="center" class="gris"><b>Tipo</b></td>
	<td width="70" align="center" class="gris"><b>SubTipo</b></td>
	<td width="90" align="center" class="gris"><b>Batch ID</b></td>
	<td width="100" align="center" class="gris"><b>No.Serial</b></td>
	<td width="70" align="center" class="gris"><b>Ubicación</b></td>
	<td width="120" align="center" class="gris"><b>Parte Padre</b></td>
	<td width="20" align="center" class="gris"><b>Acción</b></td>
</tr>	
<tr bgcolor="#DDDDDD">
	<td align="center"><input type="text" name="parte" id="modelos" class="texto" value="<?php echo $parte;?>" tabindex="10" onBlur="cambio_foco('editar','cantidad');" size="18"></td>
	<td align="center"><input type="text" name="cantidad" size="3" class="texto" tabindex="11" value="<?php echo $cantidad;?>" onKeyPress="return solo_numeros(event);" maxlength="11"></td>
	<td align="center"><input type="text" name="tipo" value="<?php echo $tipos['tipo'];?>" readonly="readonly" size="3" class="texto"></td>
	<td align="center"><input type="text" name="subt" value="<?php echo $tipos['subt'];?>" readonly="readonly" size="8" class="texto"></td>		
	<td align="center">
		<?php if(count($batchs)=='0') { ?><input type="text" class="texto" size="12" name="batch_id" value="NA"><?php } ?>
		<?php if(count($batchs)=='1') { ?><input type="text" class="texto" size="12" name="batch_id" value="<?php echo $batchs['0'];?>"><?php } ?>
		<?php if(count($batchs)>'1') { ?>
	<select name="batch_id" class="texto" style="width:120px;" tabindex="12">
		<option value=""></option>
		<?php for($i=0;$i<count($batchs);$i++) { ?>
		<option value="<?php echo $batchs[$i];?>" <?php if($batchs[$i]==$batch){?> selected="selected"<?php }?>><?php echo $batchs[$i];?></option>
		<?php } ?>
	</select><?php } ?></td>
	<td align="center">
	<input type="text" name="serial_unidad" size="14" class="texto" tabindex="13" onKeyPress="return solo_numeros(event);" value="<?php echo $serial_unidad;?>"></td>
	<?php $s_2 = "select * from proy_ubicacion where id_proyecto='$proyecto' order by ubicacion";
  	   $r_2 = mysql_query($s_2); ?>
	<td align="center">
	<select name="ubicacion" style="width:70px;" class="texto" tabindex="14">
  	   <option value=""></option>
	   <?php while( $d_2 = mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['ubicacion'];?>" <?php if($ubicacion==$d_2['ubicacion']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_2['ubicacion'];?></option>
	   <?php } ?>
	</select></td>		
	<td align="center">
	<?php if($cod_scr['txs']=='ZSCR') { 

	    $s_2 = "select padre from partes_padre where material='$parte' and apd='$apd_nom' and type='$tipos[tipo]' group by padre ";
	   	$s_2.= "order by padre";
		$r_2 = mysql_query($s_2); $i=0;
	    while($d_2=mysql_fetch_array($r_2)) {
			   $padres[$i]  = $d_2['padre'];
			   $mostrar[$i] = $d_2['padre']; $i++; }  

		if(count($padres)<=0 && $tipos['tipo']=="FERT") {
			$padres['0'] = $parte;   
			$mostrar['0'] = $parte;  } ?>
		<select name="padre" id="padre" class="texto" style="width:120px;" tabindex="14">	
		 <option value=""></option>
		 <?php for($i=0;$i<count($padres);$i++) { ?>
			 <option value="<?php echo $padres[$i];?>"><?php echo $mostrar[$i];?></option><?php } ?>
		</select><?php } else { ?>
		<input type="text" name="padre" size="18" class="texto" readonly="readonly" value="NA"><?php } ?></td> 
	<td align="center"><input type="button" value=" + " class="texto" onClick="validar_add('<?php echo $tipos['tipo'];?>','<?php echo $cod_scr['txs'];?>','<?php echo $codigo_scrap;?>','<?php echo $apd;?>')" tabindex="15"></td>	
</tr>
<?php $s_ = "select * from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
   $r_ = mysql_query($s_); $items = mysql_num_rows($r_);
   while($d_=mysql_fetch_array($r_)) { ?>
<tr bgcolor="#EEEEEE">
	<td align="center"><input type="text" class="texto" size="18" value="<?php echo $d_['no_parte'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="3" class="texto" value="<?php echo $d_['cantidad'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="3" class="texto" value="<?php echo $d_['tipo'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="8" class="texto" value="<?php echo $d_['tipo_sub'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="12" class="texto" value="<?php echo $d_['batch_id'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="14" class="texto" value="<?php echo $d_['serial_unidad'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="8" class="texto" value="<?php echo $d_['ubicacion'];?>" disabled="disabled"></td>
	<td align="center"><input type="text" size="18" class="texto" value="<?php echo $d_['padre'];?>" disabled="disabled"></td>
	<td align="center">
		<input type="button" value=" - " class="texto" onClick="validar_del('<?php echo $d_['id'];?>')" tabindex="16" style="width:20px;"></td>	
</tr>
<?php } ?>
</table>

<br><table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_azul.gif"></td>
	<td width="80">&nbsp;O.Mantto</td>
	<td width="170" align="center"><input type="text" name="o_mantto" value="<?php echo $o_mantto;?>" size="37" class="texto" tabindex="17" onKeyPress="return solo_numeros(event);"></td>	
	<td rowspan="3" background="../imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td width="95">&nbsp;Supervisor</td>
	<td><input type="text" name="supervisor" class="texto" size="34" value="<?php echo $supervisor;?>" tabindex="21" onKeyPress="return  soloLetras(evt);"></td>	
</tr>
<tr>
	<td width="20" align="center">&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Operador</td>
    <td><input type="text" size="34" class="texto" name="operador" value="<?php echo $operador;?>" tabindex="22" onKeyPress="return  soloLetras(evt);"></td>	
</tr>
<tr>
	<?php if($cod_scr['info']=="SI") { ?>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Info.Obligatoria</td>
	<td><select name="info_1" class="texto" style="width:65px;" tabindex="19">
		<option value=""></option>
		<option value="CO" <?php if($info_1=="CO"){?> selected="selected"<?php } ?>>CO</option>
		<option value="ECN" <?php if($info_1=="ECN"){?> selected="selected"<?php } ?>>ECN</option>
		<option value="LSR" <?php if($info_1=="LSR"){?> selected="selected"<?php } ?>>LSR</option>
		<option value="QN" <?php if($info_1=="QN"){?> selected="selected"<?php } ?>>QN</option>
		<option value="VUG" <?php if($info_1=="VUG"){?> selected="selected"<?php } ?>>VUG</option>
		<option value="VUT" <?php if($info_1=="VUT"){?> selected="selected"<?php } ?>>VUT</option>
		</select><input type="text" name="info_2" value="<?php echo $info_2;?>" size="24" class="texto" tabindex="20" onKeyPress="return solo_numeros(event);"></td><?php } else { ?><td colspan="3">&nbsp;</td><?php } ?>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;No.Personal</td>
    <td><input type="text" name="no_personal" class="texto" size="34" value="<?php echo $no_personal;?>" tabindex="23" onKeyPress="return solo_numeros(event);"></td>	
	<td colspan="3">&nbsp;</td>
</tr>	
<?php validar_autorizadores($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); ?>
<tr><td colspan="7" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<?php $aplica = aplica_lo_loa($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); 
      if($aplica=='SI' && $archivo!='carga_masiva') { ?>
<tr>
	<td colspan="10" class="naranja" align="left">
    Si no desea modificar el archivo, deje el campo en blanco:
    &nbsp;&nbsp;
	<?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$archivo; 		
	      if($archivo) { ?><a href="<?php echo $ruta;?>" target="_blank" class="menuLink">Archivo Actual</a><?php } ?></td>
</tr>      
<tr>
	<td colspan="10" align="left">Archivo de evidencia:&nbsp;&nbsp;<input type="file" name="archivo" class="texto" size="50"></td>
</tr>
<tr><td colspan="7" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<?php } ?>
<tr>
	<td colspan="7">
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
	<tr>
		<td align="center"><img src="../imagenes/cuadro_azul.gif">&nbsp;Comentarios</td>
		<td width="10" rowspan="2">&nbsp;</td>
		<td align="center"><img src="../imagenes/cuadro_azul.gif">&nbsp;Acci&oacute;n Correctiva</td>
	</tr>
	<tr>
		<td align="center">
			<textarea name="comentario" cols="60" rows="2" class="texto" tabindex="24"><?php echo $comentario;?></textarea></td>
		<td align="center">
			<textarea name="accion_correctiva" cols="60" rows="2" class="texto" tabindex="25"><?php echo $accion_correctiva;?></textarea></td>
	</table></td>
</tr>
</table>
<br><div align="center" class="naranja">&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if(is_bloqueado()=='SI') { 
		echo "El administrador está modificando el sistema.<br>Espere un momento y actualice<br><br>"; $dis = 'disabled'; } ?>
<input type="button" value="Guardar" onClick="validar_m(2,'<?php echo $cod_scr['info'];?>','<?php echo $items;?>','<?php echo $aplica;?>','<?php echo $archivo;?>','<?php echo $cod_scr['info'];?>');" class="submit" tabindex="27" <?php echo $dis;?>>
</div>
</form>
<?php } 


function del_temp($id_borrar,$folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name) {
	$s_ = "delete from scrap_partes_tmp where id='$id_borrar'";
	$r_ = mysql_query($s_);	
}


function add_temp($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$ubicacion,$padre,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name) {

$data_p  = get_tipo($parte); $i=0;
if(count($data_p)>0) { 
	$partes[$i] = $padre; $i++;
	$partes[$i] = $parte; $i++;
	$partes[$i] = $data_p['tipo']; $i++;
	$partes[$i] = $data_p['subt']; $i++;
	$partes[$i] = $data_p['desc']; $i++;
	$partes[$i] = $cantidad; $i++;
	$partes[$i] = $data_p['costo']; $i++;
	$partes[$i] = $data_p['costo']*$cantidad; $i++;
	$partes[$i] = $batch_id; $i++;
	$partes[$i] = $serial_unidad; $i++;
	$partes[$i] = $ubicacion;

	$s_1 = "insert into scrap_partes_tmp values ('',";
	for($i=0;$i<count($partes);$i++) {
			$s_1 = $s_1."'".$partes[$i]."',"; }
	$s_1 = $s_1."'$_SESSION[IDEMP]')";
	$r_1 = mysql_query($s_1);
	}
}


function update($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name) {

//Obtengo el código de scrap viejo
$s_2 = "select archivo, codigo_scrap from scrap_folios where no_folio='$folio'"; 
$r_2 = mysql_query($s_2);
$d_2 = mysql_fetch_array($r_2);
$cod = $d_2['codigo_scrap'];

//Obtengo el id del autorizador que había rechazado
$s_1 = "select id_emp, depto from autorizaciones where no_folio='$folio' and status='2'";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);
$emp = $d_1['id_emp']; 

//Borro todo de la boleta para volver a ingresarlo
$s_1 = "delete from scrap_partes where no_folio='$folio'"; 
$r_1 = mysql_query($s_1); 
$s_1 = "delete from scrap_folios where no_folio='$folio'"; 
$r_1 = mysql_query($s_1); 
$s_1 = "delete from scrap_codigos where no_folio='$folio'";
$r_1 = mysql_query($s_1);

if($archivo!='') { 
	$s_ = "select * from configuracion where variable='ruta_evidencias'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = "evidencia_".$folio.".".$pext;	
	$nom_final = $r_server.$nombre;
	
	if(file_exists($nom_final)) { unlink($nom_final); }
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; }
	
	}
} else { $nombre = $old; }

    $fecha	 = date("Y-m-d");
	$anio	 = date("Y");
	list($anio,$mes,$dia) = split("-",$fecha);
    $semana  = date('W',mktime(0,0,0,$mes,$dia,$anio)); $i=0;
	
	$folios[$i] = $_SESSION['IDEMP']; $i++;
	$folios[$i] = $_SESSION['NAME']; $i++;
	$folios[$i] = $folio; $i++;
	$folios[$i] = $fecha; $i++;
	$folios[$i] = $semana; $i++; 
	$folios[$i] = $anio; $i++;
	$folios[$i] = $turno; $i++;

	$d_pr      = get_datos_proyecto($proyecto);
	$folios[$i] = $d_pr['id_pr']; $i++; //ID Proyecto
	$folios[$i] = $d_pr['nom_pr']; $i++; //Nombre Proyecto
	$folios[$i] = $d_pr['id_p']; $i++; //ID Planta
	$folios[$i] = $d_pr['nom_p']; $i++; //Nombre Planta
	$folios[$i] = $d_pr['id_d']; $i++; //ID División
	$folios[$i] = $d_pr['nom_d']; $i++; //Nombre División
	$folios[$i] = $d_pr['id_s']; $i++; //ID Segmento
	$folios[$i] = $d_pr['nom_s']; $i++; //Nombre Segmento
	$folios[$i] = $d_pr['id_pc']; $i++; //ID ceco
	$folios[$i] = $d_pr['nom_pc']; $i++; //Nombre ceco

	$folios[$i] = $apd; $i++;
	$folios[$i] = get_dato("nombre",$apd,"apd"); $i++;
	$folios[$i] = $area; $i++;
	$folios[$i] = get_dato("nombre",$area,"areas"); $i++;
	$folios[$i] = $estacion; $i++;
	$folios[$i] = get_dato("nombre",$estacion,"estaciones"); $i++;	
	$folios[$i] = $linea; $i++;
	$folios[$i] = get_dato("nombre",$linea,"lineas"); $i++;
	$folios[$i] = $defecto; $i++;	
	$folios[$i] = get_dato("nombre",$defecto,"defectos"); $i++;		
	$folios[$i] = $causa; $i++;
	$folios[$i] = get_dato("nombre",$causa,"causas"); $i++;
	$folios[$i] = $codigo_scrap; $i++;
	$cod_scr   = data_cod_scrap($d_pr['nom_pc'], $codigo_scrap);
	$folios[$i] = $cod_scr['fin']; $i++;
	$folios[$i] = $cod_scr['rc']; $i++;
	$folios[$i] = $cod_scr['oi']; $i++;
	$folios[$i] = $cod_scr['txs']; $i++;
	$folios[$i] = $cod_scr['mov']; $i++;
	$folios[$i] = $supervisor; $i++;
	$folios[$i] = $operador; $i++;
	$folios[$i] = $no_personal; $i++;		
	if($info_1=='') { $info_1 = 'NA'; }
	$folios[$i] = $info_1; $i++;
	$folios[$i] = $info_2; $i++;
	$folios[$i] = $o_mantto; $i++;
	//Revisar nuevamente los autorizadores que aplican por si estos cambiaron para el adjunto
	if(aplica_lo_loa($d_pr['id_p'],$codigo_scrap,$d_pr['id_d'],$d_pr['id_pc'],$area,$d_pr['id_pr'])=='SI') {
		$folios[$i] = $nombre; $i++; }
	else { $folios[$i] = ''; $i++; }	
	$folios[$i] = $comentario; $i++;	
	$folios[$i] = $accion_correctiva;

	if($from=='proceso')   { $estado = '0'; }
	if($from=='rechazado') { $estado = '2'; }

	$s_1 = "insert into scrap_folios values ('',";
	for($i=0;$i<count($folios);$i++) {
			$s_1 = $s_1."'".$folios[$i]."',"; }
	$s_1 = $s_1."'$estado',1)"; 
	$r_1 = mysql_query($s_1); $i=0;	
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1); 

	//Si es un código scrap que tiene financiero en 1
	if($cod_scr['fin']=='1') { 
		$cod_id_area   = $area_2; 
		$cod_nom_area  = get_dato("nombre",$area_2,"areas"); 
		$cod_id_est    = $estacion_2;
		$cod_nom_est   = get_dato("nombre",$estacion_2,"estaciones");
		$cod_id_linea  = $linea_2; 
		$cod_nom_linea = get_dato("nombre",$linea_2,"lineas");
		$cod_id_def    = $defecto_2; 
		$cod_nom_def   = get_dato("nombre",$defecto_2,"defectos"); 		
		$cod_id_cau    = $causa_2; 
		$cod_nom_cau   = get_dato("nombre",$causa_2,"causas");
	
		$s_1 = "insert into scrap_codigos values('', '$folio', '$cod_id_area', '$cod_nom_area', '$cod_id_est', '$cod_nom_est', ";
		$s_1.= "'$cod_id_linea', '$cod_nom_linea', '$cod_id_def', '$cod_nom_def', '$cod_id_cau', '$cod_nom_cau', '$codigo_scrap_2')";
		$r_1 = mysql_query($s_1); 
		/*LOG SISTEMA*/log_sistema("scrap","nuevo",$s_1); }

	$s_ = "select * from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	while($d_=mysql_fetch_array($r_)) {
		$s_1 = "insert into scrap_partes values('', '$folio', '$d_[padre]', '$d_[no_parte]', '$d_[tipo]', '$d_[tipo_sub]', ";
		$s_1.= "'$d_[descripcion]', '$d_[cantidad]', '$d_[costo]', '$d_[total]', '$d_[batch_id]', '$d_[serial_unidad]', ";
		$s_1.= "'$d_[ubicacion]', '0', '0')";
		$r_1 = mysql_query($s_1);
		/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1); 
	}
	
	//Reasigno los autorizadores que aplican sólo si cambió el código de scrap
	if($codigo_scrap!=$cod) {
		$s_a = "delete from autorizaciones where no_folio='$folio'";
		$r_a = mysql_query($s_a); 
		autorizaciones($folio,$d_pr['id_p'],$codigo_scrap,$d_pr['id_d'],$d_pr['id_pc'],$area,$d_pr['id_pr']);
	}	
	
	$s_ = "delete from scrap_temporal where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);	
	if($from=='rechazado') { 
		enviar_aviso_autorizador($folio,$emp); }
		echo"<script>alert('Registro Almacenado');</script>"; 
} 

function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>