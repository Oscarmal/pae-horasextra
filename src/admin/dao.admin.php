<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function select_view_nomina($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_nomina = (is_array($data[id_nomina]))?implode(',',$data[id_nomina]):$data[id_nomina];
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$id_number = (is_array($data[id_number]))?implode(',',$data[id_number]):$data[id_number];
		$activo = (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$grupo 			= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		$orden 			= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		$filtro.=filtro_grupo(array(
					 ''
					,"and a.id_empresa='$usuario[id_empresa_nomina]'"
					,"and a.id_empresa='$usuario[id_empresa_nomina]'"
					,"and a.id_usuario='$usuario[id_usuario]'"
				));
		$filtro.= ($id_nomina)?" and a.id_nomina IN ($id_nomina)":'';
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($id_number)?" and a.id_number IN ($id_number)":'';
		$filtro.= ($activo)?" and b.activo IN ($activo)":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_empleado'.$desc;		
		$sql="SELECT 
				 a.id_empresa
				,a.id_number
				,a.nombre
				,a.position
				,a.area
				,a.rfc
				,a.imss
				,a.ingreso
				,a.empresa
				,a.empresa_razon_social
				,a.id_empleado
				,IF(a.activo=1,'Activo','Inactivo') AS activo
				FROM $db[view_nomina] a
				LEFT JOIN $db[tbl_empresas] b ON a.id_empresa=b.id_nomina
				WHERE 1
				$filtro $grupo $orden ;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}
/*O3M*/
?>