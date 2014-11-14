<?php 
/**
*   Conexión a PHP-PostgreSQL 
*   @author Oscar Maldonado
*	Creación 	2014-11-11
*   O3M
*/

function pgConn() {
	global $db;
	// $link = new mysqli($db[host],$db[user],$db[pass],$db[db2]);
	$cadenaConexion = "host=$db[db_postgres_host] port=$db[db_postgres_port] dbname=$db[db_postgres_db] user=$db[db_postgres_user] password=$db[db_postgres_pass] connect_timeout=5";

	$pgLink = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
	// dump_var($pgLink);
	// if ($link->connect_error) {
	// 	echo "Error de Connexion ($link->connect_errno) $link->connect_error\n".$db[conn_dbi];
	// 	exit;
	// } else {
	// 	return $link;
	// }		
 //    $Result = 'ERROR: No puede conectarse con la base de datos';
 //    return $Result;
}

?>