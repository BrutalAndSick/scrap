<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   include("../menu_capturista.php"); 
   include("../conexion_db.php");
   include("../generales.php");
   conectame_db('scrap_gdl'); ?><strong></strong>
   
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
function showMenu(which,tf) {
	if (tf==true) {
	which.style.display="block"; }
	if (tf==false) {
	which.style.display="none";  }
}

function validar_cantidad(evt,decimales){
if(decimales==0) { 
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57)); }
if(decimales==1) { 
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key == 46); }	
}

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

function letras_coma(evt){ 
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key == 44);
}

function apd_sel(campo) {
	form1.action = '?op=apd_sel&campo='+campo;
	form1.submit();	
}
</script>
</head>
<?php include('funciones.php');
   include('validaciones.php');
   if(!$campo) $campo='turno'; ?>
   
<body topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" background="../imagenes/fondo.png" style="background-repeat:repeat-x;" bgcolor="#F1F1F1" onLoad="poner_foco('<?php echo $campo;?>');">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" class="aviso">
		<div style="margin-right:30px; margin-top:10px;"><?php echo $_SESSION['USER']."  [ ".$_SESSION['TYPE']." ]";?></div></td>
	<td rowspan="2"><img src="../imagenes/app_titulo.png" width="285" height="104" /></td>
  </tr>
  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_capturai'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_capturai',''); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td background="../imagenes/borde_izq_tabla.png">&nbsp;</td>
    <td>&nbsp;
	<!--Todo el contenido de cada página--->
	<?php menu_scrap(); switch($op) {
		case "nuevo"	:	nuevo($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
							$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,batch_id,$supervisor,$operador,$no_personal,
							$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,$accion_correctiva,
							$archivo,$archivo_name);
							break;
		case "apd_sel"	:	$nom = get_dato("nombre",$apd,"apd"); 
							if($nom!='WHSE' && $nom!='WHS2' && $nom!='AEES') {
							$s_1 = "CREATE OR REPLACE VIEW vw_padre_".$_SESSION["IDEMP"]." AS SELECT * from partes_padre where ";
							$s_1.= "activo!='2' and apd like '$nom'"; 
							$r_1 = mysql_query($s_1); }
							nuevo($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
							$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,batch_id,$supervisor,$operador,$no_personal,
							$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,$accion_correctiva,
							$archivo,$archivo_name); break;
		case "guardar"  :	//VALIDAR QUE EL NÚMERO DE PARTE EXISTA EN EL APD SI EL MATERIAL NO ES FERT NI KMAT, O PERTENECE A ALGÚN APD ESPECIAL
							$tipos_ = get_tipo($parte); 
							$nom    = get_dato("nombre",$apd,"apd"); 
							if($tipos_['tipo']=='HALB' && $parte==$padre) { $validar = 'no'; } else { $validar = 'si'; }							
							if($tipos_['tipo']!='FERT' && $tipos_['tipo']!='KMAT' && $nom!='WHSE' && $nom!='WHS2' && $nom!='AEES' && $validar=='si') { 
								$s_1 = "select padre from vw_padre_".$_SESSION["IDEMP"]." where material='$parte' and type='$tipos_[tipo]'";
								$r_1 = mysql_query($s_1); 
								if(mysql_num_rows($r_1)<=0) { 
									echo "<script>alert('El número de parte no existe para el APD seleccionado.');</script>"; 
								nuevo($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,
								$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,batch_id,$supervisor,$operador,
								$no_personal,$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,
								$accion_correctiva,$archivo,$archivo_name); }
							else { 	
								guardar($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,
								$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,$batch_id,$supervisor,$operador,
								$no_personal,$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,
								$accion_correctiva,$archivo,$archivo_name); } } else { 
								guardar($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,
								$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,$batch_id,$supervisor,$operador,
								$no_personal,$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,
								$accion_correctiva,$archivo,$archivo_name); }								
							break;		
	} ?>			
	<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");

	
	
	
function menu_scrap() { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo" align="left">CAPTURA INDIVIDUAL DE SCRAP</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
	<span title="header=[&nbsp;&nbsp;Captura de Scrap] body=[Complete todos los campos de cada parte del formulario. Los cuadros de colores indican lo siguiente:<table align='center' border='0' cellspacing='2'><tr bgcolor='#FFFFFF'><td class='obligatorio' width='60'>&nbsp;ROJO</td><td width='100'>&nbsp;Obligatorio</td></tr><tr bgcolor='#FFFFFF'><td class='automatico'>&nbsp;VERDE</td><td>&nbsp;Automático</td></tr><tr bgcolor='#FFFFFF'><td class='opcional'>&nbsp;AZUL</td><td>&nbsp;Opcional</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>
</tr>
</table>
<hr><br>
<?php } 


