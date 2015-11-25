<?php include("conexion_db.php"); 


/*

if(date("Y-m-d")<='2014-03-31'){ 
	$s_ = "select * from estaciones where activo!='1'";
	$r_ = mysql_query($s_); $i=0;
	while($d_=mysql_fetch_array($r_)) { 
		$s_1 = "delete from est_proyecto where id_tecnologia='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "delete from estaciones where id='$d_[id]'";
		$r_1 = mysql_query($s_1); $i++;
	}
	echo "Estaciones-->$i<br>";
		
	$s_ = "select * from lineas where activo!='1'";
	$r_ = mysql_query($s_); $i=0;
	while($d_=mysql_fetch_array($r_)) { 
		$s_1 = "delete from lineas_proy where id_linea='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "delete from lineas where id='$d_[id]'";
		$r_1 = mysql_query($s_1); $i++;
	}
	echo "Líneas-->$i<br>";	
	
	$s_ = "select * from defectos where activo!='1'";
	$r_ = mysql_query($s_); $i=0;
	while($d_=mysql_fetch_array($r_)) { 
		$s_1 = "delete from def_proyecto where id_defecto='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "delete from defecto_causa where id_defecto='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "delete from defectos where id='$d_[id]'";
		$r_1 = mysql_query($s_1); $i++;
	}
	echo "Defectos-->$i<br>";	
}


/*
$s_ = "select * from temporal";
$r_ = mysql_query($s_);
while($d_=mysql_fetch_array($r_)) { 
	$s_1 = "select apd from scrap_folios where no_folio='$d_[no_folio]'";
	$r_1 = mysql_query($s_1);
}	

	

	$s_ = "SELECT z_defectos.* FROM z_defectos, z_def_proyecto, proyectos where id_area='1' and z_def_proyecto.id_defecto = z_defectos.id and proyectos.id = ";
	$s_.= "z_def_proyecto.id_proyecto and (id_segmento='28' or id_segmento='29' or id_segmento='30' or id_segmento='31') and z_defectos.activo='1' group by id ";
	$r_ = mysql_query($s_); 
	while ($d_ = mysql_fetch_array($r_)){
		
		$s_1 = "select * from defectos where id='$d_[id]'";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) { 
			echo "insert into defectos (id, id_area, id_estacion, nombre, activo) values ('$d_[id]', '$d_[id_area]', '$d_[id_estacion]', '$d_[nombre]', '$d_[activo]');<br>"; 

			$s_1 = "select * from z_defecto_causa where id_defecto='$d_[id]'";
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {  	
				echo "insert into defecto_causa (id, id_defecto, id_causa) values ('', '$d_[id]', '$d_1[id_causa]');<br>"; } 
	} }	
			
	$s_ = "SELECT z_def_proyecto.* FROM z_defectos, z_def_proyecto, proyectos where id_area='1' and z_def_proyecto.id_defecto = z_defectos.id and proyectos.id = ";
	$s_.= "z_def_proyecto.id_proyecto and (id_segmento='28' or id_segmento='29' or id_segmento='30' or id_segmento='31') and z_defectos.activo='1'";
	$r_ = mysql_query($s_); 
	while ($d_ = mysql_fetch_array($r_)){
		
		$s_1 = "select * from def_proyecto where id_defecto='$d_[id_defecto]' and id_proyecto='$d_[id_proyecto]'";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) { 			
			echo "insert into def_proyecto (id, id_defecto, id_proyecto) values ('', '$d_[id_defecto]', '$d_[id_proyecto]');<br>"; 
	} }	
	

	
	$s_ = "SELECT z_estaciones.* FROM z_estaciones, z_est_proyecto, proyectos where id_area='1' and z_est_proyecto.id_tecnologia = z_estaciones.id and proyectos.id = ";
	$s_.= "z_est_proyecto.id_proyecto and (id_segmento='28' or id_segmento='29' or id_segmento='30' or id_segmento='31') group by id";
	$r_ = mysql_query($s_); 
	while ($d_ = mysql_fetch_array($r_)){
		
		$s_1 = "select * from estaciones where id='$d_[id]'";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) { 
			echo "insert into estaciones (id, id_area, nombre, activo) values ('$d_[id]', '$d_[id_area]', '$d_[nombre]', '$d_[activo]');<br>"; 

			$s_1 = "select * from z_est_proyecto where id_tecnologia='$d_[id]'";
			$r_1 = mysql_query($s_1);
			while($d_1=mysql_fetch_array($r_1)) {  	
				echo "insert into est_proyecto (id, id_tecnologia, id_proyecto) values ('', '$d_[id]', '$d_1[id_proyecto]');<br>"; } 
		}
	}

	$s_ = "SELECT z_estaciones.* FROM z_estaciones, z_est_proyecto, proyectos where id_area='1' and z_est_proyecto.id_tecnologia = z_estaciones.id and proyectos.id = ";
	$s_.= "z_est_proyecto.id_proyecto and (id_segmento='28' or id_segmento='29' or id_segmento='30' or id_segmento='31') group by id";
	$r_ = mysql_query($s_); 
	while ($d_ = mysql_fetch_array($r_)){
					
		$s_1 = "select * from z_defectos where id_estacion='$d_[id]' and activo='1'";
		$r_1 = mysql_query($s_1);
		while($d_1=mysql_fetch_array($r_1)) {
		
			$s_2 = "select * from defectos where id='$d_1[id]'";
			$r_2 = mysql_query($s_2);
			if(mysql_num_rows($r_2)<=0) { 
				echo "insert into defectos (id, id_area, id_estacion, nombre, activo) values ('$d_1[id]', '$d_1[id_area]', '$d_[id]', '$d_1[nombre]', '$d_1[activo]');<br>"; 
				
				$s_2 = "select * from z_defecto_causa where id_defecto='$d_1[id]'";
				$r_2 = mysql_query($s_2);
				while($d_2=mysql_fetch_array($r_2)) {  	
					echo "insert into defecto_causa (id, id_defecto, id_causa) values ('', '$d_1[id]', '$d_2[id_causa]');<br>"; } 

				$s_2 = "select * from z_def_proyecto where id_defecto='$d_1[id]'";
				$r_2 = mysql_query($s_2);
				while($d_2=mysql_fetch_array($r_2)) {  	
					echo "insert into def_proyecto (id, id_defecto, id_proyecto) values ('', '$d_1[id]', '$d_2[id_proyecto]');<br>"; }
			}		
	} }	


	$s_ = "SELECT z_estaciones.* FROM z_estaciones, z_est_proyecto, proyectos where id_area='1' and z_est_proyecto.id_tecnologia = z_estaciones.id and proyectos.id = ";
	$s_.= "z_est_proyecto.id_proyecto and (id_segmento='28' or id_segmento='29' or id_segmento='30' or id_segmento='31') group by id";
	$r_ = mysql_query($s_); 
	while ($d_ = mysql_fetch_array($r_)){
					
		$s_1 = "select * from z_lineas where id_area='$d_[id_area]' and id_estacion='$d_[id]'";
		$r_1 = mysql_query($s_1);
		while($d_1=mysql_fetch_array($r_1)) { 
		
			$s_2 = "select * from lineas where id='$d_1[id]'";
			$r_2 = mysql_query($s_2);
			if(mysql_num_rows($r_2)<=0) { 
				echo "insert into lineas (id, id_area, id_estacion, nombre, activo) values ('$d_1[id]', '$d_1[id_area]', '$d_[id]', '$d_1[nombre]', '$d_1[activo]');<br>"; 
				
				$s_2 = "select * from z_lineas_proy where id_linea='$d_1[id]'";
				$r_2 = mysql_query($s_2);
				while($d_2=mysql_fetch_array($r_2)) {  	
					echo "insert into lineas_proy (id, id_linea, id_proyecto) values ('', '$d_1[id]', '$d_2[id_proyecto]');<br>"; } 
			}		
	} }*/
	
	$hoy = date("Y-m-d");
	
