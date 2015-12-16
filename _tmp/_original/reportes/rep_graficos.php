<?php include("../header.php"); ?>
<script>
function graficar(ruta) {
var faltan=0;
if(form1.grafica.value=='cantidades' || form1.grafica.value=='costos' || form1.grafica.value=='defectos') { 
	if(form2.defecha.value>form2.afecha.value) {
		alert('La fecha inicial no pueden ser mayor a la final'); 
		faltan++; }
} else { 
	if(form2.deanio.value==form2.aanio.value) {
		if(parseInt(form2.desemana.value)>parseInt(form2.asemana.value)) {
		alert('La semana inicial no pueden ser mayor a la final'); 
		faltan++; }
	}	
	if(parseInt(form2.deanio.value)>parseInt(form2.aanio.value)) {
		alert('El año inicial no pueden ser mayor al final'); 
		faltan++; }
}
if(form1.grafica.value=='defectos') {
	if(form2.ventas.value=='') {
		alert('Debe ingresar el valor para las ventas');
		faltan++; }
	if(form2.top.value=='') {
		alert('Debe ingresar el valor para el top de defectos');
		faltan++; }
}			
if(faltan<=0) { 
	form2.action='graficas.php?op=ver_reporte_'+ruta;
	form2.target='_blank';
	form2.submit();
	form2.action='rep_graficos.php?op=filtros_'+ruta+'&grafica='+form1.grafica.value;
	form2.target='_self'; }
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','graficos'); ?></td>
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
        <?php deldir('charts'); ?>
        <div style="margin-left:100px;" class="titulo">
        <table align="center" cellpadding="10" cellspacing="0" border="0">
        <tr>
            <td class="titulo" width="300">REPORTES GRÁFICOS DE SCRAP</td>
            <td align="left">&nbsp;
            <span title="header=[&nbsp;&nbspReportes Gráficos de SCRAP] body=[Seleccione cualquiera de los filtros que desea aplicar al reporte gráfico]">
            <img src="../imagenes/question.gif" width="20" height="20"></span>	
            </td>	
        </tr>
        </table></div><hr>
        <!-- -->
        <form action="rep_graficos.php?borrar=1" method="post" name="form1">
        <table align="center" class="tabla" border="0">
            <tr><td colspan="4" align="center" bgcolor="#CCCCCC"><b>Tipo de Reporte</b></td></tr>
            <tr height="30">
                <td align="left" width="80">&nbsp;&nbsp;Tipo</td>
                <td align="left" width="210" colspan="3">
                <select name="grafica" class="texto" style="width:200px;" onchange="submit();">
                <option valie=""></option>
                <option value="cantidades" <?php if($grafica=='cantidades'){?> selected="selected"<?php }?>>Gráfica Barras (Piezas)</option>
                <option value="costos" <?php if($grafica=='costos'){?> selected="selected"<?php }?>>Gráfica Barras (Montos)</option>
                <option value="series1" <?php if($grafica=='series1'){?> selected="selected"<?php }?>>Gráfica serie de tiempo (Piezas)</option>
                <option value="series2" <?php if($grafica=='series2'){?> selected="selected"<?php }?>>Gráfica serie de tiempo (Montos)</option>
                <option value="series3" <?php if($grafica=='series3'){?> selected="selected"<?php }?>>Porcentajes</option>
                <option value="defectos" <?php if($grafica=='defectos'){?> selected="selected"<?php }?>>Defectos</option>
                </select>			
                </td>	
            </tr>
        </table>
        </form>
		<?php if($borrar=='1') { 
				$s_ = "delete from filtros where id_emp='$_SESSION[IDEMP]'";
				$r_ = mysql_query($s_); }			  
			  if($grafica!="defectos") { 
				filtros_1($defecha,$afecha,$desemana,$asemana,$deanio,$aanio,$grafica,$tipo,$planta_add,$planta_del,$div_add,$div_del,$area_add,$area_del,$est_add,$est_del,
				$proy_add,$proy_del,$parte,$reason); }
			  else {
				filtros_2($defecha,$afecha,$grafica,$ventas,$top,$div_add,$div_del,$proy_add,$proy_del,$def_add,$def_del); } ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");

 

function llenar_filtros($grafica,$planta_add,$planta_del,$div_add,$div_del,$area_add,$area_del,$est_add,$est_del,$proy_add,$proy_del,$def_add,$def_del){

if($grafica!="defectos") { 
	//PLANTAS
	if($planta_add!='') {
		if($planta_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='plantas' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
			$s_ = "select * from plantas where activo!='2' order by nombre";
			$r_ = mysql_query($s_);
			while($d_=mysql_fetch_array($r_)) {
				$s_1 = "insert into filtros values('','plantas','$d_[id]','$_SESSION[IDEMP]')";
				$r_1 = mysql_query($s_1);	}
		} else { 		
		$s_ = "insert into filtros values('','plantas','$planta_add','$_SESSION[IDEMP]')";
		$r_ = mysql_query($s_); }
	} 	
	if($planta_del!='') {
		if($planta_del=='del_all'){
			$s_ = "delete from filtros where filtro='plantas' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {		
			$s_ = "delete from filtros where filtro='plantas' and id_emp='$_SESSION[IDEMP]' and valor='$planta_del'"; 
			$r_ = mysql_query($s_);
	} }
	//DIVISIONES
	if($div_add!='') {
		if($div_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
			$s_1 = "select * from divisiones where activo!='2' ";
				  $s_ = "select * from filtros where filtro='plantas' and id_emp='$_SESSION[IDEMP]'";
				  $r_ = mysql_query($s_);
				  if(mysql_num_rows($r_)>0) {
					$s_1.= " and (";
					while($d_=mysql_fetch_array($r_)) {
						$s_1.= "id_planta = '$d_[valor]' or "; }
			$s_1 = substr($s_1,0,-4).")" ;	}
			$s_1.= "order by nombre";		
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','divisiones','$d_1[id]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else { 	
		$s_ = "insert into filtros values('','divisiones','$div_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($div_del!='') {
	if($div_del=='del_all'){
			$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {		
		$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]' and valor='$div_del'";
		$r_ = mysql_query($s_); 
	} }	
	//PROYECTOS
	if($proy_add!='') {
		if($proy_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
			$s_1 = "select * from proyectos where activo!='2' ";
				  $s_ = "select * from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
				  $r_ = mysql_query($s_);
				  if(mysql_num_rows($r_)>0) {
					$s_1.= " and (";
					while($d_=mysql_fetch_array($r_)) {
						$s_1.= "id_division = '$d_[valor]' or "; }
			$s_1 = substr($s_1,0,-4).")" ;	}
			$s_1.= "order by nombre";
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','proyectos','$d_1[id]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else {
		$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($proy_del!='') {
	if($proy_del=='del_all'){
			$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {	
		$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'";
		$r_ = mysql_query($s_); }
	}
	//AREAS
	if($area_add!='') {
		if($area_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
			$s_ = "select * from areas where activo!='2' order by nombre";
			$r_ = mysql_query($s_);
			while($d_=mysql_fetch_array($r_)) {
				$s_1 = "insert into filtros values('','areas','$d_[id]','$_SESSION[IDEMP]')";
				$r_1 = mysql_query($s_1);	}
		} else { 		
		$s_ = "insert into filtros values('','areas','$area_add','$_SESSION[IDEMP]')";
		$r_ = mysql_query($s_); }
	} 	
	if($area_del!='') {
		if($area_del=='del_all'){
			$s_ = "delete from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {		
			$s_ = "delete from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]' and valor='$area_del'"; 
			$r_ = mysql_query($s_);
	} }
	//TECNOLOGÍAS
	if($est_add!='') {
		if($est_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='estaciones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
			$s_1 = "select * from estaciones where activo!='2' ";
				  $s_ = "select * from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]'";
				  $r_ = mysql_query($s_);
				  if(mysql_num_rows($r_)>0) {
					$s_1.= " and (";
					while($d_=mysql_fetch_array($r_)) {
						$s_1.= "id_area = '$d_[valor]' or "; }
			$s_1 = substr($s_1,0,-4).")" ;	}
			$s_1.= "order by nombre";		
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','estaciones','$d_1[id]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else { 	
		$s_ = "insert into filtros values('','estaciones','$est_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($est_del!='') {
	if($est_del=='del_all'){
			$s_ = "delete from filtros where filtro='estaciones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {		
		$s_ = "delete from filtros where filtro='estaciones' and id_emp='$_SESSION[IDEMP]' and valor='$est_del'";
		$r_ = mysql_query($s_); 
	} }	
} else { 
	//DIVISIONES
	if($div_add!='') {
		if($div_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
				$s_1 = "select folios.id_division, folios.division from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
				$s_1.= "filtro='divisiones' and folios.division = filtros.valor) and folios.no_folio = partes.no_folio and division!='' ";
				$s_1.= "group by folios.division order by division";
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','divisiones','$d_1[division]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else { 	
		$s_ = "insert into filtros values('','divisiones','$div_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($div_del!='') {
	if($div_del=='del_all'){
			$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {		
		$s_ = "delete from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]' and valor='$div_del'";
		$r_ = mysql_query($s_); 
	} }	
	//PROYECTOS
	if($proy_add!='') {
		if($proy_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
				$s_1 = "select folios.id_proyecto, folios.proyecto from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
				$s_1.= "filtro='proyectos' and folios.proyecto = filtros.valor) and folios.no_folio = partes.no_folio and proyecto!='' ";
				$s_ = "select * from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
				$r_ = mysql_query($s_);
				  if(mysql_num_rows($r_)>0) {
					$s_1.= " and (";
					while($d_=mysql_fetch_array($r_)) {
						$s_1.= "folios.division like '$d_[valor]' or "; }
					$s_1 = substr($s_1,0,-4).")" ;	}
			   $s_1.= "group by folios.proyecto order by proyecto";
			   $r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','proyectos','$d_1[proyecto]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else {
		$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($proy_del!='') {
	if($proy_del=='del_all'){
			$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {	
		$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'";
		$r_ = mysql_query($s_); }
	}
	//DEFECTOS
	if($def_add!='') {
		if($def_add=='add_all'){ 
			$s_ = "delete from filtros where filtro='defectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
				$s_1 = "select folios.id_defecto, folios.defecto from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
				$s_1.= "filtro='defectos' and folios.defecto = filtros.valor) and folios.no_folio = partes.no_folio and defecto!='' ";
				$s_ = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
				$r_ = mysql_query($s_);
				  if(mysql_num_rows($r_)>0) {
					$s_1.= " and (";
					while($d_=mysql_fetch_array($r_)) {
						$s_1.= "folios.proyecto like '$d_[valor]' or "; }
					$s_1 = substr($s_1,0,-4).")" ;	}
			   $s_1.= "group by folios.defecto order by defecto";
			   $r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {
				$s_ = "insert into filtros values('','defectos','$d_1[defecto]','$_SESSION[IDEMP]')";
				$r_ = mysql_query($s_);	}
		} else {
		$s_ = "insert into filtros values('','defectos','$def_add','$_SESSION[IDEMP]')"; 
		$r_ = mysql_query($s_); }
	}	
	if($def_del!='') {
	if($def_del=='del_all'){
			$s_ = "delete from filtros where filtro='defectos' and id_emp='$_SESSION[IDEMP]'";
			$r_ = mysql_query($s_);
		} else {	
		$s_ = "delete from filtros where filtro='defectos' and id_emp='$_SESSION[IDEMP]' and valor='$def_del'";
		$r_ = mysql_query($s_); }
	} 
} }


function filtros_1($defecha,$afecha,$desemana,$asemana,$deanio,$aanio,$grafica,$tipo,$planta_add,$planta_del,$div_add,$div_del,$area_add,$area_del,$est_add,$est_del,$proy_add,$proy_del,$parte,$reason) { 

	llenar_filtros($grafica,$planta_add,$planta_del,$div_add,$div_del,$area_add,$area_del,$est_add,$est_del,$proy_add,$proy_del,$def_add,$def_del);

	if(!$desemana)      $desemana   = date("W");	
	if(!$asemana)       $asemana    = date("W");	
	if(!$deanio)		$deanio     = date("Y");
	if(!$aanio)			$aanio      = date("Y");
	if(!$defecha)		$defecha	= date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));
	if(!$afecha)		$afecha		= date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); ?>

<form action="?op=filtros_1" method="post" name="form2">
<input type="hidden" name="grafica" value="<?php echo $grafica;?>">
<table align="center" class="tabla" border="0">
	<tr><td colspan="4" align="center" bgcolor="#CCCCCC"><b>Parámetros de la Gráfica</b></td></tr>
	<?php if($grafica=='cantidades' || $grafica=='costos' || !$grafica) { ?>
	<tr height="20">
		<td align="left" width="80">&nbsp;&nbsp;Eje X's</td>
		<td align="left" width="210" colspan="3">
		<select name="tipo" class="texto" style="width:200px;" onchange="submit();">
		<option valie=""></option>
		<option value="division" <?php if($tipo=='division'){?> selected="selected"<?php }?>>Divisiones</option>
		<option value="proyecto" <?php if($tipo=='proyecto'){?> selected="selected"<?php }?>>Proyectos</option>
		<option value="area" <?php if($tipo=='area'){?> selected="selected"<?php }?>>Áreas</option>
		<option value="estacion" <?php if($tipo=='estacion'){?> selected="selected"<?php }?>>Tecnologías</option>
		<option value="modelo" <?php if($tipo=='modelo'){?> selected="selected"<?php }?>>Números de Parte</option>
		</select>			
		</td>	
	</tr>	
	<tr>	
		<td>&nbsp;&nbsp;De fecha</td>
		<td colspan="3">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'defecha',
		'valor': '<?php echo $defecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>		
		</td>
	</tr>
	<tr>	
		<td>&nbsp;&nbsp;A Fecha</td>
		<td colspan="3">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'afecha',
		'valor': '<?php echo $afecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>		
		</td>	
	</tr>
<?php } else { ?>
	<tr>
		<td>&nbsp;&nbsp;De Semana</td>
		<td width="60"><select name="desemana" class="texto" style="width:50px;">
			<?php for($i=1;$i<=52;$i++) {?>
			<option value="<?php echo $i;?>" <?php if($desemana==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
			<?php } ?></select></td>
		<td width="55">Del Año</td>
		<td width="60"><select name="deanio" class="texto" style="width:70px;">
			<?php for($i=2009;$i<=2013;$i++) {?>
			<option value="<?php echo $i;?>" <?php if($deanio==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
			<?php } ?></select></td>			
	</tr>		
	<tr>
		<td>&nbsp;&nbsp;A Semana</td>
		<td width="60"><select name="asemana" class="texto" style="width:50px;">
			<?php for($i=1;$i<=52;$i++) {?>
			<option value="<?php echo $i;?>" <?php if($asemana==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
			<?php } ?></select></td>
		<td width="55">Del Año</td>
		<td width="60"><select name="aanio" class="texto" style="width:70px;">
			<?php for($i=2009;$i<=2013;$i++) {?>
			<option value="<?php echo $i;?>" <?php if($aanio==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
			<?php } ?></select></td>			
	</tr>
<?php } ?>
	<tr>
    	<td>&nbsp;&nbsp;No.Parte</td>
		<td colspan="3"><input type="text" class="texto" name="parte" value="<?php echo $parte;?>" size="34"></td>
    </tr>
	<tr>
    	<td align="center"><input type="checkbox" name="reason" value="1" <?php if($reason==1){?> checked="checked"<?php } ?>></td>
		<td colspan="3">Mostrar Reason Code</td>
    </tr>    
</table><br>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr height="25">
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Plantas</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Plantas Reales</td>
			<td align="center" bgcolor="#F2F2F2">Plantas en Filtro</td>
		</tr>
		<td align="center">
		<?php $s_1 = "select * from plantas where not exists (select * from filtros where filtro='plantas' and id_emp = ";
	     	 $s_1.= " '$_SESSION[IDEMP]' and plantas.id = filtros.valor) and activo!='2' order by nombre"; 
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	 	<select name="planta_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select plantas.nombre, filtros.* from filtros, plantas where plantas.id = filtros.valor and filtros.filtro = ";
		      $s_1.= "'plantas' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="planta_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>
	<td width="20">&nbsp;</td>
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Divisiones</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Divisiones Reales</td>
			<td align="center" bgcolor="#F2F2F2">Divisiones en Filtro</td>
		</tr>
		<td align="center">
		<?php $s_1 = "select * from divisiones where not exists (select * from filtros where filtro='divisiones' and ";
		      $s_1.= " id_emp='$_SESSION[IDEMP]' and divisiones.id = filtros.valor) and activo!='2' ";
			  $s_ = "select * from filtros where filtro='plantas' and id_emp='$_SESSION[IDEMP]'";
			  $r_ = mysql_query($s_);
			  if(mysql_num_rows($r_)>0) {
			  	$s_1.= " and (";
				while($d_=mysql_fetch_array($r_)) {
					$s_1.= "id_planta = '$d_[valor]' or "; }
				$s_1 = substr($s_1,0,-4).")" ;	}
		   $s_1.= "order by nombre";
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="div_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select divisiones.nombre, filtros.* from filtros, divisiones where divisiones.id = filtros.valor and ";
		      $s_1.= "filtros.filtro='divisiones' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="div_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>	
	<td width="20">&nbsp;</td>
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Proyectos</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Proyectos Reales</td>
			<td align="center" bgcolor="#F2F2F2">Proyectos en Filtro</td>
		</tr>
		<td align="center">
		<?php $s_1 = "select * from proyectos where not exists (select * from filtros where filtro='proyectos' and ";
		      $s_1.= " id_emp='$_SESSION[IDEMP]' and proyectos.id = filtros.valor) and activo!='2' ";
			  $s_ = "select * from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			  $r_ = mysql_query($s_);
			  if(mysql_num_rows($r_)>0) {
			  	$s_1.= " and (";
				while($d_=mysql_fetch_array($r_)) {
					$s_1.= "id_division = '$d_[valor]' or "; }
				$s_1 = substr($s_1,0,-4).")" ;	}
		   $s_1.= "order by nombre";
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="proy_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todos</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.id = filtros.valor and ";
		      $s_1.= "filtros.filtro='proyectos' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="proy_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todos</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>	
</tr>
<tr height="10"><td colspan="3">&nbsp;</td></tr>
<tr height="25">
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Áreas</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Áreas Reales</td>
			<td align="center" bgcolor="#F2F2F2">Áreas en Filtro</td>
		</tr>
		<td align="center">
		<?php $s_1 = "select * from areas where not exists (select * from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]'";
	     	 $s_1.= " and areas.id = filtros.valor) and activo!='2' order by nombre"; 
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	 	<select name="area_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select areas.nombre, filtros.* from filtros, areas where areas.id = filtros.valor and filtros.filtro = ";
		      $s_1.= "'areas' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	<select name="area_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>
	<td width="20">&nbsp;</td>
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Tecnologías</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Tecnologías Reales</td>
			<td align="center" bgcolor="#F2F2F2">Tecnologías en Filtro</td>
		</tr>
		<td align="center">
		<?php $s_1 = "select * from estaciones where not exists (select * from filtros where filtro='estaciones' and ";
		      $s_1.= " id_emp='$_SESSION[IDEMP]' and estaciones.id = filtros.valor) and activo!='2' ";
			  $s_ = "select * from filtros where filtro='areas' and id_emp='$_SESSION[IDEMP]'";
			  $r_ = mysql_query($s_);
			  if(mysql_num_rows($r_)>0) {
			  	$s_1.= " and (";
				while($d_=mysql_fetch_array($r_)) {
					$s_1.= "id_area = '$d_[valor]' or "; }
				$s_1 = substr($s_1,0,-4).")" ;	}
		   $s_1.= "order by nombre";
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="est_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select estaciones.nombre, filtros.* from filtros, estaciones where estaciones.id = filtros.valor and ";
		      $s_1.= "filtros.filtro='estaciones' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="est_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todas</option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>	
</tr>
</table>		
<div align="center"><br>
<?php if(!$grafica || ($grafica=='cantidades' && !$tipo) || ($grafica=='costos' && !$tipo)){ $dis = "disabled"; } ?>
<input type="button" value="Graficar" class="submit"  onclick="graficar('1');" <?php echo $dis;?>>
</div>
</form>
<?php }


function filtros_2($defecha,$afecha,$grafica,$ventas,$top,$div_add,$div_del,$proy_add,$proy_del,$def_add,$def_del) { 

	llenar_filtros($grafica,$planta_add,$planta_del,$div_add,$div_del,$area_add,$area_del,$est_add,$est_del,$proy_add,$proy_del,$def_add,$def_del);
	if(!$defecha)		$defecha	= date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));
	if(!$afecha)		$afecha		= date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); ?>

<form action="?op=filtros_2" method="post" name="form2">
<input type="hidden" name="grafica" value="<?php echo $grafica;?>">
<table align="center" class="tabla" border="0">
	<tr><td colspan="4" align="center" bgcolor="#CCCCCC"><b>Reporte Gráfico de Defectos</b></td></tr>
	<tr>	
		<td>&nbsp;&nbsp;De fecha</td>
		<td colspan="3">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'defecha',
		'valor': '<?php echo $defecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>		
		</td>
	</tr>
	<tr>	
		<td>&nbsp;&nbsp;A Fecha</td>
		<td colspan="3">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'afecha',
		'valor': '<?php echo $afecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>		
		</td>	
	</tr>
	<tr>
    	<td>&nbsp;&nbsp;Ventas</td>
		<td colspan="3"><input type="text" class="texto" name="ventas" value="<?php echo $ventas;?>" size="34"></td>
    </tr>
	<tr>
    	<td>&nbsp;&nbsp;Top Defectos:</td>
		<td colspan="3">
        <select name="top" class="texto" style="width:200px;">
        	<option vlaue=""></option>
            <option value="5" <?php if($top=='5'){?> selected="selected"<?php } ?>>5</option>
            <option value="10" <?php if($top=='10'){?> selected="selected"<?php } ?>>10</option>
            <option value="20" <?php if($top=='20'){?> selected="selected"<?php } ?>>20</option>
            <option value="25" <?php if($top=='25'){?> selected="selected"<?php } ?>>25</option>
        </select>      
        </td>
    </tr>   
</table><br>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr height="25">
	<td>
		<table align="center" border="0" class="tabla">
		<?php $s_1 = "select folios.id_division, folios.division from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
		      $s_1.= "filtro='divisiones' and folios.division = filtros.valor) and folios.no_folio = partes.no_folio and division!='' ";
		 	  $s_1.= "group by folios.division order by division";
		  	  $r_1 = mysql_query($s_1); 
	 	      $n_1 = mysql_num_rows($r_1); ?>
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Divisiones</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Divisiones Reales</td>
			<td align="center" bgcolor="#F2F2F2">Divisiones en Filtro</td>
		</tr>
		<tr>           
		<td align="center">
	 	 <select name="div_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['division'];?>"><?php echo $d_1['division'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select divisiones.nombre, filtros.* from filtros, divisiones where divisiones.nombre = filtros.valor and ";
		      $s_1.= "filtros.filtro='divisiones' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="div_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todas</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>	
	<td width="20">&nbsp;</td>
		<?php $s_1 = "select folios.id_proyecto, folios.proyecto from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
		      $s_1.= "filtro='proyectos' and folios.proyecto = filtros.valor) and folios.no_folio = partes.no_folio and proyecto!='' ";
			  $s_ = "select * from filtros where filtro='divisiones' and id_emp='$_SESSION[IDEMP]'";
			  $r_ = mysql_query($s_);
			  if(mysql_num_rows($r_)>0) {
			  	$s_1.= " and (";
				while($d_=mysql_fetch_array($r_)) {
					$s_1.= "folios.division like '$d_[valor]' or "; }
				$s_1 = substr($s_1,0,-4).")" ;	}
		   $s_1.= "group by folios.proyecto order by proyecto";
		   $r_1 = mysql_query($s_1); 
	 	   $n_1 = mysql_num_rows($r_1); ?>
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Proyectos</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Proyectos Reales</td>
			<td align="center" bgcolor="#F2F2F2">Proyectos en Filtro</td>
		</tr>
		<td align="center">
	 	 <select name="proy_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todos</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['proyecto'];?>"><?php echo $d_1['proyecto'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select proyectos.nombre, filtros.* from filtros, proyectos where proyectos.nombre = filtros.valor and ";
		      $s_1.= "filtros.filtro='proyectos' and id_emp='$_SESSION[IDEMP]' order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="proy_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todos</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>	
	<td width="20">&nbsp;</td>
		<?php $s_1 = "select folios.id_defecto, folios.defecto from scrap_folios as folios, scrap_partes as partes where not exists (select * from filtros where ";
		      $s_1.= "filtro='defectos' and folios.defecto = filtros.valor) and folios.no_folio = partes.no_folio and defecto!='' ";
			  $s_ = "select * from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'";
			  $r_ = mysql_query($s_);
			  if(mysql_num_rows($r_)>0) {
			  	$s_1.= " and (";
				while($d_=mysql_fetch_array($r_)) {
					$s_1.= "folios.proyecto like '$d_[valor]' or "; }
				$s_1 = substr($s_1,0,-4).")" ;	}
		   $s_1.= "group by folios.defecto order by defecto";
		   $r_1 = mysql_query($s_1);
	 	   $n_1 = mysql_num_rows($r_1); ?>
	<td>
		<table align="center" border="0" class="tabla">
		<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Filtro de Defectos</b></td></tr>
		<tr>
			<td align="center" bgcolor="#F2F2F2">Defectos Reales</td>
			<td align="center" bgcolor="#F2F2F2">Defectos en Filtro</td>
		</tr>
		<td align="center">
	 	 <select name="def_add" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="add_all" class="agregar">Agregar Todos</option>
			<?php $r_1 = mysql_query($s_1);
			      while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['defecto'];?>"><?php echo $d_1['defecto'];?></option><?php } ?>
		</select></td>	
		<td align="center">
		<?php $s_1 = "select defectos.nombre, filtros.* from filtros, defectos where defectos.nombre = filtros.valor and ";
		      $s_1.= "filtros.filtro='defectos' and id_emp='$_SESSION[IDEMP]' group by nombre order by nombre";
	 	  $r_1 = mysql_query($s_1);
	  	  $n_1 = mysql_num_rows($r_1); ?>
	 	 <select name="def_del" class="texto" style="width:150px;" onchange="submit();" size="3">
			<option value="del_all" class="quitar">Quitar Todos</option>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['nombre'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td>	
		</tr>
		</table>
	</td>   
</tr>
</table>		
<div align="center"><br>
<?php if(!$grafica){ $dis = "disabled"; } ?>
<input type="button" value="Graficar" class="submit" onclick="graficar('2');" <?php echo $dis;?>>
</div>
</form>
<?php } 


function deldir($dir){ 
    $current_dir = opendir($dir); 
    while($entryname = readdir($current_dir)){ 
        if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){ 
            deldir("${dir}/${entryname}");   
        }elseif($entryname != "." and $entryname!=".."){ 
            unlink("${dir}/${entryname}"); 
        } 
    } 
    closedir($current_dir); 
} ?>