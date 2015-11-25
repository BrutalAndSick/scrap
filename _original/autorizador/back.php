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
	form1.action='scrap_firmar.php';
	form1.submit();	
}

function validar_comentario() {
	if(form1.comentario.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.comentario.value='';
		alert('Es necesario ingresar el comentario');
		form1.comentario.focus(); return; }
	form1.submit();	
}

function select_all(valor,tipo) {
	if(form1.todos.checked==true) {
	if(valor>1) { 	
		for(i=0;i<valor;i++) {
			form1.varios[i].checked = true;
			form1.app[i].disabled   = true;
			form1.rech[i].disabled  = true;
			form1.aprobar_todos.disabled = false;
			if(tipo=='inv') { form1.canc[i].disabled = true; } } }
	else { 
			form1.varios.checked = true;
			form1.app.disabled   = true;
			form1.rech.disabled  = true;
			form1.aprobar_todos.disabled = false;
			if(tipo=='inv') { form1.canc.disabled = true; } }				
	}
	if(form1.todos.checked==false) {
	if(valor>1) { 	
		for(i=0;i<valor;i++) {
			form1.varios[i].checked = false; 
			form1.app[i].disabled   = false;
			form1.rech[i].disabled  = false;
			form1.aprobar_todos.disabled = true;
			if(tipo=='inv') { form1.canc[i].disabled = false; } } }
	else { 
			form1.varios.checked = false;
			form1.app.disabled   = false;
			form1.rech.disabled  = false;
			form1.aprobar_todos.disabled = true; 
			if(tipo=='inv') { form1.canc.disabled = false; } }				
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


function exportar() {
	form2.action='excel_reportes.php?op=por_firmar';
	form2.submit();
	form2.action='?op=listado';
}

function exportar_2() {
	form2.action='excel_reportes.php?op=por_firmar_otros';
	form2.submit();
	form2.action='?op=listado';
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
			case "listado"			:	if($_SESSION["DEPTO"]!='inv') { 
											listado_otro($defecha,$afecha,$orden,$division,$proy_add,$proy_del,$folio,$turno,$apd,
											$parte); }
										if($_SESSION["DEPTO"]=='inv') { 
											listado_inv($defecha,$afecha,$orden,$division,$tipo,$proy_add,$proy_del,$folio,$turno,
											$depto,$apd,$parte); } break;		
			case "aprobar"			:	aprobar($defecha,$afecha,$orden,$_GET['folio']);  break;
			case "app_sel"			:	app_sel($defecha,$afecha,$orden,$varios); break;
			
			case "rechazar"			:	rechazar($defecha,$afecha,$orden,$_GET['folio']); break;	
			case "save_rechazar"	:	save_rechazar($defecha,$afecha,$orden,$folio,$comentario); break;		
			
			case "cancelar"			:	cancelar($defecha,$afecha,$orden,$_GET['folio']); break;
			case "save_cancelar"	:	save_cancelar($defecha,$afecha,$orden,$folio,$comentario); break;
	
			default					:	mails(); 
			   							if($_SESSION["DEPTO"]!='inv') {
											listado_otro($defecha,$afecha,$orden,$division,$proy_add,$proy_del,$folio,$turno,$apd,
											$parte); }
										if($_SESSION["DEPTO"]=='inv') { 
											listado_inv($defecha,$afecha,$orden,$division,$tipo,$proy_add,$proy_del,$folio,$turno,
											$depto,$apd,$parte); } break;
			}?>			
		<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function mails() {
	$s_1 = "select * from configuracion where variable = 'send_mails_hour'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	list($hora,$min,$seg) = split(":",$d_1['valor']);
	$hora_envio  = mktime($hora,$min,$seg,0,0,0);
	$hora_actual = mktime(date("H"),date("i"),date("s"),0,0,0);

	$s_1 = "select * from configuracion where variable = 'last_mails_date'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	list($anio,$mes,$dia) = split("-",$d_1['valor']);
	$dia_ultimo = mktime(0,0,0,$mes,$dia,$anio); 
	$dia_actual = mktime(0,0,0,date("m"),date("d"),date("Y")); 

$fecha = date("Y-m-d");
$hora  = date("H:i:s");
$user_ = get_config("usuario");
$pass_ = get_config("password");

if($dia_ultimo<$dia_actual && $hora_actual>=$hora_envio) { 
	enviar_mails_auto(); }	

$s_ = "update configuracion set valor='$fecha' where variable='last_mails_date'";
	log_sistema("configuracion","editar",$s_);
$r_ = mysql_query($s_);
$s_ = "update configuracion set valor='$hora' where variable='last_mails_hour'";
	log_sistema("configuracion","editar",$s_);
$r_ = mysql_query($s_);	
}


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
<!--    <td align="right"><a href="<?php=$d_['valor'];?>" class="menuLink" target="_blank">Archivo Oficial de Códigos de Scrap</a></td>
 echo '<td align="right"><a href="'.$d_['valor'].'" class="menuLink" target="_blank">Archivo Oficial de Códigos de Scrap</a></td>>' -->
<td align="right"><a href="file:///T:/Locgdlc/Common/LocalGeneral-access/CF/CF1/Gastos-Adic/2011/Catalogo_scrap/Catalogo_scrap_final.xls"></a></td>
</tr>
</table></div><hr>
<?php } 	


function listado_otro($defecha,$afecha,$orden,$division,$proy_add,$proy_del,$folio,$turno,$apd,$parte) {
if($proy_add!='') {
	$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')"; }
if($proy_del!='') {
	$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; }
	$r_ = mysql_query($s_);
	if(!$orden)   $orden   = "fecha"; 
				  $ruta    = "&defecha=$defecha&afecha=$afecha"; ?>
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos.</div>
<form action="?op=listado" method="post" name="form2">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla">
<caption>Resúmen de mis autorizaciones pendientes</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="100">De la fecha</td>
		<td align="center" width="100">A la fecha</td>
        <td align="center" width="80">Folio</td>
        <td align="center" width="100">No.Parte</td>
        <td align="center" width="60">Turno</td>
		<td align="center" width="150">División</td>
        <td align="center" width="100">APD</td>
		<td align="center" width="300" colspan="2">Agregue/quite proyectos</td>
	</tr>
<tbody>
<tr>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'defecha',
		'valor': '<?php echo $defecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>
	<td align="center">
	  	<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'afecha',
		'valor': '<?php echo $afecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>	
	</td>
	<td align="center"><input type="text" name="folio" class="texto" size="10" value="<?php echo $folio;?>"></td>
    <td align="center"><input type="text" name="parte" class="texto" size="13" value="<?php echo $parte;?>"></td>
	<td align="center">
	<select name="turno" class="texto" style="width:60px;" onchange="submit();">
	  	<option value="">Todos</option>
		<?php for($i=1;$i<=7;$i++) { ?>
		<option value="<?php echo $i;?>" <?php if($turno==$i){?> selected="selected" <?php } ?>>
		<?php echo $i;?></option><?php } ?>
	</select></td>  
	<td align="center">
	<?php $r_1 = mysql_query(get_divisiones()); ?>
	<select name="division" class="texto" style="width:150px;" onchange="submit();">
	  	<option value="">Todas</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
		<?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>
 	<td align="center">
 	<?php $s_a = "select * from apd where activo='1' ";
		  if($division!='') { $s_a .= "and id_division = '$division' "; }
		  else { 
		  	$r_d = mysql_query(get_divisiones());
			if(mysql_num_rows($r_d)>0) { $s_a .= "and ("; 
			while($d_d=mysql_fetch_array($r_d)) { 
				$s_a.= "id_division='$d_d[id]' or "; }
				$s_a = substr($s_a,0,-4).") "; 
		  } }		
		  $s_a.= "group by nombre order by nombre";
		  $r_a = mysql_query($s_a); ?> 
	<select name="apd" class="texto" style="width:100px;" onchange="submit();">
	  	<option value="">Todos</option>
		<?php while($d_a=mysql_fetch_array($r_a)) { ?>
		<option value="<?php echo $d_a['nombre'];?>" <?php if($apd==$d_a['nombre']){?> selected="selected" <?php } ?>>
		<?php echo $d_a['nombre'];?></option><?php } ?>
	</select></td>   
	<td align="center">
	<?php $r_1 = mysql_query(get_proyectos_out($division)); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_add" class="texto" style="width:150px;" onchange="submit();">
		<option value="">Sin filtro (<?php echo $n_1;?>)</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del" class="texto" style="width:150px;" onchange="submit();">
	  	<option value="">En filtro (<?php echo $n_1;?>)</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>		
</tr>		
</tbody>
</table>
<div align="center"><br>
<input type="submit" value="Buscar" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Exportar" class="submit" onclick="exportar_2();">
</div>
</form>

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
	</table>

<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">

<?php  $s_1 = "select folios.*, sum(partes.cantidad) as cantidad_total, sum(partes.total) as costo_total from scrap_partes as ";
	   $s_1.= "partes, scrap_folios as folios where folios.status='0' and folios.no_folio = partes.no_folio and folios.activo='1' ";
	   $s_1.= "and folios.turno like '$turno%' ";
	   if($apd!='') { $s_1 .= " and folios.apd like '$apd' "; }
	   if(!$folio && $defecha!='' && $afecha!='') { $s_1 .= " and folios.fecha>='$defecha' and folios.fecha<='$afecha' "; }
	   $s_1.= filtros_autorizador($division);
	   $s_1.= "and folios.no_folio like '$folio%' group by folios.no_folio order by $orden DESC";
 	   $r_1 = mysql_query($s_1); $n_1=0;
	   while($d_1=mysql_fetch_array($r_1)) { 
       	if(aplica_mi_depto($d_1['no_folio'])=='SI') { 
	   	 if(aplica_parte($parte,$d_1['no_folio'])=="SI") { $n_1++; } } } ?>
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="25"><input type="checkbox" name="todos" id="todos" value="1" <?php if($todos=='1'){?> checked="checked" <?php } ?> onclick="select_all('<?php echo $n_1;?>','otro');"></td>
	<td align="center" width="25"><img src="../imagenes/zoom.png" /></td>
	<td align="center" width="25"><img src="../imagenes/information.png" /></td>
    <?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { ?>
    <td align="center" width="25"><img src="../imagenes/attach.png" /></td><?php } ?>
	<td align="center" width="60"><a href="?op=listado<?php echo $ruta;?>&orden=no_folio">Folio</a></td>
	<td align="center" width="90"><a href="?op=listado<?php echo $ruta;?>&orden=fecha">Fecha</a></td>
	<td align="center" width="90"><a href="?op=listado<?php echo $ruta;?>&orden=codigo_scrap">Cod.Scrap</a></td>
	<td align="center" width="90" colspan="2">Cod. Causa Original</td>    
	<td align="center" width="60"><a href="?op=listado<?php echo $ruta;?>&orden=cantidad_total">Cant</a></td>
	<td align="center" width="80"><a href="?op=listado<?php echo $ruta;?>&orden=costo_total">Total</a></td>	
	<td align="center" width="250">Números de Parte</td>
    <td align="center" width="50">APD</td>
    <td align="center" width="100">Supervisor</td>
	<?php  switch($_SESSION["DEPTO"]) { 
		case "lo"	: echo"<td align='center' colspan=2>Firma LO</td>"; break;
		case "loa"	: echo"<td align='center' colspan=2>Firma LO-Almacén</td>"; break;
		case "lpl"	: echo"<td align='center' colspan=2>Firma LPL</td>"; break;
		case "ffm"	: echo"<td align='center' colspan=2>Firma FFM</td>"; break;
		case "ffc"	: echo"<td align='center' colspan=2>Firma FFC</td>"; break;
		case "prod"	: echo"<td align='center' colspan=2>Firma Producción</td>"; break;
		case "sqm"	: echo"<td align='center' colspan=2>Firma SQM</td>"; break; } ?>
</tr>
</thead>
<tbody>
<?php  $r_1 = mysql_query($s_1); $qty = $cost = 0;  
   while($d_1=mysql_fetch_array($r_1)) { 
   if(aplica_mi_depto($d_1['no_folio'])=='SI') { 
   if(aplica_parte($parte,$d_1['no_folio'])=="SI") { 
   	$qty = $qty+$d_1['cantidad_total']; $cost = $cost+$d_1['costo_total']; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><input type="checkbox" name="varios[]" id="varios" value="<?php echo $d_1['no_folio'];?>"></td>	
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/zoom.png" border="0"></a></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/information.png" border="0"></a></td>		
	<?php if($_SESSION["DEPTO"]=='lo' || $_SESSION["DEPTO"]=='loa') { $cols = 9; ?>
    <td align="center">    
	<?php $s_c  = "select * from configuracion where variable='ruta_evidencias'";
	      $r_c  = mysql_query($s_c);
		  $d_c  = mysql_fetch_array($r_c);
		  $ruta = $d_c['valor'].$d_1['archivo']; 		
	      if($d_1['archivo']!='') { ?>
         	<a href="<?php echo $ruta;?>" target="_blank">
         	<img src="../imagenes/attach.png" border="0"></a><?php } ?>
    </td><?php } else { $cols = 8; } ?>
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>
	<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
	<td align="center" width="70">
		<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
		      echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>      
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;</td>
	<td align="center">
	<?php if($d_1['carga_masiva']=='0'){ ?>
    	<table align="center" border="0" cellpadding="0" cellspacing="2" style="border:#CCCCCC solid 1px;" width="250">
		<?php $s_2 = "select * from scrap_partes where no_folio='$d_1[no_folio]' order by no_parte";
		      $r_2 = mysql_query($s_2);
		   while($d_2=mysql_fetch_array($r_2)) { ?>
		<tr bgcolor="#EEEEEE">
		    <td align="left" width="100">&nbsp;<?php echo $d_2['no_parte'];?></td>
            <td align="left" width="150">&nbsp;<?php echo $d_2['descripcion'];?></td> 
		</tr>   
		<?php } ?>   
		</table>
    <?php } ?>
    <?php if($d_1['carga_masiva']=='1') { ?>
   		<a href="../excel_reportes.php?op=ver_modelos&folio=<?php echo $d_1['no_folio'];?>" class="menuLink">
        Archivo de modelos<br>(carga masiva)</a>
    <?php } ?>        
	</td>	
    <td align="center"><?php echo $d_1['apd'];?></td>
    <td align="center"><?php echo $d_1['supervisor'];?></td>	
    <td align="center" width="60">
	<input type="button" class="submit_small" value="Aprobar" name="app" onclick="aprobar('<?php echo $d_1['no_folio'];?>');"></td>
	<td align="center" width="60">
	<input type="button" class="submit_small" value="Rechazar" name="rech" onclick="rechazar('<?php echo $d_1['no_folio'];?>');"></td>
</tr>
<?php } } } ?>
<?php if($qty>0) { ?>
<tr bgcolor="#E6E6E6">
	<td colspan="<?php echo $cols;?>" align="right" class="naranja"><b>Totales</b>&nbsp;&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b>&nbsp;</td>
	<td colspan="5">&nbsp;</td>
</tr><?php } ?>
</tbody>
</table><br>
<div align="center">
	<input type="button" name="aprobar_todos" class="submit_big" value="Aprobar Seleccionados" onclick="app_sel();" disabled="disabled">
</div><br><br></form>
<?php }


function aplica_mi_depto($folio) {
   $s_ = "select * from autorizaciones where no_folio='$folio' and depto='$_SESSION[DEPTO]' and status='0' ";
   switch($_SESSION["DEPTO"]) { 
		case "lpl"	: $s_.= "and (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]')"; break;
		case "ffm"	: $s_.= "and (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]')"; break;
		case "ffc"	: $s_.= "and (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]')"; break;
		case "prod"	: $s_.= "and (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]')"; break;
	}
	$r_ = mysql_query($s_); //echo $s_."<br>";	
	if(mysql_num_rows($r_)>0) { return "SI"; } else { return "NO"; }	
}


function listado_inv($defecha,$afecha,$orden,$division,$tipo,$proy_add,$proy_del,$folio,$turno,$depto,$apd,$parte) {
if($proy_add!='') {
	$s_ = "insert into filtros values('','proyectos','$proy_add','$_SESSION[IDEMP]')"; }
if($proy_del!='') {
	$s_ = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del'"; }
	$r_ = mysql_query($s_);
	if(!$tipo)    $tipo    = "todos"; 
	if(!$orden)   $orden   = "fecha"; 
				  $ruta    = "&defecha=$defecha&afecha=$afecha"; ?>
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos. Haga clic en las banderas para ver detalles de autorización.<br>Este es el listado de scrap capturado hoy. Si desea ver capturas anteriores, utilice los filtros.</div>
<form action="?op=listado" method="post" name="form2">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla" border="0">
<caption>Resúmen de mis autorizaciones pendientes</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td align="center" width="120">De la fecha</td>
		<td align="center" width="120">A la fecha</td>
        <td align="center" width="100">Folio</td>
		<td align="center" width="100">Turno</td>
        <td align="center" width="100">Departamento</td>      
        <td align="center" width="100">No.Parte</td>      
    </tr>
	<tr>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'defecha',
		'valor': '<?php echo $defecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>
	<td align="center">
	  	<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'afecha',
		'valor': '<?php echo $afecha;?>'
		}
		new gCalendar(GC_SET_0);
		</script>	
	</td>
	<td align="center"><input type="text" name="folio" class="texto" size="12" value="<?php echo $folio;?>"></td>
    <td align="center">
	<select name="turno" class="texto" style="width:100px;" onchange="submit();">
	  	<option value="">Todos</option>
		<?php for($i=1;$i<=7;$i++) { ?>
		<option value="<?php echo $i;?>" <?php if($turno==$i){?> selected="selected" <?php } ?>>
		<?php echo $i;?></option><?php } ?>
	</select></td>  
	<td align="center">
	<select name="depto" class="texto" style="width:100px;" onchange="submit();">
	  	<option value="">Todos</option>
        <option value="ffc" <?php if($depto=="ffc"){?> selected="selected" <?php } ?>>FFC</option>
        <option value="ffm" <?php if($depto=="ffm"){?> selected="selected" <?php } ?>>FFM</option>
		<option value="lo" <?php if($depto=="lo"){?> selected="selected" <?php } ?>>LO</option>
        <option value="loa" <?php if($depto=="loa"){?> selected="selected" <?php } ?>>LO Almacén</option>
         <option value="lpl" <?php if($depto=="lpl"){?> selected="selected" <?php } ?>>LPL</option>
        <option value="prod" <?php if($depto=="prod"){?> selected="selected" <?php } ?>>Producción</option>
        <option value="sqm" <?php if($depto=="sqm"){?> selected="selected" <?php } ?>>SQM</option>       
	</select></td>    
    <td align="center"><input type="text" name="parte" class="texto" size="12" value="<?php echo $parte;?>"></td>
    </tr>
    <tr bgcolor="#E6E6E6" height="20">     
        <td align="center">Tipo</td>
		<td align="center">División</td>
        <td align="center">APD</td>
		<td align="center" colspan="3">Agregue/quite proyectos</td>
	</tr>
	<tr>
	<td align="center">
	<select name="tipo" class="texto" style="width:120px;" onchange="submit();">
	  	<option value="todos" <?php if($tipo=='todos') {?> selected="selected"<?php } ?>>Todos</option>
        <option value="aprobados" <?php if($tipo=='aprobados') {?> selected="selected"<?php } ?>>Aprobados</option>
        <option value="pendientes" <?php if($tipo=='pendientes') {?> selected="selected"<?php } ?>>Pendientes</option>
	</select></td>
    <td align="center">
	<?php $r_1 = mysql_query(get_divisiones()); ?>
	<select name="division" class="texto" style="width:120px;" onchange="submit();">
	  	<option value="">Todas</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php } ?>>
		<?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>
 	<td align="center">
 	<?php $s_a = "select * from apd where activo='1' ";
		  if($division!='') { $s_a .= "and id_division = '$division' "; }
		  else { 
		  	$r_d = mysql_query(get_divisiones());
			if(mysql_num_rows($r_d)>0) { $s_a .= "and ("; 
			while($d_d=mysql_fetch_array($r_d)) { 
				$s_a.= "id_division='$d_d[id]' or "; }
				$s_a = substr($s_a,0,-4).") "; 
		  } }		
		  $s_a.= "group by nombre order by nombre";
		  $r_a = mysql_query($s_a); ?> 
	<select name="apd" class="texto" style="width:100px;" onchange="submit();">
	  	<option value="">Todos</option>
		<?php while($d_a=mysql_fetch_array($r_a)) { ?>
		<option value="<?php echo $d_a['nombre'];?>" <?php if($apd==$d_a['nombre']){?> selected="selected" <?php } ?>>
		<?php echo $d_a['nombre'];?></option><?php } ?>
	</select></td>  
	<td align="center" colspan="3">
    <table align="center" width="300">
    <tr><td>
	<?php $r_1 = mysql_query(get_proyectos_out($division)); 
	   	  $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_add" class="texto" style="width:150px;" onchange="submit();">
		<option value="">Sin filtro (<?php echo $n_1;?>)</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
	<td align="center">
	<?php $r_1 = mysql_query(get_proyectos_in());
	      $n_1 = mysql_num_rows($r_1); ?>
	<select name="proy_del" class="texto" style="width:150px;" onchange="submit();">
	  	<option value="">En filtro (<?php echo $n_1;?>)</option>	
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['valor'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
	</select></td>	
    </tr></table>	
</tr>		
</table>
<div align="center"><br>
	<input type="submit" value="Buscar" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Exportar" class="submit" onclick="exportar();">
</div>
</form>

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
        <td width="20" align="center">|</td>  
		<td width="20" align="center"><img src="../imagenes/flag_orange.gif" /></td>
    	<td width="50" align="left">Pendientes</td>  
        <td width="20" align="center">|</td>   
		<td width="20" align="center"><img src="../imagenes/flag_green.gif" /></td>
    	<td width="50" align="left">Aprobados</td>     
        <td width="20" align="center">|</td>   
		<td width="20" align="center"><img src="../imagenes/flag_red.gif" /></td>
    	<td width="50" align="left">Rechazados</td>                         
	</tr>
	</table>

<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="20">&nbsp;</td>
	<td align="center" width="25"><img src="../imagenes/zoom.png" /></td>
	<td align="center" width="25"><img src="../imagenes/information.png" /></td>
	<td align="center" width="50"><a href="?op=listado<?php echo $ruta;?>&orden=no_folio">Folio</a></td>
	<td align="center" width="80"><a href="?op=listado<?php echo $ruta;?>&orden=fecha">Fecha</a></td>
	<td align="center" width="70"><a href="?op=listado<?php echo $ruta;?>&orden=codigo_scrap">Cod.Scrap</a></td>
    <td align="center" width="80" colspan="2">Cod. Causa Original</td>
	<td align="center" width="50"><a href="?op=listado<?php echo $ruta;?>&orden=cantidad_total">Cant</a></td>
	<td align="center" width="60"><a href="?op=listado<?php echo $ruta;?>&orden=costo_total">Total</a></td>
	<td align="center" width="250">Números de Parte</td>
    <td align="center" width="50">APD</td>
	<td align="center" width="100">Supervisor</td>
	<?php  echo"<td width='30' align='center'>LO</td>"; 
	    echo"<td width='30' align='center'>LO-A</td>"; 
		echo"<td width='30' align='center'>LPL</td>"; 
		echo"<td width='30' align='center'>FFM</td>"; 
		echo"<td width='30' align='center'>FFC</td>"; 
		echo"<td width='30' align='center'>Prod</td>";
		echo"<td width='30' align='center'>SQM</td>"; 
		echo"<td align='center' colspan='3'>Inventarios</td>"; ?>
</tr>
</thead>
<tbody>
<?php 
    $s_1 = "select folios.no_folio, folios.fecha, sum(partes.cantidad) as cantidad_total, folios.planta, folios.id_division, ";
    $s_1.= "folios.division, folios.archivo, folios.carga_masiva, folios.financiero, folios.profit_center, folios.area, folios.apd, ";
	$s_1.= "folios.codigo_scrap, folios.supervisor, sum(partes.total) as costo_total from scrap_partes as partes, scrap_folios ";
	$s_1.= "as folios where folios.status='0' and folios.no_folio = partes.no_folio and folios.turno like '$turno%' ";
	if($apd!='') { $s_1 .= " and folios.apd like '$apd' "; }
	if(!$folio && $defecha!='' && $afecha!='') { $s_1 .= " and folios.fecha>='$defecha' and folios.fecha<='$afecha' "; }
	$s_1.= filtros_autorizador($division);	
	$s_1.= "and folios.no_folio like '$folio%' and folios.activo='1' group by folios.no_folio order by $orden DESC";
 	$r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1);
	while($d_1=mysql_fetch_array($r_1)) { 
	  if(aplica_inventarios($d_1['id_division'])=='SI') { 
		if(aplica_depto($depto,$d_1['no_folio'],$tipo)=="SI") {
		if(aplica_parte($parte,$d_1['no_folio'])=="SI") { 
		$qty = $qty+$d_1['cantidad_total']; $cost = $cost+$d_1['costo_total']; $show = 'SI'; 
		if(firma_inventarios($d_1['no_folio'])=='SI') { $dis = ''; } else { $dis = 'disabled'; } 
		
		//Aplicar el filtro de tipo
		switch($tipo) {
			case "todos"		:	$show = 'SI'; break;
			case "aprobados"	:	if(firma_inventarios($d_1['no_folio'])=='SI') { $show = 'SI'; }
									if(firma_inventarios($d_1['no_folio'])=='NO') { $show = 'NO'; } break;
			case "pendientes"	:	if(firma_inventarios($d_1['no_folio'])=='SI') { $show = 'NO'; }
									if(firma_inventarios($d_1['no_folio'])=='NO') { $show = 'SI'; } break;	
		} if($show=='SI') { ?>	
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><input type="checkbox" name="varios[]" id="varios" value="<?php echo $d_1['no_folio'];?>" <?php echo $dis;?>></td>	
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=ver_boleta&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/zoom.png" border="0"></a></td>
	<td align="center">
		<a class="frame_ver_boleta" href="../detalles.php?op=historial&folio=<?php echo $d_1['no_folio'];?>&buscar=no">
		<img src="../imagenes/information.png" border="0"></a></td>	
	<td align="center"><?php echo $d_1['no_folio'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['fecha']);?></td>	
	<td align="center"><?php echo $d_1['codigo_scrap'];?></td>
	<td align="center" width="60">
		<?php $original = data_codigo_original($d_1['no_folio'],$d_1['financiero']);
		      echo $original['codigo']; ?></td>
    <td align="center" width="20">
    	<?php if($d_1['financiero']=='1') { ?> 
		<span title='header=[&nbsp;&nbsp;Código de Causa Original] body=[<?php echo detalles_codigo_original($d_1['no_folio']);?>]'>
		<img src="../imagenes/ayuda.gif" style="cursor: hand;"></span><?php } ?>   
    </td>      
	<td align="center"><?php echo $d_1['cantidad_total'];?></td>
	<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?>&nbsp;</td>
	<td align="center">
	<?php if($d_1['carga_masiva']=='0') { ?>
		<table align="center" border="0" cellpadding="0" cellspacing="2" style="border:#CCCCCC solid 1px;" width="250">
		<?php $s_2 = "select * from scrap_partes where no_folio='$d_1[no_folio]' order by no_parte";
		      $r_2 = mysql_query($s_2);
		   while($d_2=mysql_fetch_array($r_2)) { ?>
		<tr bgcolor="#EEEEEE">
		    <td align="left" width="100">&nbsp;<?php echo $d_2['no_parte'];?></td>
            <td align="left" width="150">&nbsp;<?php echo $d_2['descripcion'];?></td>
		</tr>   
		<?php } ?>   
		</table><?php } ?>
    <?php if($d_1['carga_masiva']=='1') { ?>
   		<a href="../excel_reportes.php?op=ver_modelos&folio=<?php echo $d_1['no_folio']?>" class="menuLink">
        Archivo de modelos<br>(carga masiva)</a>
    <?php } ?>          
	</td>	
    <td align="center"><?php echo $d_1['apd'];?></td>	
	<td align="center"><?php echo $d_1['supervisor'];?></td>	
    <td align="center"><?php echo get_bandera("lo",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("loa",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("lpl",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffm",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("ffc",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("prod",$d_1['no_folio']);?></td>
	<td align="center"><?php echo get_bandera("sqm",$d_1['no_folio']);?></td>
	<td align="center">
		<input type="button" class="submit_small" value="Aprobar" name="app" onclick="aprobar('<?php echo $d_1['no_folio'];?>');" <?php echo $dis;?>></td>
	<td align="center">
		<input type="button" class="submit_small" value="Rechazar" name="rech" onclick="rechazar('<?php echo $d_1['no_folio'];?>');"></td>
	<td align="center">
		<input type="button" class="submit_small" value="Cancelar" name="canc" onclick="cancelar('<?php echo $d_1['no_folio'];?>');"></td>	
</tr>
<?php } } } } } if($qty>0) { ?>
<tr bgcolor="#E6E6E6">
	<td colspan="8" align="right" class="naranja"><b>Totales</b>&nbsp;&nbsp;</td>
	<td align="center" class="naranja"><b><?php echo $qty;?></b></td>
	<td align="right" class="naranja"><b><?php echo "$ ".number_format($cost,2);?></b>&nbsp;</td>
	<td colspan="15">&nbsp;</td>
</tr><?php } ?>
</tbody>
</table><br>
<div align="center">
	<input type="button" name="aprobar_todos" class="submit_big" value="Aprobar Seleccionados" onclick="app_sel();">
</div><br><br></form>
<?php }


function aplica_parte($parte,$folio) {
if($parte!='') { 
	$s_p = "select * from scrap_partes where no_folio='$folio' and no_parte like '$parte%'";
	$r_p = mysql_query($s_p);
	if(mysql_num_rows($r_p)>0) { return "SI"; }
		else { return "NO"; } 
	} else { return "SI"; }		
}


function aplica_depto($depto,$folio,$tipo) {
	if($tipo=='todos' || $tipo=='') { $tipo = '%'; }
	if($tipo=='aprobados')  { $tipo = '1'; }
	if($tipo=='pendientes') { $tipo = '0'; }

	if($depto!='') { 
		$s_d = "select * from autorizaciones where depto='$depto' and no_folio='$folio' and status like '$tipo'";
		$r_d = mysql_query($s_d);
		if(mysql_num_rows($r_d)>0) { return "SI"; }
		else { return "NO"; } 
	} else { return "SI"; }
}


function aplica_inventarios($division) {
	$s_ = "select * from autorizadores where tipo='inv' and (id_division like '$division' or id_division='%') and ";
	$s_.= "(id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]')";
	$r_= mysql_query($s_);
	if(mysql_num_rows($r_)>0) { return "SI"; }
	else { return "NO"; }
}


function firma_inventarios($folio){
	$s_ = "select * from autorizaciones where no_folio='$folio' and status!='1' and depto!='inv'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { return "NO"; }
	else { return "SI"; }
} 


function app_sel($defecha,$afecha,$orden,$varios) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

for($i=0;$i<count($varios);$i++) {

	$s_1 = "update autorizaciones set status='1' "; 
	switch($_SESSION['DEPTO']) {
		case "lo"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
		case "loa"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]' where ";  break;
		case "lpl"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "ffm"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "ffc"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "prod"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";   break;
		case "sqm"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
		case "inv"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
	}
	$s_1.= "no_folio='$varios[$i]' and depto='$_SESSION[DEPTO]'"; 
	$r_1 = mysql_query($s_1);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1); 

	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$varios[$i]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', ";
	$s_2.= "'$fecha', '$hora', '')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_2);	
	
	//Si ya todos los departamentos aprobaron e inventarios firma, entonces se pone a 1 todo el scrap capturado
	if($_SESSION['DEPTO']=='inv') {
		$s_1 = "update scrap_folios set status='1' where no_folio='$varios[$i]'";
		$r_1 = mysql_query($s_1); 
		/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1); 	
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
	echo"<input type='hidden' name='defecha' value='$defecha'>";
	echo"<input type='hidden' name='afecha' value='$afecha'>";
	echo"<input type='hidden' name='orden' value='$orden'>";	
	echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
	echo"</form>";	
}


function aprobar($defecha,$afecha,$orden,$folio) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

	$s_1 = "update autorizaciones set status='1' "; 
	switch($_SESSION['DEPTO']) {
		case "lo"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
		case "loa"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]' where ";  break;
		case "lpl"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "ffm"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "ffc"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";  break;
		case "prod"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') and ";   break;
		case "sqm"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
		case "inv"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where ";  break;
	}
	$s_1.= "no_folio='$folio' and depto='$_SESSION[DEPTO]'";
	$r_1 = mysql_query($s_1); //echo $s_1;
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1); 

	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '1', '$fecha',";
	$s_2.= "'$hora','')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_2);
	
	//Si ya todos los departamentos aprobaron e inventarios firma, entonces se pone a 1 todo el scrap capturado
	if($_SESSION['DEPTO']=='inv') {
		$s_1 = "update scrap_folios set status='1' where no_folio='$folio'";
		$r_1 = mysql_query($s_1); 
		/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1);
	}

	echo"<br><br>";
	echo"<table align=center width=500 bgcolor=#FFFFFF>";
		echo"<tr><td align=center><img src='../imagenes/aprobado.gif'></td></tr>";
		echo"<tr><td align=center>";
		echo"<br><strong class=texto>Su firma ha sido almacenada para aprobar el folio $folio</strong><br><br>";
	echo"</td></tr></table>";
	echo"<form name=form1>";
	echo"<input type='hidden' name='defecha' value='$defecha'>";
	echo"<input type='hidden' name='afecha' value='$afecha'>";
	echo"<input type='hidden' name='orden' value='$orden'>";
	echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
	echo"</form>";	
}	


