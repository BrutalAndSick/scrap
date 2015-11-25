<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 

function upload_file($tabla,$type,$archivo,$archivo_name) { 
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
			upload_form($tabla); exit; }
		else { 
			insert_csv($tabla,$type,$nombre); }
	}
}	

function insert_csv($tabla, $type, $alias) { 	
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor']; 	
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."$alias", "r");
	$insertar = 0;

	while ( !feof($fd) ) 
 	{
		$buffer    = fgets($fd);
		$campos    = split ("\t", $buffer);
		if($campos['0']!='' && $campos['0']!='tipo' && $campos['0']!='Material Type') {
			$campos['0'] = trim($campos['0']);
			$campos['1'] = trim($campos['1']);
			$campos['2'] = trim($campos['2']);
			$campos['3'] = str_replace("\"","",trim($campos['3']));
			$campos['4'] = trim($campos['4']);
			$campos['5'] = trim($campos['5']);
			$campos['6'] = trim($campos['6']);
		switch($tabla) {
		
		case "roh"	:
			/*campo 0*/
			if($campos['0']=='ROH' || $campos['0']=='HIBE' || $campos['0']=='HAWA' || $campos['0']=='VERP') {
					$field['tipo'] = $campos['0'];  } //Tipo 
			else { 
				echo"<div align=center class=aviso_naranja>El tipo $campos[0] no debe estar vacío, sólo ROH, HIBE, HAW ó VERP</div>";
				$insertar++; } 	
			/*campo 1*/
			if($campos['1']!='') {
				$field['parte'] = ltrim($campos['1'],"0"); //Número de parte
			} else { 
				echo"<div align=center class=aviso_naranja>El número de parte $campos[1] no debe estar vacío</div>";
				$insertar++; }	
			/*campo 2*/ 
			$campos['2'] = str_replace(",","",$campos['2']);
			$campos['2'] = str_replace("\"","",$campos['2']);
			$campos['2'] = str_replace("'","",$campos['2']);
			if($campos['2']!='' && $campos['2']>0) {
				$field['costo'] = $campos['2']; //Costo
			} else { 
				echo"<div align=center class=aviso_naranja>El costo $campos[2] no debe estar vacío y debe mayor a cero</div>";
				$insertar++; } 
			/*campo 3*/
			$field['descripcion']	= htmlentities($campos['3'],ENT_QUOTES,"UTF-8"); //Descripción
			$field['batch']			= ''; //Batch ID
			$field['unidad']		= trim($campos['4']); //Unidad de medida	
			$campos['5'] = str_replace(",","",$campos['5']);
			$campos['5'] = str_replace("\"","",$campos['5']);
			$campos['5'] = str_replace("'","",$campos['5']);
			$field['global_pc']		= trim($campos['5']); //Global Profit Center
			
			$s_u = "select * from unidades where unidad='$field[unidad]'";
			$r_u = mysql_query($s_u);
			if(mysql_num_rows($r_u)<=0) { 
				echo"<div align=center class=aviso_naranja>La unidad $field[unidad] no existe</div>";
				$insertar++; } 			
			break;
		
		case "halb"	:
			/*campo 0*/ 
			if($campos['0']!='HALB') { 
				echo"<div align=center class=aviso_naranja>El tipo $campos[0] no debe estar vacío, sólo HALB</div>";
				$insertar++; } 
			/*campo 1*/
			if($campos['1']=='S.Real' || $campos['1']=='S.Real/AutoBF') {
				$field['tipo'] = $campos['1']; //Parte Subensamble
			} else { 
				echo"<div align=center class=aviso_naranja>El tipo sub $campos[1] debe ser S.Real ó S.Real/AutoBF</div>";
				$insertar++;  }	
			/*campo 2*/ 
			if($campos['2']!='') {
				$field['parte'] = ltrim($campos['2'],"0"); //Número de parte
			} else { 
				echo"<div align=center class=aviso_naranja>El número de parte $campos[2] no debe estar vacío</div>";
				$insertar++; } 
			/*campo 3*/ 
			$campos['3'] = str_replace(",","",$campos['3']);
			$campos['3'] = str_replace("\"","",$campos['3']);
			$campos['3'] = str_replace("'","",$campos['3']);
			if($campos['3']!='' && $campos['3']>0) {
				$field['costo'] = $campos['3']; //Costo
			} else { 
				echo"<div align=center class=aviso_naranja>El costo $campos[3] no debe estar vacío y debe ser mayor a cero</div>";
				$insertar++; } 
			/*campo 4*/
			$field['descripcion'] = htmlentities($campos['4'],ENT_QUOTES,"UTF-8"); //Descripción
			$field['unidad'] = trim($campos['5']); //Unidad de medida	
			$campos['6'] = str_replace(",","",$campos['6']);
			$campos['6'] = str_replace("\"","",$campos['6']);
			$campos['6'] = str_replace("'","",$campos['6']);
			$field['global_pc'] = trim($campos['6']); //Global P.C.	
			
			$s_u = "select * from unidades where unidad='$field[unidad]'";
			$r_u = mysql_query($s_u);
			if(mysql_num_rows($r_u)<=0) { 
				echo"<div align=center class=aviso_naranja>La unidad $field[unidad] no existe</div>";
				$insertar++; } 				
			break;
		
		case "fert"	:		
			/*campo 0*/
			if($campos['0']=='FERT' || $campos['0']=='KMAT') {
					$field['tipo'] = $campos['0'];  } //Tipo 
			else { 
				echo"<div align=center class=aviso_naranja>El tipo $campos[0] no debe estar vacío, sólo FERT/KMAT</div>";
				$insertar++; } 	
			/*campo 1*/
			if(trim($campos['1'])!='') {
				$field['parte'] = ltrim($campos['1'],"0"); //Número de parte
			} else { 
				echo"<div align=center class=aviso_naranja>El número de parte $campos[1] no debe estar vacío</div>";
				$insertar++; }	
			if(existe_batch($campos['2'])=="SI"){
				if($campos['2']!=''){ 
					//BUSCO EL ID DEL BATCH ID
					$s_b = "select * from batch_id where batch_id='$campos[2]'";
					$r_b = mysql_query($s_b);					
					if(mysql_num_rows($r_b)>0){
						$d_b = mysql_fetch_array($r_b);
						$field['batch'] = $d_b['id']; 
					} else {
						echo"<div align=center class=aviso_naranja>El batch id: ($campos[2]) no existe</div>";
						$insertar++;	
					}
				}
			} else {
				echo"<div align=center class=aviso_naranja>El batch id: ($campos[2]) no existe</div>";
				$insertar++; }
			/*campo 3*/
			$campos['3'] = str_replace(",","",$campos['3']);
			$campos['3'] = str_replace("\"","",$campos['3']);
			$campos['3'] = str_replace("'","",$campos['3']);
			$campos['3'] = str_replace("/","",$campos['3']);
			$campos['3'] = str_replace("\\","",$campos['3']);
			if($campos['3']!='' && $campos['3']>0) {
				$field['costo'] = $campos['3']; //Costo
			} else { 
				echo"<div align=center class=aviso_naranja>El costo $campos[3] no debe estar vacío y debe ser mayor a cero</div>";
				$insertar++; } 
			/*campo 4*/
			$campos['4'] = str_replace(",","",$campos['4']);
			$campos['4'] = str_replace("\"","",$campos['4']);
			$campos['4'] = str_replace("'","",$campos['4']);
			$campos['4'] = str_replace("/","",$campos['4']);
			$campos['4'] = str_replace("\\","",$campos['4']);
			$field['descripcion']	= htmlentities($campos['4'],ENT_QUOTES,"UTF-8");
			$field['unidad'] = trim($campos['5']); //Unidad de medida	
			$campos['6'] = str_replace(",","",$campos['6']);
			$campos['6'] = str_replace("\"","",$campos['6']);
			$campos['6'] = str_replace("'","",$campos['6']);
			$campos['6'] = str_replace("/","",$campos['6']);
			$campos['6'] = str_replace("\\","",$campos['6']);
			$field['global_pc'] = trim($campos['6']); //Global P.C.
			if($field['global_pc']=='' || $field['global_pc']=='0' || $field['global_pc']=='NA') { 
				$field['global_pc'] = 'ND'; } 
			
			$s_u = "select * from unidades where unidad='$field[unidad]'";
			$r_u = mysql_query($s_u);
			if(mysql_num_rows($r_u)<=0) { 
				echo"<div align=center class=aviso_naranja>La unidad $field[unidad] no existe</div>";
				$insertar++; } 	
			break;
		}
		
		if($insertar<=0) {
				$query = "INSERT into tmp_numeros values('', '$field[parte]', '$field[descripcion]', '$tabla', '$field[tipo]', ";
				$query.= "'$field[batch]', '$field[costo]', '$field[unidad]', '$field[global_pc]', '$alias')"; 
				mysql_query($query); $insertar=0;
				if($tabla=='fert') {
					  $query = "INSERT into tmp_batch values('','$field[parte]','$field[batch]')";
					  mysql_query($query); }
				 }
			else { 
				echo "<br><div class=aviso_naranja align=center>Error al insertar el registro $field[parte], ";
				echo "por favor verifique que el archivo tenga el formato necesario<br>";
				echo "<br><br>No se puede continuar con la carga !!</div><br>"; 
				fclose ($fd); 
				unlink($r_server.$alias);
				exit; }						
		}	
	} 
	fclose ($fd); 
	unlink($r_server.$alias); 
	listado_temporal($type,$tabla,$orden,$tipo); } 



