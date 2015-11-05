<?php include("../header.php"); ?>

<script>
function mostrar() {
div = document.getElementById('campos_extra');
	if(form1.ac_di[1].checked==true) {
		div.style.display=''; }
	if(form1.ac_di[0].checked==true) {
		div.style.display='none'; }	
}

function validar(tipo) {
if(form1.ac_di[0].checked==true) {
	if(form1.usuario.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.usuario.value='';
		alert('Es necesario ingresar un usuario');
		form1.usuario.focus(); return; }
}

if(form1.ac_di[1].checked==true) {	
	if(form1.usuario.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.usuario.value='';
		alert('Es necesario ingresar un usuario');
		form1.usuario.focus(); return; }
	if(form1.nombre.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.nombre.value='';
		alert('Es necesario ingresar el nombre');
		form1.nombre.focus(); return; }
	if(form1.apellidos.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.apellidos.value='';
		alert('Es necesario ingresar los apellidos');
		form1.ap_paterno.focus(); return; }
	if(form1.password1.value.replace(/^\s*|\s*$/g,"")=='' && tipo=='1') {
		form1.password1.value='';
		alert('Es necesario ingresar el password');
		form1.password1.focus(); return; }	
	if(form1.password1.value!='' && form1.password2.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.password2.value='';
		alert('Es necesario confirmar el password');
		form1.password2.focus(); return; }		
	if(form1.password1.value.replace(/^\s*|\s*$/g,"")!=form1.password2.value.replace(/^\s*|\s*$/g,"")) {
		alert('Los passwords no coinciden');
		form1.password2.value='';
		form1.password2.focus(); return; }	
	}	
	
	form1.submit();	
}

function exportar() {
	form1.action='excel.php?op=empleados';
	form1.submit();	
	form1.action='empleados.php?op=listado';
}

function validarEmail(mail) {
if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
	return (true)
} else {
	return (false);
	}
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
    <td><?php submenu('b_usuarios','empleados'); ?></td>
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
		case "nuevo"		:	nuevo(); break;
		case "guardar"		:	guardar($nombre,$apellidos,$usuario,$password1,$email,$ac_di); nuevo(); break;
		
		case "privilegio"	:	privilegio($f_acdi,$f_userid,$f_depto,$f_pagina,$id_emp,$tipo,$valor,$autorizador,$ausencia); 
								listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
		case "listado"		:	listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
		case "estado"		:	estado($id_,$estado,$nombre,$f_acdi,$f_userid,$f_depto,$f_pagina); 
								listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
	
		case "editar"		:	editar($id_,$f_acdi,$f_userid,$f_depto,$f_pagina); break;
		case "update"		:	update($id_,$nombre,$apellidos,$usuario,$password1,$email,$ac_di,$f_acdi,$f_userid,$f_depto,$f_pagina);
								listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
	
		case "borrar"		:	borrar($id_,$nombre,$f_acdi,$f_userid,$f_depto,$f_pagina); 
								listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;		
		default				:	listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo); break;
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



function menu_interno() { 
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; } ?>
    
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="150">EMPLEADOS</td>
	<?php if($admin=='2') { ?>
    <td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>	
	<td><a href="?op=listado" class="menuLink">Consultar</a></td><?php } ?>
</tr>
</table></div><hr>
<?php } 	


