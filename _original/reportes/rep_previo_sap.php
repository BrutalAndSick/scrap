<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function exportar() {
	form1.action='excel_reportes.php?op=previo_sap';
	form1.submit();	
	form1.action='?op=listado';
}

function cancelar() {
	form1.action='?op=listado';
	form1.submit();
}

function upload() {
	form1.action='?op=cargar_p1';
	form1.submit();
}

function guardar(total) {
var faltan=0;

if(total>1) { 
	for(i=0;i<total;i++) { 
		if(form1.deficit[i].checked==true) {	
		 if(form1.sap[i].value.replace(/^\s*|\s*$/g,"")=='' && form1.docto_def[i].value!='') {	
			document.form1.sap[i].style.backgroundColor = '#F78181';
			faltan++; }
		 if(form1.sap[i].value.replace(/^\s*|\s*$/g,"")!='' && form1.docto_def[i].value=='') { 	
			document.form1.docto_def[i].style.backgroundColor = '#F78181';
			faltan++; }
		}
	}
}				

if(total<=1) { 
	if(form1.deficit.checked==true) {	
	 if(form1.sap.value.replace(/^\s*|\s*$/g,"")=='' && form1.docto_def.value!='') {	
		document.form1.sap.style.backgroundColor = '#F78181';
		faltan++; }
	 if(form1.sap.value.replace(/^\s*|\s*$/g,"")!='' && form1.docto_def.value=='') { 	
		document.form1.docto_def.style.backgroundColor = '#F78181';
		faltan++; }
	}
}

if(faltan>0) {
	alert('Debe llenar todos los valores necesarios'); 
	return; 
} else {	
	form1.action='?op=save_sap';
	form1.submit(); }
}

function validar_carga() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='txt') {
		alert('Utilice solamente archivos .txt');
		form1.archivo.focus(); return; }				
	form1.submit();	
}

function solo_numeros(evt){
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57));
}

function deficit_(pos,total) {
if(total>1) { 
	if(form1.deficit[pos].checked==true) {
		form1.sap[pos].value = '';
		form1.sap[pos].disabled = false;
		form1.docto_def[pos].value = '';
		form1.docto_def[pos].disabled = false; }
	if(form1.deficit[pos].checked==false) {
		form1.sap[pos].disabled = false;
		form1.docto_def[pos].value = '';
		form1.docto_def[pos].disabled = true; }
} else {
	if(form1.deficit.checked==true) {
		form1.sap.value = '';
		form1.sap.disabled = true; 
		form1.docto_def.value = '';
		form1.docto_def.disabled = false; }
	if(form1.deficit.checked==false) {
		form1.sap.disabled = false;
		form1.docto_def.value = '';
		form1.docto_def.disabled = true;  }
} }

