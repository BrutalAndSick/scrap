<script>

function validar_a1(tipo,info,financiero,evidencias,lo_loa,aplica_vendor) {
//Primera Parte
	if(form1.turno.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el turno');?>'); 
		form1.turno.focus(); return; }
	if(form1.proyecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el proyecto');?>'); 
		form1.proyecto.focus(); return; }		
	if(form1.plant.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la planta del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.division.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la división del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.segmento.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el segmento del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.prce.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el centro de costos del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }	
	if(form1.apd.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el APD');?>'); 
		form1.apd.focus(); return; }

//Segunda Parte
	if(form1.area.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área');?>'); 
		form1.area.focus(); return; }
	if(form1.estacion.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología');?>'); 
		form1.estacion.focus(); return; }
	if(form1.linea.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea');?>'); 
		form1.linea.focus(); return; }
	if(form1.defecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto');?>'); 
		form1.defecto.focus(); return; }
	if(form1.causa.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a');?>'); 
		form1.causa.focus(); return; }		
	if(form1.codigo_scrap.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap');?>'); 
		form1.codigo_scrap.focus(); return; }
	if(form1.orden_interna.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de orden interna');?>'); 
		form1.orden_interna.focus(); return; }	
	if(form1.reason_code.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de reason code');?>'); 
		form1.reason_code.focus(); return; }					

//validar si el código de scrap en financiero tiene 1
	if(financiero=='1' && form1.area_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área para la causa original');?>'); 
		form1.area_2.focus(); return; }
	if(financiero=='1' && form1.estacion_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología para la causa original');?>'); 
		form1.estacion_2.focus(); return; }
	if(financiero=='1' && form1.linea_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea para la causa original');?>'); 
		form1.linea_2.focus(); return; }
	if(financiero=='1' && form1.defecto_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto para la causa original');?>'); 
		form1.defecto_2.focus(); return; }
	if(financiero=='1' && form1.causa_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a para la causa original');?>'); 
		form1.causa_2.focus(); return; }		
	if(financiero=='1' && form1.codigo_scrap_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap para la causa original');?>'); 
		form1.codigo_scrap_2.focus(); return; }
	if(aplica_vendor=='si') {
	if(form1.vendor.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el número de vendor');?>'); 
		form1.vendor.focus(); return; } }	
	if(aplica_vendor=='si') {
	if(form1.v_nombre.value=='') {
		alert('<?php echo utf8_encode('Genere el nombre del vendor haciendo clic sobre el campo con el ícono verde: Vendor.');?>'); 
		form1.vendor.focus(); return; } }

	//Validar archivo
	if(tipo=='1' && lo_loa=='SI' && form1.archivo.value=='') {
		alert('<?php echo utf8_encode('Debe adjuntar el archivo de evidencia');?>'); 
		form1.archivo.focus(); return; } 	
	if(tipo=='2' && lo_loa=='SI' && evidencias=='' && form1.archivo.value=='') {
		alert('<?php echo utf8_encode('Debe adjuntar el archivo de evidencia');?>'); 
		form1.archivo.focus(); return; }
	//Tercera parte
	if(info=='SI' && form1.info_1.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la primera parte de la información obligatoria');?>'); 
		form1.info_1.focus(); return; }				
	if(info=='SI' && form1.info_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar la segunda parte de la información obligatoria');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && (form1.info_1.value=='VUT' || form1.info_1.value=='VUG' || form1.info_1.value=='ENC' || form1.info_1.value=='CO') && form1.info_2.value.length<6) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 6 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length<9) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 9 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length==9 && form1.info_2.value.substr(0,2)!='41') {
		alert('<?php echo utf8_encode('La información obligatoria debe iniciar con 41');?>'); 
		form1.info_2.focus(); return; }		
				
	if(form1.supervisor.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.supervisor.value='';
		alert('<?php echo utf8_encode('Debe seleccionar el nombre del supervisor');?>'); 
		form1.supervisor.focus(); return; }
	if(form1.operador.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.operador.value='';
		alert('<?php echo utf8_encode('Debe ingresar el nombre del operador');?>'); 
		form1.operador.focus(); return; }	
	if(form1.no_personal.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.no_personal.value='';
		alert('<?php echo utf8_encode('Debe ingresar el número personal del operador');?>'); 
		form1.no_personal.focus(); return; }	

	//Validar archivo de números de parte
	if(tipo=='1') {
	var extension, file_name;
	if(form1.partes.value=='') {
		alert('Es necesario adjuntar el archivo de partes');
		form1.partes.focus(); return; }	
	file_name = form1.partes.value;
	extension = (/[.]/.exec(file_name)) ? /[^.]+$/.exec(file_name) : undefined;
	if(extension!='csv') {
		alert('Utilice solamente archivos .csv');
		form1.partes.focus(); return; } }				
	
	if(tipo=='1') { form1.action='?op=guardar_1'; }
	if(tipo=='2') { form1.action='?op=update';    }
	form1.submit();			
}


