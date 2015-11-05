<?php
function mantto($boton) {

	if($boton=='1'){
		require_once("config.php"); respaldar_secc_2("catalogos");
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE PLANTAS
	/*$s_ = "select * from plantas where activo='2'";
	$r_ = mysql_query($s_); 
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "update divisiones set activo='2' where id_planta='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "update profit_center set activo='2' where id_planta='$d_[id]'";
		$r_1 = mysql_query($s_1);
		$s_1 = "update proyectos set activo='2' where id_planta='$d_[id]'";
		$r_1 = mysql_query($s_1);		
		$s_1 = "update segmentos set activo='2' where id_planta='$d_[id]'";
		$r_1 = mysql_query($s_1);	
		$s_1 = "delete from codigo_scrap_depto where id_planta='$d_[id]'";
		$r_1 = mysql_query($s_1);				
	}*/
	
	//ACTUALIZAR TODO LO QUE CUELGA DE DIVISIONES
	if($boton=='1'){ echo "<br>DIVISIONES<br><br>"; }
	$s_ = "select * from divisiones where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "update apd set activo='2' where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from autorizadores where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from capturistas where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br><br>"; }
		$s_1 = "update profit_center set activo='2' where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update proyectos set activo='2' where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update segmentos set activo='2' where id_division='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE SEGMENTOS
	if($boton=='1'){ echo "<br>SEGMENTOS<br><br>"; }
	$s_ = "select * from segmentos where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "update apd set activo='2' where id_segmento='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update profit_center set activo='2' where id_segmento='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }		
		$s_1 = "update proyectos set activo='2' where id_segmento='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DEL PROFIT CENTER
	if($boton=='1'){ echo "<br>PROFIT CENTER<br><br>"; }
	$s_ = "select * from profit_center where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from autorizadores where id_pc='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update proyectos set activo='2' where id_pc='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }		
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE PROYECTOS
	if($boton=='1'){ echo "<br>PROYECTOS<br><br>"; }
	$s_ = "select * from proyectos where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from def_proyecto where id_proyecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from est_proyecto where id_proyecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from lineas_proy where id_proyecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from proy_ubicacion where id_proyecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }				
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE ÁREAS
	if($boton=='1'){ echo "<br>&Aacute;REAS<br><br>"; }
	$s_ = "select * from areas where activo='2' and (id!='14' and id!='1')";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from autorizadores where id_area='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update defectos set activo='2' where id_area='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
		$s_1 = "update estaciones set activo='2' where id_area='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update lineas set activo='2' where id_area='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }			
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE TECNOLOGÍA
	if($boton=='1'){ echo "<br>TECNOLOG&Iacute;A<br><br>"; }
	$s_ = "select * from estaciones where activo='2' and (id!='731' and id!='554')";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "update defectos set activo='2' where id_estacion='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
		$s_1 = "delete from est_proyecto where id_tecnologia='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "update lineas set activo='2' where id_estacion='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }			
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE LÍNEA
	if($boton=='1'){ echo "<br>L&Iacute;NEA<br><br>"; }
	$s_ = "select * from lineas where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from lineas_proy where id_linea='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE CÓDIGO DE SCRAP
	if($boton=='1'){ echo "<br>C&Oacute;DIGO DE SCRAP<br><br>"; }
	$s_ = "select * from codigo_scrap where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from causa_codigo where id_codigo='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from codigo_scrap_depto where codigo_scrap='$d_[codigo]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE CAUSAS
	if($boton=='1'){ echo "<br>CAUSAS<br><br>"; }
	$s_ = "select * from causas where activo='2' and (id!='3')";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from causa_codigo where id_causa='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }
		$s_1 = "delete from defecto_causa where id_causa='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE DEFECTOS
	if($boton=='1'){ echo "<br>DEFECTOS<br><br>"; }
	$s_ = "select * from defectos where activo='2' and (id!='12162' and id!='11364')";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from defecto_causa where id_defecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
		$s_1 = "delete from def_proyecto where id_defecto='$d_[id]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }		
	}
	
	//ACTUALIZAR TODO LO QUE CUELGA DE NÚMEROS DE PARTE
	if($boton=='1'){ echo "<br>N&Uacute;MEROS DE PARTE<br><br>"; }
	$s_ = "select * from numeros_parte where activo='2'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)) { 
		$s_1 = "delete from batch_ids where parte='$d_[nombre]'";
		$r_1 = mysql_query($s_1); if($boton=='1'){ echo $s_1."<br>"; }	
	} 
} ?>