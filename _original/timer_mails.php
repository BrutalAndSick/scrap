<?php include("conexion_db.php"); conectame_db("scrap_gdl"); include("mails.php");

//WINDOWS:
//Panel de control, Herramientas administrativas, Programador de Tareas
//Crear la nueva tarea ejecutando una aplicación: php con argumentos c:/servidor/web/scrap_gdl/timer_mails.php -r
//Cambiar el orden en php.ini de php_exif.dll por php_mbstring.dll

$s_1 = "select * from configuracion where variable = 'send_mails_hour'";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);
list($hora,$min,$seg) = split(":",$d_1['valor']);
$hora_envio  = mktime($hora,$min,$seg,0,0,0);
$hora_actual = mktime(date("H"),date("i"),date("s"),0,0,0);

$s_1 = "select * from configuracion where variable = 'last_mails_date'";
$r_1 = mysql_query($s_1);
$d_1 = mysql_fetch_array($r_1);
list($anio,$mes,$dia) = split("-",$d_1['valor']);
$dia_ultimo = mktime(0,0,0,$mes,$dia,$anio); 
$dia_actual = mktime(0,0,0,date("m"),date("d"),date("Y")); 

$fecha = date("Y-m-d");
$hora  = date("H:i:s");
$user_ = get_config("usuario");
$pass_ = get_config("password");

if($dia_ultimo<$dia_actual && $hora_actual>=$hora_envio) { 
	echo "Enviando recordatorios de scrap pendiente pre-programados...";
	enviar_mails_auto(); }	

$s_ = "update configuracion set valor='$fecha' where variable='last_mails_date'";
$r_ = mysql_query($s_);
$s_ = "update configuracion set valor='$hora' where variable='last_mails_hour'";
$r_ = mysql_query($s_);	
?>