function validar_m(tipo,info,registros,lo_loa,file,financiero,aplica_vendor) {
//Primera Parte
	if(form1.turno.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el turno');?>'); 
		form1.turno.focus(); return; }
	if(form1.proyecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el proyecto');?>'); 
		form1.proyecto.focus(); return; }		
	if(form1.plant.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la planta del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.division.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la división del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.segmento.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el segmento del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.prce.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el centro de costos del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }	
	if(form1.apd.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el APD');?>'); 
		form1.apd.focus(); return; }

//Segunda Parte
	if(form1.area.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área');?>'); 
		form1.area.focus(); return; }
	if(form1.estacion.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología');?>'); 
		form1.estacion.focus(); return; }
	if(form1.linea.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea');?>'); 
		form1.linea.focus(); return; }
	if(form1.defecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto');?>'); 
		form1.defecto.focus(); return; }
	if(form1.causa.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a');?>'); 
		form1.causa.focus(); return; }		
	if(form1.codigo_scrap.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap');?>'); 
		form1.codigo_scrap.focus(); return; }
	if(form1.orden_interna.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de orden interna');?>'); 
		form1.orden_interna.focus(); return; }	
	if(form1.reason_code.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de reason code');?>'); 
		form1.reason_code.focus(); return; }
							
	//Validar si el código de scrap tiene financiero en 1 
	if(financiero=='1' && form1.area_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área para la causa original');?>'); 
		form1.area_2.focus(); return; }
	if(financiero=='1' && form1.estacion_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología para la causa original');?>'); 
		form1.estacion_2.focus(); return; }
	if(financiero=='1' && form1.linea_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea para la causa original');?>'); 
		form1.linea_2.focus(); return; }
	if(financiero=='1' && form1.defecto_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto para la causa original');?>'); 
		form1.defecto_2.focus(); return; }
	if(financiero=='1' && form1.causa_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a para la causa original');?>'); 
		form1.causa_2.focus(); return; }		
	if(financiero=='1' && form1.codigo_scrap_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap para la causa original');?>'); 
		form1.codigo_scrap_2.focus(); return; }
	if(aplica_vendor=='si') {
	if(form1.vendor.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el número de vendor');?>'); 
		form1.vendor.focus(); return; } }	
	if(aplica_vendor=='si') {
	if(form1.v_nombre.value=='') {
		alert('<?php echo utf8_encode('Genere el nombre del vendor haciendo clic sobre el campo con el ícono verde: Vendor.');?>'); 
		form1.vendor.focus(); return; } }
		
	//Tabla de captura masiva
	if(registros<=0) {
		alert('<?php echo utf8_encode('Debe agregar al menos 1 número de parte');?>');
		return; }
	
	//Tercera parte
	if(info=='SI' && form1.info_1.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la primera parte de la información obligatoria');?>'); 
		form1.info_1.focus(); return; }				
	if(info=='SI' && form1.info_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar la segunda parte de la información obligatoria');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && (form1.info_1.value=='VUT' || form1.info_1.value=='VUG' || form1.info_1.value=='ENC' || form1.info_1.value=='CO') && form1.info_2.value.length<6) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 6 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length<9) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 9 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length==9 && form1.info_2.value.substr(0,2)!='41') {
		alert('<?php echo utf8_encode('La información obligatoria debe iniciar con 41');?>'); 
		form1.info_2.focus(); return; }		
	if(form1.supervisor.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.supervisor.value='';
		alert('<?php echo utf8_encode('Debe seleccionar el nombre del supervisor');?>'); 
		form1.supervisor.focus(); return; }
	if(form1.operador.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.operador.value='';
		alert('<?php echo utf8_encode('Debe ingresar el nombre del operador');?>'); 
		form1.operador.focus(); return; }	
	if(form1.no_personal.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.no_personal.value='';
		alert('<?php echo utf8_encode('Debe ingresar el número personal del operador');?>'); 
		form1.no_personal.focus(); return; }	

	//Validar archivo
	if(tipo=='1' && lo_loa=='SI' && form1.archivo.value=='') {
		alert('<?php echo utf8_encode('Debe adjuntar el archivo de evidencia');?>'); 
		form1.archivo.focus(); return; } 	
	if(tipo=='2' && lo_loa=='SI' && file=='' && form1.archivo.value=='') {
		alert('<?php echo utf8_encode('Debe adjuntar el archivo de evidencia');?>'); 
		form1.archivo.focus(); return; }
	
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update'; }
	form1.submit();			
}


