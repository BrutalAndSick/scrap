<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.motivo.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.motivo.value='';
		alert('Es necesario ingresar el motivo');
		form1.nombre.focus(); return; }
	form1.submit();	
}

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_plantas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_plantas','motivos_cancel'); ?></td>
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
		case "nuevo"	:	nuevo(); break;
		case "guardar"	:	guardar($motivo); nuevo(); break;
	
		case "listado"	:	listado($orden,$pagina); break;
		case "estado"	:	estado($id_,$estado,$motivo); listado($orden,$pagina); break;
		case "editar"	:	editar($id_); break;
		case "update"	:	update($id_,$motivo); listado($orden,$pagina); break;
		
		case "borrar"	:	borrar($id_,$motivo); listado($orden,$pagina);	 break;		
		default			:	listado($orden,$pagina); break;
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
	<td class="titulo" width="230">MOTIVOS CANCELAR</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function nuevo() { ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=guardar" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Motivo Cancelar</caption>
<tr>
	<td>Motivo:</td>
	<td><input type="text" name="motivo" class="texto" size="60">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function guardar($motivo) {
	$motivo = trim($motivo);
	$existe = ver_si_existe($motivo);	
	if($existe=='NO') {
		$s_1 = "insert into motivos_cancel values('','$motivo','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($motivo) {
	$s_1 = "select * from motivos_cancel where motivo='$motivo' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) { return 'NO'; }
		else { return 'SI'; }
}


function listado($orden,$pagina) {
   if(!$pagina) $pagina = '1';
   if(!$orden)  $orden  = 'motivo'; ?>
   <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div><br>  

   <?php
	$s_1 = "select * from motivos_cancel where activo!='2' order by motivo DESC, $orden ASC";
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
			echo "<a href='?op=listado&orden=$orden&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>

<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
        <td width="50" align="center">Estado</td>
        <td width="350" align="center">Motivo</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
	<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
	      $r_1 = mysql_query($s_1); 
	while($d_1=mysql_fetch_array($r_1)) { 
	$ruta = "&orden=$orden&motivo=$d_1[motivo]"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
	<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'><img src='../imagenes/tick.png' alt='Activo' border='0'>";     } 
	      if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'><img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['motivo'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>">
        	<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a>
	</td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>
	</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function editar($id_) { 
	$s_1 = "Select * from motivos_cancel where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=update" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Motivo Cancelar</caption>
<tr>
	<td>Motivo:</td>
	<td><input type="text" name="motivo" class="texto" size="60" value="<?php echo $d_1['motivo'];?>">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function update($id_,$motivo) {
	$motivo = trim($motivo);
	$s_1 = "update motivos_cancel set motivo='$motivo' where id='$id_'"; 
	$r_1 = mysql_query($s_1); 
}	


function borrar($id_,$nombre) {
	$s_1 = "delete from motivos_cancel where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	


function estado($id_,$estado,$nombre) {
	$s_1 = "update motivos_cancel set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); }
?>