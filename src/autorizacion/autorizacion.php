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
				$valor=explode('_',$vtmp[1]);
				$estatus = ($valor[0]=='no')?0:1;
				$argumento = mb_strtoupper($valor[1], 'UTF-8');
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
					,argumento 		=> $argumento
				);
				$success = insert_autorizacion_1($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif($in[accion]=='autorizacion1-popup'){
		// Extraccion de datos
		$sqlData = array(
			 auth 			=> true
			,id_horas_extra	=> $in[id_horas_extra]
		);
		$datos = select_layout_autorizacion_1($sqlData);
		// Deteccion de semana del año ISO8601
		$datos_semama = select_acumulado_semanal_2(array(
			 auth 			=> 1
			,id_empresa 	=> $datos[id_empresa]
			,id_personal	=> $datos[id_personal]
			,fecha 			=> $datos[fecha]
		));
		$semana_iso8601 = ($datos_semama[semana_iso8601])?$datos_semama[semana_iso8601]:$datos[semana_iso8601];
		$semana_horas	= ($datos_semama[tot_horas])?$datos_semama[tot_horas]:0;
		// Impresion de vista
		$vista_new 	= 'autorizacion/autorizar_popup.html';
		$tpl_data = array(
				 MORE 			=> incJs($Path[srcjs].strtolower(MODULO).'/autorizar_popup.js')
				,id 	 		=> $datos[id_horas_extra]
				,nombre	 		=> $datos[nombre_completo]
				,clave	 		=> $datos[empleado_num]
				,fecha	 		=> $datos[fecha]
				,horas	 		=> $datos[horas]
				,semana_iso 	=> $semana_iso8601
				,tot_horas		=> $semana_horas.' hrs.'
				,guardar 		=> 'Guardar'			
				,cerrar	 		=> 'Cerrar'			
				);		
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data);
		// Envio de resultado
		$success = true;
		$msj = ($success)?'Popup OK':'Popup Fail';
		$data = array(success => $success, message => $msj, html => $CONTENIDO);			
	}
	elseif($in[accion]=='autorizacion1-guardar'){
		if(!empty($ins[datos])){				
			$datos = explode('|',$in[datos]);
			foreach($datos as $dato){
				$data = explode('=',$dato);	
				$data_arr[$data[0]]=$data[1];
				// id_conceptos OJO:hardcode
				$id_concepto[0]=($data[0]=='rechazadas')?0:$id_concepto[0];
				$id_concepto[1]=($data[0]=='simples')?1:$id_concepto[1];
				$id_concepto[2]=($data[0]=='dobles')?2:$id_concepto[2];
				$id_concepto[3]=($data[0]=='triples')?3:$id_concepto[3];
			}			
			$id_horas_extra 	= $data_arr['id_horas_extra'];
			$dobles 			= $data_arr['dobles'];
			$triples 			= $data_arr['triples'];
			$rechazadas			= $data_arr['rechazadas'];
			$estatus			= ($dobles+$triples==0)?0:1;
			$argumento 			= mb_strtoupper($data_arr['argumento'], 'UTF-8');
			$sqlData = array(
						 auth 				=> true
						,id_horas_extra		=> $id_horas_extra
						,dobles 			=> $dobles
						,triples 			=> $triples
						,rechazadas 		=> $rechazadas
						,estatus 			=> $estatus
						,argumento 			=> $argumento
						,id_concepto 		=> $id_concepto[$i]
					);
			$success  =  insert_layout_autorizacion_1($sqlData);
			$msj = ($success)?'Guardado':'No guardó';			
			$data = array(success => $success, message => $msj);
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
				//$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				//$estatus = ($vtmp[1]=='no')?0:1;
				$valor=explode('_',$vtmp[1]);
				$estatus = ($valor[0]=='no')?0:1;
				$argumento = mb_strtoupper($valor[1], 'UTF-8');
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
					,argumento 		=> $argumento
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
				//$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				//$estatus = ($vtmp[1]=='no')?0:1;
				$valor=explode('_',$vtmp[1]);
				$estatus = ($valor[0]=='no')?0:1;
				$argumento = mb_strtoupper($valor[1], 'UTF-8');
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
					,argumento 		=> $argumento
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
				//$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				//$estatus = ($vtmp[1]=='no')?0:1;
				$valor=explode('_',$vtmp[1]);
				$estatus = ($valor[0]=='no')?0:1;
				$argumento = mb_strtoupper($valor[1], 'UTF-8');
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
					,argumento 		=> $argumento
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
				//$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				//$estatus = ($vtmp[1]=='no')?0:1;
				$valor=explode('_',$vtmp[1]);
				$estatus = ($valor[0]=='no')?0:1;
				$argumento = mb_strtoupper($valor[1], 'UTF-8');
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,estatus 		=> $estatus
					,argumento 		=> $argumento
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