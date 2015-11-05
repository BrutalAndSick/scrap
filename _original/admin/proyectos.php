<?php include("../header.php"); ?>

<script>
function validar(tipo) {
	if(form1.planta.value=='') {
		alert('Es necesario seleccionar una planta');
		form1.planta.focus(); return; }	
	if(form1.division.value=='') {
		alert('Es necesario seleccionar una división');
		form1.division.focus(); return; }	
	if(form1.prce.value=='') {
		alert('Es necesario seleccionar un profit center');
		form1.prce.focus(); return; }	
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

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}

function upload() {
	var extension, file_name;
	if(form1.proyecto.value=='') {
		alert('Es necesario seleccionar el proyecto');
		form1.proyecto.focus(); return; }	
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

function excel() {
	form1.action = 'excel.php?op=proyectos';
	form1.submit();	
	form1.action = 'proyectos.php?op=listado';
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_plantas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_plantas','proyectos'); ?></td>
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
		case "nuevo"			:	nuevo($planta,$division,$prce,$segmento); break;
		case "guardar"			:	guardar($planta,$division,$prce,$segmento,$nombre,$lsr,$apr_especial); 
									nuevo($planta,$division,$prce,$segmento); break;

		case "listado"			:	listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina); break;
		case "estado"			:	estado($id_,$estado,$nombre,$f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial); 
									listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina); break;
		case "editar"			:	editar($id_,$planta,$division,$prce,$segmento,$f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial); break;
		case "update"			:	update($id_,$planta,$division,$prce,$segmento,$nombre,$lsr,$apr_especial,$f_planta,$f_division,$f_segmento,$f_prce,
									$f_proyecto,$f_apr_especial); listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina); break;
	
		case "borrar"			:	borrar($id_,$nombre,$f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial); 
									listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina);	 break;		
		case "upload_form"		:	upload_form($division,$segmento,$prce); break;	
		case "upload_file"		: 	upload_file($division,$segmento,$prce,$proyecto,$archivo,$archivo_name); break;
							
		default					:	listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina); break;
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
	<td class="titulo" width="150">PROYECTOS</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload Ubicaciones</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>	
</tr>
</table></div><hr>
<?php } 	


function upload_form($division,$segmento,$prce) { ?>
<form action="?op=upload_form" method="post" enctype="multipart/form-data" name="form1">	
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo CSV con Ubicaciones por Proyecto</caption>
<tbody>
<tr>	
	<td align="left" width="60">División:</td>
	<td><select name="division" style="width:355px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select divisiones.* from divisiones, plantas where divisiones.activo='1' and plantas.activo='1' and ";
	   $s_3.= "divisiones.id_planta = plantas.id order by divisiones.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>	
	<td align="left">Segmento:</td>
	<td><select name="segmento" style="width:355px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select segmentos.* from segmentos, divisiones, plantas where segmentos.id_division='$division' and ";
	   $s_3.= "segmentos.activo='1' and plantas.activo='1' and divisiones.activo='1' and segmentos.id_planta = plantas.id and ";
	   $s_3.= "segmentos.id_division = divisiones.id order by segmentos.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$segmento){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>	
	<td align="left">Profit Center:</td>
	<td>
	<?php $s_3 = "select profit_center.* from profit_center, plantas, divisiones, segmentos where profit_center.activo='1' and ";
	   $s_3.= "plantas.activo='1' and divisiones.activo='1' and segmentos.activo='1' and profit_center.id_planta = plantas.id and ";
	   $s_3.= "profit_center.id_division = divisiones.id and profit_center.id_segmento = segmentos.id and ";
	   $s_3.= "profit_center.id_segmento='$segmento' and profit_center.id_division='$division' order by profit_center.nombre"; ?>
	<select name="prce" style="width:355px;" class="texto" onchange="submit();">
		<option value=""></option>
	   <?php $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$prce){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>	
	<td align="left">Proyecto:</td>
	<td><?php $s_3 = "select * from proyectos where id_division='$division' and id_pc='$prce' and id_segmento='$segmento' and ";
	   	   $s_3.= "activo='1' order by nombre"; 
	       $r_3 = mysql_query($s_3); ?>
	<select name="proyecto" style="width:355px;" class="texto">
		<option value=""></option>
	   <?php while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$proyecto){ ?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select></td>
</tr>
<tr>
	<td align="left">Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="button" value="Guardar" class="submit" onclick="upload();"></div>
</form>
<div align="center" class="aviso_naranja">Se insertarán solamente los registros que no existan</div>
<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="7" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Ubicaciones por Proyecto</b></td></tr>
	</td></tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Los encabezados del archivo deben ser minúsculas sin acentos ó ñ</td>	
	</tr>
	<tr><td class="gris" align="left">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La única columna debe tener los códigos de ubicación para el proyecto</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="archivos/ejemplo_ubicaciones.csv"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div><br><br><br>
<?php }


