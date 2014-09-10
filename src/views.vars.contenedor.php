<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Manejador de Vistas y asignación de variables
* 
*/
require_once('views.vars.error.php');
// Modulo Padre
#Modulos
$modulos = array(
			 GENERAL 		=> 'views.vars.general.php'
			,CAPTURA 		=> 'views.vars.captura.php'
			,CONSULTAS 		=> 'views.vars.consultas.php'
			,AUTORIZACION 	=> 'views.vars.autorizacion.php'
			,REPORTES 		=> 'views.vars.reportes.php'
			,ADMIN 			=> 'views.vars.admin.php'
		);
// $modulo = strtoupper(enArray($modulo,$modulos));
#Vistas
$contenedor = array(
			 CONTENEDOR => 'frm_contenedor.html'
			,FRM_HEADER => 'frm_header.html'
			,FRM_MENU 	=> 'frm_menu.html'
			,FRM_FOOTER => 'frm_footer.html'
		);

// $visitas = MODULO => SECCIONES
$frm_vistas = array(
			 GENERAL => 
			 	array(
			 		 INICIO 		=> 'inicio.html'
			 	)
			,CAPTURA => 
			 	array(
			 		 CAPTURA 		=> 'captura.html'			 		
			 	)
			,CONSULTA => 
			 	array(
			 		 CONSULTA 		=> 'consulta.html'
			 	) 
			,AUTORIZACION => 
			 	array(
			 		 AUTORIZACION 	=> 'autorizacion.html'
			 	) 
			,REPORTES => 
			 	array(
			 		 REPORTES 		=> 'reportes.html'
			 		,REPORTE1 		=> 'reporte1.html'
			 		,REPORTE2 		=> 'reporte2.html'
			 		,REPORTE3 		=> 'reporte3.html'
			 	)
			,ADMIN => 
			 	array(
			 		 ADMIN 			=> 'admin.html'
			 	)
			,ERROR => 'error.html'
		);

# Comandos
function frm_vistas($cmd){
	global $contenedor; 
	$seccion = $cmd;
	if(array_key_exists($seccion,$contenedor)){
		$html = strtolower($contenedor[$seccion]);		
	}else{
		$html = $contenedor
		[ERROR];
	}
	return $html;
}

# Variables
function frm_vars($modulo, $seccion, $urlParams=array()){
	global $frm_vistas, $modulos;	
	$mod  = strtoupper(enArray($modulo,$modulos));
	$sec = strtoupper(enArray($seccion,$frm_vistas[$mod]));
	if($mod){
		$inc = $modulos[$mod];
	}
	if($sec){
		$vars = vars_frame($urlParams, $inc, $modulo, $seccion);
	}else{
		$vars = vars_frm_error($sec);
	}
	return $vars;
}

#############
// Funciones para asignar variables a cada vista
// $negocio => Logica de negocio; $texto => Mensajes de interfaz

function vars_frame($urlParams, $inc, $modulo, $seccion){
// Carga la vista del Contenedor principal
	global $var, $Path, $dic, $contenedor, $usuario;
	## Logica de negocio ##
	if(!file_exists($Path[src].$inc)){				
		print_error('El archivo no existe: '.$inc);
	}else{
		require_once($Path[src].$inc);	
		
		// FRM_HEADER
		$header_opc = array(
					 img_logo		=> $var[img_logo]
					,ico_user		=> $var[ico_user]
					,ico_exit		=> $var[ico_exit]
					,LINK_SALIR		=> '../site/?m='.$var[GENERAL].'&s='.$var[LOGIN].'&e=2'
				);
		$HEADER 	= contenidoHtml($contenedor[FRM_HEADER], $header_opc);
		// --
		// FRM_MENU
		require_once($Path[src].'build.menu.php');
		$menu = buildMenu(4);
		$menu_opc = array(	
					 MENU 			=> $menu		 
					,txt_opc1		=> $dic[general][inicio]
					,img_opc1		=> $var[menu_opc1]
					,LINK_OPC1		=> '../site/?m='.$var[GENERAL].'&s='.$var[INICIO]
					,txt_opc2		=> $dic[general][captura]
					,img_opc2		=> $var[menu_opc2]
					,LINK_OPC2		=> '../site/?m='.$var[CAPTURA].'&s='.$var[CAPTURA]
					,txt_opc3		=> $dic[general][autorizacion]
					,img_opc3		=> $var[menu_opc3]
					,LINK_OPC3		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION]
					,txt_opc4 		=> $dic[general][consulta]
					,img_opc4		=> $var[menu_opc4]
					,LINK_OPC4		=> '../site/?m='.$var[CONSULTA].'&s='.$var[CONSULTA]
					,txt_opc5 		=> $dic[general][reportes]
					,img_opc5		=> $var[menu_opc5]
					,LINK_OPC5		=> '../site/?m='.$var[REPORTES].'&s='.$var[REPORTES]
				);
		$MENU 		= contenidoHtml($contenedor[FRM_MENU], $menu_opc);
		// --	
		// FRM_FOOTER
		$footer_opc = array(ANIO => date('Y'));
		$FOOTER 	= contenidoHtml($contenedor[FRM_FOOTER], $footer_opc);
		// --	
		// FRM_CONTENIDO
		$vista_new 	= vistas($seccion);
		$tpl_data 	= tpl_vars($seccion,$urlParams); 
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data); 
		// --

		## Envio de valores ##
		$negocio = array(
					 MORE 			=> $tpl_data[MORE]				
					,FRM_HEADER		=> $HEADER
					,FRM_MENU 		=> $MENU
					,FRM_CONTENIDO	=> $CONTENIDO
					,FRM_FOOTER		=> $FOOTER
				);
		$texto = array(
					 salir 			=> $dic[general][salir]
					,usuario 		=> $dic[general][usuario]
					,user 			=> $usuario[nombre]
				);
		$data = array_merge($negocio, $texto);
		return $data;
	}
}
function vars_frm_error($cmd){
	global $dic;
	## Envio de valores ##
	$data = array(MENSAJE => $dic[error][mensaje].': '.$cmd);
	return $data;
}
?>