function filtro_oes(){
	form1.action='?op=listado';
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','previo_sap'); ?></td>
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
		<?php 
		   switch($op) {
			case "listado"		:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina); break; 
			case "save_sap"		:	save_sap($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$stock,$id_,$sap,$docto_def,$deficit); 
									listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina); break;
			case "cargar_p1"	:	cargar_p1(); break;	
			case "cargar_p2"	:	cargar_p2($archivo,$archivo_name); break;
			case "cargar_p3"	:	cargar_p3(); break;
			case "cargar_p4"	:	cargar_p4(); listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina); break; 
			default				:	$s_ = "delete from tmp_docto_sap where id_emp='$_SESSION[IDEMP]'";
									$r_ = mysql_query($s_);
									listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina); break;						
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina) {
	if(!$aplica_oes) $aplica_oes = 'no';
	if(!$pagina) $pagina = 1; 
	if(count($proy_add)>0) {
		for($i=0;$i<count($proy_add);$i++) {
		$s_1 = "insert into filtros values('','proyectos','$proy_add[$i]','$_SESSION[IDEMP]')";
		$r_1 = mysql_query($s_1); } }
	if(count($proy_del)>0) {
		for($i=0;$i<count($proy_del);$i++) {
		$s_1 = "delete from filtros where filtro='proyectos' and id_emp='$_SESSION[IDEMP]' and valor='$proy_del[$i]'"; 
		$r_1 = mysql_query($s_1); }	} ?>
    
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="270">REPORTE DE CAPTURA SAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte de captura SAP] body=[Este es el reporte previo a SAP donde se muestra todo el scrap aprobado que aún no tiene asignado un número de documento SAP.<br><br>Puede imprimir este reporte exportándolo directamente a excel e ingresar los números de documento SAP. Una vez guardado el número, no podrá modificarse.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>  

<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
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
</tr>
</table><br>

<table align="center" class="tabla">
	<tr height="20">
		<td align="left" width="62">&nbsp;&nbsp;Buscar:</td>
   		<td>
        	<input type="text" class="texto" size="75" value="<?php echo $buscar['0'];?>" name="buscar[0]" id="buscar">
        </td>
      	<td align="center">En:</td>
        <td><?php $s_1 = "select campo, nombre from encabezados where de_captura='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where de_captura='1' order by nombre"; 
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
        <td><?php $s_1 = "select campo, nombre from encabezados where de_captura='1' order by nombre"; 
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
	<input type="checkbox" name="stock" value="1" <?php if($stock=='1'){?> checked="checked"<?php } ?>>
    Mostrar sólo déficit de Stock&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Buscar" class="submit" onclick="submit();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="exportar();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Cargar Archivo" class="submit" onclick="upload();">
</div><br>

<?php 
    $s_f = "select folios.*, partes.id as idp, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, ";
	$s_f.= "partes.ubicacion, partes.docto_sap, partes.deficit, partes.tipo, partes.tipo_sub, partes.padre, partes.batch_id, ";
	$s_f.= "partes.serial_unidad from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio and ";
	//$s_f.= "autorizaciones.no_folio = folios.no_folio and folios.status='1' and folios.activo='1' and (partes.docto_sap='0' or partes.docto_sap='') and ";
	$s_f.= "autorizaciones.no_folio = folios.no_folio and folios.status='1' and folios.activo='1' and (partes.docto_sap='0' or partes.docto_sap='') ";
	$s_f.= "and partes.no_parte = numeros_parte.nombre ";
	//$s_f.= "partes.tipo like '$f_material' ";
	if($f_sub=="REAL") { $s_f.= " and partes.tipo_sub like 'S.Real'"; }
	if($f_sub=="AUTO") { $s_f.= " and partes.tipo_sub like '%AutoBF'"; } 
	if($stock=='1') { $s_f.= " and partes.deficit='1' "; }
	if($stock!='1') { $s_f.= " and partes.deficit like '%' "; }
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
	$s_f.= "group by partes.id order by folios.no_folio asc ";
	$r_1 = mysql_query($s_f);
	$tot = mysql_num_rows($r_1); 
	
	  $pags = ceil($tot/100);
	  $ini_ = ($pagina-1)*100; $i=1;$j=0;
	  $ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
	  $ruta.= "&buscar[1]=$buscar[1]&filtros[1]=$filtros[1]&buscar[2]=$buscar[2]&filtros[2]=$filtros[2]&stock=$stock";
	  
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
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center">No.Item</td>
    <td align="center">Fecha</td>
	<td align="center">Txs SAP</td>
	<td align="center">Mov</td>
	<td align="center">Código Scrap</td>
    <td align="center">Código Original</td>
	<td align="center">O.I.</td>
    <td align="center">APD</td>
    <td align="center">Proyecto</td>
	<td align="center">Parte Padre</td>
	<td align="center">Batch ID</td>
	<td align="center">No.Parte</td>
	<td align="center">Cantidad</td>
	<td align="center">Reason Code</td>
	<td align="center">Descripción</td>
	<td align="center">Info.Obl.</td>
	<td align="center">Tipo</td>
    <td align="center">Tipo Sub</td>
	<td align="center" width="70">Valor</td>
	<td align="center" width="70">Doc. Header Tex</td>
	<td align="center">No.Docto.SAP</td>
    <td align="center">Docto.Déficit</td>
    <td align="center" width="50">Déficit Stock</td>
</tr>
</thead>
<tbody>
<?php 
	  $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  $s_f.= " limit $ini_, 100"; 
	  $r_1 = mysql_query($s_f); $i=0; 
	  $total = mysql_num_rows($r_1);
	  while($d_1=mysql_fetch_array($r_1)) {
	  echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">"; ?>
	  	<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['txs_sap'];?></td>
		<td align="center"><?php echo $d_1['mov_sap'];?></td>
		<td align="center"><?php echo $d_1['codigo_scrap']; ?></td>
		<td align="center">
		<?php if($d_1['financiero']=='1') { 
				$original = data_codigo_original($d_1['no_folio'],$d_1['financiero']); echo $original['codigo']; } 
				else { echo "NA"; } ?></td>
		<td align="left"><?php echo $d_1['orden_interna']; ?></td>
        <td align="left"><?php echo $d_1['apd']; ?></td>
        <td align="left"><?php echo $d_1['proyecto']; ?></td>
		<!--<td align="left"><?php echo iconv("UTF-8", "ISO-8859-1",$d_1['padre']); ?></td>-->
        <td align="left"><?php echo $d_1['padre']; ?></td>
		<td align="center"><?php echo $d_1['batch_id']; ?></td>
		<td align="left"><?php echo $d_1['no_parte'];?></td>
		<td align="center"><?php echo $d_1['cantidad'];?></td>
		<td align="left"><?php echo $d_1['reason_code'];?></td>
		<td align="left"><?php echo $d_1['descripcion'];?></td>
		<td align="left"><?php echo $d_1['info_1'].$d_1['info_2'];?></td>
		<td align="center"><?php echo $d_1['tipo'];?></td>
    	<td align="center"><?php if($d_1['tipo']=='HALB'){ echo $d_1['tipo_sub']; } else { echo "NA"; } ?></td>
		<td align="right"><?php echo "$ ".number_format($d_1['costo_total'],2);?></td>
		<td align="left"><?php echo get_doc_header($d_1['info_1'],$d_1['info_2'],$d_1['segmento'],$d_1['proyecto'],$d_1['no_folio']);?></td>
		<td align="center">
        	<input type="hidden" name="id_[<?php echo $i;?>]" class="texto" size="15" id="id_" value="<?php echo $d_1['idp'];?>">
            <input type="text" name="sap[<?php echo $d_1['idp'];?>]" class="texto" size="15" id="sap" onkeypress="return solo_numeros(event);"></td>
        <td align="center">    
        <select name="docto_def[<?php echo $d_1['idp'];?>]" class="texto" style="width:107px;" id="docto_def" <?php if($d_1['deficit']=='0'){?> disabled="disabled"<?php } ?>>
        	<option value=""></option>
            <?php $s_ = "select * from motivos_sap where activo='1' order by motivo";
			$r_ = mysql_query($s_); 
			while($d_=mysql_fetch_array($r_)) { ?>
            <option value="<?php echo $d_['id'];?>" <?php if($d_['docto_sap']==$d_['motivo']){?> selected="selected"<?php } ?>><?php echo $d_['motivo'];?></option>
            <?php } ?>
        </select></td>
  		<td align="center">
            <input type="checkbox" name="deficit[<?php echo $d_1['idp'];?>]" value="1" <?php if($d_1['deficit']=='1') {?> checked="checked"<?php } ?> onclick="deficit_('<?php echo $i;?>','<?php echo $total;?>');" id="deficit"></td>
</tr>	
<?php $i++; } ?>
</tbody>
</table>
<br><div align="center">
	<input type="button" class="submit" value="Guardar" onclick="guardar('<?php echo $total;?>');">
</div></form><br><br>
<?php } 


