<?php session_name('o3m'); session_start(); include_once($_SESSION['header_path']);
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.login.php');
// Lógica de negocio
if(!empty($ins[usuario]) && !empty($ins[clave])){
	if($usuario = login($ins[usuario], $ins[clave])){
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('INICIO',1);
		$_SESSION['id_usuario'] 	= $usuario[id_usuario];
		$_SESSION['usuario'] 		= $usuario[usuario];
		$_SESSION['grupo'] 			= $usuario[grupo];
		$_SESSION['usuario'] 		= $usuario[usuario];
		$_SESSION['id_personal']	= $usuario[id_personal];
		$_SESSION['nombre']			= $usuario[nombreCompleto];
		$_SESSION['empleado_num']  	= $usuario[empleado_num];
		$_SESSION['email'] 			= $usuario[email];
		$_SESSION['empresa'] 		= $usuario[empresa];
		$_SESSION['pais'] 			= $usuario[pais];
		$_SESSION['mod1'] 			= $usuario[mod1];
		$_SESSION['mod2'] 			= $usuario[mod2];
		$_SESSION['mod3'] 			= $usuario[mod3];
		$_SESSION['mod4'] 			= $usuario[mod4];
		$_SESSION['mod5'] 			= $usuario[mod5];
		$_SESSION['mod6'] 			= $usuario[mod6];	
		$url = "?m=$modulo&s=$seccion";
		$success = true;
	}else{
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('LOGIN',1);
		$url = "?m=$modulo&s=$seccion&e=1";
		$success = false;		
	}
	$data = array(success => $success, url => $url);
	$data = json_encode($data);
}
// Resultado
echo $data;
/*O3M*/
?>