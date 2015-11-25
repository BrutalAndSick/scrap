<?php session_name("loginUsuario"); 
   session_start(); 
   if(!$_SESSION['TYPE']) {	header("Location: ../index.php");  }
   include("../conexion_db.php"); ?>
   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema SCRAP :.</title>
<link href="../css/style_main.css" rel="stylesheet" type="text/css" />
<link media="screen" rel="stylesheet" href="../colorbox/colorbox.css" />
<link rel="stylesheet" href="../pop_Calendar/calendar.css">
<script type="text/javascript" src="../select_checks/jquery-1.6.1.min.js"></script>
<script>
function add_scrap() {
	form1.action='?op=save_codigo';
	form1.submit();
}
function add_codigo_emp() {
	form1.action='?op=save_c_emp';
	form1.submit();
}
function add_codigo_proy() {
	form1.action='?op=save_c_proy';
	form1.submit();
}
function add_relacionado() {
	form1.action='?op=save_relacionado';
	form1.submit();
}
function add_batch() {
	form1.action='?op=save_batch_ids';
	form1.submit();
}
function del_all_ubicaciones(){
	if(confirm("¿Borrar todas las ubicaciones?")==true) {
		form1.action='?op=del_ubicaciones';
		form1.submit(); }
	else {
		return; }	
}
function save_ubicaciones() {
	if(form1.ubicacion.value=='') {
		alert('Ingrese el nombre de la ubicación');
		return; }
	form1.submit();	
}

function delegado() {
	form1.action = '?op=ausencia_b';
	form1.submit();
}

function aut_guardar() {
	form1.action = '?op=autorizador_g';
	form1.submit();
}

function aut_borrar() {
	form1.action = '?op=autorizador_b';
	form1.submit();
}

function exportar(){
	form1.action='excel.php?op=codigo_proy';
	form1.submit();	
	form1.action='?op=codigo_proy';
}

function guar_proy(){
	form1.action='?op=save_proyectos';
	form1.submit();	
}

function guar_lineas(){
	form1.action='?op=save_lineas';
	form1.submit();	
}

function select_all() { 
	if($('input[type="checkbox"]').attr('checked')){
		$('input[type="checkbox"]').removeAttr('checked');	
	} else {
		$('input[type="checkbox"]').attr('checked', 'checked');	
	}
}	
</script>
<?php switch($op) {
	 case "autorizador"			:	autorizador($id_,$depto); break;
	 case "autorizador_g"		:	autorizador_g($id_,$depto,$actual); autorizador($id_,''); break;
	 case "autorizador_b"		:	autorizador_b($id_); autorizador($id_,''); break;
	 
	 case "ausencia"			:	ausencia($id_); break;
	 case "ausencia_g"			:	ausencia_g($id_,$ausencia); ausencia($id_); break;
	 case "ausencia_b"			:	ausencia_b($id_); ausencia($id_); break;

	 case "codigo_scrap"		:	codigo_scrap($id_,$planta,$depto); break; 
	 case "borrar_codigo"		:	borrar_codigo($id_,$planta,$depto,$id_b); codigo_scrap($id_,$planta,$depto); break;
	 case "save_codigo"			:	save_codigo($id_,$planta,$depto,$proyecto); codigo_scrap($id_,$planta,$depto); break; 
	 
	 case "codigo_emp"			:	codigo_emp($id_,$id_emp); break; 
	 case "borrar_c_emp"		:	borrar_c_emp($id_,$id_emp,$id_b); codigo_emp($id_,''); break;
	 case "save_c_emp"			:	save_c_emp($id_,$id_emp); codigo_emp($id_,''); break;

	 case "codigo_proy"			:	codigo_proy($id_,$id_division,$id_segmento,$id_pc,$id_proyecto); break; 
	 case "borrar_c_proy"		:	borrar_c_proy($id_,$id_b); codigo_proy($id_,'','','',''); break;
	 case "save_c_proy"			:	save_c_proy($id_,$id_division,$id_segmento,$id_pc,$id_proyecto); codigo_proy($id_,'','','',''); break;

	 case "relacionado_a"		:	relacionado_a($id_); break; 
	 case "save_relacionado"	:	save_relacionado($id_,$codigo); relacionado_a($id_); break;
	 case "borrar_relacionado"	:	borrar_relacionado($id_,$id_b); relacionado_a($id_); break;
	 
	 case "proyectos"			:	proyectos($id_,$division); break;
	 case "save_proyectos"		:	save_proyectos($id_,$division,$casilla); proyectos($id_,$division); break; 
	 case "borrar_proyecto"		:	borrar_proyecto($id_,$division,$id_b); proyectos($id_,$division); break;  

	 case "lineas"				:	lineas($id_,$division); break;
	 case "save_lineas"			:	save_lineas($id_,$division,$casilla); lineas($id_,$division); break; 
	 case "borrar_lineas"		:	borrar_lineas($id_,$division,$id_b); lineas($id_,$division); break;  

	 case "def_proyecto"		:	def_proyecto($id_); break;
	 case "save_def_proyecto"	:	save_def_proyecto($id_,$casilla); def_proyecto($id_); break; 
	 case "borrar_def_proyecto"	:	borrar_def_proyecto($id_,$id_b); def_proyecto($id_); break;  	 

	 case "defectos"			:	defectos($id_); break; 
	 case "save_defectos"		:	save_defectos($id_,$causa); defectos($id_); break;
	 case "borrar_defectos"		:	borrar_defectos($id_,$id_b); defectos($id_); break;

	 case "ubicaciones"			:	ubicaciones($id_); break; 
	 case "save_ubicaciones"	:	save_ubicaciones($id_,$ubicacion); ubicaciones($id_); break;
	 case "borrar_ubicaciones"	:	borrar_ubicaciones($id_,$id_b); ubicaciones($id_); break;
	 case "del_ubicaciones"		:	del_ubicaciones($id_); ubicaciones($id_); break;
	 
	 case "batch_ids"			:	batch_ids($parte,$batch); break;
	 case "save_batch_ids"		:	save_batch_ids($parte,$batch); batch_ids($parte,$batch); break;
	 case "borrar_batch_ids"	:	borrar_batch_ids($parte,$id_b); batch_ids($parte,$batch); break;
	 
	 case "ver_mail"			:	ver_mail($id_,$leido); break;
	 case "reenviar"			:	reenviar($id_,$leido); ver_mail($id_,$leido); break;
}