function rechazar($defecha,$afecha,$orden,$folio) { ?>
<form action="?op=save_rechazar" method="post" name="form1">
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
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
<input type="button" value="Guardar" onclick="validar_comentario();" class="submit">
</div>
</form>
<?php }


function cancelar($defecha,$afecha,$orden,$folio) { ?>
<form action="?op=save_cancelar" method="post" name="form1">
<input type="hidden" name="defecha" value="<?php echo $defecha;?>">
<input type="hidden" name="afecha" value="<?php echo $afecha;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="folio" value="<?php echo $folio;?>">
<table align="center" class="tabla">
<caption>Debe especificar la razón para cancelar el <b>Folio <?php echo $folio;?></b></caption>
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


function save_cancelar($defecha,$afecha,$orden,$folio,$comentario) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
	$s_1 = "update scrap_folios set activo='2' where no_folio='$folio'";
	$r_1 = mysql_query($s_1); 
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1);

	$s_1 = "update autorizaciones set status='3', id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where depto='$_SESSION[DEPTO]' ";
	$s_1.= "and no_folio='$folio'";
	$r_1 = mysql_query($s_1); 
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1);
	
	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '3', '$fecha', ";
	$s_2.= "'$hora', '$comentario')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_2);	
		
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/rechazado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>El folio $folio ha sido cancelado</strong><br>";
		echo"</td></tr></table>";
		echo"<form name=form1>";
		echo"<input type='hidden' name='defecha' value='$defecha'>";
		echo"<input type='hidden' name='afecha' value='$afecha'>";
		echo"<input type='hidden' name='orden' value='$orden'>";
		echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
		echo"</form>";		
}



