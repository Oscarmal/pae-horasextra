<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function captura_listado_select($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		$id_personal 	= $data[id_personal];
		$empleado_num 	= $data[empleado_num];
		$estatus		= $data[estatus];
		$activo			= $data[activo];
		$grupo 			= $data[grupo];
		$orden 			= $data[orden];
		$desc 			= $data[desc];
		$filtro.=filtro_grupo(array(
					 ''
					,"and a.id_empresa='$usuario[id_empresa]'"
					,"and a.id_empresa='$usuario[id_empresa]'"
					,"and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra='$id_horas_extra'":'';
		$filtro.= ($id_personal)?" and a.id_personal='$id_personal'":'';
		$filtro.= ($empleado_num)?" and b.empleado_num='$empleado_num'":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus='$estatus'";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NULL";
		}
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_horas_extra'.$desc;
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
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

	}else{
		$resultado = false;
	}
	return $resultado;
}

function autorizacion_listado_select($data=array()){
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
					 ''
					,"and a.id_empresa='$usuario[id_empresa]'"
					,"and a.id_empresa='$usuario[id_empresa]' and d.id_usuario='$usuario[id_usuario]'"
					,"and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
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
					,a.timestamp as capturado_el
					,f.usuario as autorizado_por
					,d.timestamp as autorizado_el
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

/*O3M*/
?>