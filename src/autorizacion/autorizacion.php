<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].MODULO.'/xls.'.strtolower(MODULO).'.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
// Lógica de negocio

if($in[auth]){	
	/**
	* Autorización nivel 1
	*/
	if($in[accion]=='guardar_autorizacion_1'){	
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){				
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$estatus = ($vtmp[1]=='no')?0:1;
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
				);
				$success = insert_autorizacion_1($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				//$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	/*Fin1*/


	/**
	* Autorización nivel 2
	*/
	elseif($in[accion]=='guardar_autorizacion_2'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				$estatus = ($vtmp[1]=='no')?0:1;
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
				);
				$success = insert_autorizacion_2($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	/*Fin2*/


	/**
	* Autorización nivel 3
	*/
	elseif($in[accion]=='guardar_autorizacion_3'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				$estatus = ($vtmp[1]=='no')?0:1;
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
				);
				$success = insert_autorizacion_3($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	/*Fin3*/


	/**
	* Autorización nivel 4
	*/
	elseif($in[accion]=='guardar_autorizacion_4'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				$estatus = ($vtmp[1]=='no')?0:1;
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
				);
				$success = insert_autorizacion_4($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	/*Fin4*/


	/**
	* Autorización nivel 5
	*/
	elseif($in[accion]=='guardar_autorizacion_5'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				$estatus = ($vtmp[1]=='no')?0:1;
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
				);
				$success = insert_autorizacion_5($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}		
	}
	/*Fin5*/
	$data = json_encode($data);
}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>