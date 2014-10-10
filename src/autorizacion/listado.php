<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].MODULO.'/xls.'.strtolower(MODULO).'.php');
// Lógica de negocio
if($in[auth]){
	if($ins[accion]=='insert'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				$estatus = ($vtmp[1]=='no')?'RECHAZADO':'ACEPTADO';
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,id_concepto 	=> $id_concepto
					,estatus 		=> $estatus
				);
				$success = autorizacion_insert($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}

			$xls = xsl_autorizaciones($ids);
			$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename]);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}		
	}elseif(!$ins[accion]){
		$error = array(error => 'Sin accion');
		$data = json_encode($error);
	}
}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>