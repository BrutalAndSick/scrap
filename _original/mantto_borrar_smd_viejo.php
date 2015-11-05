<?php include("conexion_db.php");

//ID SMD VIEJO MÁS VIEJO 3

$s_ = "select * from areas where id='3'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from autorizadores where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
	$s_1 = "update defectos set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);	
	$s_1 = "update estaciones set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
	$s_1 = "update lineas set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);				
}

//=================ELIMINO TODO LO QUE CUELGA DE DEFECTOS
$s_ = "select * from defectos where activo='2' and id_area='3'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from defecto_causa where id_defecto='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);	
	$s_1 = "delete from def_proyecto where id_defecto='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);		
}
//=================ELIMINO TODO LO QUE CUELGA DE ESTACIONES
$s_ = "select * from estaciones where activo='2' and id_area='3'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 	
	$s_1 = "delete from est_proyecto where id_tecnologia='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);		
}

//=================ELIMINO TODO LO QUE CUELGA DE LINEAS
$s_ = "select * from lineas where activo='2' and id_area='3'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from lineas_proy where id_linea='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
}

//ID SMD VIEJO MÁS RECIENTE 13

$s_ = "select * from areas where id='13'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from autorizadores where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
	$s_1 = "update defectos set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);	
	$s_1 = "update estaciones set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
	$s_1 = "update lineas set activo='2' where id_area='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);				
}

//=================ELIMINO TODO LO QUE CUELGA DE DEFECTOS
$s_ = "select * from defectos where activo='2' and id_area='13'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from defecto_causa where id_defecto='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);	
	$s_1 = "delete from def_proyecto where id_defecto='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);		
}
//=================ELIMINO TODO LO QUE CUELGA DE ESTACIONES
$s_ = "select * from estaciones where activo='2' and id_area='13'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 	
	$s_1 = "delete from est_proyecto where id_tecnologia='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);		
}

//=================ELIMINO TODO LO QUE CUELGA DE LINEAS
$s_ = "select * from lineas where activo='2' and id_area='13'";
$r_ = mysql_query($s_);
while($d_ = mysql_fetch_array($r_)) { 
	$s_1 = "delete from lineas_proy where id_linea='$d_[id]'";
	echo $s_1.";<br>";
	$r_1 = mysql_query($s_1);
}