function autorizador($id_,$depto) { 
	$s_1 = "select * from empleados where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	if(!$depto) $depto = $d_1['autorizador']; ?>	

<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">ASIGNACIÓN COMO AUTORIZADOR</td>	
</tr>
</table><hr>
<form action="?op=autorizador" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="actual" value="<?php echo $d_1['autorizador'];?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>		
	<td width="110" align="left">Usuario:</td>
	<td><select name="usuario" style="width:300px;" class="texto" disabled="disabled">
	  <?php $s_ = "select * from empleados where activo='1' order by apellidos, usuario";
	     $r_ = mysql_query($s_);
		 while($d_=mysql_fetch_array($r_)) { ?>
	  <option value="<?php echo $d_['id'];?>" <?php if($d_1['id']==$d_['id']){?> selected="selected"<?php } ?>>
	  	<?php if(trim($d_['apellidos'])!='') { echo $d_['apellidos']." ".$d_['nombre']; } else { echo $d_['usuario']; } ?></option>
	  <?php } ?>
	</select></td>
</tr>
<tr>
	<td width="110" align="left">Departamento:</td>
	<td><select name="depto" class="texto" style="width:300px;" onchange="submit();">
			<option value="cero"></option>
			<option value="esp" <?php if($depto=='esp'){?> selected="selected"<?php } ?>>Especial</option>	
			<option value="ffc" <?php if($depto=='ffc'){?> selected="selected"<?php } ?>>FFC</option>
			<option value="ffm" <?php if($depto=='ffm'){?> selected="selected"<?php } ?>>FFM</option>
			<option value="inv" <?php if($depto=='inv'){?> selected="selected"<?php } ?>>Inventarios</option>
			<option value="lo" <?php if($depto=='lo'){?> selected="selected"<?php } ?>>LO</option>
			<option value="loa" <?php if($depto=='loa'){?> selected="selected"<?php } ?>>LO Almacén</option>
			<option value="lpl" <?php if($depto=='lpl'){?> selected="selected"<?php } ?>>LPL</option>			
			<option value="oes" <?php if($depto=='oes'){?> selected="selected"<?php } ?>>OES</option>
			<option value="prod" <?php if($depto=='prod'){?> selected="selected"<?php } ?>>Producción</option>
			<option value="sqm" <?php if($depto=='sqm'){?> selected="selected"<?php } ?>>SQM</option>	
            <option value="fin" <?php if($depto=='fin'){?> selected="selected"<?php } ?>>Finanzas</option>	 
		</select></td>
</tr>
</table>
</form>

<?php if($depto=='lpl' || $depto=='ffc' || $depto=='ffm') { ?>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="180" align="center">División</td>
		<td width="120" align="center">P.C.</td>	
		<td width="150" align="center">Proyecto</td>	
	</tr>
</thead>
	<?php $s_1 = "select * from autorizadores where id_emp='$id_' and tipo='$depto'";
      $r_1 = mysql_query($s_1); 
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo get_div_name($d_1['id_division']);?></td>
		<td align="left">&nbsp;<?php echo get_pc_name($d_1['id_pc']);?></td>
		<td align="left">&nbsp;<?php echo get_proy_name($d_1['id_proyecto']);?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>
<?php if($depto=='prod') { ?>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="180" align="center">División</td>
		<td width="120" align="center">Área</td>	
		<td width="150" align="center">Proyecto</td>	
	</tr>
</thead>
	<?php $s_1 = "select * from autorizadores where id_emp='$id_' and tipo='$depto'";
      $r_1 = mysql_query($s_1); 
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo get_div_name($d_1['id_division']);?></td>
		<td align="left">&nbsp;<?php echo get_area_name($d_1['id_area']);?></td>
		<td align="left">&nbsp;<?php echo get_proy_name($d_1['id_proyecto']);?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>
<?php if($depto=='inv') { ?>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="180" align="center">División</td>
	</tr>
</thead>
	<?php $s_1 = "select * from autorizadores where id_emp='$id_' and tipo='$depto'";
      $r_1 = mysql_query($s_1); 
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo get_div_name($d_1['id_division']);?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>
<br><div align="center">
	<input type="button" value="Borrar" class="submit" onclick="aut_borrar();" <?php echo $dis;?>>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Guardar" class="submit" onclick="aut_guardar();" <?php echo $dis;?>>
</div>
</form>
<?php }

function get_div_name($id_) {
	if($id_!='%') {
		$s_ = "select nombre from divisiones where id='$id_'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']; 
	} else { return "Todas"; }	
}

function get_area_name($id_) {
	if($id_!='%') {
		$s_ = "select nombre from areas where id='$id_'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']; 
	} else { return "Todas"; }	
}

function get_pc_name($id_) {
	if($id_!='%') {
		$s_ = "select nombre from profit_center where id='$id_'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']; 
	} else { return "Todos"; }	
}

function get_proy_name($id_) {
	if($id_!='%') {
		$s_ = "select nombre from proyectos where id='$id_'";
		$r_ = mysql_query($s_);
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']; 
	} else { return "Todos"; }	
}

function autorizador_g($id_,$depto,$actual) {
if($depto!=$actual) {
  $s_ = "delete from autorizadores where id_emp='$id_'";
  $r_ = mysql_query($s_); }
  $s_ = "update empleados set autorizador='$depto' where id='$id_'";
  $r_ = mysql_query($s_);
  if($depto=='oes') {
	$s_ = "delete from autorizadores where id_emp='$id_' and tipo='oes'";
	$r_ = mysql_query($s_);
	$s_ = "insert into autorizadores values('','0','0','%','0','oes','$id_')";
	$r_ = mysql_query($s_);
  }
}

function autorizador_b($id_) {
  $s_ = "delete from autorizadores where id_emp='$id_'";
  $r_ = mysql_query($s_);
  $s_ = "update empleados set autorizador='0' where id='$id_'";
  $r_ = mysql_query($s_);
}

function ausencia($id_) { 
	$s_1 = "select * from empleados where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">DELEGADO EN AUSENCIA</td>	
</tr>
</table><hr>
<?php $existe = es_delegado($id_); 

if($existe!='') { ?>
	<br><div align="center" class="aviso_naranja">El empleado es delegado en ausencia de:<br><br><?php echo $existe;?></div>
<?php } else { ?>
<form action="?op=ausencia_g" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td width="70" align="left">Delegado:</td>
	<td><?php if(es_delegado($id_)>0) { $dis = 'disabled'; } else { $dis = ''; }
		   $s_ = "select * from empleados where activo='1' and autorizador='$d_1[autorizador]' and id!='$d_1[id]' order by apellidos, ";
		   $s_.= "nombre"; $r_ = mysql_query($s_); ?>
		<select name="ausencia" class="texto" style="width:350px;" <?php echo $dis;?>>
		<option value="cero"></option>
		<?php while($d_=mysql_fetch_array($r_)) { ?>
		<option value="<?php echo $d_['id'];?>" <?php if($d_1['ausencia']==$d_['id']){?> selected="selected"<?php } ?>>
			<?php if($d_['nombre']!='') { echo $d_['apellidos']." ".$d_['nombre']; } else { echo $d_['usuario']; } ?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
</table>
<br><div align="center">
	<input type="button" value="Quitar" class="submit" onclick="delegado();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" value="Asignar" class="submit">	
</div>
</form><?php } ?>
<?php }

function es_delegado($id_emp){
	$s_ = "select * from empleados where ausencia='$id_emp'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) { 
		$d_ = mysql_fetch_array($r_);
		return $d_['nombre']." ".$d_['apellidos'];
	} else { return ""; }	
}

function ver_si_tiene_registros($id_empleado) { 
	$s_1   = "select * from autorizaciones where id_emp='$id_empleado' and status!='1'";
	$r_1   = mysql_query($s_1);
	return mysql_num_rows($r_1);
}

function ausencia_g($id_,$ausencia) {
	if($ausencia!='') { 
		$s_1 = "update empleados set ausencia='$ausencia' where id='$id_'";
		$r_1 = mysql_query($s_1); 
	}	
}

function ausencia_b($id_) {
	$s_1 = "update empleados set ausencia='0' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}

function ver_mail($id_,$leido) {
$fecha = date("Y-m-d");
$hora  = date("H:i:s");
 
	if($leido=='1') {
		$s_ = "update mails set leido_fecha='$fecha', leido_hora='$hora' where id='$id_'";
		$r_ = mysql_query($s_); } ?>
<form method="post" action="?op=reenviar">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<input type="hidden" name="leido" value="<?php echo $leido;?>">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">LEER E-MAIL</td>	
</tr>
</table><hr><br>
<?php $s_ = "select * from mails where id='$id_'";
   $r_ = mysql_query($s_);
   $d_ = mysql_fetch_array($r_); ?>
  
<table align="center" class="tabla" width="550" cellpadding="3">
<tr bgcolor="#F2F2F2">
	<td align="left" width="40"><b>Para:</b></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_['para_name'];?></td>
</tr>
<tr bgcolor="#F2F2F2">
	<td align="left"><b>Mail:</b></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_['para_mail'];?></td>
</tr>
</table><br>
<table align="center" border="0" class="tabla" width="550" cellpadding="3">
<tr bgcolor="#F2F2F2">
	<td align="left"><b>Asunto:</b>&nbsp;&nbsp;<?php echo $d_['asunto'];?>&nbsp;</td>
</tr>
<?php $mensaje = str_replace("Autorizar sus registros pendientes del sistema de Scrap"," ",$d_['mensaje']);
      $mensaje = str_replace("Verificar y aprobar boleta rechazada de Scrap"," ",$mensaje);
	  $mensaje = str_replace("Editar boleta rechazada de Scrap"," ",$mensaje); ?> 
<tr>
	<td align="left"><br><?php echo html_entity_decode($mensaje);?>&nbsp;</td>
</tr>
</table>
<br><div align="center"><input type="submit" class="submit" value="Reenviar Correo"></div>
</form>
<?php }

function reenviar($id_,$leido) {
	$s_ = "select * from mails where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
$fecha = date("Y-m-d");
$hora  = date("H:i:s");
	
$s_1="insert into mails values('','$d_[para_id]','$d_[para_name]','$d_[para_mail]','$d_[asunto]','$d_[mensaje]','$fecha','$hora','','')";
$r_1=mysql_query($s_1);	

$sheader = "From: Sistema SCRAP\n"; 
$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
$sheader = $sheader."Mime-Version: 1.0\n";    
$sheader = $sheader."Content-Type: text/html"; 

if(mail($d_['para_mail'],$d_['asunto'],html_entity_decode($d_['mensaje']),$sheader)) {
   echo "<script>alert('Correo enviado a ".utf8_encode($d_['para_name'])."');</script>"; }
else {
  echo "<script>alert('Error al enviar a ".utf8_encode($d_['para_name'])."');</script>"; }
}

function batch_ids($parte,$batch) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">BATCH ID'S</td>	
</tr>
</table><hr>
<form action="?op=batch_ids" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="70">No. Parte:</td>
	<td><?php $s_1 = "select * from numeros_parte where tabla='fert' and activo='1'";
	       $r_1 = mysql_query($s_1); ?>
		<select name="parte" style="width:200px;" class="texto" onchange="submit();">   
			<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['nombre'];?>" <?php if($parte==$d_1['nombre']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left">Batch ID:</td>
	<td><?php $s_1 = "select * from batch_id where activo='1'";
	       $r_1 = mysql_query($s_1); ?>
		<select name="batch" style="width:200px;" class="texto" onchange="submit();">   
			<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($batch==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['batch_id'];?></option>
		<?php } ?>
		</select>
    	<!--<input type="text" class="texto" name="batch" size="34" />--></td>
</tr>
</table>
<?php if($_SESSION["TYPE"]=='administrador') { ?>
<br><div align="center"><input type="button" value="Agregar" onclick="add_batch();" class="submit"></div><?php } ?>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="110" align="center">Batch ID</td>	
		<td width="40" align="center">Borrar</td>
		<td width="110" align="center">Batch ID</td>	
		<td width="40" align="center">Borrar</td>
		<td width="110" align="center">Batch ID</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select * from batch_ids where parte='$parte' order by batch_id";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo get_batch($d_1['batch_id']);?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_batch_ids&parte=<?php echo $parte;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>
		<?php $d_1=mysql_fetch_array($r_1); ?>
		<td align="center"><?php echo get_batch($d_1['batch_id']);?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_batch_ids&parte=<?php echo $parte;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>	
		<?php $d_1=mysql_fetch_array($r_1); ?>
		<td align="center"><?php echo get_batch($d_1['batch_id']);?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_batch_ids&parte=<?php echo $parte;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>				
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_batch_ids($parte,$batch) {
	if($parte!='' && $batch!='') {
		$s_ = "select * from batch_ids where parte='$parte' and batch_id='$batch'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) {	
			$s_ = "insert into batch_ids values('','$parte','$batch','1')";
			$r_ = mysql_query($s_); }
		else {
			echo"<script>alert('Ese registro ya existe');</script>"; }
	}			
}

function borrar_batch_ids($parte,$id_b) {
	$s_ = "delete from batch_ids where id='$id_b'";
	$r_ = mysql_query($s_);
}

function get_batch($id){
	$s_ = "select batch_id from batch_id where id='$id'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['batch_id'];	
}

function ubicaciones($id_) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">PROYECTOS Y UBICACIONES</td>	
</tr>
</table><hr>
<form action="?op=save_ubicaciones" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="70">Proyecto:</td>
	<td><?php $s_1 = "select * from proyectos where id='$id_'";
	       $r_1 = mysql_query($s_1); ?>
		<select style="width:200px;" class="texto" disabled="disabled">   
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left">Ubicación:</td>
	<td><input name="ubicacion" type="text" class="texto" size="34"></td>
</tr>
</table>
<br><div align="center">
	<input type="button" value="Agregar" class="submit" onclick="save_ubicaciones();">&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Borrar Todos" class="submit" onclick="del_all_ubicaciones();">
</div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">Ubicación</td>	
		<td width="40" align="center">Borrar</td>
		<td width="100" align="center">Ubicación</td>	
		<td width="40" align="center">Borrar</td>
		<td width="100" align="center">Ubicación</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select * from proy_ubicacion where id_proyecto='$id_' order by ubicacion";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="center"><?php echo $d_1['ubicacion'];?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_ubicaciones&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>
		<?php $d_1=mysql_fetch_array($r_1); ?>
		<td align="center"><?php echo $d_1['ubicacion'];?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_ubicaciones&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>	
		<?php $d_1=mysql_fetch_array($r_1); ?>
		<td align="center"><?php echo $d_1['ubicacion'];?></td>
		<td align="center"><?php if($d_1['id']!=''){ ?><a href="?op=borrar_ubicaciones&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } ?>
		</td>				
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_ubicaciones($id_,$ubicacion) {
	if($id_!='' && $ubicacion!='') {
		$s_ = "select * from proy_ubicacion where id_proyecto='$id_' and ubicacion='$ubicacion'";
		$r_ = mysql_query($s_);
		if(mysql_num_rows($r_)<=0) {
			$s_ = "insert into proy_ubicacion values('','$id_','$ubicacion')";
			$r_ = mysql_query($s_); }
		else { 
			echo"<script>alert('Registro Duplicado');</script>"; }	
	}		
}

function ya_existe_ubicacion($id_,$ubicacion) {
	$s_ = "select * from proy_ubicacion where id_proyecto='$id_' and id_ubicacion='$ubicacion'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {	return "SI"; } else { return "NO"; }
}

function borrar_ubicaciones($id_,$id_b) {
	$s_ = "delete from proy_ubicacion where id='$id_b'";
	$r_ = mysql_query($s_);
}

function del_ubicaciones($id_) {
	$s_ = "delete from proy_ubicacion where id_proyecto='$id_'";
	$r_ = mysql_query($s_);
}

function defectos($id_) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">DEFECTOS RELACIONADOS A</td>	
</tr>
</table><hr>
<form action="?op=save_defectos" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="90">Defecto:</td>
	<td><?php $s_1 = "select * from defectos where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select style="width:200px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<?php $s_1 = "select * from causas where activo='1' order by nombre";
	   $r_1 = mysql_query($s_1); ?>
	<td align="left">Relacionado a:</td>
	<td>
	  <select name="causa" style="width:200px;" class="texto">
	  <option value=""></option>
	  <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	    <?php if(ya_existe_relacionado($id_,$d_1['id'])=='NO') { ?><option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option><?php } ?>
      <?php } ?>	
	  </select>	
	</td>
</tr>
</table>
<br><div align="center"><input type="submit" value="Agregar" class="submit"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="250" align="center">Código Scrap</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select causas.nombre, defecto_causa.* from defecto_causa, causas where defecto_causa.id_causa = causas.id and ";
	   $s_1.= "defecto_causa.id_defecto = '$id_' order by nombre";
	   $r_1 = mysql_query($s_1);
	   $n_1 = mysql_num_rows($r_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['nombre'];?></td>
		<td align="center">
		<?php if($n_1>1){ ?>
		<a href="?op=borrar_defectos&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } else { ?><img src="../imagenes/delete_gris.gif" alt="No puede borrar. Debe existir al menos 1 causa asociada." border="0"><?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_defectos($id_,$causa) {
	if($id_!='' && $causa!='') {
		$s_ = "insert into defecto_causa values('','$id_','$causa')";
		$r_ = mysql_query($s_); }
}

function ya_existe_relacionado($id_,$causa) {
	$s_ = "select * from defecto_causa where id_causa='$causa' and id_defecto='$id_'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {	return "SI"; } else { return "NO"; }
}

function borrar_defectos($id_,$id_b) {
	$s_ = "delete from defecto_causa where id='$id_b'";
	$r_ = mysql_query($s_);
}

function relacionado_a($id_) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CÓDIGOS DE SCRAP RELACIONADOS</td>	
</tr>
</table><hr>
<form action="?op=relacionado_a" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="100">Relacionado a:</td>
	<td><?php $s_1 = "select * from causas where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_" style="width:200px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<?php $s_1 = "select * from codigo_scrap where activo='1' order by codigo";
	   $r_1 = mysql_query($s_1); ?>
	<td align="left">Código de Scrap:</td>
	<td>
	  <select name="codigo" style="width:200px;" class="texto">
	  <option value=""></option>
	  <?php while($d_1=mysql_fetch_array($r_1)) { ?>
	    <?php if(ya_existe_codigo($id_,$d_1['id'])=='NO') { ?><option value="<?php echo $d_1['id'];?>"><?php echo $d_1['codigo'];?></option><?php } ?>
      <?php } ?>	
	  </select>	
	</td>
</tr>
</table>
<br><div align="center"><input type="button" value="Agregar" class="submit" onclick="add_relacionado();"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="250" align="center">Código Scrap</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select codigo_scrap.codigo, causa_codigo.* from codigo_scrap, causa_codigo where codigo_scrap.id = causa_codigo.id_codigo";
	   $s_1.= " and causa_codigo.id_causa = '$id_' order by codigo";
	   $r_1 = mysql_query($s_1);
	   $n_1 = mysql_num_rows($r_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['codigo'];?></td>
		<td align="center">
		<?php if($n_1>1){ ?>
		<a href="?op=borrar_relacionado&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a><?php } else { ?><img src="../imagenes/delete_gris.gif" alt="No puede borrar. Debe existir al menos 1 código asociado." border="0"><?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_relacionado($id_,$codigo) {
	if($id_!='' && $codigo!='') {
		$s_ = "insert into causa_codigo values('','$id_','$codigo')";
		$r_ = mysql_query($s_); }
}

function ya_existe_codigo($id_,$codigo) {
	$s_ = "select * from causa_codigo where id_causa='$id_' and id_codigo='$codigo'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {	return "SI"; } else { return "NO"; }
}

function borrar_relacionado($id_,$id_b) {
	$s_ = "delete from causa_codigo where id='$id_b'";
	$r_ = mysql_query($s_);
}

function proyectos($id_,$division) { 
	$s_ = "select * from estaciones where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
		$area = $d_['id_area']; ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">PROYECTOS RELACIONADOS</td>	
</tr>
</table><hr>
<form action="?op=proyectos" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="65">Área:</td>
	<td><?php $s_1 = "select * from areas where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="area" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($area==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">Tecnología:</td>
	<td><?php $s_1 = "select * from estaciones where id_area='$area' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="tecnologia" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">División:</td>
	<td><?php $s_1 = "select * from divisiones where activo='1' order by nombre";
	          $r_1 = mysql_query($s_1); ?>
		<select name="division" style="width:250px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) {?>
			<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php }?>><?php echo $d_1['nombre'];?></option>
		<?php }?>
		</select> 
	</td>
</tr>
<!--<tr>
	<td align="left" width="65">Proyectos:</td>
	<td><?php $s_1 = "select divisiones.nombre as division, proyectos.* from divisiones, proyectos where divisiones.activo='1' and proyectos.activo='1' and ";
	          $s_1.="divisiones.id = proyectos.id_division order by division, nombre";
	          $r_1 = mysql_query($s_1); ?>
		<select name="proyecto" style="width:250px;" class="texto">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) {
				$s_2 = "select * from est_proyecto where id_proyecto='$d_1[id]' and id_tecnologia='$id_'";
				$r_2 = mysql_query($s_2);
				if(mysql_num_rows($r_2)<=0) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['division']." - ".$d_1['nombre'];?></option>
		<?php } } ?>
		</select> 
	</td>
</tr>-->
<?php $s_1 = "select * from proyectos where activo='1' and id_division = '$division' order by nombre";
$r_1 = mysql_query($s_1);
while($d_1=mysql_fetch_array($r_1)) {
	$s_2 = "select * from est_proyecto where id_proyecto='$d_1[id]' and id_tecnologia='$id_'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { 
		$arreglo[$d_1['id']]= $d_1['nombre'];
	}
}?>
</table>
<br>
<?php if(count($arreglo)>0){?>
	<div align="center"><input type="button" value="Seleccionar Todos" onclick="select_all();" class="submit"></div>
    <table align="center">
        <tr>
            <td valign="top" style="border-width: 1px; border-style:solid; border-color: #cccccc;">
            <table>
            <?php $i=1; 
            foreach($arreglo as $key => $nombre){?>
                <?php if($i==1){?><tr><?php }?>
                <td align="center"><input type="checkbox" name="casilla[<?php echo $key;?>]" id="casilla[<?php echo $key;?>]" value="<?php echo $key;?>"></td>
                <td align="left" class="texto"><?php echo $nombre;?></td>
                <?php if($i==5){?></tr><?php $i=0; }?>
            <?php $i++; }?>
            </table>
            </td>
        </tr>
    </table>
<?php }?>
<br><div align="center"><input type="button" value="Agregar" class="submit" onclick="guar_proy();"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">División</td>
        <td width="200" align="center">Proyecto</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select divisiones.nombre as division, proyectos.nombre, est_proyecto.* from proyectos, est_proyecto, divisiones ";
		  $s_1.= "where proyectos.id = est_proyecto.id_proyecto and est_proyecto.id_tecnologia = '$id_' and divisiones.id = ";
		  $s_1.= "proyectos.id_division order by division, nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['division'];?></td>
        <td align="left">&nbsp;<?php echo $d_1['nombre'];?></td>
		<td align="center">
		<a href="?op=borrar_proyecto&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_proyectos($id_,$division,$casilla) {
	if(count($casilla)>0){
		foreach($casilla as $id_proyecto){
			$s_ = "insert into est_proyecto values('','$id_','$id_proyecto')";
			$r_ = mysql_query($s_);
			agregar_proy_def($id_,$id_proyecto);
		}
	} else {
		echo"<script>alert('Seleccione al menos 1 proyecto');</script>";
		proyectos($id_,$division);
		exit;	
	}
}

