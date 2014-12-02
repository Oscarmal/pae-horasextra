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
				case 3 : 
						$opt = ($usuario[grupo]<3)?'<li><a href="#" onclick="location.href=\'{LINK_OPC32}\';" target="_self">{txt_opc32}</a></li>':'';
						$submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC31}\';" target="_self">{txt_opc31}</a></li>
			        	'.$opt.'
			         </ul>';
					break;
				case 4 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC41}\';" target="_self">{txt_opc41}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC42}\';" target="_self">{txt_opc42}</a></li>
			         </ul>';
					break;
				case 5 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC51}\';" target="_self">{txt_opc51}</a></li>
			         </ul>';
					break;
				case 6 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC61}\';" target="_self">{txt_opc61}</a></li>
			         </ul>';
					break;
				default: $submenu = ''; break;
			}
			$opc   .= '<li><a href="#" onclick="location.href=\'{'.$link.'}\';" target="_self"><img src="'.$Path[img].'{'.$img.'}" alt="" class="icono_dos"/>{'.$txt.'}</a>'.$submenu.'</li>';
		}
	}
	return $opc;
}
/*O3M*/
?>