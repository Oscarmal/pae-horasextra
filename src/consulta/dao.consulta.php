<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
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
function select_listado_horas_capturadas($data=array()){
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
		
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario!='$usuario[id_usuario]'"
					,60 => "and a.id_empresa='$usuario[id_empresa]' and a.id_usuario='$usuario[id_usuario]'"
				));	
		$filtro.= ($id_horas_extra)?" and a.id_horas_extra='$id_horas_extra'":'';
		$filtro.= ($id_personal)?" and a.id_personal='$id_personal'":'';
		$filtro.= ($empleado_num)?" and b.empleado_num='$empleado_num'":'';
		

		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$grupo 	= ($grupo)?"
							GROUP BY $grupo":"GROUP BY a.id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden".$desc:"ORDER BY a.id_horas_extra".$desc;

		$sql = "SELECT 
					 a.id_horas_extra
					,a.id_empresa
					,a.id_personal
					,c.nombre as empresa
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num
					,a.fecha
					,a.horas
					,a.semana_iso8601
					,b.nombre AS capturado_por
					,a.timestamp AS capturado_el
					,n1.estatus AS n1_estatus
					,n1.id_usuario AS n1_id_usuario
					,n1.timestamp AS n1_fecha
				FROM 
					he_horas_extra a
				LEFT JOIN 
					he_personal b 
					ON 
						a.id_empresa=b.id_empresa 
					AND 
						a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN 
					he_autorizaciones AS n1 
					ON 
						a.id_horas_extra=n1.id_horas_extra 
					AND 
						n1.id_cat_autorizacion=1          
			   WHERE 
			  	 	1 
			   		$filtro 
			   		$grupo 
					$orden;";
					//dump_var($sql);
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	}else{
		$resultado = false;
	}
	return $resultado;
}
function listado_select_autorizacion_2($data=array()){
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
		$filtro.= ($empleado_num)?" and $db[tbl_personal].empleado_num IN ($empleado_num)":'';		
		$filtro.= ($activo)?" and a.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY a.id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY .id_horas_extra ASC";

			$sql = "SELECT 
					 a.id_horas_extra
					,a.id_empresa
					,c.nombre as empresa
					,a.id_personal
					,b.empleado_num
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,a.fecha
					,a.horas
					,a.semana_iso8601
					,n1.estatus AS n1_estatus
					,d.nombre as capturado_por
					,n1.id_usuario AS n1_id_usuario
					,n1.timestamp AS n1_fecha
					,n2.estatus AS n2_estatus
					,n2.id_usuario AS n2_id_usuario
					,n2.timestamp AS n2_fecha
					/*,n3.estatus AS n3_estatus
					,n3.id_usuario AS n3_id_estatus
					,n3.timestamp AS n3_fecha
					,n4.estatus AS n4_estatus
					,n4.id_usuario AS n4_id_usuario
					,n4.timestamp AS n4_fecha*/
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_personal] d ON n1.id_usuario=d.id_personal	 
				/*LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 */
				WHERE 1 $filtro AND n1.estatus IS NOT NULL
				$grupo 
				$orden;";
			//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function listado_select_autorizacion_3($data=array()){
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
		
			//echo $sql; dump_var($sql);
					$sql = "SELECT 
					 a.id_horas_extra
					,a.id_empresa
					,c.nombre as empresa
					,a.id_personal
					,b.empleado_num
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,d.nombre as capturado_por
					,a.fecha
					,a.horas
					,a.semana_iso8601
					,n1.estatus AS n1_estatus
					,n1.id_usuario AS n1_id_usuario
					,n1.timestamp AS n1_fecha
					,n2.estatus AS n2_estatus
					,n2.id_usuario AS n2_id_usuario
					,n2.timestamp AS n2_fecha
					,n3.estatus AS n3_estatus
					,n3.id_usuario AS n3_id_estatus
					,n3.timestamp AS n3_fecha
					/*,n4.estatus AS n4_estatus
					,n4.id_usuario AS n4_id_usuario
					,n4.timestamp AS n4_fecha*/
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_personal] d ON n1.id_usuario=d.id_personal
				/*LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 */
				WHERE 1 $filtro AND n2.estatus=1
				$grupo 
				$orden;";
				//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function listado_select_autorizacion_4($data=array()){
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

				$sql = "SELECT 
					 a.id_horas_extra
					,a.id_empresa
					,c.nombre as empresa
					,a.id_personal
					,b.empleado_num
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,d.nombre as capturado_por
					,a.fecha
					,a.horas
					,a.semana_iso8601
					,n1.estatus AS n1_estatus
					,n1.id_usuario AS n1_id_usuario
					,n1.timestamp AS n1_fecha
					,n2.estatus AS n2_estatus
					,n2.id_usuario AS n2_id_usuario
					,n2.timestamp AS n2_fecha
					,n3.estatus AS n3_estatus
					,n3.id_usuario AS n3_id_estatus
					,n3.timestamp AS n3_fecha
					,n4.estatus AS n4_estatus
					,n4.id_usuario AS n4_id_usuario
					,n4.timestamp AS n4_fecha
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				LEFT JOIN $db[tbl_personal] d ON n1.id_usuario=d.id_personal
				WHERE 1 $filtro AND n3.estatus=1
				$grupo 
				$orden;";
				//echo $sql;
			$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function listado_select_autorizacion_5($data=array()){
/**
* Listado de registros autorizados en todos sus niveles
*/
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$id_usuario		= (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
		$activo 		= (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
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
		$filtro.= ($activo)?" and n5.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY a.id_horas_extra";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY a.id_horas_extra ASC";		
		$sql = "SELECT 
					 a.id_horas_extra
					,a.id_empresa
					,c.nombre as empresa
					,a.id_personal
					,b.empleado_num
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,a.fecha
					,a.horas
					,a.semana_iso8601
					,n1.estatus AS n1_estatus
					,n1.id_usuario AS n1_id_usuario
					,n1.timestamp AS n1_fecha
					,n2.estatus AS n2_estatus
					,n2.id_usuario AS n2_id_usuario
					,n2.timestamp AS n2_fecha
					,n3.estatus AS n3_estatus
					,n3.id_usuario AS n3_id_estatus
					,n3.timestamp AS n3_fecha
					,n4.estatus AS n4_estatus
					,n4.id_usuario AS n4_id_usuario
					,n4.timestamp AS n4_fecha
					,n5.estatus AS n5_estatus
					,n5.id_usuario AS n5_id_usuario
					,n5.timestamp AS n5_fecha
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				LEFT JOIN $db[tbl_autorizaciones] AS n5 ON a.id_horas_extra=n5.id_horas_extra AND n5.id_cat_autorizacion=5
				WHERE 1 $filtro AND n5.estatus IS NOT NULL
				$grupo 
				$orden;";
				//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
/*O3M*/
?>