function borrar_proyecto($id_,$division,$id_b) {
	del_proy_def($id_b);
	$s_ = "delete from est_proyecto where id='$id_b'";
	$r_ = mysql_query($s_);
}

function lineas($id_,$division) { 
	$s_ = "select * from lineas where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
		$area 		= $d_['id_area'];
		$tecnologia = $d_['id_estacion']; ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">PROYECTOS RELACIONADOS</td>	
</tr>
</table><hr>
<form action="?op=lineas" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="65">Área:</td>
	<td><?php $s_1 = "select * from areas where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="area" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($area==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">Tecnología:</td>
	<td><?php $s_1 = "select * from estaciones where id_area='$area' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="tecnologia" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($tecnologia==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">Línea:</td>
	<td><?php $s_1 = "select * from lineas where id_area='$area' and id_estacion='$tecnologia' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="linea" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">División:</td>
	<td><?php $s_1 = "select * from divisiones where activo='1' order by nombre";
	          $r_1 = mysql_query($s_1); ?>
		<select name="division" style="width:250px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) {?>
			<option value="<?php echo $d_1['id'];?>" <?php if($division==$d_1['id']){?> selected="selected" <?php }?>><?php echo $d_1['nombre'];?></option>
		<?php }?>
		</select> 
	</td>
