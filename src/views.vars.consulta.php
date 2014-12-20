<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/* O3M
* Manejador de Vistas y asignación de variables
* CONSULTA
*/
// Modulo Padre
global $vistas, $contenidos, $icono;
$icono = $var[ico_03];
define(MODULO,'CONSULTA');
require_once($Path[src].strtolower(MODULO).'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].'build.contenidos.php');
# Vistas HTML
$vistas = array(
		 INDEX 			=> 'index.html'
		,CAPTURA 		=> 'captura_listado.html'
		,AUTORIZACION 	=> 'autorizacion_listado.html'
	);

# Vistas
function vistas($cmd){
	global $vistas;
	$comando = strtoupper(enArray(MODULO,$vistas));	
	if(array_key_exists($comando,$vistas)){
		$html = $vistas[$comando];
	}else{
		$html = $vistas[ERROR];
	}
	return $html;
}

# Variables
function tpl_vars($cmd, $urlParams=array()){
	global $vistas;
	$cmd = strtoupper(enArray($cmd,$vistas));
	if($cmd == 'INDEX'){
		$vars = vars_index($cmd, $urlParams);
	}elseif($cmd == 'CAPTURA'){
		$vars = vars_captura_listado($cmd, $urlParams);
	}elseif($cmd == 'AUTORIZACION'){
		$vars = vars_autorizacion_listado($cmd, $urlParams);
	}else{
		$vars = vars_error($cmd);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz
function vars_captura_listado($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##		
	$titulo 	= $dic[consulta][captura_titulo];
	$tbl_resultados = build_grid_capturadas();
	$data_contenido = array(
				TBL_RESULTS=> $tbl_resultados
		);
	$contenido 	= contenidoHtml(strtolower(MODULO).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	$negocio = array(
				 MORE 		=> incJs($Path[srcjs].strtolower(MODULO).'/captura_listado.js')	
				,MODULE 	=> strtolower(MODULO)
				,SECTION 	=> ($seccion)				 
			);
	$texto = array(
				 ICONO 			=> $icono
				,TITULO			=> $titulo
				,CONTENIDO 		=> $contenido
			);
	$data = array_merge($negocio, $texto);
	return $data;
}

function vars_autorizacion_listado($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##		
	$titulo 	= $dic[autorizacion][titulo2];
	$tbl_resultados = build_grid_validacion();
	$data_contenido = array(
				TBL_RESULTS=> $tbl_resultados
		);
	$contenido 	= contenidoHtml(strtolower(MODULO).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	$negocio = array(
				 MORE 		=> incJs($Path[srcjs].strtolower(MODULO).'/autorizacion_listado.js')	
				,MODULE 	=> strtolower(MODULO)
				,SECTION 	=> ($seccion)				 
			);
	$texto = array(
				 ICONO 			=> $icono
				,TITULO			=> $titulo
				,CONTENIDO 		=> $contenido
			);
	$data = array_merge($negocio, $texto);
	return $data;
}

function vars_error($cmd){
	global $dic;
	## Envio de valores ##
	$data = array(MENSAJE => $dic[error][mensaje].': '.$cmd);
	return $data;
}
?>