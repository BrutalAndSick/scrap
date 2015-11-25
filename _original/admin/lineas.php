<?php include("../header.php"); ?>

<script>
function validar(tipo) {
	if(form1.area.value=='') {
		alert('Es necesario seleccionar un área');
		form1.area.focus(); return; }	
	if(form1.estacion.value=='') {
		alert('Es necesario seleccionar una tecnología');
		form1.estacion.focus(); return; }			
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el nombre');
		form1.nombre.focus(); return; }
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update'; }
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

function excel() {
	form1.action = 'excel.php?op=lineas';
	form1.submit();	
	form1.action = 'lineas.php?op=listado';
}

function select_all(total) {
	if(total>1) { 
		if(document.form1.casillas.checked==true) { 
			for(i=0;i<total;i++) { 
				if(document.form1.casilla[i].disabled==false) { 
				document.form1.casilla[i].checked = true; }	
		} } 
		if(document.form1.casillas.checked==false) { 
			for(i=0;i<total;i++) { 
				if(document.form1.casilla[i].disabled==false) { 
				document.form1.casilla[i].checked = false; }	
		} } 	
	} else {
	if(document.form1.casillas.checked==true) { 
		if(document.form1.casilla.disabled==false) { 
		document.form1.casilla.checked = true; } }
	else { 		
		if(document.form1.casilla.disabled==false) { 
		document.form1.casilla.checked = false; } }
	}	
}

