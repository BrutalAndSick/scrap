<?php function conectame_db( $BD )
{	
	$mysql=mysql_connect('localhost','root','');
	if (!$mysql)
	{	echo "Can not connect mysql ".$BD;
		exit;
	}
	
	$mysql=mysql_select_db( $BD );
	if (!$mysql)
	{	echo "Can not select database ".$BD;
		exit;
	}
}

conectame_db("scrap_gdl");

$s_1 = "select no_folio, fecha from scrap_folios";
$r_1 = mysql_query($s_1); $i=0;
while($d_1=mysql_fetch_array($r_1)) {
	$s_2 = "select no_folio, fecha from aut_bitacora where no_folio='$d_1[no_folio]' and status='6' order by id asc";
	$r_2 = mysql_query($s_2); 
	if(mysql_num_rows($r_2)>0) { 
		$d_2 = mysql_fetch_array($r_2);
		$fecha = $d_2['fecha'];
		list($anio,$mes,$dia) = split("-",$fecha);
    	$semana  = date('W',mktime(0,0,0,$mes,$dia,$anio));
		if($fecha!=$d_1['fecha']) { 
			echo $d_1['fecha']."-->";
			$s_3 = "update scrap_folios set fecha='$fecha', semana='$semana', anio='$anio' where no_folio='$d_1[no_folio]'";
			$r_3 = mysql_query($s_3);
			echo $s_3."<br>";
		$i++; }	
	}
}  echo "$i Registros Editados";
?>	