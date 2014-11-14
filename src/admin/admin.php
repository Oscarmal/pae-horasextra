<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.admin.php');
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
		// $success = captura_insert($sqlData);
		$success = true;
		$msj = ($success)?'Guardado':'No guardó';
	}elseif(!$ins[accion]){
		$error = array(error => 'Sin accion');
		$data = json_encode($error);
	}		
	$data = array(success => $success, message => $msj);
	$data = json_encode($data);
}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>