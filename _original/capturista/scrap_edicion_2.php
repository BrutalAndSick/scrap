<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   include("../conexion_db.php");
   include("../generales.php"); ?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>		
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../pop_Calendar/calendar.css">
<!--------------------------Calendario---------------------------->
<script language="JavaScript" src="../pop_Calendar/GCappearance.js"></script>
<script language="JavaScript" src="../pop_Calendar/GurtCalendar.js"></script>
<!--------------------------Tooltips---------------------------->
<script language="JavaScript" src="../css/boxover.js"></script>
<!--------------------------Autocompletar---------------------------->
<script language="javascript" type="text/javascript" src="../css/actb.js"></script>
<script language="javascript" type="text/javascript" src="../css/common.js"></script>
<script>
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
						editar($folio,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
						$from,'','','','',$porque1,$porque2,$porque3,$porque4,$porque5); break;
	case "editar"	:	editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$vendor,$v_nombre,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$padre,$supervisor,$operador,
						$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$partes,
						$partes_name,$archivo,$archivo_name,$porque1,$porque2,$porque3,$porque4,$porque5); break;															
						//Si no cargo nuevamente el archivo de partes
	case "update"	:	update($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
						$causa_2,$codigo_scrap,$vendor,$v_nombre,$codigo_scrap_2,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,
						$info_2,$comentario,$accion_correctiva,$from,$partes,$partes_name,$archivo,$archivo_name,$porque1,$porque2,$porque3,$porque4,$porque5); 
						editar($folio,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
						$from,'','','','',$porque1,$porque2,$porque3,$porque4,$porque5); break;
	}	
	

	
function menu_scrap($folio) { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo" align="left">EDITAR BOLETA FOLIO <?php echo $folio;?></td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
	<span title="header=[&nbsp;&nbsp;Captura de Scrap] body=[Complete todos los campos de cada parte del formulario. Los cuadros de colores indican lo siguiente:<table align='center' border='0' cellspacing='2' class='texto'><tr bgcolor='#FFFFFF'><td class='obligatorio' width='60'>&nbsp;ROJO</td><td width='100'>&nbsp;Obligatorio</td></tr><tr bgcolor='#FFFFFF'><td class='automatico'>&nbsp;VERDE</td><td>&nbsp;Automático</td></tr><tr bgcolor='#FFFFFF'><td class='opcional'>&nbsp;AZUL</td><td>&nbsp;Opcional</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>
</tr>
</table><hr><?php $s_ = "select * from aut_bitacora where no_folio='$folio' and status='2' order by id desc";
      $r_ = mysql_query($s_);
	  if(mysql_num_rows($r_)>0) { 
      $d_ = mysql_fetch_array($r_); ?>
      <div align="center" class="naranja"><b>Comentarios:</b>&nbsp;&nbsp;<?php echo $d_['comentario'];?></div>
      <?php } ?>
<br>
<?php } 


function editar($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$vendor,$v_nombre,$codigo_scrap_2,$parte,$cantidad,$batch_id,$serial_unidad,$padre,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$archivo,$archivo_name,$porque1,$porque2,$porque3,$porque4,$porque5){ 

$s_1 = "select * from scrap_folios where no_folio='$folio' group by no_folio";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);

$s_2 = "select * from scrap_codigos where no_folio='$folio' group by no_folio";
$r_2 = mysql_query($s_2);
$d_2 = mysql_fetch_array($r_2);

