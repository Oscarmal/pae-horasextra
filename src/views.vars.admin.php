<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/* O3M
* Manejador de Vistas y asignación de variables
* ADMIN
*/
// Modulo Padre
global $vistas, $contenidos, $icono;
$icono = $var[ico_03];
define(MODULO,'ADMIN');
require_once($Path[src].strtolower(MODULO).'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].'build.contenidos.php');
# Vistas HTML
$vistas = array(
		 INDEX 			=> 'index.html'
		,USUARIOS 		=> 'usuarios.html'
		,SINCRONIZACION => 'sincronizacion.html'
		,ALTA_USUARIO	=> 'alta_usuario.html'
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
	}elseif($cmd == 'USUARIOS'){
		$vars = vars_usuarios($cmd, $urlParams);
	}
	elseif($cmd == 'SINCRONIZACION'){
		$vars = vars_sincronizacion($cmd, $urlParams);
	}
	elseif($cmd == 'ALTA_USUARIO'){
		$vars = vars_alta_usuario($cmd, $urlParams);
	}
	else{
		$vars = vars_error($cmd);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz
function vars_sincronizacion($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##		
	$titulo 	= $dic[admin][sincronizar_titulo];

	$tbl_resultados = build_grid_usuarios();
	$data_contenido = array(
				TBL_RESULTS=> $tbl_resultados
		);
	$contenido 	= contenidoHtml(strtolower(MODULO).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	$negocio = array(
				 MORE 		=>  incJs($Path[srcjs].strtolower(MODULO).'/admin.js')
				,MODULE 	=> strtolower(MODULO)
				,SECTION 	=> ($seccion)								 
			);
	$texto = array(
				 ICONO 			=> $icono
				,TITULO			=> $titulo
				,CONTENIDO 		=> $contenido
				,sincronizar_usuarios =>$dic[admin][sincronizar_usuarios]
			);
	$data = array_merge($negocio, $texto);
	return $data;
}
function vars_alta_usuario($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##
	$titulo 	= $dic[admin][sincronizar_titulo];
	$data_contenido = array();
	$catalago_empresa=build_catalgo_empresa();
	$contenido 	= contenidoHtml(strtolower(MODULO).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	/*var_dump(MODULO);
	die();*/
	$negocio = array(
				 MORE 		=>  incJs($Path[srcjs].strtolower(MODULO).'/admin.js')
				,MODULE 	=> strtolower(MODULO)
				,SECTION 	=> ($seccion)								 
			);
	$texto = array(
				 ICONO 			=> $icono
				,TITULO			=> $titulo
				,CONTENIDO 		=> $contenido
				,sincronizar_usuarios =>$dic[admin][sincronizar_usuarios]
				,catalgo_empresa => $catalago_empresa
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