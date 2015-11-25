<?php include("conexion_db.php");
conectame_db("scrap_gdl");

//Vista de empleados
$s_ = "CREATE OR REPLACE VIEW vw_empleados as select * from empleados where activo!='2'";
$r_ = mysql_query($s_);

//Vista de autorizadores
$s_ = "CREATE OR REPLACE VIEW vw_autorizadores as select autorizadores.*, empleados.nombre, empleados.apellidos, empleados.usuario, divisiones.nombre as division ";
$s_.= "from autorizadores, empleados, divisiones where autorizadores.id_emp = empleados.id and autorizadores.id_division = divisiones.id";
$r_ = mysql_query($s_);

//Vista aprobados
$s_ = "CREATE OR REPLACE view vw_aprobados as select folios.*, partes.padre, partes.no_parte, partes.tipo, partes.tipo_sub, partes.descripcion, partes.cantidad, ";
$s_.= "partes.costo, partes.total, partes.batch_id, partes.serial_unidad, partes.ubicacion, partes.docto_sap, partes.deficit from scrap_folios as folios, scrap_partes ";
$s_.= "as partes where folios.no_folio = partes.no_folio and folios.status='1' and folios.activo='1' order by folios.no_folio desc";
$r_ = mysql_query($s_);

//Vista cancelados
$s_ = "CREATE OR REPLACE view vw_cancelados as select folios.*, partes.padre, partes.no_parte, partes.tipo, partes.tipo_sub, partes.descripcion, partes.cantidad, ";
$s_.= "partes.costo, partes.total, partes.batch_id, partes.serial_unidad, partes.ubicacion, partes.docto_sap, partes.deficit from scrap_folios as folios, scrap_partes ";
$s_.= "as partes where folios.no_folio = partes.no_folio and (folios.status='0' or folios.status='2') and folios.activo='2' order by folios.no_folio desc";
$r_ = mysql_query($s_);

//Vista pendientes
$s_ = "CREATE OR REPLACE view vw_pendientes as select folios.*, partes.padre, partes.no_parte, partes.tipo, partes.tipo_sub, partes.descripcion, partes.cantidad, ";
$s_.= "partes.costo, partes.total, partes.batch_id, partes.serial_unidad, partes.ubicacion, partes.docto_sap, partes.deficit from scrap_folios as folios, scrap_partes ";
$s_.= "as partes where folios.no_folio = partes.no_folio and folios.status='0' and folios.activo='1' order by folios.no_folio desc";
$r_ = mysql_query($s_);

//Vista rechazados
$s_ = "CREATE OR REPLACE view vw_rechazados as select folios.*, partes.padre, partes.no_parte, partes.tipo, partes.tipo_sub, partes.descripcion, partes.cantidad, ";
$s_.= "partes.costo, partes.total, partes.batch_id, partes.serial_unidad, partes.ubicacion, partes.docto_sap, partes.deficit from scrap_folios as folios, scrap_partes ";
$s_.= "as partes where folios.no_folio = partes.no_folio and folios.status='2' and folios.activo='1' order by folios.no_folio desc";
$r_ = mysql_query($s_);

//Vista todos
$s_ = "CREATE OR REPLACE view vw_todos as select folios.*, partes.padre, partes.no_parte, partes.tipo, partes.tipo_sub, partes.descripcion, partes.cantidad, ";
$s_.= "partes.costo, partes.total, partes.batch_id, partes.serial_unidad, partes.ubicacion, partes.docto_sap, partes.deficit from scrap_folios as folios, scrap_partes ";
$s_.= "as partes where folios.no_folio = partes.no_folio and folios.activo='1' order by folios.no_folio desc";
$r_ = mysql_query($s_); 

echo "LISTO!!"; ?>