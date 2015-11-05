<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.vendor.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.vendor.value='';
		alert('Es necesario ingresar el vendor');
		form1.vendor.focus(); return; }
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el nombre');
		form1.nombre.focus(); return; }
	form1.submit();	
}

function upload() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='csv') {
		alert('Utilice solamente archivos .csv');
		form1.archivo.focus(); return; }
			
	form1.action='?op=upload_file';
	form1.submit();	
}


function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_usuarios'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_usuarios','vendors'); ?></td>
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
		case "upload_form"		:	upload_form(); break;
		case "upload_file"		: 	upload_file($archivo,$archivo_name); break;
		case "guardar_temp"		:	guardar_temp();	listado($f_planta,$f_division,$f_segmento,$orden); break;	

		case "nuevo"			:	nuevo(); break;
		case "guardar"			:	guardar($vendor,$nombre); nuevo(); break;
	
		case "listado"			:	listado($buscar,$pagina); break;
		case "estado"			:	estado($id_,$estado,$vendor,$nombre); listado($buscar,$pagina); break;
		case "editar"			:	editar($id_); break;
		case "update"			:	update($id_,$vendor,$nombre); listado($buscar,$pagina); break;
	
		case "borrar"			:	borrar($id_,$vendor,$nombre); listado($buscar,$pagina);	 break;		
		default					:	listado($buscar,$pagina); break;
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
	<td class="titulo" width="150">VENDORS</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="excel.php?op=vendors" class="menuLink">Download</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function upload_form(){ 
	$s_1 = "delete from tmp_vendors"; $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo CSV con Vendors</caption>
<tbody>
<tr>
	<td valign="top">Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="button" value="Guardar" class="submit" onclick="upload();">
</div>
</form>
<div align="center" class="aviso_naranja">Se insertarán solamente los registros que no existan</div>
<br><div align="center">
	<table align="center" width="450" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="5" valign="top">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Vendors</b></td></tr>
	<tr><td class="gris" align="left">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Los encabezados del archivo deben ser minúsculas sin acentos ó ñ</td>	
	</tr>
	<tr><td class="gris" align="left">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La primera columna contiene el número del vendor</td>	
	</tr>	
	<tr><td class="gris" align="left">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LA segunda columna contiene el nombre del vendor</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="archivos/ejemplo_vendors.csv"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>			
	</table>
</div>
<?php  } 


function nuevo() { ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=guardar" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Vendor</caption>
<tr>
	<td valign="top">Vendor:</td>
	<td><input type="text" name="vendor" class="texto" size="75"></td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="75"></td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function guardar($vendor,$nombre) {
	$nombre = trim($nombre);

	$existe = ver_si_existe($vendor,$nombre);
	if($existe=='NO') {
		$s_1 = "insert into vendors values('','$vendor','$nombre','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($vendor,$nombre) {
	$s_1 = "select * from vendors where (vendor='$vendor' or nombre='$nombre') and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}

function listado($buscar,$pagina) { 
	if(!$pagina) $pagina = '1'; ?>
    <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div><br>
<?php
	$s_1 = "select * from vendors where activo!='2' and vendor like '%$buscar%' order by activo desc, vendor asc";
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
			echo "<a href='?op=listado&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>
    
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
<tr>
	<td width="60" align="center">Vendor:</td>
    <td><input type="text" name="buscar" value="<?php echo $buscar;?>" size="40" class="texto"></td>
    <td><input type="button" value="Buscar" onclick="submit();" class="submit"></td>
</tr>
</table><br>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
    	<td width="50" align="center">No.</td>
		<td width="50" align="center">Estado</td>
		<td width="250" align="center">Vendor</td>
        <td width="350" align="center">Nombre</td>
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
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0&orden&nombre=$d_1[nombre]'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1&nombre=$d_1[nombre]'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['vendor'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?>&nombre=<?php echo $d_1['nombre'];?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function editar($id_) { 
	$s_1 = "Select * from vendors where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=update" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Vendor</caption>
<tr>
	<td valign="top">Vendor:</td>
	<td><input type="text" name="vendor" class="texto" size="75" value="<?php echo $d_1['vendor'];?>"></td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="75" value="<?php echo $d_1['nombre'];?>"></td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function update($id_,$vendor,$nombre) {
	$nombre = trim($nombre);
	$s_1 = "update vendors set vendor='$vendor', nombre='$nombre' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	


function borrar($id_,$vendor,$nombre) {
	$s_1 = "update vendors set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	


function estado($id_,$estado,$vendor,$nombre) {
	$s_1 = "update vendors set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); 
} 


function upload_file($archivo,$archivo_name) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = date("YmdHis").".".$pext;
	$nom_final = $r_server.$nombre;
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			upload_form(); exit; }
		else { 
			insert_csv($nombre); }
	}
}	

function insert_csv($alias) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."$alias", "r");
	$insertar = 0;

	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		$campos = split (",", $buffer); $error = '';		

		if($campos['0']!='' && $campos['0']!='vendor') {
			$vendor = trim($campos['0']);
			$nombre = trim($campos['1']);
		
			if($vendor=='') {
				$insertar++; $error .= "Error en el vendor: $campos[0]"; }
			if($nombre=='') {
				$insertar++; $error .= "Error en el nombre: $campos[1]"; }				

			if($insertar<=0) {
				$query = "INSERT into tmp_vendors values('', '$vendor', '$nombre', '$alias', '$fecha')"; 
				mysql_query($query); $ins++; }
			else { 
				echo "<br><div class=aviso_naranja align=center>".$error;
				echo "Verifique que el archivo tenga el formato necesario y que los registros no estén vacíos<br>";
				echo "<br><br>No se puede continuar con la carga !!</div><br>"; 
				exit; }				
			$insertar=0;			
		}			
	}     
	echo "<script>alert('Se han cargado los registros');</script>";
	fclose ($fd); 
	unlink($r_server.$alias);
	listado_temporal(); 
}



function listado_temporal() { ?>
<div align="center" class="aviso">Por favor, verifique si la información para la carga es correcta</div>
<form action="?op=guardar_upload" method="post" name="form1">
<table align="center" class="tabla">
<caption>¿Es correcta la información?</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="200" align="center">Vendor</td>
		<td width="400" align="center">Nombre</td>	
	</tr>
</thead>
<?php 
   $s_1 = "select * from tmp_vendors order by id";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['vendor'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<div align="center"><br>
<input type="button" value="Cancelar" onclick="cancelar('?op=upload_form')" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Continuar" onclick="cancelar('?op=guardar_temp')" class="submit">
</div>
</form>
<?php }


function guardar_temp() {

$s_2 = "select * from tmp_vendors order by vendor";
$r_2 = mysql_query($s_2); $i=0;
	while($d_2=mysql_fetch_array($r_2)) { 
		$s_ = "select * from vendors where vendor='$d_2[vendor]' and nombre='$d_2[nombre]' and activo!='2'"; 
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { //SÓLO SI NO EXISTE LO INSERTO		
			$s_1 = "insert into vendors values('', '$d_2[vendor]', '$d_2[nombre]', '1')";
			$r_1 = mysql_query($s_1); $i++; }
	} echo"<script>alert('$i registros insertados');</script>";		
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>