<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/* O3M
* Crea las opciones del MENU principal del sistema
* 
*/
function buildMenu($elementos=0){
	global $Path, $usuario;
	for($i=1; $i<=$elementos+1; $i++){
		$link 	= 'LINK_OPC'.$i;
		$img 	= 'img_opc'.$i;
		$txt 	= 'txt_opc'.$i;
		if($usuario[accesos][mod.$i]){
			switch($i){
				case 4 : $submenu = '
					<ul>
			        	<li><a href="{LINK_OPC41}">{txt_opc41}</a></li>
			        	<li><a href="{LINK_OPC42}">{txt_opc42}</a></li>
			         </ul>';
					break;
				case 5 : $submenu = '
					<ul>
			        	<li><a href="{LINK_OPC51}">{txt_opc51}</a></li>
			         </ul>';
					break;
				default: $submenu = ''; break;
			}
			$opc   .= '<li><a href="{'.$link.'}" target="_self"><img src="'.$Path[img].'{'.$img.'}" alt="" class="icono_dos"/>{'.$txt.'}</a>'.$submenu.'</li>';
		}
	}
	return $opc;
}
/*O3M*/
?>