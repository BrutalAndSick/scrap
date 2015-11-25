<?php include("../header.php"); ?>

<script>
function guardar() {
	form1.action='?op=guardar';
	form1.submit();
}

function validar_1() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='sql') {
		alert('Utilice solamente archivos .sql');
		form1.archivo.focus(); return; }				
	form1.submit();	
}

function validar_2() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='csv') {
		alert('Utilice solamente archivos .csv');
		form1.archivo.focus(); return; }				
	form1.submit();	
}

function validar_t() {
	if(form1.tabla.value=='') {
		alert('Es necesario seleccionar el nombre de la tabla');
		form1.archivo.focus(); return; }			
	form1.submit();			
}

function validar_auto(tipo,subtipo){
	var faltan=0;
	if(tipo=="proyectos"){
		if(subtipo=="formulario"){
			if(form1.proyecto.value.replace(/^\s*|\s*$/g,"")==''){
				form1.proyecto.style.backgroundColor = '#F78181'; 
				form1.proyecto.value='';
				faltan++; }
			if(form1.valores.value.replace(/^\s*|\s*$/g,"")==''){
				form1.valores.style.backgroundColor = '#F78181'; 
				form1.valores.value='';
				faltan++; }	
		}
	}
	if(faltan>0) { return; }
	else { 
		form1.action = '?op=guardar_auto&f_tipo='+tipo+'&subtipo='+subtipo;
		form1.submit(); 
	}
} 

function nuevo(){
	form1.action = '?op=nuevo_auto';
	form1.submit();	
}

function regresar(proyecto){
	form1.action = '?op=auto&f_tipo=proyectos&f_proyecto='+proyecto;
	form1.submit();
}

function borrar(proyecto){
	form1.action = '?op=borrar_auto&id_auto='+proyecto;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu(''); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('',''); ?></td>
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
	<?php menu_interno();
	switch($op) {
		case "mantenimiento"	:  	mantenimiento(); break;
		case "guardar"			:	guardar($id_,$valor,$variable); parametros($orden,''); break;
		case "parametros"		:	parametros($orden,$id_); break;

		case "borrar_auto"		:	borrar_auto($f_proyecto,$id_auto); auto("proyectos",$f_proyecto); break;
		case "nuevo_auto"		:	nuevo_auto($f_tipo,$f_proyecto,$proyecto); break;
		case "guardar_auto"		:	guardar_auto($f_tipo,$valores,$proyecto,$subtipo);  break;
		case "auto"				:	auto($f_tipo,$f_proyecto); break;
			
		case "respaldar_secc"	:	respaldar_secc(); break;
		case "respaldar_secc_2"	:	respaldar_secc_2($seccion); respaldar_secc(); break;

		case "scripts_mantto_1"	:	scripts_mantto_1(); break;
		case "scripts_mantto_2"	:	scripts_mantto_1(); scripts_mantto_2($archivo,$archivo_name); break;
		
		case "errores_1"		:	errores_1(); break;
		case "errores_2"		:	errores_2($estado); errores_1(); break;

		case "ajustar_p1"		:	ajustar_p1($folio); break;
		case "ajustar_p2"		:	ajustar_p2($folios); ajustar_p1($folio); break;
		
		case "filtros_1"		:	filtros_1($reporte); break;
		case "filtros_2"		:	filtros_2($reporte,$valor); filtros_1($reporte); break;

		case "desbloquear_1"	:	desbloquear_1($empleado); break;
		case "desbloquear_2"	:	desbloquear_2($empleado,$id_); desbloquear_1($empleado); break;
		
		case "historial_1"		:	historial_1($anio,$mes); break;
		case "historial_2"		:	historial_2($anio,$mes); historial_1($anio,$mes); break;
		
		default					:	parametros($orden,$id_); break;
		} ?>			
		<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { 
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; } ?>
    
<div style="margin-left:100px;" class="titulo">
<?php if($admin=='2') { ?>
<table align="center" cellpadding="5" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200" rowspan="3">CONFIGURACIÓN</td>	
	<td bgcolor="#CCCCCC" align="center" width="130"><a href="?op=parametros" class="menuLink">Parámetros</a></td>
	<td width="5">&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center" width="130"><a href="?op=auto" class="menuLink">Autorizaciones Auto</a></td>
	<td width="5">&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center" width="130"><a href="?op=respaldar_secc" class="menuLink">Respaldar Base Datos</a></td>
	<td width="5">&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center" width="130"><a href="?op=scripts_mantto_1" class="menuLink">Scripts de Mantenimiento</a></td>
    <td width="5">&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center" width="130"><a href="?op=mantenimiento" class="menuLink">Mantenimiento</a></td>
</tr>  
<tr height="5"><td colspan="8"></td></tr>  
<tr>    
	<td bgcolor="#CCCCCC" align="center"><a href="?op=ajustar_p1" class="menuLink">Ajustar Autorizaciones</a></td>   
	<td>&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center"><a href="?op=filtros_1" class="menuLink">Filtros Reportes</a></td>
	<td>&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center"><a href="?op=desbloquear_1" class="menuLink">Cuentas en uso</a></td>    
	<td>&nbsp;</td>
	<td bgcolor="#CCCCCC" align="center"><a href="?op=historial_1" class="menuLink">Enviar a Historial</a></td>
    <td width="5">&nbsp;</td>
    <td bgcolor="#CCCCCC" align="center">&nbsp;</td>
</tr>
</table><?php } ?>
</div><hr>
<?php } 	