</tr>
<!--<tr>
	<td align="left" width="65">Proyectos:</td>
	<td><?php $s_1 = "select divisiones.nombre as division, proyectos.*, segmentos.nombre as segmento from proyectos, est_proyecto, divisiones, segmentos where ";
	          $s_1.= "proyectos.activo='1' and segmentos.activo='1' and segmentos.id = proyectos.id_segmento and ";
	          $s_1.= "est_proyecto.id_tecnologia = '$tecnologia' and est_proyecto.id_proyecto = proyectos.id and proyectos.id_division = divisiones.id ";
			  $s_1.= "and divisiones.activo='1' order by division, nombre"; 
	          $r_1 = mysql_query($s_1); ?>
		<select name="proyecto" style="width:250px;" class="texto">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { 
				$s_2 = "select * from lineas_proy where id_proyecto='$d_1[id]' and id_linea='$id_'";
				$r_2 = mysql_query($s_2);
				if(mysql_num_rows($r_2)<=0) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['division']." (".$d_1['segmento'].") - ".$d_1['nombre'];?></option>
		<?php } } ?>
		</select> 
	</td>
</tr>-->
<?php  $s_1 = "select proyectos.* from proyectos, est_proyecto, segmentos where proyectos.activo='1' and segmentos.activo='1' and segmentos.id = proyectos.id_segmento ";
$s_1.= "and est_proyecto.id_tecnologia = '$tecnologia' and est_proyecto.id_proyecto = proyectos.id and proyectos.id_division = segmentos.id_division and ";
$s_1.= "proyectos.id_division='$division' order by nombre"; 
$r_1 = mysql_query($s_1); 
while($d_1=mysql_fetch_array($r_1)) {
	$s_2 = "select * from lineas_proy where id_proyecto='$d_1[id]' and id_linea='$id_'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { 
		$arreglo[$d_1['id']]= $d_1['nombre'];
	}
}?>
</table>
<br>
<?php if(count($arreglo)>0){?>
	<div align="center"><input type="button" value="Seleccionar Todos" onclick="select_all();" class="submit"></div>
    <table align="center">
        <tr>
            <td valign="top" style="border-width: 1px; border-style:solid; border-color: #cccccc;">
            <table>
            <?php $i=1; 
            foreach($arreglo as $key => $nombre){?>
                <?php if($i==1){?><tr><?php }?>
                <td align="center"><input type="checkbox" name="casilla[<?php echo $key;?>]" id="casilla[<?php echo $key;?>]" value="<?php echo $key;?>"></td>
                <td align="left" class="texto"><?php echo $nombre;?></td>
                <?php if($i==5){?></tr><?php $i=0; }?>
            <?php $i++; }?>
            </table>
            </td>
        </tr>
    </table>
<?php }?>
<br><div align="center"><input type="button" value="Agregar" class="submit" onclick="guar_lineas()"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">División</td>	
        <td width="100" align="center">Segmento</td>	
        <td width="150" align="center">Proyecto</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select divisiones.nombre as division, proyectos.nombre, lineas_proy.*, segmentos.nombre as segmento from proyectos, lineas_proy, divisiones, segmentos ";
		  $s_1.= "where proyectos.id = lineas_proy.id_proyecto and lineas_proy.id_linea = '$id_' and proyectos.id_division = divisiones.id and segmentos.id = ";
		  $s_1.= "proyectos.id_segmento order by division, nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['division'];?></td>
        <td align="left">&nbsp;<?php echo $d_1['segmento'];?></td>
        <td align="left">&nbsp;<?php echo $d_1['nombre'];?></td>
		<td align="center">
		<a href="?op=borrar_lineas&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_lineas($id_,$division,$casilla) {
	if(count($casilla)>0){
		foreach($casilla as $id_proyecto){
			$s_ = "insert into lineas_proy values('','$id_','$id_proyecto')";
			$r_ = mysql_query($s_);
		}
	} else {
		echo"<script>alert('Seleccione al menos 1 proyecto');</script>";
		lineas($id_,$division);
		exit;	
	}
}

