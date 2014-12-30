<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
/*
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
					,DATE_FORMAT(a.timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 
				$filtro $grupo $orden
				;";
				echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	}else{
		$resultado = false;
	}
	return $resultado;
}
*/
function captura_listado_select_coordinador($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		$id_personal 	= $data[id_personal];
		$empleado_num 	= $data[empleado_num];
		//$estatus		= $data[estatus];
		$activo			= $data[activo];
		$grupo 			= $data[grupo];
		$orden 			= $data[orden];
		$desc 			= $data[desc];
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
		$filtro.= ($id_horas_extra)?" and $db[tbl_horas_extra].id_horas_extra='$id_horas_extra'":'';
		$filtro.= ($id_personal)?" and $db[tbl_horas_extra].id_personal='$id_personal'":'';
		$filtro.= ($empleado_num)?" and $db[tbl_personal].empleado_num='$empleado_num'":'';
		

		$filtro.= ($activo)?" and $db[tbl_horas_extra].activo IN ($activo)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$grupo 	= ($grupo)?"
							GROUP BY $grupo":"GROUP BY $db[tbl_horas_extra].id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden".$desc:"ORDER BY $db[tbl_horas_extra].id_horas_extra".$desc;

		$sql = "SELECT 
					$db[tbl_horas_extra].id_horas_extra
					,CONCAT($db[tbl_personal].nombre,' ',IFNULL($db[tbl_personal].paterno,''),' ',IFNULL($db[tbl_personal].materno,'')) as nombre_completo
					,$db[tbl_personal].empleado_num
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
				AND 
					$db[tbl_horas_extra].id_usuario_aut  is NULL
					$filtro 
					$grupo 
					$orden;";

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
		/*$filtro.=filtro_grupo(array(
					 ''
					,"and a.id_empresa='$usuario[id_empresa]'"
					,"and a.id_empresa='$usuario[id_empresa]'"
					,"and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));*/

		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,30 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]'"
					,40 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,50 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario!='$usuario[id_usuario]'"
					,60 => "and $db[tbl_horas_extra].id_empresa='$usuario[id_empresa]' and $db[tbl_horas_extra].id_usuario='$usuario[id_usuario]'"
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
		//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function autorizacion_listado_select_coordinador($data=array()){
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
					,$db[tbl_usuarios].usuario as capturado_por
					,DATE_FORMAT($db[tbl_horas_extra].timestamp, '%d/%m/%Y %H:%i:%s') as capturado_el
					,f.usuario as autorizado_por
					,DATE_FORMAT($db[tbl_horas_extra].estatus_fecha, '%d/%m/%Y %H:%i:%s') as autorizado_el
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
				WHERE 
					1 
				and 
					$db[tbl_horas_extra].estatus IS NOT NULL
					$filtro 
					$grupo 
					$orden;";
		//echo $sql;

		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function asignacion_listado_select_gerente($data=array()){
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
				,g.aut_estatus 
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
					g.aut_estatus IS NOT NULL				
					$filtro 
					$grupo 
					$orden;";
			//echo $sql; dump_var($sql);
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function aprobadas_listado_select($data=array()){
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
					WHERE 
							g.aut_id_usuario IS NOT NULL
					AND 
							g.aut_estatus='ACEPTADO'
							$filtro 
							$grupo 
							$orden";
					//echo $sql;
			$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
/*O3M*/
?>