function validar_del(id_borrar) {
	form1.action='?op=del_temp&id_borrar='+id_borrar;
	form1.submit();
}


function validar_add(tipo,txs,cod_scrap,apd,decimales) {
if(cod_scrap!='' && apd!='') { 
	if(form1.parte.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.parte.value='';
		alert('<?php echo utf8_encode('Debe seleccionar el número de parte');?>'); 
		form1.cantidad.focus(); return; }	
	if(decimales==0) {
		for(i=0;i<form1.cantidad.value.length;i++) {
		if(form1.cantidad.value.charAt(i)==".") { 
			alert('<?php echo utf8_encode('El modelo no acepta números decimales');?>'); 
			form1.cantidad.focus(); return;	}
		}
	}	
	if(form1.cantidad.value.replace(/^\s*|\s*$/g,"")=='' || form1.cantidad.value=='0') {
		form1.cantidad.value='';
		alert('<?php echo utf8_encode('Debe ingresar una cantidad válida');?>'); 
		form1.cantidad.focus(); return; }
	if(form1.tipo.value=='') {
		alert('<?php echo utf8_encode('El número de parte que ingresó no tiene un tipo asignado');?>'); 
		form1.parte.focus(); return; }	
	if(form1.tipo.value=='halb' && form1.subt.value=='') {
		alert('<?php echo utf8_encode('El número de parte que ingresó no tiene un subtipo asignado');?>'); 
		form1.parte.focus(); return; }	
	if(tipo=='FERT' && form1.batch_id.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el Batch ID');?>'); 
		form1.batch_id.focus(); return; }	
	if(form1.serial_unidad.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el número serial');?>'); 
		form1.serial_unidad.focus(); return; }
	if(form1.ubicacion.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar la ubicación');?>'); 
		form1.ubicacion.focus(); return; }		
	if(form1.padre.value=='' && txs=='ZSCR') {
		alert('<?php echo utf8_encode('Debe seleccionar el código de la parte padre');?>'); 
		form1.padre.focus(); return; } 	
	if(form1.padre.value=='' && txs!='ZSCR') {
		alert('<?php echo utf8_encode('El número de parte no existe para el APD seleccionado');?>'); 
		form1.padre.focus(); return; } 			
	form1.action='?op=add_temp'; 
	form1.submit();	}
else { 
	alert('<?php echo utf8_encode('Debe seleccionar el código de scrap y el APD');?>'); 
	return; }
					
}


