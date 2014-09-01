<?php /*O3M*/
/**
* Descripcion:	Establece ambiente de trabajo para cada página
* Creación:		2014-06-11
* Modificación:	2014-09-01
* @author 		Oscar Maldonado - O3M
*
*/
// Establece zona horaria y tipo de codificación
date_default_timezone_set("America/Mexico_City");
header('Content-Type: text/html; charset=utf-8');
// Detección de ruta y definicion de paths de trabajo
require_once('inc.path.php');
$Raiz[local] = $_SESSION[RaizLoc];
$Raiz[url] = $_SESSION[RaizUrl];
$Raiz[sitefolder] = $_SESSION[SiteFolder];
// Parsea archivo.cfg y crea $cfg[], $db[], $var[]
require_once($Raiz['local'].'common\php\inc.parse-cfg.php');
load_vars($Raiz['local'].'common\cfg\system.cfg');
// Establece variables
$Path[php]=$Raiz[local].$cfg[path_php];
$Path[js]=$Raiz[url].$cfg[path_js];
$Path[css]=$Raiz[url].$cfg[path_css];
$Path[img]=$Raiz[url].$cfg[path_img];
$Path[log]=$Raiz[local].$cfg[path_log];
$Path[tmp]=$Raiz[local].$cfg[path_tmp];
$Path[html]=$Raiz[local].$cfg[path_html];
$Path[src]=$Raiz[local].$cfg[path_src];
$Path[srcjs]=$Raiz[url].$cfg[path_src];
$Path[site]=$Raiz[local].$cfg[path_site];
// Crea variable de sesion con ruta de header
if(!isset($_SESSION['header_path'])){$_SESSION['header_path'] = $Raiz[local].$cfg[php_header];}
// Prepara archivos de apoyo
require_once($Raiz[local].$cfg[php_functions]);
require_once($Raiz[local].$cfg[php_mysql]);
require_once($Raiz[local].$cfg[php_tpl]);
require_once($Raiz[local].$cfg[path_php].'inc.constructHtml.php');
require_once($Path[src].'dao.online.php');;
// Parsea parámetros obtenidos por URL y los pone en arrays: $in[] y $ins[]
parseFormSanitizer($_GET, $_POST); # $ins[]
parseForm($_GET, $_POST); # $in[]
// Diccionario de idioma
$idioma = (!isset($_SESSION[idioma]))?strtoupper($cfg[idioma]):strtoupper($_SESSION[idioma]);
if($idioma=='EN'){
	$dicFile = $cfg[path_dic_en];
}else{
	$dicFile = $cfg[path_dic_es];
}
diccionario($Raiz[local].$dicFile);
// Valida autentificación de Usuario
if(!$_SESSION[usuario] && $in[s]!=$var[LOGIN]) { 
	header('location: '.$Raiz[url].'?m='.$var[GENERAL].'&s='.$var[LOGIN].'&e=2');
	exit();
}
// Cierra de Sesión de usuario
if($_SESSION[usuario] && $in[s]==$var[LOGIN] && $in[e]==2) { 
	unset($_SESSION[usuario]);
}
// Variables de usuario
$usuario[id_usuario]		= $_SESSION['id_usuario'];
$usuario[usuario]			= $_SESSION['usuario'];
$usuario[grupo]				= $_SESSION['grupo'];
$usuario[id_personal]		= $_SESSION['id_personal'];
$usuario[nombre]			= $_SESSION['nombre'];
$usuario[empleado_num]		= $_SESSION['empleado_num'];
$usuario[email]				= $_SESSION['email'];
$usuario[empresa]			= $_SESSION['empresa'];
$usuario[pais]				= $_SESSION['pais'];
$usuario[mod1]				= $_SESSION['mod1'];
$usuario[mod2]				= $_SESSION['mod2'];
$usuario[mod3]				= $_SESSION['mod3'];
$usuario[mod4]				= $_SESSION['mod4'];
$usuario[mod5]				= $_SESSION['mod5'];
$usuario[mod6]				= $_SESSION['mod6'];

#Log Txt | (nombre_archivo, usuario ID, usuario_nombre, usuario, nivel, ruta, URLparams)
if($cfg[log_onoff] && $in[s]!=$var[LOGIN]){
	$params = ($in) ? implode('&', array_map(function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $in, array_keys($in))) : '';
	LogTxt('he_'.$usuario[empresa],$usuario[id_usuario],$usuario[nombre],$usuario[usuario],$usuario[grupo],$Raiz[local],$params);
}	
#Online
if($cfg[online_onoff] && $in[s]!=$var[LOGIN]){
	$ultimo_clic=time();
	if(online_select($usuario[id_usuario])){
		online_update($usuario[id_usuario], $ultimo_clic);
	}else{
		online_insert($usuario[id_usuario], $ultimo_clic);
	}
}
/*O3M*/
?>