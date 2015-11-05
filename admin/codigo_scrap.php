<?php include("../header.php"); ?>

<script>
function validar(ruta) {
	if(form1.codigo.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.codigo.value='';
		alert('Es necesario ingresar el código');
		form1.codigo.focus(); return; } 
	if(form1.reason_code.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.reason_code.value='';
		alert('Es necesario ingresar el reason code');
		form1.reason_code.focus(); return; } 
	form1.action=ruta;
	form1.submit();	
}

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_areas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_areas','codigo_scrap'); ?></td>
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
		case "nuevo"	:	nuevo($codigo,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$depto1,$depto2,$txs_sap,
							$mov_sap); break;
		case "guardar"	:	guardar($codigo,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$depto1,$depto2,$txs_sap,
							$mov_sap); nuevo('','','','','','','','','',''); break;
	
		case "listado"	:	borrar_no_guardados(); listado($orden,$f_orden,$pagina); break;
		case "estado"	:	estado($id_,$estado,$nombre,$orden,$f_orden); listado($orden,$f_orden,$pagina); break;
		case "editar"	:	editar($id_,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$txs_sap,$mov_sap,$depto1,
							$depto2,$orden,$f_orden); break;
		case "update"	:	update($id_,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$txs_sap,$mov_sap,$orden,
							$f_orden,$codigo); listado($orden,$f_orden,$pagina); break;
	
		case "borrar"	:	borrar($id_,$nombre); listado($orden,$f_orden,$pagina);	 break;		
		default			:	borrar_no_guardados(); listado($orden,$f_orden,$pagina); 	 break;
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
	<td class="titulo" width="200">CÓDIGO SCRAP</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function borrar_no_guardados() {
	$s_1 = "delete from codigo_scrap_depto where saved='0'";
	$r_1 = mysql_query($s_1);
}


function nuevo($codigo,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$depto1,$depto2,$txs_sap,$mov_sap) {
if($depto1 && $codigo!='') { $s_1 = "insert into codigo_scrap_depto values('','$codigo','$depto1','0')"; }
if($depto2 && $codigo!='') { $s_1 = "delete from codigo_scrap_depto where id='$depto2'"; }
$r_1 = mysql_query($s_1); ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Código de Scrap</caption>
<tr>
	<td valign="top">Código:*</td>
	<td colspan="3"><input type="text" name="codigo" class="texto" size="45" value="<?php echo $codigo;?>" onkeyup="crear_reason_code();"></td>
</tr>
<tr>
	<td valign="top">Reason Code:*</td>
	<td colspan="3"><input type="text" name="reason_code" class="texto" size="45" value="<?php echo $reason_code;?>"></td>
</tr>
<tr>
	<td valign="top">Descripción:</td>
	<td colspan="3"><textarea name="descripcion" class="texto" cols="47" rows="3"><?php echo $descripcion;?></textarea></td>
</tr>
<tr height="40">
	<td valign="middle" colspan="4" align="center">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<?php if($orden_interna=='1') { $check_1 = 'checked'; } else { $check_1 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspOrden Interna] body=[Seleccione esta casilla si el código requiere de un número de orden interna.]"><input type="checkbox" name="orden_interna" value="1" <?php echo $check_1;?>></span></td>
			<td align="left">O.I.</td>
			<td width="20">&nbsp;</td>
			<?php if($info_ad=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspInformación Adicional] body=[Seleccione esta casilla si el código requiere de información adicional obligatoria (ECN, CO, VUT o VUG, QN, LSR, etc.)]"><input type="checkbox" name="info_ad" value="1" <?php echo $check_2;?>></span></td>
			<td align="left">Información Adicional</td>		
		</tr>
        <tr>
			<?php if($financiero=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspCódigo Financiero] body=[Seleccione esta casilla si el código es financiero y necesita capturar la causa original en la boleta de scrap]"><input type="checkbox" name="financiero" value="1" <?php echo $check_2;?>></span></td><td align="left">Financiero</td>	            
			<td width="20">&nbsp;</td>
			<?php if($vendor=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbsp;Vendor] body=[Seleccione esta casilla si el código requiere de un número de vendor obligatorio.)]"><input type="checkbox" name="vendor" value="1" <?php echo $check_2;?>></span></td>
			<td align="left">Requiere Vendor</td>	
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td valign="top">TXs SAP:</td>
	<td colspan="3"><input type="text" name="txs_sap" class="texto" size="45" value="<?php echo $txs_sap;?>"></td>
