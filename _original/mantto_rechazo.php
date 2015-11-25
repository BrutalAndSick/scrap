<?php include("conexion_db.php"); 

$s_ = "select folios.no_folio from scrap_folios as folios,aut_bitacora as bit,autorizaciones as auto where folios.status='2' and folios.activo='1' ";
$s_.= "and bit.no_folio=folios.no_folio and auto.no_folio=folios.no_folio and bit.status='2' group by folios.no_folio ";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)){
	echo $d_['no_folio']."<br>";
}
