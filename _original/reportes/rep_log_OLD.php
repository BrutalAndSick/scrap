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
			case "borrar"		:	borrar_log(); listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$accion,$usuario); break;			
			case "listado"		:	listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$accion,$usuario); break;							
			default				:	listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$accion,$usuario); break;
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


function listado($fechai,$fechaf,$horai,$mini,$horaf,$minf,$accion,$usuario) { 
	if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d");
	if(!$horai) { $horai=date("H"); $mini="00"; }
	if(!$horaf) { $horaf=date("H"); $minf="59"; } ?>		
        		
<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Fecha Inicio</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fecha Fin</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Hora Inicio</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Hora Fin</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Acción</td>
        <td align="center" width="100" bgcolor="#E6E6E6">Usuario</td>
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
        <?php for($i=0;$i<=59;$i++) { ?>
		<option value="<?php echo $i;?>" <?php if($i==$mini){?> selected="selected"<?php } ?>><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
        <?php } ?>
     </select></td>  
 	<td align="center">
    <select name="horaf" class="texto" style="width:40px;">
        <?php for($i=1;$i<=23;$i++) { ?>
        <option value="<?php echo $i;?>" <?php if($i==$horaf){?> selected="selected"<?php } ?>><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
        <?php } ?>
     </select>&nbsp;:
     <select name="minf" class="texto" style="width:40px;">
        <?php for($i=0;$i<=59;$i++) { ?>
		<option value="<?php echo $i;?>" <?php if($i==$minf){?> selected="selected"<?php } ?>><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
        <?php } ?>
     </select></td>  
     <td align="center"><select name="accion" class="texto" style="width:100px;">
		<option value=""></option>
        <option value="editar" <?php if($accion=="editar"){?> selected="selected"<?php } ?>>Editar</option>
        <option value="nuevo" <?php if($accion=="nuevo"){?> selected="selected"<?php } ?>>Nuevo</option>
     </select></td>
     <td align="center"><input type="text" value="<?php echo $usuario;?>" class="texto" name="usuario" /></td>         
</tr>
</table><br>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="center" valign="middle"><input type="submit" value="Buscar" class="submit"></td>
</tr>
</table></form>

<?php $hora1 = date("H:i:s",mktime($horai,$mini,0,0,0,0));
	  $hora2 = date("H:i:s",mktime($horaf,$minf,0,0,0,0));
	  $s_ = "select empleados.nombre, empleados.usuario as user, empleados.apellidos, log_sistema.* from log_sistema, empleados where fecha>='$fechai' and ";
	  $s_.= "fecha<='$fechaf' and hora>='$hora1' and hora<='$hora2' and seccion='scrap' and accion like '$accion%' and empleados.usuario like '$usuario%' and ";
	  $s_.= "empleados.id=log_sistema.usuario order by fecha desc, hora desc";
	  $r_ = mysql_query($s_);
	  $n_ = mysql_num_rows($r_); ?>
      
<table align="center" class="tabla" width="990">
<caption><?php echo $n_;?> Movimientos</caption>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="80">Fecha</td>
    <td align="center" width="80">Hora</td>
    <td align="center" width="80">Acción</td>
    <td align="center" width="70">Usuario</td>
    <td align="center" width="250">Nombre</td>
    <td align="center" width="500">Movimiento</td>
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
			echo "<td align='center' class='texto'>$d_[accion]</td>";
			echo "<td align='left' class='texto'>$d_[user]</td>";
			echo "<td align='left' class='texto'>$d_[nombre] $d_[apellidos]</td>"; ?>
            <td align="center"><textarea cols="100" rows="3" class="texto"><?php echo $d_['consulta'];?></textarea></td>
	<?php echo "</tr>"; } ?>
</tbody>
</table><br><br>
<?php } 


function get_empleado($id_){
	$s_1 = "select nombre, apellidos from empleados where id='$id_'";
	$r_1 = mysql_query($s_1);
	$d_1 = mysql_fetch_array($r_1);
	return $d_1['nombre']." ".$d_1['apellidos']; }
?>