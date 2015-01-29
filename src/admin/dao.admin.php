<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// require_once($Raiz[local].$cfg[php_postgres]);
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
set_time_limit(0);
function select_view_nomina($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_nomina = (is_array($data[id_nomina]))?implode(',',$data[id_nomina]):$data[id_nomina];
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$id_number = (is_array($data[id_number]))?implode(',',$data[id_number]):$data[id_number];
		//$activo = (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and c.id_empresa='$usuario[id_empresa]'"
					,30 => "and c.id_empresa='$usuario[id_empresa]'"
					,40 => "and c.id_empresa='$usuario[id_empresa]'"
					,50 => "and c.id_empresa='$usuario[id_empresa]'"
					,60 => "and c.id_personal='$usuario[id_personal]'"
				));
		$filtro.= ($id_nomina)?" and a.id_nomina IN ($id_nomina)":'';
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($id_number)?" and a.id_number IN ($id_number)":'';
		//$filtro.= ($activo)?" and b.activo IN ($activo)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_empresa, a.id_empleado'.$desc;		

		$sql="SELECT IF(a.id_empresa is null, c.id_nomina,a.id_empresa) as id_empresa
				,IF(a.id_number is null, c.empleado_num,a.id_number) as id_number
				,CONCAT(IFNULL(c.nombre,''),' ', IFNULL(c.paterno,''),' ',IFNULL(c.materno,'')) as nombre
				,c.timestamp as fecha_corte
				,c.id_personal
				,a.position
				,a.area
				,a.rfc
				,a.imss
				,a.ingreso
				,IF(a.empresa is null, b.nombre,a.empresa) as empresa
				,IF(a.empresa_razon_social is null, b.nombre,a.empresa_razon_social) as empresa_razon_social
				,a.id_empleado
				,b.id_empresa as id_he_empresa
				FROM 
					$db[tbl_personal] c
				LEFT JOIN 
					$db[view_nomina] a 
						ON c.id_nomina=a.id_view_nomina
				LEFT JOIN 
					$db[tbl_empresas] b 
						ON a.id_empresa=b.id_empresa
				WHERE 1
				$filtro $grupo $orden ;";
		//dump_var($sql);
		$resultado = SQLQuery($sql);				

		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}
