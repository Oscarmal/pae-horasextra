<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function login($usuario, $clave){
	global $db;
	$sql = "SELECT 
				 a.id_usuario
				,a.usuario
				,a.id_grupo
				,a.activo
				,a.login
				,b.id_personal
				,CONCAT(b.nombre,' ',IFNULL(b.paterno,''),' ',IFNULL(b.materno,'')) as nombreCompleto
				,b.empleado_num
				,b.email
				,c.nombre as empresa
				,c.id_empresa as id_empresa
				,c.id_nomina as id_empresa_nomina
				,c.pais
				,d.mod1
				,d.mod2
				,d.mod3
				,d.mod4
				,d.mod5
				,d.mod6
				,d.mod7
				,d.mod8
				,d.mod9
				,d.mod10
				FROM $db[tbl_usuarios] a
				LEFT JOIN $db[tbl_personal] b USING(id_personal)
				LEFT JOIN $db[tbl_empresas] c USING(id_empresa)
				LEFT JOIN $db[tbl_grupos] d ON a.id_grupo=d.id_grupo
				WHERE a.usuario='$usuario' and a.clave='$clave' and a.activo=1
				LIMIT 1;";
	$resultado = SQLQuery($sql);
	$resultado = ($resultado[0]) ? $resultado : false ;
	return $resultado;
}
/*O3M*/
function update_pass_user($user,$pass){
	global $db,$usuario;

	$sql="UPDATE 
			$db[tbl_usuarios]
		SET 
			usuario ='$user',
			clave 	='$pass',
			login 	= 1
		WHERE 
			id_usuario=$usuario[id_usuario];";
	//echo $sql;
	$resultado = SQLDo($sql);
	$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
?>