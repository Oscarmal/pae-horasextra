<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.captura.php');
// Lógica de negocio
if($ins[accion]=='insert'){
	if(!empty($ins[horas]) && !empty($ins[fecha])){
		$success = captura_insert(1, $usuario[id_personal], fecha_form($in[fecha]), $in[horas]);
		$msj = ($success)?'Guardado':'No guardó';
	}else{
		$success = false;
		$msj = "Sin guardar por falta de datos.";
	}		
	$data = array(success => $success, message => $msj);
	$data = json_encode($data);
}
// Resultado
if($ins[accion]){
	echo $data;
}else{
	$error=array(error => 'Sin accion');
	echo json_encode($error);
}
/*O3M*/
?>