function nuevo() { ?>    
<div align="center" class="aviso">Todos los campos son obligatorios<br>
Si el usuario no utilizará el sistema con su cuenta de Active Directory, seleccione NO y llene los campos necesarios</div>
<form action="?op=guardar" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nuevo Empleado</caption>
<tr>
	<td valign="middle">User-ID (Minúsculas):</td>
	<td><input type="text" name="usuario" class="texto" size="39" maxlength="20">
	</td>
</tr>
<tr>
	<td valign="middle">Active Directory:</td>
	<td>&nbsp;&nbsp;<input type="radio" name="ac_di" value="SI" checked="checked" onclick="mostrar();">SI
		&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ac_di" value="NO" onclick="mostrar();">NO
	</td>
</tr>
</table><br>
<div id="campos_extra" style="display:none;">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td valign="top" width="90">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45">
	</td>
</tr>
<tr>
	<td valign="top">Apellidos:</td>
	<td><input type="text" name="apellidos" class="texto" size="45">
	</td>
</tr>
<tr>
	<td valign="top">Password 1:</td>
	<td><input type="password" name="password1" class="texto" size="20" maxlength="20">
	</td>
</tr>
<tr>
	<td valign="top">Password 2:</td>
	<td><input type="password" name="password2" class="texto" size="20" maxlength="20">
	</td>
</tr>
<tr>
	<td valign="top">E-mail:</td>
	<td><input type="text" name="email" class="texto" size="45">
	</td>
</tr>
</table>
</div>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(1);" class="submit">
</div>
</form>
<?php  } 


