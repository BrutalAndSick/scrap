<?php include("../header.php"); ?>
<script>
function re_send() {
	form1.action='?op=reenviar';
	form1.submit();	
}
function del_all() {
	form1.action='?op=del_all';
	form1.submit();	
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_mails'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_mails','mails'); ?></td>
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
			case "config"		:	config(); break;
			case "save_config"	:	save_config($hora_1,$hora_2); config(); break;
			case "borrar"		:	borrar($id_,$for_id,$fechai,$fechaf,$orden); listado($orden,$fechai,$fechaf,$for_id,$reenviar); 
									break;
			case "reenviar"		:	reenviar($orden,$fechai,$fechaf,$for_id,$mails); 
									listado($orden,$fechai,$fechaf,$for_id,$reenviar); break;
			case "del_all"		:	del_all($orden,$fechai,$fechaf,$for_id,$mails); 
									listado($orden,$fechai,$fechaf,$for_id,$reenviar); break;						
			default				:	listado($orden,$fechai,$fechaf,$for_id,$reenviar); break;
		} ?>			
		<!-- -->
	</td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="150">E-MAILS</td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=config" class="menuLink">Configurar</a></td>		
</tr>
</table></div><hr>
<?php } 


function listado($orden,$fechai,$fechaf,$for_id,$reenviar) {
	if(!$for_id)  $for_id  = '%'; 
	if(!$orden)   $orden	= 'enviado_fecha';
	if(!$fechai)  $fechai = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("y")));
	if(!$fechaf)  $fechaf = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("y")));
	$ruta = "&for_id=$for_id&fechai=$fechai&fechaf=$fechaf"; ?>
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos. Para reenviar correos, active la casillas de los que desea enviar.</div>
<form action="?op=listado" method="post" name="form1">
<input type="hidden" name="orden" value="<?php echo $orden;?>">
<table align="center" class="tabla">
<tr>
	<td align="center" width="120">Buscar mensajes</td>
	<td align="center">
	<?php $s_ = "select para_id, para_name from mails group by para_id order by para_name";
	   $r_ = mysql_query($s_); ?>
	   <select name="for_id" class="texto" style="width:250px;" onchange="submit();">
	   	<option value=""></option>
		<?php while($d_=mysql_fetch_array($r_)) { ?>
			<option value="<?php echo $d_['para_id'];?>" <?php if($for_id==$d_['para_id']){?> selected="selected"<?php } ?>>
				<?php echo $d_['para_name'];?></option>
		<?php } ?>
		</select> 
	</td>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechai',
		'valor': '<?php echo $fechai;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>
	<td align="center">
		<script language="JavaScript">
		var GC_SET_0 = {
		'appearance': GC_APPEARANCE,
		'dataArea': 'fechaf',
		'valor': '<?php echo $fechaf;?>'
		}
		new gCalendar(GC_SET_0);
		</script>
	</td>	
	<td><input type="submit" value="Buscar" class="submit"></td>
	<td><input type="button" value="Reenviar" class="submit" onclick="re_send();"></td>
	<td><input type="button" value="Borrar" class="submit" onclick="del_all();"></td></tr>
</table>

<br><table align="center" class="tabla">
<caption>Resúmen de mensajes de <?php echo fecha_dmy($fechai);?> a <?php echo fecha_dmy($fechaf);?></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">
		<input type="checkbox" name="reenviar" value="1" onclick="submit();" <?php if($reenviar=='1'){?>checked="checked"<?php } ?>></td>
		<td width="50" align="center">Leído</td>
		<td width="250" align="center">Para</td>
		<td width="350" align="center"><a href="?op=listado<?php echo $ruta;?>&orden=asunto" class="linkTabla">Asunto</a></td>
		<td width="100" align="center"><a href="?op=listado<?php echo $ruta;?>&orden=enviado_fecha" class="linkTabla">Fecha Envío</a></td>
		<td width="80" align="center"><a href="?op=listado<?php echo $ruta;?>&orden=enviado_hora" class="linkTabla">Hora Envío</a></td>
		<td width="50" align="center">Leer</td>
		<td width="50" align="center">Borrar</td>
	</tr>
