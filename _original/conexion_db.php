<?php conectame_db("scrap_gdl");
foreach($_POST as $key => $val){
	$_POST[$key] = addslashes($val);
}
foreach($_GET as $key => $val){
	$_GET[$key] = addslashes($val);
}
foreach (array_merge($_GET, $_POST) as $key => $val) {
	global $$key;
	$$key = addslashes($val);
}
function conectame_db( $BD )
{	
	$mysql=mysql_connect('localhost','root','');
	if (!$mysql)
	{	echo "Can not connect mysql ".$BD;
		exit;
	}
	
	$mysql=mysql_select_db( $BD );
	if (!$mysql)
	{	echo "Can not select database ".$BD;
		exit;
	}
} ?>