function get_doc_header($info_1,$info_2,$segmento,$proyecto,$folio) {
	$s_ = "select empleado from autorizaciones where no_folio='$folio' and depto='lpl'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	$nombre   = $d_['empleado'];
	$palabras = split(" ",$d_['empleado']);
	$inicial  = substr($palabras['0'],0,1);
	
	for($i=0;$i<count($palabras);$i++) {
		$apellido.= $palabras[$i]." "; }

	if($info_1=='QN') {
		$doc_header = substr($info_2."_".$proyecto."_".$inicial.".".$apellido,0,16);
	} else { 
		$doc_header = substr($segmento."_".$proyecto."_".$inicial.".".$apellido,0,23);	
	}	
	return $doc_header;
}


function save_sap($fechai,$fechaf,$aplica_oes,$buscar,$filtros,$stock,$id_,$sap,$docto_def,$deficit) {
$fecha = date("Y-m-d");
$hora  = date("H:i:s");

if(count($id_)>0) {
	foreach($id_ as $indice => $valor) { 
		if($valor!='') { 
	
			if($deficit[$valor]=='1') { 
				$s_ = "update scrap_partes set deficit='1' where id='$valor'";
				$r_ = mysql_query($s_); 
				if($sap[$valor]!='' && $docto_def[$valor]!='') {
					$s_1 = "select motivo from motivos_sap where id='".trim($docto_def[$valor])."'";
					$r_1 = mysql_query($s_1);
					$d_1 = mysql_fetch_array($r_1);
					$s_ = "update scrap_partes set docto_sap='".trim($sap[$valor])."', docto_def='".trim($d_1['motivo'])."' where id='$valor'";
					$r_ = mysql_query($s_);
					//Insertar en la bitácora de eventos quién cerró esa boleta con SAP
					$s_ = "select no_folio, no_parte from scrap_partes where id='$valor'";
					$r_ = mysql_query($s_);
					$d_ = mysql_fetch_array($r_);
						$s_1 = "insert into aut_bitacora values('', '$d_[no_folio]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '7', '$fecha', '$hora', ";
						$s_1.= "'CAPTURA DE SAP: ".$d_['no_parte']." (".trim($sap[$valor]).")', '')";
						$r_1 = mysql_query($s_1); 
				}		
			}
			if($deficit[$valor]=='0' || $deficit[$valor]=='') { 
				$s_ = "update scrap_partes set deficit='0' where id='$valor'";
				$r_ = mysql_query($s_); 
				if($sap[$valor]!='') {
					$s_ = "update scrap_partes set docto_sap='".trim($sap[$valor])."' where id='$valor'";
					$r_ = mysql_query($s_);
					//Insertar en la bitácora de eventos quién cerró esa boleta con SAP
					$s_ = "select no_folio, no_parte from scrap_partes where id='$valor'";
					$r_ = mysql_query($s_);
					$d_ = mysql_fetch_array($r_);
						$s_1 = "insert into aut_bitacora values('', '$d_[no_folio]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '7', '$fecha', '$hora', ";
						$s_1.= "'CAPTURA DE SAP: ".$d_['no_parte']." (".trim($sap[$valor]).")', '')";
						$r_1 = mysql_query($s_1); 
				}				
			}		
		}
	}
} }