</thead>
<?php $s_1 = "select * from mails where enviado_fecha>='$fechai' and enviado_fecha<='$fechaf' and para_id like '$for_id' order by ";
   	  $s_1.= "leido_fecha, $orden ASC";
      $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
		<input type="checkbox" name="mails[]" value="<?php echo $d_1['id'];?>" <?php if($reenviar=='1'){?>checked="checked"<?php } ?>></td>
	<td align="center">
		<?php if($d_1['leido_fecha']!='0000-00-00') { echo "<img src='../imagenes/tick.png' alt='Le&iacute;do' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['para_name'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['asunto'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['enviado_fecha']);?></td>
	<td align="center"><?php echo $d_1['enviado_hora'];?></td>
	<td align="center"><a class="frame_ver_mail" href="detalles.php?op=ver_mail&leido=0&id_=<?php echo $d_1['id'];?>">
	<img src="../imagenes/mail.png" border="0"></a></td>
	<td align="center"><a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'><img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php } ?>
</tbody>
</table>
</form><br><br><br>
<?php } 


function reenviar($orden,$fechai,$fechaf,$for_id,$mails) {

for($i=0;$i<count($mails);$i++) { 
	$s_ = "select * from mails where id='$mails[$i]'"; 
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
$fecha = date("Y-m-d");
$hora  = date("H:i:s");
	
$s_1 = "insert into mails values('','$d_[para_id]','$d_[para_name]','$d_[para_mail]','$d_[division]','$d_[asunto]','$d_[mensaje]',";
$s_1.= "'$fecha','$hora','','')";
$r_1 = mysql_query($s_1);	

$sheader = "From: Sistema SCRAP\n"; 
$sheader = $sheader."X-Mailer:PHP/".phpversion()."\n";  
$sheader = $sheader."Mime-Version: 1.0\n";    
$sheader = $sheader."Content-Type: text/html"; 

mail($d_['para_mail'],$d_['asunto'],html_entity_decode($d_['mensaje']),$sheader); }
}


function del_all($orden,$fechai,$fechaf,$for_id,$mails) {
	for($i=0;$i<count($mails);$i++) {
		$s_ = "delete from mails where id='$mails[$i]'";
		$r_ = mysql_query($s_);
	}
}


function borrar($id_,$for_id,$fechai,$fechaf,$orden) {
	$s_ = "delete from mails where id='$id_'";
	$r_ = mysql_query($s_);
}


function config() {
	if(!$orden) 	$orden	= 'nombre'; ?>
<div align="center" class="aviso">Seleccione la hora a la que se enviarán los recordatorios diarios</div>
<form action="?op=save_config" method="post" name="form1">
<br><table align="center" class="tabla">
<?php $s_1 = "select * from configuracion where variable='send_mails_hour'";
   $r_1 = mysql_query($s_1);
   $d_1 = mysql_fetch_array($r_1); 
   list($hora_1,$hora_2,$hora_3) = split("-",$d_1['valor']); ?>
<tr>
	<td width="130" align="center">Recordatorios diarios</td>
	<td><select name="hora_1" class="texto" style="width:40px;">
		<option value=""></option>
		<?php for($i=6;$i<=17;$i++) { ?>
		<option value="<?php echo str_pad($i,2,"0",STR_PAD_LEFT);?>" <?php if($hora_1==$i){?> selected="selected"<?php } ?>>
			<?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
		<?php } ?>
		</select></td>
	<td width="10" align="center">:</td>	
	<td><select name="hora_2" class="texto" style="width:40px;">
		<option value="00" <?php if($hora_2=="00"){?> selected="selected"<?php } ?>>00</option>
		<option value="15" <?php if($hora_2=="15"){?> selected="selected"<?php } ?>>15</option>
		<option value="30" <?php if($hora_2=="30"){?> selected="selected"<?php } ?>>30</option>
		<option value="45" <?php if($hora_2=="45"){?> selected="selected"<?php } ?>>45</option>
		</select></td>	
	<td align="center" width="130"><input type="button" class="submit" value="Guardar" onclick="validar();"></td>	
</tr>
</table>
</form>
<?php }


function save_config($hora_1,$hora_2) {
	$horario = $hora_1.":".$hora_2.":00";
	$s_ = "update configuracion set valor='$horario' where variable='send_mails_hour'";
	$r_ = mysql_query($s_);
}

?>