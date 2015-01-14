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
				,a.position
				,a.area
				,a.rfc
				,a.imss
				,a.ingreso
				,IF(a.empresa is null, b.nombre,a.empresa) as empresa
				,IF(a.empresa_razon_social is null, b.nombre,a.empresa_razon_social) as empresa_razon_social
				,a.id_empleado
				,b.id_nomina
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
	if(!$filtrado){
		$sql_alterno='';
	}else{
		$sql_alterno="WHERE id_empresa=$id_empresa";
	}		
		$sql="SELECT 
				* 
			FROM 
				$db[pgsql_vista_credenciales]
			$sql_alterno;";
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
		$sql_alterno="WHERE id_empresa=$usuario[id_empresa]";
	}
	
	$sql="SELECT 
				id_nomina,
				nombre
			FROM 
				$db[tbl_empresas] 
				$sql_alterno;";
		//echo $sql;
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
				id_grupo >20";
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
function select_empresas_nomina(){
	global $db, $usuario;
		
		$sql="SELECT DISTINCT 
				$db[pgsql_vista_credenciales].id_empresa,
				$db[pgsql_vista_credenciales].empresa,
				$db[pgsql_vista_credenciales].empresa_razon_social
			FROM 
				$db[pgsql_vista_credenciales]
			ORDER BY 
				$db[pgsql_vista_credenciales].id_empresa;";
				//echo $sql;
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

?>