<?php 
include("conexion_db.php");
conectame_db('scrap_gdl');
//Ajustar pendientes
$s_ = "select no_folio from scrap_folios where status='2' and activo='1'";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$s_1 = "select * from autorizaciones where no_folio='$d_[no_folio]' and status!='0'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)<=0) {
		$s_2 = "update scrap_folios set status='0' where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2); }
}

//Ajustar aprobados
$s_ = "select no_folio from scrap_folios where status='2' and activo='1'";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$s_1 = "select * from autorizaciones where no_folio='$d_[no_folio]' and status!='1'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)<=0) {
		$s_2 = "update scrap_folios set status='1' where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2); }
}

//Ajustar cancelados
$s_ = "select no_folio from scrap_folios where status='0' and activo='1'";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$s_1 = "select * from autorizaciones where no_folio='$d_[no_folio]' and status='2'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)>0) {
		$s_2 = "update scrap_folios set status='2' where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2); }
}

//Ajustar aprobados
$s_ = "select no_folio from scrap_folios where status='0' and activo='1'";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) {
	$s_1 = "select * from autorizaciones where no_folio='$d_[no_folio]' and status!='1'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)<=0) {
		$s_2 = "update scrap_folios set status='1' where no_folio='$d_[no_folio]'";
		$r_2 = mysql_query($s_2); }
}

echo "LISTO!!";
?>