function cargar_p1() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="280">ARCHIVO NÚMEROS DE SAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspCargar archivo con números de SAP] body=[Puede cargar el archivo de manera masiva con los números de documento SAP.<br><br>Descargue primero el archivo a excel y luego capture los números correspondientes.<br><br>Guarde el mismo archivo de excel como archivo de texto separado por tabulaciones y cárguelo.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>   
    
<div align="center" class="aviso">Seleccione el archivo en formato .txt separado por tabulaciones.</div>
<form action="?op=cargar_p2" method="post" name="form1" enctype="multipart/form-data">
<table align="center" class="tabla">
	<tr>
    	<td align="left" width="80">&nbsp;&nbsp;Archivo:</td>
		<td align="center"><input type="file" name="archivo" class="texto" size="50"></td>
	</tr>
</tbody>
</table>
<div align="center"><br>
	<input type="button" value="Cancelar" class="submit" onclick="cancelar();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Cargar" class="submit" onclick="validar_carga();">
</div>
</form>

<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="9" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Carga No.SAP</b></td></tr>
	</td><td align="left" class="gris" width="440">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		1.- Las columnas que se tomarán en cuenta son: </td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		a) La primera columna que contiene el número de folio</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		b) La octava columna que contiene las partes padre</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		c) La décima columna que contiene los números de parte</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		d) La última columna (20) que contiene los números de documento SAP</td>	
	</tr>	    
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="../admin/descargar.php?id=ejemplo_sap.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div>
<?php }


