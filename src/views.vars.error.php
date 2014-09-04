<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Mensaje de Error
* 
*/
// Modulo Padre

function print_error($msj){
	$data = array(MENSAJE => $msj);
	echo contenedorHtml('error.html', $data);
	die();
}