function upload_file($division,$segmento,$prce,$proyecto,$archivo,$archivo_name) {
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
			upload_form($division,$segmento,$prce); exit; }
		else { 
			insert_csv($division,$segmento,$prce,$proyecto,$nombre); }
	}		
}								


function insert_csv($division,$segmento,$prce,$proyecto,$alias) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d"); $j=0;
	$fd       = fopen ($r_server."$alias", "r");

	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		$campos = split (",", $buffer); 
		
		$campos['0'] = trim($campos['0']); 
		if($campos['0']!='' && $campos['0']!='referencia') {
		  $s_ = "select * from proy_ubicacion where id_proyecto='$proyecto' and ubicacion='$campos[0]'";
		  $r_ = mysql_query($s_);
		  if(mysql_num_rows($r_)<=0) { 	//SÓLO SI NO EXISTE LO INSERTO
		    $query = "INSERT into proy_ubicacion values('', '$proyecto', '$campos[0]')"; 
		  	mysql_query($query); $j++; }
		}	
	}     
	echo "<script>alert('$j registros insertados');</script>";
	fclose ($fd); 
	unlink($r_server.$alias);
	upload_form($division,$segmento,$prce); 
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}


function nuevo($planta,$division,$prce,$segmento) { ?>
<div align="center" class="aviso">Todos los campos son obligatorios excepto el campo de nombre LSR</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Proyecto</caption>
<tr>
	<td valign="top">Planta:</td>
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
	<td valign="top">División:</td>
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
	<td valign="top">Segmento:</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_4 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($segmento==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">P.C.:</td>
	<td>
	<select name="prce" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select * from profit_center where activo='1' and id_segmento='$segmento' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($prce==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45">
	</td>
</tr>
<tr>
	<td valign="top">Nombre LSR:</td>
	<td><input type="text" name="lsr" class="texto" size="45">
	</td>
</tr>
<tr>
	<td>Apr.Especial</td>
    <td align="center">
    	<input type="radio" value="si" name="apr_especial">&nbsp;&nbsp;SI
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" value="no" name="apr_especial" checked="checked">&nbsp;&nbsp;NO
    </td>    
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(1);" class="submit"></div>
</form>
<?php  } 


function guardar($planta,$division,$prce,$segmento,$nombre,$lsr,$apr_especial) {
	$planta = trim($planta);
	$division = trim($division);
	$prce  = trim($prce);
	$segmento = trim($segmento);
	$nombre = trim($nombre);
	$lsr = trim($lsr);
	$limite = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m")+6,date("d"),date("Y")));

	$existe = ver_si_existe($prce,$segmento,$nombre);
	if($existe=='NO') {
		$s_1 = "insert into proyectos values('','$planta','$division','$prce','$segmento','$nombre','$lsr','$apr_especial','$limite','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($prce,$segmento,$nombre) {
	$s_1 = "select * from proyectos where nombre='$nombre' and id_pc='$prce' and id_segmento='$segmento' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial,$pagina) { 
	if(!$pagina)		 $pagina     = '1';
	if(!$f_planta) 		 $f_planta	 = '%';
	if(!$f_proyecto) 	 $f_proyecto = '%';
	if(!$f_division)	 $f_division = '%';
	if(!$f_segmento)  	 $f_segmento = '%'; 
	if(!$f_prce)  		 $f_prce 	 = '%'; 
    if(!$f_apr_especial) $f_apr_especial = '%'; ?>
    <div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos. Seleccione cualquiera de los filtros para ver los registros.</div><br>

   <?php
    $s_1 = "select plantas.nombre as planta, divisiones.nombre as division, profit_center.nombre as profit_center, segmentos.nombre as segmento, proyectos.* ";
	$s_1.= "from segmentos, plantas, divisiones, profit_center, proyectos where proyectos.nombre like '$f_proyecto' and proyectos.activo!='2' and ";
	$s_1.= "proyectos.id_segmento like '$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' ";
	$s_1.= "and proyectos.id_planta like '$f_planta' and proyectos.id_pc = profit_center.id and proyectos.id_segmento = segmentos.id ";
	$s_1.= "and proyectos.id_division = divisiones.id and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and ";
	$s_1.= "plantas.activo='1' and divisiones.id_planta = plantas.id and proyectos.apr_especial like '$f_apr_especial' order by activo DESC, ";
	$s_1.= "division, segmento, profit_center, nombre ASC"; 
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
			echo "<a href='?op=listado&orden=$orden&f_planta=$f_planta&f_division=$f_division&f_segmento=$f_segmento&f_prce=$f_prce&f_proyecto=$f_proyecto";
			echo "&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>       
	
<form action="?op=listado" method="post" name="form1">
<div align="center">
	<input type="button" value="Exportar" class="submit" onclick="excel();"></div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
        <td width="50" align="center">Estado</td>
		<td width="150" align="center">Planta</td>
		<td width="150" align="center">
	<select name="f_division" style="width:150px;" class="texto" onchange="submit();">
		<option value="%">División</option>
	<?php $s_3 = "select divisiones.* from divisiones, plantas where divisiones.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id and divisiones.id_planta like '$f_planta' order by divisiones.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="100" align="center">
	<select name="f_segmento" style="width:150px;" class="texto" onchange="submit();">
		<option value="%">Segmento</option>
	<?php $s_3 = "select segmentos.* from segmentos, divisiones, plantas where segmentos.id_division like '$f_division' and segmentos.id_planta like '$f_planta' and segmentos.activo='1' and plantas.activo='1' and divisiones.activo='1' and segmentos.id_planta = plantas.id and segmentos.id_division = divisiones.id order by segmentos.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_segmento){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>
		</td>
		<td width="100" align="center">
<?php $s_3 = "select profit_center.* from profit_center, plantas, divisiones, segmentos where profit_center.activo='1' and plantas.activo='1' and divisiones.activo='1' and segmentos.activo='1' and profit_center.id_planta = plantas.id and profit_center.id_division = divisiones.id and profit_center.id_segmento = segmentos.id and profit_center.id_planta like '$f_planta' and profit_center.id_segmento like '$f_segmento' and profit_center.id_division like '$f_division' order by profit_center.nombre"; ?>
	<select name="f_prce" style="width:100px;" class="texto" onchange="submit();">
		<option value="%">P.C.</option>
	   <?php $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_prce){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="200" align="center">Nombre</td>
		<td width="80" align="center">Ubicaciones</td>
        <td width="80" align="center">	
        <select name="f_apr_especial" style="width:100px;" class="texto" onchange="submit();">
           <option value="%">Apr.Especial</option>
           <option value="si" <?php if($f_apr_especial=='si'){ ?> selected="selected"<?php } ?>>SI</option>
 		   <option value="no" <?php if($f_apr_especial=='no'){ ?> selected="selected"<?php } ?>>NO</option>
        </select>	
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
      $r_1 = mysql_query($s_1);
	  while($d_1=mysql_fetch_array($r_1)) { 
      $ruta = "&f_proyecto=$f_proyecto&nombre=$d_1[nombre]&f_division=$f_division&f_segmento=$f_segmento&f_prce=$f_prce&f_planta=$f_planta&f_apr_especial=$f_apr_especial"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
    <span title="header=[Nombre LSR] body=[<?=$d_1['lsr'];?>]">
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['planta'];?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
        <td>&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['profit_center'];?></td>
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
    </span>
	<td align="center">
		<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="50" align="center">[<?php echo get_ubicaciones($d_1['id']);?>]</td>
				<td width="30" align="center"><a class="frame_proyectos" href="detalles.php?op=ubicaciones&id_=<?php echo $d_1['id'];?>"><img src="../imagenes/right.gif" border="0"></a></td>
			</tr>
		</table>
	</tr>	
    <td align="center">
    	<?php if($d_1['apr_especial']=='si') { echo "SI"; } else { echo "NO"; } ?></td>		
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


function get_ubicaciones($id_) {
	$s_ = "select * from proy_ubicacion where id_proyecto='$id_'";
	$r_ = mysql_query($s_);
	return mysql_num_rows($r_);
}


function ver_si_tiene_pendientes($proyectos) {
	$s_1 = "select * from scrap_folios where id_proyecto='$proyectos' and status='0'";
	$r_1 = mysql_query($s_1);
	$c_1 = mysql_num_rows($r_1);

	if($c_1>0)   { return "SI"; }
	if($c_1<=0)  { return "NO"; }
}


function editar($id_,$planta,$division,$prce,$segmento,$f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial) { 
	$s_1 = "Select * from proyectos where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	if(!$planta) $planta=$d_1['id_planta'];
	if(!$division) $division=$d_1['id_division'];
	if(!$prce) $prce=$d_1['id_pc']; 
	if(!$segmento) $segmento=$d_1['id_segmento']; ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="f_proyecto" value="<?php echo $f_proyecto;?>">
<input type="hidden" name="f_division" value="<?php echo $f_division;?>">
<input type="hidden" name="f_segmento" value="<?php echo $f_segmento;?>">
<input type="hidden" name="f_prce" value="<?php echo $f_prce;?>">
<input type="hidden" name="f_apr_especial" value="<?php echo $f_apr_especial;?>">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Proyecto</caption>
<tr>
	<td valign="top">Planta:</td>
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
	<td valign="top">División:</td>
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
	<td valign="top">Segmento:</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_4 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($segmento==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">P.C.:</td>
	<td>
	<select name="prce" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
	<?php $s_3 = "select * from profit_center where activo='1' and id_segmento='$segmento' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($prce==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45" value="<?php echo $d_1['nombre'];?>">
	</td>
</tr>
<tr>
	<td valign="top">Nombre LSR:</td>
	<td><input type="text" name="lsr" class="texto" size="45" value="<?php echo $d_1['lsr'];?>">
	</td>
</tr>
<tr>
	<td>Apr.Especial</td>
    <td align="center">
    	<input type="radio" value="si" name="apr_especial" <?php if($d_1['apr_especial']=='si'){?> checked="checked"<?php } ?>>&nbsp;&nbsp;SI
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" value="no" name="apr_especial" <?php if($d_1['apr_especial']=='no'){?> checked="checked"<?php } ?>>&nbsp;&nbsp;NO
    </td>    
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
</form>
<?php  } 

function update($id_,$planta,$division,$prce,$segmento,$nombre,$lsr,$apr_especial,$f_planta,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial) {
	$planta = trim($planta);
	$division = trim($division);
	$prce  = trim($prce);
	$segmento = trim($segmento);
	$nombre = trim($nombre);
	$lsr = trim($lsr);
	$s_1 = "update proyectos set id_planta='$planta', id_division='$division', id_pc='$prce', id_segmento='$segmento', nombre='$nombre', ";
	$s_1.= "lsr='$lsr', apr_especial='$apr_especial' ";
	$s_1.= "where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function borrar($id_,$nombre,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial) {
	$s_1 = "update proyectos set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$s_2 = "delete from oi_especial where id_proyecto='$id_'";
	$r_2 = mysql_query($s_2);
	require_once("mantto.php"); mantto('');
}	

function estado($id_,$estado,$nombre,$f_division,$f_segmento,$f_prce,$f_proyecto,$f_apr_especial) {
	$s_1 = "update proyectos set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); 
} ?>