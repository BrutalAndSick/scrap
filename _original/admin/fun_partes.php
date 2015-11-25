<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>
function RefreshParent() { 
    parent.location.reload(); 
} 
</script>

<?php include("../conexion_db.php"); 
   switch($op) {
	case "insert_txt"	:	insert_txt($alias,$tipo); break; 
 }	

function insert_txt($alias,$tipo) { 
	$s_ = "delete from tmp_partes";
	$r_ = mysql_query($s_);
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
		
	$r_server = $d_['valor']; $cont=0;
	//echo "Leyendo archivo: ".$r_server.$alias."<br>";
	$fd       = fopen($r_server."$alias", "r");
	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		if(substr($buffer,0,3)!='---') { 
			$campo['0'] = trim(substr($buffer,0,15)); //Parte Padre
			$campo['0'] = ltrim($campo['0'],"0"); //Quitar ceros a la izquierda
			$campo['1'] = trim(substr($buffer,39,25)); //Material
			$campo['1'] = ltrim($campo['1'],"0"); //Quitar ceros a la izquierda
			$campo['2'] = trim(substr($buffer,81,5)); //APD
			$campo['3'] = trim(substr($buffer,89,6)); //Tipo
			$campo['4'] = trim(substr($buffer,26,28)); //Nivel
		if($campo['0']!='Material' && $campo['0']!='' && $campo['1']!='' && $campo['2']!='' && $campo['3']!='' && $campo['4']!='') {
			$sql = "INSERT into tmp_partes values('$campo[0]', '$campo[1]', '$campo[2]', '$campo[3]', '$campo[4]', '$alias')"; 
			$res = mysql_query($sql); 
			$cont++;
		}	
	} } 
	fclose($fd); 
	unlink($r_server.$alias); 
	guardar_temp($tipo);
	exit;

	/*$s_ = "delete from tmp_partes";
	$r_ = mysql_query($s_);
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
		
	$r_server = $d_['valor']; $cont=0; 
	//echo "Leyendo archivo: ".$r_server.$alias."<br>";
	$fd       = fopen($r_server."$alias", "r");
	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		$campos = split ("\t", $buffer);
		if($campos['0']!='Material' && substr($campos['0'],0,3)!='---') { 
			$padre    = ltrim($campos['0'],"0"); //Parte Padre
			$nivel    = trim($campos['2']); //Nivel
			$material = ltrim($campos['5'],"0"); //Material
			$apd 	  = trim($campos['8']); //APD
			$tipo_    = trim($campos['11']); //Tipo
		
		if($padre!='' && $material!='' && $nivel!='' && $apd!='' && $tipo!='') {
			$sql = "INSERT into tmp_partes values('$padre', '$material', '$apd', '$tipo_', '$nivel', '$alias')"; 
			$res = mysql_query($sql);
			$cont++;
		}	
	} } 
	fclose($fd); 
	unlink($r_server.$alias); 
	guardar_temp($tipo); 
	exit;*/
}	 


function existe_registro($padre,$material,$apd) {
	$s_ = "select * from partes_padre where padre='$padre' and material='$material' and apd='$apd'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)<=0) {
		return "NO"; }
	else { return "SI"; }	
}


function guardar_temp($tipo) {
$i = 0;

if($tipo=='nueva') { //borro
	$s_2 = "select * from tmp_partes group by apd order by apd"; 
	$r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { 
		$s_1 = "delete from partes_padre where apd='$d_2[apd]'";
		$r_1 = mysql_query($s_1); }

	$s_2 = "select * from tmp_partes group by padre, material, apd order by apd"; 
	$r_2 = mysql_query($s_2);//inserto
	while($d_2=mysql_fetch_array($r_2)) { 	
		$s_1 = "insert into partes_padre values('$d_2[padre]', '$d_2[material]', '$d_2[apd]', '$d_2[type]', '$d_2[nivel]', '1', ";
		$s_1.= "'$d_2[archivo]')";
		$r_1 = mysql_query($s_1); $i++; } 
}
if($tipo=='acumulada') {
	$s_2 = "select * from tmp_partes group by padre, material, apd order by apd"; 
	$r_2 = mysql_query($s_2);//comparo e inserto 
	while($d_2=mysql_fetch_array($r_2)) { 	
		if(existe_registro($d_2['padre'],$d_2['material'],$d_2['apd'])=='NO') { 
			$s_1 = "insert into partes_padre values('$d_2[padre]', '$d_2[material]', '$d_2[apd]', '$d_2[type]', '$d_2[nivel]', '1', ";
			$s_1.= "'$d_2[archivo]')";
			$r_1 = mysql_query($s_1); $i++; } 
		else {
			$s_1 = "update partes_padre set nivel='$d_2[nivel]' where padre='$d_2[padre]' and material='$d_2[material]' and ";
			$s_1.= "apd='$d_2[apd]'";
			$r_1 = mysql_query($s_1); $i++; } 			
	}
}	
	echo"<script>alert('$i registros insertados/actualizados');</script>";	
	echo"<script>parent.change_parent_url('partes_padre.php?op=listado');</script>";
} ?>