function cargar_p2($archivo,$archivo_name) { 
	$s_ = "select * from configuracion where variable='ruta_cargas_sap'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = date("YmdHis").".".$pext;
	$nom_final = $r_server.$nombre;
	$validos   = "0123456789"; 
	
	$row = 0;
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			listado($fechai,$fechaf,$folio,$division,$proy_add,$proy_del,$turno,$depto,$apd,$parte); exit; }
		else { 
			$s_ = "delete from tmp_docto_sap";  
			$r_ = mysql_query($s_);
			$s_ = "select * from configuracion where variable='ruta_cargas_sap'";
			$r_ = mysql_query($s_);
			$d_ = mysql_fetch_array($r_);

			$fd = fopen ($d_['valor']."$nombre", "r");
			while ( !feof($fd) ) 
 			{ 
				$buffer    = fgets($fd);
				$campos    = split ("\t", $buffer);
				//Validar que los encabezados del archivo correspondan
				if($row==0) {
					if(trim($campos['0'])!='No.Item' || trim($campos['7'])!='Parte Padre' || trim($campos['9'])!='No.Parte' || trim($campos['19'])!='No.Docto.SAP') {
						echo "<script>alert('Error al subir el archivo: $nom_final. Las columnas no coinciden.');</script>"; 					
						listado($fechai,$fechaf,$folio,$division,$proy_add,$proy_del,$turno,$depto,$apd,$parte); exit; }
				} else { 	
				if($campos['0']!='' && $campos['0']!='No.Item' && trim($campos['15'])!='') {
					$campos['0']  = trim($campos['0']);
					$campos['1']  = trim($campos['1']); //Fecha no se usa
					$campos['2']  = trim($campos['2']);
					$campos['3']  = trim($campos['3']);
					$campos['4']  = trim($campos['4']);
					$campos['5']  = trim($campos['5']);
					$campos['6']  = trim($campos['6']); //APD no se usa
					$campos['7']  = trim($campos['7']);
					$campos['8']  = trim($campos['8']);
					$campos['9']  = trim($campos['9']);
					$campos['10']  = trim($campos['10']);
					$campos['11'] = str_replace("/","",$campos['11']);
					$campos['11'] = str_replace("'","",$campos['11']);
					$campos['11'] = trim($campos['11']);
					$campos['12'] = trim($campos['12']);
					$campos['13'] = trim($campos['13']);
					$campos['14'] = trim($campos['14']);
					$campos['15'] = trim($campos['15']);
					$campos['16'] = trim($campos['16']);
					$campos['17'] = trim($campos['17']);
					$campos['18'] = trim($campos['18']);
					$campos['19'] = trim($campos['19']);//No. Docto. SAP

					//Solamente puede ser numérico este campo
					$no_permitido = 0;
					if(strlen($campos['19'])>=10) { 
						for ($v=0; $v<strlen($campos['19']); $v++){ 
						  if (strpos($validos, substr($campos['19'],$v,1))===false){ 
							 $no_permitido++;
							} 
						} 
					} else { $no_permitido++; } 					
					
			$s_1 = "select docto_sap from scrap_partes where no_folio='$campos[0]' and no_parte='$campos[9]' and padre='$campos[7]'";
			$r_1 = mysql_query($s_1);
			$d_1 = mysql_fetch_array($r_1);
				if(trim($d_1['docto_sap'])=='0' || trim($d_1['docto_sap'])=='') { $existe = 0; } else { $existe = 1; }
				if($no_permitido>0) { $existe = 1; $campos['19'] = 'Inválido ('.$campos['19'].')'; } 
					$query = "insert into tmp_docto_sap values('','$campos[0]','$campos[2]','$campos[3]','$campos[4]','$campos[5]',";
					$query.= "'$campos[7]','$campos[8]','$campos[9]','$campos[10]','$campos[11]','$campos[12]','$campos[13]',";
					$query.= "'$campos[14]','$campos[15]','$campos[16]','$campos[17]','$campos[19]','$_SESSION[IDEMP]','$existe')";
					$res   = mysql_query($query);
				}
			} $row++; }
			fclose ($fd); 
			unlink($d_['valor'].$nombre);
			cargar_p3(); }
	}
}	


