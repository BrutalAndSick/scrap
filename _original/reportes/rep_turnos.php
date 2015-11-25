<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function exportar() {
	form1.action='excel_reportes.php?op=turnos';
	form1.submit();	
	form1.action='?op=listado';
}

function search() {
	form1.action='?op=listado&boton=1';
	form1.submit();	
}

function filtro_oes(){
	form1.action='?op=listado';
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_extras'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_extras','turnos'); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td>&nbsp;
		<!--Todo el contenido de cada página--->
		<?php menu_interno();
		   switch($op) {
			case "listado"		:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$pagina,$boton); break;							
			default				:	$s_1 = "select * from configuracion where variable='mantto_turnos'";
									$r_1 = mysql_query($s_1);
									$d_1 = mysql_fetch_array($r_1);
									if($d_1['valor']!=date("Y-m-d")){ mantto_turnos(); }
									listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$pagina,$boton); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="250">REPORTE POR TURNOS</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte por turnos] body=[Este es el reporte de turnos, donde se muestra la relación entre folios de scrap y el turno en que fueron creados.<br>Puede imprimir este reporte exportándolo directamente a excel.<br><br><b>Turno 4</b><br>(Dom,Lun,Mar / Dom,Lun,Mar,Mie 07:00 - 18:59)<br><b>Turno 5</b><br>(Dom,Lun,Mar / Dom,Lun,Mar,Mie 19:00 - 06:59)<br><b>Turno 6</b><br>(Mie,Jue,Vie,Sab / Jue,Vie,Sab 07:00 - 18:59)<br><b>Turno 7</b><br>(Mie,Jue,Vie,Sab / Jue,Vie,Sab 19:00 - 06:59)]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	

function listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$pagina,$boton) {
	$s_ = "delete from reporte_turnos where (emp='0' or emp='$_SESSION[IDEMP]')";
	$r_ = mysql_query($s_); 
	if(!$aplica_oes) $aplica_oes = 'no'; 
	if(!$fechai) $fechai = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
	if(!$fechaf) $fechaf = date("Y-m-d");
	if(!$pagina) $pagina = 1; 
	if(count($proy_add)>0) {
		for($i=0;$i<count($proy_add);$i++) {
		$s_1 = "insert into filtros values('','proyectos','$proy_add[$i]','$_SESSION[IDEMP]')";
		$r_1 = mysql_query($s_1); } }
	if(count($proy_del)>0) {
		for($i=0;$i<count($proy_del);$i++) {
		$s_1 = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del[$i]'"; 
		$r_1 = mysql_query($s_1); }	} ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables. Puede personalizar este reporte y conservar los cambios siempre que inicie sesión.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
    </tr>
    <tr>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechai',
		'valor': '<?php echo $fechai;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>
	<td align="center">
	  	<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechaf',
		'valor': '<?php echo $fechaf;?>'
		}
		new gCalendar(GC_SET_0);
		</script>	
	</td>
	<td align="center">
    <table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr><td align="left">
	<?php $r_1 = mysql_query(get_proyectos_out()); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="turnos_add[]" multiple="multiple" class="texto" id="turnos_add_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center"><input type="button" value="<< >>" onclick="submit()" style="width:50px;" class="submit"></td>
	<td align="left">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="turnos_del[]" multiple="multiple" class="texto" id="turnos_del_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
    </tr></table></td>
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[0]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[0]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
		<td align="center" width="110">Filtrar por OES?</td>
	</tr> 
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['1'];?>" name="buscar[1]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[1]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[1]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>
		<td align="center"><input type="radio" value="si" name="aplica_oes" <?php if($aplica_oes=='si') { ?>checked="checked"<?php } ?> onclick='filtro_oes();'>&nbsp;&nbsp;SI</td>	
	</tr>    
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['2'];?>" name="buscar[2]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where ver_filtros='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[2]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[2]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
		<td align="center"><input type="radio" value="no" name="aplica_oes" <?php if($aplica_oes=='no') { ?>checked="checked"<?php } ?> onclick='filtro_oes();'>&nbsp;&nbsp;NO</td>
	</tr>         
</table><br>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="center" valign="middle">
    	<input type="button" value="Buscar" class="submit" name="boton" onclick="search();" <?php if($boton==1){?> disabled='disabled' <?php } ?>></td>
    <td width="150" align="center" valign="middle">
		<input type="button" value="Exportar" class="submit" onclick="exportar();"></td>
</tr>
</table></form>

