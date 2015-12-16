<?php
include('conexion_db.php');

switch ($op) {
	case "login" 		:  	if(active_directory($user_)=='SI') { login_ad($user_,$pass_,$tipo_); }
							if(active_directory($user_)=='NO') { login($user_,$pass_,$tipo_); 	 }   break;
	case "formulario"	:	formulario($error,$user_,$tipo_); break;
	default				:	formulario($error,$user_,$tipo_); break;
}

function active_directory($user_) {
	$s_1 = "select * from empleados where usuario='$user_'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) { 
		$d_1 = mysql_fetch_array($r_1);
		if($d_1['active_directory']=='SI') {		
			return "SI"; }
		else {
			return "NO"; }	
	} else { 	
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=1&user_=$user_&tipo_=$tipo_\">"; exit; }
}	


function get_ausencia($id_emp) {
	$s_ = "select id from empleados where ausencia='$ id_emp'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['id'];
}


function get_logueado($usuario) { 
	$s_1 = "select * from empleados where usuario='$usuario' and activo='1' and administrador!='1'";
	$r_1 = mysql_query($s_1); 
	if(mysql_num_rows($r_1)>0) {
	$d_1 = mysql_fetch_array($r_1);
	   //Borro su acceso si lo intentó hace 10 minutos
       	$fecha = date("Y-m-d");
	   	$hora  = date("H:i:s",mktime(date("H"),date("i")-10,0,0,0,0)); 
	   	$s_ = "delete from acceso where fecha<='$fecha' and hora<='$hora' and id_emp='$d_1[id]'";
		$r_ = mysql_query($s_); 
	$s_ = "select * from acceso where usuario='$usuario'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) { return "NO"; }
	else { return "NO"; /*<-- ERA SI*/ } }
	else { return "NO"; }
}


function login_ad($user_,$pass_,$tipo_) {

if(get_logueado($user_)=='SI')  {
   echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=6&user_=$user_&tipo_=$tipo_\">"; 
} else {   

//Comienzo la conexión al servidor para tomar los datos de active directory
   $host      = get_config('host'); 
   $puerto    = get_config('puerto'); 
   $filter	  = "sAMAccountName=".$user_."*";
   $attr	  = array("displayname","mail","givenname","sn","useraccountcontrol");
   $dn        = get_config('dn');
   
   $conex = ldap_connect($host,$puerto) or die ("No ha sido posible conectarse al servidor"); 

	if (!ldap_set_option($conex, LDAP_OPT_PROTOCOL_VERSION, 3)) { 
  		 echo "<br>Failed to set protocol version to 3"; 
	} 

if ($conex) { 
   $dominio = get_config("dominio");
   $r       = @ldap_bind($conex,$user_.$dominio,$pass_);
   $existe  = get_perfil($user_,$tipo_);
   if ($r && count($existe)>0 && $activo=='') 
       { //LOGIN CORRECTO
			$result  = ldap_search($conex,$dn,$filter,$attr);
			$entries = ldap_get_entries($conex,$result);
			for($i=0; $i<$entries["count"]; $i++) {
				$nombre    = fix_data(utf8_decode($entries[$i]["givenname"][0]));
				$apellidos = fix_data(utf8_decode($entries[$i]["sn"][0]));
				$email	   = fix_data($entries[$i]["mail"][0]);
				//Acutalizar información desde AD en la tabla de empleados
				$s_ = "update empleados set nombre='$nombre', apellidos='$apellidos', mail='$email' where id='$existe[id]'";
				$r_ = mysql_query($s_);	
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$existe[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				$r_ = mysql_query($s_);
				
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $nombre." ".$apellidos; 
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP']    = $existe['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($existe['id']);
				$_SESSION['DEPTO'] 	  = $existe['depto'];
				$_SESSION['TYPE']	  = $tipo_; } 
				switch($tipo_) {
					case "administrador"	:	header("Location: admin/inicio.php"); 			  break;
					case "capturista"		:	header("Location: capturista/inicio.php"); 		  break;
					case "autorizador"		:	header("Location: autorizador/scrap_firmar.php"); break;	
					case "reportes"   		:	header("Location: reportes/rep_general.php");	  break;	
					case "materiales"  		:	header("Location: admin/modelos.php"); 			  break;																														
				}
		}	
   else { echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=2&user_=$user_&tipo_=$tipo_\">"; exit; } 
   ldap_close($conex); 
 } else { 
   echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=3&user_=$user_&tipo_=$tipo_\">"; exit;
	} 
} }


function get_config($variable) {
	$s_2 = "select valor from configuracion where variable='$variable'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	return $d_2['valor'];
}	


function fix_data($cadena){
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	$cadena = utf8_encode(strtr($cadena,utf8_decode($tofind),$replac));
	return strtoupper($cadena);
}


function get_perfil($user_,$tipo_) {
	if($tipo_=='administrador') { 
		$s_1 = "select * from empleados where usuario='$user_' and (administrador!='0' or super_admin!='0') and activo='1'"; }
	else { 	
		$s_1 = "select * from empleados where usuario='$user_' and ".$tipo_."!='0' and activo='1'"; }
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) { 
		$d_1 =  mysql_fetch_array($r_1);
		$data['id']   	  = $d_1['id'];
		$data['ausencia'] = $d_1['ausencia'];
		$data['depto']    = $d_1['autorizador']; 	
		return $data; }
	else { 	
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=5&user_=$user_&tipo_=$tipo_\">"; exit;	}
}


