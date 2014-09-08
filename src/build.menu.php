<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/* O3M
* Crea las opciones del MENU principal del sistema
* 
*/
function buildMenu($elementos=0){
	global $Path;
	for($i=2; $i<=$elementos+1; $i++){
		$link 	= 'LINK_OPC'.$i;
		$img 	= 'img_opc'.$i;
		$txt 	= 'txt_opc'.$i;
		$opc   .= '<li><a href="{'.$link.'}" target="_self"><img src="'.$Path[img].'{'.$img.'}" alt="" class="icono_dos"/>{'.$txt.'}</a></li>';
	}
	return $opc;
}
/*O3M*/
?>