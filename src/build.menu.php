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
		switch($i){
			case 2 : $submenu = '
				<ul>
		        	<li><a href="{LINK_OPC21}">{txt_opc21}</a></li>
		        	<li><a href="{LINK_OPC22}">{txt_opc22}</a></li>
		         </ul>';
				break;
			default: $submenu = '';
		}
		$opc   .= '<li><a href="{'.$link.'}" target="_self"><img src="'.$Path[img].'{'.$img.'}" alt="" class="icono_dos"/>{'.$txt.'}</a>'.$submenu.'</li>';
	}
	return $opc;
}
/*O3M*/
?>