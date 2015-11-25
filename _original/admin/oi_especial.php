<?php include("../header.php"); ?>

<script>
function validar(ruta) {
	if(form1.division.value=='') {
		alert('Es necesario seleccionar una división');
		form1.division.focus(); return; }	
	if(form1.segmento.value=='') {
		alert('Es necesario seleccionar un segmento');
		form1.segmento.focus(); return; }
	if(form1.prce.value=='') {
		alert('Es necesario seleccionar un profit center');
		form1.prce.focus(); return; }
	if(form1.proyecto.value=='') {
		alert('Es necesario seleccionar un proyecto');
		form1.proyecto.focus(); return; }
	if(form1.codigo.value=='') {
		alert('Es necesario seleccionar un código de scrap');
		form1.codigo.focus(); return; }
	if(form1.orden_interna.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.orden_interna.value='';
		alert('Es necesario ingresar la orden interna');
		form1.orden_interna.focus(); return; }
		
	if(form1.division.value=='na' && form1.proyecto.value!='todos') {
		alert('No puede seleccionar división NA a menos que proyecto sea TODOS');
		form1.division.focus(); return; }
	if(form1.segmento.value=='na' && form1.proyecto.value!='todos') {
		alert('No puede seleccionar segmento NA a menos que proyecto sea TODOS');
		form1.segmento.focus(); return; }
	if(form1.prce.value=='na' && form1.proyecto.value!='todos') {
		alert('No puede seleccionar profit center NA a menos que proyecto sea TODOS');
		form1.prce.focus(); return; }		
		
	form1.action = '?op='+ruta;
	form1.submit();	
}

function upload() {
	var extension, file_name;
	if(form1.archivo.value=='') {
		alert('Es necesario seleccionar el archivo');
		form1.archivo.focus(); return; }	
	file_name = form1.archivo.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='csv') {
		alert('Utilice solamente archivos .csv');
		form1.archivo.focus(); return; }
			
	form1.action='?op=upload_file';
	form1.submit();	
}

function cancelar(ruta) {
	form1.action=ruta;
	form1.submit();	
}

function excel() {
	form1.action = 'excel.php?op=oi_especial';
	form1.submit();	
	form1.action = 'oi_especial.php?op=listado';
}
</script>

  <tr>
  	<td><div style="margin-left:30px; margin-top:20px;"><?php menu('b_areas'); ?></div></td>
  </tr>
  <tr>
    <td><?php submenu('b_areas','oi_especial'); ?></td>
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
		case "upload_form"		:	upload_form(); break;
		case "upload_file"		: 	upload_file($archivo,$archivo_name); break;
		case "guardar_temp"		:	guardar_temp();	listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina); break;	

		case "nuevo"			:	nuevo($division,$segmento,$prce,$proyecto,$codigo,$orden_interna); break;
		case "guardar"			:	guardar($division,$segmento,$prce,$proyecto,$codigo,$orden_interna); 
									nuevo($division,$segmento,$prce,$proyecto,$codigo,$orden_interna); break;
	
		case "listado"			:	listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina); break;
		case "estado"			:	estado($id_,$estado,$nombre,$f_area,$f_estacion); listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina); break;
		case "editar"			:	editar($id_,$division,$segmento,$prce,$proyecto,$codigo,$orden_interna,$f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo); break;
		case "update"			:	update($id_,$division,$segmento,$prce,$proyecto,$codigo,$orden_interna,$f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo); 
									listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina); break;
	
		case "borrar"			:	borrar($id_,$nombre,$f_area,$f_estacion); listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina);	 break;		
		default					:	listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina); break;
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
	<td class="titulo" width="150">O.I. ESPECIAL</td>
	<td><a href="?op=nuevo" class="menuLink">Nueva</a></td>
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=upload_form" class="menuLink">Upload</a></td>	
	<td><img src="../imagenes/division_submenu.png"></td>
	<td><a href="?op=listado" class="menuLink">Consultar</a></td>
</tr>
</table></div><hr>
<?php } 	


