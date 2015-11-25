<?php include("../header.php");
      include('funciones.php');
      include('../mails.php'); 
	  include('filtros.php'); ?>
<script>
function aprobar(folio) {
var agree=confirm("¿Seguro que desea aprobar el folio "+folio+"?");
if (agree) {
	form1.action='?op=aprobar&folio='+folio;
	form1.submit();
}
else return false ;
}

function rechazar(folio) {
var agree=confirm("¿Seguro que desea rechazar el folio "+folio+"?");
if (agree) {
	form1.action='?op=rechazar&folio='+folio;
	form1.submit();
}
else return false ;
}

function cancelar(folio) {
var agree=confirm("¿Seguro que desea cancelar el folio "+folio+"?");
if (agree) {
	form1.action='?op=cancelar&folio='+folio;
	form1.submit();
}
else return false ;
}

function regresar() {
	form1.action='scrap_firmar.php?op=listado';
	form1.submit();	
}

function validar_comentario(seccion) {
	if(seccion=="cancelar") {
	if(form1.motivo.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.motivo.value='';
		alert('Es necesario seleccionar el motivo');
		form1.comentario.focus(); return; } }
	if(form1.comentario.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.comentario.value='';
		alert('Es necesario ingresar el comentario');
		form1.comentario.focus(); return; }
	form1.submit();	
}

function select_all(valor) {
	if(form1.todos.checked==true) {
	if(valor>1) { 	
		for(i=0;i<valor;i++) {
			if(form1.varios[i].disabled == false) { 
			form1.varios[i].checked = true; } } }
	else { 
			if(form1.varios[i].disabled == false) {
			form1.varios.checked = true; } }				
	}
	if(form1.todos.checked==false) {
	if(valor>1) { 	
		for(i=0;i<valor;i++) {
			if(form1.varios[i].disabled == false) {
			form1.varios[i].checked = false; } } }
	else { 
			if(form1.varios[i].disabled == false) {
			form1.varios.checked = false; } }				
	}			
}

function app_sel() {
var agree=confirm("¿Aprobar todos los folios seleccionados?");
if (agree) {
	form1.action='?op=app_sel';
	form1.submit();
}
else return false ;	
}

function rech_sel() {
var agree=confirm("¿Rechazar todos los folios seleccionados?");
if (agree) {
	form1.action='?op=rech_sel_1';
	form1.submit();
}
else return false ;	
}

function validar(destino) {
	if(destino=='excel') { 
		form1.action='excel_reportes.php?op=por_firmar';
		form1.submit();	
		form1.action='?op=listado';	}
	else { 
		form1.action='?op=listado';	
		form1.submit(); }	
}