function guardar($nombre,$apellidos,$usuario,$password1,$email,$ac_di) {
		$nombre    = fix_data(utf8_decode($nombre));
		$apellidos = fix_data(utf8_decode($apellidos));
		$usuario   = trim(strtolower($usuario));
		$password1 = trim($password1);
		$email     = fix_data(utf8_decode($email));

	$existe = ver_si_existe($usuario);
	if($existe=='NO') {
		if($ac_di=='NO') {
			$s_1 = "insert into empleados values('','$nombre','$apellidos','$usuario','$password1','$email','$ac_di','0','0','','0','0','0','0','1')"; }
		if($ac_di=='SI') {
			$s_1 = "insert into empleados values('','','','$usuario','','','$ac_di','0','0','','0','0','0','0','1')"; }
			$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($usuario) {
	$s_1 = "select * from empleados where usuario='$usuario' and activo!='2'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) {	return 'NO'; }
		else { return 'SI'; }
}


function listado($f_acdi,$f_userid,$f_depto,$f_pagina,$f_tipo) {
	//Revisar si es administrador o super administrador del sistema
	$s_ = "select super_admin, administrador from empleados where id='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_); 
	$d_ = mysql_fetch_array($r_);
	if($d_['super_admin']=='1')   { $admin = 2; }
	if($d_['administrador']=='1') { $admin = 1; } 

	if(!$f_acdi)   $f_acdi   = '%';
	if(!$f_depto)  $f_depto  = '%'; 
	if(!$f_pagina) $f_pagina = '1';

	$s_1 = "select * from empleados where (usuario like '%$f_userid%' or nombre like '$f_userid%' or apellidos like '$f_userid%') and active_directory like '$f_acdi' ";
	$s_1.= "and autorizador like '$f_depto' and activo='1' ";
	
	if($f_tipo!='') { 
		switch($f_tipo) { 
			case "super_admin"		:	$s_1.= "and super_admin='1' "; break;
			case "administrador"	:	$s_1.= "and administrador='1' "; break;
			case "autorizador"		:	$s_1.= "and autorizador!='0' "; break;
			case "capturista"		:	$s_1.= "and capturista='1' "; break;
			case "reportes"			:	$s_1.= "and reportes='1' "; break;
		}
	}	
	$s_1.= "order by apellidos, nombre ";
	$r_1 = mysql_query($s_1); 
	$tot = mysql_num_rows($r_1);
	$pag = ceil($tot/100); 
	$ini = ($f_pagina-1)*100; ?>
	
<div align="center" class="aviso">
	Los encabezados de la tabla permiten filtrar los datos. Para ver reflejados los cambios, es necesario hacer clic en Buscar.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="240" class="texto">Buscar empleado:</td>
	<td width="120" class="texto">Active Directory:</td>
	<td width="120" class="texto">Departamento:</td>
    <td width="120" class="texto">Tipo:</td>
	<td width="100" class="texto">Registros:</td>
	<td width="140">&nbsp;</td>
</tr>
<tr>
	<td><input type="text" name="f_userid" value="<?php echo $f_userid;?>" class="texto" size="40"></td>
	<td><select name="f_acdi" style="width:110px;" class="texto">
			<option value=""></option>
			<option value="SI" <?php if($f_acdi=='SI'){?> selected="selected"<?php } ?>>SI</option>
			<option value="NO" <?php if($f_acdi=='NO'){?> selected="selected"<?php } ?>>NO</option>
	</select></td>
	<td><select name="f_depto" class="texto" style="width:110px;">
			<option value=""></option>
            <option value="esp" <?php if($f_depto=='esp'){?> selected="selected"<?php } ?>>Especial</option>	
			<option value="ffc" <?php if($f_depto=='ffc'){?> selected="selected"<?php } ?>>FFC</option>
			<option value="ffm" <?php if($f_depto=='ffm'){?> selected="selected"<?php } ?>>FFM</option>
			<option value="inv" <?php if($f_depto=='inv'){?> selected="selected"<?php } ?>>Inventarios</option>
			<option value="lo" <?php if($f_depto=='lo'){?> selected="selected"<?php } ?>>LO</option>
			<option value="loa" <?php if($f_depto=='loa'){?> selected="selected"<?php } ?>>LO Almacén</option>
			<option value="lpl" <?php if($f_depto=='lpl'){?> selected="selected"<?php } ?>>LPL</option>			
			<option value="oes" <?php if($f_depto=='oes'){?> selected="selected"<?php } ?>>OES</option>		
			<option value="prod" <?php if($f_depto=='prod'){?> selected="selected"<?php } ?>>Producción</option>
			<option value="sqm" <?php if($f_depto=='sqm'){?> selected="selected"<?php } ?>>SQM</option>		
            <option value="fin" <?php if($f_depto=='fin'){?> selected="selected"<?php } ?>>Finanzas</option>			
	</select></td>
	<td><select name="f_tipo" class="texto" style="width:110px;">
			<option value=""></option>
			<option value="super_admin" <?php if($f_tipo=='super_admin'){?> selected="selected"<?php } ?>>Súper Administrador</option>
            <option value="administrador" <?php if($f_tipo=='administrador'){?> selected="selected"<?php } ?>>Administrador</option>
			<option value="autorizador" <?php if($f_tipo=='autorizador'){?> selected="selected"<?php } ?>>Autorizador</option>
			<option value="capturista" <?php if($f_tipo=='capturista'){?> selected="selected"<?php } ?>>Capturista</option>
			<option value="reportes" <?php if($f_tipo=='reportes'){?> selected="selected"<?php } ?>>Reportes</option>	
	</select></td>    
	<td><select name="f_pagina" style="width:110px;" class="texto" onchange="submit();">
		<option value=""></option>
		<?php for($i=1;$i<=$pag;$i++) { 
			$del = (($i-1)*100)+1; $al = $i*100; ?>
		<option value="<?php echo $i;?>" <?php if($f_pagina==$i){?> selected="selected"<?php } ?>><?php echo $del."-".$al;?></option>
		<?php } ?>
	</select></td>	
	<td align="center"><input type="submit" class="submit" value="Buscar"></td>
    <td align="center"><input type="button" class="submit" value="Exportar" onclick="exportar();"></td>
</tr>
</table>		
</form>
<table align="center" class="tabla">
<caption>Consultar Empleados: <?php echo $tot;?> registros</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="40" align="center">No.</td>
		<td width="50" align="center">Estado</td>
		<td width="60" align="center">Delegado</td>
		<td width="100" align="center">User ID</td>
		<td width="320" align="center">Nombre</td>
		<td width="50" align="center">A.D.</td>
		<td width="55" align="center">Súper Admin.</td>
        <td width="55" align="center">Admin.</td>
		<td width="70" align="center">Capturista</td>
		<td width="70" align="center">Reportes</td>
        <td width="70" align="center">Materiales</td>
		<td width="110" align="center" colspan="2">Autorizador</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php 
	$s_1.= " limit $ini, 100";
    $r_1 = mysql_query($s_1); $no = $ini+1;
    while($d_1=mysql_fetch_array($r_1)) { 
	$ruta = "&f_acdi=$f_acdi&f_userid=$f_userid&f_depto=$f_depto&f_pagina=$f_pagina&f_tipo=$f_tipo"; ?>

<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $no;?></td>
	<td align="center">
	<?php if($admin=='2') { 
	      if($d_1['activo']=='1') { 
	 			echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'><img src='../imagenes/tick.png' alt='Activo' border='0'>"; } 
	      if($d_1['activo']=='0') { 
		   		echo"<a href='?op=estado&id_=$d_1[id]&estado=1$ruta'><img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } } else { 
 	      if($d_1['activo']=='1') { 
	 			echo"<img src='../imagenes/tick.png' alt='Activo' border='0'>"; } 
	      if($d_1['activo']=='0') { 
		   		echo"<img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } } ?>          
	</td>
	<td align="center"><?php if($d_1['autorizador']!='0' && $d_1['autorizador']!='') { ?>
	<?php if($admin=='2') { ?>
    <a class="frame_ausencia" href="detalles.php?op=ausencia&id_=<?php echo $d_1['id'];?>">	
	<?php if($d_1['ausencia']!='0') { echo"<img src='../imagenes/user_go.png' alt='Tiene 1 delegado' border='0'>"; } 
		   if($d_1['ausencia']=='0') { echo"<img src='../imagenes/user_go_gris.png' alt='No tiene delegados' border='0'>"; } } ?>
	</a><?php } else { ?>
	<?php if($d_1['ausencia']!='0')  { echo"<img src='../imagenes/user_go.png' alt='Tiene 1 delegado' border='0'>"; } 
		   if($d_1['ausencia']=='0') { echo"<img src='../imagenes/user_go_gris.png' alt='No tiene delegados' border='0'>"; } } ?>   
    </td>	
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['usuario'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre']." ".$d_1['apellidos'];?></td>
	<td align="center">&nbsp;&nbsp;<?php echo $d_1['active_directory'];?></td>
	<td align="center">
		<?php if($admin=='2') { ?>
		<?php if($d_1['super_admin']=='1') { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=super_admin&valor=0&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=super_admin&valor=1&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } else { ?>
        <?php if($d_1['super_admin']=='1') { ?><img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } ?></td>	  
	<td align="center">
		<?php if($admin=='2') { ?>
		<?php if($d_1['administrador']=='1') { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=admin&valor=0&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=admin&valor=1&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } else { ?>
        <?php if($d_1['administrador']=='1') { ?><img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } ?></td>	
    <td align="center">
    	<?php if($admin=='2') { ?>
		<?php if($d_1['capturista']=='1') { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=captu&valor=0&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><a href="?op=privilegio<?php echo $ruta;?>&tipo=captu&valor=1&id_emp=<?php echo $d_1['id'];?>">
        <img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } else { ?>
        <?php if($d_1['capturista']=='1') { ?><img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } ?></td>
	<td align="center">
    	<?php if($admin=='2') { ?>
		<?php if($d_1['reportes']=='1') { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=rep&valor=0&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=rep&valor=1&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } else { ?>
        <?php if($d_1['reportes']=='1') { ?><img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } ?></td>					
	<td align="center">
    	<?php if($admin=='2') { ?>
		<?php if($d_1['materiales']=='1') { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=mat&valor=0&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?>
        	<a href="?op=privilegio<?php echo $ruta;?>&tipo=mat&valor=1&id_emp=<?php echo $d_1['id'];?>">
            <img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } else { ?>
        <?php if($d_1['materiales']=='1') { ?><img src='../imagenes/cuadro_verde.gif' alt='Activo' border='0'></a>
		<?php } else { ?><img src='../imagenes/cuadro_rojo.gif' alt='Inactivo' border='0'></a><?php } } ?></td>
	<td width="80">&nbsp;
	<?php switch($d_1['autorizador']) {
		case "0"	:	echo ""; 		    break;
		case "esp"	:	echo "Especial";    break;
		case "oes"	:	echo "OES";   	    break;
		case "ffc"	:	echo "FFC"; 	    break;
		case "ffm"	:	echo "FFM"; 	    break;
		case "sqm"	:	echo "SQM"; 	    break;
		case "fin"	:	echo "Finanzas";    break;
		case "lo"	:	echo "LO"; 		    break;
		case "loa"	:	echo "LO Almacén";  break;
		case "lpl"	:	echo "LPL"; 	    break;
		case "prod"	:	echo "Producción";  break;
		case "inv"	:	echo "Inventarios"; break;
		default		:	echo "";		    break;		
	} ?></td>
	<td width="30" align="center">
    	<?php if($admin=='2') { ?>
        <a class="frame_autorizador" href="detalles.php?op=autorizador&id_=<?php echo $d_1['id'];?>">
        <img src="../imagenes/right.gif" border="0"></a><?php } else { ?><img src="../imagenes/right_off.gif" border="0"><?php } ?></td>
	<td align="center">
   		<?php if($admin=='2') { ?>
        <a href="?op=editar&id_=<?php echo $d_1['id'];?>&<?php echo $ruta;?>">
		<img src="../imagenes/pencil.gif" alt="Editar" border="0"></a><?php } else { ?>
        <img src="../imagenes/pencil_gris.gif" border="0"></a><?php } ?></td>
	<td align="center">
    	<?php if($admin=='2') { ?>
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?>&<?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } else { ?>
        <img src="../imagenes/delete_gris.gif" border="0"></a><?php } ?></td>
</tr>
<?php $no++; } ?>
</tbody>
</table><br><br><br>
<?php }


function privilegio($f_acdi,$f_userid,$f_depto,$f_pagina,$id_emp,$tipo,$valor,$autorizador,$ausencia) {

	if($tipo=='super_admin') {
		$s_ = "update empleados set administrador='0' where id='$id_emp'";
		$r_ = mysql_query($s_);
		$s_ = "update empleados set super_admin='$valor' where id='$id_emp'"; }
	if($tipo=='admin') {
		$s_ = "update empleados set super_admin='0' where id='$id_emp'";
		$r_ = mysql_query($s_);
		$s_ = "update empleados set administrador='$valor' where id='$id_emp'"; }
	if($tipo=='captu') {
		$s_ = "delete from capturistas where id_emp='$id_emp'";
		$r_ = mysql_query($s_);
		$s_ = "update empleados set capturista='$valor' where id='$id_emp'"; }
	if($tipo=='rep') {
		$s_ = "update empleados set reportes='$valor' where id='$id_emp'"; }
	if($tipo=='gen') {
		$s_ = "update empleados set general='$valor' where id='$id_emp'"; }		
	if($tipo=='mat') {
		$s_ = "update empleados set materiales='$valor' where id='$id_emp'"; }		
	$r_ = mysql_query($s_);	

if($autorizador!='') {
	if($autorizador=='cero') { $autorizador = '0'; } 	
	if($ausencia=='cero')    { $ausencia    = '0'; } 	
	$s_ = "update empleados set autorizador='$autorizador', ausencia='$ausencia' where id='$id_emp'";
	$r_ = mysql_query($s_);	
	if($autorizador=='0') {
		$s_ = "update empleados set ausencia='0' where ausencia='$id_emp'";
		$r_ = mysql_query($s_); } }
}


function editar($id_,$f_acdi,$f_userid,$f_depto,$f_pagina) { 
	$s_1 = "Select * from empleados where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
<div align="center" class="aviso">Todos los campos son obligatorios<br>
Si el usuario no utilizará el sistema con su cuenta de Active Directory, seleccione NO y llene los campos necesarios</div>
<form action="?op=update" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="f_acdi" value="<?php echo $f_acdi;?>">
<input type="hidden" name="f_userid" value="<?php echo $f_userid;?>">
<input type="hidden" name="f_depto" value="<?php echo $f_depto;?>">
<input type="hidden" name="f_pagina" value="<?php echo $f_pagina;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Empleado</caption>
<tr>
	<td valign="middle">User-ID (Minúsculas):</td>
	<td><input type="text" name="usuario" class="texto" size="39" maxlength="40" value="<?php echo $d_1['usuario'];?>">
	</td>
</tr>
<tr>
	<td valign="middle">Active Directory:</td>
	<?php $valor = $d_1['active_directory'];?>
	<td>&nbsp;&nbsp;
		<input type="radio" name="ac_di" value="SI" onclick="mostrar();" <?php if($valor=='SI' || $valor=='') {?> checked="checked"<?php } ?>>SI
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="ac_di" value="NO" onclick="mostrar();" <?php if($valor=='NO') {?> checked="checked"<?php } ?>>NO
	</td>
</tr>
</table><br>
<?php if($valor=='SI' || $valor=='') { $display='none'; } else { $display=''; } ?>
 <div id="campos_extra" style="display:<?php echo $display;?>;">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td valign="top" width="90">Nombre:</td>
	<td><input type="text" name="nombre" class="texto" size="45" value="<?php echo $d_1['nombre'];?>">
	</td>
</tr>
<tr>
	<td valign="top">Apellidos:</td>
	<td><input type="text" name="apellidos" class="texto" size="45" value="<?php echo $d_1['apellidos'];?>">
	</td>
</tr>
<tr>
	<td colspan="2" class="aviso">Si no desea cambiar el password, deje los campos en blanco</td>
</tr>
<tr>
	<td valign="top">Password 1:</td>
	<td><input type="password" name="password1" class="texto" size="20" maxlength="10">
	</td>
</tr>
<tr>
	<td valign="top">Password 2:</td>
	<td><input type="password" name="password2" class="texto" size="20" maxlength="10">
	</td>
</tr>
<tr>
	<td valign="top">E-mail:</td>
	<td><input type="text" name="email" class="texto" size="45" value="<?php echo $d_1['mail'];?>">
	</td>
</tr>
</table>
</div>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
</form>
<?php  } 


function update($id_,$nombre,$apellidos,$usuario,$password1,$email,$ac_di,$f_acdi,$f_userid,$f_depto,$f_pagina) {

		$nombre    = fix_data(utf8_decode($nombre));
		$apellidos = fix_data(utf8_decode($apellidos));
		$usuario   = trim(strtolower($usuario));
		$password1 = trim($password1);
		$email     = fix_data(utf8_decode($email));

if($ac_di=='NO') {
	$s_1 = "update empleados set nombre='$nombre', apellidos='$apellidos', usuario='$usuario', mail='$email', active_directory='$ac_di' ";	
	if($password1) { $s_1 = $s_1.", password='$password1'"; } }
if($ac_di=='SI') {
	$s_1 = "update empleados set usuario='$usuario', password='', active_directory='$ac_di' "; }

	$s_1 = $s_1." where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	


function fix_data($cadena){
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	$cadena = utf8_encode(strtr($cadena,utf8_decode($tofind),$replac));
	return strtoupper($cadena);
}


function borrar($id_,$nombre,$f_acdi,$f_userid,$f_depto,$f_pagina) {
	$s_1 = "delete from empleados where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$s_1 = "delete from autorizadores where id_emp='$id_'";
	$r_1 = mysql_query($s_1);	
	$s_1 = "delete from capturistas where id_emp='$id_'";
	$r_1 = mysql_query($s_1);			
	$s_1 = "delete from fitros where id_emp='$id_'";
	$r_1 = mysql_query($s_1);
	$s_1 = "delete from mails where para_id='$id_'";
	$r_1 = mysql_query($s_1);	
	$s_1 = "delete from reportes where id_emp='$id_'";
	$r_1 = mysql_query($s_1);			
}	


function estado($id_,$estado,$nombre,$f_acdi,$f_userid,$f_depto,$f_pagina) {
	$s_1 = "update empleados set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1); }
?>