$s_3 = "select * from scrap_porques where no_folio='$folio' group by no_folio";
$r_3 = mysql_query($s_3);
$d_3 = mysql_fetch_array($r_3);

	if(!$turno)	 		 		$turno=$d_1['turno'];
	if(!$proyecto)	 			$proyecto=$d_1['id_proyecto']; 
	if(!$area)			 		$area=$d_1['id_area'];
	if(!$estacion)	 			$estacion=$d_1['id_estacion'];
	if(!$linea)		 	 		$linea=$d_1['id_linea'];
	if(!$defecto)    	 		$defecto=$d_1['id_defecto'];
	if(!$causa)		 	 		$causa=$d_1['id_causa'];
	if(!$codigo_scrap)			$codigo_scrap=$d_1['codigo_scrap'];
	if(!$vendor)				$vendor=$d_1['vendor'];
	if(!$v_nombre)				$v_nombre=$d_1['v_nombre'];
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
	if(!$codigo_scrap_2)		$codigo_scrap_2=$d_2['codigo_scrap'];

	if(!$porque1)		 		$porque1=$d_3['porque_1'];
	if(!$porque2)		 		$porque2=$d_3['porque_2'];
	if(!$porque3)		 		$porque3=$d_3['porque_3'];
	if(!$porque4)		 		$porque4=$d_3['porque_4'];
	if(!$porque5)		 		$porque5=$d_3['porque_5'];	
	
	if($d_1['status']!='0') { $dis_1 = "disabled"; } else { $dis_1 = ""; } ?>	
	
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
<?php	$s_pr = "select limite from proyectos where id='$proyecto' and limite>='".date("Y-m-d H:i:s")."'";
		$r_pr = mysql_query($s_pr);
		if(mysql_num_rows($r_pr)>0) { $limite_pr = 'si'; } else { $limite_pr = 'no'; } ?>
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
		<?php //El código 035-1 solamente se puede usar cuando sea tipo FERT/ el 095 solamente en casos especiales y en proyectos nuevos (límite)
		   $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap, causa_codigo where ";
		   $s_10.= "codigo_scrap.activo='1' and causa_codigo.id_causa='$causa' and causa_codigo.id_codigo =  codigo_scrap.id ";
		   if($tipos['tipo']!='ROH' && $tipos['tipo']!='') { $s_10.= "and codigo!='035-1' "; } 
		   if($limite_pr=='si') { 
		   $s_10.= "UNION select id, codigo, descripcion from codigo_scrap where activo='1' and codigo like '095'"; }
		   $s_10.= "group by codigo order by codigo";
	  	   $r_10 = mysql_query($s_10);
	   while($d_10=mysql_fetch_array($r_10)) { $capturar = 1; 
		 //Si ese código tiene restricción para que lo capture un usuario específico lo quito
		 $s_cs = "select * from codigo_scrap_emp where codigo='$d_10[codigo]'";
		 $r_cs = mysql_query($s_cs); 
		 if(mysql_num_rows($r_cs)>0) { 
			$s_ce = "select * from codigo_scrap_emp where codigo='$d_10[codigo]' and id_emp='$_SESSION[IDEMP]'";
			$r_ce = mysql_query($s_ce); 
			if(mysql_num_rows($r_ce)<=0) { $capturar=0; } }
		 //Si ese código tiene restricción para que lo capture un proyecto específico lo quito
		 $s_cs = "select * from codigo_scrap_proy where codigo='$d_10[codigo]'";
		 $r_cs = mysql_query($s_cs); 
		 if(mysql_num_rows($r_cs)>0) { 
			$s_ce = "select * from codigo_scrap_proy where codigo='$d_10[codigo]' and id_proy='$data_proy[id_pr]'";
			$r_ce = mysql_query($s_ce); 
			if(mysql_num_rows($r_ce)<=0) { $capturar=0; } }	 
		 if($capturar=='1') { ?>		   
	   <option value="<?php echo $d_10['codigo'];?>" <?php if($codigo_scrap==$d_10['codigo']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_10['codigo']." (".$d_10['descripcion'].")";?></option><?php } } ?>
	</select></td>
	<?php $cod_scr = data_cod_scrap($data_proy['nom_pc'], $codigo_scrap); ?>
</tr>		
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif"></td>
	<td>&nbsp;Profit Center</td>
	<td colspan="4"><input name="prce" type="text" readonly="readonly" value="<?php echo $data_proy['nom_pc'];?>" class="texto" size="37"></td>
	<td align="center"><img src="../imagenes/cuadro_verde.gif"></td>
    <?php //Si aplica la orden interna se muestra y se busca si tiene OI Especial o se genera
		  $s_oi = "select orden_interna from oi_especial where (id_division='$data_proy[id_d]' or id_division='na') and (id_segmento='$data_proy[id_s]' or ";
		  $s_oi.= "id_segmento='na') and (id_pc='$data_proy[id_pc]' or id_pc='na') and (id_proyecto='$data_proy[id_pr]' or id_proyecto='todos') and ";
		  $s_oi.= "codigo_scrap='$codigo_scrap' and activo='1'";
		  $r_oi = mysql_query($s_oi);
		  if(mysql_num_rows($r_oi)>0) { 
			  $d_oi = mysql_fetch_array($r_oi);
			  $orden_interna = $d_oi['orden_interna']; }
		  else { $orden_interna = $cod_scr['oi']; } ?>	    
	<td>&nbsp;Orden Interna</td>
	<td><input type="text" class="texto" size="34" value="<?php echo $orden_interna;?>" readonly="readonly" name="orden_interna"></td>	
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
<?php //Si el código de SCRAP requiere número de vendor se activa el campo
	  $aplica_vendor = 'no';
	  $s_v1 = "select vendor from codigo_scrap where codigo='$codigo_scrap' and activo='1'";
	  $r_v1 = mysql_query($s_v1); 
	  $d_v1 = mysql_fetch_array($r_v1);
	  if($d_v1['vendor']=='1') { $aplica_vendor='si'; ?>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Vendor #</td>
    <td colspan="4"><input type="text" name="vendor" class="texto" size="37" value="<?php echo $vendor;?>"></td>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Vendor</td>
	<?php $s_v2 = "select nombre from vendors where vendor='$vendor' and activo='1'";
	   	  $r_v2 = mysql_query($s_v2);
	      $d_v2 = mysql_fetch_array($r_v2); ?> 
    <td colspan="4"><input type="text" class="texto" size="34" value="<?php echo $d_v2['nombre'];?>" readonly="readonly" name="v_nombre" onfocus="submit();"></td>
</tr><?php } ?>	

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
	<td>&nbsp;Defecto</td>
	<td><select name="defecto_2" style="width:200px;" class="texto" onchange="cambio_foco('editar','defecto_2');" tabindex="13">
	   <option value=""></option>
		<?php if($limite_pr!='si') { 
		      $s_7 = "select defectos.* from defectos, def_proyecto where defectos.activo='1' and defectos.id_area='$area_2' and ";
		      $s_7.= "defectos.id_estacion='$estacion_2' and def_proyecto.id_defecto = defectos.id and def_proyecto.id_proyecto = ";
			  $s_7.= "'$proyecto' order by nombre"; }
			  else { 
		      $s_7 = "select defectos.* from defectos where defectos.activo='1' and defectos.id_area='$area_2' and ";
		      $s_7.= "defectos.id_estacion='$estacion_2' order by nombre"; }			  
	   	      $r_7 = mysql_query($s_7);
	   	   while($d_7=mysql_fetch_array($r_7)) { ?>
	   <option value="<?php echo $d_7['id'];?>" <?php if($defecto_2==$d_7['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_7['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>	

<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbspTecnología&iacute;a</td>
	<td colspan="4">
	<?php $s_5 = "select estaciones.* from estaciones, est_proyecto where estaciones.activo='1' and estaciones.id_area='$area_2' ";
	      $s_5.= "and est_proyecto.id_tecnologia = estaciones.id and est_proyecto.id_proyecto = '$proyecto' order by nombre";
	      $r_5 = mysql_query($s_5); ?>	
	<select name="estacion_2" style="width:215px;" class="texto" onchange="cambio_foco('editar','estacion_2');" tabindex="11">
		<option value=""></option>
		<?php while($d_5=mysql_fetch_array($r_5)) { ?>
	    <option value="<?php echo $d_5['id'];?>" <?php if($estacion_2==$d_5['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_5['nombre'];?></option>
	    <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Relacionado a</td>
	<td><select name="causa_2" style="width:200px;" class="texto" onchange="cambio_foco('editar','causa_2');" tabindex="14">
		<option value=""></option>
	<?php if($limite_pr!='si') { 
	      $s_8 = "select causas.id, causas.nombre from causas, defecto_causa where causas.activo='1' and ";
		  $s_8.= "defecto_causa.id_defecto='$defecto_2' and defecto_causa.id_causa =  causas.id order by causas.nombre"; }
		  else { 	
	     	$s_8 = "select causas.id, causas.nombre from causas where causas.activo='1' order by causas.nombre"; }
	   $r_8 = mysql_query($s_8);
	   while($d_8=mysql_fetch_array($r_8)) { ?>
	   <option value="<?php echo $d_8['id'];?>" <?php if($causa_2==$d_8['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_8['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>	
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;L&iacute;nea</td>
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
	<td>&nbsp;Relacionado a</td>
	<td><select name="codigo_scrap_2" style="width:200px;" class="texto" tabindex="15">
		<option value=""></option>
		<?php if($limite_pr!='si') { 
		   $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap, causa_codigo where ";
		   $s_10.= "codigo_scrap.activo='1' and causa_codigo.id_causa='$causa_2' and causa_codigo.id_codigo =  codigo_scrap.id and ";
		   $s_10.= "codigo_scrap.financiero='0' order by codigo_scrap.codigo"; }
			  else { 	   
				   $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap where ";
				   $s_10.= "codigo_scrap.activo='1' and codigo_scrap.financiero='0' order by codigo_scrap.codigo"; }		
	  	   $r_10 = mysql_query($s_10);
	   while($d_10=mysql_fetch_array($r_10)) { ?>
	   <option value="<?php echo $d_10['codigo'];?>" <?php if($codigo_scrap_2==$d_10['codigo']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_10['codigo']." (".$d_10['descripcion'].")";?></option><?php } ?>
	</select></td>
</tr>
<?php } //----------------------------------------------------------------------------------- ?>

<?php validar_autorizadores($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); ?>
<?php $aplica = aplica_lo_loa($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); 
      if($aplica=='SI') { ?>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="10" class="naranja" align="left">
    Si no desea modificar el archivo de evidencias, deje el campo en blanco:
    &nbsp;&nbsp;	
	<?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$archivo; 		
	      if($archivo) { ?><a href="<?php echo $ruta;?>" target="_blank" class="menuLink">Archivo Actual</a><?php } ?></td></tr>      
<tr>
	<td colspan="10" align="left">Archivo de evidencia:&nbsp;&nbsp;
    <input type="file" name="archivo" class="texto" size="50" tabindex="16"></td>
</tr>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr><?php } ?>
</table>

<br><table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_azul.gif"></td>
	<td width="80">&nbsp;O.Mantto</td>
	<td width="170" align="center"><input type="text" name="o_mantto" value="<?php echo $o_mantto;?>" size="39" class="texto" tabindex="17" onKeyPress="return solo_numeros(event);"></td>	
	<td rowspan="3" background="../imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<script>
	var customarray=new Array();
	var i=0;
	<?	$s_1 = "select empleados.id, empleados.nombre, empleados.apellidos from empleados where empleados.activo='1' group by id order by apellidos, nombre";
		$r_1 = mysql_query($s_1);
		while($d_1=mysql_fetch_array($r_1)) { ?>
		customarray[i]="<? echo $d_1['apellidos']." ".$d_1['nombre'];?>"; i=i+1; <? } ?>		
	</script>
	<td width="95">&nbsp;Supervisor</td>
	<td>
	    <input type="text" size="34" class="texto" id="sup" name="supervisor" value="<?php echo trim($supervisor);?>" tabindex="2" <?php echo $dis;?>></td>
		<script> var obj = new actb(document.getElementById('sup'),customarray); </script>
	</td>
</tr>
<tr>
	<td width="20" align="center">&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Operador</td>
    <td><input type="text" size="34" class="texto" name="operador" value="<?php echo $operador;?>" tabindex="21" onKeyPress="return  soloLetras(evt);"></td>	
</tr>
<tr>
	<?php if($cod_scr['info']=="SI") { 
	      if($codigo_scrap!='') { 
			if($codigo_scrap=='010-3') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='010-4') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='010-5') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='010-6') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='010-7') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='012-1') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='013-1') { $aplica_qn = 'si'; $largo = '9'; }
			if($codigo_scrap=='025-3') { $aplica_qn = 'no'; $largo = '6'; } } else { $aplica_qn = '0'; $largo = '0'; } ?>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;Info.Obligatoria</td>
	<td><select name="info_1" class="texto" style="width:65px;" tabindex="25" onchange="submit();">
		<? if($aplica_qn=='si') { ?>
        <option value="QN" <?php if($info_1=="QN"){?> selected="selected"<?php } ?>>QN</option><? } ?>
        <? if($aplica_qn=='no') { ?>
        <option value=""></option>
		<option value="VUG" <?php if($info_1=="VUG"){?> selected="selected"<?php } ?>>VUG</option>
		<option value="VUT" <?php if($info_1=="VUT"){?> selected="selected"<?php } ?>>VUT</option>
        <option value="ECN" <?php if($info_1=="ECN"){?> selected="selected"<?php } ?>>ECN</option>
        <option value="CO" <?php if($info_1=="CO"){?> selected="selected"<?php } ?>>CO</option><? } ?>
		</select>
        <?php if($info_2!='' && strlen($info_2)>$largo){ $info_2 = ''; } ?>
    	<input type="text" name="info_2" value="<?php echo $info_2;?>" size="24" class="texto" tabindex="26" onKeyPress="return solo_numeros(event);" maxlength="<?php echo $largo;?>"></td><?php } else { ?><td colspan="3">&nbsp;</td><?php } ?>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif"></td>
	<td>&nbsp;No.Personal</td>
    <td><input type="text" name="no_personal" class="texto" size="34" value="<?php echo $no_personal;?>" tabindex="22" onKeyPress="return solo_numeros(event);"></td>	
	<td colspan="3">&nbsp;</td>
</tr>	

<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr><td colspan="10" class="naranja" align="left">
	Si no desea modificar el archivo de partes, deje el campo en blanco:&nbsp;&nbsp;
	<a href="../excel_reportes.php?op=ver_modelos&folio=<?php echo $folio;?>" class="menuLink">Archivo Actual</a></td></tr> 
<tr><td colspan="10" align="left">Archivo de partes:&nbsp;&nbsp;<input type="file" name="partes" class="texto" size="50" tabindex="23"></td></tr>  
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<tr>
	<td colspan="10">
	<table align="left" border="0" cellpadding="0" cellspacing="0" class="texto">
	<tr height="25">
		<td align="center" width="25"><img src="../imagenes/cuadro_azul.gif" /></td>
		<td width="80">&nbsp;Por qué 1</td>
		<td align="right">&nbsp;<input type="text" name="porque1" class="texto" tabindex="31" size="105" value="<?php echo $porque1;?>"></td>	
	</tr>
	<tr height="25">
		<td align="center" width="25"><img src="../imagenes/cuadro_azul.gif" /></td>
		<td width="80">&nbsp;Por qué 2</td>
		<td align="right">&nbsp;<input type="text" name="porque2" class="texto" tabindex="32" size="105" value="<?php echo $porque2;?>"></td>	
	</tr>
	<tr height="25">
		<td align="center" width="25"><img src="../imagenes/cuadro_azul.gif" /></td>
		<td width="80">&nbsp;Por qué 3</td>
		<td align="right">&nbsp;<input type="text" name="porque3" class="texto" tabindex="33" size="105" value="<?php echo $porque3;?>"></td>	
	</tr>
	<tr height="25">
		<td align="center" width="25"><img src="../imagenes/cuadro_azul.gif" /></td>
		<td width="80">&nbsp;Por qué 4</td>
		<td align="right">&nbsp;<input type="text" name="porque4" class="texto" tabindex="34" size="105" value="<?php echo $porque4;?>"></td>	
	</tr>
	<tr height="25">
		<td align="center" width="25"><img src="../imagenes/cuadro_azul.gif" /></td>
		<td width="80">&nbsp;Por qué 5</td>
		<td align="right">&nbsp;<input type="text" name="porque5" class="texto" tabindex="35" size="105" value="<?php echo $porque5;?>"></td>	
	</tr>	
	</table></td>
</tr>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
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
			<textarea name="comentario" cols="60" rows="2" class="texto" tabindex="24"><?php echo html_entity_decode($comentario,ENT_QUOTES,"UTF-8");?></textarea></td>
		<td align="center">
			<textarea name="accion_correctiva" cols="60" rows="2" class="texto" tabindex="25"><?php echo html_entity_decode($accion_correctiva,ENT_QUOTES,"UTF-8");?></textarea></td>
	</table></td>
</tr>
</table>
<br><div align="center" class="naranja">&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(is_bloqueado()=='SI') { 
		echo "El sistema de captura se encuentra bloqueado.<br>Contecte al administrador.<br><br>"; 
		$dis = 'disabled'; } ?>
<input type="button" value="Guardar" onClick="validar_a1(2,'<?php echo $cod_scr['info'];?>','<?php echo $cod_scr['fin'];?>','<?php echo $archivo;?>','<?php echo $aplica;?>','<?php echo $aplica_vendor;?>');" class="submit" tabindex="26" <?php echo $dis;?> <?php echo $dis_1;?>>
</div>
</form>
<?php } 


function update($folio,$turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$vendor,$v_nombre,$codigo_scrap_2,$supervisor,$operador,$no_personal,$apd,$o_mantto,$docto_sap,$info_1,$info_2,$comentario,$accion_correctiva,$from,$partes,$partes_name,$archivo,$archivo_name,$porque1,$porque2,$porque3,$porque4,$porque5) {

//Reviso si cambió el archivo de numeros de parte. Si algo sale mal, se cancela la actualización.
if($partes!='') { 
	$s_ = "select * from configuracion where variable='ruta_capturas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server   = $d_['valor'];
	$pext       = getFileExtension($partes_name);
	$nombre_    = "partes_UID".$_SESSION["IDEMP"].".".$pext;	
	$nom_final_ = $r_server.$nombre_;
	if (is_uploaded_file($partes)) {
		if (!copy($partes,"$nom_final_")) {
			echo "<script>alert('Error al subir el archivo de partes: $nom_final_');</script>"; exit;
		}
	}
	insert_csv($apd,$proyecto,$codigo_scrap);
	$s_1 = "delete from scrap_partes where no_folio='$folio'"; 
	$r_1 = mysql_query($s_1); 
	$s_ = "select * from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	while($d_=mysql_fetch_array($r_)) {
		$s_1 = "insert into scrap_partes values('', '$folio', '$d_[padre]', '$d_[no_parte]', '$d_[tipo]', '$d_[tipo_sub]', ";
		$s_1.= "'$d_[descripcion]', '$d_[cantidad]', '$d_[costo]', '$d_[total]', '$d_[batch_id]', '$d_[serial_unidad]', ";
		$s_1.= "'$d_[ubicacion]', '0', '0', '0', '')";
		$r_1 = mysql_query($s_1);
		/*LOG SISTEMA*/log_sistema("scrap_partes","editar",$folio,$s_1); 
	}	
}		

//Obtengo el código de scrap viejo
$s_2    = "select archivo, codigo_scrap, fecha, status from scrap_folios where no_folio='$folio'"; 
$r_2    = mysql_query($s_2);
$d_2    = mysql_fetch_array($r_2);
$cod    = $d_2['codigo_scrap'];
$old    = $d_2['archivo'];
$fecha2 = $d_2['fecha'];
$estado = $d_2['status'];

//Obtengo el id del autorizador que había rechazado
$s_1 = "select id_emp, depto from autorizaciones where no_folio='$folio' and status='2'";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);
$emp = $d_1['id_emp']; 

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

//Borro todo de la boleta para volver a ingresarlo
$s_1 = "delete from scrap_codigos where no_folio='$folio'";
$r_1 = mysql_query($s_1);
	$error   = 0;
    $fecha   = date("Y-m-d");
	$hora	 = date("H:i:s");
	//$anio	 = date("Y");
	$timer   = date("YmdHis");
	list($anio,$mes,$dia) = split("-",$fecha2);
    $semana  = date('W',mktime(0,0,0,$mes,$dia,$anio)); $i=0;
	
	$folios[$i] = $_SESSION['IDEMP']; $campo[$i] = 'id_emp'; $i++;
	$folios[$i] = $_SESSION['NAME']; $campo[$i] = 'empleado'; $i++;
	$folios[$i] = $folio; $campo[$i] = 'no_folio'; $i++;
	$folios[$i] = $fecha2; $campo[$i] = 'fecha'; $i++;
	$folios[$i] = $timer; $campo[$i] = 'timer'; $i++;
	$folios[$i] = $semana; $campo[$i] = 'semana'; $i++; 
	$folios[$i] = $anio; $campo[$i] = 'anio'; $i++;
	$folios[$i] = $turno; $campo[$i] = 'turno'; $i++;

	$d_pr      = get_datos_proyecto($proyecto);
	$folios[$i] = $d_pr['id_pr']; $campo[$i] = 'id_proyecto'; $i++; //ID Proyecto
	$folios[$i] = $d_pr['nom_pr']; $campo[$i] = 'proyecto'; $i++; //Nombre Proyecto
	$folios[$i] = $d_pr['id_p']; $campo[$i] = 'id_planta'; $i++; //ID Planta
	$folios[$i] = $d_pr['nom_p']; $campo[$i] = 'planta'; $i++; //Nombre Planta
	$folios[$i] = $d_pr['id_d']; $campo[$i] = 'id_division'; $i++; //ID División
	$folios[$i] = $d_pr['nom_d']; $campo[$i] = 'division'; $i++; //Nombre División
	$folios[$i] = $d_pr['id_s']; $campo[$i] = 'id_segmento'; $i++; //ID Segmento
	$folios[$i] = $d_pr['nom_s']; $campo[$i] = 'segmento'; $i++; //Nombre Segmento
	$folios[$i] = $d_pr['id_pc']; $campo[$i] = 'id_pc'; $i++; //ID ceco
	$folios[$i] = $d_pr['nom_pc']; $campo[$i] = 'profit_center'; $i++; //Nombre ceco

	$folios[$i] = $apd; $campo[$i] = 'id_apd'; $i++;
	$folios[$i] = get_dato("nombre",$apd,"apd"); $campo[$i] = 'apd'; $i++;
	$folios[$i] = $area; $campo[$i] = 'id_area'; $i++;
	$folios[$i] = get_dato("nombre",$area,"areas"); $campo[$i] = 'area'; $i++;
	$folios[$i] = $estacion; $campo[$i] = 'id_estacion'; $i++;
	$folios[$i] = get_dato("nombre",$estacion,"estaciones"); $campo[$i] = 'estacion'; $i++;	
	$folios[$i] = $linea; $campo[$i] = 'id_linea'; $i++;
	$folios[$i] = get_dato("nombre",$linea,"lineas"); $campo[$i] = 'linea'; $i++;
	$folios[$i] = $defecto; $campo[$i] = 'id_defecto'; $i++;	
	$folios[$i] = get_dato("nombre",$defecto,"defectos"); $campo[$i] = 'defecto'; $i++;		
	$folios[$i] = $causa; $campo[$i] = 'id_causa'; $i++;
	$folios[$i] = get_dato("nombre",$causa,"causas"); $campo[$i] = 'causa'; $i++;
	$cod_scr   = data_cod_scrap($d_pr['nom_pc'], $codigo_scrap);
	$folios[$i] = $cod_scr['fin']; $campo[$i] = 'financiero'; $i++;
	$folios[$i] = $cod_scr['rc']; $campo[$i] = 'reason_code'; $i++;

	//Si aplica la orden OI Especial o se toma de códigos de scrap
	  $s_oi = "select orden_interna from oi_especial where (id_division='$d_pr[id_d]' or id_division='na') and (id_segmento='$d_pr[id_s]' or ";
      $s_oi.= "id_segmento='na') and (id_pc='$d_pr[id_pc]' or id_pc='na') and (id_proyecto='$d_pr[id_pr]' or id_proyecto='todos') and ";
	  $s_oi.= "codigo_scrap='$codigo_scrap' and activo='1'";
	  $r_oi = mysql_query($s_oi);
	  if(mysql_num_rows($r_oi)>0) { 
		  $d_oi = mysql_fetch_array($r_oi);
		  $folios[$i] = $d_oi['orden_interna']; }
	  else { $folios[$i] = $cod_scr['oi']; } $campo[$i] = 'orden_interna'; $i++;

	$folios[$i] = $cod_scr['txs']; $campo[$i] = 'txs_sap'; $i++;
	$folios[$i] = $cod_scr['mov']; $campo[$i] = 'mov_sap'; $i++;
	$folios[$i] = $id_supervisor; $campo[$i] = 'id_supervisor'; $i++;
	$folios[$i] = $supervisor; $campo[$i] = 'supervisor'; $i++;
	$folios[$i] = $operador; $campo[$i] = 'operador'; $i++;
	$folios[$i] = $no_personal; $campo[$i] = 'no_personal'; $i++;		
	if($info_1=='') { $info_1 = 'NA'; }
	$folios[$i] = $info_1; $campo[$i] = 'info_1'; $i++;
	$folios[$i] = $info_2; $campo[$i] = 'info_2'; $i++;
	$folios[$i] = $o_mantto; $campo[$i] = 'o_mantto'; $i++;	
	//Revisar nuevamente los autorizadores que aplican por si estos cambiaron para el adjunto
	if(aplica_lo_loa($d_pr['id_p'],$codigo_scrap,$d_pr['id_d'],$d_pr['id_pc'],$area,$d_pr['id_pr'])=='SI') {
		$folios[$i] = $nombre; $campo[$i] = 'archivo'; $i++; }
	else { 
		$s_2  = "select * from configuracion where variable='ruta_evidencias'";
		$r_2  = mysql_query($s_2);
		$d_2  = mysql_fetch_array($r_2);
		$data = glob($d_2['valor']."evidencia_".$folio.".*");
		if(count($data)>0) {
			$pext = getFileExtension($data['0']); 
			unlink($d_2['valor']."evidencia_".$folio.".".$pext); }		
			$folios[$i] = ''; $campo[$i] = 'archivo'; $i++; }
	$comentario = str_replace("/","",$comentario);					
	$folios[$i] = htmlentities($comentario,ENT_QUOTES,"UTF-8"); $campo[$i] = 'comentario'; $i++;
	$accion_correctiva = str_replace("/","",$accion_correctiva);		
	$folios[$i] = htmlentities($accion_correctiva,ENT_QUOTES,"UTF-8"); $campo[$i] = 'accion_correctiva'; $i++;
	$folios[$i] = $vendor; $campo[$i] = 'vendor'; $i++;
	$folios[$i] = $v_nombre; $campo[$i] = 'v_nombre'; $i++;

	$s_1 = "update scrap_folios set ";
	for($i=3;$i<count($folios);$i++) {
			$s_1 = $s_1.$campo[$i]."='".$folios[$i]."',"; }
	$s_1 = $s_1." carga_masiva='1', status='0', activo='1', editada='2' where no_folio='$folios[2]'"; 
	$r_1 = mysql_query($s_1);
	/*LOG SISTEMA*/log_sistema("scrap_folios","editar",$folio,$s_1);

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
		/*LOG SISTEMA*/log_sistema("scrap_codigos","editar",$folio,$s_1); }

	//Reasigno los autorizadores que aplican sólo si cambió el código de scrap
	if($codigo_scrap!=$cod) {
	$s_a = "delete from autorizaciones where no_folio='$folio' and depto!='inv'";
	$r_a = mysql_query($s_a); 
	$s_2 = "select * from autorizadores where id_emp='$_SESSION[IDEMP]' and tipo='inv'";
	$r_2 = mysql_query($s_2);
	$s_t = "select sum(total) from scrap_partes where no_folio='$folio' group by no_folio";
	$r_t = mysql_query($s_t);
	$d_t = mysql_fetch_array($r_t);
	
	if(mysql_num_rows($r_2)>0 && $codigo_scrap=='035-1') { 
		//$s_1 = "insert into autorizaciones values('', '$folio', 'inv', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', '$fecha')";
		//$r_1 = mysql_query($s_1); 
		/*LOG SISTEMA*/log_sistema("autorizaciones","editar",$folio,$s_1); 
		$s_1 = "insert into aut_bitacora values('', '$folio', 'inv', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', '$fecha', '$hora', ";
		$s_1.= "'APROBACIÓN AUTOMÁTICA POR CARGA MASIVA', '')";
		$r_1 = mysql_query($s_1);
		/*LOG SISTEMA*/log_sistema("aut_bitacora","editar",$folio,$s_1); 
		$s_1 = "update scrap_folios set status='1' where no_folio='$folio'";
		$r_1 = mysql_query($s_1); }
	else { 	
		autorizaciones($folio,$d_pr['id_p'],$codigo_scrap,$d_pr['id_pr'],$d_t['total']); }
	}	

	//Inserto el registro de los 5 porqués
	$porque1 = htmlentities(str_replace("/","",$porque1),ENT_QUOTES,"UTF-8");
	$porque2 = htmlentities(str_replace("/","",$porque2),ENT_QUOTES,"UTF-8");
	$porque3 = htmlentities(str_replace("/","",$porque3),ENT_QUOTES,"UTF-8");
	$porque4 = htmlentities(str_replace("/","",$porque4),ENT_QUOTES,"UTF-8");
	$porque5 = htmlentities(str_replace("/","",$porque5),ENT_QUOTES,"UTF-8");	
	$s_2 = "delete from scrap_porques where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	$s_2 = "insert into scrap_porques values('','$folio','$porque1','$porque2','$porque3','$porque4','$porque5')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap_porques","editar",$folio,$s_2);	
	
	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '5', ";
	$s_2.= "'$fecha', '$hora', '', '')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("aut_bitacora","editar",$folio,$s_2); 
	
	$s_ = "delete from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);	
	$s_ = "delete from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);		
	$s_ = "delete from scrap_codigos_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);			
	if($from=='rechazado') { 
		enviar_aviso_autorizador($folio,$emp); }

	//Validar que la boleta se haya guardado correctamente
	$s_2 = "select * from scrap_folios where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en scrap_folios."; $error++; }
	$s_2 = "select * from scrap_partes where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en scrap_partes."; $error++; }	
	$s_2 = "select * from aut_bitacora where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en aut_bitacora."; $error++; }
	$s_2 = "select * from autorizaciones where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) {$movimiento = "La boleta no se guardo en autorizaciones."; $error++; }

	if($error>0) { 
		$s_ = "update configuracion set valor='SI' where variable='bloqueado'";
		$r_ = mysql_query($s_);
		echo"<script>alert('Error en la edición de la boleta!');</script>";
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$s_);
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$movimiento); }
	else { 	
		echo"<script>alert('Registro Almacenado');</script>"; }
} 


