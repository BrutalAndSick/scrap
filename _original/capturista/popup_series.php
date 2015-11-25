<?php session_name("loginUsuario"); 
   session_start(); 
   include("../conexion_db.php");?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../css/style_main.css" rel="stylesheet" type="text/css">
<body topmargin="0">
<script>
function exportar() {
	form1.action = 'excel_ayudas.php?op=proyectos';
	form1.submit();
	form1.action = 'popup_series.php?op=proyectos';
}

function exportar1() {
	form1.action = 'excel_ayudas.php?op=proyectos_095';
	form1.submit();
	form1.action = 'popup_series.php?op=proyectos_095';
}

function exportar2() {
	form1.action = 'excel_ayudas.php?op=batch_id';
	form1.submit();
	form1.action = 'popup_series.php?op=batch_id';
}
</script>

<?php switch($op) {
		case "sin_guardar"		:	sin_guardar();   break;
		case "guardados"		:	guardados($id_); break;
		case "proyectos"		:	proyectos($f_division,$f_segmento,$f_prce,$f_proyecto); break;
		case "proyectos_095"	:	proyectos_095($f_division,$f_segmento,$f_prce,$f_proyecto); break;
		case "batch_id"			:	batch_id($f_parte); break;
} 

function proyectos($f_division,$f_segmento,$f_prce,$f_proyecto) { 
	if(!$f_proyecto) 	$f_proyecto	= '%';
	if(!$f_division)	$f_division = '%';
	if(!$f_segmento)  	$f_segmento = '%'; 
	if(!$f_prce)  		$f_prce 	= '%'; ?>

<form action="?op=proyectos" method="post" name="form1">
<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CATÁLOGOS DE AYUDA: PROYECTOS</td>
</tr>
</table><hr>   
<div align="center" class="aviso">En esta sección podrá consultar todos los proyectos existentes y sus relaciones con división, segmento y profit center</div>
<br><div align="center">
	<input type="button" value="Buscar" onClick="submit();" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" onClick="exportar();" class="submit">
</div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">
	<select name="f_division" style="width:150px;" class="texto" onChange="submit();">
		<option value="">División</option>
		<?php $s_3 = "select divisiones.* from divisiones, plantas where divisiones.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id ";
              $s_3.= "order by divisiones.nombre";
	   		  $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="100" align="center">
	<select name="f_segmento" style="width:150px;" class="texto" onChange="submit();">
		<option value="">Segmento</option>
		<?php $s_3 = "select segmentos.* from segmentos, divisiones, plantas where segmentos.id_division='$f_division' and segmentos.activo='1' and plantas.activo='1' ";
		      $s_3.= "and divisiones.activo='1' and segmentos.id_planta = plantas.id and segmentos.id_division = divisiones.id order by segmentos.nombre";
		  	  $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_segmento){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>
		</td>
		<td width="100" align="center">
		<?php $s_3 = "select profit_center.* from profit_center, plantas, divisiones, segmentos where profit_center.activo='1' and plantas.activo='1' and ";
		      $s_3.= "divisiones.activo='1' and segmentos.activo='1' and profit_center.id_planta = plantas.id and profit_center.id_division = divisiones.id and ";
			  $s_3.= "profit_center.id_segmento = segmentos.id and profit_center.id_segmento='$f_segmento' and profit_center.id_division='$f_division' order by ";
			  $s_3.= "profit_center.nombre"; ?>
	<select name="f_prce" style="width:100px;" class="texto" onChange="submit();">
		<option value="">P.C.</option>
	   <?php $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_prce){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="200" align="center">
	<select name="f_proyecto" style="width:200px;" class="texto" onChange="submit();">
		<option value="">Proyecto</option>
		<?php $s_3 = "select distinct(proyectos.nombre) from proyectos, plantas, divisiones, profit_center, segmentos where proyectos.activo='1' and plantas.activo='1' ";
		      $s_3.= "and divisiones.activo='1' and profit_center.activo='1' and proyectos.id_planta = plantas.id and proyectos.id_division = divisiones.id and ";
			  $s_3.= "proyectos.id_pc = profit_center.id and proyectos.id_segmento = segmentos.id order by proyectos.nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['nombre'];?>" <?php if($d_3['nombre']==$f_proyecto){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
	</tr>
</thead>
<?php if($f_division!='%' || $f_segmento!='%' || $f_prce!='%' || $f_proyecto!='%') { 
      $s_1 = "select plantas.nombre as planta, divisiones.nombre as division, profit_center.nombre as profit_center, segmentos.nombre as segmento, proyectos.* from ";
	  $s_1.= "segmentos, plantas, divisiones, profit_center, proyectos where proyectos.nombre like '$f_proyecto' and proyectos.activo='1' and proyectos.id_segmento like ";
	  $s_1.= "'$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' and divisiones.id_planta = plantas.id and proyectos.id_pc = ";
	  $s_1.= "profit_center.id and proyectos.id_segmento = segmentos.id and proyectos.id_division = divisiones.id and divisiones.activo='1' and segmentos.activo='1' and ";
	  $s_1.= "profit_center.activo='1' and plantas.activo='1' and divisiones.id_planta = plantas.id order by activo DESC, division, segmento, profit_center, nombre ASC"; 
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['profit_center'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
</tr>
<?php } } ?>
</tbody>
</table><br><br><br>
</form>
<?php } 

function proyectos_095($f_division,$f_segmento,$f_prce,$f_proyecto) { 
	if(!$f_proyecto) 	$f_proyecto	= '%';
	if(!$f_division)	$f_division = '%';
	if(!$f_segmento)  	$f_segmento = '%'; 
	if(!$f_prce)  		$f_prce 	= '%'; ?>

<form action="?op=proyectos_095" method="post" name="form1">
<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CATÁLOGOS DE AYUDA: PROYECTOS</td>
</tr>
</table><hr>   
<div align="center" class="aviso">En esta sección podrá consultar todos los proyectos existentes y sus relaciones con división, segmento y profit center</div>
<br><div align="center">
	<input type="button" value="Buscar" onClick="submit();" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" onClick="exportar1();" class="submit">
</div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">
	<select name="f_division" style="width:150px;" class="texto" onChange="submit();">
		<option value="">División</option>
		<?php $s_3 = "select divisiones.id, divisiones.nombre as division from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap ";
			  $s_3.= "where codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo ";
			  $s_3.= "and divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc ";
			  $s_3.= "and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' ";
			  $s_3.= "group by divisiones.id order by divisiones.nombre ";
	   		  $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['division'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="100" align="center">
	<select name="f_segmento" style="width:150px;" class="texto" onChange="submit();">
		<option value="">Segmento</option>
		<?php $s_3 = "select segmentos.id, segmentos.nombre as segmento from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap ";
			  $s_3.= "where codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo ";
			  $s_3.= "and divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc ";
			  $s_3.= "and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' ";
			  $s_3.= "and segmentos.id_division='$f_division' group by segmentos.id order by segmentos.nombre ";
		  	  $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_segmento){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['segmento'];?></option>
	   <?php } ?>
	</select>
		</td>
		<td width="100" align="center">
		<?php $s_3 = "select profit_center.id, profit_center.nombre as profit_center from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, ";
			  $s_3.= "codigo_scrap where codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo ";
			  $s_3.= "and divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc and ";
			  $s_3.= "divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' ";
			  $s_3.= "and profit_center.id_segmento='$f_segmento' and profit_center.id_division='$f_division' group by profit_center.id order by profit_center.nombre "; ?>
	<select name="f_prce" style="width:100px;" class="texto" onChange="submit();">
		<option value="">P.C.</option>
	   <?php $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_prce){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['profit_center'];?></option>
	   <?php } ?>
	</select>		
		</td>
		<td width="200" align="center">
	<select name="f_proyecto" style="width:200px;" class="texto" onChange="submit();">
		<option value="">Proyecto</option>
		<?php $s_3 = "select distinct(proyectos.nombre) from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap ";
		      $s_3.= "where codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo ";
			  $s_3.= "and divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc ";
			  $s_3.= "and divisiones.activo='1' and segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' ";
			  $s_3.= "and proyectos.id_segmento like '$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' ";
			  $s_3.= "group by proyectos.id order by proyectos.nombre ";
	          $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['nombre'];?>" <?php if($d_3['nombre']==$f_proyecto){ ?> selected="selected"<?php } ?>>
	   	<?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>		
		</td>
	</tr>
</thead>
<?php if($f_division!='%' || $f_segmento!='%' || $f_prce!='%' || $f_proyecto!='%') { 
	  $s_1 = "select divisiones.nombre as division, segmentos.nombre as segmento, proyectos.nombre as proyecto, ";
	  $s_1.= "profit_center.nombre as profit_center, proyectos.* from divisiones, segmentos, profit_center, proyectos, codigo_scrap_proy, codigo_scrap where ";
	  $s_1.= "codigo_scrap_proy.id_proy = proyectos.id and codigo_scrap.id = '36' and codigo_scrap.codigo = codigo_scrap_proy.codigo and ";
	  $s_1.= "divisiones.id = proyectos.id_division and segmentos.id = proyectos.id_segmento and profit_center.id = proyectos.id_pc and divisiones.activo='1' and ";
	  $s_1.= "segmentos.activo='1' and profit_center.activo='1' and proyectos.activo='1' and codigo_scrap.activo='1' and proyectos.nombre like '$f_proyecto' ";
	  $s_1.= "and proyectos.id_segmento like '$f_segmento' and proyectos.id_pc like '$f_prce' and proyectos.id_division like '$f_division' order by activo DESC, division, ";
	  $s_1.= "segmento, profit_center, nombre ASC";
      $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td>&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['profit_center'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['nombre'];?></td>
</tr>
<?php } } ?>
</tbody>
</table><br><br><br>
</form>
<?php } 