<?php $fecha_i = str_replace("-","",$fechai); $fecha_f = str_replace("-","",$fechaf);
	
	// INSERTO VALORES DE ACUERDO A LAS FECHAS SELECCIONADAS (1 MES POR DEFAULT)
	$s_r = "select * from reporte_turnos where emp='$_SESSION[IDEMP]' order by folio asc ";
	$r_r = mysql_query($s_r);
	if(mysql_num_rows($r_r)<=0){
		/*$s_ = "select folios.* from ".date("Y")."_scrap_folios as folios, scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where timer BETWEEN '$fecha_i%' and '$fecha_f%' ";
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)){
			$anio_r = substr($d_['timer'],0,4);
			$mes_r = substr($d_['timer'],4,2);
			$dia_r = substr($d_['timer'],6,2);
			$horas_r = substr($d_['timer'],8,2);
			$min_r = substr($d_['timer'],10,2);
			$seg_r = substr($d_['timer'],12,2);
			$fecha_r = $anio_r."-".$mes_r."-".$dia_r;
			$hora_r = $horas_r.":".$min_r.":".$seg_r;
			$folio = $d_['no_folio'];
			$s_1 = "select * from tmp_turnos where '$fecha_r' between fecha_1 and fecha_2";
			$r_1 = mysql_query($s_1);
			while($d_1 = mysql_fetch_array($r_1)){
				if($hora_r>="07:00" && $hora_r<="18:59"){
					$s_2 = "insert into reporte_turnos values ('','$_SESSION[IDEMP]','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";
					$s_2.= "'$d_1[turno_1]')";	
					$r_2 = mysql_query($s_2);
				} else {
					$s_2 = "insert into reporte_turnos values ('','$_SESSION[IDEMP]','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";	
					$s_2.= "'$d_1[turno_2]')";	
					$r_2 = mysql_query($s_2);
				}
			}
		}*/
		$s_ = "select folios.* from scrap_folios as folios, scrap_partes as partes, autorizaciones, numeros_parte where timer BETWEEN '$fecha_i%' and '$fecha_f%' ";
		$s_.= "and folios.no_folio = partes.no_folio and autorizaciones.no_folio = folios.no_folio and partes.no_parte = numeros_parte.nombre ";
		for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
			$s_f = substr($s_f,0,-3)." ) "; } 	
		}
		if($fechai!='' && $fechaf!='') 	 { $s_ .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
		if($aplica_oes=='si') { $s_.= " and autorizaciones.depto = 'oes' "; } 
		if($_SESSION["TYPE"]!='autorizador') { $s_.= filtros_capturista();  }
		if($_SESSION["TYPE"]=='autorizador') { $s_.= filtros_autorizador(); }
		$s_.= "group by folios.id order by folios.no_folio asc"; 
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)){
			$anio_r = substr($d_['timer'],0,4);
			$mes_r = substr($d_['timer'],4,2);
			$dia_r = substr($d_['timer'],6,2);
			$horas_r = substr($d_['timer'],8,2);
			$min_r = substr($d_['timer'],10,2);
			$fecha_r = $anio_r."-".$mes_r."-".$dia_r;
			$hora_r = $horas_r.":".$min_r;
			$folio = $d_['no_folio'];
			$s_1 = "select * from tmp_turnos where '$fecha_r' between fecha_1 and fecha_2";
			$r_1 = mysql_query($s_1);
			while($d_1 = mysql_fetch_array($r_1)){
				if($hora_r>="07:00" && $hora_r<="18:59"){
					$s_2 = "insert into reporte_turnos values ('','$_SESSION[IDEMP]', '$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]', ";
					$s_2.= "'$d_[apd]','$d_1[turno_1]')";
					$r_2 = mysql_query($s_2);
				} else {
					$s_2 = "insert into reporte_turnos values ('','$_SESSION[IDEMP]','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";	
					$s_2.= "'$d_1[turno_2]')";	
					$r_2 = mysql_query($s_2);
				}
			}
		}
	}
	
	$r_ = mysql_query($s_r);
	$tot = mysql_num_rows($r_);
	
    $pags = ceil($tot/100);
	$ini_ = ($pagina-1)*100; $i=1;$j=0;
	$ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
	$ruta.= "&buscar[1]=$buscar[1]&filtros[1]=$filtros[1]&buscar[2]=$buscar[2]&filtros[2]=$filtros[2]";
	  
if($tot>0) { ?>     
<table align="center" border="0" class="texto" cellpadding="0" cellspacing="0">
<tr>
	<td width="110" align="center" bgcolor="#BDBDBD" class="link_paginas"><?php echo $tot;?> Registros</td>
	<td align="center" valign="top" bgcolor="#BDBDBD">
    <table align="center" border="0" class="texto" cellpadding="0" cellspacing="2">
	<?php while($i<=$pags) { 
		if($j>=30) { echo "</tr>"; $j=0; }
	    if($j==0)  { echo "<tr height='20'>"; } 
		if($pagina==$i) { $color = '#FFBF00'; } else { $color = '#F2F2F2'; } ?>
    <td width="30" align="center" bgcolor="<?php echo $color;?>">
    	<a href="?op=listado<?php echo $ruta;?>&pagina=<?php echo $i;?>" class="link_paginas"><?php echo $i;?></a></td>
	<?php $i++; $j++; } ?>
    </table>
    </td>
</tr>    
</table><br><?php } ?>
      
<table align="center" class="tabla">
<tr bgcolor="#E6E6E6" height="20">
	<td rowspan="2">&nbsp;</td>
  	<td align="center" width="150">Folio</td>
	<td align="center" width="80">Fecha</td>
	<td align="center" width="80">Hora</td>  
	<td align="center" width="80">Turno</td>
	<td align="center" width="150">Empleado</td>
	<td align="center" width="100">APD</td>
</tr>
</thead>
<tbody>
<?php $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  
	  $s_r.= " limit $ini_, 100";
	  $r_1 = mysql_query($s_r); 
      while($d_1=mysql_fetch_array($r_1)) { 
		echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">"; ?>
	<td><a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['folio'];?>">
        	<img src="../imagenes/zoom.png" border="0" alt="Ver Boleta"></a></td>              
	<td align="center" class="small"><?php echo $d_1['folio'];?></td>
	<td align="center" class="small"><?php echo $d_1['fecha'];;?></td>
	<td align="center" class="small"><?php echo $d_1['hora'];?></td>
	<td align="center" class="small"><?php echo $d_1['turno'];?></td>
	<td align="left" class="small"><?php echo $d_1['empleado'];?></td>
	<td align="center" class="small"><?php echo $d_1['apd'];?></td>
</tr>
<?php } ?>
<tr bgcolor="#E6E6E6">
	<td align="center" colspan="7">&nbsp;</td>	 		
</tr>
</tbody>
</table><br><br>
<?php echo "<script>form1.boton.disabled=false;</script>"; } ?>