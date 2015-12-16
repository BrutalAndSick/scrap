<?php include("../header.php"); ?>

<script>
function validar() {
	if(form1.padre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.padre.value='';
		alert('Es necesario ingresar la parte padre');
		form1.padre.focus(); return; }							
	if(form1.apd_.value=='') {
		alert('Es necesario seleccionar el APD');
		form1.apd_.focus(); return; }
	if(form1.tipo.value=='') {
		alert('Es necesario seleccionar el tipo');
		form1.tipo.focus(); return; }		
	if(form1.nivel.value=='') {
		alert('Es necesario ingresar el nivel');
		form1.nivel.focus(); return; }
	if(form1.material.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.material.value='';
		alert('Es necesario ingresar el componente');
		form1.material.focus(); return; }		
	form1.submit();	
}


function upload() {
	var extension, file_name;
	if(form1.tipo.value=='') {
		alert('Es necesario seleccionar el tipo de carga');
		form1.tipo.focus(); return; }	
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


function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}

function change_parent_url(url) { 
	document.location=url; }	
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_materiales'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_materiales','partes_padre'); ?></td>
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
		case "upload_file"		: 	upload_file($tipo,$archivo,$archivo_name); break;
	
		case "nuevo"			:	nuevo($padre,$apd_,$tipo,$nivel); break;
		case "guardar"			:	guardar($padre,$apd_,$tipo,$nivel,$material); nuevo($padre,$apd_,$tipo,$nivel); break;
	
		case "listado"			:	desbloquear_sistema(); listado($type,$apd,$buscar,$pagina,$tipo); break;
		case "estado"			:	estado($id_1,$id_2,$id_3,$estado,$type,$apd,$buscar,$pagina); 
									listado($type,$apd,$buscar,$pagina,$tipo); break;
		case "editar"			:	editar($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina); break;
		case "update"			:	update($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina,$padre,$apd_,$tipo,$nivel,$material); 
									listado($type,$apd,$buscar,$pagina,$tipo); break;

		case "borrar"			:	borrar($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina); 
									listado($type,$apd,$buscar,$pagina,$tipo); break;		
		default					:	listado($type,$apd,$buscar,$pagina,$tipo); break;
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function upload_file($tipo,$archivo,$archivo_name) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = "temporal_.".$pext;
	$nom_final = $r_server.$nombre; 
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			upload_form(); exit; }
		else { 
	$ruta = "&alias=$nombre&tipo=$tipo"; ?>
	<div align='center'><img src='../imagenes/loading.gif'><br><span class="aviso">Cargando registros, por favor espere...</span></div>
	<iframe src="fun_partes.php?op=insert_txt<?php echo $ruta;?>" width="400" height="400" frameborder="0" scrolling="no"></iframe></div>
	<?php }	
	}
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200">PARTES PADRE</td>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function upload_form(){ 
	$s_1 = "delete from tmp_partes"; $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo TXT con partes padre y componentes</caption>
<tbody>
<tr>
	<td width="90">Carga Masiva:</td>
	<td><select name="tipo" class="texto" style="width:355px;">
		<option value=""></option>
		<option value="acumulada">Acumulativa (agrega registros, actualiza los existentes)</option>
		<option value="nueva">Nueva (borra registros, carga nuevamente todo el archivo)</option>
		</select>
	</td>
</tr>
<tr>
	<td>Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="button" value="Guardar" class="submit" onclick="upload();"></div>
</form>
<div align="center" class="aviso_naranja">Es importante seleccionar el tipo de carga correcta (este proceso es irreversible)</div>
<br><div align="center">
	<table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="8" valign="middle">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Partes Padre</b></td></tr>
	</td><td align="left" class="gris">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>	
	</td><td align="left" class="gris">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;El contenido de las columnas no mencionadas no es relevante para la carga</td></tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		1.- La primera columna contiene el número de parte</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		2.- La sexta columna contiene el nombre del componente</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		3.- La novena columna contiene el nombre del APD</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		4.- La doceava columna contiene los tipos de componente (ROH, HIBE, HALB...)</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="descargar.php?id=ejemplo_partes.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>		
	</table>
