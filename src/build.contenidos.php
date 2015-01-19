<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/* O3M
* Funciones para construir contenidos HTML
* 
*/
require_once($Path[src].'captura/dao.captura.php');
require_once($Path[src].'autorizacion/dao.autorizacion.php');
require_once($Path[src].'consulta/dao.consulta.php');
require_once($Path[src].'reportes/dao.reportes.php');
//*****************************************************************************************************************************************

function build_grid_autorizaciones(){
	// Construye grid de autorizaciones
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 0
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = autorizaciones_listado_select($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
			);
	// $conceptos = conceptos_select(array(auth=>1));
	// foreach($conceptos as $concepto){
	// 	$opts .= '<option value="'.$concepto[id_concepto].'">'.$concepto[concepto].' - '.$concepto[clave].'</option>';
	// }
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
		}
		$tbl_resultados .= '<td align="center">
								<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
									<option value="" selected></option>
									<option value="si">Autorizar</option>
									<option value="no">Rechazar</option>
								</select>
							</td>';
		$tbl_resultados .= '<td align="center">
								<input type="checkbox" id="ok_'.$data[0].'" class="element-checkbox" style="display: none;">
								<div id="ico-'.$data[0].'" class="ico-autorizacion" title="Pendiente"></div>
							</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
function autorizacion_coordinador(){
	// Construye grid de autorizaciones
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 0
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = autorizaciones_listado_select_coordinador($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
			);
	
	// $conceptos = conceptos_select(array(auth=>1));
	// foreach($conceptos as $concepto){
	// 	$opts .= '<option value="'.$concepto[id_concepto].'">'.$concepto[concepto].' - '.$concepto[clave].'</option>';
	// }
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
		}
		$tbl_resultados .= '<td align="center">
								<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
									<option value="" selected></option>
									<option value="si">Aceptar</option>
									<option value="no">Rechazar</option>
								</select>
							</td>';
		$tbl_resultados .= '<td align="center">
								<input type="checkbox" id="ok_'.$data[0].'" class="element-checkbox" style="display: none;">
								<div id="ico-'.$data[0].'" class="ico-autorizacion" title="Pendiente"></div>
							</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}

function build_grid_autorizaciones_gerente($data=array()){
// Construye listado de horas extra autorizadas

	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = autorizaciones_listado_select_gerente($sqlData);	
	$campos = array(
			  	'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
				,'asignado_por'
				,'asignado_el'					
			);
	// $conceptos = conceptos_select(array(auth=>1));
	// foreach($conceptos as $concepto){
	// 	$opts .= '<option value="'.$concepto[id_concepto].'">'.$concepto[concepto].' - '.$concepto[clave].'</option>';
	// }
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
		}
		$tbl_resultados .= '<td align="center">
								<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
									<option value="" selected></option>
									<option value="si">Autorizar</option>
									<option value="no">Rechazar</option>
								</select>
							</td>';
		$tbl_resultados .= '<td align="center">
								<input type="checkbox" id="ok_'.$data[0].'" class="element-checkbox" style="display: none;">
								<div id="ico-'.$data[0].'" class="ico-autorizacion" title="Pendiente"></div>
							</td>';
	//	$tbl_resultados .= '<td><span class="btn" onclick="autorizar('.$data[0].');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
//*****************************************************************************************************************************************
// CONSULTA
function build_grid_capturadas(){
	// Construye listado de horas extra capturadas
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo		=> 1
			,desc		=> 1
		);
	//$tabla = captura_listado_select($sqlData);	
	$tabla = captura_listado_select_coordinador($sqlData);	
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
function build_grid_validacion($data=array()){
// Construye listado de horas extra autorizadas
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 1
			,orden		=> 'he_horas_extra.id_horas_extra DESC'
			,xls 		=> $data[xls]
		);
	//$tabla = autorizacion_listado_select($sqlData);	
	$tabla = autorizacion_listado_select_coordinador($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'				
				,'capturado_por'
				,'capturado_el'				
				,'autorizado_por'
				,'autorizado_el'
				// ,'horas_rechazadas'
				//,'estatus'
				//,'xls'
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
			/*if($campos[$i]=='xls' ){
				//$xls_nomina = ($usuario[grupo]<4)?'<br/><a href="#" onclick="xls_nomina(\''.$data[$campos[$i]].'\')" target="_self" title="Layout Nómina" class="link-xls">[Nómina]</a>':'';
				//$tbl_resultados .= ($data[$campos[$i]])?'<td ><a href="'.$Path[docsurl].'autorizacion/'.$data[$campos[$i]].'" target="_self" title="Descargar Archivo" class="link-xls">'.$data[$campos[$i]].'</a></td>':'<td>XLS Pendiente</td>';
				$tbl_resultados .= ($data[$campos[$i]])
					?'<td ><a href="#" onclick="xls(\''.$data[$campos[$i]].'\')" target="_self" title="Descargar Archivo" class="link-xls">'.$data[$campos[$i]].'</a>'
					.$xls_nomina.'</td>'
					:'<td>XLS Pendiente</td>';
			//}else{*/
			/**/
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';
			//}
		}
		if($registro[estatus]=='RECHAZADO'){
				$valor='Rechazado';
			}
			else if($registro[estatus]=='ACEPTADO'){
				$valor='Aceptado';
			}			
				$tbl_resultados .='<td>'.$valor.'</td>';
		//$tbl_resultados .= '<td><span class="btn" onclick="autorizar('.$data[0].');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
