<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Manejador de Vistas y asignación de variables
* CAPTURA
*/
// Modulo Padre
global $vistas, $contenidos, $icono;
$icono = $var[ico_01];
define(MODULO,'CAPTURA');
# Vistas HTML
$vistas = array(
		 INDEX 		=> 'index.html'
		,CAPTURA 	=> 'captura.html'
		,ERROR 	 	=> 'error.html'
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
		$vars = vars_captura($cmd, $urlParams);
	}else{
		$vars = vars_error($cmd);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz

function vars_index($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##	
	require_once($Path[src].strtolower(MODULO).'/dao.captura.php');
	$titulo 	= $dic[captura][index];
	$tabla = captura_select(1,0,0,0,0,0,1);		
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr>';
		for($i=0; $i<=count($registro); $i++){
			$tbl_resultados .= '<td>'.$registro[$i].'</td>';
		}
		$tbl_resultados .= '</tr>';
	}	
	$data_contenido = array(
				TBL_RESULTS=> $tbl_resultados
		);
	$contenido 	= contenidoHtml(strtolower(MODULO).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	$negocio = array(
				 // MORE 		=> incJs($Path[srcjs].strtolower($seccion).'/captura.js')	
				 MODULE 	=> strtolower(MODULO)
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

function vars_captura($seccion, $urlParams){
	global $var, $Path, $icono, $dic, $vistas, $usuario;
	## Logica de negocio ##
	$titulo 	= $dic[captura][titulo];
	$data_contenido = array();
	$contenido 	= contenidoHtml(strtolower($seccion).'/'.$vistas[strtoupper($seccion)], $data_contenido);
	## Envio de valores ##
	$negocio = array(
				 MORE 		=> incJs($Path[srcjs].strtolower($seccion).'/captura.js')	
				,MODULE 	=> strtolower(MODULO)
				,SECTION 	=> ($seccion)
			);
	$texto = array(
				 ICONO 			=> $icono
				,TITULO			=> $titulo
				,CONTENIDO 		=> $contenido				
				,id_usuario 	=> $usuario[id_usuario]
				,empleado_num 	=> $usuario[empleado_num]
				,empleado_nombre=> $usuario[nombre]
				,captura_fecha 	=> date('d/m/Y')
				,guardar		=> $dic[captura][guardar]
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