function historial_1($anio,$mes) {
if(!$anio) $anio = date("Y");
if(!$mes)  $mes  = date("m")-1; ?>
<form action="?op=historial_1" method="post" name="form1">
<table align="center" class="tabla">
<caption>Enviar boletas liberadas a historial</caption>
<tr height="20">
	<td align="center" width="50">Año:</td>
	<td align="center" width="160">
    <select name="anio" class="texto" style="width:150px;">
    	<option value=""></option>
        <option value="2011" <?php if($anio=='2011'){?> selected="selected"<?php } ?>>2011</option>
        <option value="2012" <?php if($anio=='2012'){?> selected="selected"<?php } ?>>2012</option> 
        <option value="2013" <?php if($anio=='2013'){?> selected="selected"<?php } ?>>2013</option> 
        <option value="2014" <?php if($anio=='2014'){?> selected="selected"<?php } ?>>2014</option> 
        <option value="2015" <?php if($anio=='2015'){?> selected="selected"<?php } ?>>2015</option>
	</select>    
    </td>
</tr>
<tr height="20">
	<td align="center">Mes:</td>
	<td align="center">
    <select name="mes" class="texto" style="width:150px;">
    	<option value=""></option>
        <option value="1" <?php if($mes=='1'){?> selected="selected"<?php } ?>>Enero</option>
        <option value="2" <?php if($mes=='2'){?> selected="selected"<?php } ?>>Febrero</option> 
        <option value="3" <?php if($mes=='3'){?> selected="selected"<?php } ?>>Marzo</option> 
        <option value="4" <?php if($mes=='4'){?> selected="selected"<?php } ?>>Abril</option> 
        <option value="5" <?php if($mes=='5'){?> selected="selected"<?php } ?>>Mayo</option> 
        <option value="6" <?php if($mes=='6'){?> selected="selected"<?php } ?>>Junio</option> 
        <option value="7" <?php if($mes=='7'){?> selected="selected"<?php } ?>>Julio</option> 
        <option value="8" <?php if($mes=='8'){?> selected="selected"<?php } ?>>Agosto</option> 
        <option value="9" <?php if($mes=='9'){?> selected="selected"<?php } ?>>Septiembre</option> 
        <option value="10" <?php if($mes=='10'){?> selected="selected"<?php } ?>>Octubre</option> 
        <option value="11" <?php if($mes=='11'){?> selected="selected"<?php } ?>>Noviembre</option> 
        <option value="12" <?php if($mes=='12'){?> selected="selected"<?php } ?>>Diciembre</option> 
	</select>    
    </td>
</tr>
<tr height="40">
	<td colspan="2" align="center" valign="middle">
    	<input type="submit" value="Buscar" class="submit"></td>
    </td>
</tr>
</table></form>

<?php if($anio!='' && $mes!='') { ?> 
<table align="center" class="tabla">
<caption><span class="rojo"><b>IMPORTANTE! No interrumpa el proceso hasta que concluya. Puede tomar algunos minutos.</b></span></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">Año</td>
		<td width="150" align="center">Mes</td>
		<td width="100" align="center">Totales</td>
        <td width="100" align="center">Pendientes</td>
		<td width="100" align="center">Respaldadas</td>
        <td width="40" align="center">Enviar</td>
	</tr>
</thead>
<tbody>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $anio;?></td>
    <td align="left">&nbsp;&nbsp;
	<?php switch($mes) {
			case "1"	:	echo "Enero";      $fecha = $anio."-01-%"; break;
			case "2"	:	echo "Febrero";    $fecha = $anio."-02-%"; break;
			case "3"	:	echo "Marzo"; 	   $fecha = $anio."-03-%"; break;
			case "4"	:	echo "Abril"; 	   $fecha = $anio."-04-%"; break;
			case "5"	:	echo "Mayo"; 	   $fecha = $anio."-05-%"; break;
			case "6"	:	echo "Junio"; 	   $fecha = $anio."-06-%"; break;
			case "7"	:	echo "Julio";	   $fecha = $anio."-07-%"; break;
			case "8"	:	echo "Agosto";	   $fecha = $anio."-08-%"; break;
			case "9"	:	echo "Septiembre"; $fecha = $anio."-09-%"; break;
			case "10"	:	echo "Octubre";    $fecha = $anio."-10-%"; break;
			case "11"	:	echo "Noviembre";  $fecha = $anio."-11-%"; break;
			case "12"	:	echo "Diciembre";  $fecha = $anio."-12-%"; break;
	} ?></td>
    <?php $s_1 = "Select * from scrap_folios where fecha like '$fecha'"; 
	      $r_1 = mysql_query($s_1); 
		  $totales = mysql_num_rows($r_1); ?>
    <td align="center"><?php echo $totales;?></td>
    <?php $s_1 = "Select * from scrap_folios, scrap_partes where fecha like '$fecha' and scrap_partes.no_folio = scrap_folios.no_folio and ";	      
		  $s_1.= "(docto_sap='0' or docto_sap='') and status!='2' and activo!='2' group by scrap_folios.no_folio";
	      $r_1 = mysql_query($s_1); 
		  $pendientes = mysql_num_rows($r_1); ?>
    <td align="center"><?php echo $pendientes; ?></td>
    <?php $s_1 = "Select * from ".$anio."_scrap_folios where fecha like '$fecha'"; 
	      $r_1 = mysql_query($s_1); 
		  $respaldadas = mysql_num_rows($r_1); ?>
    <td align="center"><?php echo $respaldadas; ?></td>      
	<td align="center">
    	<?php if($respaldadas==0 && $pendientes==0) { ?>
		<a href="?op=historial_2&anio=<?php echo $anio;?>&mes=<?php echo $mes;?>" onclick='return confirm("¿Enviar folios a historial?")'>
		<img src="../imagenes/right.gif" alt="Enviar" border="0"></a><?php } ?></td>
</tr>
</tbody>
</table>
<?php } }



