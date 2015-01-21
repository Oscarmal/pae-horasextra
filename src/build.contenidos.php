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
function build_grid_consulta_autorizacion_1(){
	// Construye listado de horas extra capturadas
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo		=> 1
			,desc		=> 1
		);
	//$tabla = captura_listado_select($sqlData);	
	$tabla = select_listado_horas_capturadas($sqlData);	

	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas'
				//,'capturado_por'
				//,'capturado_el'
			);	
	if($tabla){	
		foreach ($tabla as $registro) {		
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
			}			
			if(is_null($data[n1_estatus])){
				$valor='Pendiente';
			}else{
				if($data[n1_estatus]==0){
						$valor='Rechazado';
					}
					else if($data[n1_estatus]==1){
						$valor='Aceptado';
					}
			}

			$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break;
		}
		
		
	}
	return $tbl_resultados;
}
function build_grid_consulta_autorizacion_2($data=array()){
// Construye listado de horas extra autorizadas
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
			,xls 		=> $data[xls]
		);
	
	$tabla = listado_select_autorizacion_2($sqlData);
	//dump_var($tabla);
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas'
			);
	if($tabla){
		foreach ($tabla as $registro) {		
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';
			}
	if($soloUno){
			if(is_null($data[n2_estatus])){
				$valor='Pendiente';
			}
			else{
					if($data[n2_estatus]==0){
					$valor='Rechazado';
				}
				else if($data[n2_estatus]==1){
					$valor='Aceptado';
				}
			}
		}
		else{
			if(is_null($registro[n2_estatus])){
				$valor='Pendiente';
			}
			else{
					if($registro[n2_estatus]==0){
					$valor='Rechazado';
				}
				else if($registro[n2_estatus]==1){
					$valor='Aceptado';
				}
			}
		}

		$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	
	return $tbl_resultados;
}
function build_grid_consulta_autorizacion_3(){
	// Construye listado de horas extra asignadas
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = listado_select_autorizacion_3($sqlData);	
	$campos = array(
			  	'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas'
				//,'capturado_por'
				//,'capturado_el'
				//,'asignado_por'
				//,'asignado_el'					
			);
	if($tabla){
		foreach ($tabla as $registro) {		
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}

			if($soloUno){
				if(is_null($data[n3_estatus])){
					$valor='Pendiente';
				}
				else{
						if($data[n3_estatus]==0){
						$valor='Rechazado';
					}
					else if($data[n3_estatus]==1){
						$valor='Aceptado';
					}
				}
			}
			else{
				if(is_null($registro[n3_estatus])){
					$valor='Pendiente';
				}
				else{
						if($registro[n3_estatus]==0){
						$valor='Rechazado';
					}
					else if($registro[n3_estatus]==1){
						$valor='Aceptado';
					}
				}
			}
			
			$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}	
	}
	
	return $tbl_resultados;
}
function build_grid_consulta_autorizacion_4(){
	// Construye listado de horas extra autorizadas

	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = listado_select_autorizacion_4($sqlData);	
	$campos = array(
			  	'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas'
			);
	if($tabla){
		foreach ($tabla as $registro) {		
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros

			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			if($soloUno){
				if(is_null($data[n4_estatus])){
					$valor='Pendiente';
				}
				else{
						if($data[n4_estatus]==0){
						$valor='Rechazado';
					}
					else if($data[n4_estatus]==1){
						$valor='Aceptado';
					}
				}
			}else{
				if(is_null($registro[n4_estatus])){
					$valor='Pendiente';
				}
				else{
						if($registro[n4_estatus]==0){
						$valor='Rechazado';
					}
					else if($registro[n4_estatus]==1){
						$valor='Aceptado';
					}
				}
			}
			
			$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}	
	}
	return $tbl_resultados;
}
function build_grid_consulta_autorizacion_5($data=array()){
/**
* Construye listado de horas extra autorizadas 
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = listado_select_autorizacion_5($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas'	
			);
	if($tabla){
		foreach ($tabla as $registro) {	
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			if($soloUno){
				if(is_null($data[n5_estatus])){
					$valor='Pendiente';
				}
				else{
						if($data[n5_estatus]==0){
						$valor='Rechazado';
					}
					else if($data[n5_estatus]==1){
						$valor='Aceptado';
					}
				}
			}
			else{
				if(is_null($registro[n5_estatus])){
					$valor='Pendiente';
				}
				else{
						if($registro[n5_estatus]==0){
						$valor='Rechazado';
					}
					else if($registro[n5_estatus]==1){
						$valor='Aceptado';
					}
				}
			}
		$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	return $tbl_resultados;
}
//*****************************************************************************************************************************************
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
//*****************************************************************************************************************************************
// REPORTES
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
	}		
}
function build_hitorial_usuario(){
	
	$tabla = historial_usuario($sqlData);	

	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	


		$tbl_resultados .= '<td>'.$data[id_horas_extra].'</td>';
		$tbl_resultados .= '<td>'.$data[nombre].'</td>';
		$tbl_resultados .= '<td>'.$data[paterno].'</td>';
		$tbl_resultados .= '<td>'.$data[fecha].'</td>';
		$tbl_resultados .= '<td>'.$data[hora_extra].'</td>';
		$tbl_resultados .= '<td>'.$data[estatus_fecha].'</td>';

		if($data[estatus]=='' || $data[estatus]==NULL){
			$tbl_resultados .= '<td>PENDIENTE</td>';
		}
		else{
			$tbl_resultados .= '<td>'.$data[estatus].'</td>';
		}
		//$tbl_resultados .= '<td>'.$data[id_autorizacion].'</td>';
		//$tbl_resultados .= '<td>'.$data[time_auto].'</td>';
		$tbl_resultados .= '<td>'.$data[horas_rechazadas].'</td>';
		$tbl_resultados .= '<td>'.$data[horas_simples].'</td>';
		$tbl_resultados .= '<td>'.$data[horas_dobles].'</td>';
		$tbl_resultados .= '<td>'.$data[horas_triples].'</td>';
		$tbl_resultados .= '<td>'.$data[id_concepto].'</td>';
		

		$tbl_resultados .= '</tr>';
		if($soloUno) break; 		
	}
	return $tbl_resultados;
}

//*****************************************************************************************************************************************
// AUTORIZACION
function buil_autorizacion_1(){
	// Construye grid de autorizaciones
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 0
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_1($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'capturado_por'
				,'capturado_el'
			);
	if($tabla){
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
	}
	return $tbl_resultados;
}

function build_grid_autorizacion_2($data=array()){
/**
* Construye listado de horas extra autorizadas
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_2($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'	
			);
	if($tabla){
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
	}
	return $tbl_resultados;
}

function build_grid_autorizacion_3($data=array()){
/**
* Construye listado de horas extra autorizadas
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_3($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'	
			);
	if($tabla){
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
	}
	return $tbl_resultados;
}

function build_grid_autorizacion_4($data=array()){
/**
* Construye listado de horas extra autorizadas
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_4($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'	
			);
	if($tabla){
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
	}
	return $tbl_resultados;
}

function build_grid_autorizacion_5($data=array()){
/**
* Construye listado de horas extra autorizadas
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_5($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'	
			);
	if($tabla){
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
	}
	return $tbl_resultados;
}
//*****************************************************************************************************************************************
// ADMINISTRACION
function build_grid_layout($data=array()){
/**
* Construye listado de horas extra autorizadas 
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_layout($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
			);
	if($tabla){
		foreach ($tabla as $registro) {	
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			$tbl_resultados .= '<td><span class="btn" onclick="layout('.$data[0].');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	return $tbl_resultados;
}

function build_grid_xls($data=array()){
/**
* Construye listado de horas extra que se incluiran en el XLS 
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_xls($sqlData);
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'periodo'	
			);
	if($tabla){
		foreach ($tabla as $registro) {	
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	return $tbl_resultados;
}

function build_grid_xls_lista($data=array()){
/**
* Construye listado XLS para regenerar 
*/
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_xls_lista($sqlData);
	$campos = array(
				 'id_empresa'
				,'empresa'
				,'xls'	
			);
	if($tabla){
		foreach ($tabla as $registro) {	
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			$tbl_resultados .= '<td><span class="btn" onclick="genera_xls_rebuild(\'regenera-xls-nomina\');"><img src="'.$Path[img].'excel.gif" width="20" /></span></td>';
			$tbl_resultados .= '<td><span class="btn" onclick="genera_xls_rebuild(\'regenera-xls-resumen\');"><img src="'.$Path[img].'excel.gif" width="20" /></span></td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	return $tbl_resultados;
}
/*O3M*/
?>