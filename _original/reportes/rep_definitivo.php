<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); } ?>
<script>
function validar(destino) {
var lleno = 0; 
	if(form1.fechai.value!='' && form1.fechaf.value!='') { lleno++; }
	if(form1.buscar[0].value!='' && form1.filtros[0].value!='') { lleno++; }
	if(form1.buscar[1].value!='' && form1.filtros[1].value!='') { lleno++; }
	if(form1.buscar[2].value!='' && form1.filtros[2].value!='') { lleno++; }
	if(lleno<=0) { 
		alert('Seleccione un rango de fechas'); 
		return; }
	else { 
		if(destino=='excel') { 
			form1.action='excel_reportes.php?op=definitivo';
			form1.submit();	
			form1.action='?op=listado';	}
		else { 
			form1.action='?op=reporte';	
			form1.submit(); }	
	}		
}

function guardar() {
	form1.target='_blank';
	form1.action='reporte_sap.php';
	form1.submit();	
	form1.target='_self';
	form1.action='?op=listado';
}

function filtro_oes(){
	form1.action='?op=listado';
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','definitivo'); ?></td>
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
			case "listado"		:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$division,$tipo,$buscar,$filtros); break;							
			default				:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$division,$tipo,$buscar,$filtros); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="310">REPORTE DEFINITIVO DE SCRAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte Definitivo de Scrap] body=[Este es el reporte definitivo donde se muestra todo scrap aprobado en el día seleccionado por cada uno de los departamentos.<br><br>Puede imprimir este reporte exportándolo directamente a excel.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	


function listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$division,$tipo,$buscar,$filtros) {
if(!$aplica_oes) $aplica_oes = 'no'; 
if(!$fechai)   $fechai   = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
if(!$fechaf)   $fechaf   = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	
if($proy_add!='') {
	$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')";
	$r_ = mysql_query($s_); }
if($proy_del!='') {
	if($proy_del=='del_all') { 
		$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]'"; 
		$r_ = mysql_query($s_); }
	else { 
	$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; 
	$r_ = mysql_query($s_); }	
} ?>
    
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
        <td align="center" width="100" bgcolor="#E6E6E6">División</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Tipo Reporte</td>
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
    <tr><td align="center">
	<?php $r_1 = mysql_query(get_proyectos_out()); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_add" class="texto" style="width:180px;" onchange="submit();">
		<option value="">Sin filtro (<?php echo $n_1;?>)</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del" class="texto" style="width:180px;" onchange="submit();">
	  	<option value="">En filtro (<?php echo $n_1;?>)</option>
        <option value="del_all" class="quitar">Quitar Todos</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
    </tr></table></td>
	<td align="center">
	<?php $s_1 = "select divisiones.id, divisiones.nombre from divisiones, proyectos where divisiones.activo!='2' and divisiones.id = proyectos.id_division ";
			$s_2 = "select id_division from autorizadores where id_emp='$_SESSION[IDEMP]'";
			$r_2 = mysql_query($s_2); $i=0;
			if(mysql_num_rows($r_2)>0) {
				$s_1.= "and (";
				while($d_2=mysql_fetch_array($r_2)) { 
					if($d_2['id_division']!='0' && $d_2['id_division']!='%') { 
						$s_1.= "id_division='$d_2[id_division]' or ";
						$divisiones[$i] = $d_2['id_division']; $i++; }
				} $s_1 = substr($s_1,0,-4).")"; }	
		  $s_1.= "group by divisiones.id order by nombre";
	      $r_1 = mysql_query($s_1); ?>
	<select name="division" class="texto" style="width:120px;">
	  	<option value="%">Todas</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
		<?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>
	<td align="center">
	  <select name="tipo" class="texto" style="width:100px;">
		<option value="%" <?php if($tipo=="%"){?> selected="selected" <?php } ?>>Todos</option>
        <option value="1" <?php if($tipo=="1"){?> selected="selected" <?php } ?>>MB1A con OI</option>
        <option value="2" <?php if($tipo=="2"){?> selected="selected" <?php } ?>>MB1A sin OI</option>
        <option value="3" <?php if($tipo=="3"){?> selected="selected" <?php } ?>>ZSCR</option>        
	</select></td>
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where definitivo='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[2]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[2]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
		<td align="center"><input type="radio" value="no" name="aplica_oes" <?php if($aplica_oes=='no') { ?>checked="checked"<?php } ?> onclick='filtro_oes();'>&nbsp;&nbsp;NO</td>	
	</tr>         
</table>
<div align="center" class="texto"><br>
	<input type="button" value="Buscar" class="submit" onclick="validar('reporte');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="validar('excel');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Enviar Datos a SAP" class="submit" onclick="guardar();">
</div><br></form>