function historial_2($anio,$mes) { 

$fecha = $anio."-".str_pad($mes,2,0,STR_PAD_LEFT)."-%";

$s_ = "select * from scrap_folios where fecha like '$fecha' order by no_folio asc";
$r_ = mysql_query($s_); $folios_real_1  = mysql_num_rows($r_);

$s_ = "select * from ".$anio."_scrap_folios where fecha like '$fecha' order by no_folio asc";
$r_ = mysql_query($s_); $folios_nuevo_1 = mysql_num_rows($r_);

$s_ = "select * from scrap_folios where fecha like '$fecha' order by no_folio asc";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$boleta_1 = $partes_1 = $bitacora_1 = $autorizaciones_1 = $codigos_1 = 0;
	$boleta_2 = $partes_2 = $bitacora_2 = $autorizaciones_2 = $codigos_2 = 0;
	
	if($anio>=2013) { 
	$s_1 = "insert into ".$anio."_scrap_folios (id, id_emp, empleado, no_folio, fecha, timer, semana, anio, turno, id_proyecto, proyecto, id_planta, planta, id_division, ";
	$s_1.= "division, id_segmento, segmento, id_pc, profit_center, id_apd, apd, id_area, area, id_estacion, estacion, id_linea, linea, id_defecto, defecto, id_causa, ";
	$s_1.= "causa, codigo_scrap, financiero, reason_code, orden_interna, txs_sap, mov_sap, id_supervisor, supervisor, operador, no_personal, info_1, info_2, o_mantto, ";
	$s_1.= "archivo, comentario, accion_correctiva, vendor, v_nombre, carga_masiva, status, activo, editada) values ('', '$d_[id_emp]', '$d_[empleado]', '$d_[no_folio]', ";
	$s_1.= "'$d_[fecha]', '$d_[timer]', '$d_[semana]', '$d_[anio]', '$d_[turno]', '$d_[id_proyecto]', '$d_[proyecto]', '$d_[id_planta]', '$d_[planta]', '$d_[id_division]', ";
	$s_1.= "'$d_[division]', '$d_[id_segmento]', '$d_[segmento]', '$d_[id_pc]', '$d_[profit_center]', '$d_[id_apd]', '$d_[apd]', '$d_[id_area]', '$d_[area]', ";
	$s_1.= "'$d_[id_estacion]', '$d_[estacion]', '$d_[id_linea]', '$d_[linea]', '$d_[id_defecto]', '$d_[defecto]', '$d_[id_causa]', '$d_[causa]', '$d_[codigo_scrap]', ";
	$s_1.= "'$d_[financiero]', '$d_[reason_code]', '$d_[orden_interna]', '$d_[txs_sap]', '$d_[mov_sap]', '$d_[id_supervisor]', '$d_[supervisor]', '$d_[operador]', ";
	$s_1.= "'$d_[no_personal]', '$d_[info_1]', '$d_[info_2]', '$d_[o_mantto]', '$d_[archivo]', '$d_[comentario]', '$d_[accion_correctiva]', '$d_[vendor]', '$d_[vnombre]', ";
	$s_1.= "'$d_[carga_masiva]', '$d_[status]', '$d_[activo]', '$d_[editada]')"; }
	else { 
	$s_1 = "insert into ".$anio."_scrap_folios (id, id_emp, empleado, no_folio, fecha, timer, semana, anio, turno, id_proyecto, proyecto, id_planta, planta, id_division, ";
	$s_1.= "division, id_segmento, segmento, id_pc, profit_center, id_apd, apd, id_area, area, id_estacion, estacion, id_linea, linea, id_defecto, defecto, id_causa, ";
	$s_1.= "causa, codigo_scrap, financiero, reason_code, orden_interna, txs_sap, mov_sap, id_supervisor, supervisor, operador, no_personal, info_1, info_2, o_mantto, ";
	$s_1.= "archivo, comentario, accion_correctiva, carga_masiva, status, activo, editada) values ('', '$d_[id_emp]', '$d_[empleado]', '$d_[no_folio]', '$d_[fecha]', ";
	$s_1.= "'$d_[timer]', '$d_[semana]', '$d_[anio]', '$d_[turno]', '$d_[id_proyecto]', '$d_[proyecto]', '$d_[id_planta]', '$d_[planta]', '$d_[id_division]', ";
	$s_1.= "'$d_[division]', '$d_[id_segmento]', '$d_[segmento]', '$d_[id_pc]', '$d_[profit_center]', '$d_[id_apd]', '$d_[apd]', '$d_[id_area]', '$d_[area]', ";
	$s_1.= "'$d_[id_estacion]', '$d_[estacion]', '$d_[id_linea]', '$d_[linea]', '$d_[id_defecto]', '$d_[defecto]', '$d_[id_causa]', '$d_[causa]', '$d_[codigo_scrap]', ";
	$s_1.= "'$d_[financiero]', '$d_[reason_code]', '$d_[orden_interna]', '$d_[txs_sap]', '$d_[mov_sap]', '$d_[id_supervisor]', '$d_[supervisor]', '$d_[operador]', ";
	$s_1.= "'$d_[no_personal]', '$d_[info_1]', '$d_[info_2]', '$d_[o_mantto]', '$d_[archivo]', '$d_[comentario]', '$d_[accion_correctiva]', '$d_[carga_masiva]', ";
	$s_1.= "'$d_[status]', '$d_[activo]', '$d_[editada]')"; }
	$r_1 = mysql_query($s_1); 
	$boleta_1 = 1;
	
	$s_1 = "Select * from scrap_partes where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 
		$s_2 = "insert into ".$anio."_scrap_partes (id, no_folio, padre, no_parte, tipo, tipo_sub, descripcion, cantidad, costo, total, batch_id, serial_unidad, ubicacion, ";
		$s_2.= "o_mantto, docto_sap, deficit, docto_def) values ('', '$d_1[no_folio]', '$d_1[padre]', '$d_1[no_parte]', '$d_1[tipo]', '$d_1[tipo_sub]', '$d_1[descripcion]',";
		$s_2.= "'$d_1[cantidad]', '$d_1[costo]', '$d_1[total]', '$d_1[bacth_id]', '$d_1[serial_unidad]', '$d_1[ubicacion]', '$d_1[o_mantto]', '$d_1[docto_sap]', ";
		$s_2.= "'$d_1[deficit]', '$d_1[docto_def]')";
		$r_2 = mysql_query($s_2); 
		$partes_1++;
	}
	
	$s_1 = "Select * from aut_bitacora where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 	
		if($anio>='2015'){
			$s_2 = "insert into ".$anio."_aut_bitacora (id, no_folio, depto, id_emp, empleado, status, fecha, hora, comentario, motivo) values ('', '$d_1[no_folio]', '$d_1[depto]', ";
			$s_2.= "'$d_1[id_emp]', '$d_1[empleado]', '$d_1[status]', '$d_1[fecha]', '$d_1[hora]', '$d_1[comentario]', '$d_1[motivo]')";
			$r_2 = mysql_query($s_2); 
			$bitacora_1++;
		} else {
			$s_2 = "insert into ".$anio."_aut_bitacora (id, no_folio, depto, id_emp, empleado, status, fecha, hora, comentario) values ('', '$d_1[no_folio]', '$d_1[depto]', ";
			$s_2.= "'$d_1[id_emp]', '$d_1[empleado]', '$d_1[status]', '$d_1[fecha]', '$d_1[hora]', '$d_1[comentario]')";
			$r_2 = mysql_query($s_2); 
			$bitacora_1++;
		}
	}	
	
	$s_1 = "Select * from autorizaciones where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 	
		$s_2 = "insert into ".$anio."_autorizaciones (id, no_folio, depto, id_emp, empleado, status, aviso) values ('', '$d_1[no_folio]', '$d_1[depto]', ";
		$s_2.= "'$d_1[id_emp]', '$d_1[empleado]', '$d_1[status]', '$d_1[aviso]')";
		$r_2 = mysql_query($s_2); 
		$autorizaciones_1++;
	}	

	$s_1 = "Select * from scrap_codigos where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 	
		$s_2 = "insert into ".$anio."_scrap_codigos (id, no_folio, id_area, area, id_estacion, estacion, id_linea, linea, id_defecto, defecto, id_causa, causa, ";
		$s_2.= "codigo_scrap) values ('', '$d_1[no_folio]', '$d_1[id_area]', '$d_1[area]', '$d_1[id_estacion]', '$d_1[estacion]', '$d_1[id_linea]', '$d_1[linea]', ";
		$s_2.= "'$d_1[id_defecto]', '$d_1[defecto]', '$d_1[id_causa]', '$d_1[causa]', '$d_1[codigo_scrap]')";
		$r_2 = mysql_query($s_2); 
		$codigos_1++;
	}
	
	$s_1 = "select * from ".$anio."_scrap_folios where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	$boleta_2 = mysql_num_rows($r_1);
	
	$s_1 = "select * from ".$anio."_scrap_partes where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	$partes_2 = mysql_num_rows($r_1);
	
	$s_1 = "select * from ".$anio."_aut_bitacora where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	$bitacora_2 = mysql_num_rows($r_1);	

	$s_1 = "select * from ".$anio."_autorizaciones where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	$autorizaciones_2 = mysql_num_rows($r_1);	

	$s_1 = "select * from ".$anio."_scrap_codigos where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
	$codigos_2 = mysql_num_rows($r_1);
	
	if($boleta_1 == $boleta_2 && $partes_1 == $partes_2 && $bitacora_1 == $bitacora_2 && $autorizaciones_1 == $autorizaciones_2 && $codigos_1 == $codigos_2){ 
		$s_2 = "delete from scrap_folios where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from scrap_partes where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from aut_bitacora where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from autorizaciones where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from scrap_codigos where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);								
	} else { 
		$s_2 = "delete from ".$anio."_scrap_folios where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from ".$anio."_scrap_partes where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from ".$anio."_aut_bitacora where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from ".$anio."_autorizaciones where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
		$s_2 = "delete from ".$anio."_scrap_codigos where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2);
	}	
}

//$s_ = "select * from scrap_folios where fecha like '$fecha' order by no_folio asc";
//$r_ = mysql_query($s_); $folios_real_2  = mysql_num_rows($r_);