function nuevo($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,$batch_id,$supervisor,$operador,$no_personal,$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,$accion_correctiva,$archivo,$archivo_name) { 
	if(!$area_2) 		 { $area_2 = $area; }
	if(!$estacion_2) 	 { $estacion_2 = $estacion; }
	if(!$linea_2)		 { $linea_2 = $linea; }

	$s_2 = "select * from configuracion where variable='ruta_cargas'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	if(file_exists($d_2['valor']."partes_UID".$_SESSION["IDEMP"].".csv")) { 
		unlink($d_2['valor']."partes_UID".$_SESSION["IDEMP"].".csv"); }

	$s_2  = "select * from configuracion where variable='ruta_evidencias'";
	$r_2  = mysql_query($s_2);
	$d_2  = mysql_fetch_array($r_2);
	$data = glob($d_2['valor']."evidencia_UID".$_SESSION["IDEMP"].".*");
	if(count($data)>0) {
		$pext = getFileExtension($data['0']); 
		unlink($d_2['valor']."evidencia_UID".$_SESSION["IDEMP"].".".$pext); }

	$s_ = "delete from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	$s_ = "delete from scrap_codigos_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);	
	$s_ = "delete from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); ?>
<form action="?op=nuevo" method="post" name="form1" enctype="multipart/form-data">
<table align="center" class="texto" cellpadding="2" cellspacing="0" border="0"><tr></tr>
</table>
<br>
<table align="center" class="texto" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="20" align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td width="80">&nbsp;Turno</td>
	<td width="130">	
		<select name="turno" style="width:95px;" class="texto" tabindex="1">
		<option value=""></option>
		<option value="1" <?php if($turno=='1'){?> selected="selected"<?php } ?>>1</option>
		<option value="2" <?php if($turno=='2'){?> selected="selected"<?php } ?>>2</option>
		<option value="3" <?php if($turno=='3'){?> selected="selected"<?php } ?>>3</option>
		<option value="4" <?php if($turno=='4'){?> selected="selected"<?php } ?>>4</option>
		<option value="5" <?php if($turno=='5'){?> selected="selected"<?php } ?>>5</option>
		<option value="6" <?php if($turno=='6'){?> selected="selected"<?php } ?>>6</option>
		<option value="7" <?php if($turno=='7'){?> selected="selected"<?php } ?>>7</option>
		</select></td>
	<td rowspan="8" background="../imagenes/separador_1.png" width="10">&nbsp;</td>
	<td width="20" align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td width="85">&nbsp;Fecha</td>
	<td width="120"><b><?php echo date("d-m-Y");?></b></td>
	<td width="20" align="center">&nbsp;</td>
	<td width="70">&nbsp;</td>
	<td width="80"><b><?php //echo get_folio();?></b></td>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Proyecto&nbsp;&nbsp;<a href="popup_series.php?op=proyectos" target="_blank" onClick="javascript:window.open(this.href, this.target,'height=600,width=800,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes'); return false;"><img src="../imagenes/information.png" border="0"></a></td>
	<td>
	<?php 
	$s_1 = "select * from capturistas where id_emp='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	if($d_1['id_division']!='%') { 
	   $s_1 = "select proyectos.*, divisiones.nombre as division from plantas, divisiones, segmentos, profit_center, proyectos, capturistas where ";
	   $s_1.= "proyectos.activo='1' and plantas.activo='1' and divisiones.activo='1' and segmentos.activo='1' and ";
	   $s_1.= "profit_center.activo='1' and proyectos.id_planta = plantas.id and proyectos.id_division = divisiones.id and ";
	   $s_1.= "proyectos.id_segmento = segmentos.id and proyectos.id_pc = profit_center.id and capturistas.id_division = ";
	   $s_1.= "proyectos.id_division and capturistas.id_emp='$_SESSION[IDEMP]' order by proyectos.nombre"; }
	 else { 
	   $s_1 = "select proyectos.*, divisiones.nombre as division from plantas, divisiones, segmentos, profit_center, proyectos where proyectos.activo='1' and ";
	   $s_1.= "plantas.activo='1' and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and ";
	   $s_1.= "proyectos.id_planta = plantas.id and proyectos.id_division = divisiones.id and proyectos.id_segmento = segmentos.id ";
	   $s_1.= "and proyectos.id_pc = profit_center.id order by proyectos.nombre"; }     
	   $r_1 = mysql_query($s_1);?>
	<select name="proyecto" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','proyecto');" tabindex="2">
	   <option value=""></option>
	   <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_1['nombre']." (".$d_1['division'].")";?></option>
	   <?php } ?>
	</select>
	<?php $data_proy = get_datos_proyecto($proyecto); ?></td>	
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;No. de Parte</td>
	<td><?php $parte = strtoupper($parte); ?>
    <input type="text" name="parte" class="texto" value="<?php echo $parte;?>" tabindex="4" onBlur="cambio_foco('nuevo','cantidad');" size="20" />
	</td>
	<td align="center"><?php $tipos = get_tipo($parte); ?><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Tipo</td>
	<td>
		<input type="text" name="tipo" value="<?php echo $tipos['tipo'];?>" readonly="readonly" size="3" class="texto" />
		<input type="text" name="subt" value="<?php echo $tipos['subt'];?>" readonly="readonly" size="8" class="texto" />
	</td>
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Planta</td>
	<td><input name="plant" type="text" readonly="readonly" value="<?php echo $data_proy['nom_p'];?>" class="texto" size="32" /></td>
	<td align="center" rowspan="2"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td rowspan="2">&nbsp;Descripción</td>
	<td colspan="4" rowspan="2">
	<textarea name="descripcion" cols="72" rows="2" class="texto" readonly="readonly"><?php echo $tipos['desc'];?></textarea></td>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Divisi&oacute;n</td>
	<td><input name="division" type="text" readonly="readonly" value="<?php echo $data_proy['nom_d'];?>" class="texto" size="32" /></td>	