function cargar_p3(){ ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="280">ARCHIVO NÚMEROS DE SAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspCargar archivo con números de SAP] body=[Puede cargar el archivo de manera masiva con los números de documento SAP.<br><br>Descargue primero el archivo a excel y luego capture los números correspondientes.<br><br>Guarde el mismo archivo de excel como archivo de texto separado por tabulaciones y cárguelo.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>   

<form action="?op=cargar_p4" method="post" name="form1">
<div align="center" class="aviso">Los registros que aparece en rojo no se actualizarán porque ya tienen un número de documento asignado o los datos de carga están equivocados.</div>
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center">No.Item</td>
	<td align="center">Txs SAP</td>
	<td align="center">Mov</td>
	<td align="center">Código Scrap</td>
	<td align="center">O.I.</td>
	<td align="center">Parte Padre</td>
	<td align="center">Batch ID</td>
	<td align="center">No.Parte</td>
	<td align="center">Cantidad</td>
	<td align="center">Reason Code</td>
	<td align="center">Descripción</td>
	<td align="center">Info.Obl.</td>
	<td align="center">Tipo</td>
    <td align="center">Tipo Sub</td>
	<td align="center">Valor</td>
	<td align="center">Doc. Header Tex</td>
	<td align="center">No.Docto.SAP</td>
</tr>
</thead>
<tbody>
<?php	
	$s_1 = "select * from tmp_docto_sap where id_emp='$_SESSION[IDEMP]' order by no_folio"; 
	$r_1 = mysql_query($s_1); 
	echo "<tr bgcolor=\"#FF9900\"><td colspan=\"17\"></td></tr>";
    while($d_1=mysql_fetch_array($r_1)) { if($d_1['existe']=='1') { $class = 'rojo'; } else { $class='texto'; } ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['no_folio'];?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['txs_sap'];?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['mov'];?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['codigo_scrap'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['orden_interna'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['parte_padre']; ?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['batch_id']; ?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['no_parte'];?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['cantidad'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['reason_code'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['descripcion'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['info_obl'];?></td>
	<td align="center" class="<?php echo $class;?>"><?php echo $d_1['tipo'];?></td>
    <td align="center" class="<?php echo $class;?>"><?php echo $d_1['tipo_sub']; ?></td>
	<td align="right" class="<?php echo $class;?>"><?php echo "$ ".number_format($d_1['valor'],2);?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['doc_header_tex'];?></td>
	<td align="left" class="<?php echo $class;?>"><?php echo $d_1['no_docto_sap'];?></td>
</tr>	
<?php } ?>
</tbody>
</table>
<br><div align="center">
	<input type="button" class="submit" value="Cancelar" onclick="cancelar();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" class="submit" value="Guardar">
</div></form><br><br>    
<?php }


function cargar_p4() {
	$fecha = date("Y-m-d");
	$hora  = date("H:i:s");
	
	$s_ = "select * from tmp_docto_sap where id_emp='$_SESSION[IDEMP]' and existe='0'";
	$r_ = mysql_query($s_); $i=0;
	while($d_=mysql_fetch_array($r_)) {
	if(strlen(trim($d_['no_docto_sap']))>=10) { 
		$s_1 = "update scrap_partes set docto_sap='".trim($d_['no_docto_sap'])."' where no_folio='$d_[no_folio]' and no_parte='$d_[no_parte]' ";
		$s_1.= "and padre='$d_[parte_padre]' and (docto_sap='0' or docto_sap='')";
		$r_1 = mysql_query($s_1); 

		//Insertar en la bitácora de eventos quién cerró esa boleta con SAP
		$s_1 = "insert into aut_bitacora values('', '$d_[no_folio]', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '7', '$fecha', '$hora', ";
		$s_1.= "'CAPTURA DE SAP: ".$d_['no_parte']." (".trim($d_['no_docto_sap']).")', '')";
		$r_1 = mysql_query($s_1); 

	$i++; }	}
	echo "<script>alert('$i registros actualizados');</script>";
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>