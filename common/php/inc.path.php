<?php /*O3M*/
## Establece variables con ruta local
## $SiteFolder => El nombre de la carpeta del sitio: www/[Carpeta]
##
session_name('o3m');
session_start();
$PublicFolder="www";
$SiteFolder="template";
$DirLocal=getcwd();
$path=explode($SiteFolder,$DirLocal);
$RaizLoc=$path[0].$SiteFolder.'/';
$path=explode($PublicFolder,$DirLocal);
$path2=explode($SiteFolder,$path[1]);
$RaizUrl="http://".$_SERVER['HTTP_HOST'].$path2[0].$SiteFolder."/";
$RaizUrl=str_replace('\\','/',$RaizUrl);
$_SESSION['RaizLoc']=$RaizLoc;
$_SESSION['RaizUrl']=$RaizUrl;
$_SESSION['SiteFolder']=$SiteFolder;
/*O3M*/
?>