</tr>
<tr>	
	<td valign="top">Mov. SAP:</td>
	<td colspan="3"><input type="text" name="mov_sap" class="texto" size="45" value="<?php echo $mov_sap;?>"></td>
</tr>	
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar('?op=guardar');" class="submit"></div>
</form>
<?php  } 


function guardar($codigo,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$depto1,$depto2,$txs_sap,$mov_sap) {
	$codigo = trim($codigo);
	$reason_code = trim($reason_code);
	$descripcion = trim($descripcion);
	$txs_sap = trim($txs_sap);
	$mov_sap = trim($mov_sap);

	$existe = ver_si_existe($codigo);
	if($existe=='NO') {
		$s_1 = "insert into codigo_scrap values('', '$codigo', '$reason_code', '$descripcion', '$orden_interna', '$info_ad', '$txs_sap',";
		$s_1.= "'$mov_sap','$financiero','$vendor','1')"; 
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($codigo) {
	$s_1 = "select * from codigo_scrap where codigo='$codigo' and activo='1'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($orden,$f_orden,$pagina) {
	if(!$pagina)    $pagina  = '1';
	if(!$f_orden)	$f_orden = '%';
	if(!$orden) 	$orden	 = 'codigo'; ?>
    <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div><br>
   
<?php
	$s_1 = "select * from codigo_scrap where activo!='2' and orden_interna like '$f_orden' order by activo desc, $orden asc";
    $r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1);
	$pags = ceil($n_1/50);
    $ini_ = ($pagina-1)*50;
    $fin_ = 50; $i=1;

	if($pags>0) {      
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='texto'>";
		echo "<tr height='25'>";
			echo "<td width='120' align='center' bgcolor='#D8D8D8' style='border:#CCCCCB solid 1px;'>$n_1&nbsp;Registros</td>";
			echo "<td width='3'></td>";		
			while($i<=$pags) {
			if($pagina==$i) { $bg_img = '../imagenes/pag_on.jpg'; } else { $bg_img = '../imagenes/pag_off.jpg'; }
			echo "<td width='25' align='center' background='$bg_img' style='border:#CCCCCB solid 1px;'>";
			echo "<a href='?op=listado&f_orden=$f_orden&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>   
    
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla" width="95%">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
        <td width="50" align="center">Estado</td>
		<td width="80" align="center"><a href="?op=listado&orden=codigo&f_orden=<?php echo $f_orden;?>" class="linkTabla">Código</a></td>
		<td width="60" align="center"><a href="?op=listado&orden=reason_code&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Reason Code</a></td>
		<td width="220" align="center"><a href="?op=listado&orden=descripcion&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Descripción</a></td>
		<td width="60" align="center"><a href="?op=listado&orden=txs_sap&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Transacción</a></td>
		<td width="60" align="center"><a href="?op=listado&orden=mov_sap&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Mov. Sap</a></td>
		<td width="60" align="center"><a href="?op=listado&orden=orden_interna&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Orden Interna</a></td>			
		<td width="80" align="center"><a href="?op=listado&orden=orden_interna&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Info.Adicional</a></td>	
		<td width="60" align="center"><a href="?op=listado&orden=orden_interna&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Financiero</a></td>	            
		<td width="60" align="center"><a href="?op=listado&orden=vendor&f_orden=<?php echo $f_orden;?>" class="linkTabla">
			Vendor</a></td>	  
        <td width="50" align="center" colspan="2">Users</td>
        <td width="50" align="center" colspan="2">Proys.</td>
		<td width="50" align="center" colspan="2">Deptos.</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0&orden=$orden&nombre=$d_1[codigo]&f_orden=$f_orden'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1&orden=$orden&nombre=$d_1[codigo]&f_orden=$f_orden'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="center"><?php echo $d_1['codigo'];?></td>
	<td align="center"><?php echo $d_1['reason_code'];?></td>
	<td align="left">&nbsp;
		<?php if(strlen($d_1['descripcion'])>50) {
			echo substr($d_1['descripcion'],0,50)."..."; }
		   else { echo $d_1['descripcion']; } ?></td>
	<td align="left">&nbsp;<?php echo $d_1['txs_sap'];?></td>
	<td align="left">&nbsp;<?php echo $d_1['mov_sap'];?></td>
	<td align="center">
		<?php if($d_1['orden_interna']=='1') { echo "<input type='checkbox' checked='checked' disabled='disabled'>"; }
		   if($d_1['orden_interna']=='0') { echo "<input type='checkbox' disabled='disabled'>"; } ?></td>
	<td align="center">
		<?php if($d_1['info_ad']=='1') { echo "<input type='checkbox' checked='checked' disabled='disabled'>"; }
		   if($d_1['info_ad']=='0') { echo "<input type='checkbox' disabled='disabled'>"; } ?></td>		   
	<td align="center">
		<?php if($d_1['financiero']=='1') { echo "<input type='checkbox' checked='checked' disabled='disabled'>"; }
		   if($d_1['financiero']=='0') { echo "<input type='checkbox' disabled='disabled'>"; } ?></td>	
	<td align="center">
		<?php if($d_1['vendor']=='1') { echo "<input type='checkbox' checked='checked' disabled='disabled'>"; }
		   if($d_1['vendor']=='0') { echo "<input type='checkbox' disabled='disabled'>"; } ?></td>	
	<td align="center" width="20">
    	<a class="frame_cod_emp" href="detalles.php?op=codigo_emp&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/user.png" border="0"></a></td>
    <td align="center" width="30">
    	<?php $s_2 = "select * from codigo_scrap_emp where codigo='$d_1[codigo]'";
		      $r_2 = mysql_query($s_2);
			  $n_2 = mysql_num_rows($r_2); ?>    
    		  [<?php echo $n_2;?>]</td>
    <td align="center" width="20">
    	<a class="frame_cod_proy" href="detalles.php?op=codigo_proy&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/right.gif" border="0"></a></td>
    <td align="center" width="30">
    	<?php $s_2 = "select * from codigo_scrap_proy where codigo='$d_1[codigo]'";
		      $r_2 = mysql_query($s_2);
			  $n_2 = mysql_num_rows($r_2); ?>    
    		  [<?php echo $n_2;?>]</td>
    <td align="center" width="20">
    	<a class="frame_cod_scrap" href="detalles.php?op=codigo_scrap&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/right.gif" border="0"></a></td>
    <td align="center" width="30">
    	<?php $s_2 = "select * from codigo_scrap_depto where codigo_scrap='$d_1[codigo]'";
		      $r_2 = mysql_query($s_2);
			  $n_2 = mysql_num_rows($r_2); ?>    
    		  [<?php echo $n_2;?>]</td>	
    <td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?>&f_orden=<?php echo $f_orden;?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a>
	</td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?>&nombre=<?php echo $d_1['codigo'];?>&f_orden=<?php echo $f_orden;?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function ya_existe_depto($codigo,$tipo) {
	$s_ = "select * from codigo_scrap_depto where codigo_scrap='$codigo' and tipo='$tipo'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {	return "SI"; } else { return "NO"; }
}


function editar($id_,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$txs_sap,$mov_sap,$depto1,$depto2,$orden,$f_orden){ 
	$s_1 = "Select * from codigo_scrap where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); 
		$codigo = $d_1['codigo'];
		if(!$reason_code) $reason_code = $d_1['reason_code']; 
		if(!$descripcion) $descripcion = $d_1['descripcion']; 
		if(!$orden_interna) $orden_interna = $d_1['orden_interna'];
		if(!$info_ad) 	 $info_ad    = $d_1['info_ad'];
		if(!$financiero) $financiero = $d_1['financiero'];
		if(!$vendor)     $vendor     = $d_1['vendor'];
		if(!$txs_sap) 	 $txs_sap 	 = $d_1['txs_sap'];
		if(!$mov_sap)    $mov_sap    = $d_1['mov_sap'];

if($depto1 && $codigo) { $s_1 = "insert into codigo_scrap_depto values('','$codigo','$depto1','0')"; }
if($depto2 && $codigo) { $s_1 = "delete from codigo_scrap_depto where id='$depto2'"; }
$r_1 = mysql_query($s_1); ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="f_orden" value="<?php echo $f_orden;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Código de Scrap</caption>
<tr>
	<td valign="top">Código:*</td>
	<td colspan="3">
		<input type="text" name="codigo" class="texto" size="45" value="<?php echo $codigo;?>" <?php echo $dis;?> onkeyup="crear_reason_code();">
	</td>
</tr>
<tr>
	<td valign="top">Reason Code:*</td>
	<td colspan="3"><input type="text" name="reason_code" class="texto" size="45" value="<?php echo $reason_code;?>">
	</td>
</tr>
<tr>
	<td valign="top">Descripción:</td>
	<td colspan="3"><textarea name="descripcion" class="texto" cols="47" rows="3"><?php echo $descripcion;?></textarea>
	</td>
</tr>
<tr height="40">
	<td valign="middle" colspan="4" align="center">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<?php if($orden_interna=='1') { $check_1 = 'checked'; } else { $check_1 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspOrden Interna] body=[Seleccione esta casilla si el código requiere de un número de orden interna.]"><input type="checkbox" name="orden_interna" value="1" <?php echo $check_1;?>></span></td>
			<td align="left">O.I.</td>
			<td width="20">&nbsp;</td>
			<?php if($info_ad=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspInformación Adicional] body=[Seleccione esta casilla si el código requiere de información adicional obligatoria (ECN, CO, VUT o VUG, QN, LSR, etc.)]"><input type="checkbox" name="info_ad" value="1" <?php echo $check_2;?>></span></td>
			<td align="left">Información Adicional</td>	
		</tr>
        <tr>
			<?php if($financiero=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbspCódigo Financiero] body=[Seleccione esta casilla si el código es financiero y necesita capturar la causa original en la boleta de scrap]"><input type="checkbox" name="financiero" value="1" <?php echo $check_2;?>></span></td><td align="left">Financiero</td>	            	
			<td width="20">&nbsp;</td>
			<?php if($vendor=='1') { $check_2 = 'checked'; } else { $check_2 = ''; } ?>
			<td width="30" align="center"><span title="header=[&nbsp;&nbsp;Vendor] body=[Seleccione esta casilla si el código requiere de un número de vendor obligatorio.]"><input type="checkbox" name="vendor" value="1" <?php echo $check_2;?>></span></td><td align="left">Requiere Vendor</td>	  
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td valign="top">TXs SAP:</td>
	<td colspan="3"><input type="text" name="txs_sap" class="texto" size="45" value="<?php echo $txs_sap;?>"></td>
</tr>
<tr>	
	<td valign="top">Mov. SAP:</td>
	<td colspan="3"><input type="text" name="mov_sap" class="texto" size="45" value="<?php echo $mov_sap;?>"></td>
</tr>	
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar('?op=update');" class="submit"></div>
</form>
<?php  } 


function update($id_,$reason_code,$descripcion,$orden_interna,$info_ad,$financiero,$vendor,$txs_sap,$mov_sap,$orden,$f_orden,$codigo) {
	$reason_code = trim($reason_code);
	$descripcion = trim($descripcion);
	$txs_sap = trim($txs_sap);
	$mov_sap = trim($mov_sap);

	$s_1 = "update codigo_scrap set reason_code='$reason_code', descripcion='$descripcion', orden_interna='$orden_interna', ";
	$s_1.= "info_ad = '$info_ad', financiero='$financiero', txs_sap='$txs_sap', mov_sap='$mov_sap', vendor='$vendor' ";
	if($codigo!='') { $s_1 .= ",codigo='$codigo'"; }
	$s_1 .= "where id='$id_'"; 
	$r_1 = mysql_query($s_1); 
}	



function borrar($id_,$nombre) {
	$s_1 = "update codigo_scrap set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$s_1 = "select codigo from codigo_scrap where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	$s_2 = "delete from oi_especial where codigo_scrap='$d_1[codigo]'";
	$r_2 = mysql_query($s_2);	
	require_once("mantto.php"); mantto('');
}	



function estado($id_,$estado,$nombre,$orden,$f_orden) {
	$s_1 = "update codigo_scrap set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1);  }


?>