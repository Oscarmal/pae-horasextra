<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Manejador de Vistas y asignación de variables
* CAPTURA
*/
// Modulo Padre
global $vistas, $contenidos, $icono;
$icono = $var[ico_01];
# Vistas CONTENEDOR
$vistas = array(
			 CAPTURA => 'frm_contenido.html'			
			,ERROR 	 => 'error.html'
			);
# Contenidos HTML
$contenidos = array(
		 CAPTURA 	=> 'captura.html'
		);

# Comandos
function vistas($cmd){
	global $vistas;
	$comando = strtoupper(enArray($cmd,$vistas));	
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
	if($cmd == 'CAPTURA'){
		$vars = vars_captura($cmd, $urlParams);
	}else{
		$vars = vars_error($cmd);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz

function vars_captura($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $contenidos;
	## Logica de negocio ##
	$titulo 	= $dic[captura][titulo];
	$contenido 	= contenidoHtml(strtolower($seccion).'/'.$contenidos[strtoupper($seccion)], array());

	## Envio de valores ##
	$negocio = array(
				 MORE 		=> ''				
				,ICONO 		=> $icono
				,TITULO		=> $titulo
				,CONTENIDO 	=> $contenido
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