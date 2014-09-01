<?php /*O3M*/
/**
* Descripción:	Funciones globales
* Creación:		2014-04-25
* Modificación:	2014-08-25
* @author		Oscar Maldonado - O3M
*/

##################
#Funciones críticas - ¡Cuidado al editar!
##################

function parseFormSanitizer($g,$p){
#Load form information ($_GET/$_POST) into array $ins[], $cmd[] with sanitizer
#ejem: parse_form($_GET, $_POST);
	global $ins, $cmd;
	if(!empty($g)){
		$tvars = count($g);
		$vnames = array_keys($g);
		$vvalues = array_values($g);
	}elseif(!empty($p)){
		$tvars = count($p);
		$vnames = array_keys($p);
		$vvalues = array_values($p);
	}
	for($i=0;$i<$tvars;$i++){
		if($vnames[$i]=='cmd'){$cmd=$vvalues[$i];}
		$ins[$vnames[$i]]=sanitizerUrl($vvalues[$i]);
	}
}

function parseForm($g,$p){
#Load form information ($_GET/$_POST) into array $in[], $cmd[] without sanitizer
#ejem: parse_form($_GET, $_POST);
	global $in, $cmd;
	if(!empty($g)){
		$tvars = count($g);
		$vnames = array_keys($g);
		$vvalues = array_values($g);
	}elseif(!empty($p)){
		$tvars = count($p);
		$vnames = array_keys($p);
		$vvalues = array_values($p);
	}
	for($i=0;$i<$tvars;$i++){
		if($vnames[$i]=='cmd'){$cmd=$vvalues[$i];}
		$in[$vnames[$i]]=$vvalues[$i];
	}
}

function sanitizerUrl($param) {
#Sanitizes a url param
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($param)));
	return $clean;
}

function limpiarTmp($dir, $extension, $segundos){
    $t=time();
    $h=opendir($dir);
    while($file=readdir($h)){
        if(substr($file,-4)=='.'.$extension){
            $path=$dir.$file;
            if($t-filemtime($path)>$segundos)
                @unlink($path);
        }
    }
    closedir($h);
}

function LogTxt($nom, $u, $n, $g, $ub, $r, $params){
# Función para crear archivo log .txt con movimientos dentro del sistema
# acceso(nombre_archivo, usuario ID, usuario_nombre, usuario, nivel, ruta)
global $cfg;
$fec = date("Y-m-d");
if($_SERVER["HTTP_X_FORWARDED_FOR"])
{
	if($pos=strpos($_SERVER["HTTP_X_FORWARDED_FOR"]," "))
	{
		$ip_loc = substr($_SERVER["HTTP_X_FORWARDED_FOR"],0,$pos);
		$ip_pub = substr($_SERVER["HTTP_X_FORWARDED_FOR"],$pos+1);
	}else{$ip_pub=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	if($_SERVER["REMOTE_ADDR"])
		$proxy = $_SERVER["REMOTE_ADDR"];
}else{$ip_pub=$_SERVER["REMOTE_ADDR"];
}
$s = "|";	//separador
$page_ant = $_SERVER['HTTP_REFERER'];
$page = $_SERVER['PHP_SELF'];  
#$hostname=gethostbyaddr($ip_pub);
if($ip_pub!=$hostname){	$host = $hostname; }
$f = date("d-m-Y H:i:s");
$nav = $_SERVER['HTTP_USER_AGENT'];
$archivo = $r.$cfg[path_log].strtolower($nom)."_".$fec.".txt";
$fp = fopen($archivo, "a+");
# FECHA | USUARIO | NOMBRE | GRUPO | UBICACION | IP PUBLICA | IP LOCAL | NOMBRE DE PC | NAVEGADOR | URL ANTERIOR | URL ACTUAL
#$texto = $f."$s".$u."$s".$n."$s".$e."$s".$ub."$s".$ip_pub."$s".$ip_loc."$s".$host."$s".$nav."$s".$page_ant."$s".$page."\r\n";
$texto = $f."$s".$u."$s".$n."$s".$g."$s".$ub."$s".$ip_pub."$s".$ip_loc."$s".$host."$s".$nav."$s".$page_ant."$s".$page."$s".$params."\r\n";
$write = fputs($fp, $texto);
fclose($fp);
return ;
}

function breadcrumbs($home = 'Inicio', $separator = '>') {
## Crea la barra de navegación para el usuario
## @params $home String, $separatos String (puede ser un caracter o la ruta de una imagen)
	global $Raiz;
	$home=(!$Raiz['sitefolder']?ucwords($home):ucwords($Raiz['sitefolder']));
	#$path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
	$rootPath = explode($Raiz['sitefolder'].'/', $_SERVER['REQUEST_URI']);
	$path = array_filter(explode('/', $rootPath[1]));
	$base = $Raiz['url'];
	$breadcrumbs = array(':: <a rel="nofollow" href="'. $base .'">'. $home .'</a>');	
	$last = end(array_keys($path));	 
	foreach ($path as $x => $crumb) {
		$base.=$crumb.'/';
		$title = ucwords(str_replace(array('.php', '_', '-'), array('', ' ', ' '), $crumb));	 
		if ($x != $last) {
			$breadcrumbs[] = '<a rel="nofollow" href="'. $base .'">'. $title .'</a>';
		} else {
			$breadcrumbs[] = $title;
		}
	}
	if(strlen($separator)>3){
		$separator="&nbsp;<img src='$separator' border='0' align='middle'>&nbsp;";
	}
	return implode($separator, $breadcrumbs);
}

function plantillaRtf($Plantilla,$Ruta,$NuevoDoc,$Variables,$CharAbre,$CharCierra,$Valores){
	$NuevoDoc=$Ruta.$NuevoDoc;
	$txtplantilla=file_get_contents($Plantilla);
	$matriz=explode("sectd",$txtplantilla);
	$cabecera=$matriz[0]."sectd";
	$inicio=strlen($cabecera);
	$final=strrpos($txtplantilla,"}");
	$largo=$final-$inicio;
	$cuerpo=substr($txtplantilla,$inicio,$largo);
	$punt=fopen($NuevoDoc,"wb");
	fputs($punt,$cabecera);
	$Registros = count($Valores);
	for($i=1; $i<=$Registros; $i++){			
		$row=$Valores[$i];
		$despues=$cuerpo;
		for($x=0; $x<count($Variables); $x++){$nvariables[$x][0]=$CharAbre.$Variables[$x].$CharCierra;}
		$n=0;
		foreach($nvariables as $dato){
			$datosql=utf8_decode(str_replace('\\','Ñ',utf8_encode(utf8_decode($row[$n]))));
			$datortf=$dato[0];
			$despues=str_replace($datortf,$datosql,$despues);
			$despues=str_replace(strtoupper($datortf),$datosql,$despues);
			$despues=str_replace(strtolower($datortf),$datosql,$despues);
			$n++;
		}
		fputs($punt,$despues);
	}	
	fputs($punt,"}");
	fclose($punt);
	return $NuevoDoc;
}

function encrypt($input,$opt=false){
	global $cfg;
	$Key = $cfg[encrypt_key]; 
	if($opt){
		$output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($Key), $input, MCRYPT_MODE_CBC, md5(md5($Key))));
    	// $output = urlencode($output);
    	$output = md5($output);
	}else{
		// $input = urldecode($input);
		$output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($Key))), "\0");
    }
	return ($output);	
}

