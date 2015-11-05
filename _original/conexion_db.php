<?php conectame_db("scrap_gdl");
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