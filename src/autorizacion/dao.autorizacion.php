<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function capturados_select($data=array()){
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
		$filtro.=filtro_grupo(array('','',"and b.personal='$usuario[empresa]'","and a.id_usuario='$usuario[id_usuario]'"));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
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
					,CONCAT(b.nombre,' ', b.paterno,' ',b.materno) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,d.estatus
					,d.xls
					,e.clave as concepto_clave
					,c.usuario as capturado_por
					,a.timestamp as capturado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_conceptos] e ON d.id_concepto=e.id_concepto
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}

function autorizacion_insert($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		$horas 			= horas_int($data[horas]);
		$id_concepto 	= $data[id_concepto];
		$estatus 		= $data[estatus];
		$timestamp = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra='$id_horas_extra',
					horas = '$horas',
					id_concepto = '$id_concepto',
					estatus ='$estatus',
					id_usuario = '$usuario[id_usuario]',
					timestamp = '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}

function autorizacion_update($data=array()){
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$campos = array();
		$timestamp = date('Y-m-d H:i:s');
		$id_autorizacion = (is_array($data[id_autorizacion]))?implode(',',$data[id_autorizacion]):$data[id_autorizacion];
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$campos [] = ($data[id_concepto])?"id_concepto='$data[id_concepto]'":'';
		$campos [] = ($data[estatus])?"estatus='$data[estatus]'":'';
		$campos [] = ($data[xls])?"xls='$data[xls]'":'';
		#$campos [] = "id_usuario='$usuario[id_usuario]'";
		#$campos [] = "timestamp='$timestamp'";				
		$campos = implode(',',array_filter($campos));		
		$filtro	= ($id_autorizacion)?" and id_autorizacion IN ($id_autorizacion)":'';
		$filtro	.= ($id_horas_extra)?" and id_horas_extra IN ($id_horas_extra)":'';
		if(!empty($campos)){
			$sql = "UPDATE $db[tbl_autorizaciones] 
					SET $campos
					WHERE 1 $filtro
					;";
			$resultado = (SQLDo($sql))?true:false;
		}
	}
	return $resultado;
}

function conceptos_select($data=array()){
	$resultado = false;
	if($data[auth]){
		$id_concepto 	= $data[id_concepto];
		$activo			= $data[activo];
		global $db;
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
		$filtro.=filtro_grupo(array('','',"and b.personal='$usuario[empresa]'","and a.id_usuario='$usuario[id_usuario]'"));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
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
					,CONCAT(b.nombre,' ', b.paterno,' ',b.materno) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,d.estatus
					/*,d.xls*/
					,c.usuario as capturado_por
					,a.timestamp as capturado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
/*O3M*/
?>