<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
set_time_limit(0);
require_once($Path[src].MODULO.'/dao.admin.php');
global $usuario,$db;
// Lógica de negocio
if($in[auth]){
	if($ins[accion]=='sincronizar'){
		// $sqlData = array(
		// 	 auth 			=> 1
		// 	,id_personal	=> $usuario[id_personal]
		// 	,id_empresa		=> $usuario[id_empresa]
		// 	,fecha 			=> fecha_form($in[fecha])
		// 	,horas 			=> $in[horas]
		// );
		//$success = captura_insert($sqlData);
		if($usuario[id_grupo]==0){
			$user='root';
			$success=select_view_vista_credenciales($user,$vacio);
		}
		else{
			$user='administrador';
			$id_empresa=$usuario[id_empresa_nomina];
			$success=select_view_vista_credenciales($user,$id_empresa);
		}
		$msj = ($success)?'Guardado':'No guardó';
			if($msj=='Guardado'){
				$valor=count($success);
				$query='';
				for($i=0; $i<=$valor-1; $i++){
					$id_empresa 			=	$success[$i][id_empresa];
					$id_number				=	$success[$i][id_number];
					$nombre					=	$success[$i][nombre];
					$position				=	$success[$i][position];
					$area					=	$success[$i][area];
					$rfc					=	$success[$i][rfc];
					$imss					=	$success[$i][imss];
					$ingreso				=	$success[$i][ingreso];
					$empresa 				=	$success[$i][empresa];
					$empresa_razon_social	=	$success[$i][empresa_razon_social];
					$id_empleado			=	$success[$i][id_empleado];

					$query.="INSERT INTO
						prueba_view_vista_credenciales
						SET
						id_empresa 				=	'$id_empresa',
						id_number 				=	'id_number',
						nombre 					=	'$nombre',
						position 				=	'$position',
						area 					=	'$area',
						rfc 					=	'$rfc',
						imss 					=	'$imss',
						ingreso 				=	'$ingreso',
						empresa 				=	'$empresa',
						empresa_razon_social 	=	'$empresa_razon_social',
						id_empleado 			=	'$id_empleado';\r\n";
				}
				
				$file=fopen('../../tmp/insert.sql', 'w');
				$query2=count($query);
				fwrite($file,$query);
				fclose($file);
				
				$host = $db[db_local_host];
				$user = $db[db_local_user];
				$pass = $db[db_local_pass];
				$base = $db[db_local_db1];
				truncate_vista_nomina();
				$insert=exec("mysql -h ".$host." -u ".$user." -p".$pass." ".$base." < ../../tmp/insert.sql");
				if ($insert === FALSE) {
				  print "Error en el insert";
				}
				else{
					unlink('../../tmp/insert.sql');
					select_sincronizacion_update();
				}
			}
			else{
				//echo 'No guardó';
			}
	}elseif(!$ins[accion]){
		$error = array(error => 'Sin accion');
		$data = json_encode($error);
	}		
	$data = array(success => $msj, message => $msj);
	$data = json_encode($data);
}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>