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

			$xls = xsl_autorizaciones($ids);
			$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename]);
			$data = json_encode($data);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}
	}elseif($ins[accion]=='autorizacion-popup'){
		// Extraccion de datos
		$sqlData = array(
			 auth 			=> true
			,id_horas_extra	=> $ins[id_horas_extra]
		);
		$datos = capturados_select($sqlData);
		// Impresion de vista
		$vista_new 	= 'autorizacion/autorizar_popup.html';
		$tpl_data = array(
				 MORE 	 => incJs($Path[srcjs].strtolower(MODULO).'/autorizar_popup.js')
				,id 	 => $datos[id_horas_extra]
				,nombre	 => $datos[nombre_completo]
				,clave	 => $datos[empleado_num]
				,fecha	 => $datos[fecha]
				,horas	 => $datos[horas]
				,guardar => 'Guardar'			
				,cerrar	 => 'Cerrar'			
				);		
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data);
		// Envio de resultado
		$success = true;
		$msj = ($success)?'Guardado':'No guardó';
		$data = array(success => $success, message => $msj, html => $CONTENIDO);
		$data = json_encode($data);		
	}elseif($ins[accion]=='autorizacion-guardar'){
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
			$horas[0] = $data_arr['rechazadas'];
			$horas[1] = $data_arr['simples'];
			$horas[2] = $data_arr['dobles'];
			$horas[3] = $data_arr['triples'];				
			for($i=0; $i<=count($data_arr)-1; $i++){	
				if($horas[$i]){
					$estatus = (!$id_concepto[$i])?'RECHAZADO':'ACEPTADO';				
					// Save data in SQL
					$sqlData = array(
						 auth 			=> true
						,id_horas_extra	=> $id_horas_extra
						,horas 			=> $horas[$i]
						,id_concepto 	=> $id_concepto[$i]
						,estatus 		=> $estatus
					);
					$success = autorizacion_insert($sqlData);
				}
			}
			$msj = ($success)?'Guardado':'No guardó';
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