function build_grid_asignacion(){
	// Construye listado de horas extra asignadas
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = asignacion_listado_select($sqlData);	
	$campos = array(
			  	'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
				,'asignado_por'
				,'asignado_el'					
			);
	
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
		}
		if($registro[aut_estatus]=='RECHAZADO'){
			$valor='Rechazado';
		}
		else if($registro[aut_estatus]=='ACEPTADO'){
			$valor='Aceptado';
		}
		else{
			$valor='Pendiente';	
		}
		$tbl_resultados .='<td>'.$valor.'</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
function build_grid_aprobadas(){
	// Construye listado de horas extra autorizadas

	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'g.id_horas_extra DESC'
		);
	$tabla = aprobadas_listado_select($sqlData);	
	$campos = array(
			  //	'id_horas_extra'
				'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'autorizado_por'
				,'autorizado_el'
			);
	$count=count($tabla);
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		
		if($registro[id_horas_extra]){
			$tbl_resultados .= '<td>
									'.$registro[id_horas_extra].'
									<input type="hidden" id ="id_personal_'.$count.'" name="id_personal_'.$count.'" value="'.$registro[id_personal].'"/>
									<input type="hidden" id ="id_empresa_'.$count.'"  name="id_empresa_'.$count.'" value="'.$registro[id_empresa].'"/>
									<input type="hidden" id ="horas_rechazadas_'.$count.'"  name="horas_rechazadas_'.$count.'" value="'.$registro[horas_rechazadas].'"/>
									<input type="hidden" id ="horas_dobles_'.$count.'"  name="horas_dobles_'.$count.'" value="'.$registro[horas_dobles].'"/>
									<input type="hidden" id ="horas_triples_'.$count.'"  name="horas_triples_'.$count.'" value="'.$registro[horas_triples].'"/>
									<input type="hidden" id ="empleado_num_'.$count.'"  name="empleado_num_'.$count.'" value="'.$registro[empleado_num].'"/>
								</td>';	
		$count--;	
		}
		for($i=0; $i<count($campos); $i++){

			$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
		}
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
//*****************************************************************************************************************************************
function build_reporte01($id_empresa=false, $anio=false){
// Construye reporte general
	global $Path;
	$sqlData = array( auth => true, id_empresa => $id_empresa, anio => $anio );
	$tabla = reporte01_select($sqlData);
	$campos = array(
				 // 'id_empresa',
				'empresa'
				,'siglas'
				,'anio_fecha'
				,'horas_capturadas'				
				,'horas_pendientes'
				,'horas_autorizadas'				
				,'horas_rechazadas'
				,'horas_dobles'
				,'horas_triples'
				,'tot_semanas'
			);
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr >';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
		}	
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
function build_select_empresas(){
	global $usuario;
	$sqlData = array( auth => true, activo => 1 );
	$tabla = empresas($sqlData);	
	// $readonly = ($usuario[grupo]>1)?'readonly="readonly" onmouseover="this.disabled=true;" onmouseout="this.disabled=false;"':'';
	$objeto = "<select id='sel_empresa' name='sel_empresa' $readonly >";
	// $objeto .= ($usuario[grupo]<=1)?'<option value="" selected="selected">Todas</option>' : '' ;
	foreach ($tabla as $registro) {
		$data = (!is_array($registro))?$tabla:$registro; 
		$regs = (!is_array($registro))?count($tabla)/2:1; 
		for($i=0; $i<$regs; $i++){
			$objeto .='<option value="'.$data[id_empresa].'">'.$data[empresa].'</option>'; 
		if(!is_array($registro)) break;
		}
		if(!is_array($registro)) break;
	}
	$objeto .= "</select>";
	return $objeto;
}
function build_select_anios($id_empresa=false){
	$sqlData = array( auth => true, id_empresa => $id_empresa );
	$tabla = anios($sqlData);		
	$objeto = "<select id='sel_anio' name='sel_anio'>";	
	foreach ($tabla as $registro) {
		$soloUno = (!is_array($registro))?true:false; 
		$data = (!$soloUno)?$registro:$tabla;		
		for($i=0; $i<count($data)/2; $i++){				
			$objeto .='<option value="'.$data[anio].'">'.$data[anio].'</option>'; 
		}		
		if($soloUno) break;
	}
	$objeto .= (!$soloUno)?'<option value="" selected="selected">Todos</option>':'';		
	$objeto .= "</select>";
	return $objeto;
}
function build_grid_autorizaciones_aprobadas(){
	// Construye listado de horas extra autorizadas

	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'g.id_horas_extra DESC'
		);
	$tabla = autorizaciones_aprobadas($sqlData);	
	$campos = array(
			  //	'id_horas_extra'
				'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'autorizado_por'
				,'autorizado_el'
			);
	$count=count($tabla);
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		
		if($registro[id_horas_extra]){
			$tbl_resultados .= '<td>
									'.$registro[id_horas_extra].'
									<input type="hidden" id ="id_personal_'.$count.'" name="id_personal_'.$count.'" value="'.$registro[id_personal].'"/>
									<input type="hidden" id ="id_empresa_'.$count.'"  name="id_empresa_'.$count.'" value="'.$registro[id_empresa].'"/>
									<input type="hidden" id ="horas_rechazadas_'.$count.'"  name="horas_rechazadas_'.$count.'" value="'.$registro[horas_rechazadas].'"/>
									<input type="hidden" id ="horas_dobles_'.$count.'"  name="horas_dobles_'.$count.'" value="'.$registro[horas_dobles].'"/>
									<input type="hidden" id ="horas_triples_'.$count.'"  name="horas_triples_'.$count.'" value="'.$registro[horas_triples].'"/>
									<input type="hidden" id ="empleado_num_'.$count.'"  name="empleado_num_'.$count.'" value="'.$registro[empleado_num].'"/>
								</td>';	
		$count--;	
		}
		for($i=0; $i<count($campos); $i++){

			$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
		}
		$tbl_resultados .= '<td><span class="btn" onclick="autorizar('.$data[0].');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
		// $tbl_resultados .= '<td align="center">
		// 						<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
		// 							<option value="" selected></option>
		// 							<option value="1">1</option>
		// 							<option value="2">2</option>
		// 							<option value="3">3</option>
		// 							<option value="4">4</option>
		// 							<option value="5">5</option>
		// 						</select>
		// 					</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
