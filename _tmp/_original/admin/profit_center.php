<?php include("../header.php"); ?>

<script language="JavaScript" src="../css/boxover.js"></script>
<script>
function validar(tipo) {
	if(form1.planta.value=='') {
		alert('Es necesario seleccionar una planta');
		form1.planta.focus(); return; }	
	if(form1.division.value=='') {
		alert('Es necesario seleccionar una división');
		form1.division.focus(); return; }	
	if(form1.segmento.value=='') {
		alert('Es necesario seleccionar un segmento');
		form1.segmento.focus(); return; }			
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
</script>


  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_plantas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_plantas','profit_center'); ?></td>
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

		case "nuevo"			:	nuevo($planta,$division,$segmento); break;
		case "guardar"			:	guardar($planta, $division, $segmento, $nombre, $descripcion); 
									nuevo($planta,$division,$segmento); break;

		case "listado"			:	listado($f_planta,$f_division,$f_segmento,$orden,$pagina); break;
		case "estado"			:	estado($id_,$estado,$nombre,$orden,$f_planta,$f_division,$f_segmento); 
									listado($f_division,$f_segmento,$orden,$pagina); break;
		case "editar"			:	editar($id_,$planta,$division,$segmento,$orden,$f_planta,$f_division,$f_segmento); break;
		case "update"			:	update($id_,$planta,$division,$segmento,$nombre,$orden,$f_planta,$f_division,$f_segmento); 
									listado($f_planta,$f_division,$f_segmento,$orden,$pagina); break;
	
		case "borrar"			:	borrar($id_,$nombre,$orden,$f_planta,$f_division,$f_segmento); 
									listado($f_planta,$f_division,$f_segmento,$orden,$pagina);	 break;		
		default					:	listado($f_planta,$f_division,$f_segmento,$orden,$pagina); break;
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
	<td class="titulo" width="200">PROFIT CENTERS</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	



function upload_form(){ 
	$s_1 = "delete from tmp_pc"; $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo CSV con Profit Centers</caption>
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
	<tr><td align="center" width="60" rowspan="6" valign="top">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Profit Centers</b></td></tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Los encabezados del archivo deben ser minúsculas sin acentos ó ñ</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Divisiones] body=[<?php echo consulta('division');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La primera columna contiene el nombre de la división</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span title="header=[&nbsp;&nbsp;Nombre de Segmentos] body=[<?php echo consulta('segmento');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La segunda columna contiene el nombre del segmento</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La tercera columna contiene el nombre del profit center</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="archivos/ejemplo_pc.csv"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>			
	</table>
</div>
<?php  } 

function consulta($tipo) {
	$i=1;
	switch($tipo) {
		case "plantas"	:	$s_1 = "select * from plantas where activo='1' order by nombre"; break;
		case "division"	:	$s_1 = "select divisiones.* from plantas, divisiones where divisiones.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id order by divisiones.nombre"; break;
		case "segmento"	:	$s_1 = "select distinct(segmentos.nombre) from plantas, divisiones, segmentos where plantas.activo='1' and divisiones.activo = '1' and segmentos.activo='1' and segmentos.id_planta = plantas.id and segmentos.id_division = divisiones.id order by segmentos.nombre"; break;
	}
	echo"<table align=left class=texto>";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 
		echo"<tr><td width=20 align=center bgcolor=#CCCCCC>$i</td><td bgcolor=#CCCCCC width=220>$d_1[nombre]</td></tr>"; $i++; }
	echo"</table>";
}			
		