</tr>	
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Segmento</td>
	<td>
    <input name="segmento" type="text" readonly="readonly" value="<?php echo $data_proy['nom_s'];?>" class="texto" size="32" /></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Cantidad</td>
	<td><input type="text" name="cantidad" class="texto" size="25" value="<?php echo $cantidad;?>" tabindex="5" onKeyPress="return validar_cantidad(event,'<?php echo $tipos['decimales'];?>');" maxlength="11" /></td>	
	<?php $batchs = get_batch($parte); ?>
	<td align="center">
	<?php if(count($batchs)<='1'){?><img src="../imagenes/cuadro_verde.gif" /><?php } else { ?>
    <img src="../imagenes/cuadro_rojo.gif" /><?php } ?></td>
	<td>&nbsp;Batch ID</td>	
	<td><?php if(count($batchs)=='0') { ?>
			<input type="text" class="texto" size="18" name="batch_id" value="NA" tabindex="6" /><?php } ?>
		<?php if(count($batchs)=='1') { ?>
			<input type="text" class="texto" size="18" name="batch_id" value="<?php echo $batchs['0'];?>" tabindex="6" /><?php } ?>
		<?php if(count($batchs)>'1') { ?>
	<select name="batch_id" class="texto" style="width:120px;" tabindex="6">
		<option value=""></option>
		<?php for($i=0;$i<count($batchs);$i++) { ?>
		<option value="<?php echo $batchs[$i];?>" <?php if($batchs[$i]==$batch){?> selected="selected"<?php }?>>
		<?php echo $batchs[$i];?></option>
		<?php } ?>
	</select><?php } ?></td>
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Profit Center</td>
	<td><input name="prce" type="text" readonly="readonly" value="<?php echo $data_proy['nom_pc'];?>" class="texto" size="32" /></td>
	<?php $cod_scr = data_cod_scrap($data_proy['nom_pc'], $codigo_scrap); 
	      $apd_nom = get_dato("nombre",$apd,"apd"); 

	if($cod_scr['txs']=='ZSCR' && $parte!='' && $apd_nom!='' && $tipos['tipo']!='') { 
	    $s_2 = "select padre from vw_padre_".$_SESSION["IDEMP"]." where material='$parte' and type='$tipos[tipo]' group by padre order by padre";
		$r_2 = mysql_query($s_2); 
	    if($tipos['tipo']=='HALB') {
			$padres['0']  = $parte;
			$mostrar['0'] = $parte; $i=1; }
		else { $i=0; }	
		while($d_2=mysql_fetch_array($r_2)) {
			   $padres[$i]  = $d_2['padre'];
			   $mostrar[$i] = $d_2['padre']; $i++; }  

		if(count($padres)<=0 && ($tipos['tipo']=="FERT" || $tipos['tipo']=="KMAT")) {
			$padres['0']  = $parte;   
			$mostrar['0'] = $parte;  } ?>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Parte Padre</td>
	<td colspan="4"><select name="padre" id="padre" class="texto" style="width:385px;" tabindex="7">	
		 <option value=""></option>
		 <?php for($i=0;$i<count($padres);$i++) { ?>
			 <option value="<?php echo $padres[$i];?>" <?php if($padre==$padres[$i]){?> selected="selected" <?php } ?>>
			 <?php echo $mostrar[$i];?></option>
		 <?php } ?>
	</select></td>	
	<?php } else { ?>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" />
	</td><td>&nbsp;Parte Padre</td>
	<td><input name="padre" type="text" readonly="readonly" value="NA" class="texto" size="25" /></td><?php } ?>	
