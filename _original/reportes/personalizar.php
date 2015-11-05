<?php session_name("loginUsuario"); 
   session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../css/style_main.css" rel="stylesheet" type="text/css">

<?php include('../conexion_db.php');

switch($op) {
	case "personalizar"		:	personalizar($reporte); break;
	case "guardar"			:	guardar($tipo,$posicion); personalizar($reporte); break;
}


function personalizar($reporte) { ?>
    
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CAMPOS PARA EXPORTAR A EXCEL</td>
</tr>
</table></div><hr><br>
<form method="post" action="?op=guardar">
<input type="hidden" name="tipo" value="excel">
<input type="hidden" name="reporte" value="<?php echo $reporte;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>    	
</tr>	
</thead>
<tbody>
<?php $s_1 = "select reportes.id, encabezados.campo, encabezados.nombre, reportes.excel from encabezados, reportes where ";
      $s_1.= "reportes.id_emp='$_SESSION[IDEMP]' and reportes.campo = encabezados.campo and encabezados.ver_reportes!='0' and reportes.reporte='$reporte' ";
	  $s_1.= "order by reportes.excel";
      $r_1 = mysql_query($s_1);
	  while($d_1=mysql_fetch_array($r_1)) { 
	  if($d_1['nombre']!='') { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['excel']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['excel']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td> 
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['excel']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['excel']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>   
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['excel']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['excel']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td><?php } ?>	 
</tr><?php } ?>	
</tbody>
</table>
<br><div align="center"><input type="submit" value="Guardar" class="submit" /></div>
</form>

   
<table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CAMPOS PARA REPORTE EN PANTALLA</td>
</tr>
</table></div><hr><br>
<form method="post" action="?op=guardar">
<input type="hidden" name="tipo" value="pantalla">
<input type="hidden" name="reporte" value="<?php echo $reporte;?>">
<table align="center" class="tabla">
<thead>
<tr bgcolor="#E6E6E6" height="20">
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>
	<td align="center" width="30">&nbsp;</td>
    <td align="center" width="100">Campo</td>    	
</tr>	
</thead>
<tbody>
<?php $s_1 = "select reportes.id, encabezados.campo, encabezados.nombre, reportes.pantalla from encabezados, reportes where ";
      $s_1.= "reportes.id_emp='$_SESSION[IDEMP]' and reportes.campo = encabezados.campo and encabezados.ver_reportes!='0' and reportes.reporte='$reporte' ";
	  $s_1.= "order by reportes.pantalla";
      $r_1 = mysql_query($s_1);
	  while($d_1=mysql_fetch_array($r_1)) { 
	  if($d_1['nombre']!='') { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['pantalla']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['pantalla']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td> 
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['pantalla']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['pantalla']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>   
	<?php } $d_1=mysql_fetch_array($r_1); if($d_1['nombre']!='') { ?>
	<td align="center">
    	<select name="posicion[<?php echo $d_1['id'];?>]" style="width:50px;" class="texto">
        <option value="0" <?php if($d_1['pantalla']=='0'){?> selected="selected"<?php } ?>></option>
        <?php for($i=1;$i<=28;$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($d_1['pantalla']==$i){?> selected="selected"<?php } ?>><?php echo $i;?></option>
        <?php } ?>
        </select></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td><?php } ?>	 
</tr><?php } ?>	
</tbody>
</table>
<br><div align="center"><input type="submit" value="Guardar" class="submit" /></div>
</form>
<?php } 


function guardar($tipo,$posicion) {
	$s_ = "update reportes set ".$tipo."='0' where id_emp='$_SESSION[IDEMP]'"; 
	$r_ = mysql_query($s_);
	foreach($posicion as $id => $valor) {
		$s_ = "update reportes set ".$tipo."='$valor' where id_emp='$_SESSION[IDEMP]' and id='$id'";
		$r_ = mysql_query($s_); 
	}
} 
?>