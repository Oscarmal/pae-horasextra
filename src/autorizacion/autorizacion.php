<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].MODULO.'/xls.'.strtolower(MODULO).'.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
// Lógica de negocio
if($in[auth]){	

	/**
	* Autorización nivel 1
	*/
	if($ins[accion]=='autorizacion_1'){

	}
	/*1*/


	/**
	* Autorización nivel 2
	*/

	/*2*/


	/**
	* Autorización nivel 3
	*/

	/*3*/


	/**
	* Autorización nivel 4
	*/

	/*4*/


	/**
	* Autorización nivel 5
	*/

	/*5*/

}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>