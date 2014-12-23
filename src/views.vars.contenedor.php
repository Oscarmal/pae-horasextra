<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
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
			,AUTORIZACION 	=> 'views.vars.autorizacion.php'
			,CONSULTA 		=> 'views.vars.consulta.php'
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
			,FRM_CONTENT=> 'frm_contenido.html'
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
			,AUTORIZACION => 
			 	array(
			 		 INDEX 			=> 'autorizacion.html'
			 		,AUTORIZACION_2 => 'autorizacion_2.html'
					,AUTORIZACION_3	=> 'autorizacion_3.html'
					,AUTORIZACION_4	=> 'autorizacion_4.html'
					,AUTORIZACION_5	=> 'autorizacion_5.html'
					,AUTORIZACION_6	=> 'autorizacion_6.html'
			 		// ,LAYOUT 		=> 'layout.html'
			 	)
			,CONSULTA => 
			 	array(
			 		 INDEX 			=> 'index.html'
			 		,CAPTURA 		=> 'captura_listado.html'
			 		,AUTORIZACION 	=> 'autorizacion_listado.html'
			 	)  
			,REPORTES => 
			 	array(
			 		 INDEX 			=> 'index.html'
			 		,REPORTE01 		=> 'rep_general.html'
			 		,REPORTE02	 	=> 'rep_mensual.html'
			 	)
			,ADMIN => 
			 	array(
			 		 INDEX 			=> 'index.html'
			 		,USUARIOS		=> 'usuarios.html'
			 		,SINCRONIZACION	=> 'sincronizacion.html'
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
		$html = $contenedor[ERROR];
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
	// print_r($inc);	die();
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
					,fecha_hoy		=> fechaHoy()
					,LINK_SALIR		=> '../site/?m='.$var[GENERAL].'&s='.$var[LOGIN].'&e=2'
				);
		$HEADER 	= contenidoHtml($contenedor[FRM_HEADER], $header_opc);
		// --
		// FRM_MENU
		require_once($Path[src].'build.menu.php');
		$menu = buildMenu(5);
		$menu_opc = array(	
					 MENU 			=> $menu
					// Inicio
					,txt_opc1		=> $dic[general][inicio]
					,img_opc1		=> $var[menu_opc1]
					,LINK_OPC1		=> '../site/?m='.$var[GENERAL].'&s='.$var[INICIO]
					// Captura
					,txt_opc2		=> $dic[general][captura]
					,img_opc2		=> $var[menu_opc2]
					,LINK_OPC2		=> '../site/?m='.$var[CAPTURA].'&s='.$var[CAPTURA]
					// Autorizaciones
					,txt_opc3		=> $dic[general][autorizacion]
					,img_opc3		=> $var[menu_opc3]
					,LINK_OPC3		=> '#'				
					,txt_opc31		=> $dic[autorizacion][titulo_autorizacion_coordinador]
					,LINK_OPC31		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION_2]
					// ,txt_opc32		=> $dic[autorizacion][layout_menu]
					// ,LINK_OPC32		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[LAYOUT]
					,txt_opc33		=> $dic[autorizacion][titulo_autorizacion_supervisor]
					,LINK_OPC33		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION_3]
					,txt_opc34		=> $dic[autorizacion][titulo_autorizacion_gerente]
					,LINK_OPC34		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION_4]
					//,txt_opc35		=> $dic[autorizacion][titulo_autorizacion_cliente]
					//,LINK_OPC35		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION_5]
					,txt_opc36		=> $dic[autorizacion][titulo_autorizacion_inplant]
					,LINK_OPC36		=> '../site/?m='.$var[AUTORIZACION].'&s='.$var[AUTORIZACION_6]
					// Consulta
					,txt_opc4 		=> $dic[general][consulta]
					,img_opc4		=> $var[menu_opc4]
					,LINK_OPC4		=> '#'
					,txt_opc41		=> $dic[consulta][captura_menu]
					,LINK_OPC41		=> '../site/?m='.$var[CONSULTA].'&s='.$var[CAPTURA]
					,txt_opc42		=> $dic[consulta][autorizacion_menu]
					,LINK_OPC42		=> '../site/?m='.$var[CONSULTA].'&s='.$var[AUTORIZACION]
					// Reportes
					,txt_opc5 		=> $dic[general][reportes]
					,img_opc5		=> $var[menu_opc5]
					,LINK_OPC5		=> '#'
					,txt_opc51		=> $dic[reportes][reporte01_menu]
					,LINK_OPC51		=> '../site/?m='.$var[REPORTES].'&s='.$var[REPORTE01]
					,txt_opc52		=> $dic[reportes][reporte02_menu]
					,LINK_OPC52		=> '../site/?m='.$var[REPORTES].'&s='.$var[REPORTE02]
					// Administración
					,txt_opc6 		=> $dic[general][admin]
					,img_opc6		=> $var[menu_opc6]
					,LINK_OPC6		=> '#'
					,txt_opc61		=> $dic[admin][sincronizar_menu]
					,LINK_OPC61		=> '../site/?m='.$var[ADMIN].'&s='.$var[SINCRONIZACION]					
				);
		$MENU 		= contenidoHtml($contenedor[FRM_MENU], $menu_opc);
		// --	
		// FRM_FOOTER
		$footer_opc = array(
					 ANIO 		=> date('Y')
					,IMG_FOOTER => $Path[img].$var[img_footer]
				);
		$FOOTER 	= contenidoHtml($contenedor[FRM_FOOTER], $footer_opc);
		// --	
		// FRM_CONTENIDO
		$vista_new 	= $contenedor[FRM_CONTENT];
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