function nuevo($division,$segmento,$prce,$proyecto,$codigo,$orden_interna) { 
	if($proyecto=='todos') { 
		$division = 'na'; 
		$segmento = 'na';
		$prce	  = 'na'; } ?>
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=nuevo" method="post" name="form1">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Nueva Orden Interna Especial</caption>
<tr>
	<td valign="top">División:</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($division=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_2 = "select * from divisiones where activo='1' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Segmento:</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($segmento=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_4 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($segmento==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">P.C.:</td>
	<td>
	<select name="prce" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($prce=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_3 = "select * from profit_center where activo='1' and id_segmento='$segmento' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($prce==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Proyecto:</td>
	<td>
	<select name="proyecto" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="todos" <?php if($proyecto=='todos') { ?> selected="selected"<?php } ?>>Todos</option>
	<?php $s_3 = "select * from proyectos where activo='1' and id_division='$division' and id_segmento='$segmento' and id_pc='$prce' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($proyecto==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Codigo SCRAP:</td>
	<td>
	<select name="codigo" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_3 = "select * from codigo_scrap where activo='1' and orden_interna='1' order by codigo";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['codigo'];?>" <?php if($codigo==$d_3['codigo']){?> selected="selected"<?php } ?>><?php echo $d_3['codigo'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Orden Intera:</td>
	<td><input type="text" name="orden_interna" class="texto" size="45">
	</td>
</tr>
</table>
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar('guardar');" class="submit"></div>
</form>
<?php  } 


function guardar($division,$segmento,$prce,$proyecto,$codigo,$orden_interna) {
	$orden_interna = trim($orden_interna);

	$existe = ver_si_existe($division,$segmento,$prce,$proyecto,$codigo);
	if($existe=='NO') {
		$s_1 = "insert into oi_especial values('','$division','$segmento','$prce','$proyecto','$codigo','$orden_interna','1')";
		$r_1 = mysql_query($s_1);
		echo"<script>alert('Registro almacenado');</script>"; }
	else { echo"<script>alert('Ese registro ya existe');</script>"; }
}


function ver_si_existe($division,$segmento,$prce,$proyecto,$codigo) {
	$s_1 = "select * from oi_especial where id_division='$division' and id_segmento='$segmento' and id_pc='$prce' and id_proyecto='$proyecto' and codigo_scrap='$codigo' ";
	$s_1.= "and activo='1'";
	$r_1 = mysql_query($s_1);
		if(mysql_num_rows($r_1)<=0) { return 'NO'; }
		else { return 'SI'; }
}


function listado($f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo,$pagina) {
	if(!$pagina)		$pagina = '1';
	if(!$f_division)    $f_division = '%'; 
	if(!$f_pc)          $f_pc = '%'; 
	if(!$f_segmento)    $f_segmento = '%'; 
	if(!$f_codigo)      $f_codigo = '%'; 
	if(!$f_proyecto) 	$f_proyecto	= '%'; ?>
    <div align="center" class="aviso">Los encabezados de la tabla permiten filtrar los campos. Seleccione cualquiera de los filtros para mostrar los registros.</div><br>

<?php
    $s_1 = "select * from oi_especial where activo!='2' ";
	if($f_division!='%') { $s_1.= "and oi_especial.id_division like '$f_division'";  }
	if($f_pc!='%')       { $s_1.= "and oi_especial.id_pc like '$f_pc'"; }
	if($f_segmento!='%') { $s_1.= "and oi_especial.id_segmento like '$f_segmento'";  }
	if($f_proyecto!='%') { $s_1.= "and oi_especial.id_proyecto like '$f_proyecto'";  }
	if($f_codigo!='%')   { $s_1.= "and oi_especial.codigo_scrap like '$f_codigo'";   }
	
	$s_1.= " order by activo desc, codigo_scrap asc";
    $r_1 = mysql_query($s_1);
	$n_1 = mysql_num_rows($r_1);
	$pags = ceil($n_1/50);
    $ini_ = ($pagina-1)*50;
    $fin_ = 50; $i=1;

	if($pags>0) {      
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='texto'>";
		echo "<tr height='25'>";
			echo "<td width='120' align='center' bgcolor='#D8D8D8' style='border:#CCCCCB solid 1px;'>$n_1&nbsp;Registros</td>";
			echo "<td width='3'></td>";		
			while($i<=$pags) {
			if($pagina==$i) { $bg_img = '../imagenes/pag_on.jpg'; } else { $bg_img = '../imagenes/pag_off.jpg'; }
			echo "<td width='25' align='center' background='$bg_img' style='border:#CCCCCB solid 1px;'>";
			echo "<a href='?op=listado&f_area=$f_area&f_estacion=$f_estacion&pagina=$i' class='link_paginas'>$i</a></td>";
			echo "<td width='3'></td>";	
		$i++; $j++; }
		echo "</tr>";
	echo "</table><br>"; } ?>

<form action="?op=listado" method="post" name="form1">
<div align="center"><input type="button" value="Exportar" class="submit" onclick="excel();"></div><br>
<table align="center" class="tabla">
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="50" align="center">No.</td>
        <td width="50" align="center">Estado</td>
		<td width="150" align="center">
        <select name="f_division" style="width:150px;" class="texto" onchange="submit();">
           <option value="%" <?php if($f_division=='%'){?> selected="selected"<?php } ?>>División</option>
           <option value="na" <?php if($f_division=='na'){?> selected="selected"<?php } ?>>No Aplica</option>
        <?php $s_3 = "select divisiones.* from divisiones where divisiones.activo='1' order by divisiones.nombre";
              $r_3 = mysql_query($s_3);
           while($d_3=mysql_fetch_array($r_3)) { ?>
           <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_division){ ?> selected="selected"<?php } ?>>
           <?php echo $d_3['nombre'];?></option>
           <?php } ?>
        </select>		
		</td>
		<td width="100" align="center">
		<select name="f_segmento" style="width:150px;" class="texto" onchange="submit();">
			<option value="%" <?php if($f_segmento=='%'){?> selected="selected"<?php } ?>>Segmento</option>
            <option value="na" <?php if($f_segmento=='na'){?> selected="selected"<?php } ?>>No Aplica</option>
	 	<?php $s_3 = "select segmentos.* from segmentos, divisiones where segmentos.id_division like '$f_division' and segmentos.activo='1'and divisiones.activo='1' and ";
	          $s_3.= "segmentos.id_division = divisiones.id order by segmentos.nombre";
              $r_3 = mysql_query($s_3);
           	while($d_3=mysql_fetch_array($r_3)) { ?>
            <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_segmento){ ?> selected="selected"<?php } ?>>
            <?php echo $d_3['nombre'];?></option>
            <?php } ?>
		</select>
		</td>
		<td width="100" align="center">
		<?php $s_3 = "select profit_center.* from profit_center, divisiones, segmentos where profit_center.activo='1' and divisiones.activo='1' and segmentos.activo='1' ";
		      $s_3.= "and profit_center.id_division = divisiones.id and profit_center.id_segmento = segmentos.id and profit_center.id_segmento like '$f_segmento' and ";
			  $s_3.= "profit_center.id_division like '$f_division' order by profit_center.nombre"; ?>
		<select name="f_pc" style="width:100px;" class="texto" onchange="submit();">
			<option value="%" <?php if($f_pc=='%'){?> selected="selected"<?php } ?>>P.C.</option>
            <option value="na" <?php if($f_pc=='na'){?> selected="selected"<?php } ?>>No Aplica</option>
	    <?php $r_3 = mysql_query($s_3);
	   		while($d_3=mysql_fetch_array($r_3)) { ?>
	  	    <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_pc){ ?> selected="selected"<?php } ?>>
	   		<?php echo $d_3['nombre'];?></option>
	   		<?php } ?>
		</select>		
		</td>
		<td width="200" align="center">
		<?php $s_3 = "select proyectos.* from profit_center, divisiones, segmentos, proyectos where profit_center.activo='1' and divisiones.activo='1' and ";
		      $s_3.= "segmentos.activo='1' and proyectos.activo='1' and proyectos.id_division = divisiones.id and proyectos.id_segmento = segmentos.id and ";
			  $s_3.= "proyectos.id_pc = profit_center.id and proyectos.id_segmento like '$f_segmento' and proyectos.id like '$f_proyecto' and proyectos.id_division like ";
			  $s_3.= "'$f_division' and proyectos.id_pc like '$f_pc%' order by proyectos.nombre"; ?>
		<select name="f_proyecto" style="width:200px;" class="texto" onchange="submit();">
			<option value="%" <?php if($f_proyecto=='%'){?> selected="selected"<?php } ?>>Proyecto</option>
            <option value="todos" <?php if($f_proyecto=='todos'){?> selected="selected"<?php } ?>>Todos</option>
	    <?php $r_3 = mysql_query($s_3);
	   		while($d_3=mysql_fetch_array($r_3)) { ?>
	  	    <option value="<?php echo $d_3['id'];?>" <?php if($d_3['id']==$f_proyecto){ ?> selected="selected"<?php } ?>>
	   		<?php echo $d_3['nombre'];?></option>
	   		<?php } ?>
		</select>		
		</td>
		<td width="100" align="center">
		<select name="f_codigo" style="width:100px;" class="texto" onchange="submit();">
			<option value="%" <?php if($f_codigo=='%'){?> selected="selected"<?php } ?>>Código Scrap</option>
		<?php $s_2 = "select * from codigo_scrap where activo='1' order by codigo";
		      $r_2 = mysql_query($s_2);
		   while($d_2=mysql_fetch_array($r_2)){?>
		   <option value="<?php echo $d_2['codigo'];?>" <?php if($f_codigo==$d_2['codigo']){?> selected="selected"<?php } ?>>
		   <?php echo $d_2['codigo'];?></option>
		   <?php } ?>
		</select>  		
		</th>
		<td width="100" align="center">Orden Interna</td>
		<td width="40" align="center">Editar</td>
		<td width="40" align="center">Borrar</td>
	</tr>