function nuevo($planta,$division,$segmento) { ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Profit Center</caption>
<tr>
	<td valign="top">Planta:*</td>
	<td>
	<select name="planta" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_1 = "select * from plantas where activo='1' order by nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	   <option value="<?php echo $d_1['id'];?>" <?php if($planta==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">División:*</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_2 = "select * from divisiones where activo='1' and id_planta='$planta' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Segmento:*</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($segmento==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:*</td>
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


function guardar($planta, $division, $segmento, $nombre) {
	$planta = trim($planta);
	$division = trim($division);
	$segmento = trim($segmento);
	$nombre = trim($nombre);
	$fecha = date("Y-m-d");
	
	$existe = ver_si_existe($segmento,$nombre);
	if($existe=='NO') {
		$s_1 = "insert into profit_center values('', '$planta', '$division', '$segmento', '$nombre', '1', 'NA','$fecha')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($segmento,$nombre) {
	$s_1 = "select * from profit_center where nombre='$nombre' and id_segmento='$segmento' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($f_planta,$f_division,$f_segmento,$orden,$pagina) {
	$s_1 = "delete from tmp_pc"; $r_1 = mysql_query($s_1);
	if(!$pagina)		$pagina		= '1';
	if(!$f_planta)  	$f_planta   = '%';
	if(!$f_division)  	$f_division = '%'; 
	if(!$f_segmento)  	$f_segmento = '%'; ?>
    <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar y filtrar los campos</div><br>

   <?php
   $s_1 = "select plantas.nombre as planta, divisiones.nombre as division, segmentos.nombre as segmento, profit_center.* from plantas, segmentos, divisiones, profit_center where profit_center.activo!='2' and plantas.activo='1' and segmentos.activo='1' and divisiones.activo='1' and profit_center.id_segmento = segmentos.id and divisiones.id = profit_center.id_division and plantas.id = profit_center.id_planta and profit_center.id_division like '$f_division' and profit_center.id_segmento like '$f_segmento' and profit_center.id_planta like '$f_planta' order by activo DESC, planta, division, segmento, nombre ASC";
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
			echo "<a href='?op=listado&orden=$orden&f_planta=$f_planta&f_division=$f_division&f_segmento=$f_segmento&pagina=$i' class='link_paginas'>$i</a></td>";
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
		<td width="150" align="center">Planta</td>
		<td width="150" align="center">
	<select name="f_division" style="width:180px;" class="texto" onchange="submit();">
		<option value="">División</option>
	   <?php $s_3 = "select divisiones.* from divisiones, plantas where divisiones.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id ";
	   		 $s_3.= "and divisiones.id_planta like '$f_planta' order by divisiones.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="100" align="center">
	<select name="f_segmento" style="width:150px;" class="texto" onchange="submit();">
		<option value="">Segmento</option>
	    <?php $s_3 = "select segmentos.* from segmentos, divisiones, plantas where segmentos.activo='1' and divisiones.activo='1' and plantas.activo='1' and ";
		      $s_3.= "segmentos.id_planta = plantas.id and segmentos.id_division = divisiones.id and id_division like '$f_division' and segmentos.id_planta like '$f_planta' ";
			  $s_3.= "order by segmentos.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_segmento){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="150" align="center">Nombre</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
      $r_1 = mysql_query($s_1);
	  while($d_1=mysql_fetch_array($r_1)) { 
      $ruta = "&orden=$orden&nombre=$d_1[nombre]&f_division=$f_division&f_segmento=$f_segmento&f_planta=$f_planta"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="center"><?php echo $d_1['nombre'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>">
			<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>	
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }


function editar($id_,$planta,$division,$segmento,$orden,$f_planta,$f_division,$f_segmento) { 
	$s_1 = "Select * from profit_center where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1);
	if(!$planta) $planta=$d_1['id_planta'];
	if(!$division) $division=$d_1['id_division'];
	if(!$segmento) $segmento=$d_1['id_segmento']; ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<input type="hidden" name="f_segmento" value="<?php echo $f_segmento;?>">
<input type="hidden" name="f_planta" value="<?php echo $f_planta;?>">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Profit Center</caption>
<tr>
	<td valign="top">Planta:*</td>
	<td>
	<select name="planta" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_4 = "select * from plantas where activo='1' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($planta==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">División:*</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_2 = "select * from divisiones where activo='1' and id_planta='$planta' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Segmento:*</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($segmento==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:*</td>
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


function update($id_,$planta,$division,$segmento,$nombre,$orden,$f_planta,$f_division,$f_segmento) {
	$planta = trim($planta);
	$division = trim($division);
	$segmento = trim($segmento);
	$nombre = trim($nombre);

	$s_1 = "update profit_center set id_planta='$planta', id_division='$division', id_segmento='$segmento', nombre='$nombre' ";
	$s_1.= "where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	



function borrar($id_,$nombre,$orden,$f_planta,$f_division,$f_segmento) {
	$s_1 = "update profit_center set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1);  
	$s_2 = "delete from oi_especial where id_pc='$id_'";
	$r_2 = mysql_query($s_2);	
	require_once("mantto.php"); mantto('');
}	


function estado($id_,$estado,$nombre,$orden,$f_planta,$f_division,$f_segmento) {
	$s_1 = "update profit_center set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); }


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

		if($campos['0']!='' && $campos['0']!='division') {

			//Buscar el id de la división
			$s_1 = "Select id, nombre from divisiones where nombre like '$campos[0]' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_d']  = $d_1['id'];
				$field['nom_d'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en la división: $campos[0]<br>"; }	

			//Buscar el id de la planta en base a la división
			$s_1 = "Select plantas.id, plantas.nombre from plantas, divisiones where plantas.activo='1' and divisiones.activo='1'".
				   " and divisiones.id = '$field[id_d]' and divisiones.id_planta = plantas.id";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_p']  = $d_1['id'];
				$field['nom_p'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error al buscar la planta<br>"; }	
			
			//Buscar el id del segmento
			$s_1 = "Select id, nombre from segmentos where nombre like '$campos[1]' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_s']  = $d_1['id'];
				$field['nom_s'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el segmento: $campos[1]<br>"; }						

			if(trim($campos['2'])!='') {
				$field['prce'] = trim($campos['2']); //Profit Center
			} else { $insertar++; $error .= "Error en el profit center: $campos[2]"; }

			if($insertar<=0) {
				$query = "INSERT into tmp_pc values('', '$field[id_p]', '$field[nom_p]', '$field[id_d]', '$field[nom_d]', ";
				$query.= "'$field[id_s]', '$field[nom_s]','$field[prce]','$alias','$fecha')"; 
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
		<td width="100" align="center">Planta</td>
		<td width="100" align="center">División</td>	
		<td width="100" align="center">Segmento</td>	
		<td width="100" align="center">Nombre</td>
	</tr>
</thead>
<?php 
   $s_1 = "select * from tmp_pc order by id";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="center"><?php echo $d_1['nombre'];?></td>
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

$s_2 = "select * from tmp_pc order by nombre";
$r_2 = mysql_query($s_2); $i=0;
	while($d_2=mysql_fetch_array($r_2)) { 
		$s_ = "select * from profit_center where nombre='$d_2[nombre]' and id_planta='$d_2[id_planta]' and id_division=";
		$s_.= "'$d_2[id_division]' and id_segmento='$d_2[id_segmento]'"; 
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { //SÓLO SI NO EXISTE LO INSERTO		
			$s_1 = "insert into profit_center values('', '$d_2[id_planta]', '$d_2[id_division]', '$d_2[id_segmento]', ";
			$s_1.= "'$d_2[nombre]', '1', '$d_2[archivo]', '$d_2[fecha]')";
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