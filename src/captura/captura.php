<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.captura.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
// Lógica de negocio
if($ins[accion]=='insert'){
	if(!empty($ins[horas]) && !empty($ins[fecha])){		
		$sqlData = array(
			 auth 			=> 1
			,id_personal	=> $usuario[id_personal]
			,id_empresa		=> $usuario[id_empresa]
			,fecha 			=> fecha_form($in[fecha])
			,horas 			=> $in[horas]
		);
		$success = captura_insert($sqlData);
		$msj = ($success)?'Guardado':'No guardó';
	}else{
		$success = false;
		$msj = "Sin guardar por falta de datos.";
	}		
	if($success){	
	// envío de correo
		if($html_tpl = email_tpl_captura($success)){
			// extraccion de datos
			$sqlData = array(
				 auth 			=> 1
				,id_horas_extra	=> $success
			);
			$data = select_correos($sqlData);
			$destinatarios[] = array(
				 email	=> $data[email]
				,nombre	=> $data[nombre_completo]
			);
			$destinatarios[] = array(
				 email	=> $data[s1_email]
				,nombre	=> $data[s1_nombre_completo]
			);
			$adjuntos[] = $Raiz[local].$cfg[path_img].'email_top.jpg';
			$tplData = array(
				 html_tpl 		=> $html_tpl
				,destinatarios 	=> $destinatarios
				,asunto 		=> 'Sistema de Horas Extra'
				,adjuntos 		=> $adjuntos
			);
			send_mail_smtp($tplData);
		}
	}
	$data = array(success => $success, message => $msj);
	$data = json_encode($data);
}
// Resultado
if($ins[accion]){
	echo $data;
}else{
	$error=array(error => 'Sin accion');
	echo json_encode($error);
}
/*O3M*/
?>