function descargar() {
	//form1.target = '_blank';
	form1.action='excel_reportes.php?op=descargar';
	form1.submit();	
	//form1.target = '_self';
	form1.action='scrap_firmar.php?op=listado';	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_autorizar'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_autorizar',''); ?></td>
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
			/*Individual*/
			case "aprobar"			:	aprobar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$_GET['folio']);	  break;
			case "rechazar"			:	rechazar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$_GET['folio']);  break;	
			case "save_rechazar"	:	save_rechazar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio,$comentario); 
										break;	
			/*Masivo*/
			case "app_sel"			:	app_sel($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios); 		  		break;		
			case "rech_sel_1"		:	rech_sel_1($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios); 	  		break;
			case "rech_sel_2"		:	rech_sel_2($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios,$comentario); break;
			/*Cancelación*/
			case "cancelar"			:	cancelar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$_GET['folio']); break;
			case "save_cancelar"	:	save_cancelar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio,$comentario,$motivo); 
										break;
			/*Generales*/
			case "listado"			:	listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$tipo,$reason,$pagina); break;
			default					:	listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$tipo,$reason,$pagina); break;
			}?>			
		<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { 
	$s_ = "select * from configuracion where variable='archivo_oficial'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_); ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0" width="90%">
<tr>
	<td class="titulo" width="290">AUTORIZACIONES PENDIENTES</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbsp;Autorizaciones Pendientes] body=[Estas son sus autorizaciones pendientes de firmar.<br>Usted puede seleccionar si aprueba o rechaza el folio de scrap usando los botones.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	


function listado($fechai,$fechaf,$proy_add,$proy_del,$buscar,$filtros,$tipo,$reason,$pagina) {
	if(!$tipo) 	 $tipo   = 'todos'; 
	if(!$pagina) $pagina = 1; 
	
if(count($proy_add)>0) {
	for($i=0;$i<count($proy_add);$i++){
	if($proy_add[$i]!='' && $proy_add[$i]!='0') {
	$s_ = "insert into filtros values('','proyectos','$proy_add[$i]','$_SESSION[IDEMP]')";
	$r_ = mysql_query($s_); } } }

if(count($proy_del)>0) {
	for($i=0;$i<count($proy_del);$i++){
	if($proy_del[$i]!='' && $proy_del[$i]!='0') {
		$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del[$i]'"; 
		$r_ = mysql_query($s_); } } } ?>
    
<div align="center" class="aviso">Utilice los filtros para generar búsquedas. Al cambiar un filtro, haga clic en el botón Buscar para refrescar la información. El reporte muestra toda la información sobre folios pendientes de autorizar.</div>	
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>" />
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
        <td align="center" bgcolor="#E6E6E6">Agregue/quite proyectos</td>
        <?php if($_SESSION["DEPTO"]=='inv') { ?>
       		<td align="center" bgcolor="#E6E6E6">Tipo</td><?php } ?>
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
	<select name="proy_add[]" multiple="multiple" class="texto" id="proy_add_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center"><input type="button" value="<< >>" onclick="submit()" style="width:50px;" class="submit"></td>
	<td align="left">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del[]" multiple="multiple" class="texto" id="proy_del_">
		<option value="">Seleccionar Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
    </tr></table></td>
	<?php if($_SESSION["DEPTO"]=='inv') { ?>
    <td align="center">
	<select name="tipo" class="texto" style="width:120px;">
	  	<option value="todos" <?php if($tipo=='todos'){?> selected="selected"<?php } ?>>Todos</option>	
        <option value="aprobados" <?php if($tipo=='aprobados'){?> selected="selected"<?php } ?>>Aprobados</option>
        <option value="cancelados" <?php if($tipo=='cancelados'){?> selected="selected"<?php } ?>>Cancelados</option>
        <option value="pendientes" <?php if($tipo=='pendientes'){?> selected="selected"<?php } ?>>Pendientes</option>
        <option value="rechazados" <?php if($tipo=='rechazados'){?> selected="selected"<?php } ?>>Rechazados</option>
	</select></td><?php } ?>	
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where scrap_firmar='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[0]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[0]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr> 
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['1'];?>" name="buscar[1]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where scrap_firmar='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[1]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[1]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr>    
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['2'];?>" name="buscar[2]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where scrap_firmar='1' order by nombre"; 
	              $r_1 = mysql_query($s_1); ?>
		<select name="filtros[2]" class="texto" style="width:150px;" id="filtros">
	  		<option value=""></option>	
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['campo']; ?>" <?php if($filtros[2]==$d_1['campo']) { ?> selected="selected"<?php } ?>>
			<?php echo $d_1['nombre'];?></option><?php } ?>
		</select></td> 
	</tr>         
</table>
<div align="center" class="texto"><br>
	<input type="button" value="Buscar" class="submit" onclick="validar('reporte');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="validar('excel');">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="reason" value="1" <?php if($reason==1) {?> checked="checked"<?php } ?>>
	Mostrar Reason code
</div><br>

<table align="center" border="0" class="texto">
<tr>
	<td width="20" align="center"><img src="../imagenes/zoom.png" /></td>
   	<td width="60" align="left">Ver Boleta</td> 
    <td width="20" align="center">|</td>
	<td width="20" align="center"><img src="../imagenes/information.png" /></td>
   	<td width="40" align="left">Historial</td>      
    <td width="20" align="center">|</td>  
	<td width="20" align="center"><img src="../imagenes/attach.png" /></td>
   	<td width="50" align="left">Evidencias</td> 
    <td width="20" align="center">|</td>  
	<td width="20" align="center"><img src="../imagenes/ayuda.gif" /></td>
   	<td width="40" align="left">Detalles</td> 
</tr>
</table><br>

<?php //Reviso que realmente tenga asignada una división, profit_center, área o proyecto 
	$s_p = "select * from autorizadores where id_emp='$_SESSION[IDEMP]'";
	$r_p = mysql_query($s_p); 
	if(mysql_num_rows($r_p)>0) { 	

	//CONSULTA GLOBAL
	$s_f = "select folios.*, sum(partes.cantidad) as cantidad_total, sum(partes.total) as costo_total from scrap_partes as partes, ";
	$s_f.= "scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio and autorizaciones.no_folio = ";
	$s_f.= "folios.no_folio and partes.no_parte = numeros_parte.nombre ";
	if($_SESSION["DEPTO"]!='esp') { $s_f.= " and autorizaciones.depto='$_SESSION[DEPTO]' "; }
	else  { $s_f.= " and (autorizaciones.depto='esp_1' or autorizaciones.depto='esp_2') ";  }
	
	switch($tipo) {
		case "todos"		:	if($_SESSION["DEPTO"]=='inv') { 
									$s_f.= " and (folios.status='0' or folios.status='2') and folios.activo='1' and ";
									$s_f.= "autorizaciones.status='0' "; }
								if($_SESSION["DEPTO"]!='inv') { 
									$s_f.= " and folios.status='0' and folios.activo='1' and autorizaciones.status='0' "; } break;
		case "aprobados"	:	$s_f.= " and not exists (select no_folio from autorizaciones where status!='1' and folios.no_folio=";
								$s_f.= "autorizaciones.no_folio and autorizaciones.depto!='inv') and ";
								$s_f.= "folios.status='0' and folios.activo='1' and autorizaciones.status='0' "; break;
		case "cancelados"	:	$s_f.= " and (folios.status='0' or folios.status='2') and folios.activo='2' ";
		case "rechazados"	:	$s_f.= " and folios.status='2' and folios.activo='1' "; break;	
		case "pendientes"	:	$s_f.= " and folios.status='0' and folios.activo='1' and autorizaciones.status='0' "; break;
	} 
	
	for($i=0;$i<=2;$i++) {
		if($buscar[$i]!='' && $filtros[$i]!='')	{
			$s_f .= " and ( ";
			$data = split(",",$buscar[$i]);
			for($j=0;$j<count($data);$j++) { 
				if($filtros[$i]=="global_pc"){
					$s_f.= get_operador($filtros[$i],$data[$j])." or ";	
				}
				$s_f.= get_operador($filtros[$i],$data[$j])." or "; }
		$s_f = substr($s_f,0,-3)." ) "; } 	
	} 
	if($fechai!='' && $fechaf!='') { $s_f.= "and (fecha>='$fechai' and fecha<='$fechaf') "; }
	$s_f.= filtros_autorizador(); $s_f.= " group by no_folio order by no_folio DESC"; 
  	$r_1 = mysql_query($s_f);
	$d_1 = mysql_fetch_array($r_1);	
	$tot = mysql_num_rows($r_1); 
	
	  $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&tipo=$tipo&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
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
    
    
<?php if($_SESSION["DEPTO"]=='inv') { 
      //Mostrar la hora y detalles de la última autorización existente de cualquier perfil
	  $s_b = "select aut_bitacora.*, general.* from aut_bitacora, autorizaciones, (".$s_f.") as general where general.no_folio = aut_bitacora.no_folio and ";
	  $s_b.= "autorizaciones.no_folio = general.no_folio and general.status='0' and autorizaciones.status='0' and aut_bitacora.status='1' order by aut_bitacora.fecha ";
	  $s_b.= "desc, aut_bitacora.hora desc";
	  $r_b = mysql_query($s_b);
	  $d_b = mysql_fetch_array($r_b); ?>    
<table align="center" class="tabla">
<tr>
   <td align="center" bgcolor="#CCCCCC" width="100"><b>Folio</b></td>
   <td align="center" bgcolor="#CCCCCC" width="100"><b>Fecha</b></td>
   <td align="center" bgcolor="#CCCCCC" width="100"><b>Hora</b></td>
   <td align="center" bgcolor="#CCCCCC" width="100"><b>Perfil</b></td>
   <td align="center" bgcolor="#CCCCCC" width="250"><b>Empleado</b></td>
   <td align="center" bgcolor="#CCCCCC" width="120" rowspan="2"><input type="button" value="Descargar Reporte" class="submit" onclick="descargar();"></td>
</tr>   
<tr>
	<td align="center"><?php echo $d_b['no_folio'];?></td>
    <td align="center"><?php echo $d_b['fecha'];?></td>
    <td align="center"><?php echo $d_b['hora'];?></td>
    <td align="center"><?php  switch($d_b["depto"]) { 
		case "lo"	: echo"LO"; break;
		case "loa"	: echo"LO-Almacén"; break;
		case "lpl"	: echo"LPL"; break;
		case "ffm"	: echo"FFM"; break;
		case "ffc"	: echo"FFC"; break;
		case "prod"	: echo"Producción"; break;
		case "sqm"	: echo"SQM"; break; 
		case "fin"	: echo"Finanzas"; break; 
		case "esp"	: echo"ESP"; break;
		case "oes"	: echo"OES"; break; } ?></td>
	<td align="left">&nbsp;<?php echo $d_b['empleado'];?></td>        
</tr>
</table><br>
<?php } ?>  
   
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="25">
    	<input type="checkbox" name="todos" id="todos" value="1" <?php if($todos=='1'){?> checked="checked" <?php } ?> onclick="select_all('<?php echo $tot;?>');"></td>
	<td align="center" width="25"><img src="../imagenes/zoom.png" /></td>
	<td align="center" width="25"><img src="../imagenes/information.png" /></td>
    <?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { ?>
    <td align="center" width="25"><img src="../imagenes/attach.png" /></td><?php } ?>
	<td align="center" width="60">Folio</td>
	<td align="center" width="90">Fecha</td>
    <?php if($reason!=1) { ?><td align="center" width="90">Cod.Scrap</td><?php } ?>
    <?php if($reason==1) { ?><td align="center" width="90">Reason Code</td><?php } ?>
	<td align="center" width="90" colspan="2">Cod. Causa Original</td>    
	<td align="center" width="100">Info.Obligatoria</td>
    <td align="center" width="60">Qty</td>
	<td align="center" width="80">Total</td>	
	<td align="center" width="250">Números de Parte</td>
    <td align="center" width="50">APD</td>
    <td align="center" width="100">Proyecto</td>
    <td align="center" width="100">Capturista</td>
	<?php  switch($_SESSION["DEPTO"]) { 
		case "lo"	: echo"<td align='center' colspan=2>LO</td>"; break;
		case "loa"	: echo"<td align='center' colspan=2>LO-Almacén</td>"; break;
		case "lpl"	: echo"<td align='center' colspan=2>LPL</td>"; break;
		case "ffm"	: echo"<td align='center' colspan=2>FFM</td>"; break;
		case "ffc"	: echo"<td align='center' colspan=2>FFC</td>"; break;
		case "prod"	: echo"<td align='center' colspan=2>Producción</td>"; break;
		case "sqm"	: echo"<td align='center' colspan=2>SQM</td>"; break; 
		case "fin"	: echo"<td align='center' colspan=2>Finanzas</td>"; break; 
		case "esp"	: echo"<td align='center' colspan=2>ESP</td>"; break;
		case "oes"	: echo"<td align='center' colspan=2>OES</td>"; break;
		case "inv"	: echo"<td width='30' align='center'>LO</td>"; 
	    			  echo"<td width='30' align='center'>LO-A</td>"; 
					  echo"<td width='30' align='center'>LPL</td>"; 
					  echo"<td width='30' align='center'>FFM</td>"; 
					  echo"<td width='30' align='center'>FFC</td>"; 
					  echo"<td width='30' align='center'>Prod</td>";
					  echo"<td width='30' align='center'>SQM</td>"; 
					  echo"<td width='30' align='center'>Finanzas</td>"; 
					  echo"<td width='30' align='center'>ESP</td>";
					  echo"<td width='30' align='center'>OES</td>"; 
					  echo"<td width='30' align='center'>Inv</td>"; 
					  echo"<td align='center' colspan='3'>Acciones</td>"; break; } ?>
</tr>
</thead>
<tbody>
<?php $qty=$cost=0; 
	 $s_f.= " limit $ini_, 100";
	 $r_1 = mysql_query($s_f);
	 $n_1 = mysql_num_rows($r_1);
     while($d_1 = mysql_fetch_array($r_1)) { 
		$qty  = $qty+$d_1['cantidad_total']; 
		$cost = $cost+$d_1['costo_total']; 
		$dis  = firma_inventarios($d_1['no_folio']); ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
    	<input type="checkbox" name="varios[]" id="varios" value="<?php echo $d_1['no_folio'];?>" <?php echo firma_inventarios($d_1['no_folio']);?>></td>	
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/zoom.png" border="0"></a></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/information.png" border="0"></a></td>		
	<?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { $cols = 10; ?>
    <td align="center">    
	<?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$d_1['archivo']; 		
	      if($d_1['archivo']!='') { ?>
         	<a href="<?php echo $ruta;?>" target="_blank">
         	<img src="../imagenes/attach.png" border="0"></a><?php } ?>
    </td><?php } else { $cols = 9; } ?>
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>
	<?php if($reason!=1) { ?><td align="center"><?php echo $d_1['codigo_scrap'];?></td><?php } ?>
	<?php if($reason==1) { ?><td align="center"><?php echo $d_1['reason_code'];?></td><?php } ?>
	<td align="center" width="70">
		<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>  
    <td align="center"><?php if($d_1['info_1']!='NA' && $d_1['info_1']!='') { echo $d_1['info_1']."-".$d_1['info_2']; } else { echo "NA"; } ?></td>      
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;</td>
	<td align="center">
	<?php if($d_1['carga_masiva']=='0'){ 
    	echo "<table align='center' border='0' cellpadding='0' cellspacing='2' style='border:#CCCCCC solid 1px;' width='250'>";
			  $s_2 = "select * from scrap_partes where no_folio='$d_1[no_folio]' order by no_parte";
		      $r_2 = mysql_query($s_2);
		   while($d_2=mysql_fetch_array($r_2)) { 
		echo "<tr bgcolor='#EEEEEE'>";
		   echo "<td align='left' width='100'>&nbsp;$d_2[no_parte]</td>";
           echo "<td align='left' width='150'>&nbsp;$d_2[descripcion]</td>"; 
		echo "</tr>";   
		} echo "</table>"; }
    	if($d_1['carga_masiva']=='1') { 
   			echo "<a href='../excel_reportes.php?op=ver_modelos&folio=$d_1[no_folio]' class='menuLink'>";
			echo "Archivo de modelos<br>(carga masiva)</a>"; } ?>
	</td>	
    <td align="center"><?php echo $d_1['apd'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td> 
    <td align="center"><?php echo $d_1['empleado'];?></td>	
    <?php if($_SESSION["DEPTO"]=='inv') { ?>
	    <td align="center"><?php echo get_bandera("lo",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("loa",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("lpl",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("ffm",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("ffc",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("prod",$d_1['no_folio']);?></td>
		<td align="center"><?php echo get_bandera("sqm",$d_1['no_folio']);?></td>
        <td align="center"><?php echo get_bandera("fin",$d_1['no_folio']);?></td>
        <td align="center"><?php echo get_bandera("esp",$d_1['no_folio']);?></td>
        <td align="center"><?php echo get_bandera("oes",$d_1['no_folio']);?></td>
    	<td align="center"><?php echo get_bandera("inv",$d_1['no_folio']);?></td>
		<td align="center">
		<input type="button" class="submit_small" value="Aprobar" name="app" onclick="aprobar('<?php echo $d_1['no_folio'];?>');" 
		 <?php echo $dis;?>></td>
		<td align="center">
		<input type="button" class="submit_small" value="Rechazar" name="rech" onclick="rechazar('<?php echo $d_1['no_folio'];?>');">
        </td>
		<td align="center">
		<input type="button" class="submit_small" value="Cancelar" name="canc" onclick="cancelar('<?php echo $d_1['no_folio'];?>');">
        </td>	  
    <?php } else { ?>
    <td align="center" width="60">
	<input type="button" class="submit_small" value="Aprobar" name="app" onclick="aprobar('<?php echo $d_1['no_folio'];?>');"></td>
	<td align="center" width="60">
	<input type="button" class="submit_small" value="Rechazar" name="rech" onclick="rechazar('<?php echo $d_1['no_folio'];?>');"></td>
</tr>
<?php } } ?>
<tr bgcolor="#E6E6E6">
	<td colspan="<?php echo $cols;?>" align="right" class="naranja"><b>Totales</b>&nbsp;&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b>&nbsp;</td>
	<td colspan="18">&nbsp;</td>
</tr>
</tbody>
</table><br>
<div align="center">
	<input type="button" name="aprobar_todos" class="submit_big" value="Aprobar Seleccionados" onclick="app_sel();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="rechazar_todos" class="submit_big" value="Rechazar Seleccionados" onclick="rech_sel();">
</div><br><br></form>
<?php } else {
		echo "<div align='center' class='naranja'><b>Usted no tiene división, área, profit center o proyectos asignados como autorizador.<br>";
    	echo "Contacte al administrador del sistema para que le asigne dichos filtros para ver las boletas correspondientes.<b></div>"; } 
}


function firma_inventarios($folio){
if($_SESSION["DEPTO"]=='inv') {
	$s_ = "select * from autorizaciones where no_folio='$folio' and status!='1' and depto!='inv'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { return "disabled"; }
	else { return ""; } }
else { return ""; }	
} 


function app_sel($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
for($i=0;$i<count($varios);$i++) {
	//Si se trata de LPL se pone el nombre del lpl_auto
	if($_SESSION["DEPTO"]=='lpl') {
		$s_1 = "select id_proyecto from scrap_folios where no_folio='$varios[$i]'";
		$r_1 = mysql_query($s_1);
		$d_1 = mysql_fetch_array($r_1);
			$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
			$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'";
			$r_a = mysql_query($s_a);
			$d_a = mysql_fetch_array($r_a);  $nombre = $d_a['nombre']." ".$d_a['apellidos'];
		$s_1 = "update autorizaciones set status='1', id_emp='$d_a[id]', empleado='$nombre' where no_folio='$varios[$i]' and depto='lpl'";
		$r_1 = mysql_query($s_1);			
	} else { 
		$s_1 = "update autorizaciones set status='1', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$varios[$i]' and ";
		if($_SESSION["DEPTO"]!='esp') { $s_1.= "depto='$_SESSION[DEPTO]'"; }
		else { $s_1.= "(depto='esp_1' or depto='esp_2')"; } 	
		$r_1 = mysql_query($s_1); }

	$s_2 = "insert into aut_bitacora values('', '$varios[$i]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', ";
	$s_2.= "'$fecha', '$hora', '', '')";
	$r_2 = mysql_query($s_2);

	//Si la aprobación es de LPL, LO, LOA, SQM, FIN entonces aprueba automático Producción
	if($_SESSION['DEPTO']=='lpl' || $_SESSION['DEPTO']=='lo' || $_SESSION['DEPTO']=='loa' || $_SESSION['DEPTO']=='sqm' || $_SESSION['DEPTO']=='fin') {
		$s_1 = "select * from autorizaciones where no_folio='$varios[$i]' and depto='prod' and status='0'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
			$msj = "APROBACION AUTOMATICA DESDE ".strtoupper($_SESSION['DEPTO']).".";
			$s_1 = "update autorizaciones set status='1', id_emp='1', empleado='SISTEMA. APROBACION AUTOMATICA.' where no_folio='$varios[$i]' and depto='prod'";
			$r_1 = mysql_query($s_1);
			$s_2 = "insert into aut_bitacora values('', '$varios[$i]', 'prod', '1', 'SISTEMA', '1', '$fecha','$hora','$msj','')";
			$r_2 = mysql_query($s_2); 		
		}
	}		
	
	//Si ya todos los departamentos aprobaron e inventarios firma, entonces se pone a 1 todo el scrap capturado
	if($_SESSION['DEPTO']=='inv') {
		$s_1 = "update scrap_folios set status='1' where no_folio='$varios[$i]'";
		$r_1 = mysql_query($s_1); 
	}
}
	echo"<br><br>";
	echo"<table align=center width=500 bgcolor=#FFFFFF>";
		echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
		echo"<tr><td align=center>";
		echo"<br><strong class=texto>Su firma ha sido almacenada para aprobar los folios:<br>";
		for($i=0;$i<count($varios);$i++) {
			echo $varios[$i]."<br>"; }
		echo "</strong>";
	echo"</td></tr></table>";
	echo"<form name=form1>";
	echo"<input type='hidden' name='fechai' value='$fechai'>";
	echo"<input type='hidden' name='fechaf' value='$fechaf'>";
	echo"<input type='hidden' name='buscar[0]' value='$buscar[0]'>";
	echo"<input type='hidden' name='buscar[1]' value='$buscar[1]'>";
	echo"<input type='hidden' name='buscar[2]' value='$buscar[2]'>";
	echo"<input type='hidden' name='filtros[0]' value='$filtros[0]'>";
	echo"<input type='hidden' name='filtros[2]' value='$filtros[1]'>";
	echo"<input type='hidden' name='filtros[3]' value='$filtros[2]'>";
	echo"<input type='hidden' name='tipo' value='$tipo'>";
	echo"<input type='hidden' name='reason' value='$reason'>";	
	echo"<input type='hidden' name='pagina' value='$pagina'>";	
	echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
	echo"</form>";	
}


function rech_sel_1($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios) {
	$tmp = serialize($varios);  
	$tmp = urlencode($tmp); ?>    
<form action="?op=rech_sel_2" method="post" name="form1">
<input type="hidden" name="fechai" value="<?php echo $fechai;?>">
<input type="hidden" name="fechaf" value="<?php echo $fechaf;?>">
<input type="hidden" name="tipo" value="<?php echo $tipo;?>">
<input type="hidden" name="reason" value="<?php echo $reason;?>">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>">
<input type="hidden" name="buscar[0]" value="<?php echo $buscar[0];?>">
<input type="hidden" name="buscar[1]" value="<?php echo $buscar[1];?>">
<input type="hidden" name="buscar[2]" value="<?php echo $buscar[2];?>">
<input type="hidden" name="filtros[0]" value="<?php echo $filtros[0];?>">
<input type="hidden" name="filtros[1]" value="<?php echo $filtros[1]?>">
<input type="hidden" name="filtros[2]" value="<?php echo $filtros[2]?>">
<input type="hidden" name="varios" value="<?php echo $tmp;?>">
<table align="center" class="tabla">
<caption>Debe especificar la razón para rechazar los folios</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150">Comentario:</td>
		<td align="center" width="150">
			<textarea name="comentario" class="texto" cols="80" rows="2"></textarea>
		</td>
	</tr>
</thead>
</table>
<br><div align="center">
<input type="button" value="Regresar" onclick="regresar();" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar_comentario();" class="submit">
</div>
</form>
<?php }


function rech_sel_2($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$varios,$comentario) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

    $tmp = stripslashes($varios); 
    $tmp = urldecode($tmp); 
    $tmp = unserialize($tmp); 

	for($i=0;$i<count($tmp);$i++) {
	
		if($_SESSION["DEPTO"]=='lpl') { 
			$s_1 = "select id_proyecto from scrap_folios where no_folio='$tmp[$i]'";
			$r_1 = mysql_query($s_1);
			$d_1 = mysql_fetch_array($r_1);
				$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
				$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'";
				$r_a = mysql_query($s_a);
				$d_a = mysql_fetch_array($r_a);  $nombre = $d_a['nombre']." ".$d_a['apellidos'];
			$s_1 = "update autorizaciones set status='2', id_emp='$d_a[id]', empleado='$nombre' where no_folio='$tmp[$i]' and depto='lpl'";
			$r_1 = mysql_query($s_1);
		} else {
			$s_1 = "update autorizaciones set status='2', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$tmp[$i]' ";
			if($_SESSION["DEPTO"]!='esp') { $s_1.= "and depto='$_SESSION[DEPTO]'"; }
			else { $s_1.= "and (depto='esp_1' or depto='esp_2')"; } 	 
			$r_1 = mysql_query($s_1); }
	
		$s_1 = "update scrap_folios set status='2' where no_folio='$tmp[$i]'";
		$r_1 = mysql_query($s_1); 
	
		//Inserto en la bitácora de autorizaciones el movimiento correspondiente
		$s_2 = "insert into aut_bitacora values('', '$tmp[$i]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '2', ";
		$s_2.= "'$fecha', '$hora', '$comentario', '')";
		$r_2 = mysql_query($s_2);
		
		enviar_aviso_generador($tmp[$i],$comentario,$_SESSION['DEPTO']);	
	}
	echo"<br><br>";
	echo"<table align=center width=500 bgcolor=#FFFFFF>";
		echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
		echo"<tr><td align=center>";
		echo"<br><strong class=texto>Su firma ha sido almacenada para rechazar los folios:<br>";
		for($i=0;$i<count($tmp);$i++) {
			echo $tmp[$i]."<br>"; }
		echo "</strong>";
	echo"</td></tr></table>";
	echo"<form name=form1>";
	echo"<input type='hidden' name='fechai' value='$fechai'>";
	echo"<input type='hidden' name='fechaf' value='$fechaf'>";
	echo"<input type='hidden' name='buscar[0]' value='$buscar[0]'>";
	echo"<input type='hidden' name='buscar[1]' value='$buscar[1]'>";
	echo"<input type='hidden' name='buscar[2]' value='$buscar[2]'>";
	echo"<input type='hidden' name='filtros[0]' value='$filtros[0]'>";
	echo"<input type='hidden' name='filtros[2]' value='$filtros[1]'>";
	echo"<input type='hidden' name='filtros[3]' value='$filtros[2]'>";
	echo"<input type='hidden' name='tipo' value='$tipo'>";
	echo"<input type='hidden' name='reason' value='$reason'>";	
	echo"<input type='hidden' name='pagina' value='$pagina'>";		
	echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
	echo"</form>";	
}


function aprobar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

	if($_SESSION["DEPTO"]=='lpl') { 
		$s_1 = "select id_proyecto from scrap_folios where no_folio='$folio'";
		$r_1 = mysql_query($s_1);
		$d_1 = mysql_fetch_array($r_1);
			$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
			$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'";
			$r_a = mysql_query($s_a);
			$d_a = mysql_fetch_array($r_a);  $nombre = $d_a['nombre']." ".$d_a['apellidos'];
		$s_1 = "update autorizaciones set status='1', id_emp='$d_a[id]', empleado='$nombre' where no_folio='$folio' and depto='lpl'";
		$r_1 = mysql_query($s_1);
	} else { 
		$s_1 = "update autorizaciones set status='1', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$folio' and "; 
		if($_SESSION["DEPTO"]!='esp') { $s_1.= "depto='$_SESSION[DEPTO]'"; }
		else { $s_1.= "(depto='esp_1' or depto='esp_2')"; } 
		$r_1 = mysql_query($s_1); }
		
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', '$fecha',";
	$s_2.= "'$hora','','')";
	$r_2 = mysql_query($s_2);

	//Si la aprobación es de LPL, LO, LOA, SQM, FIN entonces aprueba automático Producción
	if($_SESSION['DEPTO']=='lpl' || $_SESSION['DEPTO']=='lo' || $_SESSION['DEPTO']=='loa' || $_SESSION['DEPTO']=='sqm' || $_SESSION['DEPTO']=='fin') {
		$s_1 = "select * from autorizaciones where no_folio='$varios[$i]' and depto='prod' and status='0'"; 
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)>0) {
			$msj = "APROBACION AUTOMATICA DESDE LPL.";
			$s_1 = "update autorizaciones set status='1', id_emp='1', empleado='SISTEMA. APROBACION AUTOMATICA.' where no_folio='$varios[$i]' and depto='prod'";
			$r_1 = mysql_query($s_1);
			$s_2 = "insert into aut_bitacora values('', '$varios[$i]', 'prod', '1', 'SISTEMA', '1', '$fecha','$hora','$msj','')";
			$r_2 = mysql_query($s_2); 		
		}
	}
	
	//Si ya todos los departamentos aprobaron e inventarios firma, entonces se pone a 1 todo el scrap capturado
	if($_SESSION['DEPTO']=='inv') {
		$s_1 = "update scrap_folios set status='1' where no_folio='$folio'";
		$r_1 = mysql_query($s_1); 
	}
	echo"<br><br>";
	echo"<table align=center width=500 bgcolor=#FFFFFF>";
		echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
		echo"<tr><td align=center>";
		echo"<br><strong class=texto>Su firma ha sido almacenada para aprobar el folio: $folio</strong><br><br>";
	echo"</td></tr></table>";
	echo"<form name=form1>";
	echo"<input type='hidden' name='fechai' value='$fechai'>";
	echo"<input type='hidden' name='fechaf' value='$fechaf'>";
	echo"<input type='hidden' name='buscar[0]' value='$buscar[0]'>";
	echo"<input type='hidden' name='buscar[2]' value='$buscar[1]'>";
	echo"<input type='hidden' name='buscar[3]' value='$buscar[2]'>";
	echo"<input type='hidden' name='filtros[0]' value='$filtros[0]'>";
	echo"<input type='hidden' name='filtros[1]' value='$filtros[1]'>";
	echo"<input type='hidden' name='filtros[2]' value='$filtros[2]'>";
	echo"<input type='hidden' name='tipo' value='$tipo'>";
	echo"<input type='hidden' name='reason' value='$reason'>";	
	echo"<input type='hidden' name='pagina' value='$pagina'>";	
	echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
	echo"</form>";	
}	


function rechazar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio) { ?>
<form action="?op=save_rechazar" method="post" name="form1">
<input type="hidden" name="fechai" value="<?php echo $fechai;?>">
<input type="hidden" name="fechaf" value="<?php echo $fechaf;?>">
<input type="hidden" name="tipo" value="<?php echo $tipo;?>">
<input type="hidden" name="reason" value="<?php echo $reason;?>">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>">
<input type="hidden" name="filtros[0]" value="<?php echo $filtros[0]?>">
<input type="hidden" name="buscar[0]" value="<?php echo $buscar[0];?>">
<input type="hidden" name="filtros[1]" value="<?php echo $filtros[1]?>">
<input type="hidden" name="buscar[1]" value="<?php echo $buscar[1];?>">
<input type="hidden" name="filtros[2]" value="<?php echo $filtros[2]?>">
<input type="hidden" name="buscar[2]" value="<?php echo $buscar[2];?>">
<input type="hidden" name="folio" value="<?php echo $folio;?>">
<table align="center" class="tabla">
<caption>Debe especificar la razón para rechazar el <b>Folio <?php echo $folio;?></b></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150">Comentario:</td>
		<td align="center" width="150">
			<textarea name="comentario" class="texto" cols="80" rows="2"></textarea>
		</td>
	</tr>
</thead>
</table>
<br><div align="center">
<input type="button" value="Regresar" onclick="regresar();" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar_comentario('rechazar');" class="submit">
</div>
</form>
<?php }


