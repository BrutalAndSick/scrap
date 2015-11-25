<?php include("../header.php"); ?>

<script>
function validar(tipo) {						
	if(form1.batch_id.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.batch_id.value='';
		alert('Es necesario ingresar el Batch ID');
		form1.batch_id.focus(); return; }
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update'; }
	form1.submit();	
}

function exportar() {
	form1.action = 'excel.php?op=batch_id';
	form1.submit();
	form1.action = 'batch_id.php';
}

function upload() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='txt') {
		alert('Utilice solamente archivos .txt');
		form1.archivo.focus(); return; }				
	form1.submit();	
}

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_materiales'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_materiales',"batch_id"); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td background="../imagenes/borde_izq_tabla.png">&nbsp;</td>
    <td>&nbsp;
	<!--Todo el contenido de cada página--->
	<?php menu_interno();
		switch($op) {
		case "upload_form"		:	upload_form($tabla); break;
		case "upload_file"		: 	upload_file($archivo,$archivo_name); break;

		case "guardar_temp"		:	guardar_temp(); listado($orden,$buscar); break;			
	
		case "nuevo"			:	nuevo(); break;
		case "guardar"			:	guardar($batch_id); 
									nuevo(); break;
	
		case "listado"			:	listado($inicio,$buscar); break;
		case "estado"			:	estado($id_,$estado,$buscar,$inicio); 
									listado($inicio,$buscar); break;
		case "editar"			:	editar($id_,$buscar,$inicio); break;
		case "update"			:	update($id_,$buscar,$inicio,$batch_id); 
									listado($inicio,$buscar); break;
	
		case "borrar"			:	borrar($id_,$tabla,$buscar,$inicio); 
									listado($inicio,$buscar); break;		
		default					:	listado($inicio,$buscar); break;
	} ?>	
	<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");



function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200">BATCH ID</td>
	<?php if($_SESSION["TYPE"]=='administrador') { ?>
	<td><a href="?op=nuevo" class="menuLink">Nuevo</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td><?php } ?>    
</tr>
</table></div><hr>
<?php } 	