function batch_id($f_parte) { 
	if(!$f_parte) 	$f_parte	= '%'; ?>

<form action="?op=batch_id" method="post" name="form1">
<br><table align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td class="titulo">CATÁLOGOS DE AYUDA: BATCH ID</td>
</tr>
</table><hr>   
<div align="center" class="aviso">En esta sección podrá consultar todos los batch id existentes y sus relaciones con los números de parte</div>
<br><div align="center">
	<input type="button" value="Buscar" onClick="submit();" class="submit">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Exportar" onClick="exportar2();" class="submit">
</div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="150" align="center">
            <select name="f_parte" style="width:150px;" class="texto" onChange="submit();">
                <option value="">No. Parte</option>
                <?php $s_3 = "select * from numeros_parte, batch_ids where activo='1' and batch_ids.parte = numeros_parte.nombre ";
					  $s_3.= "group by parte order by parte";
                      $r_3 = mysql_query($s_3);
               while($d_3=mysql_fetch_array($r_3)) { ?>
                    <option value="<?php echo $d_3['nombre'];?>" <?php if($d_3['nombre']==$f_parte){ ?> selected="selected"<?php } ?>>
                    <?php echo $d_3['nombre'];?></option>
               <?php } ?>
            </select>		
		</td>
        <td width="100" align="center">Batch ID</td>
	</tr>
</thead>
<?php if($f_parte!='%') { 
      $s_1 = "select * from batch_ids where parte like '$f_parte' order by batch_id"; 
   	  $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
    <tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
        <td align="left">&nbsp;&nbsp;<?php echo $d_1['parte'];?></td>
        <td align="center">&nbsp;&nbsp;<?php echo get_batch($d_1['batch_id']);?></td>
    </tr>
<?php } } ?>
</tbody>
</table><br><br><br>
</form>
<?php } 