function listado_temporal($type,$tabla,$orden,$tipo) { ?>
<div align="center" class="aviso">Por favor, verifique si la información para la carga es correcta</div>
<form action="?op=upload_form" method="post" name="form1">
<input type="hidden" name="type" value="<?php echo $type;?>">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<input type="hidden" name="tipo" value="<?php echo $tipo;?>">
<table align="center" class="tabla">
<caption>¿Es correcta la información?<br>(Solamente se muestran los primeros 100 registros)</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">No. de Parte</td>
		<td width="100" align="center">Descripcion</td>	
		<td width="100" align="center">Tipo</td>
		<?php if($tabla=='halb') { ?>	
		<td width="100" align="center">Tipo Sub</td>
		<?php } ?>	
		<td width="100" align="center">Precio</td>
        <td width="100" align="center">Unidad</td>
		<td width="100" align="center">Global P.C.</td>
		<?php if($tabla=='fert') { ?>	
		<td width="100" align="center">BatchID</td>
		<?php } ?>	
	</tr>
</thead>
<?php 
   $s_1 = "select * from tmp_numeros order by id limit 0,100";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo html_entity_decode($d_1['descripcion'],ENT_QUOTES,"UTF-8");?></td>
	<?php if($tabla!='halb') { ?>
		<td align="left">&nbsp;&nbsp;<?php echo $d_1['tipo'];?></td>
	<?php } else { ?>
		<td align="left">&nbsp;&nbsp;<?php echo strtoupper($d_1['tabla']);?></td>
	<?php } ?>
	<?php if($tabla=='halb') { ?>
		<td align="left">&nbsp;&nbsp;<?php echo $d_1['tipo'];?></td>
	<?php } ?>	
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['costo'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['unidad'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['global_pc'];?></td>
	<?php if($tabla=='fert') { ?>	
		<td align="left">&nbsp;&nbsp;<?php echo get_batch($d_1['batch_id']);?></td>
	<?php } ?>	
</tr>
<?php } ?>
</tbody>
</table>
<div align="center"><br>
<input type="button" value="Cancelar" onclick="cancelar('?op=upload_form')" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Continuar" onclick="cancelar('?op=guardar_temp')" class="submit">
</div>
</form>
<?php }



function guardar_temp($type,$tabla,$orden,$tipo) {
	$i=$j=0;

	$s_2 = "select * from tmp_numeros group by nombre, tipo order by id";
	$r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { 
		$s_ = "select * from numeros_parte where nombre='$d_2[nombre]' and tabla='$tabla'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { //SI SON NUEVOS LOS INSERTO
			$s_1 = "insert into numeros_parte values('','$d_2[nombre]','$d_2[descripcion]', '$tabla', '$d_2[tipo]', '$d_2[costo]',";
			$s_1.= "'$d_2[unidad]', '$d_2[global_pc]', '1', '$d_2[archivo]')"; $i++;}
		else { 	//SI NO SON NUEVOS LOS ACTUALIZO
			$s_1 = "update numeros_parte set descripcion='$d_2[descripcion]', costo='$d_2[costo]', unidad='$d_2[unidad]', tipo='$d_2[tipo]', global_pc='$d_2[global_pc]' where ";
			$s_1.= "nombre='$d_2[nombre]' and tabla='$tabla'"; $j++; }
			$r_1 = mysql_query($s_1);  
	} 
			
	if($tabla=='fert') {
		$s_2 = "select * from tmp_batch order by id";
		$r_2 = mysql_query($s_2);
			while($d_2=mysql_fetch_array($r_2)) { 
				$s_ = "select * from batch_ids where parte='$d_2[parte]' and batch_id='$d_2[batch_id]'";
				$r_ = mysql_query($s_);
				if(trim($d_2['batch_id'])!='' && trim($d_2['batch_id'])!='0' && mysql_num_rows($r_)<=0) { 
					$s_1 = "insert into	batch_ids values('','$d_2[parte]','$d_2[batch_id]','1')";
					$r_1 = mysql_query($s_1); }	
			}	
	}
	echo"<script>alert('$i registros insertados, $j actualizados');</script>";		
}

function existe_batch($batch){
	$batch = trim($batch);
	$s_ = "select * from batch_id where batch_id='$batch'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0){
		return "SI";	
	} else {
		if($batch!=''){
			return "NO";	
		} else {
			return "SI";
		}
	}
}

function get_batch($id){
	$s_ = "select batch_id from batch_id where id='$id'";	
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['batch_id'];
}?>