//$s_ = "select * from ".$anio."_scrap_folios where fecha like '$fecha' order by no_folio asc";
//$r_ = mysql_query($s_); $folios_nuevo_2 = mysql_num_rows($r_);

//echo "<br><div align='center' class='rojo'>";
	//echo "Folios Originales  -> Había: ".$folios_real_1." Quedan: ".$folios_real_2."<br>";
	//echo "Folios Respaldados -> Había: ".$folios_nuevo_1." Quedan: ".$folios_nuevo_2."<br>";
//echo "</div>";
}


function desbloquear_1($empleado) { 
	if(!$empleado) $empleado = '%'; 
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	$s_ = "delete from acceso where fecha<'$fecha'";
	$r_ = mysql_query($s_); ?>
<form action="?op=desbloquear_1" method="post" name="form1">
<table align="center" class="tabla">
<caption>Usuarios conectados / Cuentas en uso</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="120" align="center">Usuario</td>
		<td width="350" align="center">
        <?php $s_2 = "select * from empleados where activo='1' and apellidos!='' and nombre!='' order by apellidos";
              $r_2 = mysql_query($s_2); ?>
        <select name="empleado" class="texto" style="width:350px;" onchange="submit();">
        <option value="%">Todos</option>
        	<?php while($d_2 = mysql_fetch_array($r_2)) { ?>
            <option value="<?php echo $d_2['id'];?>" <?php if($empleado==$d_2['id']){?> selected="selected"<?php } ?>>
				<?php echo $d_2['apellidos']." ".$d_2['nombre'];?></option>
            <?php } ?>   
        </select>    
        </td>
		<td width="100" align="center">Fecha</td>
        <td width="100" align="center">Hora</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<?php 
   $s_1 = "select empleados.nombre, empleados.apellidos, acceso.* from empleados, acceso where acceso.id_emp = empleados.id and acceso.id_emp like '$empleado' and ";
   $s_1.= "activo='1' order by apellidos";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $d_1['usuario'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['apellidos']." ".$d_1['nombre'];?></td>
    <td align="center"><?php echo $d_1['fecha'];?></td>
    <td align="center"><?php echo $d_1['hora'];?></td>
	<td align="center">
		<a href="?op=desbloquear_2&id_=<?php echo $d_1['id'];?>&empleado=<?php echo $empleado;?>" onclick='return confirm("Borrar registro?");'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php } ?>
</tbody>
</table>
</form>
<?php }


function desbloquear_2($empleado,$id_) {
	$s_ = "delete from acceso where id='$id_'";
	$r_ = mysql_query($s_);
}	


function filtros_1($reporte) { ?>
<form action="?op=filtros_1" method="post" name="form1">
<table align="center" class="tabla">
<caption>Configurar filtros en reportes</caption>
<tr height="20">
	<td align="center" width="80">Reporte:</td>
	<td align="center">
    <select name="reporte" class="texto" style="width:250px;" onchange="submit();">
    	<option value=""></option>
        <option value="">---------Autorizador-----------</option>
        <option value="a_aprobados" <?php if($reporte=='a_aprobados'){?> selected="selected"<?php } ?>>Aprobado</option>
        <option value="a_cancelados" <?php if($reporte=='a_cancelados'){?> selected="selected"<?php } ?>>Cancelado</option>
        <option value="scrap_firmar" <?php if($reporte=='scrap_firmar'){?> selected="selected"<?php } ?>>Por firmar</option>
        <option value="a_proceso" <?php if($reporte=='a_proceso'){?> selected="selected"<?php } ?>>Proceso</option>
        <option value="a_rechazados" <?php if($reporte=='a_rechazados'){?> selected="selected"<?php } ?>>Rechazado</option>
        <option value="">---------Capturista------------</option>
        <option value="c_aprobados" <?php if($reporte=='c_aprobados'){?> selected="selected"<?php } ?>>Aprobado</option>
        <option value="c_cancelados" <?php if($reporte=='c_cancelados'){?> selected="selected"<?php } ?>>Cancelado</option>
        <option value="c_proceso" <?php if($reporte=='c_proceso'){?> selected="selected"<?php } ?>>Proceso</option>
        <option value="c_rechazados" <?php if($reporte=='c_rechazados'){?> selected="selected"<?php } ?>>Rechazado</option>
        <option value="">---------Reportes------------</option>        
        <option value="de_captura" <?php if($reporte=='de_captura'){?> selected="selected"<?php } ?>>Captura SAP</option>
        <option value="definitivo" <?php if($reporte=='definitivo'){?> selected="selected"<?php } ?>>Definitivo</option>
        <option value="atrasos" <?php if($reporte=='atrasos'){?> selected="selected"<?php } ?>>Atrasos</option>
        <option value="ver_filtros" <?php if($reporte=='ver_filtros'){?> selected="selected"<?php } ?>>Preliminar y General</option>
	</select>    
    </td>
</tr>
</table></form>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">
<?php switch($reporte) {
		case "a_aprobados"		:	echo "Autorizador - Aprobados"; break;
		case "a_cancelados"		:	echo "Autorizador - Cancelado"; break;
		case "scrap_firmar"		:	echo "Autorizador - Por firmar"; break;
		case "a_proceso"		:	echo "Autorizador - Proceso"; break;
		case "a_rechazados"		:	echo "Autorizador - Rechazado"; break;
		case "c_aprobados"		:	echo "Capturista - Aprobados"; break;
		case "c_cancelados"		:	echo "Capturista - Cancelado"; break;
		case "c_proceso"		:	echo "Capturista - Proceso"; break;
		case "c_rechazado"		:	echo "Capturista - Rechazado"; break;
		case "de_captura"		:	echo "Reportes - Captura SAP"; break;
		case "definitivo"		:	echo "Reportes - Definitivo"; break;
		case "atrasos"			:	echo "Reportes - Atrasos"; break;
		case "ver_filtros"		:	echo "Preliminar y General"; break; } ?>
</td></tr>
</table>
<?php if($reporte!='') { ?>
<form method="post" action="?op=filtros_2">
<input type="hidden" name="reporte" value="<?php echo $reporte;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="120">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="120">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="120">Campo</td>    	
</tr>	
</thead>
<tbody>
<?php $s_1 = "select id, campo, nombre from encabezados where ver_reportes!='0' order by nombre";
      $r_1 = mysql_query($s_1);
	  while($d_1=mysql_fetch_array($r_1)) { 
	  if($d_1['nombre']!='') { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
    	<input type="checkbox" name="valor[<?php echo $d_1['id'];?>]" <?php echo get_checked($reporte,$d_1['id']);?> value="1"></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td> 
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<input type="checkbox" name="valor[<?php echo $d_1['id'];?>]" <?php echo get_checked($reporte,$d_1['id']);?> value="1"></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>   
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<input type="checkbox" name="valor[<?php echo $d_1['id'];?>]" <?php echo get_checked($reporte,$d_1['id']);?> value="1"></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td><?php } ?>	 
</tr><?php } ?>	
</tbody>
</table>
<br><div align="center"><input type="submit" value="Guardar" class="submit" /></div>
</form>               
<?php } }