</thead>
<tbody>
<?php $s_1.= " limit $ini_,$fin_"; $i=$ini_+1;
      $r_1 = mysql_query($s_1); 
      while($d_1=mysql_fetch_array($r_1)) { 
      $ruta = "&nombre=$d_1[nombre]&f_area=$f_area&f_estacion=$f_estacion"; ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="center"><?php echo $i;?></td>
    <td align="center">
		<?php if($d_1['activo']=='1') { echo"<a href='?op=estado&id_=$d_1[id]&estado=0$ruta'>
									      <img src='../imagenes/tick.png' alt='Activo' border='0'>";    } 
		   if($d_1['activo']=='0') { echo"<a href='?op=estado&&id_=$d_1[id]&estado=1$ruta'>
									      <img src='../imagenes/cross.png' alt='Inactivo' border='0'>"; } ?>
	</td>
    <?php if($d_1['id_division']!='na') { 
	      $s_2 = "select nombre from divisiones where id='$d_1[id_division]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; } ?>
	<td align="left">&nbsp;&nbsp;<?php echo $nombre;?></td>
    <?php if($d_1['id_segmento']!='na') {
		  $s_2 = "select nombre from segmentos where id='$d_1[id_segmento]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; }  ?>
    <td align="left">&nbsp;&nbsp;<?php echo $nombre;?></td>
    <?php if($d_1['id_pc']!='na') {
	      $s_2 = "select nombre from profit_center where id='$d_1[id_pc]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'No Aplica'; }  ?>
    <td align="left">&nbsp;&nbsp;<?php echo $nombre;?></td>
    <?php if($d_1['id_proyecto']!='todos') {
	      $s_2 = "select nombre from proyectos where id='$d_1[id_proyecto]'";
	      $r_2 = mysql_query($s_2);
	      $d_2 = mysql_fetch_array($r_2); $nombre = $d_2['nombre']; } else { $nombre = 'Todos'; }  ?>
    <td align="left">&nbsp;&nbsp;<?php echo $nombre;?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['codigo_scrap'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['orden_interna'];?></td>
	<td align="center">
		<a href="?op=editar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>"><img src="../imagenes/pencil.gif" alt="Editar" border="0"></a></td>
	<td align="center">
		<a href="?op=borrar&id_=<?php echo $d_1['id'];?><?php echo $ruta;?>" onclick='return confirm("¿Borrar Registro?")'>
		<img src="../imagenes/delete.gif" alt="Borrar" border="0"></a></td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</form>
<?php }



function editar($id_,$division,$segmento,$prce,$proyecto,$codigo,$orden_interna,$f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo) { 
	$s_1 = "Select * from oi_especial where id='$id_'";
	$r_1 = mysql_query($s_1); 
	$d_1 = mysql_fetch_array($r_1);
	if(!$division) { $division = $d_1['id_division']; }
	if(!$segmento) { $segmento = $d_1['id_segmento']; }
	if(!$prce) { $prce = $d_1['id_pc']; }
	if(!$proyecto) { $proyecto = $d_1['id_proyecto']; }
	if(!$codigo) { $codigo = $d_1['codigo_scrap']; }
	if(!$orden_interna) { $orden_interna = $d_1['orden_interna']; } 
	
 	if($proyecto=='todos' || $division=='na' || $segmento=='na' || $prc=='na') { 
		$division = 'na'; 
		$segmento = 'na';
		$prce	  = 'na';
		$proyecto = 'todos'; } ?>
    
<div align="center" class="aviso">Todos los campos son obligatorios</div>
<form action="?op=editar" method="post" name="form1">
<input type="hidden" name="id_" value="<?php echo $id_;?>">
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Editar Orden Interna Especial</caption>
<tr>
	<td valign="top">División:</td>
	<td>
	<select name="division" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($division=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_2 = "select * from divisiones where activo='1' order by nombre";
	   $r_2 = mysql_query($s_2);
	   while($d_2=mysql_fetch_array($r_2)) { ?>
	   <option value="<?php echo $d_2['id'];?>" <?php if($division==$d_2['id']){?> selected="selected"<?php } ?>><?php echo $d_2['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Segmento:</td>
	<td>
	<select name="segmento" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($segmento=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_4 = "select * from segmentos where activo='1' and id_division='$division' order by nombre";
	   $r_4 = mysql_query($s_4);
	   while($d_4=mysql_fetch_array($r_4)) { ?>
	   <option value="<?php echo $d_4['id'];?>" <?php if($segmento==$d_4['id']){?> selected="selected"<?php } ?>><?php echo $d_4['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">P.C.:</td>
	<td>
	<select name="prce" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="na" <?php if($prce=='na') { ?> selected="selected"<?php } ?>>No Aplica</option>
	<?php $s_3 = "select * from profit_center where activo='1' and id_segmento='$segmento' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($prce==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Proyecto:</td>
	<td>
	<select name="proyecto" style="width:254px;" class="texto" onchange="submit();">
		<option value=""></option>
        <option value="todos" <?php if($proyecto=='todos') { ?> selected="selected"<?php } ?>>Todos</option>
	<?php $s_3 = "select * from proyectos where activo='1' and id_division='$division' and id_segmento='$segmento' and id_pc='$prce' order by nombre";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['id'];?>" <?php if($proyecto==$d_3['id']){?> selected="selected"<?php } ?>><?php echo $d_3['nombre'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Codigo SCRAP:</td>
	<td>
	<select name="codigo" style="width:254px;" class="texto">
		<option value=""></option>
	<?php $s_3 = "select * from codigo_scrap where activo='1' and orden_interna='1' order by codigo";
	   $r_3 = mysql_query($s_3);
	   while($d_3=mysql_fetch_array($r_3)) { ?>
	   <option value="<?php echo $d_3['codigo'];?>" <?php if($codigo==$d_3['codigo']){?> selected="selected"<?php } ?>><?php echo $d_3['codigo'];?></option>
	   <?php } ?>
	</select>	   
	</td>
</tr>
<tr>
	<td valign="top">Orden Intera:</td>
	<td><input type="text" name="orden_interna" class="texto" size="45" value="<?php echo $d_1['orden_interna'];?>">
	</td>
</tr>
</table>    
<br><div align="center">
<input type="button" value="Cancelar" onclick="cancelar('?op=listado');" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Guardar" onclick="validar('update');" class="submit"></div>
</form>
<?php  } 


function update($id_,$division,$segmento,$prce,$proyecto,$codigo,$orden_interna,$f_division,$f_pc,$f_segmento,$f_proyecto,$f_codigo) {
	$orden_interna = trim($orden_interna);
	
	$s_1 = "update oi_especial set id_division='$division', id_segmento='$segmento', id_pc='$prce', id_proyecto='$proyecto', codigo_scrap='$codigo', ";
	$s_1.= "orden_interna='$orden_interna' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function borrar($id_,$nombre,$f_area,$f_estacion) {
	$s_1 = "update oi_especial set activo='2' where id='$id_'";
	$r_1 = mysql_query($s_1); 
}	

function estado($id_,$estado,$nombre,$f_area,$f_estacion) {
	$s_1 = "update oi_especial set activo='$estado' where id='$id_'";
	$r_1 = mysql_query($s_1);  }


function upload_form(){ 
	$s_1 = "delete from tmp_oi_especial"; $r_1 = mysql_query($s_1); ?>
<form action="?op=upload_file" method="post" enctype="multipart/form-data" name="form1">	
<table align="center" class="tabla" cellpadding="0" cellspacing="5">
<caption>Subir Archivo CSV con Ordenes Internas Especiales</caption>
<tbody>
<tr>
	<td valign="top">Archivo:</td>
	<td><input type="file" name="archivo" class="texto" size="50"></td>
</tr>
</tbody>
</table>
<br><div align="center">
<input type="button" value="Guardar" class="submit" onclick="upload();">
</div>
</form>
<div align="center" class="aviso_naranja">Se insertarán solamente los registros que no existan</div>
<br><div align="center">
	<table align="center" width="650" border="0" cellspacing="5" style="border-color:#999999; border-style:solid; border-width:1px;">
	<tr><td align="center" width="60" rowspan="9" valign="top">
		<img src="../imagenes/exclamation.gif">
	</td><td align="left" class="gris" width="440"><b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Formato del Archivo para Líneas y Proyectos</b></td></tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Los encabezados del archivo deben ser minúsculas sin acentos ó ñ</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de División] body=[<?php echo consulta('division');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La primera columna contiene el nombre de la división (si no aplica utilice la leyenda "NA")</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Segmento] body=[<?php echo consulta('segmento');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La segunda columna contiene el nombre del segmento (si no aplica utilice la leyenda "NA")</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Profit Center] body=[<?php echo consulta('prce');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La tercera columna contiene el nombre del profit center (si no aplica utilice la leyenda "NA")</td>	
	</tr>
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Nombre de Proyecto] body=[<?php echo consulta('proyecto');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La cuarta columna contiene el nombre del proyecto (si se utilizará en todos use la leyenda "TODOS")</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span title="header=[&nbsp;&nbsp;Código de Scrap] body=[<?php echo consulta('codigo_scrap');?>]"><img src="../imagenes/information.png" border="0"></span>&nbsp;&nbsp;La quinta columna contiene el codigo de scrap</td>	
	</tr>		
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        La sexta columna contiene el valor de la orden interna</td>	
	</tr>	
	<tr><td class="gris" align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="archivos/ejemplo_oi_especial.csv"><img src="../imagenes/attach.png" border="0"></a>&nbsp;&nbsp;
		 Descargue un archivo de ejemplo</td>	
	</tr>			
	</table>
</div>
<?php  } 


function consulta($tipo) {
if($tipo=='division') 	  { $s_1 = "select * from divisiones where activo='1' order by nombre limit 0,10"; } 
if($tipo=='segmento') 	  { $s_1 = "select * from segmentos where activo='1' order by nombre limit 0,10"; } 
if($tipo=='prce') 	  	  { $s_1 = "select * from profit_center where activo='1' order by nombre limit 0,10"; } 
if($tipo=='proyecto') 	  { $s_1 = "select * from proyectos where activo='1' order by nombre limit 0,10"; }
if($tipo=='codigo_scrap') { $s_1 = "select codigo as nombre from codigo_scrap where activo='1' order by codigo limit 0,10"; } 
	$i=1; echo"<table align=left class=texto>";
	$r_1 = mysql_query($s_1);
	while($d_1=mysql_fetch_array($r_1)) { 
		echo"<tr><td width=20 align=center bgcolor=#CCCCCC>$i</td><td bgcolor=#CCCCCC width=220>$d_1[nombre]</td></tr>"; $i++; }
	echo"</table>";
}	


function upload_file($archivo,$archivo_name) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server  = $d_['valor'];
	$pext      = getFileExtension($archivo_name);
	$nombre    = date("YmdHis").".".$pext;
	$nom_final = $r_server.$nombre;
	if (is_uploaded_file($archivo)) {
		if (!copy($archivo,"$nom_final")) {
			echo "<script>alert('Error al subir el archivo: $nom_final');</script>"; 					
			upload_form(); exit; }
		else { 
			insert_csv($nombre); }
	}
}	

function insert_csv($alias) {
	$s_ = "select * from configuracion where variable='ruta_cargas'";
	$r_ = mysql_query($s_);
	$d_ = mysql_fetch_array($r_);
	
	$r_server = $d_['valor'];
	$fecha    = date("Y-m-d");
	$fd       = fopen ($r_server."$alias", "r");
	$insertar = 0;

	while ( !feof($fd) ) 
 	{
		$buffer = fgets($fd);
		$campos = split (",", $buffer);	$error = '';	

		if($campos['0']!='' && $campos['0']!='division') {

			//Buscar el id de la división
			if(trim(utf8_encode($campos[0]))!='NA') { 
			$s_1 = "Select id, nombre from divisiones where nombre like '".trim(utf8_encode($campos[0]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1); 
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_d']  = $d_1['id']; 
				$field['nom_d'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en la división: $campos[0]<br>"; }	}
			else { $field['id_d']  = 'na'; 
				   $field['nom_d'] = 'No Aplica'; } 

			//Buscar el id del segmento
			if(trim(utf8_encode($campos[1]))!='NA') { 
			$s_1 = "Select id, nombre from segmentos where id_division='".trim($field['id_d'])."' and nombre like ";
			$s_1.= "'".trim(utf8_encode($campos[1]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_s']  = $d_1['id'];
				$field['nom_s'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el segmento: $campos[1]<br>"; }	}
			else { $field['id_s']  = 'na'; 
				   $field['nom_s'] = 'No Aplica'; } 

			//Buscar el id del profit center
			if(trim(utf8_encode($campos[2]))!='NA') { 
			$s_1 = "Select id, nombre from profit_center where id_division='".trim($field['id_d'])."' and id_segmento='".trim($field['id_s'])."' and nombre like ";
			$s_1.= "'".trim(utf8_encode($campos[2]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_pc']  = $d_1['id'];
				$field['nom_pc'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el profit center: $campos[2]<br>"; } }
			else { $field['id_pc']  = 'na'; 
				   $field['nom_pc'] = 'No Aplica'; } 	

			//Buscar el id del proyecto
			if(trim(utf8_encode($campos[3]))!='TODOS') { 
			$s_1 = "Select id, nombre from proyectos where id_division='".trim($field['id_d'])."' and id_segmento='".trim($field['id_s'])."' and id_pc like '";
			$s_1.= trim($field['id_pc'])."' and nombre like '".trim(utf8_encode($campos[3]))."' and activo='1'";
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_p']  = $d_1['id'];
				$field['nom_p'] = $d_1['nombre']; }
			else { $insertar++; $error .= "Error en el proyecto: $campos[3]<br>"; }	}
			else { $field['id_p']  = 'todos'; 
				   $field['nom_p'] = 'Todos'; } 	

			//Buscar el código de scrap
			if(strlen(trim(utf8_encode($campos[4])))==2) {
				$campos[4] = str_pad(trim(utf8_encode($campos[4])),3,0,STR_PAD_LEFT); }
			$s_1 = "Select id, codigo from codigo_scrap where codigo like '".trim(utf8_encode($campos[4]))."' and activo='1'"; 
			$r_1 = mysql_query($s_1);
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['id_cs']  = $d_1['id'];
				$field['nom_cs'] = $d_1['codigo']; }
			else { $insertar++; $error .= "Error en el código de scrap: $campos[4]<br>"; }	

			//Buscar si ya existe algún registro igual para actualizar o insertar
			$s_1 = "Select id from oi_especial where id_division='$field[id_d]' and id_segmento='$field[id_s]' and id_pc='$field[id_pc]' and id_proyecto='$field[id_p]' ";
			$s_1.= "and codigo_scrap='$field[nom_cs]' and activo='1'"; 
			$r_1 = mysql_query($s_1); 
			if(mysql_num_rows($r_1)>0) {
				$d_1 = mysql_fetch_array($r_1);
				$field['existe'] = $d_1['id']; }
			else { $field['existe'] = '0'; }	

			$orden_interna = trim(utf8_encode($campos[5]));

			if($insertar<=0) {
				$query = "INSERT into tmp_oi_especial values('', '$field[id_d]', '$field[nom_d]', '$field[id_s]', '$field[nom_s]', ";
				$query.= "'$field[id_pc]', '$field[nom_pc]', '$field[id_p]', '$field[nom_p]', '$field[nom_cs]', '$orden_interna', '$field[existe]')"; 
				mysql_query($query); $ins++; }
			else { 
				echo "<br><div class=aviso_naranja align=center>".$error;
				echo "Verifique que el archivo tenga el formato necesario y que los registros estén activos<br>";
				echo "<br><br>No se puede continuar con la carga !!</div><br>"; 
				exit; }				
			$insertar=0;			
		}			
	}     
	echo "<script>alert('Se han cargado los registros');</script>";
	fclose ($fd); 
	unlink($r_server.$alias);
	listado_temporal(); 
}


function listado_temporal() { ?>
<div align="center" class="aviso">Por favor, verifique si la información para la carga es correcta</div>
<form action="?op=guardar_upload" method="post" name="form1">
<table align="center" class="tabla">
<caption>¿Es correcta la información?</caption>
<thead>
	<tr bgcolor="#E6E6E6" height="20">
		<td width="100" align="center">División</td>
		<td width="200" align="center">Segmento</td>	
		<td width="200" align="center">Profit Center</td>
		<td width="200" align="center">Proyecto</td>
        <td width="100" align="center">Código SCRAP</td>
        <td width="120" align="center">Orden Interna</td>
	</tr>
</thead>
<?php 
   $s_1 = "select * from tmp_oi_especial order by id";
   $r_1 = mysql_query($s_1); ?>
<tbody>
<?php while($d_1=mysql_fetch_array($r_1)) { ?>
<tr onMouseOut="this.style.background='#F7F7F7'" onMouseOver="this.style.background='#FFDD99'" bgcolor="#F7F7F7" height="20">
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['division'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['segmento'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['pc'];?></td>
	<td align="left">&nbsp;&nbsp;<?php echo $d_1['proyecto'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['codigo_scrap'];?></td>
    <td align="left">&nbsp;&nbsp;<?php echo $d_1['orden_interna'];?></td>
</tr>
<?php } ?>
</tbody>
</table>
<div align="center"><br>
<input type="button" value="Cancelar" onclick="cancelar('?op=upload_form')" class="submit">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Continuar" onclick="cancelar('?op=guardar_temp')" class="submit">
</div>
</form>
<?php }


function guardar_temp() {

//Inserto todas las líneas que no existan en la base de datos
$s_ = "select * from tmp_oi_especial order by id";
$r_ = mysql_query($s_); $i=$j=0;
while($d_=mysql_fetch_array($r_)) {
	if($d_['existe']=='0') { 
		$s_1 = "insert into oi_especial values('', '$d_[id_division]', '$d_[id_segmento]', '$d_[id_pc]', '$d_[id_proyecto]', '$d_[codigo_scrap]', ";
		$s_1.= "'$d_[orden_interna]', '1')"; $i++; }
	else { 
		$s_1 = "update oi_especial set id_division='$d_[id_division]', id_segment='$d_[id_segmento]', id_pc='$d_[id_pc]', id_proyecto='$d_[id_proyecto]', ";
		$s_1.= "codigo_scrap='$d_[codigo_scrap]', orden_interna='$d_[orden_interna]' where id='$d_[existe]'"; $j++; }
	$r_1 = mysql_query($s_1);
}			
	echo"<script>alert('$i registros insertados. $j registros actualizados.');</script>";		
}


function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
} ?>