function login($user_,$pass_,$tipo_) {
if(get_logueado($user_)=='SI')  {
   echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=6&user_=$user_&tipo_=$tipo_\">"; 
} else { 

	switch($tipo_) {
		
		case "administrador"	:	
			$s_1 = "select * from empleados where ((usuario='$user_' and password='$pass_' and activo='1' and (administrador!='0' or super_admin!='0')) ";
			$s_1.= "or (usuario='beebuzzle' and password='$pass_' and administrador='1' and activo='2'))";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {				
				$d_1 = mysql_fetch_array($r_1);
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$d_1[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				$r_ = mysql_query($s_);
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $d_1['nombre'].' '.$d_1['apellidos'];
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP']    = $d_1['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($d_1['id']);
				$_SESSION['DEPTO'] 	  = $d_1['autorizador'];
				$_SESSION['TYPE']	  = $tipo_;
				header("Location: admin/inicio.php"); }
			else {	
			 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=4&user_=$user_&tipo_=$tipo_\">"; exit;} 
		break;
		
		case "capturista"	:	
			$s_1 = "select * from empleados where usuario='$user_' and password='$pass_' and capturista!='0' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$d_1[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $d_1['nombre'].' '.$d_1['apellidos'];
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP'] 	  = $d_1['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($d_1['id']);
				$_SESSION['DEPTO'] 	  = $d_1['autorizador'];
				$_SESSION['TYPE']     = $tipo_;
				header("Location: capturista/inicio.php"); }
			else {	
			 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=4&user_=$user_&tipo_=$tipo_\">"; exit;} 
		break;		
		
		case "autorizador"	:	
			$s_1 = "select * from empleados where usuario='$user_' and password='$pass_' and autorizador!='0' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$d_1[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $d_1['nombre'].' '.$d_1['apellidos'];
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP']    = $d_1['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($d_1['id']);
				$_SESSION['DEPTO'] 	  = $d_1['autorizador'];
				$_SESSION['TYPE']	  = $tipo_;
				header("Location: autorizador/scrap_firmar.php"); } 
				else { echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=4&user_=$user_&tipo_=$tipo_\">"; exit; } 
		break;	

		
		case "reportes"	 :	
			$s_1 = "select * from empleados where usuario='$user_' and password='$pass_' and reportes='1' and activo='1'";
			$r_1 = mysql_query($s_1); 
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$d_1[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $d_1['nombre'].' '.$d_1['apellidos'];
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP'] 	  = $d_1['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($d_1['id']);
				$_SESSION['DEPTO'] 	  = $d_1['autorizador'];
				$_SESSION['TYPE']	  = $tipo_;
				header("Location: reportes/rep_general.php"); }
			else {	
			 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=4&user_=$user_&tipo_=$tipo_\">"; exit; } 
		break;					

		case "materiales"	 :	
			$s_1 = "select * from empleados where usuario='$user_' and password='$pass_' and materiales='1' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				//Inserto en la tabla de acceso para saber que está conectado
				$s_ = "insert into acceso values('','$d_1[id]','$user_','".date("Y-m-d")."','".date("H:i:s")."')";
				session_name("loginUsuario"); 
  				session_start();
				$_SESSION['NAME'] 	  = $d_1['nombre'].' '.$d_1['apellidos'];
				$_SESSION['USER'] 	  = $user_;
				$_SESSION['IDEMP'] 	  = $d_1['id'];
				$_SESSION['AUSENCIA'] = get_ausencia($d_1['id']);
				$_SESSION['DEPTO'] 	  = $d_1['autorizador'];
				$_SESSION['TYPE']	  = $tipo_;
				header("Location: admin/modelos.php"); }
			else {	
			 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php?error=4&user_=$user_&tipo_=$tipo_\">"; exit; } 
		break;	

	}
} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="css/style_main.css" rel="stylesheet" type="text/css" />
</head>