function borrar_lineas($id_,$division,$id_b) {
	$s_ = "delete from lineas_proy where id='$id_b'";
	$r_ = mysql_query($s_);
}

function def_proyecto($id_) { 
	$s_ = "select * from defectos where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
		$area 		= $d_['id_area'];
		$tecnologia = $d_['id_estacion']; ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">PROYECTOS RELACIONADOS</td>	
</tr>
</table><hr>
<form action="?op=save_def_proyecto" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td align="left" width="65">Área:</td>
	<td><?php $s_1 = "select * from areas where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="area" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($area==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">Tecnología:</td>
	<td><?php $s_1 = "select * from estaciones where id_area='$area' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="tecnologia" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($tecnologia==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left" width="65">Defecto:</td>
	<td><?php $s_1 = "select * from defectos where id_area='$area' and id_estacion='$tecnologia' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="linea" style="width:250px;" class="texto" disabled="disabled">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){ ?> selected="selected" <?php } ?>>
				<?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<!--<tr>
	<td align="left" width="65">Proyectos:</td>
	<td><?php $s_1 = "select proyectos.* from proyectos, est_proyecto where proyectos.activo='1' and est_proyecto.id_tecnologia = '$tecnologia' and ";
	          $s_1.= "est_proyecto.id_proyecto = proyectos.id order by nombre";
	          $r_1 = mysql_query($s_1); ?>
		<select name="proyecto" style="width:250px;" class="texto">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) {
				$s_2 = "select * from def_proyecto where id_defecto='$id_' and id_proyecto='$d_1[id]'";
				$r_2 = mysql_query($s_2);
				if(mysql_num_rows($r_2)<=0) { ?>
			<option value="<?php echo $d_1['id'];?>"><?php echo $d_1['nombre'];?></option>
		<?php } } ?>
		</select> 
	</td>