</div>
<?php  } 


function nuevo($padre,$apd_,$tipo,$nivel) { ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=guardar" method="post" name="form1">
<input type="hidden" name="buscar" value="<?php echo $padre;?>">
<input type="hidden" name="apd" value="<?php echo $apd_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nueva Parte Padre y Componente</caption>
<tr>
	<td valign="top" width="90" align="left">Parte Padre:*</td>
	<td><input type="text" name="padre" class="texto" size="30" value="<?php echo $padre;?>"></td>
</tr>
<tr>
	<td valign="top" align="left">APD:*</td>
	<td><?php $s_ = "select * from apd where activo='1' group by nombre order by nombre";
		   $r_ = mysql_query($s_); ?>
		<select name="apd_" style="width:180px;" class="texto">
			<option value=""></option>
			<?php while($d_=mysql_fetch_array($r_)){?>
			<option value="<?php echo $d_['nombre'];?>" <?php if($apd_==$d_['nombre']){?> selected="selected"<?php } ?>>
				<?php echo $d_['nombre'];?></option>
			<?php } ?>
		</select></td>
</tr>
<tr>
	<td valign="top" align="left">Tipo:*</td>
	<td valign="top">	
		<select name="tipo" style="width:180px;" class="texto">
			<option value=""></option>
			<option value="HALB" <?php if($tipo=="HALB"){?> selected="selected"<?php } ?>>HALB</option>
			<option value="HAWA" <?php if($tipo=="HAWA"){?> selected="selected"<?php } ?>>HAWA</option>
			<option value="HIBE" <?php if($tipo=="HIBE"){?> selected="selected"<?php } ?>>HIBE</option>
			<option value="ROH" <?php if($tipo=="ROH"){?> selected="selected"<?php } ?>>ROH</option>		
			<option value="VERP" <?php if($tipo=="VERP"){?> selected="selected"<?php } ?>>VERP</option>		
		</select>	
	</td>
</tr>
<tr>
	<td valign="top" align="left">Nivel:*</td>
	<td><input type="text" name="nivel" class="texto" size="30"></td>
</tr>
<tr>
	<td valign="top" align="left">Componente:*</td>
	<td><input type="text" name="material" class="texto" size="30"></td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function guardar($padre,$apd_,$tipo,$nivel,$material) {
	$padre    = trim($padre);
	$apd_     = trim($apd_);
	$tipo     = trim($tipo);
	$nivel    = trim($nivel);
	$material = trim($material);
	$archivo  = date("YmdHis");
	
	$existe = ver_si_existe($padre,$apd_,$material,$tipo,$nivel);
	if($existe=='NO') {
	 $s_1 = "insert into partes_padre values('$padre','$material','$apd_','$tipo','$nivel','1','$archivo')";
	 $r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($padre,$apd_,$material,$tipo,$nivel) {
	$s_1 = "select * from partes_padre where padre='$padre' and apd='$apd_' and material='$material' and type='$tipo' and ";
	$s_1.= "and nivel!='$nivel' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($type,$apd,$buscar,$pagina,$tipo) {
$s_ = "delete from tmp_partes";
$r_ = mysql_query($s_);
	if(!$orden)  $orden  = 'padre';
	if(!$tipo)   $tipo   = 'material';
	if(!$pagina) $pagina = '0'; 

   if($apd!='' || ($buscar!='' && strlen($buscar)>=5)) { 
	$s_1 = "CREATE OR REPLACE VIEW vw_padre AS SELECT * from partes_padre where activo!='2' "; 
    $r_1 = mysql_query($s_1); 	
  	 if($tipo=='padre' && $buscar!='')    { $s_1.= "and padre like '$buscar%' "; }
  	 if($tipo=='material' && $buscar!='') { $s_1.= "and material like '$buscar%' "; }
     if($apd!='')  { $s_1.= "and apd like '$apd' "; }   
	 if($type!='') { $s_1.= "and type like '$type' "; }
   $r_1 = mysql_query($s_1); 
   
   $s_1 = "select * from vw_padre order by padre, material"; 
   $r_1 = mysql_query($s_1); 
   $tot = mysql_num_rows($r_1); 
   $pag = ceil($tot/500); } ?>	
	
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos.</div>
<form action="?op=listado" method="post" name="form2">
<input type="hidden" name="type" value="<?php echo $type;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla">
<tr>
	<td width="80" align="center">Buscar:</td>
	<td><input type="text" class="texto" name="buscar" value="<?php echo $buscar;?>" size="20"></td>
	<td width="70" align="center"><input type="radio" class="texto" name="tipo" value="padre" <?php if($tipo=='padre'){?> checked="checked"<?php } ?>>&nbsp;&nbsp;Parte</td>
	<td width="90" align="left"><input type="radio" class="texto" name="tipo" value="material" <?php if($tipo=='material'){?> checked="checked"<?php } ?>>&nbsp;&nbsp;Material</td>
	<td width="35" align="center">APD:</td>
	<td><input type="text" class="texto" name="apd" value="<?php echo $apd;?>" size="10"></td>
	<td width="80" align="center">Ir al registro:</td>
	<td><select name="pagina" class="texto" style="width:120px;" onchange="submit();">
		<option value=""></option>
		<?php for($i=0;$i<$pag;$i++) { $valor = $i*500; ?>
		<option value="<?php echo $valor;?>" <?php if($pagina==$valor){ ?> selected="selected"<?php } ?>>
			<?php echo $valor+1;?></option>
		<?php } ?>
		</select></td>		
	<td width="120" align="center"><input type="submit" class="submit" value="Buscar"></td>
</tr>
</table><br>
</form>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="apd" value="<?php echo $apd;?>">
<input type="hidden" name="buscar" value="<?php echo $buscar;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla">
<caption>Consultar Partes Padre: <?php echo $tot;?> registros</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
		<td width="50" align="center">Estado</td>
		<td width="200" align="center">
        	<a href="?op=listado&orden=padre&type=<?php echo $type;?>" class="linkTabla">Parte Padre</a></td>
		<td width="250" align="center">
        	<a href="?op=listado&orden=material&type=<?php echo $type;?>" class="linkTabla">Componente</a></td>
		<td width="50" align="center">
        	<a href="?op=listado&orden=nivel&type=<?php echo $type;?>" class="linkTabla">Nivel</a></td>
		<td width="100" align="center"><a href="?op=listado&orden=apd&type=<?php echo $type;?>" class="linkTabla">APD</a></td>
		<td width="100" align="center">
		<select name="type" style="width:100px;" class="texto" onchange="submit();">
			<option value="">Tipo</option>
			<option value="HAWA" <?php if($type=="HAWA"){?> selected="selected"<?php } ?>>HAWA</option>
			<option value="ROH" <?php if($type=="ROH"){?> selected="selected"<?php } ?>>ROH</option>
			<option value="HIBE" <?php if($type=="HIBE"){?> selected="selected"<?php } ?>>HIBE</option>
			<option value="VERP" <?php if($type=="VERP"){?> selected="selected"<?php } ?>>VERP</option>
			<option value="HALB" <?php if($type=="HALB"){?> selected="selected"<?php } ?>>HALB</option>
		</select></td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php if($apd!='' || ($buscar!='' && strlen($buscar)>=5)) {
   $s_1 = "select * from vw_padre order by padre, material limit $pagina, 500"; 
   $r_1 = mysql_query($s_1); $j=$pagina+1;
   while($d_1=mysql_fetch_array($r_1)) {
   $ruta = "&id_1=$d_1[padre]&id_2=$d_1[material]&id_3=$d_1[apd]&orden=$orden&nombre=$d_1[nombre]&inicio=$inicio";
   $ruta.= "&type=$type&apd=$apd&buscar=$buscar"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $j;?></td>
	<td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado$ruta&estado=0'>
				<img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado$ruta&estado=1'>
		   		<img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['padre'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['material'];?></td>
	<td align="center"><?php echo $d_1['nivel'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['apd'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['type'];?></td>
	<td align="center"><a href="?op=editar<?php echo $ruta;?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center"><a href="?op=borrar<?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $j++; } } ?>
</tbody>
</table>
</form>
<?php }


function editar($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina) { 
	$s_1 = "Select * from partes_padre where padre='$id_1' and apd='$id_3' and material='$id_2'"; 
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
<div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
<form action="?op=update" method="post" name="form1">
<input type="hidden" name="buscar" value="<?php echo $buscar;?>">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>">
<input type="hidden" name="apd" value="<?php echo $apd;?>">
<input type="hidden" name="type" value="<?php echo $type;?>">
<input type="hidden" name="id_1" value="<?php echo $id_1;?>">
<input type="hidden" name="id_2" value="<?php echo $id_2;?>">
<input type="hidden" name="id_3" value="<?php echo $id_3;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Parte Padre y Componente</caption>
<tr>
	<td valign="top" width="90" align="left">Parte Padre:*</td>
	<td><input type="text" name="padre" class="texto" size="30" value="<?php echo $d_1['padre'];?>"></td>
</tr>
<tr>
	<td valign="top" align="left">APD:*</td>
	<td><?php $s_ = "select * from apd where activo='1' group by nombre order by nombre";
		   $r_ = mysql_query($s_); ?>
		<select name="apd_" style="width:180px;" class="texto">
			<option value=""></option>
			<?php while($d_=mysql_fetch_array($r_)){?>
			<option value="<?php echo $d_['nombre'];?>" <?php if($d_['nombre']==$d_1['apd']){?> selected="selected"<?php } ?>>
				<?php echo $d_['nombre'];?></option>
			<?php } ?>
		</select></td>
</tr>
<tr>
	<td valign="top" align="left">Tipo:*</td>
	<td valign="top">	
		<select name="tipo" style="width:180px;" class="texto">
			<option value=""></option>
			<option value="HALB" <?php if($d_1['type']=="HALB"){?> selected="selected"<?php } ?>>HALB</option>
			<option value="HAWA" <?php if($d_1['type']=="HAWA"){?> selected="selected"<?php } ?>>HAWA</option>
			<option value="HIBE" <?php if($d_1['type']=="HIBE"){?> selected="selected"<?php } ?>>HIBE</option>
			<option value="ROH" <?php if($d_1['type']=="ROH"){?> selected="selected"<?php } ?>>ROH</option>	
			<option value="VERP" <?php if($d_1['type']=="VERP"){?> selected="selected"<?php } ?>>VERP</option>		
		</select>	
	</td>
</tr>
<tr>
	<td valign="top" align="left">Nivel:*</td>
	<td><input type="text" name="nivel" class="texto" size="30" value="<?php echo $d_1['nivel'];?>"></td>
</tr>
<tr>
	<td valign="top" align="left">Componente:*</td>
	<td><input type="text" name="material" class="texto" size="30" value="<?php echo $d_1['material'];?>"></td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar();" class="submit"></div>
</form>
<?php  } 


function update($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina,$padre,$apd_,$tipo,$nivel,$material) {
	$padre    = trim($padre);
	$apd_     = trim($apd_);
	$tipo     = trim($tipo);
	$nivel    = trim($nivel);
	$material = trim($material);

	 $s_1 = "update partes_padre set padre='$padre', apd='$apd_', material='$material', type='$tipo', nivel='$nivel' where ";
	 $s_1.= "padre='$id_1' and material='$id_2' and apd='$id_3'"; 
	 $r_1 = mysql_query($s_1);
}	


function borrar($id_1,$id_2,$id_3,$type,$apd,$buscar,$pagina) {
	$s_1 = "delete from partes_padre where padre='$id_1' and material='$id_2' and apd='$id_3'";
	$r_1 = mysql_query($s_1); 
}	


function estado($id_1,$id_2,$id_3,$estado,$type,$apd,$buscar,$pagina) {
	$s_1 = "update partes_padre set activo='$estado' where padre='$id_1' and material='$id_2' and apd='$id_3'";
	$r_1 = mysql_query($s_1);  }

?>