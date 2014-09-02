<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Manejador de Vistas y asignación de variables
* 
*/
// Modulo Padre
define(MODULO, 'GENERAL');

#Contenidos
$contenidos = array(
			 INICIO 		=> 'inicio.html'
			,CAPTURA 		=> 'captura.html'
			,AUTORIZACION	=> 'autorizacion.html'
			,REPORTES 		=> 'reportes.html'
			);

# Comandos
function vistas($cmd){
	global $vistas;
	$modulo = strtolower(MODULO).'/';
	$comando = strtoupper(enArray($cmd,$vistas));	
	if(array_key_exists($comando,$vistas)){
		$html = $modulo.$vistas[$comando];
	}else{
		$html = $vistas[ERROR];
	}
	return $html;
}

# Variables
function tpl_vars($cmd, $urlParams=array()){
	global $vistas;
	$cmd = strtoupper(enArray($cmd,$vistas));
	if($cmd == 'INICIO'){
		$vars = vars_inicio($urlParams);
	}elseif($cmd == 'CAPTURA'){
		$vars = vars_captura($urlParams);
	}elseif($cmd == 'AUTORIZACION'){
		$vars = vars_autorizacion($urlParams);
	}elseif($cmd == 'REPORTES'){
		$vars = vars_reportes($urlParams);
	}else{
		$vars = vars_error($cmd);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz

function vars_inicio($urlParams){
	global $var, $Path, $dic, $usuario;
	## Logica de negocio ##
	## Envio de valores ##
	$negocio = array(
				 TITULO		=> 'Tidtulo'
				,ICONO		=> 'titulo_autorizaciones.png'
				,CONTENIDO	=> $usuario[nombre]
			);
	$texto = array();
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