function cancelar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio) { ?>
<form action="?op=save_cancelar" method="post" name="form1">
<input type="hidden" name="fechai" value="<?php echo $fechai;?>">
<input type="hidden" name="fechaf" value="<?php echo $fechaf;?>">
<input type="hidden" name="tipo" value="<?php echo $tipo;?>">
<input type="hidden" name="reason" value="<?php echo $reason;?>">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>">
<input type="hidden" name="filtros[0]" value="<?php echo $filtros[0]?>">
<input type="hidden" name="buscar[0]" value="<?php echo $buscar[0];?>">
<input type="hidden" name="filtros[1]" value="<?php echo $filtros[1]?>">
<input type="hidden" name="buscar[1]" value="<?php echo $buscar[1];?>">
<input type="hidden" name="filtros[2]" value="<?php echo $filtros[2]?>">
<input type="hidden" name="buscar[2]" value="<?php echo $buscar[2];?>">
<input type="hidden" name="folio" value="<?php echo $folio;?>">
<table align="center" class="tabla">
<caption>Debe especificar la razón para cancelar el <b>Folio <?php echo $folio;?></b></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="150">Motivo:</td>
		<td align="center">
			<?php $s_ = "select * from motivos_cancel where activo='1' order by motivo";
			      $r_ = mysql_query($s_); ?>
            <select name="motivo" class="texto" style="width:425px;">
				<option value=""></option>	
				<?php while($d_=mysql_fetch_array($r_)) {?>
				<option value="<?php echo $d_['id'];?>"><?php echo $d_['motivo'];?></option>
				<?php } ?>
            </select>      
		</td>
	</tr>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center">Comentario:</td>
		<td align="center">
			<textarea name="comentario" class="texto" cols="80" rows="2"></textarea>
		</td>
	</tr>
</thead>
</table>
<br><div align="center">
<input type="button" value="Regresar" onclick="regresar();" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar_comentario('cancelar');" class="submit">
</div>
</form>
<?php }