function validar_i(tipo, info, txs, lo_loa, financiero,decimales,aplica_vendor) {
//Primera Parte
	if(form1.turno.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el turno');?>'); 
		form1.turno.focus(); return; }
	if(form1.proyecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el proyecto');?>'); 
		form1.proyecto.focus(); return; }	
	if(form1.plant.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la planta del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.division.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con la división del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.segmento.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el segmento del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }
	if(form1.prce.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el profit center del proyecto seleccionado');?>'); 
		form1.proyecto.focus(); return; }					
	if(form1.apd.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el APD');?>'); 
		form1.apd.focus(); return; }

//Segunda Parte
	if(form1.parte.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.parte.value='';
		alert('<?php echo utf8_encode('Debe seleccionar el número de parte');?>'); 
		form1.parte.focus(); return; }			
	if(form1.tipo.value=='') {
		alert('<?php echo utf8_encode('El número de parte que ingresó no tiene un tipo asignado');?>'); 
		form1.parte.focus(); return; }	
	if(form1.tipo.value=='halb' && form1.subt.value=='') {
		alert('<?php echo utf8_encode('El número de parte que ingresó no tiene un subtipo asignado');?>'); 
		form1.parte.focus(); return; }	
	if(decimales==0) {
		for(i=0;i<form1.cantidad.value.length;i++) {
		if(form1.cantidad.value.charAt(i)==".") { 
			alert('<?php echo utf8_encode('El modelo no acepta números decimales');?>'); 
			form1.cantidad.focus(); return;	}
		}
	}		
	if(form1.cantidad.value.replace(/^\s*|\s*$/g,"")=='' || form1.cantidad.value=='0') {
		form1.cantidad.value='';
		alert('<?php echo utf8_encode('Debe ingresar una cantidad válida');?>'); 
		form1.cantidad.focus(); return; }
	if(form1.padre.value=='' && txs=='ZSCR') {
		alert('<?php echo utf8_encode('Debe seleccionar el código de la parte padre');?>'); 
		form1.padre.focus(); return; }			
	if(form1.padre.value=='' && txs!='ZSCR') {
		alert('<?php echo utf8_encode('El número de parte no existe para el APD seleccionado');?>'); 
		form1.padre.focus(); return; } 				
	if(tipo=='FERT' && form1.batch_id.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el Batch ID');?>'); 
		form1.batch_id.focus(); return; }					
	if(form1.supervisor.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.supervisor.value='';
		alert('<?php echo utf8_encode('Debe seleccionar el nombre del supervisor');?>'); 
		form1.supervisor.focus(); return; }
	if(form1.operador.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.operador.value='';
		alert('<?php echo utf8_encode('Debe ingresar el nombre del operador');?>'); 
		form1.operador.focus(); return; }	
	if(form1.no_personal.value.replace(/^\s*|\s*$/g,"")=='') {
		form1.no_personal.value='';
		alert('<?php echo utf8_encode('Debe ingresar el número personal del operador');?>'); 
		form1.no_personal.focus(); return; }		

//Tercera Parte
	if(form1.area.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área');?>'); 
		form1.area.focus(); return; }
	if(form1.estacion.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología');?>'); 
		form1.estacion.focus(); return; }
	if(form1.linea.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea');?>'); 
		form1.linea.focus(); return; }
	if(form1.defecto.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto');?>'); 
		form1.defecto.focus(); return; }
	if(form1.causa.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a');?>'); 
		form1.causa.focus(); return; }
	if(form1.codigo_scrap.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap');?>'); 
		form1.codigo_scrap.focus(); return; }
	if(aplica_vendor=='si') {
	if(form1.vendor.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el número de vendor');?>'); 
		form1.vendor.focus(); return; } }	
	if(aplica_vendor=='si') {
	if(form1.v_nombre.value=='') {
		alert('<?php echo utf8_encode('Genere el nombre del vendor haciendo clic sobre el campo con el ícono verde: Vendor.');?>'); 
		form1.vendor.focus(); return; } }
		