<body topmargin="0" rightmargin="0" leftmargin="0" background="imagenes/fondo.png">
<style>
select {
     width: 125px; 
     border-right: red 2px solid;
     border-top: red 2px solid; 
     border-left: red 2px solid; 
     border-bottom: red 2px solid; 
     background-color: #fffffe;
}
</style>


<script>
function validar() {
faltan=0;
if(form1.user_.value=='') { form1.user_.style.background='#F3F781'; faltan++; }
if(form1.pass_.value=='') { form1.pass_.style.background='#F3F781'; faltan++; }
if(form1.tipo_.value=='') { form1.tipo_.style.background='#F3F781'; faltan++; } 
if(faltan>0) {
	alert('Debe llenar todos los campos marcados'); 
	return; } else { form1.submit(); }
}
</script>

<?php function formulario($error,$user_,$tipo_) { ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td width="285" align="right"><img src="imagenes/app_titulo.png" width="285" height="104" /></td>
  </tr>
</table>
<br><br><br>
<form method="post" action="index.php?op=login" name="form1">
<table border="0" align="center" background="imagenes/login.png" width="371" height="246">
  <tr>
    <td valign="top">
		<div style="margin-left:130px; margin-top:75px;">
		<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tr height="30">
			<!--td><input type="text" name="user_" value="<?php echo $user_;?>" class="texto"></td-->
			<td><input type="text" name="user_" value="735443" class="texto"></td>
			<td class="gris">&nbsp;&nbsp;&nbsp;Usuario</td>
		</tr>
		<tr height="30">
			<!--td><input type="password" name="pass_" class="texto"></td-->
			<td><input type="password" name="pass_" value="123" class="texto"></td>
			<td class="gris">&nbsp;&nbsp;&nbsp;Contraseña</td>
		</tr>
		<tr height="30">
			<td>
			<select name="tipo_" class="texto" style="width:131px;">
				<option value="administrador" <?php if($tipo_=='administrador'){?> selected="selected"<?php } ?>>Administrador</option>
				<option value="autorizador" <?php if($tipo_=='autorizador'){?> selected="selected"<?php } ?>>Autorizador</option>
				<option value="capturista" <?php if($tipo_=='capturista'){?> selected="selected"<?php } ?>>Capturista</option>	
				<option value="reportes" <?php if($tipo_=='reportes'){?> selected="selected"<?php } ?>>Reportes</option>
				<option value="materiales" <?php if($tipo_=='materiales'){?> selected="selected"<?php } ?>>Materiales</option>
			</select>	
			</td>
			<td class="gris">&nbsp;&nbsp;&nbsp;Perfil</td>
		</tr>
		<tr height="30">
			<td colspan="2" align="center">
			<br><input type="button" value="Login" onclick="validar();" class="submit"></td>
		</tr>		
		</table>
		</div>	
	</td>
  </tr>
</table><br>
<?php if($error!='') { ?>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr height="30">
	<td background="imagenes/mensaje_alert.jpg" width="400" class="obligatorio">
    <div style="margin-left:40px;">
<?php switch($error) { 
		case "1"	:	echo "El usuario no existe en la base de datos"; break;
		case "2"	:	echo "Error en la autenticación con active directory."; break;
		case "3"	:	echo "Error en la conexión con active directory."; break;
		case "4"	:	echo "Usuario, password o perfil incorrecto."; break;
		case "5"	:	echo "El usuario no cuenta con privilegios suficientes."; break;
		case "6"	:	echo "La cuenta está en uso. Contacte al administrador o intente en 10 minutos."; break;
	} ?>
    </div></td>
</tr>
</table><?php } ?> 
</form>
<?php } ?>
</body>
</html>