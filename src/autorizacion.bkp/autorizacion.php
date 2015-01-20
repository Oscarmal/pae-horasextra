<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].MODULO.'/xls.'.strtolower(MODULO).'.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
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
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif($in[accion]=='autorizacion_update_horas_extra'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				//$id_concepto = ($vtmp[1]!='no')?$vtmp[1]:'';
				
				$estatus = ($vtmp[1]=='no')?'RECHAZADO':'ACEPTADO';
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
					,id_concepto 	=> $id_concepto
					,estatus 		=> $estatus
				);
				$success = autorizacion_update_horas_extra($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif($ins[accion]=='autorizacion-popup'){
		// Extraccion de datos
		$sqlData = array(
			 auth 			=> true
			,id_horas_extra	=> $ins[id_horas_extra]
		);
		$datos = capturados_select($sqlData);
		
		// Deteccion de semana del año ISO8601
		$datos_semama = select_acumulado_semanal(array(
			 auth 			=> 1
			,id_empresa 	=> $datos[id_empresa]
			,id_personal	=> $datos[id_personal]
			// ,empleado_num 	=> $datos[empleado_num]
			,fecha 			=> $datos[fecha]
		));
		$semana_iso8601 = ($datos_semama[semana_iso8601])?$datos_semama[semana_iso8601]:$datos[semana_iso8601];
		$semana_horas	= ($datos_semama[tot_horas])?$datos_semama[tot_horas]:0;
		// Impresion de vista
		$vista_new 	= 'autorizacion/autorizar_popup.html';
		$tpl_data = array(
				 MORE 	 => incJs($Path[srcjs].strtolower(MODULO).'/autorizar_popup.js')
				,id 	 => $datos[id_horas_extra]
				,nombre	 => $datos[nombre_completo]
				,clave	 => $datos[empleado_num]
				,fecha	 => $datos[fecha]
				,horas	 => $datos[horas]
				,semana_iso => $semana_iso8601
				,tot_horas	=> $semana_horas.' hrs.'
				,guardar => 'Guardar'			
				,cerrar	 => 'Cerrar'			
				);		
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data);
		// Envio de resultado
		$success = true;
		$msj = ($success)?'Guardado':'No guardó';
		$data = array(success => $success, message => $msj, html => $CONTENIDO);
		$data = json_encode($data);		
	}
	elseif($ins[accion]=='autorizacion-guardar'){
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
			$id_horas_extra = $data_arr['id_horas_extra'];
			$anio     = $data_arr['anio'];
			// $semana   = $data_arr['semana'];
			$horas[0] = $data_arr['rechazadas'];
			$horas[1] = $data_arr['simples'];
			$horas[2] = $data_arr['dobles'];
			$horas[3] = $data_arr['triples'];				
			for($i=0; $i<=count($data_arr)-1; $i++){	
				if($horas[$i]){
					//$estatus = (!$id_concepto[$i])?'RECHAZADO':'ACEPTADO';				
					// Save data in SQL
					$sqlData = array(
						 auth 			=> true
						,id_horas_extra	=> $id_horas_extra
						//,anio			=> $anio
						// ,semana			=> $semana
						,horas 			=> $horas[$i]
						,id_concepto 	=> $id_concepto[$i]
						//,estatus 		=> $estatus
					);
					//$success = autorizacion_insert($sqlData);
					$success  =  autorizacion_insert_supervsior($sqlData);
				}
			}
			$msj = ($success)?'Guardado':'No guardó';
			//$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename]);
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}		
	}
	elseif($in[accion]=='autorizacion_update_horas_supervisor'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				
				$estatus = ($vtmp[1]=='no')?'RECHAZADO':'ACEPTADO';
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,estatus 		=> $estatus
					,id_horas_extra	=> $id_horas_extra
				);
				$success = autorizacion_update_horas_supervisor($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif($in[accion]=='autorizacion_update_horas_gerente'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			foreach($datos as $dato){
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra = $idCampo[1];				
				
				$estatus = ($vtmp[1]=='no')?'RECHAZADO':'ACEPTADO';
				// Save data in SQL
				$sqlData = array(
					 auth 			=> true
					,estatus 		=> $estatus
					,id_horas_extra	=> $id_horas_extra
				);
				$success = autorizacion_update_horas_gerente($sqlData);
				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif($ins[accion]=='genera-xls'){
		$success = false;
		$nodata = true;
		// Extraccion de datos
		$sqlData = array(
			 auth 	=> true
			,activo => 1
			,xls	=> false
		);
		$datos = capturados_sin_xls($sqlData);	
		if($datos){
			$ids = array();
			foreach($datos as $registro){
				$data = (is_array($registro))?$registro:$datos;
				$ids [] = $data[0];
			}
			// Generacion de XLS
			// $success = ($xls = xsl_autorizaciones($ids))?true:false;
			$success = ($xls = xsl_nomina($ids, $in[semana]))?true:false;
			$msj = "Archivo generado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
		$data = json_encode($data);
	}
	elseif($ins[accion]=='regenera-xls'){
		$success = false;
		$nodata = true;
		// Extraccion de datos
		$sqlData = array(
			 auth 	=> true
			,activo => 1
			,xls	=> $in[xls]
		);
		$datos = build_xls($sqlData);	
		if($datos){
			$ids = array();
			foreach($datos as $registro){
				$data = (is_array($registro))?$registro:$datos;
				$ids [] = $data[0];
				if(!is_array($registro)) break;
			}
			// Generacion de XLS
			$success = ($xls = xsl_rebuild($ids))?true:false;
			$msj = "Archivo regenerado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
		$data = json_encode($data);
	}
	elseif($ins[accion]=='regenera-xls-nomina'){
		$success = false;
		$nodata = true;
		// Extraccion de datos
		$sqlData = array(
			 auth 	=> true
			,activo => 1
			,xls	=> $in[xls]
		);
		$datos = build_xls($sqlData);	
		if($datos){
			$ids = array();
			foreach($datos as $registro){
				$data = (is_array($registro))?$registro:$datos;
				$ids [] = $data[0];
				$xls = $data[xls];
				if(!is_array($registro)) break;
			}
			// Generacion de XLS
			$success = ($xls = rebuild_xsl_nomina($ids, $xls))?true:false;
			$msj = "Archivo regenerado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
		$data = json_encode($data);
	}
	elseif($ins[accion]=='layout-popup'){
		// Extraccion de datos
		$sqlData = array(
			 auth 			=> true
			,id_horas_extra	=> $ins[id_horas_extra]
		);
		$datos = capturados_select($sqlData);
		// Impresion de vista
		$vista_new 	= 'autorizacion/layout_popup.html';
		$tpl_data = array(
				 MORE 	 => incJs($Path[srcjs].strtolower(MODULO).'/layout_popup.js')
				,id 	 => $datos[id_horas_extra]
				,nombre	 => $datos[nombre_completo]
				,clave	 => $datos[empleado_num]
				,fecha	 => $datos[fecha]
				,horas	 => $datos[horas]
				,guardar => 'Guardar'			
				,cancelar	 => 'Cancelar'			
				);		
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data);
		// Envio de resultado
		$success = true;
		$msj = ($success)?'Guardado':'No guardó';
		$data = array(success => $success, message => $msj, html => $CONTENIDO);
		$data = json_encode($data);		
	}
	elseif($in[accion]=='insert_nomina'){
		if(!empty($ins[datos])){
			$datos = explode('|',$in[datos]);
			$ids = array();
			
			foreach($datos as $dato){
				
				$vtmp = explode('=',$dato);
				$idCampo = explode('_',$vtmp[0]);				
				$id_horas_extra 	= $idCampo[1];				
				$semana 			= $vtmp[1];
				$id_empresa			= $vtmp[2];
				$id_personal 		= $vtmp[3];
				$horas_rechazadas 	= $vtmp[4];
				$horas_dobles 		= $vtmp[5];
				$horas_triples 		= $vtmp[6];
				$empleado_num 		= $vtmp[7];
				$año 				= date('Y');
				$timestamp 			= date('Y-m-d H:i:s');
				// Save data in SQL
				if($horas_rechazadas==''){
				}else{
					$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
				 	,id_personal 	=> $id_personal
				 	,id_empresa 	=> $id_empresa
				 	,empleado_num 	=> $empleado_num
				 	,anio 			=> $año
				 	,semana 		=> $semana
				 	,horas 			=> $horas_rechazadas
				 	,id_concepto 	=> 1
				 	,timestamp 		=> $timestamp
				 	);
					$success = insert_nomina($sqlData);			
				}
				if($horas_dobles==''){
				}else{
						$sqlData = array(
						 auth 			=> true
						,id_horas_extra	=> $id_horas_extra
					 	,id_personal 	=> $id_personal
					 	,id_empresa 	=> $id_empresa
					 	,empleado_num 	=> $empleado_num
					 	,anio 			=> $año
					 	,semana 		=> $semana
					 	,horas 			=> $horas_dobles
						,id_concepto 	=> 2
						,timestamp 		=> $timestamp
					);
						$success = insert_nomina($sqlData);
				}
				if($horas_triples==''){
				}else{
					$sqlData = array(
					 auth 			=> true
					,id_horas_extra	=> $id_horas_extra
				 	,id_personal 	=> $id_personal
				 	,id_empresa 	=> $id_empresa
				 	,empleado_num 	=> $empleado_num
				 	,anio 			=> $año
				 	,semana 		=> $semana
				 	,horas 			=> $horas_triples
					,id_concepto 	=> 3
					,timestamp 		=> $timestamp
					);
					$success = insert_nomina($sqlData);
				}

				$msj = ($success)?'Guardado':'No guardó';	
				$ids[] = $id_horas_extra;
			}
			$data = array(success => $success, message => $msj);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}
	elseif(!$ins[accion]){
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