</tr>	
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;APD</td>
	<td><select name="apd" style="width:190px;" class="texto" tabindex="3" onChange="apd_sel('apd');">
		<option value=""></option>
		<?php $s_3 = "select * from apd where id_division='$data_proy[id_d]' and id_segmento='$data_proy[id_s]' and activo='1' ";
		   $s_3.= "order by nombre";
	  	   $r_3 = mysql_query($s_3);
	   	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($apd==$d_3['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Supervisor</td>
	<td colspan="4">
	<?php $s_3 = "select empleados.id, empleados.nombre, empleados.apellidos from supervisores, empleados where empleados.id = ";
		  $s_3.= "supervisores.id_emp and supervisores.id_division='$data_proy[id_d]' and empleados.autorizador='prod' ";
		  $s_3.= "order by apellidos, nombre"; ?>
    <select name="supervisor" style="width:385px;" class="texto" tabindex="8">
		<option value=""></option>
	  	<?php $r_3 = mysql_query($s_3);
	   	      while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($supervisor==$d_3['id']){?> selected="selected"<?php } ?>>
	   <?php if($d_3['nombre']!='' || $d_3['apellidos']!='') { echo trim($d_3['nombre']." ".$d_3['apellidos']); } ?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Operador</td>
    <td><input type="text" size="25" class="texto" name="operador" value="<?php echo $operador;?>" tabindex="9" onKeyPress="return  soloLetras(evt);" /></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;No.Personal</td>
	<td><input type="text" name="no_personal" class="texto" size="18" value="<?php echo $no_personal;?>" tabindex="10" onKeyPress="return solo_numeros(event);" /></td>	
</tr>
<tr>
	<td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td>
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;&Aacute;rea</td>
	<td><select name="area" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','area');" tabindex="11">
		<option value=""></option>
		<?php $s_4 = "select * from areas where activo='1' order by nombre";
	  	   $r_4 = mysql_query($s_4);
	   	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($area==$d_4['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td rowspan="7" background="../imagenes/separador_1.png">&nbsp;</td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;No.Serial</td>
	<td colspan="4" rowspan="3" valign="top"><textarea name="serial_unidad" cols="72" class="texto" rows="3" tabindex="17" onKeyPress="return letras_coma(event);"><?php echo $serial_unidad;?></textarea><br><span class="gris">(Separe los números de serie por comas)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="popup_series.php?op=sin_guardar" target="_blank" onClick="javascript:window.open(this.href, this.target,'height=200,width=400,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes'); return false;">Ver Listado</a></td>	
</tr>	
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Tecnología</td>
	<td><?php $s_5 = "select estaciones.* from estaciones, est_proyecto where estaciones.activo='1' and estaciones.id_area='$area' ";
		      $s_5.= "and est_proyecto.id_tecnologia = estaciones.id and est_proyecto.id_proyecto = '$proyecto' order by nombre";
		      $r_5 = mysql_query($s_5); ?>	
	<select name="estacion" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','estacion');" tabindex="12">
		<option value=""></option>
		<?php while($d_5=mysql_fetch_array($r_5)) { ?>
	    <option value="<?php echo $d_5['id'];?>" <?php if($estacion==$d_5['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_5['nombre'];?></option>
	    <?php } ?>
	</select></td>
	<td align="center" colspan="2">&nbsp;</td>		
</tr>
<tr>	
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Línea</td>
	<td><?php $s_6 = "select lineas.* from lineas, lineas_proy where lineas.activo='1' and lineas.id_area='$area' and ";
		      $s_6.= "lineas.id_estacion='$estacion' and lineas_proy.id_linea = lineas.id and lineas_proy.id_proyecto = '$proyecto' ";
			  $s_6.= "order by nombre"; 
	   	      $r_6 = mysql_query($s_6); ?>
    <select name="linea" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','linea');" tabindex="13">
		<option value=""></option>
	   	<?php while($d_6=mysql_fetch_array($r_6)) { ?>
	    <option value="<?php echo $d_6['id'];?>" <?php if($linea==$d_6['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_6['nombre'];?></option>
	    <?php } ?>
	</select></td>	
	<td align="center" colspan="2">&nbsp;</td>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Defecto</td>
		<?php $s_7 = "select defectos.* from defectos, def_proyecto where defectos.activo='1' and defectos.id_area='$area' and ";
		      $s_7.= "defectos.id_estacion='$estacion' and def_proyecto.id_defecto = defectos.id and def_proyecto.id_proyecto = ";
			  $s_7.= "'$proyecto' order by nombre"; ?>
	<td><select name="defecto" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','defecto');" tabindex="14">
	   <option value=""></option>
		   <?php $r_7 = mysql_query($s_7);
	   	   while($d_7 = mysql_fetch_array($r_7)) { ?>
	   <option value="<?php echo $d_7['id'];?>" <?php if($defecto==$d_7['id']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_7['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_azul.gif" /></td>
	<td>&nbsp;O.Mantto</td>
	<td colspan="4"><input type="text" name="o_mantto" value="<?php echo $o_mantto;?>" size="71" class="texto" tabindex="18" onKeyPress="return solo_numeros(event);" /></td>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Relacionado a</td>
	<?php $s_8 = "select causas.id, causas.nombre from causas, defecto_causa where causas.activo='1' and ".
			  "defecto_causa.id_defecto='$defecto' and defecto_causa.id_causa =  causas.id order by causas.nombre";?>
    <td><select name="causa" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','causa');" tabindex="15">
		<option value=""></option>
	<?php 
	   $r_8 = mysql_query($s_8);
	   while($d_8=mysql_fetch_array($r_8)) { ?>
	   <option value="<?php echo $d_8['id'];?>" <?php if($causa==$d_8['id']){?> selected="selected"<?php } ?>><?php echo $d_8['nombre'];?></option>
	   <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Ubicación</td>
	<td><select name="ubicacion" style="width:140px;" class="texto" tabindex="19">
		<option value=""></option>
	<?php $s_9 = "select * from proy_ubicacion where id_proyecto='$proyecto' order by ubicacion";
	   $r_9 = mysql_query($s_9);
	   while($d_9=mysql_fetch_array($r_9)) { ?>
	   <option value="<?php echo $d_9['ubicacion'];?>" <?php if($ubicacion==$d_9['ubicacion']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_9['ubicacion'];?></option><?php } ?>
	</select></td>	
	<?php if($cod_scr['info']=="SI") { ?>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>	
	<td>&nbsp;Info.Obligatoria</td>
	<td colspan="2"><select name="info_1" class="texto" style="width:50px;" tabindex="20">
		<option value=""></option>
		<option value="QN" <?php if($info_1=="QN"){?> selected="selected"<?php } ?>>QN</option>
		<option value="VUG" <?php if($info_1=="VUG"){?> selected="selected"<?php } ?>>VUG</option>
		<option value="VUT" <?php if($info_1=="VUT"){?> selected="selected"<?php } ?>>VUT</option>
		</select><input type="text" name="info_2" value="<?php echo $info_2;?>" size="8" class="texto" tabindex="21" onKeyPress="return solo_numeros(event);" /></td><?php } ?>	
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Cod. SCRAP</td>
    <td><select name="codigo_scrap" style="width:190px;" class="texto" onChange="apd_sel('codigo_scrap');" tabindex="16" id="codigo_scrap">
		<option value=""></option>
		<?php $s_10 = "select codigo_scrap.id, codigo_scrap.codigo, codigo_scrap.descripcion from codigo_scrap, causa_codigo where ";
		   $s_10.= "codigo_scrap.activo='1' and causa_codigo.id_causa='$causa' and causa_codigo.id_codigo =  codigo_scrap.id order ";
		   $s_10.= "by codigo_scrap.codigo";
	  	   $r_10 = mysql_query($s_10);
	   while($d_10=mysql_fetch_array($r_10)) { ?>
	   <option value="<?php echo $d_10['codigo'];?>" <?php if($codigo_scrap==$d_10['codigo']){?> selected="selected"<?php } ?>>
	   	<?php echo $d_10['codigo']." (".$d_10['descripcion'].")";?></option><?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Orden Interna</td>
	<td><input type="text" class="texto" size="22" value="<?php echo $cod_scr['oi'];?>" readonly="readonly" name="orden_interna" />
    </td>	
	<td align="center"><img src="../imagenes/cuadro_verde.gif" /></td>
	<td>&nbsp;Reason Code</td>
	<td><input type="text" class="texto" size="18" value="<?php echo $cod_scr['rc'];?>" readonly="readonly" name="reason_code" /></td>	
</tr>	
<tr>
<?php validar_autorizadores($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); ?>
</tr><tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr>
<?php //-----------------------------------------------------------------------------------
	  if($cod_scr['fin']=='1') { ?>
<tr>
	<td colspan="10" class="naranja" align="left">
    Llene los siguientes campos para la causa original del código financiero seleccionado:</td></tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;&Aacute;rea</td>
	<td><select name="area_2" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','area_2');" tabindex="24">
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
	<td colspan="4">
    <select name="defecto_2" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','defecto_2');" tabindex="27">
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
	<td>&nbsp;Línea</td>
	<td><select name="linea_2" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','linea_2');" tabindex="25">
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
	<td colspan="4">
    <select name="causa_2" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','causa_2');" tabindex="28">
		<option value=""></option>
	<?php $s_8 = "select causas.id, causas.nombre from causas, defecto_causa where causas.activo='1' and ".
			  "defecto_causa.id_defecto='$defecto_2' and defecto_causa.id_causa =  causas.id order by causas.nombre";
	   $r_8 = mysql_query($s_8);
	   while($d_8=mysql_fetch_array($r_8)) { ?>
	   <option value="<?php echo $d_8['id'];?>" <?php if($causa_2==$d_8['id']){?> selected="selected"<?php } ?>>
	   <?php echo $d_8['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Tecnología</td>
	<td>
	<?php $s_5 = "select estaciones.* from estaciones, est_proyecto where estaciones.activo='1' and estaciones.id_area='$area_2' ";
	      $s_5.= "and est_proyecto.id_tecnologia = estaciones.id and est_proyecto.id_proyecto = '$proyecto' order by nombre";
	      $r_5 = mysql_query($s_5); ?>	
	<select name="estacion_2" style="width:190px;" class="texto" onChange="cambio_foco('nuevo','estacion_2');" tabindex="26">
		<option value=""></option>
		<?php while($d_5=mysql_fetch_array($r_5)) { ?>
	    <option value="<?php echo $d_5['id'];?>" <?php if($estacion_2==$d_5['id']){?> selected="selected"<?php } ?>>
		<?php echo $d_5['nombre'];?></option>
	    <?php } ?>
	</select></td>
	<td align="center"><img src="../imagenes/cuadro_rojo.gif" /></td>
	<td>&nbsp;Cod. SCRAP</td>
    <td colspan="4">
    <select name="codigo_scrap_2" style="width:190px;" class="texto" tabindex="29">
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
<?php } //-----------------------------------------------------------------------------------
	  $aplica = aplica_lo_loa($data_proy['id_p'],$codigo_scrap,$data_proy['id_d'],$data_proy['id_pc'],$area,$data_proy['id_pr']); 
      if($aplica=='SI') { ?>
<tr>
	<td colspan="10" class="naranja" align="left">
    Adjunte el archivo con evidencias para los departamentos LO  y LOA:</td></tr>
<tr>
	<td colspan="10" align="left">Archivo de evidencia:&nbsp;&nbsp;
    <input type="file" name="archivo" class="texto" size="50" tabindex="30" /></td>
</tr>
<tr><td colspan="10" background="../imagenes/separador_2.png">&nbsp;</td></tr><?php } ?>

<tr>
	<td colspan="10">
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="texto">
	<tr>
		<td align="center"><img src="../imagenes/cuadro_azul.gif" />&nbsp;Comentarios</td>
		<td width="10" rowspan="2">&nbsp;</td>
		<td align="center"><img src="../imagenes/cuadro_azul.gif" />&nbsp;Acci&oacute;n Correctiva</td>
	</tr>
	<tr>
		<td align="center">
			<textarea name="comentario" cols="70" rows="2" class="texto" tabindex="31"><?php echo $comentario;?></textarea></td>
		<td align="center">
			<textarea name="accion_correctiva" cols="70" rows="2" class="texto" tabindex="32"><?php echo $accion_correctiva;?></textarea></td>
	</tr></table></td>
</tr>	
</table><br><div align="center" class="naranja">&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if(is_bloqueado()=='SI') { 
		echo "El sistema está bloqueado y no podrá capturar.<br>Reporte el problema al administrador.<br><br>"; $dis = 'disabled'; } ?>
<input type="button" value="Guardar" onClick="validar_i(1,'<?php echo $cod_scr['info'];?>','<?php echo $cod_scr['txs'];?>','<?php echo $aplica;?>', '<?php echo $cod_scr['fin'];?>','<?php echo $tipos['decimales'];?>');" class="submit" tabindex="33" <?php echo $dis;?>>
</div>
</form><br><br><br>
<?php } 


function guardar($turno,$proyecto,$area,$area_2,$estacion,$estacion_2,$linea,$linea_2,$defecto,$defecto_2,$causa,$causa_2,$codigo_scrap,$codigo_scrap_2,$parte,$cantidad,$padre,$batch_id,$supervisor,$operador,$no_personal,$apd,$serial_unidad,$o_mantto,$docto_sap,$info_1,$info_2,$ubicacion,$comentario,$accion_correctiva,$archivo,$archivo_name) {

	$error   = 0;
    $fecha	 = date("Y-m-d");
	$hora	 = date("H:i:s");
	$anio	 = date("Y");
	list($anio,$mes,$dia) = split("-",$fecha);
    $semana  = date('W',mktime(0,0,0,$mes,$dia,$anio));
	$folio	 = get_folio(); $i=0;
	aumenta_folio();

//Validar que el folio no esté duplicado
$s_ = "select * from scrap_folios where no_folio='$folio'";
$r_ = mysql_query($s_);
if(mysql_num_rows($r_)>0) { $movimiento = "El folio esta duplicado."; $error++; }

if($archivo!='') { 
	$s_ = "select * from configuracion where variable='ruta_evidencias'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = "evidencia_".$folio.".".$pext;
	$nom_final = $r_server.$nombre;
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo de evidencias: $nom_final');</script>"; }
	}
}
	$folios[$i] = $_SESSION['IDEMP']; $i++;
	$folios[$i] = $_SESSION['NAME']; $i++;
	$folios[$i] = $folio; $i++;
	$folios[$i] = $fecha; $i++;
	$folios[$i] = $hora; $i++;
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
	$cod_scr    = data_cod_scrap($d_pr['nom_pc'], $codigo_scrap);
	$folios[$i] = $cod_scr['fin']; $i++;
	$folios[$i] = $cod_scr['rc']; $i++;
	$folios[$i] = $cod_scr['oi']; $i++;
	$folios[$i] = $cod_scr['txs']; $i++;
	$folios[$i] = $cod_scr['mov']; $i++;
	$folios[$i] = $supervisor; $i++;
	$folios[$i] = get_supervisor($supervisor); $i++;
		$operador = str_replace("/","",$operador);
	$folios[$i] = htmlentities($operador,ENT_QUOTES,"UTF-8"); $i++;
	$folios[$i] = $no_personal; $i++;		
	if($info_1=='') { $info_1 = 'NA'; }	
	$folios[$i] = $info_1; $i++;
	$folios[$i] = $info_2; $i++;
	$folios[$i] = $o_mantto; $i++;	
	$folios[$i] = $nombre; $i++;
		$comentario = str_replace("/","",$comentario);
	$folios[$i] = htmlentities($comentario,ENT_QUOTES,"UTF-8"); $i++;	
		$accion_correctiva = str_replace("/","",$accion_correctiva);
	$folios[$i] = htmlentities($accion_correctiva,ENT_QUOTES,"UTF-8");

	$s_1 = "insert into scrap_folios values ('',";
	for($i=0;$i<count($folios);$i++) {
			$s_1 = $s_1."'".$folios[$i]."',"; }
	$s_1 = $s_1."0,0,1,0)"; 
	$r_1 = mysql_query($s_1); $i=0;	
	/*LOG SISTEMA*/log_sistema("scrap_folios","nuevo",$folio,$s_1);

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
		/*LOG SISTEMA*/log_sistema("scrap_codigos","nuevo",$folio,$s_1); }
	
	$partes[$i] = $folio; $i++;
	$data_p     = get_tipo($parte); 
	$partes[$i] = $padre; $i++;
	$partes[$i] = $parte; $i++;
	$partes[$i] = $data_p['tipo']; $i++;
	$partes[$i] = $data_p['subt']; $i++;
	$partes[$i] = $data_p['desc']; $i++;
	$partes[$i] = $cantidad; $i++;
	$partes[$i] = $data_p['costo']; $i++;
	$partes[$i] = $data_p['costo']*$cantidad; $i++;
	$partes[$i] = $batch_id; $i++;
	$partes[$i] = trim(rtrim($serial_unidad,",")); $i++;
	$partes[$i] = $ubicacion;

	$s_1 = "insert into scrap_partes values ('',";
	for($i=0;$i<count($partes);$i++) {
			$s_1 = $s_1."'".$partes[$i]."',"; }
	$s_1 = substr($s_1,0,-1).",'0','0','0')";
	$r_1 = mysql_query($s_1); $i=0;	
	/*LOG SISTEMA*/log_sistema("scrap_partes","nuevo",$folio,$s_1);

	autorizaciones($folio,$d_pr['id_p'],$codigo_scrap,$d_pr['id_pr']);
	$ruta = "&turno=$turno&proyecto=$proyecto&area=$area&estacion=$estacion&linea=$linea&supervisor=$supervisor&operador=$operador";
	$ruta.= "&no_personal=$no_personal&apd=$apd"; 

	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '6', ";
	$s_2.= "'$fecha', '$hora', 'Creaci&oacute;n de la boleta')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("aut_bitacora","nuevo",$folio,$s_2);
	
	$s_3 = "DROP VIEW vw_padre_".$_SESSION["IDEMP"];
	$r_3 = mysql_query($s_3);

	//Validar que la boleta se haya guardado correctamente
	$s_2 = "select * from scrap_folios where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en scrap_folios."; $error++; }
	$s_2 = "select * from scrap_partes where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en scrap_partes"; $error++; }	
	if($cod_scr['fin']=='1') { 
		$s_2 = "select * from scrap_codigos where no_folio='$folio'";
		$r_2 = mysql_query($s_2);
		if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en scrap_codigos."; $error++; } }	
	$s_2 = "select * from aut_bitacora where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = ".La boleta no se guardo en aut_bitacora."; $error++; }	
	$s_2 = "select * from autorizaciones where no_folio='$folio'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $movimiento = "La boleta no se guardo en autorizaciones"; $error++; }	

	if($error>0) { 
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/exclamation.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=rojo><b>Error al guardar el folio:</strong><br><br>";
			echo"<span style='color:#FF6600; font-size:20px;'><b>$folio</b><br><br></tr>";
		echo"<tr><td align=center>";
			echo"<br><strong class=rojo><b>Contacte al administrador del sistema.</b><br><br>";	
		echo"</td></tr></table>";
		echo"<form method='post' action='?op=nuevo'>";
		echo "<div align=center><input type='submit' value='Regresar' class='submit'></div>";
		echo"</form>";	
		$s_ = "update configuracion set valor='SI' where variable='bloqueado'";
		$r_ = mysql_query($s_);
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$s_);
		/*LOG SISTEMA*/log_sistema("configuracion","error",$folio,$movimiento);
	} else { 	
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>Boleta almacenada con el folio:</strong><br><br>";
			echo"<span style='color:#FF6600; font-size:20px;'><b>$folio</b><br><br>";
		echo"</td></tr></table>";
		echo"<form method='post' action='?op=nuevo$ruta'>";
		echo "<div align=center><input type='submit' value='Continuar' class='submit'></div>";
		echo"</form>";
	} 	
} 


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>