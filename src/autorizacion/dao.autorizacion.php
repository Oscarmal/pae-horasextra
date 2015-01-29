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
                                                  ,30 => "and a.id_empresa='$usuario[id_empresa]' and (s1.id_supervisor='$usuario[id_personal]' or s5.id_supervisor='$usuario[id_personal]')"
                                                  ,34 => "and a.id_empresa='$usuario[id_empresa]' and (s1.id_supervisor='$usuario[id_personal]' or s4.id_supervisor='$usuario[id_personal]')"
                                                  ,35 => "and a.id_empresa='$usuario[id_empresa]' and (s1.id_supervisor='$usuario[id_personal]' or s3.id_supervisor='$usuario[id_personal]')"
                                                  ,40 => "and a.id_empresa='$usuario[id_empresa]' and (s1.id_supervisor='$usuario[id_personal]' or s2.id_supervisor='$usuario[id_personal]')"
                                                  ,50 => "and a.id_empresa='$usuario[id_empresa]' and s1.id_supervisor='$usuario[id_personal]'"
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
								,c.nombre as empresa
								,a.id_personal
								,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
								,b.empleado_num
								,a.fecha
								,a.horas
								,a.semana_iso8601
								,n1.estatus AS n1_estatus
								,n1.id_usuario AS n1_id_usuario
								,n1.timestamp AS n1_fecha
							FROM $db[tbl_horas_extra] a
							LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
							LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
							LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1							
							left join $db[tbl_supervisores] s1 on b.id_empresa=s1.id_empresa and b.id_personal=s1.id_personal and s1.id_nivel=1
							left join $db[tbl_supervisores] s2 on b.id_empresa=s2.id_empresa and b.id_personal=s2.id_personal and s2.id_nivel=2
							left join $db[tbl_supervisores] s3 on b.id_empresa=s3.id_empresa and b.id_personal=s3.id_personal and s3.id_nivel=3
							left join $db[tbl_supervisores] s4 on b.id_empresa=s4.id_empresa and b.id_personal=s4.id_personal and s4.id_nivel=4
							left join $db[tbl_supervisores] s5 on b.id_empresa=s5.id_empresa and b.id_personal=s5.id_personal and s5.id_nivel=5
			               WHERE 1 AND n1.estatus IS NULL 
			               $filtro 			               
			               $grupo 
			               $orden;";
	               // dump_var($sql);
	               $resultado = SQLQuery($sql);
	               $resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function select_layout_autorizacion_1($data=array()){
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
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 $filtro 
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function select_acumulado_semanal_2($data=array()){
	if($data[auth]){
		global $db;
		$id_empresa 	= (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$id_personal 	= (is_array($data[id_personal]))?implode(',',$data[id_personal]):$data[id_personal];
		$empleado_num 	= (is_array($data[empleado_num]))?implode(',',$data[empleado_num]):$data[empleado_num];
		$fecha 			= (is_array($data[fecha]))?implode(',',date("Y-m-d", strtotime(str_replace('/', '-', $data[fecha])))):date("Y-m-d", strtotime(str_replace('/', '-', $data[fecha])));
		$semana_iso8601 = semana_iso8601($fecha);
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($id_personal)?" and a.id_personal IN ($id_personal)":'';
		$filtro.= ($empleado_num)?" and b.empleado_num IN ($empleado_num)":'';
		$filtro.= ($semana_iso8601)?" and a.semana_iso8601 IN ('$semana_iso8601')":'';
		$sql="SELECT 
				 a.id_empresa
				,a.id_personal
				,b.empleado_num
				,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
				,a.semana_iso8601
				,COUNT(*) AS tot_regs
				,SUM(DATE_FORMAT(c.horas,'%H')) AS tot_horas
			FROM $db[tbl_horas_extra] a
			LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
			LEFT JOIN $db[tbl_autorizaciones_nomina] c ON a.id_horas_extra=c.id_horas_extra
			WHERE 1 and c.activo=1 and c.horas IS NULL $filtro
			GROUP BY  id_empresa ,id_personal, semana_iso8601 ASC;";
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
		$argumento=$data[argumento];
		$timestamp = date('Y-m-d H:i:s');

		$sql="INSERT INTO
			$db[tbl_autorizaciones]
			SET 
			id_horas_extra 		= $id_hora_extra,
			id_cat_autorizacion = 1,
			estatus 	   		= $estatus,
			id_usuario 			= $usuario[id_usuario],
			argumento 			= '$argumento',
			timestamp 			= '$timestamp',
			activo 				= 1;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;

}
function insert_layout_autorizacion_1($data=array()){
/**
 * Inserta registros autorizados para generar el layout
 */
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$dobles 			 = horas_int($data[dobles]);
		$triples 			 = horas_int($data[triples]);
		$rechazadas			 = horas_int($data[rechazadas]);
		$id_concepto 		 = $data[id_concepto];	
		$estatus 			 = $data[estatus];
		$argumento 			 = $data[argumento];	
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql="INSERT INTO
			$db[tbl_autorizaciones]
			SET 
			id_horas_extra 		= '$id_horas_extra',
			id_cat_autorizacion = '1',
			estatus 	   		= '$estatus',
			id_usuario 			= '$usuario[id_usuario]',
			argumento 			= '$argumento',
			h_dobles 			= '$dobles',
			h_triples 			= '$triples',
			h_rechazadas 		= '$rechazadas',
			timestamp 			= '$timestamp',
			activo 				= '1';";
			// dump_var($sql);
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
					,30 => "and a.id_empresa='$usuario[id_empresa]' and (s2.id_supervisor='$usuario[id_personal]' or s5.id_supervisor='$usuario[id_personal]')"
					,34 => "and a.id_empresa='$usuario[id_empresa]' and (s2.id_supervisor='$usuario[id_personal]' or s4.id_supervisor='$usuario[id_personal]')"
					,35 => "and a.id_empresa='$usuario[id_empresa]' and (s2.id_supervisor='$usuario[id_personal]' or s3.id_supervisor='$usuario[id_personal]')"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and s2.id_supervisor='$usuario[id_personal]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and s2.id_supervisor='$usuario[id_personal]'"
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
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2				
				left join $db[tbl_supervisores] s1 on b.id_empresa=s1.id_empresa and b.id_personal=s1.id_personal and s1.id_nivel=1
				left join $db[tbl_supervisores] s2 on b.id_empresa=s2.id_empresa and b.id_personal=s2.id_personal and s2.id_nivel=2
				left join $db[tbl_supervisores] s3 on b.id_empresa=s3.id_empresa and b.id_personal=s3.id_personal and s3.id_nivel=3
				left join $db[tbl_supervisores] s4 on b.id_empresa=s4.id_empresa and b.id_personal=s4.id_personal and s4.id_nivel=4
				left join $db[tbl_supervisores] s5 on b.id_empresa=s5.id_empresa and b.id_personal=s5.id_personal and s5.id_nivel=5
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
		$argumento			 =	$data[argumento];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					argumento 			= '$argumento',
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
					,30 => "and a.id_empresa='$usuario[id_empresa]' and (s3.id_supervisor='$usuario[id_personal]' or s5.id_supervisor='$usuario[id_personal]')"
					,34 => "and a.id_empresa='$usuario[id_empresa]' and (s3.id_supervisor='$usuario[id_personal]' or s4.id_supervisor='$usuario[id_personal]')"
					,35 => "and a.id_empresa='$usuario[id_empresa]' and s3.id_supervisor='$usuario[id_personal]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and s3.id_supervisor='$usuario[id_personal]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and s3.id_supervisor='$usuario[id_personal]'"
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
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3				
				left join $db[tbl_supervisores] s1 on b.id_empresa=s1.id_empresa and b.id_personal=s1.id_personal and s1.id_nivel=1
				left join $db[tbl_supervisores] s2 on b.id_empresa=s2.id_empresa and b.id_personal=s2.id_personal and s2.id_nivel=2
				left join $db[tbl_supervisores] s3 on b.id_empresa=s3.id_empresa and b.id_personal=s3.id_personal and s3.id_nivel=3
				left join $db[tbl_supervisores] s4 on b.id_empresa=s4.id_empresa and b.id_personal=s4.id_personal and s4.id_nivel=4
				left join $db[tbl_supervisores] s5 on b.id_empresa=s5.id_empresa and b.id_personal=s5.id_personal and s5.id_nivel=5
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
		$argumento			 =	$data[argumento];
		$timestamp 			 = date('Y-m-d H:i:s');

		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					argumento 			= '$argumento',
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
					,30 => "and a.id_empresa='$usuario[id_empresa]' and (s4.id_supervisor='$usuario[id_personal]' or s5.id_supervisor='$usuario[id_personal]')"
					,34 => "and a.id_empresa='$usuario[id_empresa]' and s4.id_supervisor='$usuario[id_personal]'"
					,35 => "and a.id_empresa='$usuario[id_empresa]' and s4.id_supervisor='$usuario[id_personal]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and s4.id_supervisor='$usuario[id_personal]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and s4.id_supervisor='$usuario[id_personal]'"
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
				left join $db[tbl_supervisores] s1 on b.id_empresa=s1.id_empresa and b.id_personal=s1.id_personal and s1.id_nivel=1
				left join $db[tbl_supervisores] s2 on b.id_empresa=s2.id_empresa and b.id_personal=s2.id_personal and s2.id_nivel=2
				left join $db[tbl_supervisores] s3 on b.id_empresa=s3.id_empresa and b.id_personal=s3.id_personal and s3.id_nivel=3
				left join $db[tbl_supervisores] s4 on b.id_empresa=s4.id_empresa and b.id_personal=s4.id_personal and s4.id_nivel=4
				left join $db[tbl_supervisores] s5 on b.id_empresa=s5.id_empresa and b.id_personal=s5.id_personal and s5.id_nivel=5
				WHERE 1 $filtro AND n3.estatus=1 AND n4.estatus IS NULL
				$grupo 
				$orden;";
				// dump_var($sql);
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
		$argumento			 =	$data[argumento];
		$timestamp 			 = date('Y-m-d H:i:s');

		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					argumento 			= '$argumento',
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
					,30 => "and a.id_empresa='$usuario[id_empresa]' and s5.id_supervisor='$usuario[id_personal]'"
					,34 => "and a.id_empresa='$usuario[id_empresa]' and s5.id_supervisor='$usuario[id_personal]'"
					,35 => "and a.id_empresa='$usuario[id_empresa]' and s5.id_supervisor='$usuario[id_personal]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]' and s5.id_supervisor='$usuario[id_personal]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]' and s5.id_supervisor='$usuario[id_personal]'"
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
					,n5.estatus AS n5_estatus
					,n5.id_usuario AS n5_id_usuario
					,n5.timestamp AS n5_fecha
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4
				LEFT JOIN $db[tbl_autorizaciones] AS n5 ON a.id_horas_extra=n5.id_horas_extra AND n5.id_cat_autorizacion=5
				left join $db[tbl_supervisores] s1 on b.id_empresa=s1.id_empresa and b.id_personal=s1.id_personal and s1.id_nivel=1
				left join $db[tbl_supervisores] s2 on b.id_empresa=s2.id_empresa and b.id_personal=s2.id_personal and s2.id_nivel=2
				left join $db[tbl_supervisores] s3 on b.id_empresa=s3.id_empresa and b.id_personal=s3.id_personal and s3.id_nivel=3
				left join $db[tbl_supervisores] s4 on b.id_empresa=s4.id_empresa and b.id_personal=s4.id_personal and s4.id_nivel=4
				left join $db[tbl_supervisores] s5 on b.id_empresa=s5.id_empresa and b.id_personal=s5.id_personal and s5.id_nivel=5
				WHERE 1 $filtro AND n4.estatus=1 AND n5.estatus IS NULL
				$grupo 
				$orden;";
				// dump_var($sql);
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
		$argumento			 =	$data[argumento];
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones] SET
					id_horas_extra 		= '$id_horas_extra',
					id_cat_autorizacion = '$id_cat_autorizacion',
					estatus 			= '$estatus',
					id_usuario 			= '$usuario[id_usuario]',
					argumento 			= '$argumento',
					timestamp 			= '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*Fin5*/

/*O3M*/
?>