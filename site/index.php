<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);?>
<?php 
/* O3M
* Manejador de Vistas
* Dependencia: tpl.views.vars.php
* Modulos => $in[m]; Secciones => $in[s];
*/
// Valida parametro URL
if(!$in[m]){header('location: '.$Raiz[url]);}
// Modulos
define(MOD_GENERAL, 'views.vars.general.php');
define(MOD_CONTENEDOR, 'views.vars.contenedor.php');
$modulo = $in[m];
$seccion = $in[s];
// Distingue entre Login y Contendor
if(enArray($seccion,array(LOGIN=>''))){
	require_once($Path[src].MOD_GENERAL);	
	$vista 		= vistas($seccion);
	$tpl_data 	= tpl_vars($seccion,$ins);
	print(contenidoHtml($vista, $tpl_data));
}else{
	require_once($Path[src].MOD_CONTENEDOR);
	$vista 		= frm_vistas('CONTENEDOR');
	$tpl_data 	= frm_vars($modulo, $seccion,$ins);
	print(contenedorHtml($vista, $tpl_data));
}
/*O3M*/
?>