</tr>-->
<?php $s_1 = "select proyectos.* from proyectos, est_proyecto where proyectos.activo='1' and est_proyecto.id_tecnologia = '$tecnologia' and ";
$s_1.= "est_proyecto.id_proyecto = proyectos.id order by nombre";
$r_1 = mysql_query($s_1);
while($d_1=mysql_fetch_array($r_1)) {
	$s_2 = "select * from def_proyecto where id_defecto='$id_' and id_proyecto='$d_1[id]'";
	$r_2 = mysql_query($s_2);
	if(mysql_num_rows($r_2)<=0) { $arreglo[$d_1['id']]= $d_1['nombre']; }
}
?>
</table>
<br>
<?php if(count($arreglo)>0){?>
	<div align="center"><input type="button" value="Seleccionar Todos" onclick="select_all();" class="submit"></div>
    <table align="center">
        <tr>
            <td valign="top" style="border-width: 1px; border-style:solid; border-color: #cccccc;">
            <table>
            <?php $i=1; 
            foreach($arreglo as $key => $nombre){?>
                <?php if($i==1){?><tr><?php }?>
                <td align="center"><input type="checkbox" name="casilla[<?php echo $key;?>]" id="casilla[<?php echo $key;?>]" value="<?php echo $key;?>"></td>
                <td align="left" class="texto"><?php echo $nombre;?></td>
                <?php if($i==5){?></tr><?php $i=0; }?>
            <?php $i++; }?>
            </table>
            </td>
        </tr>
    </table>
<?php }?>
<br><div align="center"><input type="submit" value="Agregar" class="submit"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="250" align="center">Proyecto</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select proyectos.nombre, def_proyecto.* from proyectos, def_proyecto where proyectos.id = def_proyecto.id_proyecto ";
	      $s_1.= "and def_proyecto.id_defecto = '$id_' order by nombre";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['nombre'];?></td>
		<td align="center">
		<a href="?op=borrar_def_proyecto&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_def_proyecto($id_,$casilla) {
	if(count($casilla)>0){
		foreach($casilla as $id_proyecto){
			$s_ = "insert into def_proyecto values('','$id_','$id_proyecto')";
			$r_ = mysql_query($s_);
		}
	} else {
		echo"<script>alert('Seleccione al menos 1 proyecto');</script>";
		def_proyecto($id_);
		exit;	
	}
}

function borrar_def_proyecto($id_,$id_b) {
	$s_ = "delete from def_proyecto where id='$id_b'";
	$r_ = mysql_query($s_);
}

