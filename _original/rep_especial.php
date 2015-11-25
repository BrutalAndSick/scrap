<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SCRAP TIJERA: Reporte Especial</title>
<link href="css/style_main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include("conexion_db.php"); 
	  if(!$mes)  $mes  = date("m");
	  if(!$anio) $anio = date("Y"); 
	  if(!$division) $division = "%"; ?>

<div align="center" class="texto">
<form method="post" action="rep_especial.php">
	Mes:
    <select name="mes" style="width:120px;" onchange="submit();" class="texto">
    	<option value="%" <?php if($mes=='%'){?> selected="selected" <?php } ?>>Todos</option>
        <option value="01" <?php if($mes=='01'){?> selected="selected" <?php } ?>>Ene</option>
        <option value="02" <?php if($mes=='02'){?> selected="selected" <?php } ?>>Feb</option>
        <option value="03" <?php if($mes=='03'){?> selected="selected" <?php } ?>>Mar</option>
        <option value="04" <?php if($mes=='04'){?> selected="selected" <?php } ?>>Abr</option>
        <option value="05" <?php if($mes=='05'){?> selected="selected" <?php } ?>>May</option>
        <option value="06" <?php if($mes=='06'){?> selected="selected" <?php } ?>>Jun</option>
        <option value="07" <?php if($mes=='07'){?> selected="selected" <?php } ?>>Jul</option>
        <option value="08" <?php if($mes=='08'){?> selected="selected" <?php } ?>>Ago</option>
        <option value="09" <?php if($mes=='09'){?> selected="selected" <?php } ?>>Sep</option>
        <option value="10" <?php if($mes=='10'){?> selected="selected" <?php } ?>>Oct</option>
        <option value="11" <?php if($mes=='11'){?> selected="selected" <?php } ?>>Nov</option>
        <option value="12" <?php if($mes=='12'){?> selected="selected" <?php } ?>>Dic</option>
   	</select>
	Año:
    <select name="anio" style="width:120px;" onchange="submit();" class="texto">
    	<?php for($i=2011;$i<=date("Y");$i++) { ?>
    	<option value="<?php echo $i;?>" <?php if($anio==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
		<?php } ?>
   	</select>    
	División:
    <?php $s_ = "select * from divisiones where activo='1' order by nombre";
	      $r_ = mysql_query($s_); ?>
    <select name="division" style="width:200px;" onchange="submit();" class="texto">
    	<option value="%" <?php if($division=='%'){?> selected="selected" <?php } ?>>Todas</option>
		<?php while($d_=mysql_fetch_array($r_)) { ?>
    	<option value="<?php echo $d_['id'];?>" <?php if($division==$d_['id']){?> selected="selected" <?php } ?>><?php echo $d_['nombre'];?></option>
		<?php } ?>
   	</select>   
    <input type="submit" value="Buscar" class="submit">
</form> 
</div> 

<?php $s_ = "select count(*) as total from ".$anio."_scrap_folios where fecha like '$anio-$mes-%'";
      $r_ = mysql_query($s_);
	  $d_ = mysql_fetch_array($r_);
      if($d_['total']>0) { 
	  	$s_1 = "select scrap_folios.no_folio, fecha, no_parte, cantidad, costo, total, division, area, estacion, linea, defecto, codigo_scrap, status, activo, proyecto ";
		$s_1.= "from ".$anio."_scrap_folios as scrap_folios, ".$anio."_scrap_partes as scrap_partes where scrap_folios.no_folio = scrap_partes.no_folio and fecha ";
		$s_1.= "like '$anio-$mes-%' and id_division like '$division' and activo='1' order by no_folio asc";  	
	  } else { 
	  	$s_1 = "select scrap_folios.no_folio, fecha, no_parte, cantidad, costo, total, division, area, estacion, linea, defecto, codigo_scrap, status, activo, proyecto ";
		$s_1.= "from scrap_folios, scrap_partes where scrap_folios.no_folio = scrap_partes.no_folio and fecha like '$anio-$mes-%' and id_division like '$division' and ";
		$s_1.= "activo='1' order by no_folio asc";	  	
	  } ?>
      
<table align="center" border="1" cellpadding="0" cellspacing="1" class="texto">
	<tr bgcolor="#CCCCCC">
    	<td align="center" width="120"><b>#</b></td>
        <td align="center" width="120"><b>Folio</b></td>
        <td align="center" width="120"><b>Fecha</b></td>
        <td align="center" width="120"><b>No.Parte</b></td>
        <td align="center" width="120"><b>Qty.</b></td>
        <td align="center" width="120"><b>Valor</b></td>
        <td align="center" width="120"><b>Total</b></td>
        <td align="center" width="120"><b>División</b></td>
        <td align="center" width="120"><b>Área</b></td>
        <td align="center" width="120"><b>Tecnología</b></td>
        <td align="center" width="120"><b>Línea</b></td>
        <td align="center" width="120"><b>Defecto</b></td>
        <td align="center" width="120"><b>Cod.SCRAP</b></td>
        <td align="center" width="120"><b>Proyecto</b></td>        
        <td align="center" width="120"><b>Status</b></td>
    </tr>
    <?php $r_1 = mysql_query($s_1); $i=1;
		  while($d_1=mysql_fetch_array($r_1)) { ?>
    <tr>  
    	<td align="center"><?php echo $i;?></td>
      	<td align="center"><?php echo $d_1['no_folio'];?></td>
		<td align="center"><?php echo $d_1['fecha'];?></td>
        <td align="left"><?php echo $d_1['no_parte'];?></td>
        <td align="right"><?php echo $d_1['cantidad'];?></td>
        <td align="right"><?php echo $d_1['costo'];?></td>
        <td align="right"><?php echo $d_1['total'];?></td>
        <td align="left"><?php echo $d_1['division'];?></td>
        <td align="left"><?php echo $d_1['area'];?></td>
        <td align="left"><?php echo $d_1['estacion'];?></td>
        <td align="left"><?php echo $d_1['linea'];?></td>
        <td align="left"><?php echo $d_1['defecto'];?></td>
        <td align="left"><?php echo $d_1['codigo_scrap'];?></td>
        <td align="left"><?php echo $d_1['proyecto'];?></td>
        <td align="left">
		<?php if($d_1['status']=='0') { echo "Proceso";   } 
			  if($d_1['status']=='1') { echo "Aprobada";  }
			  if($d_1['status']=='2') { echo "Rechazada"; } ?></td>
    </tr>  
    <?php $i++; } ?>
</table>
</body>
</html>
