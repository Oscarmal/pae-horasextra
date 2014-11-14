<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.login.php');
// Lógica de negocio
if(!empty($ins[usuario]) && !empty($ins[clave])){
	if($usuario = login($ins[usuario], $ins[clave])){
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('INICIO',1);
		$_SESSION[user]['id_usuario'] 		= $usuario[id_usuario];
		$_SESSION[user]['usuario'] 			= $usuario[usuario];
		$_SESSION[user]['activo'] 			= $usuario[activo];
		$_SESSION[user]['grupo'] 			= $usuario[grupo];
		$_SESSION[user]['id_personal']		= $usuario[id_personal];
		$_SESSION[user]['nombre']			= $usuario[nombreCompleto];
		$_SESSION[user]['empleado_num']  	= $usuario[empleado_num];
		$_SESSION[user]['email'] 			= $usuario[email];		
		$_SESSION[user]['id_empresa'] 		= $usuario[id_empresa];		
		$_SESSION[user]['id_empresa_nomina']= $usuario[id_empresa_nomina];	
		$_SESSION[user]['empresa'] 			= $usuario[empresa];
		$_SESSION[user]['pais'] 			= $usuario[pais];
		$_SESSION[user]['accesos']['mod1']	= $usuario[mod1];
		$_SESSION[user]['accesos']['mod2']	= $usuario[mod2];
		$_SESSION[user]['accesos']['mod3']	= $usuario[mod3];
		$_SESSION[user]['accesos']['mod4']	= $usuario[mod4];
		$_SESSION[user]['accesos']['mod5']	= $usuario[mod5];
		$_SESSION[user]['accesos']['mod6']	= $usuario[mod6];	
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