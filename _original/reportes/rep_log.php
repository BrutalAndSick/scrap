<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','log_sistema'); ?></td>
    <td background="../imagenes/barra_gris.png" width="285" height="37"><?php general(); ?></td>
  </tr>
</table>

<script>
function depurar() {
	form1.action = '?op=depurar';
	form1.submit();
}
</script>

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
			case "depurar"		:	depurar_log(); listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$folio,$accion,$seccion,$usuario); break;			
			case "listado"		:	listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$folio,$accion,$seccion,$usuario); break;							
			default				:	depurar_log(); listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$folio,$accion,$seccion,$usuario); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function menu_interno() { ?>
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="250">REPORTE LOG SISTEMA</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspReporte Preliminar de Scrap] body=[Este es el reporte preliminar donde se muestran todos los movimientos hechos por algún usuario en concreto en el rango de fechas y horas seleccionado.</td></tr></table><br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>
<?php } 	


function listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$folio,$accion,$seccion,$usuario) { 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");
	if(!$horai) { $horai=date("H"); $mini="00"; }
	if(!$horaf) { $horaf=date("H"); $minf="30"; } ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Fecha Inicio</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fecha Fin</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Hora Inicio</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Hora Fin</td>
        <td align="center" width="100" bgcolor="#E6E6E6">No.Folio</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Acción</td>
        <td align="center" width="140" bgcolor="#E6E6E6">Sección</td>
        <td align="center" width="120" bgcolor="#E6E6E6">Usuario</td>
    </tr>
    <tr>
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
	<td align="center">
    <select name="horai" class="texto" style="width:40px;">
        <?php for($i=0;$i<=23;$i++) { ?>
        <option value="<?php echo $i;?>" <?php if($i==$horai){?> selected="selected"<?php } ?>><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
        <?php } ?>
     </select>&nbsp;:
     <select name="mini" class="texto" style="width:40px;">
		<option value="0" <?php if($mini=="0"){?> selected="selected"<?php } ?>>00</option>
        <option value="30" <?php if($mini=="30"){?> selected="selected"<?php } ?>>30</option>
     </select></td>  
 	<td align="center">
    <select name="horaf" class="texto" style="width:40px;">
        <?php for($i=1;$i<=23;$i++) { ?>
        <option value="<?php echo $i;?>" <?php if($i==$horaf){?> selected="selected"<?php } ?>><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
        <?php } ?>
     </select>&nbsp;:
     <select name="minf" class="texto" style="width:40px;">
		<option value="0" <?php if($minf=="0"){?> selected="selected"<?php } ?>>00</option>
        <option value="30" <?php if($minf=="30"){?> selected="selected"<?php } ?>>30</option>
     </select></td>  
     <td align="center"><input type="text" class="texto" size="15" name="folio" value="<?php echo $folio;?>"></td>
     <td align="center"><select name="accion" class="texto" style="width:100px;">
		<option value=""></option>
        <option value="editar" <?php if($accion=="editar"){?> selected="selected"<?php } ?>>Editar</option>
        <option value="error" <?php if($accion=="error"){?> selected="selected"<?php } ?>>Error</option>
        <option value="nuevo" <?php if($accion=="nuevo"){?> selected="selected"<?php } ?>>Nuevo</option>
     </select></td>
     <td align="center"><select name="seccion" class="texto" style="width:140px;">
		<option value=""></option>
        <option value="autorizaciones" <?php if($seccion=="autorizaciones"){?> selected="selected"<?php } ?>>Autorizaciones</option>
        <option value="aut_bitacora" <?php if($seccion=="aut_bitacora"){?> selected="selected"<?php } ?>>Bitácora</option>
        <option value="configuracion" <?php if($seccion=="configuracion"){?> selected="selected"<?php } ?>>Configuración</option>
        <option value="scrap_codigos" <?php if($seccion=="scrap_codigos"){?> selected="selected"<?php } ?>>Scrap Códigos</option>
        <option value="scrap_folios" <?php if($seccion=="scrap_folios"){?> selected="selected"<?php } ?>>Scrap Folios</option>
        <option value="scrap_partes" <?php if($seccion=="scrap_partes"){?> selected="selected"<?php } ?>>Scrap Partes</option>
        </select></td>     
     <td align="center"><input type="text" value="<?php echo $usuario;?>" class="texto" name="usuario" size="20"></td>         
</tr>
</table><br>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="center" valign="middle"><input type="submit" value="Buscar" class="submit"></td>
    <td width="150" align="center" valign="middle"><input type="button" value="Depurar LOG" class="submit" onclick="depurar();"></td>
</tr>
</table></form>

<?php $hora1 = date("H:i:s",mktime($horai,$mini,0,0,0,0));
	  $hora2 = date("H:i:s",mktime($horaf,$minf,0,0,0,0));
	  $s_ = "select empleados.nombre, empleados.usuario as user, empleados.apellidos, log_sistema.* from log_sistema, empleados where seccion like '$seccion%' and ";
	  $s_.= "accion like '$accion%' and empleados.usuario like '$usuario%' and empleados.id=log_sistema.usuario ";
	  if($folio!='') { $s_.= "and folio like '$folio' "; }
	  if($folio=='') { $s_.= "and fecha>='$fechai' and fecha<='$fechaf' and hora>='$hora1' and hora<='$hora2' "; }	  
	  $s_.= "order by fecha desc, hora desc, id desc";
	  $r_ = mysql_query($s_);
	  $n_ = mysql_num_rows($r_); ?>
      
<table align="center" class="tabla">
<caption><?php echo $n_;?> Movimientos</caption>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Fecha</td>
    <td align="center" width="80">Hora</td>
    <td align="center" width="80">Usuario</td>
    <td align="center" width="250">Empleado</td>
    <td align="center" width="80">Folio</td>
    <td align="center" width="100">Acción</td>
    <td align="center" width="150">Sección</td>
    <td align="center" width="300">Movimiento</td>
</tr>
</thead>
<tbody>
<?php $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  $r_ = mysql_query($s_);
      while($d_=mysql_fetch_array($r_)) { 
		echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">";
			echo "<td align='center' class='texto'>$d_[fecha]</td>";
			echo "<td align='center' class='texto'>$d_[hora]</td>";
			echo "<td align='left' class='texto'>$d_[user]</td>";
			echo "<td align='left' class='texto'>$d_[nombre] $d_[apellidos]</td>";
			echo "<td align='center' class='texto'>$d_[folio]</td>";
			echo "<td align='center' class='texto'>$d_[accion]</td>";
			echo "<td align='center' class='texto'>$d_[seccion]</td>"; ?>
            <td align="center"><textarea cols="80" rows="3" class="texto"><?php echo $d_['consulta'];?></textarea></td>
	<?php echo "</tr>"; } ?>
</tbody>
</table><br><br>
<?php } 


function depurar_log() {
	$meses = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$s_ = "delete from log_sistema where fecha<'$meses'";
	$r_ = mysql_query($s_);	
}


function get_empleado($id_){
	$s_1 = "select nombre, apellidos from empleados where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['nombre']." ".$d_1['apellidos']; }
?>