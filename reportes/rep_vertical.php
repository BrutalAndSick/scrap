<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function exportar() {
	form1.action='excel_reportes.php?op=vertical&reporte=vertical';
	form1.submit();	
	form1.action='?op=listado';
}imhg

function search() {
	form1.action='?op=listado&boton=1';
	form1.submit();	
}

function filtro_oes(){
	form1.action='?op=listado';
	form1.submit();	
}

function sig(pag){
	var pag = parseInt(pag)+1;
	form_1.action = '?op=ver&pag='+pag+"&bandera=1";
	form_1.submit();
}

function antes(pag){
	var pag = parseInt(pag)-1;
	form_1.action = '?op=ver&pag='+pag+"&bandera=1";
	form_1.submit();
}

function regresar(){
	form_1.action = '?op=listado';
	form_1.submit();	
}

function solo_numeros(evt){
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57));
}

function enviar(){
	form1.action = '?op=ver';
	form1.submit();	
}

function guardar(pag){
	var faltan  = 0;
	var error   = 0;
	var valido  = 0;
	if(form_1.sap.value==""){ 
		faltan++; 
	} else {
		var valor = form_1.sap.value;
		if(valor.length>=10){
			valido++;
		} else {
			error++;	
		}
	}
	if(valido==1){
		form_1.action='?op=guardar&pag='+pag;
		form_1.submit();	
	} else {
		if(faltan>0){
			alert("Faltan Num. de Doc, favor de llenar todos los campos.");
		} if(error>0){
			alert("Favor de revisar el Num. de Doc");	
		}
		return;
	}
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','vertical'); ?></td>
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
		<?php 
		   switch($op) {
			case "listado"		:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$bandera); break;
			case "ver"			:	ver($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$pag,$bandera); break;							
			case "guardar"		:	guardar($sap,$pag); 
									ver($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$pag+1,1); break;
			default				:	listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$bandera); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
  </tr>
</table>
<?php include("../footer.php");

function listado($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$bandera) {
	if(!$aplica_oes) $aplica_oes = 'no';
	if(!$pagina) $pagina = 1; 
	if(!$f_material) $f_material = "%"; 
	if(!$f_sub) $f_sub = "%"; 
	if(!$f_txs) $f_txs = "%";
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
	<td class="titulo" width="210">REPORTE VERTICAL</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte Vertical] body=[Este es el reporte vertical donde se muestra todo el scrap aprobado de manera vertical y agrupado por la sumatoria de cantidad de piezas para asignarle un número de documento SAP.<br><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
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
<tr>
	<td align="center" width="100" bgcolor="#E6E6E6">TXS</td>
	<td align="center" width="100" bgcolor="#E6E6E6">Tipo Material</td>
	<td align="center" width="100" bgcolor="#E6E6E6">Tipo Sub</td>
</tr>
<tr>
	<td align="left">
	<select name="f_txs" class="texto" style="width:105px;">
		<option value="%" <?php if($f_txs=="%"){?> selected="selected" <?php }?>>Todos</option>
        <option value="MB1A" <?php if($f_txs=="MB1A"){?> selected="selected" <?php }?>>MB1A</option>
        <option value="ZSCR" <?php if($f_txs=="ZSCR"){?> selected="selected" <?php }?>>ZSCR</option>
	</select></td>
	<td align="left">
	<select name="f_material" class="texto" style="width:105px;" onchange="submit();">
		<option value="%"    <?php if($f_material=="%"){?> selected="selected" <?php $dis = "disabled"; $f_sub="%"; }?>>Todos</option>
        <option value="FERT" <?php if($f_material=="FERT"){?> selected="selected" <?php $dis = "disabled"; $f_sub="%"; }?>>FERT</option>
        <option value="HALB" <?php if($f_material=="HALB"){?> selected="selected" <?php $dis = ""; }?>>HALB</option>
        <option value="ROH"  <?php if($f_material=="ROH") {?> selected="selected" <?php $dis = "disabled"; $f_sub="%"; }?>>ROH</option>
	</select></td>
    <td align="left">
	<select name="f_sub" class="texto" style="width:105px;" onchange="submit();" <?php echo $dis;?>>
		<option value="%">Todos</option>
        <option value="REAL" <?php if($f_sub=="REAL"){?> selected="selected" <?php }?>>S.Real</option>
        <option value="AUTO" <?php if($f_sub=="AUTO"){?> selected="selected" <?php }?>>S.Real/AutoBF</option>
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
    <input type="button" value="Buscar" class="submit" onclick="enviar();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div><br>
<?php  }