function listado($inicio,$buscar) {
	if(!$inicio)    $inicio = 0;
	if($buscar!='') $inicio = 0; 
	$s_1   = "select * from batch_id where activo!='2' ";
	$r_1   = mysql_query($s_1);
	$items = mysql_num_rows($r_1);
	$pags  = ceil($items/100)-1; ?>
	<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div>
    <form action="?op=listado" method="post" name="form1">
    <div align="center" class="texto">
        Buscar:&nbsp;&nbsp;
        <input type="text" class="texto" name="buscar" value="<?php echo $buscar;?>" size="30">&nbsp;&nbsp;&nbsp;&nbsp;
        Ir al registro:&nbsp;&nbsp;
        <select name="inicio" class="texto" style="width:50px;" onchange="submit();">
            <option value=""></option>
            <?php for($i=0;$i<=$pags;$i++) { $valor = $i*100; ?>
            	<option value="<?php echo $valor;?>" <?php if($inicio==$valor){ ?> selected="selected"<?php } ?>><?php echo $valor+1;?></option>
            <?php } ?>
        </select>
        &nbsp;&nbsp;
        <input type="submit" class="submit" value="Buscar">
        &nbsp;&nbsp;
        <input type="button" class="submit" value="Exportar" onclick="exportar();">
    </div><br>
    <table align="center" class="tabla">
        <thead>
            <tr bgcolor="#E6E6E6" height="20">
                <td width="50" align="center">No.</td>
                <td width="50" align="center">Estado</td>		
                <td width="200" align="center">Batch ID</td>
                <td width="40" align="center">Editar</td>
                <td width="40" align="center">Borrar</td>
            </tr>
        </thead>
        <?php 
           $s_1  = "select * from batch_id where activo!='2' and batch_id like '$buscar%' order by batch_id asc limit $inicio,100"; $j=$inicio+1;
           $r_1  = mysql_query($s_1); 
           $ruta = "&buscar=$buscar&inicio=$inicio"; ?>
        <tbody>
			<?php while($d_1=mysql_fetch_array($r_1)) { ?>
            <tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
                <td align="center"><?php echo $j;?></td>
                <td align="center">
                    <?php if($_SESSION["TYPE"]=='administrador') {
                    	if($d_1['activo']=='1') { 
							echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'><img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
                       	if($d_1['activo']=='0') { 
							echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'><img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } 
					} else { 
                       	if($d_1['activo']=='1') { echo"<img src='../imagenes/tick_gris.png' alt='Activo' border='0'>";    } 
                       	if($d_1['activo']=='0') { echo"<img src='../imagenes/cross_gris.png' alt='Inactivo' border='0'>"; } 
					} ?>                                         
                </td>
                <td align="left">&nbsp;&nbsp;<?php echo $d_1['batch_id'];?></td>
                <td align="center">
                    <?php if($_SESSION["TYPE"]=='administrador') { ?>
                    	<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a>
					<?php } else { ?><img src="../imagenes/pencil_gris.gif" alt="No puede editar" /><?php } ?>
                </td>
                <td align="center">
                    <?php if($_SESSION["TYPE"]=='administrador') { ?>
                        <a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
                        <img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>
					<?php } else { ?><img src="../imagenes/delete_gris.gif" alt="No puede borrar" /><?php } ?>
                </td>
            </tr>
            <?php $j++; } ?>
        </tbody>
    </table>
    </form>
<?php }

function borrar($id_,$tabla,$buscar,$inicio) {
	$s_1 = "update batch_id set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function estado($id_,$estado,$buscar,$inicio) {
	$s_1 = "update batch_id set activo='$estado' where id='$id_'"; 
	$r_1 = mysql_query($s_1);   
}
	
function editar($id_,$buscar,$inicio) { 
	$s_1 = "Select * from batch_id where id='$id_' ";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1); ?>
    <div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
    <form action="?op=editar" method="post" name="form1">
    <input type="hidden" name="id_" value="<?php echo $id_;?>">
    <input type="hidden" name="buscar" value="<?php echo $buscar;?>">
    <input type="hidden" name="inicio" value="<?php echo $inicio;?>">
    <table align="center" class="tabla" cellpadding="0" cellspacing="5">
        <caption>Editar Batch ID</caption>
        <tr>
            <td valign="top">Batch ID:*</td>
            <td><input type="text" name="batch_id" class="texto" size="20" value="<?php echo $d_1['batch_id'];?>">
            </td>
        </tr>
    </table>
    <br><div align="center">
    <input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Guardar" onclick="validar(2);" class="submit"></div>
    </form>
<?php  } 

function update($id_,$buscar,$inicio,$batch_id) {
	$batch_id = trim($batch_id);
	$s_1 = "update batch_id set batch_id='$batch_id' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function nuevo() { ?>
    <div align="center" class="aviso">Los campos marcados (*) son obligatorios</div>
    <form action="?op=nuevo" method="post" name="form1">
    <table align="center" class="tabla" cellpadding="0" cellspacing="5">
        <caption>Nuevo Batch ID</caption>
        <tr>
            <td valign="top">Batch ID:*</td>
            <td><input type="text" name="batch_id" class="texto" size="20">
            </td>
        </tr>
    </table>
    <br><div align="center">
    <input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Guardar" onclick="validar(1);" class="submit"></div>
    </form>
<?php  } 


function guardar($batch_id) {
	$batch_id = trim($batch_id);
	$s_1 = "select * from batch_id where batch_id='$batch_id' and activo!='2'";
	$r_1 = mysql_query($s_1);
	if(mysql_num_rows($r_1)<=0){
		$s_1 = "insert into batch_id values('','$batch_id','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; 
	} else { echo"<script>alert('Ese registro ya existe');</script>"; }
}
	
function upload_form(){ 
	$s_1 = "delete from tmp_batch_id"; $r_1 = mysql_query($s_1); ?>
    <form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
        <table align="center" class="tabla" cellpadding="0" cellspacing="5">
            <caption>Subir Archivo TXT con los batch id</caption>
            <tbody>
                <tr>
                    <td>Archivo:</td>
                    <td><input type="file" name="archivo" class="texto" size="50"></td>
                </tr>
            </tbody>
        </table>
        <br><div align="center">
        <input type="button" value="Guardar" class="submit" onclick="upload();"></div>
    </form>
    <div align="center" class="aviso_naranja">Se insertarán solamente los registros que no existan</div>
        <br><div align="center">
            <table align="center" width="500" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
                <tr><td align="center" width="60" rowspan="10" valign="middle">
                    <img src="../imagenes/exclamation.gif">
                </td><td align="left" class="gris" width="440"><b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para BATCH ID</b></td></tr>
                </td><td align="left" class="gris" width="440">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Si contiene títulos o renglones vacíos, no se tomarán en cuenta para la carga)</td></tr>		
                <tr><td class="gris" align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    1.- La primera columna contiene el batch id</td>	
                </tr>	
                <tr><td class="gris" align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <a href="descargar.php?id=ejemplo_batch.txt"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
                     Descargue un archivo de ejemplo</td>	
                </tr>		
            </table>
    	</div>
<?php  } 