function get_checked($reporte,$id_) {
	$s_ = "Select ".$reporte." as estado from encabezados where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	if($d_['estado']=='1') { return "checked"; }
	else { return ""; }
}	
	
	
function filtros_2($reporte,$valor) {
	$s_ = "update encabezados set ".$reporte."='0'";
	$r_ = mysql_query($s_);
	foreach($valor as $id => $campo) {
		$s_ = "update encabezados set ".$reporte."='1' where id='$id'";
		$r_ = mysql_query($s_);
	}
}	
	
	
function ajustar_p1($folio) { ?>
<br><table align="center" class="tabla">
<caption>Ajustar Autorizaciones</caption>
<form action="?op=ajustar_p1" method="post" name="form1">
	<tr height="20">
		<td width="100" align="center">Buscar Folio:</td>
		<td align="center"><input type="text" name="folio" class="texto" size="20" value="<?php echo $folio;?>" /></td>
		<td width="110" align="center"><input type="submit" class="submit" value="Buscar" /></td>
	</tr>
</form>
</table><br>

<form action="?op=ajustar_p2" method="post" name="form1">
<table align="center" class="tabla">
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="50">No.</td>
    <td align="center" width="50">Folio</td>
	<td align="center" width="40">Cant</td>
	<td align="center" width="80">Cod.Scrap</td>
	<td align="center" width="70">Total</td>
	<td align="center" width="90">Fecha</td>
	<?php  echo"<td width='60' align='center'><span>LO</span></td>"; 
		   echo"<td width='60' align='center'><span>LO-A</span></td>"; 
		   echo"<td width='60' align='center'><span>LPL</span></td>"; 
		   echo"<td width='60' align='center'><span>FFM</span></td>"; 
		   echo"<td width='60' align='center'><span>FFC</span></td>"; 
		   echo"<td width='60' align='center'><span>Prod</span></td>"; 
		   echo"<td width='60' align='center'><span>SQM</span></td>"; 
		   echo"<td width='60' align='center'><span>Finanzas</span></td>"; 
		   echo"<td width='60' align='center'><span>INV</span></td>"; ?>
    <td align="center" width="60">Estado Asignado</td>
    <td align="center" width="60">Estado Real</td>       
</tr>
</thead>
<tbody>
<?php $s_ = "select no_folio, status from scrap_folios where activo='1' and status!='1' ";
	  if($folio!='') { $s_.= "and no_folio like '$folio' "; }
	  $s_.= "order by no_folio";
	  $r_ = mysql_query($s_); $i=0;
	  while($d_ = mysql_fetch_array($r_)) {
	  	$estado = get_status($d_['no_folio']); 
		if($estado!=$d_['status']) {
			$folios[$i] = $d_['no_folio']; 
			$states[$i] = $estado; $i++; 
		}
	  }	$j=1;
	  for($i=0;$i<count($folios);$i++) { 	
	  $s_ = "select folios.no_folio, folios.fecha, sum(partes.cantidad) as cantidad_total, folios.codigo_scrap, sum(partes.total) ";
	  $s_.= "as costo_total, folios.status from scrap_partes as partes, scrap_folios as folios where folios.no_folio=partes.no_folio ";
	  $s_.= "and folios.no_folio='$folios[$i]' group by folios.no_folio";
	  $r_ = mysql_query($s_); 
	  $d_ = mysql_fetch_array($r_); ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="center"><input type="checkbox" name="folios[]" value="<?php echo $folios[$i];?>" checked="checked"></td>
	<td align="center"><?php echo $j;?></td>
    <td align="center"><?php echo $d_['no_folio'];?></td>
	<td align="center"><?php echo $d_['cantidad_total'];?></td>
	<td align="center"><?php echo $d_['codigo_scrap'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_['costo_total'],2);?>&nbsp;&nbsp;</td>
	<td align="center"><?php echo $d_['fecha'];?></td>
	<td align="center"><?php echo get_bandera("lo",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("loa",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("lpl",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffm",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffc",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("prod",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("sqm",$d_['no_folio']);?></td>
    <td align="center"><?php echo get_bandera("fin",$d_['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("inv",$d_['no_folio']);?></td>
    <td align="center">
	<?php switch($d_['status'])  {
			case "0"	:	echo "<img src='../imagenes/flag_orange.gif' border=0>"; break;
			case "1"	:	echo "<img src='../imagenes/flag_green.gif' border=0>"; break;
			case "2"	:	echo "<img src='../imagenes/flag_red.gif' border=0>"; break;
			case "3"	:	echo "<img src='../imagenes/cross.png' border=0>"; break; } ?></td>
     <td align="center">
	<?php switch($states[$i])  {
			case "0"	:	echo "<img src='../imagenes/flag_orange.gif' border=0>"; break;
			case "1"	:	echo "<img src='../imagenes/flag_green.gif' border=0>"; break;
			case "2"	:	echo "<img src='../imagenes/flag_red.gif' border=0>"; break;
			case "3"	:	echo "<img src='../imagenes/cross.png' border=0>"; break; } ?></td>
</tr>
<?php $j++; } ?>
</tbody>
</table>
<br><div align="center">
	<input type="submit" class="submit" value="Ajustar" /></div>
</form>
<br><br><br>
<?php }


function get_bandera($depto,$folio) { 
$data['t'] = $data['p'] = $data['a'] = $data['r'] = 0;

if($depto=='lpl' || $depto=='ffc' || $depto=='ffm' || $depto=='prod') {
	$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto'"; 
	$r_ = mysql_query($s_);
	$t_ = mysql_num_rows($r_);
	if($t_>0) { 
		$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto' and status='1'"; 
		$r_ = mysql_query($s_);
		$a_ = mysql_num_rows($r_);

		$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto' and status='2'"; 
		$r_ = mysql_query($s_);
		$r_ = mysql_num_rows($r_);

		if($r_>0) { $status = '2'; }
		elseif($a_==$t_) { $status = '1'; }
		else { $status = '0'; }
	}
	else {	$status = "NA"; }
}	

if($depto=='lo' || $depto=='loa' || $depto=='sqm' || $depto=='fin' || $depto=='inv') {	
	$s_ = "select * from autorizaciones where no_folio='$folio' and depto='$depto'"; 
	$r_ = mysql_query($s_);	
	if(mysql_num_rows($r_)>0) { 
		$d_ = mysql_fetch_array($r_);
		$status = $d_['status']; }
	else { 
		$status = "NA"; }	
}
	switch($status) {	
			case "0"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_orange.gif' style='cursor:hand'; border=0></a>"; break;			
			case "1"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
			  				$img.= "<img src='../imagenes/flag_green.gif' style='cursor:hand'; border=0></a>"; break;			
			case "2"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/flag_red.gif' style='cursor:hand'; border=0></a>"; break;
			case "3"	:	$img = "<a class='frame_autorizacion' href='../detalles.php?op=autorizaciones&folio=$folio'>";
							$img.= "<img src='../imagenes/cross.png' style='cursor:hand'; border=0></a>"; break;					
			case "NA"	:	$img = "NA"; break;				
		}
	return $img;	
}


function get_status($folio) {

	//Todas las autorizaciones
	$s_1    = "select * from autorizaciones where no_folio='$folio'";
	$r_1    = mysql_query($s_1);
	$d_1[0] = mysql_num_rows($r_1);
	//Autorizaciones en ceros
	$s_1    = "select * from autorizaciones where status='0' and no_folio='$folio'";
	$r_1    = mysql_query($s_1);
	$d_1[1] = mysql_num_rows($r_1);	
	//Autorizaciones en unos
	$s_1    = "select * from autorizaciones where status='1' and no_folio='$folio'";
	$r_1    = mysql_query($s_1);
	$d_1[2] = mysql_num_rows($r_1);		
	//Autorizaciones en dos
	$s_1    = "select * from autorizaciones where status='2' and no_folio='$folio'";
	$r_1    = mysql_query($s_1); 
	$d_1[3] = mysql_num_rows($r_1);
	//Autorizaciones en tres
	$s_1    = "select * from autorizaciones where status='3' and no_folio='$folio'";
	$r_1    = mysql_query($s_1); 
	$d_1[4] = mysql_num_rows($r_1);

	if($d_1[4] > 0) { $estado = 3; } //Si al menos una está en dos
	elseif($d_1[3] > 0) { $estado = 2; } //Si al menos una está en dos
	elseif($d_1[1] > 0) { $estado = 0; } //Si existe al menos un cero
	elseif($d_1[0] == $d_1[2]) { $estado = 1; } //Si todas están en unos
					
	return $estado;
}


function ajustar_p2($folios) {
	for($i=0;$i<count($folios);$i++) {
		$estado = get_status($folios[$i]);		
		if($estado=='0') { $s_ = "update scrap_folios set status='0', activo='1' where no_folio='$folios[$i]'"; } //Proceso
		if($estado=='1') { $s_ = "update scrap_folios set status='1', activo='1' where no_folio='$folios[$i]'"; } //Aprobados
		if($estado=='2') { $s_ = "update scrap_folios set status='2', activo='1' where no_folio='$folios[$i]'"; } //Rechazado
		if($estado=='3') { $s_ = "update scrap_folios set status='0', activo='2' where no_folio='$folios[$i]'"; } //Cancelado
		$r_ = mysql_query($s_);
	}
}



function auto($f_tipo,$f_proyecto) { 
if(!$f_tipo)     { $f_tipo = "horas"; } 
if(!$f_proyecto) { $f_proyecto = "%"; }?>	
<form action="?op=auto" method="post" name="form1">
<table align="center" class="tabla">
<caption>
	Aprobaciones automáticas por 
    <select name="f_tipo" class="texto" onchange="submit();">
    	<option value="horas" <?php if($f_tipo=="horas"){?> selected="selected" <?php }?>>Horas</option>
        <option value="proyectos" <?php if($f_tipo=="proyectos"){?> selected="selected" <?php }?>>Proyectos</option>
    </select>
</caption>
<?php if($f_tipo=="horas"){?>
    <thead>
        <tr bgcolor="#E6E6E6" height="20">
            <td width="250" align="center">División</td>
            <td width="60" align="center">Horas</td>
        </tr>
    </thead>
    <?php $s_1 = "select * from divisiones where activo!='2' order by nombre"; 
          $r_1 = mysql_query($s_1);
        while($d_1=mysql_fetch_array($r_1)) { ?>      
    <tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
        <?php $s_2 = "select * from configuracion where nombre='$d_1[id]'";
              $r_2 = mysql_query($s_2);
              $d_2 = mysql_fetch_array($r_2); ?>    
        <td align="center"><input type="text" name="valores[<?php echo $d_1['id'];?>]" class="texto" size="6" value="<?php echo $d_2['valor'];?>"></td>
    </tr>
    <?php } ?>
    </tbody>
<?php } else {?>
	<thead>
        <tr bgcolor="#E6E6E6" height="20">
            <td width="250" align="center">
            	<?php $s_1 = "select * from proyectos where activo!='2' order by nombre";
				$r_1 = mysql_query($s_1);?>
            	<select name="f_proyecto" class="texto" style="width:250px;" onchange="submit();">
                	<option value="%" <?php if($f_proyecto=="%"){?> selected="selected" <?php }?>>Proyectos</option>
                    <?php while($d_1 = mysql_fetch_array($r_1)){?>
                    	<option value="<?php echo $d_1['id'];?>" <?php if($f_proyecto==$d_1['id']){?> selected="selected" <?php }?>><?php echo $d_1['nombre']; ?></option>
                    <?php }?>
                </select>
            </td>
            <td width="60" align="center">Horas</td>
            <td width="60" align="center">Acción</td>
        </tr>
    </thead>
    <?php $s_1 = "select * from autorizaciones_auto where activo!='2' and id_proyecto like '$f_proyecto' and activo='1' order by proyecto"; 
          $r_1 = mysql_query($s_1);
		  $n_1 = mysql_num_rows($r_1);
        while($d_1=mysql_fetch_array($r_1)) { ?>  
        	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
                <td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
                <td align="center">
                	<input type="text" name="valores[<?php echo $d_1['id'];?>]" class="texto" size="6" value="<?php echo $d_1['horas'];?>"></td>
                <td align="center">
                	<img src="../imagenes/delete.gif" style="cursor:pointer;" onclick="borrar('<?php echo $d_1['id'];?>');">
                </td>
            </tr>
      	<?php } ?>
     </tbody>
<?php }?>
</table>
<br>
<div align="center">
	<?php if($f_tipo=="proyectos"){?>
   		<input type="button" value="Nuevo" class="submit" onclick="nuevo();">
        <?php if($n_1>0){?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" value="Guardar" class="submit" onclick="validar_auto('<?php echo $f_tipo;?>','lista');">
        <?php }
    } else {?>
		<input type="button" value="Guardar" class="submit" onclick="validar_auto('<?php echo $f_tipo;?>','');">
    <?php } ?>
</div>
</form><br>
<?php }


function guardar_auto($f_tipo,$valores,$proyecto,$subtipo){
	if($f_tipo!="proyectos"){
		if(count($valores)>0) { 
			foreach($valores as $id_ => $valor) {
				//echo $id_."-->".$valor."<br>"; 
				$s_ = "select * from configuracion where seccion='aprobaciones' and nombre='$id_'";
				$r_ = mysql_query($s_);
				if(mysql_num_rows($r_)>0) { 
					$s_1 = "update configuracion set valor='$valor' where nombre='$id_' and seccion='aprobaciones'";
					$r_1 = mysql_query($s_1); 
				} else { 
					$s_1 = "inset into configuracion VALUES ('', 'aprobaciones', '$id_', 'Horas para aprobacion automatica por division', 'aprobacion_auto', ";
					$s_1.= "'$valor', '1')";
					$r_1 = mysql_query($s_1);
				}
			} 
		} auto($f_tipo,$f_proyecto);	
	} else {
		if($subtipo=="formulario"){
			$s_ = "select * from autorizaciones_auto where id_proyecto='$proyecto' and activo='1'";
			$r_ = mysql_query($s_);
			if(mysql_num_rows($r_)>0){
				echo "<script>alert('Error, registro duplicado!');</script>";
				nuevo_auto("proyectos",$f_proyecto,$proyecto);
			} else {
				$s_1 = "select nombre from proyectos where id='$proyecto'";
				$r_1 = mysql_query($s_1);
				$d_1 = mysql_fetch_array($r_1);
				$nombre = $d_1['nombre'];
				$s_1 = "insert into autorizaciones_auto values('','$proyecto','$nombre','$valores','1')";
				$r_1 = mysql_query($s_1);
				auto($f_tipo,$f_proyecto);
			}
		} else {
			if(count($valores)>0) { 
				foreach($valores as $id_ => $valor) {
					$s_ = "select * from autorizaciones_auto where id='$id_'";
					$r_ = mysql_query($s_);
					$s_1 = "update autorizaciones_auto set horas='$valor' where id='$id_'";
					$r_1 = mysql_query($s_1); 
				} 
			} auto($f_tipo,$f_proyecto);	
		}
	}
}

function borrar_auto($f_proyecto,$id_auto){
	$s_ = "update autorizaciones_auto set activo='0' where id='$id_auto'";
	$r_ = mysql_query($s_);
}


function nuevo_auto($f_tipo,$f_proyecto,$proyecto){
if(!$f_tipo)     { $f_tipo = "proyecto"; } 
if(!$f_proyecto) { $f_proyecto = "%"; }?>	
<form action="?op=nuevo_auto" method="post" name="form1">
<input type="hidden" name="f_proyecto" value="<?php echo $f_proyecto;?>">
<table align="center" class="tabla">
<caption>
	Aprobaciones automáticas por 
    <select name="f_tipo" class="texto" onchange="submit();" disabled="disabled">
    	<option value="horas" <?php if($f_tipo=="horas"){?> selected="selected" <?php }?>>Horas</option>
        <option value="proyectos" <?php if($f_tipo=="proyectos"){?> selected="selected" <?php }?>>Proyectos</option>
    </select>
</caption>
<tr height="20">
	<td bgcolor="#E6E6E6" align="center">Proyecto</td>
    <td bgcolor="#CCCCCC">
    	<?php $s_1 = "select * from proyectos where activo!='2' order by nombre";
		$r_1 = mysql_query($s_1);?>
		<select name="proyecto" class="texto" style="width:250px;">
			<option>&nbsp;</option>
			<?php while($d_1 = mysql_fetch_array($r_1)){?>
				<option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected" <?php }?>><?php echo $d_1['nombre']; ?></option>
			<?php }?>
		</select>
    </td>
</tr>
<tr height="20">
	<td bgcolor="#E6E6E6" align="center">Horas</td>
    <td bgcolor="#CCCCCC"><input type="text" name="valores" class="texto" size="6"></td>
</tr>
</tbody>
</table>
<br>
<div align="center">
	<input type="button" value="Regresar" class="submit" onclick="regresar('<?php echo $f_proyecto;?>');">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Guardar" class="submit" onclick="validar_auto('<?php echo $f_tipo;?>','formulario');">
</div>
</form>
<?php }

function parametros($orden,$id_) { 
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; }

$dis = 'disabled';
if($id_!='') {
	$s_  = "select * from configuracion where id='$id_'";
    $r_  = mysql_query($s_);
	$d_  = mysql_fetch_array($r_); 
	$dis = ''; } ?>
<form action="?op=guardar" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="variable" value="<?php echo $d_['variable'];?>">
<table align="center" class="tabla">
<tr height="20" bgcolor="#F7F7F7">
        <td align="left" width="120">&nbsp;&nbsp;<?php echo utf8_decode($d_['nombre']);?></td>
    <td align="center"><input type="text" name="valor" value="<?php echo $d_['valor'];?>" size="50" class="texto"></td>
	<td align="center"><input type="submit" value="Guardar" class="submit" <?php echo $dis;?>></td>
</tr>
</table>
</form>

<table align="center" class="tabla" width="850">
<caption>Parámetros Generales</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">Nombre</td>
        <td width="450" align="center">Descripción</td>
		<td width="200" align="center">Valor</td>
		<td width="40" align="center">Editar</td>
	</tr>
</thead>
<?php $s_1 = "select * from configuracion where mostrar='1' and seccion='generales' order by nombre";
      $r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { ?>      
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['descripcion'];?></td>
    <td align="left">&nbsp;&nbsp;
	<?php if($d_1['variable']=='password') { 
			$num = strlen($d_1['valor']);  
			for($i=0;$i<$num;$i++) { echo "*"; } }
		  else { 
		  	if(strlen($d_1['valor'])>80) { echo substr($d_1['valor'],0,60)."..."; } 
			else { echo $d_1['valor']; } } ?></td>
	<td align="center">
    	<?php if($admin=='2' || $d_1['variable']=='bloqueado') { ?>
        <a href="?op=listado&id_=<?php echo $d_1['id'];?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a><?php } else { ?>
        <img src="../imagenes/pencil_gris.gif" alt="No puede editar" border="0"><?php } ?></td>
</tr>
<?php } ?>
</tbody>
</table><br>


<table align="center" class="tabla" width="850">
<caption>Archivos del Sistema</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">Nombre</td>
        <td width="450" align="center">Descripción</td>
		<td width="200" align="center">Valor</td>
		<td width="40" align="center">Editar</td>
	</tr>
</thead>
<?php $s_1 = "select * from configuracion where mostrar='1' and seccion='archivos' order by nombre";
      $r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { ?>      
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo utf8_decode($d_1['nombre']);?></td>
	<td align="left">&nbsp;&nbsp;<?php echo utf8_decode($d_1['descripcion']);?></td>
    <td align="center">
    	<?php if($d_1['valor']!='') { ?>
        <a href="<?php echo $d_1['valor'];?>" target="_blank">Ir al archivo</a><?php } else { echo "&nbsp;"; } ?></td>
	<td align="center">
    	<?php if($admin=='2') { ?>
        <a href="?op=listado&id_=<?php echo $d_1['id'];?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a><?php } else { ?>
        <img src="../imagenes/pencil_gris.gif" alt="No puede editar" border="0"><?php } ?></td>
</tr>
<?php } ?>
</tbody>
</table><br>

<table align="center" class="tabla" width="850">
<caption>Carpetas del Sistema</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">Nombre</td>
        <td width="350" align="center">Descripción</td>
		<td width="400" align="center">Valor</td>
		<td width="40" align="center">Editar</td>
	</tr>
</thead>
<?php $s_1 = "select * from configuracion where mostrar='1' and seccion='carpetas' order by nombre";
      $r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { ?>      
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo utf8_decode($d_1['nombre']);?></td>
	<td align="left">&nbsp;&nbsp;<?php echo utf8_decode($d_1['descripcion']);?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['valor']; ?></td>
	<td align="center">
    	<?php if($admin=='2') { ?>
        <a href="?op=listado&id_=<?php echo $d_1['id'];?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a><?php } else { ?>
        <img src="../imagenes/pencil_gris.gif" alt="No puede editar" border="0"><?php } ?></td>
</tr>
<?php } ?>
</tbody>
</table><br><br><br>
<?php }


function guardar($id_,$valor,$variable) {
if($id_=='1') { 
	$s_1 = "select * from scrap_folios where no_folio='$valor'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) {
		echo "<script>alert('El folio ya ha sido asignado!');</script>"; }
	else { 
		$s_1 = "update configuracion set valor='$valor' where id='$id_'";
		$r_1 = mysql_query($s_1);			
	}
} else { 
	$s_1 = "update configuracion set valor='$valor' where id='$id_'";
	$r_1 = mysql_query($s_1); }
}


function respaldar_secc() { ?>
<form action="?op=respaldar_secc_2" method="post" name="form1">
<table align="center" class="tabla">
<caption>Respaldar Secciones de la Base de Datos</caption>
<tr height="20">
	<td align="center" width="80">Sección:</td>
	<td align="center">
    <select name="seccion" class="texto" style="width:250px;">
    	<option value=""></option>
        <option value="catalogos">Catálogos</option>
        <option value="folios">Folios</option>
        <option value="hist_2011">Historial 2011</option>
        <option value="hist_2012">Historial 2012</option>
        <option value="hist_2013">Historial 2013</option>
        <option value="hist_2014">Historial 2014</option>
        <option value="hist_2015">Historial 2015</option>
        <option value="log">Log Sistema</option>
        <option value="materiales">Materiales</option>
        <option value="partes">Partes Padre</option>
        <option value="temporales">Temporales</option>
        <option value="usuarios">Usuarios</option>
	</select>    
    </td>
	<td align="center"><input type="submit" value="Crear Respaldo" class="submit"></td>
</tr>
</table>
<?php if (file_exists($actual)){ 
   echo "<br><div align=center class=aviso_naranja><b>Respaldo creado hoy<br><br><span class=texto>$actual</span></b></div>"; 
} ?>
</form>	
<?php } 


function respaldar_secc_2($seccion) {
	$hoy = date("Y-m-d");
	$s_1 = "update configuracion set valor='$hoy' where variable='last_backup'";
	$r_1 = mysql_query($s_1);
		
    $db 	= get_config('base_datos'); 
	$ruta   = get_config('ruta_respaldos'); 
	$pass   = get_config('password'); 
	$user   = get_config('usuario'); 
	$file   = $ruta.$db."_".$seccion."_".date("Ymd").".sql"; 
	$cadena = "-u".$user." -p".$pass." ".$db;
	
	switch($seccion) {
		case "hist_2011" : $cadena.= " 2011_aut_bitacora 2011_autorizaciones 2011_scrap_codigos 2011_scrap_folios 2011_scrap_partes "; break; 
		case "hist_2012" : $cadena.= " 2012_aut_bitacora 2012_autorizaciones 2012_scrap_codigos 2012_scrap_folios 2012_scrap_partes "; break;
		case "hist_2013" : $cadena.= " 2013_aut_bitacora 2013_autorizaciones 2013_scrap_codigos 2013_scrap_folios 2013_scrap_partes "; break;
		case "hist_2014" : $cadena.= " 2014_aut_bitacora 2014_autorizaciones 2014_scrap_codigos 2014_scrap_folios 2014_scrap_partes "; break;
		case "hist_2015" : $cadena.= " 2015_aut_bitacora 2015_autorizaciones 2015_scrap_codigos 2015_scrap_folios 2015_scrap_partes "; break;
		case "catalogos" : $cadena.= " apd areas causas causa_codigo codigo_scrap codigo_scrap_depto codigo_scrap_proy configuracion defectos ";
						   $cadena.= " defecto_causa def_proyecto divisiones estaciones est_proyecto lineas lineas_proy oi_especial ";
						   $cadena.= " motivos_cancel motivos_sap plantas profit_center proyectos proy_ubicacion segmentos unidades vendors "; break; 
		case "usuarios"	 : $cadena.= " acceso autorizadores capturistas empleados encabezados supervisores filtros mails reportes capt_merma "; break; 
		case "folios"	 : $cadena.= " aut_bitacora autorizaciones scrap_codigos scrap_folios scrap_partes scrap_porques scrap_codigos_tmp ";
						   $cadena.= " scrap_folios_tmp scrap_partes_tmp scrap_partes_35 scrap_partes_095 "; break; 
		case "materiales": $cadena.= " batch_ids batch_id numeros_parte "; break; 
		case "partes"	 : $cadena.= " partes_padre "; break; 	
		case "temporales": $cadena.= " tmp_batch tmp_batch_id tmp_defectos tmp_docto_sap tmp_estaciones tmp_lineas tmp_numeros tmp_oi_especial tmp_partes ";
						   $cadena.= " tmp_pc tmp_vendors "; break; 
		case "log"		 : $cadena.= " log_sistema "; break; 			   
	}
	$cadena.= " > ".$file; 
    $output = shell_exec("C:\AppServ\MySQL\bin\mysqldump.exe ".$cadena); // ejemplo windows
    //$output = shell_exec("/usr/bin/mysqldump ".$cadena); // ejemplo linux
	echo "<script>alert('Respaldo de $seccion finalizado!');</script>";
}	

function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}


function scripts_mantto_1() { ?>
<form action="?op=scripts_mantto_2" method="post" enctype="multipart/form-data">	
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo TXT con scripts de mantenimiento</caption>
<tbody>
<tr>
	<td>Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="submit" value="Ejecutar" class="submit"></div>
</form>
<?php } 


function scripts_mantto_2($archivo,$archivo_name) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = "temporal_.".$pext;
	$nom_final = $r_server.$nombre; 
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			scripts_mantto_1(); exit; }
		else { insert_txt($nombre); }	
	}
}