function siExiste($texto, $txtmd5){
// Compara una cadena sin codificar contra una en MD5
	// $encrypt = md5(encrypt($texto,1));
	$encrypt = encrypt($texto,1);
	$resultado = ($encrypt == $txtmd5)?true:false;
	return $resultado;
}

function enArray($valor,$array){
// Busca que exista el indice de un array y devuelve en nombre del indice
	foreach(array_keys($array) as $n){
		if(siExiste($n,$valor) && !$ok){
			$ok = true;
			$find = $n;
			break;
		}else{$find = false;}	
	}
	return $find;
}

function diccionario($filename='es.dic') {
#Load config information from config.ini file.		
	try {
		global $cfg, $dic;
		if (file_exists($filename)) {
	        if ($handle = fopen($filename, 'r')) {
	        	$varsList = explode(',',$cfg[modulos]);
	        	// $varsList = array('gral','captura','consultas','reportes','admin');
	            while (!feof($handle)) {
	                list($type, $name, $value) = preg_split("/\||=/", fgets($handle), 3);
					if (trim($type)!='#') { 
					#DIC vars
						$dic[trim($type)][trim($name)] = trim($value);
					}	
					if (in_array(trim($type),$varsList)) { 
					#Print for Debug
					 	$val.=$type.' | '.$name.' = '.$value."<br/>\n\r";
					}
	            }	            
	        }	        
			return $val;
		}else{
			$msj = "¡ERROR CRÍTICO!<br/> No se ha logrado cargar el archivo diccionario, por favor, contacte al administrador del sistema.<br/>";
	    	throw new Exception($msj, 1);    	
	    }	
	} catch (Exception $e) {		
		print($e->getMessage());
		return false;
	}	   
}

#-FIN Críticas-#

##################
#Funciones de apoyo
##################

function ceros($num=0, $digitos=1){
	if(strlen($num)<$digitos){
	    for($i=1; $i<=$digitos-strlen($num); $i++){
	        $cero .= '0';
	    }
	    $num = $cero.$num;
	}
    return $num;
}

function fechaHoy(){
	$dia=date("l");
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miercoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
	$dia2=date("d");
	$mes=date("F");
	if ($mes=="January") $mes="Enero";
	if ($mes=="February") $mes="Febrero";
	if ($mes=="March") $mes="Marzo";
	if ($mes=="April") $mes="Abril";
	if ($mes=="May") $mes="Mayo";
	if ($mes=="June") $mes="Junio";
	if ($mes=="July") $mes="Julio";
	if ($mes=="August") $mes="Agosto";
	if ($mes=="September")$mes="Septiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
	$anio=date("Y");
	return "$dia $dia2 de $mes del $anio";
}

function zipFile($File='', $Path='', $Delete=false){
##Compress in Zip file in the same directory
	chdir($Path);
	$f = explode('.',$File);
	for($i=0; $i<count($f)-1; $i++){
		$zipFile .= $f[$i];
	}
	$zipFile .= '.zip';
	$Zip = new ZipArchive;
	$Zip->open($zipFile, ZipArchive::CREATE);	
	$Zip->addFile($File);
	$Zip->close();
	if($Delete){
		unlink($File);
	}
	return $zipFile;
}

function incCss($filename){
	$cadena = '<link href="'.$filename.'" rel="stylesheet" type="text/css">';
	return $cadena;
}
function incJs($filename){
	$cadena = '<script type="text/javascript" src="'.$filename.'"></script>';
	return $cadena;
}
function incImg($filename,$w='100%',$h='100%'){
	$cadena = '<img src="'.$filename.'" width="'.$w.'" height="'.$h.'" border="0">';
	return $cadena;
}
/*O3M*/
?>
