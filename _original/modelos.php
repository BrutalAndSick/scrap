<?php include("../header.php"); 
   include("fun_modelos.php");

if($tabla=='roh') 		{ $type = "Raw Material"; }
if($tabla=='halb')  	{ $type = "Subensamble";  }
if($tabla=='fert') 		{ $type = "Prod. Terminado"; } ?>

<script>
function validar(tipo,tabla) {
	if(form1.tipo.value=='') {
		alert('Es necesario seleccionar el tipo');
		form1.tipo.focus(); return; }							
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el número de parte');
		form1.nombre.focus(); return; }
	if(form1.costo.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.costo.value='';
		alert('Es necesario ingresar el costo');
		form1.costo.focus(); return; }	
	if(form1.unidad.value=='') {
		alert('Es necesario seleccionar la unidad');
		form1.unidad.focus(); return; }			
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update'; }
form1.submit();	
}


function solo_numeros(evt){
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}


function upload() {
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


function validar_unidad() {
	if(form1.unidad.value=='') {
		alert('Es necesario ingresar la unidad');
		form1.unidad.focus(); return; }							
	if(form1.descripcion.value=='') {
		alert('Es necesario ingresar la descripción');
		form1.descripcion.focus(); return; }		

	form1.action='?op=unidades_g';
	form1.submit();	
}


function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_materiales'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_materiales',$tabla); ?></td>
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
	<?php menu_interno($type,$tabla);
		switch($op) {
		case "upload_form"		:	upload_form($tabla); break;
		case "upload_file"		: 	upload_file($tabla,$type,$archivo,$archivo_name); break;

		case "guardar_temp"		:	guardar_temp($type,$tabla,$orden,$tipo); listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;			
	
		case "nuevo"			:	nuevo($type,$tabla); break;
		case "guardar"			:	guardar($tabla,$tipo,$nombre,$costo,$unidad,$descripcion); 
									nuevo($type,$tabla); break;
	
		case "listado"			:	desbloquear_sistema(); listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;
		case "estado"			:	estado($id_,$estado,$tabla,$buscar,$inicio); 
									listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;
		case "editar"			:	editar($id_,$orden,$tabla,$tipo,$type,$buscar,$inicio); break;
		case "update"			:	update($id_,$nombre,$descripcion,$costo,$unidad,$orden,$tabla,$tipo,$buscar,$inicio); 
									listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;

		case "unidades"			:	unidades($tabla,$id_,$unidad,$descripcion,$decimales); break;
		case "unidades_g"		:	unidades_g($tabla,$id_,$unidad,$descripcion,$decimales); break;
		case "unidades_b"		:	unidades_b($tabla,$id_); unidades($tabla,$id_,'','',''); break;
	
		case "borrar"			:	borrar($id_,$tabla,$buscar,$inicio); 
									listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;		
		default					:	listado($type,$tabla,$orden,$tipo,$buscar,$inicio); break;
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

function menu_interno($type,$tabla) { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200"><?php echo strtoupper($type);?></td>
	<td><a href="?op=nuevo&tabla=<?php echo $tabla;?>" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form&tabla=<?php echo $tabla;?>" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado&tabla=<?php echo $tabla;?>" class="menuLink">Consultar</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=unidades&tabla=<?php echo $tabla;?>" class="menuLink">Unidades</a></td>    
</tr>
</table></div><hr>
<?php } 	


function unidades($tabla,$id_,$unidad,$descripcion,$decimales) { 
if($id_!='') { 
	$s_1 = "select * from unidades where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	if(!$unidad) $unidad = $d_1['unidad'];
	if(!$descripcion) $descripcion = $d_1['descripcion'];
	if(!$decimales) $decimales = $d_1['decimales']; } ?>
<div align="center" class="aviso">La lista de unidades aplica para todos los tipos de material</div>
<div align="center" class="texto">
<form method="post" action="?op=unidades_g" name="form1">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>" />
<input type="hidden" name="id_" value="<?php echo $id_;?>" />
Unidad:&nbsp;&nbsp;<input type="text" name="unidad" class="texto" size="10" value="<?php echo $unidad;?>">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Descripción:&nbsp;&nbsp;<input type="text" name="descripcion" class="texto" size="30" value="<?php echo $descripcion;?>">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="decimales" value="1" <?php if($decimales=='1'){?> checked="checked"<?php } ?> />&nbsp;&nbsp;Decimales
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar_unidad();" class="submit"/>
</form>
</div>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
		<td width="80" align="center">Unidad</td>
		<td width="200" align="center">Descripción</td>
		<td width="80" align="center">Decimales</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<?php 
   $s_1  = "select * from unidades order by unidad";
   $r_1  = mysql_query($s_1); $j=1; ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $j;?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['unidad'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['descripcion'];?></td>
    <td align="center">
    	<input type="checkbox" disabled="disabled" <?php if($d_1['decimales']=='1') { ?> checked="checked"<?php } ?> />
    </td>
    <td align="center">
		<a href="?op=unidades&id_=<?php echo $d_1['id'];?>&tabla=<?php echo $tabla;?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=unidades_b&id_=<?php echo $d_1['id'];?>&tabla=<?php echo $tabla;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $j++; } ?>
</tbody>
</table>
</form>
<?php }


function unidades_g($tabla,$id_,$unidad,$descripcion,$decimales) {
	$s_ = "select * from unidades where unidad = '$unidad' and id!='$id_'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { 
		if($id_=='') { 
			$s_1 = "insert into unidades values('', '$unidad', '$descripcion', '$decimales')";
		} if($id_!='') { 
			$s_1 = "update unidades set unidad='$unidad', descripcion='$descripcion', decimales='$decimales' where id='$id_'"; }
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>";	
		unidades($tabla,'','','','');	
	} else { 
		echo"<script>alert('Ese registro ya existe');</script>"; 
		unidades($tabla,$id_,$unidad,$descripcion,$decimales);
	}		
}


function unidades_b($tabla,$id_) {
	$s_ = "delete from unidades where id='$id_'";
	$r_ = mysql_query($s_);
}


function upload_form($tabla){ 
	$s_1 = "delete from tmp_numeros"; $r_1 = mysql_query($s_1);
	$s_1 = "delete from tmp_batch";   $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo TXT con números de parte</caption>
<tbody>
<tr>
	<td>Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="button" value="Guardar" class="submit" onclick="upload();"></div>
</form>
<div align="center" class="aviso_naranja">Se insertarán solamente los registros que no existan</div>
<?php if($tabla=='roh') { ?>
<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="8" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Raw Material</b></td></tr>
	</td><td align="left" class="gris" width="440">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		1.- La primera columna contiene los tipos con mayúsculas (ROH, HIBE, HAWA)</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		2.- La segunda columna contiene los números de parte</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		3.- La tercera columna contiene los precios (sólo dígitos del 0 al 9 y decimales)</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		4.- La cuarta columna contiene la descripción (campo opcional)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		5.- La quinta columna contiene la unidad de medida</td>	
	</tr>	    
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="descargar.php?id=ejemplo_roh.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div><?php } ?>
<?php if($tabla=='halb') { ?>
<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="9" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Subensamble</b></td></tr>
	</td><td align="left" class="gris" width="440">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>		
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		1.- La primera columna contiene el tipo (HALB para todos los casos)</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		2.- La segunda columna contiene el tipo sub (S.Real ó S.Real/AutoBF)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		3.- La tercera columna contiene los números de parte</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		4.- La cuarta columna contiene los precios (sólo dígitos del 0 al 9 y decimales)</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		5.- La quinta columna contiene la descripción (campo opcional)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		6.- La sexta columna contiene la unidad de medida</td>	
	</tr>	    
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="descargar.php?id=ejemplo_halb.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div><?php } ?>
<?php if($tabla=='fert') { ?>
<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="9" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Producto Terminado</b></td></tr>
	</td><td align="left" class="gris" width="440">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>		
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		1.- La primera columna contiene el tipo de material (FERT)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		2.- La segunda columna contiene los números de parte</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		3.- La tercera columna contiene contiene el (los) Batch ID (campo opcional)</td>
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		4.- La cuarta columna contiene los precios (sólo dígitos del 0 al 9 y decimales)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		5.- La quinta columna contiene la descripción (campo opcional)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		6.- La sexta columna contiene la unidad de medida</td>	
	</tr>	    	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="descargar.php?id=ejemplo_fert.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div><?php } ?>
<?php  } 



function nuevo($type,$tabla) { ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo <?php echo $type;?></caption>
<?php if($tabla=='roh') { ?>
<tr>
	<td valign="top">Tipo:*</td>
	<td>
		<select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="HAWA">HAWA</option>
		<option value="ROH">ROH</option>
		<option value="HIBE">HIBE</option>
		<option value="VERP">VERP</option>
		</select>	
	</td>
</tr><?php } ?>	
<?php if($tabla=='halb') { ?>
<tr>
	<td valign="top">Tipo Sub:*</td>
	<td>
		<select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="S.Real">S.Real</option>
		<option value="S.Real/AutoBF">S.Real/AutoBF</option>
		</select>	
	</td>
</tr><?php } ?>	
<?php if($tabla=='fert') { ?>
<tr>
	<td valign="top">Tipo:*</td>
	<td>
    <select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="FERT">FERT</option>
		<option value="KMAT">KMAT</option>
	</select></td>	
</tr><?php } ?>
<tr>
	<td valign="top">No.Parte:*</td>
	<td><input type="text" name="nombre" class="texto" size="20">
	</td>
</tr>
<tr>
	<td valign="top">Precio:*</td>
	<td><input type="text" name="costo" class="texto" size="20" onkeypress="return solo_numeros(event);">
	</td>
</tr>
<tr>
	<td valign="top">Unidad:*</td>
	<td>
	<?php $s_1 = "select * from unidades order by unidad";
		   $r_1 = mysql_query($s_1); ?>
    <select name="unidad" class="texto" style="width:130px;">
    	<option value=""></option>
        <?php while($d_1=mysql_fetch_array($r_1)) { ?>            
    	<option value="<?php echo $d_1['unidad'];?>"><?php echo $d_1['unidad'];?></option>
        <?php } ?>
    </select>    
    </td>
</tr>
<tr>
	<td valign="top">Descripción:</td>
	<td><textarea name="descripcion" class="texto" cols="47" rows="3"></textarea>
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(1,'<?php echo $tabla;?>');" class="submit"></div>
</form>
<?php  } 


function guardar($tabla,$tipo,$nombre,$costo,$unidad,$descripcion) {
	$tabla = trim($tabla);
	$tipo  = trim($tipo);
	$nombre = trim($nombre);
	$costo = trim($costo);
	$descripcion = htmlentities(trim($descripcion),ENT_QUOTES,"UTF-8");
	
	$existe = ver_si_existe($tipo,$tabla,$nombre);
	if($existe=='NO') {
	 $s_1 = "insert into numeros_parte values('','$nombre','$descripcion','$tabla','$tipo','$costo','$unidad','1','NA')";
	 $r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($tipo,$tabla,$nombre) {
	$s_1 = "select * from numeros_parte where nombre='$nombre' and tabla='$tabla' and tipo='$tipo' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}



function listado($type,$tabla,$orden,$tipo,$buscar,$inicio) {
$s_1 = "delete from tmp_numeros"; $r_1 = mysql_query($s_1);
$s_1 = "delete from tmp_batch";   $r_1 = mysql_query($s_1);
	if(!$orden) 	$orden	= 'nombre';
	if(!$tipo)		$tipo   = '%';
	if(!$inicio)    $inicio = 0;
	if($buscar!='') $inicio = 0; 
	$s_1   = "select * from numeros_parte where tipo like '$tipo' and activo!='2' and tabla='$tabla'";
	$r_1   = mysql_query($s_1);
	$items = mysql_num_rows($r_1);
	$pags  = ceil($items/100)-1; ?>
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<div align="center" class="texto">
	Buscar Modelo:&nbsp;&nbsp;
	<input type="text" class="texto" name="buscar" value="<?php echo $buscar;?>" size="40">&nbsp;&nbsp;&nbsp;&nbsp;
	Ir al registro:&nbsp;&nbsp;
	<select name="inicio" class="texto" style="width:120px;" onchange="submit();">
	<option value=""></option>
	<?php for($i=0;$i<=$pags;$i++) { $valor = $i*100; ?>
	<option value="<?php echo $valor;?>" <?php if($inicio==$valor){ ?> selected="selected"<?php } ?>><?php echo $valor+1;?></option>
	<?php } ?>
	</select>
	&nbsp;&nbsp;
	<input type="submit" class="submit" value="Buscar">
</div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
		<td width="50" align="center">Estado</td>		
		<td width="100" align="center">
    	<?php if($tabla=='fert') { ?>    
        <select name="tipo" style="width:100px;" class="texto" onchange="submit();">
		<option value="">Tipo</option>
		<option value="FERT" <?php if($tipo=="FERT"){?> selected="selected"<?php } ?>>FERT</option>
		<option value="KMAT" <?php if($tipo=="KMAT"){?> selected="selected"<?php } ?>>KMAT</option>
		</select>       
		<?php } if($tabla=='roh') { ?>
		<select name="tipo" style="width:100px;" class="texto" onchange="submit();">
		<option value="">Tipo</option>
		<option value="HAWA" <?php if($tipo=="HAWA"){?> selected="selected"<?php } ?>>HAWA</option>
		<option value="ROH" <?php if($tipo=="ROH"){?> selected="selected"<?php } ?>>ROH</option>
		<option value="HIBE" <?php if($tipo=="HIBE"){?> selected="selected"<?php } ?>>HIBE</option>
		</select><?php } if($tabla=='halb') { ?>
		<select name="tipo" style="width:100px;" class="texto" onchange="submit();">
		<option value="">Tipo Sub</option>
		<option value="S.Real" <?php if($tipo=="S.Real"){?> selected="selected"<?php } ?>>S.Real</option>
		<option value="S.Real/AutoBF" <?php if($tipo=="S.Real/AutoBF"){?> selected="selected"<?php } ?>>S.Real/AutoBF</option>
		</select><?php } ?>
		</td>
		<td width="200" align="center">
			<a href="?op=listado&orden=nombre&tabla=<?php echo $tabla;?>&tipo=<?php echo $tipo;?>" class="linkTabla">No.Parte</a></td>
		<td width="250" align="center">Descripción</td>	
		<?php if($tabla=='fert') { ?><td align="center" width="80">Batch ID's</td><?php } ?>
		<td width="100" align="center">
			<a href="?op=listado&orden=costo&tabla=<?php echo $tabla;?>&tipo=<?php echo $tipo;?>" class="linkTabla">Precio</a></td>
 		<td width="80" align="center">
			<a href="?op=listado&orden=unidad&tabla=<?php echo $tabla;?>&tipo=<?php echo $tipo;?>" class="linkTabla">Unidad</a></td>   
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<?php 
   $s_1  = "select * from numeros_parte where tipo like '$tipo' and activo!='2' and tabla='$tabla' and nombre like '$buscar%' ";
   $s_1 .= "order by $orden asc limit $inicio,100"; $j=$inicio+1;
   $r_1  = mysql_query($s_1); 
   $ruta = "&orden=$orden&tabla=$tabla&tipo=$tipo&buscar=$buscar&inicio=$inicio"; ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $j;?></td>
	<td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0&nombre=$d_1[nombre]$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1&nombre=$d_1[nombre]$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['tipo'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo html_entity_decode($d_1['descripcion'],ENT_QUOTES,"UTF-8");?></td>
	<?php if($tabla=='fert') { ?>
	<td align="center">
		<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="50" align="center">[<?php echo get_batchs($d_1['nombre']);?>]</td>
				<td width="30" align="center">
					<a class="frame_batch_ids" href="detalles.php?op=batch_ids&parte=<?php echo $d_1['nombre'];?>">
					<img src="../imagenes/right.gif" border="0"></a></td>
			</tr>
		</table>	
	</td><?php } ?>
	<td align="right"><?php echo "$ ".number_format($d_1['costo'],2);?>&nbsp;&nbsp;</td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['unidad'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?>&tipo=<?php echo $d_1['tipo'];?><?php echo $ruta;?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $j++; } ?>
</tbody>
</table>
</form>
<?php }


function get_batchs($parte) {
	$s_ = "select * from batch_ids where parte='$parte'";
	$r_ = mysql_query($s_);
	return mysql_num_rows($r_);
}


function editar($id_,$orden,$tabla,$tipo,$type,$buscar,$inicio) { 
	$s_1 = "Select * from numeros_parte where id='$id_' and tabla='$tabla'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<input type="hidden" name="buscar" value="<?php echo $buscar;?>">
<input type="hidden" name="inicio" value="<?php echo $inicio;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar <?php echo $type;?></caption>
<?php if($tabla=='roh') { ?>
<tr>
	<td valign="top">Tipo:*</td>
	<td>
		<select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="HAWA" <?php if($d_1['tipo']=='HAWA') { ?> selected="selected"<?php } ?>>HAWA</option>
		<option value="ROH" <?php if($d_1['tipo']=='ROH') { ?> selected="selected"<?php } ?>>ROH</option>
		<option value="HIBE" <?php if($d_1['tipo']=='HIBE') { ?> selected="selected"<?php } ?>>HIBE</option>
		<option value="VERP" <?php if($d_1['tipo']=='VERP') { ?> selected="selected"<?php } ?>>VERP</option>
		</select>	
	</td>
</tr><?php } ?>	
<?php if($tabla=='halb') { ?>
<tr>
	<td valign="top">Tipo:*</td>
	<td>
		<select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="S.Real" <?php if($d_1['tipo']=='S.Real') { ?> selected="selected"<?php } ?>>S.Real</option>
		<option value="S.Real/AutoBF" <?php if($d_1['tipo']=='S.Real/AutoBF') { ?> selected="selected"<?php } ?>>S.Real/AutoBF</option>
		</select>	
	</td>
</tr><?php } ?>	
<?php if($tabla=='fert') { ?>
<tr>
	<td valign="top">Tipo:*</td>
	<td>
		<select name="tipo" style="width:130px;" class="texto">
		<option value=""></option>
		<option value="FERT" <?php if($d_1['tipo']=='FERT') { ?> selected="selected"<?php } ?>>FERT</option>
		<option value="KMAT" <?php if($d_1['tipo']=='KMAT') { ?> selected="selected"<?php } ?>>KMAT</option>
		</select>	
    </td>        
</tr><?php } ?>
<tr>
	<td valign="top">No.Parte:*</td>
	<td><input type="text" name="nombre" class="texto" size="20" value="<?php echo $d_1['nombre'];?>">
	</td>
</tr>
<tr>
	<td valign="top">Precio:*</td>
	<td><input type="text" name="costo" class="texto" size="20" value="<?php echo $d_1['costo'];?>">
	</td>
</tr>
<tr>
	<td valign="top">Unidad:*</td>
	<td>
	<?php $s_2 = "select * from unidades order by unidad";
		  $r_2 = mysql_query($s_2); ?>
    <select name="unidad" class="texto" style="width:130px;">
    	<option value=""></option>
        <?php while($d_2=mysql_fetch_array($r_2)) { ?>            
    	<option value="<?php echo $d_2['unidad'];?>" <?php if($d_1['unidad']==$d_2['unidad']){?> selected="selected"<?php } ?>>
			<?php echo $d_2['unidad'];?></option>
        <?php } ?>
    </select>    
    </td>
</tr>
<tr>
	<td valign="top">Descripción:</td>
	<td><textarea name="descripcion" class="texto" cols="47" rows="3"><?php echo html_entity_decode($d_1['descripcion'],ENT_QUOTES,"UTF-8");?></textarea>
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
</form>
<?php  } 


function update($id_,$nombre,$descripcion,$costo,$unidad,$orden,$tabla,$tipo,$buscar,$inicio) {
	$des = htmlentities($descripcion,ENT_QUOTES,"UTF-8");
	$s_1 = "update numeros_parte set nombre='$nombre', costo='$costo', descripcion='$des', tipo='$tipo', ";
	$s_1.= "unidad='$unidad' ";
	$s_1.= "where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	



function borrar($id_,$tabla,$buscar,$inicio) {
	$s_1 = "update numeros_parte set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	



function estado($id_,$estado,$tabla,$buscar,$inicio) {
	$s_1 = "update numeros_parte set activo='$estado' where id='$id_'"; 
	$r_1 = mysql_query($s_1);   }

?>