function save_cancelar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio,$comentario,$motivo) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
	$s_1 = "select * from motivos_cancel where id='$motivo'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); $motivo_ = $d_1['motivo'];
	
	$s_1 = "update scrap_folios set activo='2' where no_folio='$folio'";
	$r_1 = mysql_query($s_1); 

	if($_SESSION["DEPTO"]=='lpl') { 
		$s_1 = "select id_proyecto from scrap_folios where no_folio='$folio'";
		$r_1 = mysql_query($s_1);
		$d_1 = mysql_fetch_array($r_1);
			$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
			$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'";
			$r_a = mysql_query($s_a);
			$d_a = mysql_fetch_array($r_a);  $nombre = $d_a['nombre']." ".$d_a['apellidos'];
		$s_1 = "update autorizaciones set status='3', id_emp='$d_a[id]', empleado='$nombre' where no_folio='$folio' and depto='lpl'";
		$r_1 = mysql_query($s_1);
	} else { 
		$s_1 = "update autorizaciones set status='3', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$folio' and ";
		if($_SESSION["DEPTO"]!='esp') { $s_1.= "depto='$_SESSION[DEPTO]'"; }
		else { $s_1.= "(depto='esp_1' or depto='esp_2')"; } 	
		$r_1 = mysql_query($s_1); }
	
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '3', '$fecha',";
	$s_2.= "'$hora', '$comentario', '$motivo_')";
	$r_2 = mysql_query($s_2);
		
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/rechazado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>El folio $folio ha sido cancelado</strong><br>";
		echo"</td></tr></table>";
		echo"<form name=form1>";
		echo"<input type='hidden' name='fechai' value='$fechai'>";
		echo"<input type='hidden' name='fechaf' value='$fechaf'>";
		echo"<input type='hidden' name='buscar[0]' value='$buscar[0]'>";
		echo"<input type='hidden' name='buscar[1]' value='$buscar[1]'>";
		echo"<input type='hidden' name='buscar[2]' value='$buscar[2]'>";
		echo"<input type='hidden' name='filtros[0]' value='$filtros[0]'>";
		echo"<input type='hidden' name='filtros[1]' value='$filtros[1]'>";
		echo"<input type='hidden' name='filtros[2]' value='$filtros[2]'>";
		echo"<input type='hidden' name='tipo' value='$tipo'>";
		echo"<input type='hidden' name='reason' value='$reason'>";
		echo"<input type='hidden' name='pagina' value='$pagina'>";
		echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
		echo"</form>";		
}


