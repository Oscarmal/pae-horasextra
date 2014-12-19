<?php /*O3M*/
/**
* Descripcion:	Establece ambiente de trabajo para cada página
* Creación:		2014-06-11
* Modificación:	2014-09-01, 2014-12-02
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
require_once($Raiz[local].'common/php/inc.parse-cfg.php');
load_vars($Raiz[local].'common/cfg/system.cfg');
if($cfg[server_prod]){error_reporting(E_ERROR);}
// Establece variables
$Path[php] 		= $Raiz[local].$cfg[path_php];
$Path[js]		= $Raiz[url].$cfg[path_js];
$Path[css]		= $Raiz[url].$cfg[path_css];
$Path[img]		= $Raiz[url].$cfg[path_img];
$Path[log]		= $Raiz[local].$cfg[path_log];
$Path[docs]		= $Raiz[local].$cfg[path_docs];
$Path[docsurl]	= $Raiz[url].$cfg[path_docs];
$Path[tmp]		= $Raiz[local].$cfg[path_tmp];
$Path[tmpurl]	= $Raiz[url].$cfg[path_tmp];
$Path[html]		= $Raiz[local].$cfg[path_html];
$Path[src]		= $Raiz[local].$cfg[path_src];
$Path[srcjs]	= $Raiz[url].$cfg[path_src];
$Path[site]		= $Raiz[local].$cfg[path_site];
// Crea variable de sesion con ruta de header
if(!isset($_SESSION['header_path'])){$_SESSION['header_path'] = $Raiz[local].$cfg[php_header];}
// Prepara archivos de apoyo
require_once($Raiz[local].$cfg[php_functions]);
require_once($Raiz[local].$cfg[php_mysql]);
// require_once($Raiz[local].$cfg[php_postgres]);
require_once($Raiz[local].$cfg[php_tpl]);
require_once($Raiz[local].$cfg[path_php].'inc.constructHtml.php');
require_once($Path[src].'dao.online.php');
// Parsea parámetros obtenidos por URL y los pone en arrays: $in[] y $ins[]
parseFormSanitizer($_GET, $_POST); # $ins[]
parseForm($_GET, $_POST); # $in[]
// Cierra de Sesión de usuario
if($_SESSION[user][id_usuario] && $in[s]==$var[LOGIN] && $in[e]==2) { 
	unset($_SESSION[user][id_usuario]);
}
// Variables de usuario
$usuario[id_usuario]		= $_SESSION[user]['id_usuario'];
$usuario[usuario]			= $_SESSION[user]['usuario'];
$usuario[id_grupo]			= $_SESSION[user]['id_grupo'];
$usuario[id_personal]		= $_SESSION[user]['id_personal'];
$usuario[nombre]			= $_SESSION[user]['nombre'];
$usuario[empleado_num]		= $_SESSION[user]['empleado_num'];
$usuario[email]				= $_SESSION[user]['email'];
$usuario[empresa]			= $_SESSION[user]['empresa'];
$usuario[id_empresa]		= $_SESSION[user]['id_empresa'];
$usuario[id_empresa_nomina] = $_SESSION[user]['id_empresa_nomina'];
$usuario[pais]				= $_SESSION[user]['pais'];
$usuario[accesos][mod1]		= $_SESSION[user]['accesos']['mod1'];
$usuario[accesos][mod2]		= $_SESSION[user]['accesos']['mod2'];
$usuario[accesos][mod3]		= $_SESSION[user]['accesos']['mod3'];
$usuario[accesos][mod4]		= $_SESSION[user]['accesos']['mod4'];
$usuario[accesos][mod5]		= $_SESSION[user]['accesos']['mod5'];
$usuario[accesos][mod6]		= $_SESSION[user]['accesos']['mod6'];
$usuario[accesos][mod7]		= $_SESSION[user]['accesos']['mod7'];
$usuario[accesos][mod8]		= $_SESSION[user]['accesos']['mod8'];
$usuario[accesos][mod9]		= $_SESSION[user]['accesos']['mod9'];
$usuario[accesos][mod10]	= $_SESSION[user]['accesos']['mod10'];

# Regionalización
$pais_params = (!isset($_SESSION[pais_params]))?strtolower($cfg[path_pais_params]):strtolower($_SESSION[pais_params]);
pais_params($Raiz[local].$pais_params);
// switch(strtoupper($usuario[pais])){
// 	case 'MX' : $pais_params=$pais_params[mexico]; break;
// 	case 'PR' : $pais_params=$pais_params[peru]; break;
// 	default : $pais_params=$pais_params[mexico]; break;
// }


# Diccionario de idioma
$idioma = (!isset($_SESSION[idioma]))?strtoupper($cfg[idioma]):strtoupper($_SESSION[idioma]);
if($idioma=='EN'){
	$dicFile = $cfg[path_dic_en];
}else{
	$dicFile = $cfg[path_dic_es];
}
diccionario($Raiz[local].$dicFile);
// Valida autenticación de Usuario
if(!$_SESSION[user][id_usuario] && $in[s]!=$var[LOGIN]) { 
	header('location: '.$Raiz[url].'?m='.$var[GENERAL].'&s='.$var[LOGIN].'&e=2');
	exit();
}

#Log Txt | (nombre_archivo, usuario ID, usuario_nombre, usuario, nivel, ruta, URLparams)
if($cfg[log_onoff] && $in[s]!=$var[LOGIN]){
	$params = ($in) ? implode('&', array_map(function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $in, array_keys($in))) : '';
	#$params='';
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
#Limpiar carpeta \tmp
$ext = array('xlsx','xls','csv', 'doc', 'docx', 'rft', 'pdf', 'rar', 'zip', 'txt', 'json', 'xml');
@limpiarTmp($Path[tmp], $ext, 60);
/*O3M*/
?>