function get_batch($id){
	$s_ = "select batch_id from batch_id where id='$id'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	return $d_['batch_id'];
}

function sin_guardar() { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo">NO. SERIE CAPTURADOS</td>
</tr>
</table></div><hr>
<script>
var cadena = window.opener.document.getElementById('serial_unidad').value;
var modelo = window.opener.document.getElementById('parte').value;
var series_array = cadena.split(',');
var counter=0; var j=1;
document.write("<table align='center' border='0' cellpadding='0' cellspacing='2'>");
   document.write("<tr height='25' bgcolor='#F2F2F2'>");
    document.write("<td align='left' class='texto' colspan='4' bgcolor='#DDDDDD'>&nbsp;&nbsp;<b>" + modelo + "</b></td>");
for (counter=0; counter<series_array.length; counter++) {
   document.write("<tr height='25' bgcolor='#F2F2F2'>");
   if(series_array[counter]) { 
   	document.write("<td align='center' class='texto' width='20' bgcolor='#DDDDDD'>" + j + "</td>");
  	document.write("<td align='left' class='texto' width='150'>&nbsp;&nbsp;" + series_array[counter] + "</td>"); }
   counter++; j++;
   if(series_array[counter]) { 
    document.write("<td align='center' class='texto' width='20' bgcolor='#DDDDDD'>" + j + "</td>");
    document.write("<td align='left' class='texto' width='150'>&nbsp;&nbsp;" + series_array[counter] + "</td>"); }
   j++;	
   document.write("</tr>");
}   
document.write("</table>");
</script>
<?php } 


function guardados($id_) { ?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="titulo">NO. SERIE CAPTURADOS</td>
</tr>
</table></div><hr>
<?php  $s_ = "select * from scrap_partes_tmp where id='$id_' order by no_parte";
	   $r_ = mysql_query($s_);
	while($d_=mysql_fetch_array($r_)) { $j=1; ?>
	<table align="center" border="0" cellpadding="0" cellspacing="2">
    	<?php $series = split(",",$d_['serial_unidad']);
		echo "<tr height='25' bgcolor='#F2F2F2'>";
		echo "<td align='left' class='texto' colspan='4' bgcolor='#DDDDDD'>&nbsp;&nbsp;<b>$d_[no_parte]</b></td></tr>";
		for($i=0;$i<count($series);$i++) { 
			echo "<tr height='25' bgcolor='#F2F2F2'>";			
			if($series[$i]!='') { 
				echo "<td align='center' class='texto' width='20' bgcolor='#DDDDDD'>$j</td>";
				echo "<td align='left' class='texto' width='150'>&nbsp;&nbsp;$series[$i]</td>"; }
			$i++; $j++;	
			if($series[$i]!='') { 
				echo "<td align='center' class='texto' width='20' bgcolor='#DDDDDD'>$j</td>";
				echo "<td align='left' class='texto' width='150'>&nbsp;&nbsp;$series[$i]</td>"; }	
			 $j++;		
			echo "</tr>";	
		}
	echo "</table>"; }
} ?>
</body>