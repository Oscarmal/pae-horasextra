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

/**
* Autorización nivel 2
*/

/*2*/


/**
* Autorización nivel 3
*/

/*3*/


/**
* Autorización nivel 4
*/

/*4*/


/**
* Autorización nivel 5
*/

/*5*/


/*O3M*/
?>