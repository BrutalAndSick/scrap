<?php    
   include("conexion_db.php");
   conectame_db('scrap_gdl'); ?>

<style>
.texto {
	font-family:Arial, Helvetica, sans-serif; 
	font-size:10px;
} </style>	

<table align="center" border="0" cellpadding="0" cellspacing="2" class="texto">
<tr bgcolor="#CCCCCC" height="20">
	<td align="center" width="50"><b>No.</b></td>
    <td align="center" width="100"><b>No.Folio</b></td>
    <td align="center"><b>Detalle de Partes</b></td>
    <td align="center"><b>Detalle de Autorizaciones</b></td> 
    <td align="center"><b>Detalle de Comentarios</b></td> 
</tr>
<?php 
$s_ = "select no_folio from scrap_partes where not exists (select * from scrap_folios where scrap_partes.no_folio = ";
$s_.= "scrap_folios.no_folio) group by no_folio order by no_folio";
$r_ = mysql_query($s_); $i=0;
while($d_=mysql_fetch_array($r_)) {
$folios[$i] = $d_['no_folio']; $i++; }


for($i=0;$i<count($folios);$i++) { 
    echo "<tr bgcolor='#EEEEEE' height='25'>";	
		$j= $i+1;
		echo "<td align='center'>$j</td>"; 
		echo "<td align='center'>$folios[$i]</td>";
	
	echo "<td valign='top'><table align='center' border='0' cellspacing='2' class='texto'>";
	echo "<tr bgcolor='#DDDDDD'>";	
		echo "<td width='150' align='center'><b>Parte</b></td>";
		echo "<td width='70' align='center'><b>Tipo</b></td>";
		echo "<td width='50' align='center'><b>Qty</b></td>";
		echo "<td width='80' align='center'><b>Costo</b></td>";
		echo "<td width='70' align='center'><b>Ubicación</b></td>";
	echo "</tr>";
	$s_1 = "select * from scrap_partes where no_folio='$folios[$i]'";
	$r_1 = mysql_query($s_1);
	while($d_1 = mysql_fetch_array($r_1)) {
	echo "<tr bgcolor='#F9F9F9'>";	
		echo "<td>$d_1[no_parte]</td>";
		echo "<td>$d_1[tipo]</td>";
		echo "<td>$d_1[cantidad]</td>";
		echo "<td>$d_1[costo]</td>";
		echo "<td>$d_1[ubicacion]</td>";
	echo "</tr>"; }
	echo "</table></td>";

	echo "<td valign='top'><table align='center' border='0' cellspacing='2' class='texto'>";		
	echo "<tr bgcolor='#DDDDDD'>";	
		echo "<td width='60' align='center'><b>Depto</b></td>";
		echo "<td width='250' align='center'><b>Empleado</b></td>";
	echo "</tr>";
	$s_2 = "select * from autorizaciones where no_folio='$folios[$i]'";
	$r_2 = mysql_query($s_2);			
	while($d_2=mysql_fetch_array($r_2)) { 
		echo "<tr bgcolor='#F9F9F9'>";
			echo "<td>$d_2[depto]</td>";
			echo "<td>$d_2[empleado]</td>";
		echo "</tr>"; }
	echo "</table></td>"; 
	
	echo "<td valign='top'><table align='center' border='0' cellspacing='2' class='texto'>";
	echo "<tr bgcolor='#DDDDDD'>";	
		echo "<td width='70' align='center'><b>Fecha</b></td>";
		echo "<td width='250' align='center'><b>Empleado</b></td>";
		echo "<td width='50' align='center'><b>Status</b></td>";
		echo "<td width='250' align='center'><b>Comentario</b></td>";
	echo "</tr>";
	$s_3 = "select * from aut_bitacora where no_folio='$folios[$i]' order by fecha desc, hora desc";
	$r_3 = mysql_query($s_3);	
	while($d_3=mysql_fetch_array($r_3)) { 
		if($d_3['status']=='2') { $color = '#FFCC66'; } else { $color = '#F9F9F9'; }		
		echo "<tr bgcolor='$color'>";
			echo "<td>$d_3[fecha]</td>";
			echo "<td>$d_3[empleado]</td>";
			echo "<td>$d_3[status]</td>";
			echo "<td>$d_3[comentario]</td>";
		echo "</tr>"; }	
	echo "</table></td>"; 		
    echo "</tr>";
	}
echo "</table>";
?>