function save_rechazar($fechai,$fechaf,$buscar,$filtros,$tipo,$reason,$pagina,$folio,$comentario) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

	if($_SESSION["DEPTO"]=='lpl') { 
		$s_1 = "select id_proyecto from scrap_folios where no_folio='$folio'";
		$r_1 = mysql_query($s_1);
		$d_1 = mysql_fetch_array($r_1);
			$s_a = "select empleados.id, empleados.nombre, empleados.apellidos from empleados, autorizadores where empleados.id = autorizadores.id_emp and ";
			$s_a.= "autorizadores.id_proyecto='$d_1[id_proyecto]' and empleados.nombre!='' and autorizadores.tipo = 'lpl_auto'";
			$r_a = mysql_query($s_a);
			$d_a = mysql_fetch_array($r_a);  $nombre = $d_a['nombre']." ".$d_a['apellidos'];
		$s_1 = "update autorizaciones set status='2', id_emp='$d_a[id]', empleado='$nombre' where no_folio='$folio' and depto='lpl'";
		$r_1 = mysql_query($s_1);
	} else { 
		$s_1 = "update autorizaciones set status='2', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where no_folio='$folio' and "; 
		if($_SESSION["DEPTO"]!='esp') { $s_1.= "depto='$_SESSION[DEPTO]'"; }
		else { $s_1.= "(depto='esp_1' or depto='esp_2')"; } 
		$r_1 = mysql_query($s_1); }

	$s_1 = "update scrap_folios set status='2' where no_folio='$folio'";
	$r_1 = mysql_query($s_1); 

	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '2', '$fecha',";
	$s_2.= "'$hora', '$comentario', '')";
	$r_2 = mysql_query($s_2);
	
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/rechazado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>Su comentario ha sido almacenado rechazando el folio $folio</strong><br>";
			echo"<br><div class=texto>Recuerde que debe cambiar el estado de su firma, una vez corregido el error en la boleta";
			echo"<br>Puede hacer esto en la sección de consulta - rechazados</div>";
		echo"</td></tr></table>";
		echo"<form name=form1>";
		echo"<input type='hidden' name='fechai' value='$fechai'>";
		echo"<input type='hidden' name='fechaf' value='$fechaf'>";
		echo"<input type='hidden' name='buscar[0]' value='$buscar[0]'>";
		echo"<input type='hidden' name='buscar[1]' value='$buscar[1]'>";
		echo"<input type='hidden' name='buscar[2]' value='$buscar[2]'>";
		echo"<input type='hidden' name='filtros[0]' value='$filtros[0]'>";
		echo"<input type='hidden' name='filtros[1]' value='$filtros[1]'>";
		echo"<input type='hidden' name='filtros[2]' value='$filtros[2]'>";
		echo"<input type='hidden' name='tipo' value='$tipo'>";
		echo"<input type='hidden' name='reason' value='$reason'>";	
		echo"<input type='hidden' name='pagina' value='$pagina'>";
		echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
		echo"</form>";
		
	enviar_aviso_generador($folio,$comentario,$_SESSION['DEPTO']);	
} 
?>