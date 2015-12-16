<?php include("../header.php"); 
   include("../mails.php"); ?>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu(''); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('',''); ?></td>
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
			default			:	llenar_banderas(); crear_vistas(); listado($orden,$fechai,$fechaf); break;
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
	<td class="titulo" width="250">E-MAILS RECIBIDOS</td>
</tr>
</table></div><hr>
<?php } 


function listado($orden,$fechai,$fechaf) {
	$s_  = "delete from scrap_partes_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_  = mysql_query($s_);
	$s_  = "delete from scrap_codigos_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_  = mysql_query($s_);
	//Borrar adjuntos cargados que al final no se guardaron
	$s_1 = "select archivo from scrap_folios_tmp where id_emp='$_SESSION[IDEMP]'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1); 
	$s_2 = "select * from configuracion where variable='ruta_evidencias'";
	$r_2 = mysql_query($s_2);
	$d_2 = mysql_fetch_array($r_2);
	if(file_exists($d_2['valor'].$d_1['archivo']) && $d_1['archivo']!='') { unlink($d_2['valor'].$d_1['archivo']); }
	$s_ = "delete from folios_temporal where id_emp='$_SESSION[IDEMP]'";
	$r_ = mysql_query($s_);

	if(!$orden)  $orden	= 'enviado_fecha';
	if(!$fechai) $fechai = date("Y-m-d",mktime(0,0,0,date("m"),1,date("y")));
	if(!$fechaf) $fechaf = date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("y"))); ?>
<div align="center" class="aviso">Los encabezados de la tabla permiten ordenar los campos</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
<tr>
	<td align="center" width="120">Buscar mensajes</td>
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
	<td><input type="submit" value="Buscar" class="submit"></td></tr>
</table>
</form>
<table align="center" class="tabla">
<caption>Resúmen de mensajes de <?php echo fecha_dmy($fechai);?> a <?php echo fecha_dmy($fechaf);?></caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">Leído</td>
		<td width="350" align="center"><a href="?op=listado&orden=asunto" class="linkTabla">Asunto</a></td>
		<td width="120" align="center"><a href="?op=listado&orden=enviado_fecha" class="linkTabla">Fecha Envío</a></td>
		<td width="120" align="center"><a href="?op=listado&orden=enviado_hora" class="linkTabla">Hora Envío</a></td>
		<td width="50" align="center">Leer</td>
	</tr>
</thead>
<?php $s_1 = "select * from mails where enviado_fecha>='$fechai' and enviado_fecha<='$fechaf' and para_id='$_SESSION[IDEMP]' order by ";
   $s_1.= "leido_fecha, $orden ASC";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { 
   $ruta = "&fechai=$fechai&fechaf=$fechaf&orden=$orden"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
		<?php if($d_1['leido_fecha']!='0000-00-00') { echo "<img src='../imagenes/tick.png' alt='Le&iacute;do' border='0'>"; } ?>
	</td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['asunto'];?></td>
	<td align="center"><?php echo fecha_dmy($d_1['enviado_fecha']);?></td>
	<td align="center"><?php echo $d_1['enviado_hora'];?></td>
	<td align="center"><a class="frame_ver_mail" href="../detalles.php?op=ver_mail&leido=1&id_=<?php echo $d_1['id'];?>">
	<img src="../imagenes/mail.png" border="0"></a></td>
</tr>
<?php } ?>
</tbody>
</table>
</form><br><br><br>
<?php } ?>