function insert_csv($apd,$proyecto,$codigo_scrap) {
	$s_ = "select * from configuracion where variable='ruta_capturas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."partes_UID".$_SESSION["IDEMP"].".csv", "r");
	$insertar = 0;
	$apd_nom  = get_dato("nombre",$apd,"apd"); 

	$s_1 = "CREATE OR REPLACE VIEW vw_padre_".$_SESSION["IDEMP"]." AS SELECT * from partes_padre where activo!='2' and apd='$apd_nom'";
	$r_1 = mysql_query($s_1);
	
	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		$campos = split (",", $buffer);	$error = '';	

		if($campos['0']!='' && $campos['0']!='no_parte') {
			
			//Validar que el número de parte exista
			$s_1 = "Select * from numeros_parte where nombre = '".trim($campos[0])."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$tipos 			= get_tipo(trim($campos[0])); //Obtener los tipos					
				//Si el tipo es diferente de FERT y el código de scrap es 035-1 no puede continuar
				if($codigo_scrap=='035-1' && $tipos['tipo']!='FERT') { 
					$error.= "No puede insertar ROH/HALB a código 035-1. "; 
				}
				$field['num_p'] = trim(strtoupper($campos[0])); //Número de parte
				$field['tipo']  = $tipos['tipo']; //Tipo
				$field['subt']  = $tipos['subt']; //Subtipo
				$field['desc']  = $tipos['desc']; //Descripción
				$field['cost']  = $tipos['costo']; //Costo
				$field['cant']  = trim($campos['1']); //Cantidad
				$field['total'] = $tipos['costo']*$field['cant']; //Total
				$field['batch'] = trim($campos['2']); //Batch ID
				$field['serie'] = trim(rtrim($campos['3'],",")); //No.Serie
				$field['ubic']  = trim($campos['4']); //Ubicación
			} else { $insertar++; $error .= "El número de parte no existe: $campos[0]"; }	
			
			//Validar que el número de parte padre corresponda
			$field['papa'] = trim(strtoupper($campos[5])); //Parte Padre
			//if($field['tipo']!="FERT" || $field['tipo']!="KMAT" && $nom!='WHSE' && $nom!='WHS2') {
			if($field['tipo']!="FERT" && $field['tipo']!="KMAT" && $nom!='WHSE' && $nom!='WHS2' && $nom!='AEES'){
   		 		$s_2 = "select * from vw_padre_".$_SESSION["IDEMP"]." where material='$field[num_p]' and type='$field[tipo]' ";
				$s_2.= "and padre='$field[papa]'";
		 		$r_2 = mysql_query($s_2); $i=0;
				if(mysql_num_rows($r_2)<=0) { 
					$insertar++; $error .= "El número de parte padre no corresponde: $field[papa] - $field[num_p]"; }
			} else { 			
				if($field['papa']!=$field['num_p']) { 
					$insertar++; $error .= "El número de parte padre no corresponde: $field[papa] - $field[num_p]"; }
			}
			//BUSCO QUE EXISTA EL BATCH ID
			$s_b = "select * from batch_id where batch_id='$field[batch]' and batch_id.activo='1'";
			$r_b = mysql_query($s_b);
			if(mysql_num_rows($r_b)>0){
				$s_2 = "select * from batch_ids, batch_id where batch_id.batch_id='$field[batch]' and batch_id.activo='1' ";
				$s_2.= "and batch_ids.batch_id = batch_id.id and parte='$field[num_p]'";
				$r_2 = mysql_query($s_2); $i=0;
				if(mysql_num_rows($r_2)<=0) { 
					$insertar++; $error .= "El batch id no corresponde al número de parte: $field[num_p] - $field[batch]"; }
			} else {
				$insertar++; $error .= "No existe el batch id: $field[batch]";
			}
				
			if($insertar<=0) {
				$query = "INSERT into scrap_partes_tmp values('', '$field[papa]', '$field[num_p]', '$field[tipo]', '$field[subt]', ";
				$query.= "'$field[desc]', '$field[cant]', '$field[cost]', '$field[total]', '$field[batch]', '$field[serie]', ";
				$query.= "'$field[ubic]','$_SESSION[IDEMP]')"; 
				mysql_query($query); $ins++; }
			else { 
				echo "<script>alert('$error. No se puede continuar con la carga.');</script>"; 
				fclose ($fd); unlink($r_server.$alias);	exit; }				
			$insertar=0;			
		}			
	}     
	fclose ($fd); 
	unlink($r_server."partes_UID".$_SESSION["IDEMP"].".csv");
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>