function codigo_emp($id_,$id_emp) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">EMPLEADOS C&Oacute;DIGOS SCRAP</td>	
</tr>
</table><hr>
<form action="?op=codigo_emp" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td width="90" align="left">C&oacute;digo Scrap:</td>
	<td><?php $s_1 = "select * from codigo_scrap where activo='1' order by codigo";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_" style="width:300px;" class="texto">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['codigo'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td width="90" align="left">Empleado:</td>
	<td><?php $s_1 = "select empleados.* from empleados, capturistas where empleados.activo='1' and empleados.id = capturistas.id_emp and nombre!='' and apellidos!='' ";
	          $s_1.= "group by empleados.id order by apellidos";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_emp" style="width:300px;" class="texto">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($id_emp==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['apellidos']." ".$d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
</table>
<br><div align="center"><input type="button" value="Agregar" class="submit" onclick="add_codigo_emp();"></div>
</form>

<table align="center" class="tabla" width="95%">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">Código</td>	
		<td align="center">Empleado</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select empleados.nombre, empleados.apellidos, codigo_scrap_emp.* from empleados, codigo_scrap_emp where codigo_scrap_emp.id_emp = ";
		  $s_1.= "empleados.id and empleados.activo='1'";
	      $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['codigo'];?></td>
		<td align="left">&nbsp;<?php echo $d_1['apellidos']." ".$d_1['nombre'];?></td>
		<td align="center">
			<a href="?op=borrar_c_emp&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'>
        	<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>
		</td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_c_emp($id_,$id_emp) {
	$s_  = "select * from codigo_scrap where id='$id_'";
	$r_  = mysql_query($s_);
	$d_  = mysql_fetch_array($r_);
	
	$s_1 = "delete from codigo_scrap_emp where codigo='$d_[codigo]' and id_emp='$id_emp'";
	$r_1 = mysql_query($s_1);
	$s_1 = "insert into codigo_scrap_emp values('','$d_[codigo]','$id_emp')"; 
	$r_1 = mysql_query($s_1);
}

function borrar_c_emp($id_,$id_emp,$id_b) {
	$s_ = "delete from codigo_scrap_emp where id='$id_b'";
	$r_ = mysql_query($s_);
}

function codigo_proy($id_,$id_division,$id_segmento,$id_pc,$id_proyecto) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">PROYECTOS C&Oacute;DIGOS SCRAP</td>	
</tr>
</table><hr>
<form action="?op=codigo_proy" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td width="90" align="left">Divisi&oacute;n:</td>
	<td><?php $s_1 = "select * from divisiones where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_division" style="width:300px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_division==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td width="90" align="left">Segmento:</td>
	<td><?php $s_1 = "select * from segmentos where id_division='$id_division' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_segmento" style="width:300px;" class="texto" onchange="submit();">    
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($id_segmento==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td width="90" align="left">Profit Center:</td>
	<td><?php $s_1 = "select * from profit_center where id_division='$id_division' and id_segmento='$id_segmento' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_pc" style="width:300px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($id_pc==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td width="90" align="left">Proyecto:</td>
	<td><?php $s_1 = "select * from proyectos where id_division='$id_division' and id_segmento='$id_segmento' and id_pc='$id_pc' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_proyecto" style="width:300px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
		<option value="<?php echo $d_1['id'];?>" <?php if($id_proyecto==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
</table>
<br>
<div align="center">
	<input type="button" value="Agregar" class="submit" onclick="add_codigo_proy();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Exportar" class="submit" onclick="exportar();">
</div>
</form>

<table align="center" class="tabla" width="95%">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">División</td>	
        <td width="150" align="center">Segmento</td>	
        <td width="150" align="center">Profit Center</td>	
        <td width="150" align="center">Proyecto</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select divisiones.nombre as division, segmentos.nombre as segmento, proyectos.nombre as proyecto, profit_center.nombre as profit_center, ";
	      $s_1.= "codigo_scrap_proy.* from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap where codigo_scrap_proy.id_proy = ";
		  $s_1.= "proyectos.id and codigo_scrap.id = '$id_' and codigo_scrap.codigo = codigo_scrap_proy.codigo and divisiones.id = proyectos.id_division and ";
		  $s_1.= "segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc and divisiones.activo='1' and segmentos.activo='1' and ";
		  $s_1.= "profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1'"; 
	      $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $d_1['division'];?></td>
		<td align="left">&nbsp;<?php echo $d_1['segmento'];?></td>
        <td align="left">&nbsp;<?php echo $d_1['profit_center'];?></td>
        <td align="left">&nbsp;<?php echo $d_1['proyecto'];?></td>
		<td align="center">
			<a href="?op=borrar_c_proy&id_=<?php echo $id_;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'>
        	<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>
		</td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_c_proy($id_,$id_division,$id_segmento,$id_pc,$id_proyecto) {
	$s_  = "select * from codigo_scrap where id='$id_'";
	$r_  = mysql_query($s_);
	$d_  = mysql_fetch_array($r_);
	
	if($id_!='' && $id_proyecto!='') { 
	$s_1 = "delete from codigo_scrap_proy where codigo_scrap='$d_[codigo]' and id_proy='$id_proyecto'";
	$r_1 = mysql_query($s_1);
	$s_1 = "insert into codigo_scrap_proy values('','$d_[codigo]','$id_proyecto')"; 
	$r_1 = mysql_query($s_1); }
}

function borrar_c_proy($id_,$id_b) {
	$s_ = "delete from codigo_scrap_proy where id='$id_b'";
	$r_ = mysql_query($s_);
}

function codigo_scrap($id_,$planta,$depto) { ?>
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">VALIDACIONES C&Oacute;DIGO SCRAP</td>	
</tr>
</table><hr>
<form action="?op=codigo_scrap" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<tr>
	<td width="90" align="left">C&oacute;digo Scrap:</td>
	<td><?php $s_1 = "select * from codigo_scrap where activo='1' order by codigo";
	       $r_1 = mysql_query($s_1); ?>
		<select name="id_" style="width:135px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($id_==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['codigo'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td width="90" align="left">Planta:</td>
	<td><?php $s_1 = "select * from plantas where activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="planta" style="width:135px;" class="texto" onchange="submit();">   
		<option value=""></option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($planta==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr>
<tr>
	<td align="left">Departamento:</td>
	<td>
	  <select name="depto" style="width:135px;" class="texto" onchange="submit();">
	  <option value=""></option>
	    <?php if($planta!='') { ?>
		<?php if(ya_existe_depto($id_,$planta,"lo")=='NO') { ?>
        	<option value="lo" <?php if($depto=='lo'){ ?> selected="selected" <?php } ?>>LO</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"loa")=='NO') { ?>
        	<option value="loa" <?php if($depto=='loa'){ ?> selected="selected" <?php } ?>>LO-Almacén</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"lpl")=='NO') { ?>
        	<option value="lpl" <?php if($depto=='lpl'){ ?> selected="selected" <?php } ?>>LPL</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"ffm")=='NO') { ?>
        	<option value="ffm" <?php if($depto=='ffm'){ ?> selected="selected" <?php } ?>>FFM</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"ffc")=='NO') { ?>
        	<option value="ffc" <?php if($depto=='ffc'){ ?> selected="selected" <?php } ?>>FFC</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"prod")=='NO') { ?>
        	<option value="prod" <?php if($depto=='prod'){ ?> selected="selected" <?php } ?>>Producción</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"sqm")=='NO') { ?>
        	<option value="sqm" <?php if($depto=='sqm'){ ?> selected="selected" <?php } ?>>SQM</option><?php } ?>
	  	<?php if(ya_existe_depto($id_,$planta,"fin")=='NO') { ?>
        	<option value="fin" <?php if($depto=='fin'){ ?> selected="selected" <?php } ?>>Finanzas</option><?php } ?>
		<?php } ?>
	  </select>	
	</td>
