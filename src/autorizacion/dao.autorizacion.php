<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-10-02
* @author 		Oscar Maldonado
*/


/**
* Autorización nivel 1
*/

function select_autorizacion_1($data=array()){
/**
* Listado de autorizaciones nivel 1
*/
	$resultado = false;
	if($data[auth]){
	               global $db, $usuario;
	               $id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];
	               $id_personal    = (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
	                $empleado_num  = (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
	               $id_usuario     = (is_array($data[id_usuario]))?implode(',',$data[id_usuario]):$data[id_usuario];
	               $grupo          = (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
	               $orden          = (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
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
	               $filtro.= ($activo)?" and a.activo IN ($activo)":'';
	               $filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
	               $grupo  = ($grupo)?"GROUP BY $grupo":"GROUP BY a.id_horas_extra";
	               $orden  = ($orden)?"ORDER BY $orden":"ORDER BY a.id_horas_extra ASC";                      
	               $sql = "SELECT 
								 a.id_horas_extra
								,a.id_empresa
								,a.id_personal
								,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
								,b.empleado_num
								,a.fecha
								,a.horas
								,a.semana_iso8601
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
							LEFT JOIN 
								he_autorizaciones AS n1 
								ON 
									a.id_horas_extra=n1.id_horas_extra 
								AND 
									n1.id_cat_autorizacion=1          
			               WHERE 1 
			               $filtro 
			               and 
			               n1.estatus IS NULL
			               $grupo 
			               $orden;";
	               //dump_var($sql);
	               $resultado = SQLQuery($sql);
	               $resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_autorizacion_1($data=array()){
	global $db,$usuario;
	$resultado = false;
	if($data[auth]==1){
		$id_hora_extra=$data[id_horas_extra];
		$estatus=$data[estatus];
		$timestamp = date('Y-m-d H:i:s');

		$sql="INSERT INTO
			$db[tbl_autorizaciones]
			SET 
			id_horas_extra 		= $id_hora_extra,
			id_cat_autorizacion = 1,
			estatus 	   		= $estatus,
			id_usuario 			= $usuario[id_usuario],
			timestamp 			= '$timestamp',
			activo 				= 1";
	//echo $sql;
	$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;

}
/*Fin1*/

/**
* Autorización nivel 2
*/
function select_autorizacion_2($data=array()){
/**
* Listado de autorizaciones nivel 2
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
		$filtro.= ($activo)?" and n1.activo IN ($activo)":'';
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
				/*LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 */
				WHERE 1 $filtro AND n1.estatus=1 AND n2.estatus IS NULL 
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_autorizacion_2($data=array()){
/**
* Inserta registros autorizados de nivel 2
*/
	$resultado = false;	
	if($data[auth]){		
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$id_cat_autorizacion = 2;
		$estatus 			 = $data[estatus];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					timestamp 			= '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*Fin2*/


/**
* Autorización nivel 3
*/
function select_autorizacion_3($data=array()){
/**
* Listado de autorizaciones nivel 3
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
		$filtro.= ($activo)?" and n2.activo IN ($activo)":'';
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
					/*,n4.estatus AS n4_estatus
					,n4.id_usuario AS n4_id_usuario
					,n4.timestamp AS n4_fecha*/
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				/*LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 */
				WHERE 1 $filtro AND n2.estatus=1 AND n3.estatus IS NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_autorizacion_3($data=array()){
/**
* Inserta registros autorizados de nivel 3
*/
	$resultado = false;	
	if($data[auth]){		
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$id_cat_autorizacion = 3;
		$estatus 			 = $data[estatus];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					timestamp 			= '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*Fin3*/


/**
* Autorización nivel 4
*/
function select_autorizacion_4($data=array()){
/**
* Listado de autorizaciones nivel 4
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
		$filtro.= ($activo)?" and n3.activo IN ($activo)":'';
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
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				WHERE 1 $filtro AND n3.estatus=1 AND n4.estatus IS NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_autorizacion_4($data=array()){
/**
* Inserta registros autorizados de nivel 4
*/
	$resultado = false;	
	if($data[auth]){		
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$id_cat_autorizacion = 4;
		$estatus 			 = $data[estatus];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					timestamp 			= '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*Fin4*/


/**
* Autorización nivel 5
*/
function select_autorizacion_5($data=array()){
/**
* Listado de autorizaciones nivel 5
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
		$filtro.= ($activo)?" and n4.activo IN ($activo)":'';
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
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				WHERE 1 $filtro AND n4.estatus=1
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function insert_autorizacion_5($data=array()){
/**
* Inserta registros autorizados de nivel 5
*/
	$resultado = false;	
	if($data[auth]){		
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$id_cat_autorizacion = 5;
		$estatus 			 = $data[estatus];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					timestamp 			= '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*Fin5*/


/*O3M*/
?>