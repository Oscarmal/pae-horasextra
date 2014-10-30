<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/* O3M
* Funciones para construir contenidos HTML
* 
*/
require_once($Path[src].'captura/dao.captura.php');
require_once($Path[src].'autorizacion/dao.autorizacion.php');
require_once($Path[src].'consulta/dao.consulta.php');

// AUTORIZACION
function build_grid_autorizaciones(){
// Construye grid de autorizaciones
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 0
			,activo		=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = sin_autorizar_select($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
			);
	$conceptos = conceptos_select(array(auth=>1));
	foreach($conceptos as $concepto){
		$opts .= '<option value="'.$concepto[id_concepto].'">'.$concepto[concepto].' - '.$concepto[clave].'</option>';
	}
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
		}
		$tbl_resultados .= '<td align="center">
								<script>document.write(buildBtn(\'btnAutorizar_'.$data[id_horas_extra].'\',\'Autorizar\',\'autorizar('.$data[id_horas_extra].');\'));</script>
							</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}

// CONSULTA
function build_grid_capturadas(){
// Construye listado de horas extra capturadas
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo		=> 1
			,desc		=> 1
		);
	$tabla = captura_listado_select($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
			);		
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
		}
		if($soloUno) break;
		$tbl_resultados .= '</tr>';
	}
	return $tbl_resultados;
}

function build_grid_autorizadas(){
// Construye listado de horas extra autorizadas
	global $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = autorizacion_listado_select($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'				
				,'capturado_por'
				,'capturado_el'				
				,'horas_dobles'
				,'horas_triples'
				,'horas_rechazadas'
				,'xls'
			);
	$conceptos = conceptos_select(array(auth=>1));
	foreach($conceptos as $concepto){
		$opts .= '<option value="'.$concepto[id_concepto].'">'.$concepto[concepto].' - '.$concepto[clave].'</option>';
	}
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		for($i=0; $i<count($campos); $i++){
			if($campos[$i]=='xls' && !empty($data[$campos[$i]])){
				$tbl_resultados .= '<td ><a href="'.$Path[docsurl].'autorizacion/'.$data[$campos[$i]].'" target="_self" title="Descargar Archivo" class="link-xls">'.$data[$campos[$i]].'</a></td>';
			}else{
				$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
			}
		}
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
/*O3M*/
?>