function insert_txt($nombre) { 
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);

   $archivo = file($d_['valor'].$nombre); 
   $lineas  = count($archivo); $j=1;
   for($i=0;$i<$lineas;$i++){ 
   	if(trim($archivo[$i])!='') { 
		$r_1 = mysql_query($archivo[$i]);
   		echo "<div class='texto' style='margin-left:150px;' align='left'>$j --> $archivo[$i]</div>";
	  $j++; } }
}	


function errores_1() { 
	$s_ = "select valor from configuracion where variable='error_display'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	$estado = $d_['valor']; ?>
<form action="?op=scripts_mantto_2" method="post" enctype="multipart/form-data">	
<table align="center" class="tabla" cellpadding="0" cellspacing="5" width="350">
<caption>Estado del desplegado de errores</caption>
<tbody>
<tr>
	<td width="120" align="center">Apagado</td>
	<td align="center">
    <?php if($estado=="SI") { ?>
    	<a href="?op=errores_2&estado=NO"><img src="../imagenes/switch_on.png" border="0"></a><?php } ?>
    <?php if($estado=="NO") { ?>
    	<a href="?op=errores_2&estado=SI"><img src="../imagenes/switch_off.png" border="0"></a><?php } ?>
    </td>
    <td width="120" align="center">Prendido</td>
</tr>
</tbody>
</table>
<?php }


function errores_2($estado) {
	if($estado=='SI') {
		error_reporting(E_ALL);
		ini_set('display_errors','On'); 
	} else { 
		error_reporting(0);
		ini_set('display_errors','Off'); 
	}
	$s_ = "update configuracion set valor='$estado' where variable='error_display'";
	$r_ = mysql_query($s_); 	
} 

function mantenimiento(){
	require_once("mantto.php"); mantto('1');	
}?>