function save_rechazar($defecha,$afecha,$orden,$folio,$comentario) {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");

	$s_1 = "update autorizaciones set status='2' "; 
	switch($_SESSION['DEPTO']) {
		case "lo"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where id_emp = '%' ";  break;
		case "loa"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where id_emp = '%' ";  break;
		case "lpl"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') ";  break;
		case "ffm"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') ";  break;
		case "ffc"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') ";  break;
		case "prod"	:	$s_1 .= "where (id_emp = '$_SESSION[IDEMP]' or id_emp = '$_SESSION[AUSENCIA]') ";   break;
		case "sqm"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where id_emp = '%' ";  break;
		case "inv"	:	$s_1 .= ", id_emp='$_SESSION[IDEMP]', empleado='$_SESSION[NAME]' where id_emp = '%' ";  break;
	}
	$s_1.= "and no_folio='$folio' and depto='$_SESSION[DEPTO]'";
	$r_1 = mysql_query($s_1);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1);

	$s_1 = "update scrap_folios set status='2' where no_folio='$folio'";
	$r_1 = mysql_query($s_1); 
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_1);	

	//Inserto en la bitácora de autorizaciones el movimiento correspondiente
	$s_2 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '2', '$fecha',";
	$s_2.= "'$hora', '$comentario')";
	$r_2 = mysql_query($s_2);
	/*LOG SISTEMA*/log_sistema("scrap","editar",$s_2);
	
		echo"<br><br>";
		echo"<table align=center width=500 bgcolor=#FFFFFF>";
			echo"<tr><td align=center><img src='../imagenes/rechazado.gif'></td></tr>";
			echo"<tr><td align=center>";
			echo"<br><strong class=texto>Su comentario ha sido almacenado rechazando el folio $folio</strong><br>";
			echo"<br><div class=texto>Recuerde que debe cambiar el estado de su firma, una vez corregido el error en la boleta";
			echo"<br>Puede hacer esto en la sección de consulta - rechazados</div>";
		echo"</td></tr></table>";
		echo"<form name=form1>";
		echo"<input type='hidden' name='defecha' value='$defecha'>";
		echo"<input type='hidden' name='afecha' value='$afecha'>";
		echo"<input type='hidden' name='orden' value='$orden'>";		
		echo "<div align=center><input type='button' value='Volver' onclick='regresar();' class='submit'></div>";
		echo"</form>";
		
	enviar_aviso_generador($folio,$comentario,$_SESSION['DEPTO']);	
}	
?>