<?php if($fechai!='' && $fechaf!='' && $tipo!='' && $division!='') {
    $s_f = "select folios.*, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, partes.ubicacion, ";
	$s_f.= "partes.docto_sap, partes.deficit, partes.tipo, partes.padre, partes.batch_id, partes.serial_unidad, partes.tipo_sub ";
	$s_f.= "from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio "; 
	$s_f.= "and autorizaciones.no_folio = folios.no_folio and folios.activo='1' and folios.status='1' and partes.docto_sap!='0' and partes.docto_sap!='' ";
	$s_f.= "and partes.no_parte = numeros_parte.nombre  ";
	if($division=='%') { 
		if(count($divisiones)>0) {
			$s_f.= " and (";
			for($i=0;$i<count($divisiones);$i++) {
				$s_f.= " id_division='$divisiones[$i]' or "; 
			} $s_f = substr($s_f,0,-4).") "; 
		}	
	}
	else { $s_f.= " and id_division='$division' "; }

	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	}
	if($fechai!='' && $fechaf!='') 	 { $s_f .= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	if($aplica_oes=='si') { $s_f.= " and autorizaciones.depto = 'oes' "; } 
	if($_SESSION["TYPE"]!='autorizador') { $s_f.= filtros_capturista();  }
	if($_SESSION["TYPE"]=='autorizador') { $s_f.= filtros_autorizador(); } 
	$r_1 = mysql_query($s_f); 
	$tot = mysql_num_rows($r_1); ?>
    
<table align="center" class="tabla" border="0">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td colspan="6" align="center"><b>Autorizaciones de SCRAP</b></td>
	</tr>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150">LPL</td>
		<td align="center" width="150">Manufactura</td>
		<td align="center" width="150">FFC</td>
		<td align="center" width="150">LO</td>
		<td align="center" width="150">LO Almacén</td>
		<td align="center" width="150">Inventarios</td>
	</tr>
</thead>	
<tbody>
	<tr bgcolor="#F7F7F7" height="20">
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='lpl' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='prod' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='ffc' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='lo' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='loa' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
		<td align="center"><?php 
			$s_2 = "select autorizaciones.empleado from autorizaciones, (".$s_f.") as general where autorizaciones.no_folio = general.no_folio and ";
			$s_2.= "autorizaciones.depto='inv' and autorizaciones.status='1' group by empleado order by empleado";
			$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) {
				echo $d_2['empleado']."<br>"; } ?></td>
	</tr>
</tbody>	
</table><br>

<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td>&nbsp;</td>
	<td align="center">No.Item</td>
    <td align="center">Fecha</td>
	<td align="center">Txs SAP</td>
	<td align="center">Mov</td>
	<td align="center">Código Scrap</td>
	<td align="center">Cod. Causa Original</td>        
	<td align="center">O.I.</td>
	<td align="center">Parte Padre</td>
	<td align="center">Batch ID</td>
	<td align="center">No.Parte</td>
	<td align="center">Cantidad</td>
	<td align="center">Reason Code</td>
	<td align="center">Descripción</td>
	<td align="center">Info.Obl.</td>
	<td align="center">No.Docto.SAP</td>
	<td align="center">Tipo Material</td>
	<td align="center">Tipo Sub</td>
	<td align="center">Valor</td>
</tr>
</thead>
<tbody>
<?php 
	if($tipo=='1' || $tipo=='%') { //Sección 1 reporte de TXS SAP con MB1A con Orden Interna
		$s_1 = $s_f." and txs_sap='MB1A' and orden_interna!='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre"; 
 		$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"19\"></td></tr>";
    while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td><a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
        	<img src="../imagenes/history.gif" border="0" alt="Historial"></a></td>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo'];?></td>    	<td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0'&& $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB') { echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
    </tr>	
	<?php } } } if($tipo=='2' || $tipo=='%') { //Sección 2 reporte de TXS SAP con MB1A sin Orden Interna
		$s_2 = $s_f." and txs_sap='MB1A' and orden_interna='NA' group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_2); 
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"19\"></td></tr>";	
    while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td><a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
        	<img src="../imagenes/history.gif" border="0" alt="Historial"></a></td>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>    	<td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
        <td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0'&& $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB') { echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
	</tr>	
	<?php } } } if($tipo=='3' || $tipo=='%') { //Sección 3 reporte de TXS SAP con ZSCR
		$s_3 = $s_f." and txs_sap='ZSCR' and (orden_interna='NA' or orden_interna='0') group by partes.id order by mov_sap, codigo_scrap, orden_interna, padre";
 		$r_1 = mysql_query($s_3); 
	if(mysql_num_rows($r_1)>0) { 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"19\"></td></tr>";	
	while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td><a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>">
        	<img src="../imagenes/history.gif" border="0" alt="Historial"></a></td>
		<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
		<td align="center"><?php $original=data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>	
        <td align="left"><?php echo $d_1['orden_interna']; ?></td>
		<td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="left"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="center"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="left"><?php if($d_1['docto_sap']!='0'&& $d_1['docto_sap']!='') { echo $d_1['docto_sap']; } else { echo ""; }?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
		<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
	</tr>	
	<?php } } } ?>
</tbody>
</table><br><br>
<?php } } ?>