function borrar_lineas(){
	if(confirm("Borrar línea(s) seleccionada(s)?")) {
		form1.action='?op=borrar_masivo';
		form1.submit();
	} else {
		return false;	
	}
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_areas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_areas','lineas'); ?></td>
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
		case "guardar_temp"		:	guardar_temp();	listado($f_linea,$f_area,$f_estacion,$pagina); break;	
		
		case "nuevo"			:	nuevo($area); break;
		case "guardar"			:	guardar($area,$estacion, $nombre); nuevo($area); break;

		case "listado"			:	listado($f_linea,$f_area,$f_estacion,$pagina); break;
		case "estado"			:	estado($id_,$estado,$nombre,$f_area,$f_estacion,$f_linea); 
									listado($f_linea,$f_area,$f_estacion,$pagina); break;	
		case "editar"			:	editar($id_,$area,$f_area,$f_segmento,$f_linea); break;
		case "update"			:	update($id_,$area,$estacion,$nombre,$f_area,$f_estacion,$f_linea); 
									listado($f_linea,$f_area,$f_estacion,$pagina); break;
	
		case "borrar"			:	borrar($id_,$nombre,$f_area,$f_estacion,$f_linea); listado($f_linea,$f_area,$f_estacion,$pagina); break;		
		case "borrar_masivo"	:	borrar_masivo($f_linea,$f_area,$f_estacion,$casilla); listado($f_linea,$f_area,$f_estacion,$pagina); break;
		default					:	listado($f_linea,$f_area,$f_estacion,$pagina); break;
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
	<td class="titulo" width="150">LÍNEAS</td>
	<td><a href="?op=nuevo" class="menuLink">Nueva</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>		
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	

function nuevo($area) { ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nueva Línea</caption>
<tr>
	<td valign="top">Área:</td>
	<td>
	<select name="area" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_1 = "select * from areas where activo='1' order by nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>" <?php if($area==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Tecnología:</td>
	<td>
	<select name="estacion" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_1 = "select * from estaciones where activo='1' and id_area='$area' order by nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(1);" class="submit"></div>
</form>
<?php  } 

function guardar($area,$estacion,$nombre) {
	$area = trim($area);
	$estacion = trim($estacion);
	$nombre = trim($nombre);
	
	$existe = ver_si_existe($estacion,$nombre);
	if($existe=='NO') {
		$s_1 = "insert into lineas values('','$area','$estacion','$nombre','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}

function ver_si_existe($estacion,$nombre) {
	$s_1 = "select * from lineas where nombre='$nombre' and id_estacion='$estacion' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}

function listado($f_linea,$f_area,$f_estacion,$pagina) {
	$s_1 = "delete from tmp_lineas"; $r_1 = mysql_query($s_1);
	if(!$pagina)		$pagina = '1';
	if(!$f_linea) 		$f_linea = '%';
	if(!$f_area)  		$f_area = '%';
	if(!$f_estacion)  	$f_estacion = '%'; ?>
	<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos. Seleccione cualquiera de los filtros para mostrar los registros.</div><br>

<?php
	$s_1 = "select areas.nombre as area, estaciones.nombre as estacion, lineas.* from areas, estaciones, lineas where lineas.activo!='2' and areas.activo='1' and ";
	$s_1.= "estaciones.activo='1' and lineas.id_estacion = estaciones.id and lineas.id_area = areas.id and lineas.id_area like '$f_area' and lineas.id_estacion like ";
	$s_1.= "'$f_estacion' and lineas.nombre like '$f_linea' order by activo DESC, area, estacion, nombre";
    $r_1 = mysql_query($s_1); 
	$n_1 = mysql_num_rows($r_1);
	$pags = ceil($n_1/200);
    $ini_ = ($pagina-1)*200;
    $fin_ = 200; $i=1;

	if($pags>0) {      
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='texto'>";
		echo "<tr height='25'>";
			echo "<td width='120' align='center' bgcolor='#D8D8D8' style='border:#CCCCCB solid 1px;'>$n_1&nbsp;Registros</td>";
			echo "<td width='3'></td>";		
			while($i<=$pags) {
			if($pagina==$i) { $bg_img = '../imagenes/pag_on.jpg'; } else { $bg_img = '../imagenes/pag_off.jpg'; }
			echo "<td width='25' align='center' background='$bg_img' style='border:#CCCCCB solid 1px;'>";
			echo "<a href='?op=listado&f_area=$f_area&f_linea=$f_linea&f_estacion=$f_estacion&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } 
	$s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
    $r_1 = mysql_query($s_1);
	$n1  = mysql_num_rows($r_1);?>
	
<form action="?op=listado" method="post" name="form1">
<div align="center">
	<input type="button" value="Exportar" class="submit" onclick="excel();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Borrar" class="submit" onclick="borrar_lineas();">
    </div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
    	<td width="50" align="center"><input type="checkbox" name="casillas" onclick="select_all('<?php echo $n1;?>');"></td>
    	<td width="50" align="center">No.</td>
		<td width="50" align="center"><a href="?op=listado&orden=activo&filtro=<?php echo $filtro;?>" class="linkTabla">Estado</a></td>
		<td width="200" align="center">
	<select name="f_area" style="width:200px;" class="texto" onchange="submit();">
		<option value="%" <?php if($f_area=='%'){?> selected="selected"<?php } ?>>Área</option>
	<?php $s_3 = "select * from areas where activo='1' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_area){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>			
		</td>
		<td width="150" align="center">
	<select name="f_estacion" style="width:150px;" class="texto" onchange="submit();">
		<option value="%" <?php if($f_estacion=='%'){?> selected="selected"<?php } ?>>Tecnología</option>
	<?php $s_3 = "select estaciones.* from areas, estaciones where estaciones.activo='1' and areas.activo='1' and estaciones.id_area = areas.id and id_area='$f_area' ";
	      $s_3.= "order by estaciones.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_estacion){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="200" align="center">
	<?php $s_3 = "select distinct(lineas.nombre) from areas, estaciones, lineas where estaciones.activo='1' and areas.activo='1' and lineas.activo='1' and ";
		  $s_3.= "lineas.id_area = areas.id and lineas.id_estacion = estaciones.id and lineas.id_area like '$f_area' and lineas.id_estacion like '$f_estacion' ";
		  $s_3.= "order by lineas.nombre"; ?>
	<select name="f_linea" style="width:200px;" class="texto" onchange="submit();">
		<option value="%" <?php if($f_linea=='%'){?> selected="selected"<?php } ?>>Nombre</option>
	   <?php $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['nombre'];?>" <?php if($d_3['nombre']==$f_linea){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>
		</td>
		<td width="80" align="center" colspan="2">Proyectos</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { 
      $ruta = "&nombre=$d_1[nombre]&f_area=$f_area&f_estacion=$f_estacion&f_linea=$f_linea"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><input type="checkbox" name="casilla[]" id="casilla" value="<?php echo $d_1['id'];?>"></td>
	<td align="center"><?php echo $i;?></td>
	<td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['area'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['estacion'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="center" width="50"><?php echo get_proyectos($d_1['id']);?></td>
	<td align="center" width="30"><a class="frame_relacionado_grande" href="detalles.php?op=lineas&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/right.gif" border="0"></a></td>	
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }

function get_proyectos($id_) {
	$s_ = "select * from lineas_proy where id_linea='$id_'";
	$r_ = mysql_query($s_);
	return mysql_num_rows($r_); }

function editar($id_,$area,$f_area,$f_estacion,$f_linea) { 
	$s_1 = "Select * from lineas where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1);
	if(!$area) $area=$d_1['id_area']; ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="f_area" value="<?php echo $f_area;?>">
<input type="hidden" name="f_estacion" value="<?php echo $f_estacion;?>">
<input type="hidden" name="f_linea" value="<?php echo $f_linea;?>">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Línea</caption>
<tr>
	<td valign="top">Áreas:</td>
	<td>
	<select name="area" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_2 = "select * from areas where activo='1' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($area==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Estación:</td>
	<td>
	<select name="estacion" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_3 = "select * from estaciones where activo='1' and id_area='$area' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$d_1['id_estacion']){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45" value="<?php echo $d_1['nombre'];?>">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
</form>
<?php  } 

function update($id_,$area,$estacion,$nombre,$f_area,$f_estacion,$f_linea) {

	$area = trim($area);
	$estacion = trim($estacion);
	$nombre = trim($nombre);

	$s_1 = "update lineas set id_area='$area', id_estacion='$estacion', nombre='$nombre' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function borrar($id_,$nombre,$f_area,$f_estacion,$f_linea) {
	$s_1 = "update lineas set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
	require_once("mantto.php"); mantto('');
}	

function estado($id_,$estado,$nombre,$f_area,$f_estacion,$f_linea) {
	$s_1 = "update lineas set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); }


function upload_form(){ 
	$s_1 = "delete from tmp_lineas"; $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo CSV con Líneas y Proyectos</caption>
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
	<tr><td align="center" width="60" rowspan="8" valign="top">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Líneas y Proyectos</b></td></tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Los encabezados del archivo deben ser minúsculas sin acentos ó ñ</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Áreas] body=[<?php echo consulta('area');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La primera columna contiene el nombre del área</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Estaciones] body=[<?php echo consulta('estacion');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La segunda columna contiene el nombre de la tecnología</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La tercera columna contiene el nombre de la línea</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Divisiones] body=[<?php echo consulta('division');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La cuarta columna contiene el nombre de la división del proyecto</td>	
	</tr>		
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Proyectos] body=[<?php echo consulta('proyecto');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La quinta columna contiene el nombre del proyecto</td>	
	</tr>		
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="archivos/ejemplo_lineas.csv"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>			
	</table>
</div>
<?php  } 

function consulta($tipo) {
if($tipo=='area')     { $s_1 = "select * from areas where activo='1' order by nombre limit 0,10"; } 
if($tipo=='estacion') { $s_1 = "select * from estaciones where activo='1' group by nombre order by nombre limit 0,10"; }
if($tipo=='proyecto') { $s_1 = "select * from proyectos where activo='1' order by nombre limit 0,10"; } 
if($tipo=='division') { $s_1 = "select * from divisiones where activo='1' order by nombre limit 0,10"; } 
	$i=1; echo"<table align=left class=texto>";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 
		echo"<tr><td width=20 align=center bgcolor=#CCCCCC>$i</td><td bgcolor=#CCCCCC width=220>$d_1[nombre]</td></tr>"; $i++; }
	echo"</table>";
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
		$campos = split (",", $buffer);	$error = '';	

		if($campos['0']!='' && $campos['0']!='area') {

			//Buscar el id del area
			$s_1 = "Select id, nombre from areas where nombre like '".trim(utf8_encode($campos[0]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_a']  = $d_1['id'];
				$field['nom_a'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el área: $campos[0]<br>"; }	

			//Buscar el id de la tecnología
			$s_1 = "Select id, nombre from estaciones where id_area='".trim($field['id_a'])."' and nombre like ";
			$s_1.= "'".trim(utf8_encode($campos[1]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_t']  = $d_1['id'];
				$field['nom_t'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en la tecnología: $campos[1]<br>"; }	

			//Buscar el id de la línea (si es que ya existe)
			$s_1 = "Select * from lineas where nombre like '".trim(utf8_encode($campos[2]))."' and ";
			$s_1.= "id_area='".trim($field['id_a'])."' and id_estacion='".trim($field['id_t'])."' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_l']  = $d_1['id'];
				$field['nom_l'] = $d_1['nombre']; }
			else { 
				$field['id_l']  = "0";
				$field['nom_l'] = trim(utf8_encode($campos[2])); }	

			//Buscar el id de la división
			$s_1 = "Select id, nombre from divisiones where nombre like '".trim(utf8_encode($campos[3]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_d']  = $d_1['id'];
				$field['nom_d'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en la división: $campos[3]<br>"; }	
			
			//Buscar el id del proyecto
			$s_1 = "Select id, nombre from proyectos where nombre like '".trim(utf8_encode($campos[4]))."' and activo='1' and ";
			$s_1.= "id_division='$field[id_d]'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_p']  = $d_1['id'];
				$field['nom_p'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el proyecto: $campos[4]<br>"; }						

			if($insertar<=0) {
				$query = "INSERT into tmp_lineas values('', '$field[id_a]', '$field[nom_a]', '$field[id_t]', '$field[nom_t]', ";
				$query.= "'$field[id_l]', '$field[nom_l]', '$field[id_d]', '$field[nom_d]', '$field[id_p]', '$field[nom_p]')"; 
				mysql_query($query); $ins++; }
			else { 
				echo "<br><div class=aviso_naranja align=center>".$error;
				echo "Verifique que el archivo tenga el formato necesario y que los registros estén activos<br>";
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
		<td width="250" align="center">Área</td>
		<td width="200" align="center">Tecnología</td>	
		<td width="200" align="center">Línea</td>
		<td width="100" align="center">División</td>
        <td width="200" align="center">Proyecto</td>
	</tr>
</thead>
<?php 
   $s_1 = "select * from tmp_lineas order by id";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['area'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['estacion'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['linea'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
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

//Inserto todas las líneas que no existan en la base de datos
$s_ = "select * from tmp_lineas where id_linea='0' group by area, estacion, linea order by id_area";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$s_1 = "insert into lineas values('','$d_[id_area]','$d_[id_estacion]','$d_[linea]','1')";
	$r_1 = mysql_query($s_1);
	$s_2 = "select * from lineas order by id desc";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	$last_id = $d_2['id'];
	$s_1 = "update tmp_lineas set id_linea ='$last_id' where id_area='$d_[id_area]' and id_estacion='$d_[id_estacion]' and  ";
	$s_1.= "linea='$d_[linea]'";
	$r_1 = mysql_query($s_1); }
	
//Inserto todos los proyectos para cada línea
$s_ = "select * from tmp_lineas order by id_area, id_estacion, linea";
$r_ = mysql_query($s_); $i=0;
while($d_=mysql_fetch_array($r_)) {
	$s_2 = "select * from est_proyecto where id_tecnologia='$d_[id_estacion]' and id_proyecto='$d_[id_proyecto]'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)>0) { 
		$s_1 = "delete from lineas_proy where id_linea='$d_[id_linea]' and id_proyecto='$d_[id_proyecto]'";
		$r_1 = mysql_query($s_1);
		$s_2 = "insert into lineas_proy values('','$d_[id_linea]','$d_[id_proyecto]')";
		$r_2 = mysql_query($s_2); $i++;
	}	
}	
	echo"<script>alert('$i registros insertados');</script>";		
}

function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

function borrar_masivo($f_linea,$f_area,$f_estacion,$casilla){
	$i=0; 
	foreach($casilla as $id){
		$s_1 = "delete from lineas_proy where id_linea='$id'";
		$r_1 = mysql_query($s_1);
		$s_1 = "delete from lineas where id='$id'";
		$r_1 = mysql_query($s_1);
		$i++;
	} echo "<script>alert('$i registros borrados');</script>";
} ?>