<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td width="5" valign="top"></td><td></td><td width="5"></td>
</tr>
<tr height="600" valign="top">
    <td>&nbsp;
		<!--Todo el contenido de cada página--->
		<?php
		   switch($op) {			
		   		case "exportar"	:	exportar($fechai,$fechaf,$apd);	break;			
		    	case "listado"	:	listado($fechai,$fechaf,$apd); break;	
				default			:	listado($fechai,$fechaf,$apd); break;
		} ?>	
		<!-- -->
	<br><br><br></td>
  </tr>
</table>

<?php function listado($fechai,$fechaf,$apd){
	include("conexion_db.php"); ?>
	<link rel="stylesheet" href="pop_Calendar/calendar.css">
	<script language="JavaScript" src="pop_Calendar/GCappearance.js"></script>
	<script language="JavaScript" src="pop_Calendar/GurtCalendar.js"></script>
	<script>
	function exportar() {
		form1.action='excel_reportes.php?op=temporal';
		form1.submit();	
		form1.action='?op=listado';	
	}
	</script>
	<?php if(!$fechai) $fechai=date("Y-m-d");
	if(!$fechaf) $fechaf=date("Y-m-d"); ?>
    <form action="?op=listado" method="post" name="form1">
	<table align="center" class="tabla">
        <tr height="20">
            <td align="center" width="100" bgcolor="#E6E6E6">Inicio Captura</td>
            <td align="center" width="100" bgcolor="#E6E6E6">Fin Captura</td>
            <td align="center" width="250" bgcolor="#E6E6E6">APD</td>
        </tr>
        <tr height="20">
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
            	<?php $s_1 = "select nombre from apd where activo='1' group by nombre order by nombre";
	              $r_1 = mysql_query($s_1); ?>
				  <select name="apd" class="texto" style="width:250px;" id="filtros">
	  				<option value="%">TODOS</option>	
					<?php while($d_1=mysql_fetch_array($r_1)) { ?>
						<option value="<?php echo $d_1['nombre']; ?>" <?php if($apd==$d_1['nombre']) { ?> selected="selected"<?php } ?>>
					<?php echo $d_1['nombre'];?></option><?php } ?>
            </td>
        </tr>
    </table>
    <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="150" align="center" valign="middle">
                <input type="button" value="Exportar" class="submit" name="boton" onclick="exportar();"></td>
        </tr>
    </table>
    </form>
<?php }?>