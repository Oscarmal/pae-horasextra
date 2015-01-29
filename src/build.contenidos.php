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
									<option value="no">Declinar</option>
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
	$tabla = listado_select_autorizacion_1($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'sucursal'
				,'fecha'
				,'horas'
			);	
	if($tabla){	
		foreach ($tabla as $registro) {		
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
			}
			$estatus = (is_null($data[n1_estatus]))?99:$data[n1_estatus];
			switch ($estatus) {
				case 0:  $valor='<div style="color:#FF0000;">Rechazado</div>'; break;
				case 1:  $valor='<div style="color:#31B404;">Aceptado</div>';	break;				
				default: $valor='<div style="color:#DF7401;">Pendiente</div>'; break;
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
				,'sucursal'
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
		$estatus = (is_null($data[n2_estatus]))?99:$data[n2_estatus];
			switch ($estatus) {
				case 0:  $valor='<div style="color:#FF0000;">Rechazado</div>'; break;
				case 1:  $valor='<div style="color:#31B404;">Aceptado</div>';	break;				
				default: $valor='<div style="color:#DF7401;">Pendiente</div>'; break;
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
				,'sucursal'
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
			$estatus = (is_null($data[n3_estatus]))?99:$data[n3_estatus];
			switch ($estatus) {
				case 0:  $valor='<div style="color:#FF0000;">Rechazado</div>'; break;
				case 1:  $valor='<div style="color:#31B404;">Aceptado</div>';	break;				
				default: $valor='<div style="color:#DF7401;">Pendiente</div>'; break;
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
				,'sucursal'
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
			$estatus = (is_null($data[n4_estatus]))?99:$data[n4_estatus];
			switch ($estatus) {
				case 0:  $valor='<div style="color:#FF0000;">Rechazado</div>'; break;
				case 1:  $valor='<div style="color:#31B404;">Aceptado</div>';	break;				
				default: $valor='<div style="color:#DF7401;">Pendiente</div>'; break;
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
				,'sucursal'
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
			$estatus = (is_null($data[n5_estatus]))?99:$data[n5_estatus];
			switch ($estatus) {
				case 0:  $valor='<div style="color:#FF0000;">Rechazado</div>'; break;
				case 1:  $valor='<div style="color:#31B404;">Aceptado</div>';	break;				
				default: $valor='<div style="color:#DF7401;">Pendiente</div>'; break;
			}
		$tbl_resultados .= '<td>'.$valor.'</td>';
			$tbl_resultados .= '</tr>';
			if($soloUno) break; 		
		}
	}
	return $tbl_resultados;
}
function build_grid_consulta_autorizaciones(){
	global $usuario, $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus	=> 1
			,activo 	=> 1
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = listado_select_autorizaciones($sqlData);
	//dump_var($tabla);
	$campos = array(
				 'id_horas_extra'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'sucursal'
				,'fecha'
				,'horas'
			);
	$estatus=array( 'n1'
				,'nombre_completo'
				,'empleado_num'
				,'empresa'
				,'fecha'
				,'horas');
	if($tabla){
		foreach ($tabla as $registro) {	
			$tbl_resultados .= '<tr class="gradeA">';
			$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
			$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo
			for($i=0; $i<count($campos); $i++){
				$tbl_resultados .= ($data[$campos[$i]])?'<td>'.$data[$campos[$i]].'</td>':'<td>-</td>';		
			}
			$estatus1 = (is_null($data[n1_estatus]))?99:$data[n1_estatus];			
			switch ($estatus1) {
				case 0:  $n1='<div style="color:#FF0000;">Rechazado</div><div style="color:#084B8A;"><p>'.utf8_encode($data[n1_argumento]).'</p></div>'; 	break;
				case 1:  $n1='<div style="color:#31B404;">Aceptado</div>'; 	break;
				case 99: $n1='<div style="color:#DF7401;">Pendiente</div>';	break;
				default: $n1='-'; break;
			}
			$estatus2 = (is_null($data[n2_estatus]))?99:$data[n2_estatus];
			if($data[n1_estatus]==0){
				$estatus2=88;
			}
			switch ($estatus2) {
				case 0:  $n2='<div style="color:#FF0000;">Rechazado</div><div style="color:#084B8A;"><p>'.utf8_decode($data[n2_argumento]).'</p></div>'; 	break;
				case 1:  $n2='<div style="color:#31B404;">Aceptado</div>';	break;
				case 99: $n2='<div style="color:#DF7401;">Pendiente</div>';	break;
				default: $n2='-'; break;
			}
			$estatus3 = (is_null($data[n3_estatus]))?99:$data[n3_estatus];
			if($data[n2_estatus]==0){
				$estatus3=88;
			}
			switch ($estatus3) {
				case 0:  $n3='<div style="color:#FF0000;">Rechazado</div><div style="color:#084B8A;"><p>'.utf8_decode($data[n3_argumento]).'</p></div>'; 	break;
				case 1:  $n3='<div style="color:#31B404;">Aceptado</div>';	break;
				case 99: $n3='<div style="color:#DF7401;">Pendiente</div>';	break;
				default: $n3='-'; break;
			}
			$estatus4 = (is_null($data[n4_estatus]))?99:$data[n4_estatus];
			if($data[n3_estatus]==0){
				$estatus4=88;
			}
			switch ($estatus4) {
				case 0:  $n4='<div style="color:#FF0000;">Rechazado</div><div style="color:#084B8A;"><p>'.utf8_decode($data[n4_argumento]).'</p></div>'; 	break;
				case 1:  $n4='<div style="color:#31B404;">Aceptado</div>';	break;
				case 99: $n4='<div style="color:#DF7401;">Pendiente</div>';	break;
				default: $n4='-'; break;
			}
			$estatus5 = (is_null($data[n5_estatus]))?99:$data[n5_estatus];
			if($data[n4_estatus]==0){
				$estatus5=88;
			}
			switch ($estatus5) {
				case 0:  $n5='<div style="color:#FF0000;">Rechazado</div><div style="color:#084B8A;"><p>'.utf8_decode($data[n5_argumento]).'</p></div>'; 	break;
				case 1:  $n5='<div style="color:#31B404;">Aceptado</div>';	break;
				case 99: $n5='<div style="color:#DF7401;">Pendiente</div>';	break;
				default: $n5='-'; break;
			}
			$tbl_resultados .= '<td>'.$n1.'</td>';
			$tbl_resultados .= '<td>'.$n2.'</td>';
			$tbl_resultados .= '<td>'.$n3.'</td>';
			$tbl_resultados .= '<td>'.$n4.'</td>';
			$tbl_resultados .= '<td>'.$n5.'</td>';
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
	global $Path;
	$sqlData = array(
			 auth 			=> 1
			,id_empresa		=> $usuario[id_empresa]
			,id_number		=> $usuario[id_number]
			,activo 		=> 1
		);
	$tabla = select_view_nomina($sqlData);	
	//dump_var($tabla);

	$campos = array(
				 'id_nomina'
				,'empresa'
				,'id_number'
				,'id_empleado'
				,'nombre'
				,'rfc'
				,'imss'
				,'fecha_corte'

			);
	
	foreach ($tabla as $registro) {		
		$tbl_resultados .= '<tr class="gradeA">';
		$soloUno = (!is_array($registro))?true:false; #Deteccion de total de registros
		$data = (!$soloUno)?$registro:$tabla; #Seleccion de arreglo	
		for($i=0; $i<count($campos); $i++){
			$tbl_resultados .= '<td>'.utf8_encode($data[$campos[$i]]).'</td>';
		}	
		if($data[id_he_empresa]==''){
			$id_empresa=0;
		}else{
			$id_empresa=$data[id_he_empresa];
		}
		$tbl_resultados .= '<td><span class="btn" onclick="supervisores('.$data[id_personal].','.$id_empresa.');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
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
			$select.='<option value="'.$data[id_empresa].'"">'.utf8_encode($data[nombre]).'</option>';
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
			$select.='<option value='.$usuario[id_grupo].' selected>'.$usuario[id_grupo].' - '.$usuario[grupo].'</option>';
		}
		else{
			$select.='<option value='.$usuario[id_grupo].'>'.$usuario[id_grupo].' - '.$usuario[grupo].'</option>';
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
function build_catalgo_supervisores($nivel=1){
	global $usuario;
	$sqlData = array(
		 auth 			=> 1
	);
	$catalgo_supervisores=select_catalgo_supervisores($sqlData);	
	$select.='<select name="nivel'.$nivel.'" id="nivel'.$nivel.'">';		
	$select.='<option value="" selected>Seleccione el supervisor del Nivel-'.$nivel.'</option>';
	if($catalgo_supervisores){		
		foreach($catalgo_supervisores as $supervisor){
			$select.='<option value="'.$supervisor[id_personal].'" >'.utf8_encode($supervisor[nombre]).' - '.$supervisor[empleado_num].'</option>';
		}		
	}
	$select.='</select>';
	return $select;
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
			);
	if($tabla){
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
	global $Path;
	$sqlData = array(
			 auth 		=> true
			,estatus 	=> 0
			,orden		=> 'a.id_horas_extra DESC'
		);
	$tabla = select_autorizacion_1($sqlData);	
	$campos = array(
				 'id_horas_extra'
				,'empresa'
				,'sucursal'
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
				$tbl_resultados .= '<td>'.$data[$campos[$i]].'</td>';
			}
			// $tbl_resultados .= '<td align="center">
			// 						<select id="id_'.$data[0].'" name="id_'.$data[0].'" onChange="ok(this)" class="campos">
			// 							<option value="" selected></option>
			// 							<option value="si">Aceptar</option>
			// 							<option value="no">Declinar</option>
			// 						</select>
			// 					</td>';
			// $tbl_resultados .= '<td align="center">
			// 						<input type="checkbox" id="ok_'.$data[0].'" class="element-checkbox" style="display: none;">
			// 						<div id="ico-'.$data[0].'" class="ico-autorizacion" title="Pendiente"></div>
			// 						<span>
			// 							<input type="text" id="muestra_'.$data[0].'" style="display: none;" width="48">
			// 							<input type="hidden" id="asig_'.$data[0].'" value="0">
			// 						</span>
			// 					</td>';
			$tbl_resultados .= '<td><span class="btn" onclick="popup_autorizacion_1('.$data[0].');"><img src="'.$Path[img].'ico_edit.png" width="20" /></span></td>';
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
				,'sucursal'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'dobles'
				,'triples'
				,'rechazadas'
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
									<span>
										<input type="text" id="muestra_'.$data[0].'" style="display: none;" width="48">
										<input type="hidden" id="asig_'.$data[0].'" value="0">
									</span>
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
				,'sucursal'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'dobles'
				,'triples'
				,'rechazadas'
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
									<span>
										<input type="text" id="muestra_'.$data[0].'" style="display: none;" width="48">
										<input type="hidden" id="asig_'.$data[0].'" value="0">
									</span>
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
				,'sucursal'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'dobles'
				,'triples'
				,'rechazadas'
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
									<span>
										<input type="text" id="muestra_'.$data[0].'" style="display: none;" width="48">
										<input type="hidden" id="asig_'.$data[0].'" value="0">
									</span>
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
				,'sucursal'
				,'nombre_completo'
				,'empleado_num'
				,'fecha'
				,'horas'
				,'dobles'
				,'triples'
				,'rechazadas'
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
									<span>
										<input type="text" id="muestra_'.$data[0].'" style="display: none;" width="48">
										<input type="hidden" id="asig_'.$data[0].'" value="0">
									</span>
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
				,'dobles'
				,'triples'
				,'rechazadas'
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

//*****************************************************************************************************************************************
// E-MAIL
function email_tpl_captura($id_horas_extra){
	global $Path, $usuario;
	// Extraccion de datos
	$sqlData = array(
			 auth 			=> true
			,id_horas_extra => $id_horas_extra
		);
	$data = captura_select($sqlData);
	// Envia datos a plantilla html
	$vista_new 	= 'email/email_captura.html';
	$tpl_data = array(
			 TOP_IMG 		=> $Raiz[local].$cfg[path_img].'email_top.jpg'
			,TITULO 		=> 'Registro de Horas Extra'	
			,EMPLEADO_NUM 	=> $data[empleado_num]
			,EMPLEADO 		=> $data[nombre_completo]
			,FECHA_HE 		=> $data[fecha]
			,HORAS 			=> $data[horas]
			,CAPTURA 		=> $data[capturado_el]
			,LINK 			=> '<a href="http://201.149.12.180/adminhorasextra" target="_blank">Sistema Horas Extra</a>'
		);		
	$HTML = contenidoHtml($vista_new, $tpl_data);
	// Crea archivo html temporal
	$fname = $Path[tmp].$usuario[id_empresa].$usuario[id_usuario].date('YmdHis').'.html';
	$file = fopen($fname, "w");
	fwrite($file, $HTML);
	fclose($file);
	// Devuelve ruta del archivo tmp
	return $fname;
}

function email_tpl_autorizaciones($id_horas_extra, $nivel){
	global $Path, $usuario;
	// Extraccion de datos
	$sqlData = array(
			 auth 			=> true
			,id_horas_extra => $id_horas_extra
			,id_nivel		=> $nivel
		);
	$data = select_data_autorizaciones($sqlData);
	// Envia datos a plantilla html
	$vista_new 	= 'email/email_autorizacion_1.html';
	$tpl_data = array(
			 TOP_IMG 		=> $Raiz[local].$cfg[path_img].'email_top.jpg'
			,TITULO 		=> utf8_decode("Autorización Nivel $nivel de Horas Extra")
			,EMPLEADO_NUM 	=> $data[empleado_num]
			,EMPLEADO 		=> utf8_decode($data[nombre_completo])
			,FECHA_HE 		=> $data[fecha]
			,DOBLES 		=> $data[h_dobles]
			,TRIPLES 		=> $data[h_triples]
			,RECHAZADAS 	=> $data[h_rechazadas]
			,ARGUMENTO 		=> utf8_decode($data[argumento])
			,ESTATUS 	=> $data[estatus]
			,SUPERVISOR 	=> $data[supervisor]
			,CAPTURA 		=> $data[timestamp]
			,LINK 			=> '<a href="http://201.149.12.180/adminhorasextra" target="_blank">Sistema Horas Extra</a>'			
		);		
	$HTML = contenidoHtml($vista_new, $tpl_data);
	// Crea archivo html temporal
	$fname = $Path[tmp].$usuario[id_empresa].$usuario[id_usuario].date('YmdHis').'.html';
	$file = fopen($fname, "w");
	fwrite($file, $HTML);
	fclose($file);
	// Devuelve ruta del archivo tmp
	return $fname;
}

/*O3M*/
?>