//*****************************************************************************************************************************************
// SINCRONIZACION
function build_grid_usuarios(){
	$sqlData = array(
			 auth 			=> 1
			,id_empresa		=> $usuario[id_empresa]
			,id_number		=> $usuario[id_number]
			,activo 		=> 1
		);
	$tabla = select_view_nomina($sqlData);	

	$campos = array(
				 'empresa_razon_social'
				,'id_empleado'
				,'id_number'
				,'nombre'
				,'rfc'
				,'imss'
				,'empresa_razon_social'
			//	,'activo'

			);
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.utf8_encode($data[$campos[$i]]).'</td>';
		}	
		$tbl_resultados .= '<td>'.date('d/m/Y H:i:s').'</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}
function build_catalgo_empresa(){
	$catalgo_empresa=select_catalgos_empresa();
	$select.='<select name="empresa" id="empresa">
				<option value="0">Seleccione una Empresa</option>';
	foreach($catalgo_empresa as $empresa){
		$soloUno = (!is_array($empresa))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$empresa:$catalgo_empresa; #Seleccion de arreglo
		for($i=1; $i<count($data)/2; $i++){
			$select.='<option value='.$data[id_nomina].'>'.$data[nombre].'</option>';
		}
		if($soloUno) break;
	}
	$select.='</select>';
	return $select;
}
function build_catalgo_usuarios_grupo(){
	$catalgo_usuarios=select_catalgo_usuarios_grupo();
	
	$select.='<select name="usuario" id="usuario">';
	foreach($catalgo_usuarios as $usuario){
		if($usuario[id_grupo]==60){
			$select.='<option value='.$usuario[id_grupo].' selected>'.$usuario[grupo].'</option>';
		}
		else{
			$select.='<option value='.$usuario[id_grupo].'>'.$usuario[grupo].'</option>';
		}
	}
	$select.='</select>';
	return $select;
}
function build_select_empresas_tabla(){
	$empresa_tabla=select_empresas_tabla();

	$campos = array(
				 'id_empresa'
				,'nombre'
				,'siglas'
				,'razon'
				,'timestamp'
			);
	foreach ($empresa_tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$empresa_tabla; #Seleccion de arreglo	
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.utf8_encode($data[$campos[$i]]).'</td>';
		}	
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}

/**
* AUTORIZACION
*/

function build_grid_autorizacion_2($data=array()){
/**
* Construye listado de horas extra autorizadas
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_2($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'	
				// ,'n1_id_usuario'				

			);
	foreach ($tabla as $registro) {	
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
		}
		$tbl_resultados .= '<td align="center">
								<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
									<option value="" selected></option>
									<option value="si">Autorizar</option>
									<option value="no">Declinar</option>
								</select>
							</td>';
		$tbl_resultados .= '<td align="center">
								<input type="checkbox" id="ok_'.$data[0].'" class="element-checkbox" style="display: none;">
								<div id="ico-'.$data[0].'" class="ico-autorizacion" title="Pendiente"></div>
							</td>';
		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}



/*O3M*/
?>