</tr>
<? if($depto=='lpl') { ?>
<tr>
	<td width="90" align="left">Proyecto:</td>
	<td><?php $s_1 = "select * from proyectos where id_planta='$planta' and activo='1' order by nombre";
	       $r_1 = mysql_query($s_1); ?>
		<select name="proyecto" style="width:135px;" class="texto">   
		<option value="0">Todos</option>
		<?php while($d_1=mysql_fetch_array($r_1)) { ?>
			<option value="<?php echo $d_1['id'];?>" <?php if($proyecto==$d_1['id']){?> selected="selected"<?php } ?>><?php echo $d_1['nombre'];?></option>
		<?php } ?>
		</select> 
	</td>
</tr><? } ?>
</table>
<br><div align="center"><input type="button" value="Agregar" class="submit" onclick="add_scrap();"></div>
</form>

<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">Departamento</td>	
		<td width="100" align="center">Planta</td>	
        <td width="170" align="center">Proyecto</td>	
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
	<?php $s_1 = "select codigo_scrap_depto.*, plantas.nombre as planta from codigo_scrap_depto, codigo_scrap, plantas where ";
	   $s_1.= "codigo_scrap.id='$id_' and codigo_scrap.codigo = codigo_scrap_depto.codigo_scrap and codigo_scrap_depto.id_planta = ";
	   $s_1.= "plantas.id and codigo_scrap_depto.id_planta like '$planta%' order by tipo";
	   $r_1 = mysql_query($s_1);
	   while($d_1=mysql_fetch_array($r_1)) { 
	   switch($d_1['tipo']) {
			case "lo" 	:	$depto = "LO"; break; 
			case "loa" 	:	$depto = "LO-Almac&eacute;n"; break; 
			case "lpl" 	:	$depto = "LPL"; break; 
			case "ffm" 	:	$depto = "FFM"; break; 
			case "ffc" 	:	$depto = "FFC"; break; 
			case "prod" :	$depto = "Producci&oacute;n"; break; 
			case "sqm" 	:	$depto = "SQM"; break; 
			case "fin" 	:	$depto = "Finanzas"; break; } ?>
	<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
		<td align="left">&nbsp;<?php echo $depto;?></td>
		<td align="left">&nbsp;<?php echo $d_1['planta'];?></td>
		<td align="left">&nbsp;<?php if($d_1['id_proyecto']=='0') { echo "Todos"; } else { echo get_nom_proyecto($d_1['id_proyecto']); } ?></td>
		<td align="center">
		<a href="?op=borrar_codigo&id_=<?php echo $id_;?>&planta=<?php echo $planta;?>&depto=<?php echo $depto;?>&id_b=<?php echo $d_1['id'];?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a>
		</td>
	</tr>
	<?php } ?>
</table>		
<?php } 

function save_codigo($id_,$planta,$depto,$proyecto) {
$s_ = "select * from codigo_scrap where id='$id_'";
$r_ = mysql_query($s_);
$d_ = mysql_fetch_array($r_);

	if($depto!='' && $id_!='' && $planta!='') {
		if($depto!='lpl') { $s_1 = "insert into codigo_scrap_depto values('','$d_[codigo]','$planta','0','$depto','1')"; }
		if($depto=='lpl') { 
			$s_1 = "delete from codigo_scrap_depto where codigo_scrap='$d_[codigo]' and tipo='$depto' and id_planta='$planta' and id_proyecto='0'";
			$r_1 = mysql_query($s_1);
			$s_1 = "delete from codigo_scrap_depto where codigo_scrap='$d_[codigo]' and tipo='$depto' and id_planta='$planta' and id_proyecto='$proyecto'";
			$r_1 = mysql_query($s_1);
			$s_1 = "insert into codigo_scrap_depto values('','$d_[codigo]','$planta','$proyecto','$depto','1')"; }
		$r_1 = mysql_query($s_1); 
	}			
}

function get_nom_proyecto($id_) {
	$s_ = "select nombre from proyectos where id='$id_'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['nombre'];
}

function ya_existe_depto($id_,$planta,$tipo) {
if($tipo!='lpl') { 
	$s_ = "select codigo_scrap_depto.* from codigo_scrap_depto, codigo_scrap where codigo_scrap.id='$id_' and codigo_scrap.codigo = ";
	$s_.= "codigo_scrap_depto.codigo_scrap and codigo_scrap_depto.tipo='$tipo' and codigo_scrap_depto.id_planta = '$planta' and codigo_scrap_depto.id_proyecto='$proyecto'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0) {	return "SI"; } else { return "NO"; } }
else { return "NO"; }	
}

function borrar_codigo($id_,$planta,$depto,$id_b) {
	$s_ = "delete from codigo_scrap_depto where id='$id_b'";
	$r_ = mysql_query($s_);
}

function agregar_proy_def($id_tec,$id_proyecto){
	$s_ = "select id from defectos where activo!='2' and id_estacion='$id_tec'";
	$r_ = mysql_query($s_);
	while($d_ = mysql_fetch_array($r_)){
		$s_1 = "select * from def_proyecto where id_defecto='$d_[id]' and id_proyecto='$id_proyecto'";
		$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0){
			$s_1 = "insert into def_proyecto values ('','$d_[id]','$id_proyecto')";
			$r_1 = mysql_query($s_1);
		}
	}
}

function del_proy_def($id_){
	$s_ = "select * from est_proyecto where id='$id_'";
	$r_ = mysql_query($s_);	
	$d_ = mysql_fetch_array($r_);
	$id_tec = $d_['id_tecnologia'];
	$id_pro = $d_['id_proyecto'];
	$s_ = "select id from defectos where activo!='2' and id_estacion='$id_tec'";
	$r_ = mysql_query($s_);
	if(mysql_num_rows($r_)>0){
		while($d_ = mysql_fetch_array($r_)){
			$s_1 = "delete from def_proyecto where id_defecto='$d_[id]' and id_proyecto='$id_pro'";	
			$r_1 = mysql_query($s_1);
		}
	}
}?>