/*	if($hoy=='2014-09-05'){
		$s_r = "select * from reporte_turnos ";
		$r_r = mysql_query($s_r);
		if(mysql_num_rows($r_r)<=0){
			$s_ = "select * from 2014_scrap_folios ";
			$r_ = mysql_query($s_);
			while($d_ = mysql_fetch_array($r_)){
				$anio_r = substr($d_['timer'],0,4);
				$mes_r = substr($d_['timer'],4,2);
				$dia_r = substr($d_['timer'],6,2);
				$horas_r = substr($d_['timer'],8,2);
				$min_r = substr($d_['timer'],10,2);
				$fecha_r = $anio_r."-".$mes_r."-".$dia_r;
				$hora_r = $horas_r.":".$min_r;
				$folio = $d_['no_folio'];
				$s_1 = "select * from tmp_turnos where '$fecha_r' between fecha_1 and fecha_2";
				$r_1 = mysql_query($s_1);
				while($d_1 = mysql_fetch_array($r_1)){
					if($hora_r>="07:00" && $hora_r<="18:59"){
						$s_2 = "insert into reporte_turnos values ('','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";
						$s_2.= "'$d_1[turno_1]')";	
						$r_2 = mysql_query($s_2);
					} else {
						$s_2 = "insert into reporte_turnos values ('','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";	
						$s_2.= "'$d_1[turno_2]')";	
						$r_2 = mysql_query($s_2);
					}
				}
			}
			$s_ = "select * from scrap_folios ";
			$r_ = mysql_query($s_);
			while($d_ = mysql_fetch_array($r_)){
				$anio_r = substr($d_['timer'],0,4);
				$mes_r = substr($d_['timer'],4,2);
				$dia_r = substr($d_['timer'],6,2);
				$horas_r = substr($d_['timer'],8,2);
				$min_r = substr($d_['timer'],10,2);
				$fecha_r = $anio_r."-".$mes_r."-".$dia_r;
				$hora_r = $horas_r.":".$min_r;
				$folio = $d_['no_folio'];
				$s_1 = "select * from tmp_turnos where '$fecha_r' between fecha_1 and fecha_2";
				$r_1 = mysql_query($s_1);
				while($d_1 = mysql_fetch_array($r_1)){
					if($hora_r>="07:00" && $hora_r<="18:59"){
						$s_2 = "insert into reporte_turnos values ('','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";
						$s_2.= "'$d_1[turno_1]')";
						$r_2 = mysql_query($s_2);
					} else {
						$s_2 = "insert into reporte_turnos values ('','$folio','$fecha_r','$hora_r','$d_[id_emp]','$d_[empleado]','$d_[id_apd]','$d_[apd]', ";	
						$s_2.= "'$d_1[turno_2]')";	
						$r_2 = mysql_query($s_2);
					}
				}
			}
		}
		$file_name="turnos2014_".date("Ymd").".xls";
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=$file_name"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
        <table align="center" border="1">
            <thead>
                <tr>
                	<td align="center" bgcolor="#FFCC00"><b>#</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>Folio</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>Fecha</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>Hora</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>Turno</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>Empleado</b></td>
                    <td align="center" bgcolor="#FFCC00"><b>APD</b></td>
                </tr>
            </thead>
            <tbody>
            <?php 
			$s_1 = "select * from reporte_turnos";
			$r_1 = mysql_query($s_1); $i=1;
                  while($d_1=mysql_fetch_array($r_1)) { ?>
            <tr>
                <td align="center"><?php echo $i;?></td>
                <td align="center"><?php echo $d_1['folio']; ?></td>
                <td align="left"><?php echo $d_1['fecha'];?></td>
                <td align="left"><?php echo $d_1['hora'];?></td>
                <td><?php echo $d_1['turno'];?></td>
                <td><?php echo $d_1['empleado'];?></td>
                <td><?php echo $d_1['apd'];?></td>
            </tr>
            <?php $i++; } ?>
            </tbody> 
        </table>  
	<?php }*/
	
	if($hoy=='2015-04-09' || $hoy =="2015-04-10"){
		//BORRO LOS PROYECTOS QUE ESTÁN REPETIDOS DEF
		/*$s_ = "select count(*) as total, def_proyecto.* from def_proyecto GROUP BY id_defecto, id_proyecto having total > 1 order by total";
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)){
			$s_1 = "select id from def_proyecto where id_proyecto='$d_[id_proyecto]' and id_defecto='$d_[id_defecto]' order by id asc limit 1,$d_[total]";	
			$r_1 = mysql_query($s_1);
			while($d_1 = mysql_fetch_array($r_1)){
				$s_2 = "delete from def_proyecto where id='$d_1[id]'";
				echo $s_2.";<br>";
				//$r_2 = mysql_query($s_2);
			}
		}
		//BORRO LOS PROYECTOS QUE ESTÁN REPETIDOS LIN
		$s_ = "select count(*) as total, lineas_proy.* from lineas_proy GROUP BY id_linea, id_proyecto having total > 1 order by total";
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)){
			$s_1 = "select id from lineas_proy where id_proyecto='$d_[id_proyecto]' and id_linea='$d_[id_linea]' order by id asc limit 1,$d_[total]";	
			$r_1 = mysql_query($s_1);
			while($d_1 = mysql_fetch_array($r_1)){
				$s_2 = "delete from lineas_proy where id='$d_1[id]'";
				echo $s_2.";<br>";
				//$r_2 = mysql_query($s_2);
			}
		}*/
		
		//LLENO MI TABLA PIVOTE
		/*$s_ = "select id_defecto, id_estacion, id_proyecto from def_proyecto, defectos where defectos.id = def_proyecto.id_defecto and activo!='2' order by id_defecto ";
		$r_ = mysql_query($s_); $query = "";
		while($d_ = mysql_fetch_array($r_)){
			$s_1 = "insert into pivote_proy values ('','$d_[id_estacion]','$d_[id_defecto]','$d_[id_proyecto]','1')";
			$query.= $s_1.";\r\n";
			//$r_1 = mysql_query($s_1);	
		}
		$fp = fopen("C:/Users/Darkangel/Desktop/pivote.txt","x");
		fwrite($fp,$query);	
		fclose($fp);*/
			
		//TECNOLOGIAS
		/*$query = "";
		$s_ = "select id from estaciones where activo!='2'";	
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)>0){
			while($d_ = mysql_fetch_array($r_)){
				//TEC - PROY
				$s_1 = "select id_proyecto from est_proyecto where id_tecnologia='$d_[id]'";	
				$r_1 = mysql_query($s_1);
				if(mysql_num_rows($r_1)>0){
					while($d_1 = mysql_fetch_array($r_1)){
						//DEF - TEC
						$s_3 = "select id from defectos where id_estacion='$d_[id]' and activo!='2'";
						$r_3 = mysql_query($s_3);
						if(mysql_num_rows($r_3)>0){
							while($d_3 = mysql_fetch_array($r_3)){
								//DEF - PROY
								$s_4 = "select id from def_proyecto where id_defecto='$d_3[id]' and id_proyecto='$d_1[id_proyecto]'";
								$r_4 = mysql_query($s_4);
								//INGRESO LOS PROYECTOS QUE NO EXISTAN EN ESE DEF
								/*if(mysql_num_rows($r_4)<=0){
									$s_5 = "insert into def_proyecto values ('','$d_3[id]','$d_1[id_proyecto]')";
									//echo $s_5.";<br>";
									//$r_5 = mysql_query($s_5);
									$query.= $s_5.";\r\n";
									$s_5 = "insert into pivote_proy values ('','$d_[id]','$d_3[id]','$d_1[id_proyecto]','1')";
									//echo $s_5.";<br>";
									//$r_5 = mysql_query($s_5);
									$query.= $s_5.";\r\n";
								}*/
								/*if(mysql_num_rows($r_4)>0){
									$s_5 = "delete from pivote_proy where id_tecnologia='$d_[id]' and id_defecto='$d_3[id]' and ";
									$s_5.= "id_proyecto='$d_1[id_proyecto]'";
									$query.= $s_5.";\r\n";
								}
							}
						}
					}
				}
			}
		}
		$fp = fopen("C:/Users/Darkangel/Desktop/del_pivote.txt","x");
		fwrite($fp,$query);	
		fclose($fp);*/
		//COMPRUEBO QUE NO EXISTAN EN EST_PROYECTO
		$query = "";
		$s_ = "select * from pivote_proy order by id_tecnologia";
		$r_ = mysql_query($s_);
		while($d_ = mysql_fetch_array($r_)){
			//EXISTE LA TECNOLOGIA?
			$s_1 = "select * from est_proyecto where id_tecnologia = '$d_[id_tecnologia]' and id_proyecto='$d_[id_proyecto]' ";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)<=0){
				$s_2 = "delete from def_proyecto where id_defecto = '$d_[id_defecto]' and id_proyecto='$d_[id_proyecto]'";
				$query.= $s_2.";\r\n";
				$s_2 = "delete from pivote_proy where id='$d_[id]'";
				$query.= $s_2.";\r\n";
			}
		}
		$fp = fopen("/usr/local/apache2/htdocs/vhosts/scrap_gdl/respaldos_db/del_proy.txt","x"); //modificar ruta
		fwrite($fp,$query);	
		fclose($fp);
	}
?>