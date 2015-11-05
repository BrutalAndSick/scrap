<? 
function upload_file($archivo,$archivo_name) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = date("YmdHis").".".$pext;
	$nom_final = $r_server.$nombre;
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			upload_form(); exit; }
		else { 
			insert_csv($nombre); }
	}		
}


function insert_csv($alias) {	
	bloquear_sistema();
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);

	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."$alias", "r");
	$ins = 0; $linea = 1;

	while ( !feof($fd) ) 
 	{
		$buffer    = fgets($fd);
		$campos    = split ("\t", $buffer);
		if($campos['0']!='' && $campos['0']!='Fecha') {

			$campos['0'] = trim($campos['0']); //Fecha
			$campos['1'] = trim($campos['1']); //Turno
			$campos['2'] = trim($campos['2']); //Proyecto
			$campos['3'] = trim($campos['3']); //APD
			$campos['4'] = trim($campos['4']); //Área
			$campos['5'] = trim($campos['5']); //Tecnología
			$campos['6'] = trim($campos['6']); //Línea
			$campos['7'] = trim($campos['7']); //Defecto
			$campos['8'] = trim($campos['8']); //Relacionado a
			$campos['9'] = trim($campos['9']); //Cod.SCRAP
			$campos['10'] = trim($campos['10']); //No.Parte
			$campos['11'] = trim($campos['11']); //Cantidad
			$campos['12'] = trim($campos['12']); //Parte Padre
			$campos['13'] = trim($campos['13']); //Ubicación
			$campos['14'] = trim($campos['14']); //Supervisor
			$campos['15'] = trim($campos['15']); //Operador
			$campos['16'] = trim($campos['16']); //No.Personal
			$campos['17'] = trim($campos['17']); //No.Serial
			$campos['18'] = trim($campos['18']); //O.Mantto
			$campos['19'] = trim($campos['19']); //Docto.SAP
			$campos['20'] = trim($campos['20']); //Comentarios
			$campos['21'] = trim($campos['21']); //Acción Correctiva		

			if($campos['0']!='') { $field['fecha'] = $campos['0']; } 
			else { echo"<div align=center class=aviso_naranja>La fecha vacía en el registro $linea</div>"; $ins++; } 	

			if($campos['1']!='') { $field['turno'] = $campos['0']; } 
			else { echo"<div align=center class=aviso_naranja>El turno no debe estar vacío en el registro $linea</div>"; $ins++; } 	

			if($campos['2']!='') { 
				$s_ = "select plantas.id as id_planta, plantas.nombre as planta, divisiones.id as id_division, divisiones.nombre as ";
				$s_.= "division, profit_center.id as id_pc, profit_center.nombre as profit_center, segmentos.id as id_segmento, ";
				$s_.= "segmentos.nombre as segmento, proyectos.id, proyectos.nombre from plantas, divisiones, profit_center, segmentos, ";
				$s_.= "proyectos where proyectos.nombre like '$campos[2]' and proyectos.id_planta = plantas.id and ";
				$s_.= "proyectos.id_division = divisiones.id and proyectos.id_pc = profit_center.id and proyectos.id_segmento = ";
				$s_.= "segmentos.id and proyectos.activo='1'";
				$r_ = mysql_query($s_);
				if(mysql_num_rows($r_)>0) {
					$d_ = mysql_fetch_array($r_);
					$field['id_proyecto'] = $d_['id'];
					$field['proyecto'] = $d_['nombre'];
					$field['id_planta'] = $d_['id_planta'];
					$field['planta'] = $d_['planta'];
					$field['id_division'] = $d_['id_division'];
					$field['division'] = $d_['division'];
					$field['id_pc'] = $d_['id_pc'];
					$field['profit_center'] = $d_['profit_center'];
					$field['id_segmento'] = $d_['id_segmento'];
					$field['segmento'] = $d_['segmento']; }
				else {
				 	echo"<div align=center class=aviso_naranja>El proyecto no existe en el registro $linea</div>"; $ins++;
			else { echo"<div align=center class=aviso_naranja>El proyecto no debe estar vacío en el registro $linea</div>"; $ins++; } 


		if($ins<=0) {
				$query = "INSERT into scrap_folios values('','$empleado','";
				mysql_query($query); $insertar=0;
				if($tabla=='fert') {
					  $query = "INSERT into batch_temporal values('','$field[parte]','$field[batch]')";
					  mysql_query($query); }
				 }
			else { 
				echo "<br><div class=aviso_naranja align=center>Error al insertar el registro $field[parte], ";
				echo "por favor verifique que el archivo tenga el formato necesario<br>";
				echo "<br><br>No se puede continuar con la carga !!</div><br>"; 
				desbloquear_sistema();
				fclose ($fd); 
				unlink($r_server.$alias);
				exit; }						
		}	
	} 
	fclose ($fd); 
	unlink($r_server.$alias);
	desbloquear_sistema();
	listado_temporal($type,$tabla,$orden,$tipo); } 
?>