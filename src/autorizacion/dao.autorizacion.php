<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-10-02
* @author 		Oscar Maldonado
*/
function capturados_select($data=array()){
	// Obtiene información capturada para realizar la autorizacion (popup)
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]'"
					,60 => "and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		if(array_key_exists('estatus',$data)){
			if(!$estatus){
				$filtro.=" and a.estatus IS NULL";
			}elseif($estatus==1){
				$filtro.=" and a.estatus IS NOT NULL";
			}elseif($estatus){
				$filtro.=" and a.estatus IN ($estatus)";
			}
		}
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ', b.paterno,' ',b.materno) as nombre_completo
					,b.empleado_num
					,a.estatus
					,d.usuario as validado_por
					,a.estatus_fecha as validado_el
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas					
					,c.usuario as capturado_por
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_usuarios] d ON a.id_usuario_aut=d.id_usuario
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}

	return $resultado;
}
function autorizacion_insert_supervsior($data=array()){
	// Inserta registros autorizados
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		// $semana 		= $data[semana];
		$horas 			= horas_int($data[horas]);
		$id_concepto 	= $data[id_concepto];
		// $estatus 		= $data[estatus];
		// $anio			= $data[anio];
		$timestamp = date('Y-m-d H:i:s');

		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra='$id_horas_extra',
					/*anio = '$anio',
					semana = '$semana',*/
					horas = '$horas',
					id_concepto = '$id_concepto',
					/*estatus ='$estatus',*/
					id_usuario = '$usuario[id_usuario]',
					timestamp = '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
function autorizacion_update_horas_extra($data=array()){
	// Inserta registros autorizados
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		//$semana 		= $data[semana];
		//$horas 			= horas_int($data[horas]);
		//$id_concepto 	= $data[id_concepto];
		$estatus 		= $data[estatus];
		//$anio			= $data[anio];

		$timestamp = date('Y-m-d H:i:s');

		$sql = "UPDATE 
					$db[tbl_horas_extra] 
				SET
					id_usuario_aut = $usuario[id_usuario],
					estatus_fecha  = '$timestamp',
					estatus        = '$estatus'
				WHERE 
					id_horas_extra = $id_horas_extra;";
					//echo $sql;
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
function autorizacion_update($data=array()){
	// Actualiza datos al generar archivo xls
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$campos = array();
		$timestamp = date('Y-m-d H:i:s');
		$id_autorizacion = (is_array($data[id_autorizacion]))?implode(',',$data[id_autorizacion]):$data[id_autorizacion];
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];		
		$campos [] = ($data[id_concepto])?"a.id_concepto='$data[id_concepto]'":'';
		$campos [] = ($data[estatus])?"a.estatus='$data[estatus]'":'';
		$campos [] = ($data[xls])?"a.xls='$data[xls]'":'';
		$campos [] = ($data[semana])?"a.semana='$data[semana]'":'';
		#$campos [] = "a.id_usuario='$usuario[id_usuario]'";
		#$campos [] = "a.timestamp='$timestamp'";				
		$campos = implode(',',array_filter($campos));
		$filtro .= filtro_grupo(array(
						 10 => ''
						,20 => "and b.id_empresa='$usuario[id_empresa]'"
						,30 => "and b.id_empresa='$usuario[id_empresa]'"
						,40 => "and b.id_empresa='$usuario[id_empresa]'"
						,50 => "and b.id_empresa='$usuario[id_empresa]'"
						,60 => "and a.id_usuario='$usuario[id_usuario]'"
				));		
		$filtro .= ($id_autorizacion)?" and a.id_autorizacion IN ($id_autorizacion)":'';
		$filtro	.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		if(!empty($campos)){
			$sql = "UPDATE $db[tbl_autorizaciones] a
					LEFT JOIN $db[tbl_horas_extra] b ON a.id_horas_extra=b.id_horas_extra			
					SET $campos
					WHERE 1 $filtro
					;";
			$resultado = (SQLDo($sql))?true:false;
		}
	}
	return $resultado;
}
function autorizacion_update_horas_gerente($data=array()){
	// Inserta registros autorizados
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$estatus = $data[estatus];
		$id_horas_extra	= $data[id_horas_extra];
		$timestamp = date('Y-m-d H:i:s');
		$usuario[id_usuario];
		
		$sql = "UPDATE 
					$db[tbl_autorizaciones] 
				SET
					aut_estatus 	= '$estatus',
					aut_timestamp  	= '$timestamp',
					aut_id_usuario  = $usuario[id_usuario]
				WHERE 
					id_horas_extra = $id_horas_extra;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
function conceptos_select($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db;
		$id_concepto 	= $data[id_concepto];
		$activo			= $data[activo];				
		$filtro = ($activo)?" and a.activo='1'":'';
		$filtro	.= ($id_concepto)?" and a.id_concepto='$id_concepto'":'';
		$sql = "SELECT a.id_concepto, a.concepto, a.clave, a.valor
				FROM $db[tbl_conceptos] a
				WHERE 1 and activo=1 $filtro 
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function xls_select($data=array()){
	// Contenido de XLS
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$anio			= (is_array($data[anio]))?implode(',',$data[anio]):$data[anio];
		$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and d.id_usuario='$usuario[id_usuario]'"
					,40 => "and d.id_usuario='$usuario[id_usuario]'"
					,60 => "and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		$filtro.= ($anio)?" and d.anio IN ($anio)":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus IN ($estatus)";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NULL";
		}
		$filtro.= ($xls)?" and d.xls IN ($xls)":'';
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,c.usuario as capturado_por
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					/*,d.xls*/	
					,d.anio	
					,d.semana			
					,f.usuario as autorizado_por
					,DATE_FORMAT(d.timestamp, '%d/%m/%Y %H:%i:%s') as autorizado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario 
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function capturados_sin_xls($data=array()){
	// Obtiene ID's que no tengan valor en campo [xls]
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;		
		// $xls			= 
		if(is_array($data[xls])){
			$xls = implode(',',$data[xls]);
			$xls = " and d.xls IN ($xls)";
		}elseif(!$data[xls]){
			$xls = ' and IFNULL(d.xls,"")=""';
		}else{
			$xls = " and d.xls='$data[xls]'";
		}
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= " and d.estatus IS NOT NULL";
		$filtro.= ($xls) ? $xls : '';
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,d.xls
					,a.id_usuario
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function sin_autorizar_select($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus IN ($estatus)";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NOT NULL";
		}elseif($estatus==0){
			$filtro.=" and d.estatus IS NULL";
		}
		$filtro.= ($xls)?" and d.xls IN ($xls)":'';		
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=1,d.horas,NULL)))),'%H:%i') AS horas_simples
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					,d.xls
					,c.usuario as capturado_por
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,f.usuario as autorizado_por
					,DATE_FORMAT(d.timestamp, '%d/%m/%Y %H:%i:%s') as autorizado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario 
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function build_xls($data=array()){
	// Obtiene ID's que no tengan valor en campo [xls]
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;		
		// $xls			= 
		if(is_array($data[xls])){
			$xls = implode(',',$data[xls]);
			$xls = " and d.xls IN ($xls)";
		}elseif(!$data[xls]){
			$xls = ' and IFNULL(d.xls,"")=""';
		}else{
			$xls = " and d.xls='$data[xls]'";
		}
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= " and d.estatus IS NOT NULL";
		$filtro.= ($xls) ? $xls : '';
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,d.xls
					,a.id_usuario
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function nomina_xls($data=array()){
	// Obtiene ID's que no tengan valor en campo [xls]
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;	
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];	
		// $xls	
		if(is_array($data[xls])){
			$xls = implode(',',$data[xls]);
			$xls = " and a.xls IN ($xls)";
		}elseif(!$data[xls]){
			$xls = ' and IFNULL(a.xls,"")=""';
		}else{
			$xls = " and a.xls='$data[xls]'";
		}
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and c.id_empresa='$usuario[id_empresa]'"
					,30 => "and c.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
					,40 => "and c.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
					,50 => "and c.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
					,60 => "and c.id_empresa='$usuario[id_empresa]' and c.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= " and a.estatus IS NOT NULL";
		$filtro.= ($xls) ? $xls : '';
		$filtro.= ($activo)?" and c.activo IN ($activo)":'';
		// $grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY c.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY c.id_horas_extra ASC';
		$sql = "SELECT d.empleado_num,					
					a.semana, 
					b.clave,
					a.horas/10000 as horas
				FROM $db[tbl_autorizaciones] a
				LEFT JOIN $db[tbl_conceptos] b on a.id_concepto=b.id_concepto
				LEFT JOIN $db[tbl_horas_extra] c ON a.id_horas_extra=c.id_horas_extra
				LEFT JOIN $db[tbl_personal] d ON c.id_personal=d.id_personal
				WHERE 1 and a.id_concepto!=0 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function autorizaciones_listado_select($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus IN ($estatus)";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NOT NULL";
		}elseif($estatus==0){
			$filtro.=" and d.estatus IS NULL";
		}
		// $filtro.= ($xls)?" and d.xls IN ($xls)":'';
		if($xls=='NULL'){
			$filtro.= ' and d.xls IS NULL';
		}elseif($xls){
			$filtro.= " and d.xls IN ($xls)";
		}else{ $filtro.= "";}
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,b.id_personal
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=1,d.horas,NULL)))),'%H:%i') AS horas_simples
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					,d.xls
					,c.usuario as capturado_por
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,f.usuario as autorizado_por
					,DATE_FORMAT(d.timestamp, '%d/%m/%Y %H:%i:%s') as autorizado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario 
				WHERE 1 
				$filtro $grupo $orden
				;";
				echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function autorizaciones_listado_select_coordinador($data=array()){
	global $db, $usuario;
	$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
	$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
	$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
	$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
	$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
	$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
	$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
	$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
	$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];

	$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,30 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,40 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,50 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,60 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario='$usuario[id_usuario]'"
				));	

	$filtro.= ($id_horas_extra)?" and $db[tbl_horas_extra].id_horas_extra IN ($id_horas_extra)":'';
	$filtro.= ($id_personal)?" and $db[tbl_horas_extra].id_personal IN ($id_personal)":'';
	$filtro.= ($empleado_num)?" and $db[tbl_personal].empleado_num IN ($empleado_num)":'';
	$filtro.= ($activo)?" and $db[tbl_horas_extra].activo IN ($activo)":'';
	$filtro.= ($id_usuario)?" and $db[tbl_horas_extra].id_usuario IN ($id_usuario)":'';
	$sql = "SELECT 
				$db[tbl_horas_extra].id_horas_extra
				,CONCAT($db[tbl_personal].nombre,' ',IFNULL($db[tbl_personal].paterno,''),' ',IFNULL($db[tbl_personal].materno,'')) as nombre_completo
				,$db[tbl_personal].empleado_num
				,$db[tbl_personal].id_personal
				,DATE_FORMAT($db[tbl_horas_extra].fecha,'%d/%m/%Y') as fecha
				,DATE_FORMAT($db[tbl_horas_extra].horas,'%H:%i') as horas
				,$db[tbl_usuarios].usuario as capturado_por
				,DATE_FORMAT($db[tbl_horas_extra].timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
			FROM 
				$db[tbl_horas_extra] 
			LEFT JOIN 
				$db[tbl_personal]  
			ON 
				$db[tbl_horas_extra].id_personal=$db[tbl_personal].id_personal
			LEFT JOIN 
				$db[tbl_usuarios]  
			ON 
				$db[tbl_horas_extra].id_usuario=$db[tbl_usuarios].id_usuario
			WHERE 
				1
			$filtro
			AND 
				$db[tbl_horas_extra].id_usuario_aut  is NULL
			GROUP BY 
				$db[tbl_horas_extra].id_horas_extra 
			ORDER BY 
				$db[tbl_horas_extra].id_horas_extra DESC;";
	$resultado = SQLQuery($sql);
	$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function autorizacion_autorizadas_select($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
		$activo			= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus IN ($estatus)";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NOT NULL";
		}elseif($estatus==0){
			$filtro.=" and d.estatus IS NULL";
		}
		// $filtro.= ($xls)?" and d.xls IN ($xls)":'';
		if($xls=='NULL'){
			$filtro.= ' and d.xls IS NULL';
		}elseif($xls){
			$filtro.= " and d.xls IN ($xls)";
		}else{ $filtro.= "";}
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden":'ORDER BY a.id_horas_extra ASC';
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=1,d.horas,NULL)))),'%H:%i') AS horas_simples
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					,d.estatus
					/*,d.xls*/
					,c.usuario as capturado_por
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,f.usuario as autorizado_por
					,DATE_FORMAT(d.timestamp, '%d/%m/%Y %H:%i:%s') as autorizado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario 
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
//*******************************************************************************************************************
//ALEx
function validacion_listado_select_supervisor($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		//$estatus		= (is_array($data[estatus]))?implode(',',$data[estatus]):$data[estatus];
		//$xls			= (is_array($data[xls]))?implode(',',$data[xls]):$data[xls];
		//$activo		= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		/*$filtro.=filtro_grupo(array(
					 ''
					,"and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,"and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,"and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario='$usuario[id_usuario]'"
				));*/
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,30 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,40 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,50 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,60 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario='$usuario[id_usuario]'"
				));

		$filtro.= ($id_horas_extra)?" and $db[tbl_horas_extra].id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and $db[tbl_horas_extra].id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and $db[tbl_personal].empleado_num IN ($empleado_num)":'';		
		$filtro.= ($activo)?" and $db[tbl_horas_extra].activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and $db[tbl_horas_extra].id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY $db[tbl_horas_extra].id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY .id_horas_extra ASC";
		
		$sql = "SELECT 
					$db[tbl_horas_extra].id_horas_extra
					,CONCAT($db[tbl_personal].nombre,' ',IFNULL($db[tbl_personal].paterno,''),' ',IFNULL($db[tbl_personal].materno,'')) as nombre_completo
					,$db[tbl_personal].empleado_num
					,DATE_FORMAT($db[tbl_horas_extra].fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT($db[tbl_horas_extra].horas,'%H:%i') as horas
					,$db[tbl_horas_extra].estatus as estatus
					,$db[tbl_usuarios].usuario as capturado_por
					,DATE_FORMAT($db[tbl_horas_extra].timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,f.usuario as validado_por
					,DATE_FORMAT($db[tbl_horas_extra].estatus_fecha, '%d/%m/%Y %H:%i:%s') as validado_el
					,g.aut_estatus
				FROM 
					$db[tbl_horas_extra]
				LEFT JOIN 
					$db[tbl_personal]
					ON  
						$db[tbl_horas_extra].id_personal=$db[tbl_personal].id_personal
				LEFT JOIN 
					$db[tbl_usuarios] 
					ON 
						$db[tbl_horas_extra].id_usuario=$db[tbl_usuarios].id_usuario
				LEFT JOIN 
					$db[tbl_usuarios] f 
					ON 
						$db[tbl_horas_extra].id_usuario_aut=f.id_usuario 
				LEFT JOIN 
					$db[tbl_autorizaciones] g
					ON 
						$db[tbl_horas_extra].id_horas_extra =g.id_horas_extra
				WHERE 
					1 
				and 
					$db[tbl_horas_extra].estatus ='ACEPTADO'
				AND 
					$db[tbl_horas_extra].id_usuario_aut IS NOT NULL
				AND 
					g.id_horas_extra IS NULL
				OR 
					g.aut_estatus ='RECHAZADO'
	 
					$filtro 
					$grupo 
					$orden;";
		//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function autorizaciones_listado_select_gerente($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));

		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';		
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY a.id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY a.id_horas_extra ASC";
		$sql="SELECT 
				a.id_horas_extra
				,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
				,b.empleado_num
				,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
				,DATE_FORMAT(a.horas,'%H:%i') as horas
				,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=0,g.horas,NULL)))),'%H:%i') AS horas_rechazadas
				,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=1,g.horas,NULL)))),'%H:%i') AS horas_simples
				,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=2,g.horas,NULL)))),'%H:%i') AS horas_dobles
				,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=3,g.horas,NULL)))),'%H:%i') AS horas_triples
				,g.id_concepto
				,c.usuario as capturado_por
				,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
				,f.usuario as asignado_por
				,DATE_FORMAT(g.timestamp, '%d/%m/%Y %H:%i:%s') as asignado_el
			FROM 
					$db[tbl_autorizaciones] g
			LEFT JOIN 
					$db[tbl_horas_extra] a
					ON 
						g.id_horas_extra =a.id_horas_extra
			LEFT JOIN 
					$db[tbl_personal] b
					ON 
						a.id_personal=b.id_personal
			LEFT JOIN 
					$db[tbl_usuarios] c
					ON 
						a.id_usuario=c .id_usuario
			LEFT JOIN 
					$db[tbl_usuarios] f 
					ON 
						a.id_usuario_aut=f.id_usuario 
			WHERE 
					1 
				and 
					a.id_horas_extra =g.id_horas_extra
				and 
					a.estatus ='ACEPTADO'
				AND 
					a.id_usuario_aut IS NOT NULL
				AND 
				 	g.aut_estatus IS NULL	 
					$filtro 
					$grupo 
					$orden;";
			//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function autorizaciones_aprobadas($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and g.id_empresa='$usuario[id_empresa]'"
					,30 => "and g.id_empresa='$usuario[id_empresa]'"
					,40 => "and g.id_empresa='$usuario[id_empresa]' and g.id_usuario!='$usuario[id_usuario]'"
					,50 => "and g.id_empresa='$usuario[id_empresa]' and g.id_usuario!='$usuario[id_usuario]'"
					,60 => "and g.id_empresa='$usuario[id_empresa]' and g.id_usuario='$usuario[id_usuario]'"
				));

		$filtro.= ($id_horas_extra)?" and g.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and g.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';		
		$filtro.= ($activo)?" and g.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and g.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY g.id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY g.id_horas_extra ASC";

				$sql="SELECT 
						g.id_horas_extra
						,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
						,b.id_personal
						,b.id_empresa
						,b.empleado_num
						,a.fecha as fecha
						,a.horas	
						,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=0,g.horas,NULL)))),'%H:%i') AS horas_rechazadas
						,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=2,g.horas,NULL)))),'%H:%i') AS horas_dobles
						,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(g.id_concepto=3,g.horas,NULL)))),'%H:%i') AS horas_triples
						,e.usuario  as validado_por
						,a.estatus_fecha as validado_el
						,c.usuario as asignado_por
						,g.timestamp as asignado_el
						,d.usuario autorizado_por
						,g.aut_timestamp as autorizado_el
					FROM 
						$db[tbl_autorizaciones] g
						LEFT JOIN 
							$db[tbl_horas_extra] a
							ON 
								g.id_horas_extra =a.id_horas_extra
						LEFT JOIN 
							$db[tbl_personal] b
							ON 
								a.id_personal=b.id_personal
						LEFT JOIN 
							$db[tbl_usuarios] c
							ON 
								g.id_usuario=c.id_usuario
						LEFT JOIN 
							$db[tbl_usuarios] d 
							ON 
								g.aut_id_usuario=d.id_usuario 
						LEFT JOIN 
							$db[tbl_usuarios] e
							ON 
								a.id_usuario_aut=e.id_usuario
						LEFT JOIN 
							$db[tbl_autorizaciones_nomina]  f
							ON 
								g.id_horas_extra =f.id_horas_extra
					WHERE 
							g.aut_id_usuario IS NOT NULL
					AND 
							g.aut_estatus='ACEPTADO'
					AND 
							f.id_horas_extra IS NULL
							$filtro 
							$grupo 
							$orden";
					//echo $sql;
			$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_nomina($data=array()){
	// Inserta tabla de nominas
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		
		$sql = "INSERT INTO $db[tbl_autorizaciones_nomina] 
					(	id_horas_extra,
						id_personal,
						id_empresa,
						empleado_num,
						anio,
						semana,
						horas,
						id_concepto,
						id_usuario,
						timestamp
					)
					values
					(	$data[id_horas_extra],
						$data[id_personal],
						$data[id_empresa],
						'$data[empleado_num]',
						$data[anio],
						$data[semana],
						'$data[horas]',
						$data[id_concepto],
						$usuario[id_usuario],
						'$data[timestamp]'
					);";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*O3M*/
?>