function ver($fechai,$fechaf,$proy_add,$proy_del,$aplica_oes,$buscar,$filtros,$stock,$pagina,$f_material,$f_sub,$f_txs,$pag,$bandera){
	if(!$pag) $pag = '1';
	if(!$bandera){
		$s_1 = "delete from tmp_vertical where id_emp='$_SESSION[IDEMP]'";
		$r_1 = mysql_query($s_1);
		
		$s_f = "select folios.*, partes.id as idp, partes.no_parte, partes.descripcion, partes.cantidad, partes.total as costo_total, ";
		$s_f.= "partes.ubicacion, partes.docto_sap, partes.deficit, partes.tipo, partes.tipo_sub, partes.padre, partes.batch_id, ";
		$s_f.= "partes.serial_unidad from scrap_partes as partes, scrap_folios as folios, autorizaciones, numeros_parte where folios.no_folio = partes.no_folio and ";
		$s_f.= "autorizaciones.no_folio = folios.no_folio and folios.status='1' and folios.activo='1' and (partes.docto_sap='0' or partes.docto_sap='') and ";
		$s_f.= "partes.tipo like '$f_material' and txs_sap like '$f_txs' and partes.no_parte = numeros_parte.nombre ";
		if($f_sub=="REAL") { $s_f.= " and partes.tipo_sub like 'S.Real'";  }
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
		$r_1 = mysql_query($s_f); $i=0;
		while($d_1 = mysql_fetch_array($r_1)){
			$total = $folios[$d_1['no_folio']];
			
			$header = get_doc_header($d_1['info_1'],$d_1['info_2'],$d_1['segmento'],$d_1['proyecto'],$d_1['no_folio'],$d_1['cantidad']);
			$s_  = "insert into tmp_vertical values ('', '$_SESSION[IDEMP]', '', '$d_1[no_folio]', '$d_1[fecha]', '$d_1[txs_sap]', '$d_1[apd]', ";
			$s_.= "'$d_1[padre]', '$d_1[no_parte]','$d_1[cantidad]','$d_1[costo_total]', '$d_1[reason_code]', '$d_1[batch_id]', '$d_1[deficit]', '$d_1[tipo]', ";
			$s_.= "'$d_1[tipo_sub]', '$d_1[info_1]', '$d_1[info_2]', '$d_1[segmento]', '$d_1[proyecto]', '$d_1[idp]', '$header')";
			$r_ = mysql_query($s_);	
		}
	}
	$s_f = "select * from tmp_vertical where id_emp='$_SESSION[IDEMP]' group by no_parte, reason_code, dto_header order by no_folio asc ";
	$r_f = mysql_query($s_f); $i=1;
	while($d_f = mysql_fetch_array($r_f)){			
		$s_1 = "update tmp_vertical set grupo='$i' where no_parte='$d_f[no_parte]' and reason_code='$d_f[reason_code]' and dto_header='$d_f[dto_header]' ";
		$s_1.= "and id_emp='$_SESSION[IDEMP]'";
		$r_1 = mysql_query($s_1);
		$i++;
	}
	
	$s_ = "select * from tmp_vertical where id_emp='$_SESSION[IDEMP]' group by grupo";
	$r_ = mysql_query($s_);
	$n_ = mysql_num_rows($r_); 
	$ruta = "&fechai=$fechai&fechaf=$fechaf&reason=$reason&buscar[0]=$buscar[0]&filtros[0]=$filtros[0]";
	$ruta.= "&buscar[1]=$buscar[1]&filtros[1]=$filtros[1]&buscar[2]=$buscar[2]&filtros[2]=$filtros[2]&stock=$stock";?>
	<form name="form_1" action="?op=ver" method="post">
    <table align="center">
    	<tr>
        	<td align="center" valign="middle">
            	<?php if($pag=='1'){ ?>
                	<img src="../imagenes/ant_gris.png" title="Anterior" width="64">
                <?php } else { ?>
                	<img src="../imagenes/ant.png" title="Anterior" width="64" onclick="antes('<?php echo $pag;?>')" style="cursor:pointer;">
                <?php } ?>
            </td>
            <td align="center">	
                <table align="center" class="tabla" cellpadding="-1" cellspacing="-1">
                <tbody>
                    <tr bgcolor="#FFFFFF" height="20">
                    	<td>
                            <table border="0" cellpadding="0" cellspacing="2">
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_material.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_planta.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_cantidad.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_sloc.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_batch.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_mov.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_num.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_blank.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_blank.png" width="95"></td>
                                 </tr>
                                 <tr height="20">
                                    <td align="center" background="../imagenes/v_header.png" width="95"></td>
                                 </tr>
                             </table>
                         </td>
                         <td>
                            <table border="0" cellpadding="0" cellspacing="2">
                                 <tr height="20">
                                    <?php $r_1 = query("material",$pag);
									while($d_1 = mysql_fetch_array($r_1)){ $parte = $d_1['no_parte'];?>
                                    	<td align="left" background="../imagenes/v_fondo.png"><?php echo $d_1['no_parte'];?></td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("planta",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png"><?php echo $d_1['planta'];?></td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("cantidad",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png"><?php echo intval($d_1['cantidad']);?></td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("apd",$pag);
									while($d_1 = mysql_fetch_array($r_1)){ 
										switch(strlen($d_1['apd'])){
											case '1':	$apd = "00".$d_1['apd']; break;
											case '2':   $apd = "0".$d_1['apd'];  break;
											case '3':   $apd = $d_1['apd'];      break;
										}?>
                                     	<td align="left" background="../imagenes/v_fondo.png"><?php echo $apd;?></td><?php } ?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("batch",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png"><?php if($d_1['batch_id']!="NA"){ echo $d_1['batch_id']; }?></td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("reason",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png"><?php echo $d_1['reason_code'];?></td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("vacio",$pag); $j=0;
									while($d_1 = mysql_fetch_array($r_1)){?>
										<td align="left" background="../imagenes/v_fondo.png">
										<input type="text" name="sap" class="texto" size="15" onkeypress="return solo_numeros(event);" id="sap">
										</td><?php $j++; } ?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("vacio",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png">&nbsp;</td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("vacio",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
									<td align="left" background="../imagenes/v_fondo.png">&nbsp;</td><?php }?>
                                 </tr>
                                 <tr height="20">
                                    <?php $r_1 = query("header",$pag);
									while($d_1 = mysql_fetch_array($r_1)){?>
                                        <td align="left" background="../imagenes/v_fondo.png">
                                        <?php echo $d_1['dto_header'];?></td>
									<?php } ?>
                                 </tr>
                             </table>
                         </td>
                    </tr>
                </tbody>
                </table>
           </td>
           <td align="center" valign="middle">
           	<?php if($pag==$n_){?>
           		<img src="../imagenes/sig_gris.png" title="Siguiente" width="64">
            <?php } else {?>
            	<img src="../imagenes/sig.png" title="Siguiente" width="64" onclick="sig('<?php echo $pag;?>');" style="cursor:pointer;">
            <?php } ?>
           </td>
    	</tr>
	</table>
    <br><div align="center">
    	<input type="button" class="submit" value="Regresar" onclick="regresar();">
    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" class="submit" value="Guardar" onclick="guardar('<?php echo $pag;?>');">
    </div>
    </form><br><br>
<?php }

function guardar($sap,$pag){
	$s_ = "select * from tmp_vertical where grupo='$pag' and id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)){ 
		$s_1 = "update scrap_partes set docto_sap='$sap' where id='$d_[idp]' and (docto_sap='0' or docto_sap='')";
		$r_1 = mysql_query($s_1); 

		//Insertar en la bitácora de eventos quién cerró esa boleta con SAP
		$s_1 = "insert into aut_bitacora values('', '$folio', '$_SESSION[DEPTO]', '$_SESSION[IDEMP]', '$_SESSION[NAME]', '7', CURDATE(), CURTIME(), ";
		$s_1.= "'CAPTURA DE SAP: $d_[no_parte] ($sap)', '')";
		$r_1 = mysql_query($s_1);	
	} 	
	//Borro temporal del reporte vertical
	$s_1 = "delete from tmp_vertical where grupo='$pag'";
	$r_1 = mysql_query($s_1);
	//CAMBIO PAGINACIÓN DE REPORTE
	$s_1 = "update tmp_vertical set grupo=grupo-1 where grupo>'$pag' ";
	$r_1 = mysql_query($s_1);
}

function get_doc_header($info_1,$info_2,$segmento,$proyecto,$folio,$cantidad) {
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

function query($columna,$pag){ 
	if($columna=="material"){ $s_f = "select no_parte ";    }
	if($columna=="padre")   { $s_f = "select padre ";       }
	if($columna=="planta")  { $s_f = "select planta ";      }
	if($columna=="cantidad"){ $s_f = "select sum(cantidad) as cantidad ";    }
	if($columna=="apd")     { $s_f = "select apd ";         }
	if($columna=="batch")   { $s_f = "select batch_id ";    }
	if($columna=="reason")  { $s_f = "select reason_code "; }
	if($columna=="header")  { $s_f = "select dto_header "; }
	if($columna=="planta")	{ $s_f = "select '3011' as planta "; }
	if($columna=="vacio")	{ $s_f = "select '' as vacio "; }
	$s_f.= "from tmp_vertical where id_emp='$_SESSION[IDEMP]' and grupo='$pag' group by grupo order by id asc"; 
	$r_1 = mysql_query($s_f); 
	return $r_1;
}