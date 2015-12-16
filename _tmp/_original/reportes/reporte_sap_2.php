<?php include("../header.php"); 
      include("funciones.php");
	  if($_SESSION["TYPE"]!='autorizador') { include('../capturista/filtros.php');  }
	  if($_SESSION["TYPE"]=='autorizador') { include('../autorizador/filtros.php'); }  ?>
<script>
function exportar() {
	form1.action='excel_reportes.php?op=reporte_sap';
	form1.submit();	
	form1.action='?op=listado';
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_reportes'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_reportes','reporte_sap'); ?></td>
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
		<?php 
		   switch($op) {
			case "listado"		:	listado($fechai,$fechaf,$pagina); break; 
			default				:	listado($fechai,$fechaf,$pagina); break;						
		} ?>	
		<!-- -->
	<br><br><br></td>
    <td width="5" background="../imagenes/borde_der_tabla.png">&nbsp;</td>
  </tr>
</table>
<?php include("../footer.php");


function listado($fechai,$fechaf,$pagina) {
	if(!$fechai) $fechai = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	if(!$fechaf) $fechaf = date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y"))); ?>
    
<div style="margin-left:100px;" class="titulo">
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo" width="200">CAPTURAS DE SAP</td>
	<td align="left">&nbsp;
	<span title="header=[&nbsp;&nbspCapturas de SAP] body=[Este es el reporte de capturas de números SAP donde se muestra todo el historial de usuarios que han cargado números de SAP.<br><br>Puede imprimir este reporte exportándolo directamente a excel.<br>]"><img src="../imagenes/question.gif" width="20" height="20"></span>	
	</td>	
</tr>
</table></div><hr>  

<div align="center" class="aviso">Si desea filtrar la información, utilice los calendarios y listas desplegables.</div>
<form action="?op=listado" method="post" name="form1">
<table align="center" class="tabla">
	<tr height="20">
		<td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
		<td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
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
</tr>
</table><br>
<div align="center" class="texto"><br>
    <input type="button" value="Buscar" class="submit" onclick="submit();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" class="submit" onclick="exportar();">
</div><br>
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="90">Fecha</td>
    <td align="center" width="80">Hora</td>
	<td align="center" width="120">Usuario</td>
	<td align="center" width="250">Empleado</td>
	<td align="center" width="150">Folio</td>
    <td align="center" width="150">Partes</td>
</tr>
</thead>
<tbody>
<?php $s_f = "select *, count(*) as total from (";
	  $s_f.= "select empleados.usuario, empleados.nombre, empleados.apellidos, aut_bitacora.* from aut_bitacora, empleados where fecha>='$fechai' and fecha<='$fechaf' ";
      $s_f.= "and empleados.id = aut_bitacora.id_emp and comentario like 'CAPTURA DE SAP:%' order by id desc) as general group by no_folio order by fecha desc"; 
	  $mouse_over = "this.style.background='#FFDD99'";
	  $mouse_out  = "this.style.background='#F7F7F7'";
	  $r_1 = mysql_query($s_f); $i=0; 
	  $total = mysql_num_rows($r_1);
	  while($d_1=mysql_fetch_array($r_1)) {
	  echo "<tr onMouseOut=\"$mouse_out\" onMouseOver=\"$mouse_over\" bgcolor=\"#F7F7F7\" height=\"20\">"; ?>
		<td align="center"><?php echo $d_1['fecha'];?></td>
		<td align="center"><?php echo $d_1['hora'];?></td>
        <td align="left"><?php echo $d_1['usuario']; ?></td>    
 		<td align="left"><?php echo $d_1['apellidos']." ".$d_1['nombre']; ?></td>
	  	<td align="center"><?php echo $d_1['no_folio'];?></td>
        <td align="center"><?php echo $d_1['total'];?></td>
</tr>	
<?php $i++; } ?>
</tbody>
</table>
<br><br><br>
<?php } ?>