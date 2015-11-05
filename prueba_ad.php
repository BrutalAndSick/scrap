<script>
function login(){
	form1.action = '?op=login';
	form1.submit(); }
	
function email(){
	form1.action = '?op=email';
	form1.submit(); }
</script>	
<?php 
include("conexion_db.php"); 
conectame_db("scrap_gdl");


switch($op) {
	case "formulario"	:	formulario($usuario); break;
	case "login"		:	formulario($usuario); login($usuario,$password); break;
	case "email"		:	formulario($usuario,$password); email($usuario,$password); break;
	default				:	formulario($usuaro); break;
}	



function formulario($usuario) { 
if($usuario!='') {
	$s_1 = "select * from empleados where usuario='$usuario'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); 
}
$s_ = "select * from empleados where active_directory='SI' order by apellidos";
$r_ = mysql_query($s_); ?>

<form method="post" action="?op=formulario" name="form1">
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="100">Usuario:</td>
	<td><select name="usuario" onchange="submit();">
	<option value=""></option>
		<?php while($d_=mysql_fetch_array($r_)) { ?>
	<option value="<?php echo $d_['usuario'];?>" <?php if($usuario==$d_['usuario']){?> selected="selected"<?php } ?>>
		<?php echo $d_['apellidos']." ".$d_['nombre'];?></option>
		<?php } ?></select></td>
</tr>
<tr>
	<td>USER ID:</td>
	<td><input type="text" disabled="disabled" value="<?php echo $d_1['usuario'];?>"></td>
</tr>
<tr>
	<td>Administrador:</td>
	<td><input type="text" disabled="disabled" value="<?php echo $d_1['administrador'];?>"></td>
</tr>
<tr>
	<td>Autorizador:</td>
	<td><input type="text" disabled="disabled" value="<?php echo $d_1['autorizador'];?>"></td>
</tr>
<tr>
	<td>Capturista:</td>
	<td><input type="text" disabled="disabled" value="<?php echo $d_1['capturista'];?>"></td>
</tr>
<tr>
	<td>Reportes:</td>
	<td><input type="text" disabled="disabled" value="<?php echo $d_1['reportes'];?>"></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" name="password"></td>
</tr></table>
<div align="center"><br>
		<input type="submit" value="Prueba de Login" onclick="login();">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Envio de Correos" onclick="email();">
</div>
</form> 
<?php }
 


function email($usuario,$password) {

	$data = get_data_ad($usuario,$password);
	echo "<div align=center>";
	echo "ID-->".strtoupper($usuario)."<br>";
	echo "Nombre-->".$data['name']."<br>";
	echo "Mail--->".$data['mail'];
	echo "</div><br>";

	$sheader = $sheader."From: Sistema Scrap\n";
	$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
	$sheader = $sheader."Mime-Version: 1.0\n";    
	$sheader = $sheader."Content-Type: text/html";	

	$msj = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" bgcolor=\"#FFFFFF\">
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Estimado:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$data[name]</b></td></tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" width=\"80\" align=\"left\">&nbsp;Mail:</td>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:11px; color:#FF6600;\" width=\"500\" align=\"left\">
		<b>&nbsp;$data[mail]</b></td></tr>		
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Se ha editado la boleta de scrap rechazada con el no. $folio</td>
	</tr>
	<tr height=20>
		<td style=\"font-family:Arial, Helvetica, sans-serif;font-size:12px;\" colspan=\"2\" align=\"left\">
		&nbsp;Revise que la edici&oacute;n sea correcta y apruebe el folio para continuar con el proceso de validaci&oacute;n</td></tr>	
	</table><br><br>";
	$msj = htmlentities(utf8_encode($msj),ENT_QUOTES,"UTF-8");
	$sub = utf8_encode("Prueba del sistema");	
	
echo html_entity_decode($msj);
mail($data['mail'],$sub,html_entity_decode($msj),$sheader); 
}



function login($usuario,$password) {
	echo "<div align=center>";
	$data   = get_data_ad($usuario,$password);
	if($data['name']!='') {
		echo "Login Correcto!<br>Su nombre es: ".$data['name']."<br>";
		echo "Su mail es: ".$data['mail']; }
	else { 
		echo "Login Incorrecto!"; }
	echo "</div>";	
}



function get_data_ad($usuario,$password) {
   $user_	  = strtoupper($usuario);

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
  	 $r       = ldap_bind($conex,$user_.$dominio,$password); 
   	 if ($r) { 
	   		$result  = ldap_search($conex,$dn,$filter,$attr);
			$entries = ldap_get_entries($conex,$result);
			for($i=0; $i<$entries["count"]; $i++) {
				$data['name'] = $entries[$i]["givenname"][0].' '.$entries[$i]["sn"][0];
				$data['mail'] = $entries[$i]["mail"][0];												
			} 
		}	
  	 } 		
 return $data; }
 

?>