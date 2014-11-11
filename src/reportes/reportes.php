<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
// Lógica de negocio
if($in[auth]){
	if($ins[accion]=='grafico01'){
		// Extraccion de datos
		$sqlData = array( 
				 auth 		=> true 
				,id_empresa	=> $ins[id_empresa]
				,anio 		=> $ins[anio]
			);
		$datos = grafico01_select($sqlData);
		if($datos){
			$data = array();
			$totales = array();
			$empresas = array();
			foreach ($datos as $registro) {
				if(!is_array($registro)){
					$soloUno = true;
					$data = $datos;
					$regs = count($registro);
				}else{
					$soloUno = false;
					$data = $registro;
					$regs = count($datos);
				}
				$totales[anio] = $data [anio_fecha];
				$totales[capturadas] += $data [horas_capturadas];
				$totales[pendientes] += $data [horas_pendientes];
				$totales[autorizadas] += $data [horas_autorizadas];
				$totales[rechazadas] += $data [horas_rechazadas];
				$totales[dobles] += $data [horas_dobles];
				$totales[triples] += $data [horas_triples];
				$totales[semanas] += $data [tot_semanas];
				$totales[contador]++;
				$totales[regs]=$regs;				
				if($soloUno) break;
			}
			$success = true;
			$msj = "Con datos";
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, datos => $datos, totales => $totales, regs => $regs);
		$data = json_encode($data);
	}elseif($in[accion]=='rebuild_reporte01'){
		$tabla = build_reporte01($ins[id_empresa],$ins[anio]);
		if($tabla){			
			$success = true;
			$msj = "Con datos";
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, tabla => $tabla);
		$data = json_encode($data);
	}elseif($in[accion]=='rebuild_sel_anio'){
		$sel_anio = build_select_anios($ins[id_empresa]);
		if($sel_anio){			
			$success = true;
			$msj = "Con datos";
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, sel_anio => $sel_anio);
		$data = json_encode($data);
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