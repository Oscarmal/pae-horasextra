<?php session_name('o3m'); session_start(); include_once($_SESSION['header_path']);
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
				,a.grupo
				,a.activo
				,b.id_personal
				,CONCAT(b.nombre,' ',b.paterno,' ',b.materno) as nombreCompleto
				,b.empleado_num
				,b.email
				,c.nombre as empresa
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
				LEFT JOIN $db[tbl_accesos] d ON a.id_usuario=d.id_usuario
				WHERE a.usuario='$usuario' and a.clave='$clave' and a.activo=1
				LIMIT 1;";
	$resultado = SQLQuery($sql);
	$resultado = ($resultado[0]) ? $resultado : false ;
	return $resultado;
}
/*O3M*/
?>