function upload_file($archivo,$archivo_name){ 
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
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor']; 	
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."$alias", "r");
	$insertar = 0;

	while (!feof($fd)){
		$buffer    = fgets($fd);
		$campos    = split ("\t", $buffer);
		if($campos['0']!='' && $campos['0']!='Batch ID') {
			$campos['0'] = str_replace("\"","",trim($campos['0']));
		
			/*campo 0*/
			$campos['0'] = str_replace(",","",$campos['0']);
			$campos['0'] = str_replace("\"","",$campos['0']);
			$campos['0'] = str_replace("'","",$campos['0']);
			if($campos['0']!='') {
				$field['batch'] = $campos['0']; //BATCH ID
			} else { 
				echo"<div align=center class=aviso_naranja>El Batch ID $campos[0] no debe estar vacío</div>";
				$insertar++; }	
		
			if($insertar<=0) {
					$query = "INSERT into tmp_batch_id values('', '$field[batch]')"; 
					mysql_query($query); $insertar=0;
			} else { 
				echo "<br><div class=aviso_naranja align=center>Error al insertar el registro $field[batch], ";
				echo "por favor verifique que el archivo tenga el formato necesario<br>";
				echo "<br><br>No se puede continuar con la carga !!</div><br>"; 
				fclose ($fd); 
				unlink($r_server.$alias);
				exit; 
			}						
		}	
	} 
	fclose ($fd); 
	unlink($r_server.$alias);
	listado_temporal(); 
} 

function listado_temporal() { ?>
    <div align="center" class="aviso">Por favor, verifique si la información para la carga es correcta</div>
    <form action="?op=upload_form" method="post" name="form1">
    <table align="center" class="tabla">
    <caption>¿Es correcta la información?<br>(Solamente se muestran los primeros 100 registros)</caption>
    <thead>
    
        <tr bgcolor="#E6E6E6" height="20">
            <td width="350" align="center">Batch ID</td>
        </tr>
    </thead>
    <?php 
       $s_1 = "select * from tmp_batch_id order by id limit 0,100";
       $r_1 = mysql_query($s_1); ?>
    <tbody>
    <?php while($d_1=mysql_fetch_array($r_1)) { ?>
    <tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['batch_id'];?></td>
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

function guardar_temp() {
	$i=$j=0;

	$s_2 = "select * from tmp_batch_id group by batch_id order by id";
	$r_2 = mysql_query($s_2);
	while($d_2=mysql_fetch_array($r_2)) { 
		$s_ = "select * from batch_id where batch_id='$d_2[batch_id]' ";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) { 
			//SI SON NUEVOS LOS INSERTO
			$s_1 = "insert into batch_id values('','$d_2[batch_id]','1')"; $i++;
		} else { 	
			//SI NO SON NUEVOS LOS ACTUALIZO
			$s_1 = "update batch_id set batch_id='$d_2[batch_id]' where batch_id='$d_2[batch_id]'"; $j++; 
		}
		$r_1 = mysql_query($s_1);  
	} 
	
	echo"<script>alert('$i registros insertados, $j actualizados');</script>";		
}?>