function select_view_vista_credenciales($filtrado,$id_empresa){
	global $db, $usuario;
	/*if(!$filtrado){
		$sql_alterno='';
	}else{
		$sql_alterno="WHERE id_empresa=$id_empresa";
	}		*/
	///consulta para que traiga solo a chrysler
		$sql="SELECT 
				* 
			FROM 
				$db[pgsql_vista_credenciales]
			WHERE 
				id_empresa in ($id_empresa)";
				/*$sql_alterno;";*/
		//echo $sql;
		$resultado = pgquery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	
	return $resultado;
}
/*O3M*/
function insert_sincronizacion_update(){
	global $db, $usuario;
	
		$sql="INSERT INTO
				$db[tbl_personal] 
					(nombre, paterno, materno, email, rfc,imss,sucursal,puesto,empleado_num,id_empresa,timestamp,id_usuario, id_nomina)
						SELECT 	
						$db[view_nomina].nombre_empleado,
						$db[view_nomina].apellido_paterno_empleado,
						$db[view_nomina].apellido_materno_empleado,
						$db[view_nomina].correo_electronico,
						$db[view_nomina].rfc,
						$db[view_nomina].imss,
						$db[view_nomina].area,
						$db[view_nomina].position,
						$db[view_nomina].id_empleado,
						$db[view_nomina].id_empresa,
						DATE_FORMAT(now(),'%Y-%m-%d %h:%i:%s') as timestamp,
						$usuario[id_usuario]
						,$db[view_nomina].id_view_nomina
					FROM 
						 $db[view_nomina] 
						LEFT JOIN
							$db[tbl_empresas]
							ON 
								$db[view_nomina].id_empresa = $db[tbl_empresas].id_nomina
						LEFT JOIN
							$db[tbl_personal]
							ON
								$db[view_nomina].id_empleado = $db[tbl_personal].empleado_num	
							AND 
								$db[view_nomina].id_empresa = $db[tbl_personal].id_empresa
							AND 
								$db[view_nomina].id_empleado = $db[tbl_personal].empleado_num	
						WHERE 	
							$db[tbl_personal].id_personal is NULL;";
		
		$id_personal = SQLDo($sql);

		$sql2="INSERT INTO 
					 $db[tbl_usuarios]
				(usuario,clave,id_personal,timestamp)
						SELECT 	
						$db[view_nomina].rfc,
						$db[view_nomina].imss,
						$db[tbl_personal].id_personal,
						DATE_FORMAT(now(),'%Y-%m-%d %h:%i:%s') as timestamp
					FROM 
						 $db[view_nomina]
						LEFT JOIN
							$db[tbl_usuarios]
							ON 
								$db[view_nomina].imss = $db[tbl_usuarios].clave
						LEFT JOIN $db[tbl_personal] ON $db[tbl_personal].id_nomina=$db[view_nomina].id_view_nomina
						WHERE 	
							$db[tbl_usuarios].usuario is NULL;";
							//echo $sql2;
		$resultado = SQLDo($sql2);
		$resultado = (count($resultado)) ? $resultado : false ;
	
	return $resultado;
}
function truncate_vista_nomina(){
	global $db, $usuario;
	$sql="TRUNCATE TABLE $db[view_nomina]";
	$resultado = SQLDo($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	
	return $resultado;
}
function select_catalgos_empresa(){
	global $db,$usuario;
	
	if($usuario[id_grupo]<=10){
		$sql_alterno='';
	}
	else{
		$sql_alterno="and id_empresa=$usuario[id_empresa]";
	}
	
	$sql="SELECT 
				id_empresa,
				nombre
			FROM 
				$db[tbl_empresas] 
			WHERE 1 and id_empresa>1
				$sql_alterno
				group by nombre ASC;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function select_catalgo_usuarios_grupo(){
	global $db,$usuario;
	$sql="SELECT 
				id_grupo,
				grupo
			FROM 
				$db[tbl_grupos]
			WHERE 
				id_grupo BETWEEN 21 AND 60
			ORDER BY id_grupo;";
		//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function insert_nuevo_registro($nombre,$apellido_paterno,$apellido_materno,$correo,$rfc,$nss,$sucursal,$puesto,$no_empleado,$id_empresa,$id_usuario_grupo,$timestamp){
	global $db, $usuario;

	$sql="INSERT INTO
				$db[tbl_personal]
				(nombre,
				paterno,
				materno,
				rfc,
				imss,
				email,
				sucursal,
				puesto,
				empleado_num,
				id_empresa,
				timestamp,
				id_usuario)
				values
				('$nombre',
				'$apellido_paterno',
				'$apellido_materno',
				'$rfc',
				'$nss',
				'$correo',
				'$sucursal',
				'$puesto',
				'$no_empleado',
				$id_empresa,
				'$timestamp',
				$usuario[id_usuario]);";
						//echo $sql;
			global $db, $usuario;
		
		$id_personal = SQLDo($sql);

		$sql2="INSERT INTO 
					$db[tbl_usuarios]
						(usuario,clave,id_grupo,id_personal,timestamp,activo)
					values
						('$no_empleado','$no_empleado',$id_usuario_grupo,$id_personal,'$timestamp',1);";
							//echo $sql2;
		$resultado = SQLDo($sql2);
		$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function select_empresas_tabla(){
	global $db,$usuario;
	
	$sql="SELECT 
				id_empresa,
				nombre,
				siglas,
				razon,
				timestamp
			FROM 
				$db[tbl_empresas]";
		//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function select_empresas_activas(){
	global $db,$usuario;
	
	$sql="SELECT 
				id_empresa,
				id_nomina,
				nombre,
				siglas,
				razon,
				timestamp
			FROM 
				$db[tbl_empresas]
			WHERE 
				activo = 1";
		//echo $sql;
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
function select_empresas_nomina(){
	global $db, $usuario;
		///consulta para que traiga solo a chrysler
		/*$sql="SELECT DISTINCT 
				$db[pgsql_vista_credenciales].id_empresa,
				$db[pgsql_vista_credenciales].empresa,
				$db[pgsql_vista_credenciales].empresa_razon_social
			FROM 
				$db[pgsql_vista_credenciales]
			WHERE
				id_empresa=3082
			ORDER BY 
				$db[pgsql_vista_credenciales].id_empresa;";*/
				//echo $sql;
				$sql="SELECT DISTINCT 
				$db[pgsql_vista_credenciales].id_empresa,
				$db[pgsql_vista_credenciales].empresa,
				$db[pgsql_vista_credenciales].empresa_razon_social
			FROM 
				$db[pgsql_vista_credenciales]
			ORDER BY 
				$db[pgsql_vista_credenciales].id_empresa;";
		$resultado = pgquery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	return $resultado;
}
function insert_empresa_nomina_tmp(){
	global $db;

	$sql="INSERT INTO 
			$db[tbl_empresas]
			(nombre,siglas,rfc,razon,direccion,pais,email,timestamp,id_usuario,activo,id_nomina)
		SELECT 
			t.nombre,
			t.siglas,
			t.rfc,
			t.razon,
			t.direccion,
			t.pais,
			t.email,
			t.timestamp,
			t.id_usuario,
			t.activo,
			t.id_nomina
		FROM 
			$db[tbl_tmp_empresas] t
			LEFT JOIN 
				$db[tbl_empresas] e
				ON 
					t.id_nomina=e.id_nomina
			WHERE 
				e.id_nomina is NULL";
		
	$resultado = SQLDo($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	
	return $resultado;
}
function eliminar_tmp_empresa_nomina(){
	global $db;

	$sql="DROP TABLE IF EXISTS $db[tbl_tmp_empresas];";
	$resultado = SQLDo($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	
	return $resultado;
}


/**
* Aministración Layout
*/
function select_layout($data=array()){
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
				WHERE 1 $filtro AND n5.estatus=1 AND d.id_autorizacion_nomina IS NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function select_acumulado_semanal($data=array()){
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
function insert_layout($data=array()){
/**
 * Inserta registros autorizados para generar el layout
 */
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra 	 = $data[id_horas_extra];
		$id_cat_autorizacion = 5;
		$anio				 = $data[anio];
		$semana 			 = $data[semana];
		$periodo			 = $data[periodo];
		$periodo_especial	 = $data[periodo_especial];
		$horas 				 = horas_int($data[horas]);
		$id_concepto 		 = $data[id_concepto];		
		$timestamp 			 = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_autorizaciones_nomina] SET
					id_horas_extra 			='$id_horas_extra',
					id_cat_autorizaciones 	= '$id_cat_autorizacion',
					anio 					= '$anio',
					semana 					= '$semana',
					periodo					= '$periodo',
					periodo_especial		= '$periodo_especial',
					horas 					= '$horas',
					id_concepto 			= '$id_concepto',					
					id_usuario 				= '$usuario[id_usuario]',
					timestamp 				= '$timestamp'
					;";
			// dump_var($sql);
		$resultado = (SQLDo($sql))?true:false;
	}
	return $resultado;
}
/*FinLayout*/

/**
* Aministración XLS
*/
function select_xls($data=array()){
/**
* Listado de registros que se incluiran en el XLS
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
					,c.nombre as empresa
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num					
					,a.fecha					
					,a.semana_iso8601
					,a.horas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					,d.anio
					,d.periodo
					,d.semana
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				WHERE 1 $filtro AND n4.estatus=1 AND d.id_autorizacion_nomina IS NOT NULL AND d.xls IS NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function select_xls_resumen($data=array()){
/**
* Listado de registros que se incluiran en el XLS-Resumen
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
					,c.id_nomina as id_empresa
					,c.nombre as empresa
					,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombre_completo
					,b.empleado_num					
					,a.fecha					
					,a.semana_iso8601
					,a.horas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=0,d.horas,NULL)))),'%H:%i') AS horas_rechazadas
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=2,d.horas,NULL)))),'%H:%i') AS horas_dobles
					,TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(d.id_concepto=3,d.horas,NULL)))),'%H:%i') AS horas_triples
					,d.anio
					,d.periodo
					,d.semana
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				WHERE 1 $filtro AND d.xls IS NOT NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function select_xls_nomina($data=array()){
/**
* Listado de registros que se incluiran en el XLS-Resumen
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
		$sql = "SELECT 
					 c.id_nomina as id_empresa
					,b.empleado_num
					,d.semana
					,e.clave as id_concepto
					,TIME_FORMAT(d.horas,'%H') as horas
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_conceptos] e ON d.id_concepto=e.id_concepto
				WHERE 1 $filtro AND d.id_autorizacion_nomina IS NOT NULL AND d.xls IS NULL
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
function update_xls($data=array()){
	// Actualiza datos al generar archivo xls
	$resultado = false;
	if($data[auth]){
		global $db, $usuario;
		$campos = array();
		$timestamp = date('Y-m-d H:i:s');
		$id_horas_extra = (is_array($data[id_horas_extra]))?implode(',',$data[id_horas_extra]):$data[id_horas_extra];		
		$campos [] = ($data[xls])?"a.xls='$data[xls]'":'';	
		$campos = implode(',',array_filter($campos));
		$filtro .= filtro_grupo(array(
						 10 => ''
						,20 => "and b.id_empresa='$usuario[id_empresa]'"
						,30 => "and b.id_empresa='$usuario[id_empresa]'"
						,40 => "and b.id_empresa='$usuario[id_empresa]'"
						,50 => "and b.id_empresa='$usuario[id_empresa]'"
						,60 => "and a.id_usuario='$usuario[id_usuario]'"
				));		
		$filtro	.= ($id_horas_extra)?" and a.id_horas_extra IN ($id_horas_extra)":'';
		if(!empty($campos)){
			$sql = "UPDATE $db[tbl_autorizaciones_nomina] a
					LEFT JOIN $db[tbl_horas_extra] b ON a.id_horas_extra=b.id_horas_extra			
					SET $campos
					WHERE 1 $filtro
					;";
			$resultado = (SQLDo($sql))?true:false;
		}
	}
	return $resultado;
}

function select_xls_lista($data=array()){
/**
* Listado de registros que se incluiran en el XLS
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
		$filtro.= ($activo)?" and d.activo IN ($activo)":'';
		$filtro.= ($id_usuario)?" and a.id_usuario IN ($id_usuario)":'';
		$grupo 	= ($grupo)?"GROUP BY $grupo":"GROUP BY d.xls";
		$orden 	= ($orden)?"ORDER BY $orden":"ORDER BY d.xls ASC";		
		$sql = "SELECT 
					 c.id_nomina as id_empresa
					,c.nombre as empresa
					,d.anio
					,d.periodo
					,d.semana
					,d.xls
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_autorizaciones] AS n1 ON a.id_horas_extra=n1.id_horas_extra AND n1.id_cat_autorizacion=1
				LEFT JOIN $db[tbl_autorizaciones] AS n2 ON a.id_horas_extra=n2.id_horas_extra AND n2.id_cat_autorizacion=2
				LEFT JOIN $db[tbl_autorizaciones] AS n3 ON a.id_horas_extra=n3.id_horas_extra AND n3.id_cat_autorizacion=3
				LEFT JOIN $db[tbl_autorizaciones] AS n4 ON a.id_horas_extra=n4.id_horas_extra AND n4.id_cat_autorizacion=4 
				WHERE 1 $filtro AND d.xls IS NOT NULL
				$grupo 
				$orden;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}

function select_xls_nomina_rebuild($data=array()){
/**
* Listado de registros que se incluiran en el XLS-Resumen
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
		$sql = "SELECT 
					 b.empleado_num
					,d.semana
					,e.clave as id_concepto
					,TIME_FORMAT(d.horas,'%H') as horas
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_empresa=b.id_empresa AND a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_empresas] c ON a.id_empresa=c.id_empresa
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_conceptos] e ON d.id_concepto=e.id_concepto
				WHERE 1 $filtro AND d.id_autorizacion_nomina IS NOT NULL AND d.xls IS NOT NULL
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}

function pgsql_select_periodo_activo($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_empresa = $usuario[id_empresa_nomina];
		$sql="SELECT
				 id_empresa
				,periodo
				,periodo_especial
				,ano_periodo as periodo_anio
				,fecha_inicio
				,fecha_fin
				,id_estatus_periodo as estatus
			FROM $db[pgsql_vista_cat_periodos] 
			WHERE id_estatus_periodo=1 AND id_empresa='$id_empresa';";
		$resultado = pgquery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}
	return $resultado;
}
/*FinLayout*/

function select_catalgo_supervisores($data=array()){
/**
* Listado de usuarios del sistema
*/
	if($data[auth]){
		global $db, $usuario;
		$id_nomina = (is_array($data[id_nomina]))?implode(',',$data[id_nomina]):$data[id_nomina];
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 10 => ''
					,20 => "and a.id_empresa='$usuario[id_empresa]'"
					,30 => "and a.id_empresa='$usuario[id_empresa]'"
					,40 => "and a.id_empresa='$usuario[id_empresa]'"
					,50 => "and a.id_empresa='$usuario[id_empresa]'"
					,60 => "and a.id_personal='$usuario[id_personal]'"
				));
		$filtro.= ($id_nomina)?" and a.id_nomina IN ($id_nomina)":'';
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($id_number)?" and a.id_number IN ($id_number)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_empresa, a.nombre, a.paterno, a.materno'.$desc;
		$sql="SELECT 
				 a.id_personal
				,a.id_empresa
				,b.razon as empresa
				,a.empleado_num
				,CONCAT(IFNULL(a.nombre,''),' ', IFNULL(a.paterno,''),' ',IFNULL(a.materno,'')) as nombre
				,a.puesto
				,a.sucursal
				,c.id_grupo
				FROM $db[tbl_personal] a
				LEFT JOIN $db[tbl_empresas] b ON a.id_empresa=b.id_empresa
				LEFT JOIN $db[tbl_usuarios] c ON a.id_personal=c.id_personal
				WHERE 1 AND c.id_grupo BETWEEN 30 AND 50 $filtro 
				$grupo $orden ;";
				// dump_var($sql);
		$resultado = SQLQuery($sql);				
		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}

function insert_supervisor($data=array()){
/**
* Inserta Supervisor
*/
	$resultado = false;	
	if($data[auth]){
		global $db, $usuario;
		$id_empresa 	= $data[id_empresa];
		$id_usuario 	= $data[id_usuario];
		$id_supervisor	= $data[id_supervisor];
		$id_nivel 		= $data[id_nivel];
		$timestamp 		= date('Y-m-d H:i:s');
		$sql="SELECT id_personal FROM $db[tbl_usuarios] WHERE id_usuario='$id_usuario';";
		$id_personal = SQLQuery($sql);
		$sql="INSERT INTO $db[tbl_supervisores] 
			SET
				 id_empresa 	='$id_empresa'
				,id_personal	='$id_personal[0]'
				,id_supervisor	='$id_supervisor'
				,id_nivel		='$id_nivel'
				,id_usuario 	='$usuario[id_usuario]'
				,timestamp 		='$timestamp'
			;";
		// $id = $id=SQLDo($sql);
		$resultado = (SQLDo($sql))?true:false;
		// $resultado = ($id)?$id:false;
	}
	return $resultado;
}
function insert_supervisor_sincronizacion($data=array()){
	global $db,$usuario;
	$nivel 			=	$data[nivel];
	$id_personal 	=	$data[id_personal];
	$id_empresa 	=	$data[id_empresa];
	$id_supervisor 	=	$data[id_supervisor];
	$timestamp 		= date('Y-m-d H:i:s');
	$sql="INSERT INTO 
			$db[tbl_supervisores]
			SET 
				id_empresa		=	$id_empresa
				id_personal		=	$id_personal
				id_supervisor	=	$id_supervisor
				id_nivel 		=	$nivel
				id_usuario  	=	$usuario[id_usuario]
				timestamp      	= 	$timestamp;";
		echo $sql;
	$resultado = (SQLDo($sql))?true:false;
}
?>