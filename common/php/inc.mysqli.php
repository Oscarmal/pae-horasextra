<?php 
/**
*   Conexión a PHP-MySQL usando MySQLi
*   @author Oscar Maldonado
*   O3M
*/

function SQLConn() {
	global $db;
	$link = new mysqli($db[host],$db[user],$db[pass],$db[db2]);
	if ($link->connect_error) {
		echo "Error de Connexion ($link->connect_errno) $link->connect_error\n".$db[conn_dbi];
		exit;
	} else {
		return $link;
	}		
    $Result = 'ERROR: No puede conectarse con la base de datos';
    return $Result;
}

function SQLQuery($SQL){
//Ejecuta consultas de extracción
	global $db;
	if($db[db_onoff]){
		$Cmd=array('SELECT');	
		$vSql=explode(' ',$SQL);
		if(in_array(strtoupper($vSql[0]),$Cmd)){
		    try{
		    	$conn = SQLConn(); //Llama conexión
		    	$qry = $conn->query($SQL)or die(mysqli_connect_errno($conn).' -> '.mysqli_connect_error()); //Ejecuta query	    	 	
		    	if(mysqli_num_rows($qry)){
		    		$Result=mysqli_fetch_array($qry);
		    	}else{$Result=null;}
		    	mysqli_close($conn); //Cierra conexión
		    	return $Result;
		    }catch(PDOException $e){
		    	echo "ERROR: La consulta SQL esta vacía o tiene errores: ".$SQL;
		    	echo $e->getMessage();
		    	return false;
		    }
		}
	}else{
		return 'DEBUG: Base de datos apagada.';
	}
}

function SQLDo($SQL){
//Ejecuta consultas de modificación
	global $cfg, $db, $usuario;
	if($db[db_onoff]){
		$SQL = utf8_decode($SQL);
		$Cmd=array('INSERT', 'UPDATE', 'DELETE');
		$vSql=explode(' ',$SQL);
		if(in_array(strtoupper($vSql[0]),$Cmd)){
		    try{
		    	$conn = SQLConn(); //Llama conexión
		    	$qry = $conn->query($SQL)or die(mysqli_connect_errno($conn).' -> '.mysqli_connect_error()); //Ejecuta query	    	 			    	
		    	// Guardar LOGS
		    	if($cfg[querylog_onoff]){
			    	$Id = $conn->insert_id;
					$TotRows = $conn->affected_rows;
					if($TotRows){	
						$idtable=$Id;
						$action=strtoupper($vSql[0]);
						if($action=='INSERT'){
							$t=explode('INTO ',strtoupper($SQL));
							$t2=explode(' ',$t[1]);
							$table=strtolower($t2[0]);
						}				
						if($action=='UPDATE'){
							$t=explode(' ',strtoupper($SQL));
							$table=strtolower($t[1]);
						}				
						if($action=='DELETE'){
							$t=explode('FROM ',strtoupper($SQL));
							$t2=explode(' ',$t[1]);
							$table=strtolower($t2[0]);
						}
						if($table!=$db[tbl_online]){
							SQLLogs($table,$idtable,$action,$SQL,'',$usuario[id_usuario]);	
						}
					}
				}
		    	// --
		    	mysqli_close($conn); //Cierra conexión
		    	return true;
		    }catch(PDOException $e){
		    	echo "ERROR: La consulta SQL esta vacía o tiene errores: ".$SQL;
		    	return false;
		    }
		}else{
			echo "ERROR: La consulta es erronea.";
			return false;
		}
	}else{
		return 'DEBUG: Base de datos apagada.';
	}
}

function SQLLogs($table='',$idtable='',$action='', $query='', $desc='', $iduser=''){
## Guarda logs en DB
	global $db;
	if(!empty($table)){
		$timestamp = date('Y-m-d H:i:s');
		$query=addslashes($query);
		$tbl_logs = $db[tbl_logs];
		$SQL = "INSERT INTO $tbl_logs SET
				tablename='$table',
				id_table='$idtable',
				accion='$action',
				query='$query',
				txt='$desc',
				timestamp='$timestamp',
				id_usuario='$iduser';";			
		try{
	    	$conn = SQLConn(); //Llama conexión
	    	$qry = $conn->query($SQL)or die(mysqli_connect_errno($conn).' -> '.mysqli_connect_error()); //Ejecuta query	    	 			    	
	    	mysqli_close($conn); //Cierra conexión
	    	return true;
	    }catch(PDOException $e){
	    	echo "ERROR: Error en Tbl Logs: ".$SQL;
	    	return false;
	    }
	}else{return "Error al grabar logs en $tbl_logs";}
}
/*O3M*/
?>