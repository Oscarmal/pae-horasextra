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
						$opt50 = ($usuario[id_grupo]<60)?'<li><a href="#" onclick="location.href=\'{LINK_OPC31}\';" target="_self">{txt_opc31}</a></li>':'';
						$opt40 = ($usuario[id_grupo]<50)?'<li><a href="#" onclick="location.href=\'{LINK_OPC33}\';" target="_self">{txt_opc33}</a></li>':'';
						$opt30 = ($usuario[id_grupo]<40)?'<li><a href="#" onclick="location.href=\'{LINK_OPC34}\';" target="_self">{txt_opc34}</a></li>':'';
						//$opt20 = ($usuario[id_grupo]<30)?'<li><a href="#" onclick="location.href=\'{LINK_OPC35}\';" target="_self">{txt_opc35}</a></li>':'';
						$opt21 = ($usuario[id_grupo]<30)?'<li><a href="#" onclick="location.href=\'{LINK_OPC36}\';" target="_self">{txt_opc36}</a></li>':'';
						$submenu = '
					<ul>
			        	'.$opt50.'
			        	'.$opt40.'
			        	'.$opt30.'
			        	'.$opt20.'
			        	'.$opt21.'
			         </ul>';
					break;
				case 4 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC41}\';" target="_self">{txt_opc41}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC42}\';" target="_self">{txt_opc42}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC43}\';" target="_self">{txt_opc43}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC44}\';" target="_self">{txt_opc44}</a></li>
			         </ul>';
					break;
				case 5 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC51}\';" target="_self">{txt_opc51}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC53}\';" target="_self">{txt_opc53}</a></li>
			         </ul>';
					break;
				case 6 : $submenu = '
					<ul>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC61}\';" target="_self">{txt_opc61}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC62}\';" target="_self">{txt_opc62}</a></li>
			        	<li><a href="#" onclick="location.href=\'{LINK_OPC63}\';" target="_self">{txt_opc63}</a></li>
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