//Cuarta Parte
	if(form1.serial_unidad.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el número serial');?>'); 
		form1.serial_unidad.focus(); return; }	
	if(form1.ubicacion.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar la ubicación');?>'); 
		form1.ubicacion.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la primera parte de la información obligatoria');?>'); 
		form1.info_1.focus(); return; }				
	if(info=='SI' && form1.info_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar la segunda parte de la información obligatoria');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && (form1.info_1.value=='VUT' || form1.info_1.value=='VUG' || form1.info_1.value=='ENC' || form1.info_1.value=='CO') && form1.info_2.value.length<6) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 6 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length<9) {
		alert('<?php echo utf8_encode('La información obligatoria debe tener 9 caracteres');?>'); 
		form1.info_2.focus(); return; }	
	if(info=='SI' && form1.info_1.value=='QN' && form1.info_2.value.length==9 && form1.info_2.value.substr(0,2)!='41') {
		alert('<?php echo utf8_encode('La información obligatoria debe iniciar con 41');?>'); 
		form1.info_2.focus(); return; }		
	if(form1.orden_interna.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de orden interna');?>'); 
		form1.orden_interna.focus(); return; }	
	if(form1.reason_code.value=='') {
		alert('<?php echo utf8_encode('Hay un problema con el número de reason code');?>'); 
		form1.reason_code.focus(); return; }	

//Validar archivo
	if(lo_loa=='SI' && form1.archivo.value=='') {
		alert('<?php echo utf8_encode('Debe adjuntar el archivo de evidencia');?>'); 
		form1.archivo.focus(); return; }			

//Validar sección del código financiero
	if(financiero=='1' && form1.area_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el área para la causa original');?>'); 
		form1.area_2.focus(); return; }
	if(financiero=='1' && form1.estacion_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la tecnología para la causa original');?>'); 
		form1.estacion_2.focus(); return; }
	if(financiero=='1' && form1.linea_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar la línea para la causa original');?>'); 
		form1.linea_2.focus(); return; }
	if(financiero=='1' && form1.defecto_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el defecto para la causa original');?>'); 
		form1.defecto_2.focus(); return; }
	if(financiero=='1' && form1.causa_2.value=='') {
		alert('<?php echo utf8_encode('Debe seleccionar el campo relacionado a para la causa original');?>'); 
		form1.causa_2.focus(); return; }
	if(financiero=='1' && form1.codigo_scrap_2.value=='') {
		alert('<?php echo utf8_encode('Debe ingresar el código scrap para la causa original');?>'); 
		form1.codigo_scrap_2.focus(); return; }		
	
	if(tipo=='1') { form1.action='?op=guardar'; }
	if(tipo=='2') { form1.action='?op=update';  }
form1.submit();	
}


function cambio_foco(op,campo)
{
	document.form1.action='?op='+op+'&campo='+campo;
	document.form1.submit();
}


function poner_foco(campo)
{ 
	if(campo=='proyecto') {
		form1.proyecto.focus();
		return; }	
	if(campo=='parte') {
		form1.parte.focus(); 
		form1.parte.value=form1.parte.value; return; }			
	if(campo=='cantidad') {
		form1.cantidad.focus(); return; }		
	if(campo=='area') {
		form1.area.focus(); return; }	
	if(campo=='estacion') {
		form1.estacion.focus(); return; }	
	if(campo=='linea') {
		form1.linea.focus(); return; }		
	if(campo=='defecto') {
		form1.defecto.focus(); return; }	
	if(campo=='causa') {
		form1.causa.focus(); return; }	
	if(campo=='codigo_scrap') {
		form1.codigo_scrap.focus(); return; }	
	if(campo=='apd') {
		form1.apd.focus(); return; }	
	if(campo=='area_2') {
		form1.area_2.focus(); return; }	
	if(campo=='estacion_2') {
		form1.estacion_2.focus(); return; }	
	if(campo=='linea_2') {
		form1.linea_2.focus(); return; }		
	if(campo=='defecto_2') {
		form1.defecto_2.focus(); return; }	
	if(campo=='causa_2') {
		form1.causa_2.focus(); return; }	
	if(campo=='codigo_scrap_2') {
		form1.codigo_scrap_2.focus(); return; }					
}
</script>