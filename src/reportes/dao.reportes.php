<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-10-30
* @author 		Oscar Maldonado
*/
function reporte01_select($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$anio 	= (is_array($data[anio]))?implode(',',$data[anio]):$data[anio];
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($anio)?" and DATE_FORMAT(a.fecha,'%Y') IN ($anio)":'';
		$filtro.=filtro_grupo(array(
                   10 => ''
                  ,20 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,30 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,40 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,50 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,60 => "and a.id_usuario='$usuario[id_usuario]'"
                   ));
		$sql = "SELECT 
					 a.id_empresa
					,g.nombre as empresa
					,g.siglas
					,DATE_FORMAT(a.fecha,'%Y') as anio_fecha
					-- ,SUM((DATE_FORMAT(a.horas,'%H'))) AS horas_capturadas
					,SUM(IF(d.horas IS NOT NULL,DATE_FORMAT(d.horas,'%H'),DATE_FORMAT(a.horas,'%H'))) AS horas_capturadas
					,SUM((IF(d.id_concepto IS NULL,DATE_FORMAT(a.horas,'%H'),0))) AS horas_pendientes
					,SUM((IF(d.id_concepto IS NOT NULL and d.id_concepto>0,d.horas/10000,0))) AS horas_autorizadas
					,SUM((IF(d.id_concepto=0,d.horas/10000,0))) AS horas_rechazadas
					/*,SUM((IF(d.id_concepto=1,d.horas/10000,0))) AS horas_simples*/
					,SUM((IF(d.id_concepto=2,d.horas/10000,0))) AS horas_dobles
					,SUM((IF(d.id_concepto=3,d.horas/10000,0))) AS horas_triples
					/*,COUNT(DISTINCT d.semana) AS tot_semanas*/
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario
				LEFT JOIN $db[tbl_empresas] g ON a.id_empresa=g.id_empresa
				WHERE 1 $filtro
				GROUP BY a.id_empresa, anio_fecha
				ORDER BY a.id_empresa, anio_fecha ASC
				;";		
		// dump_var($sql);
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}

function grafico01_select($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$anio 	= (is_array($data[anio]))?implode(',',$data[anio]):$data[anio];
		$grupo 	= (is_array($data[grupo]))?implode(',',$data[grupo]):$data[grupo];
		#$orden 	= (is_array($data[orden]))?implode(',',$data[orden]):$data[orden];
		#$desc 	= $data[desc];
		$filtro.= ($id_empresa)?" and a.id_empresa IN ($id_empresa)":'';
		$filtro.= ($anio)?" and DATE_FORMAT(a.fecha,'%Y') IN ($anio)":'';
		$filtro.=filtro_grupo(array(
                   10 => ''
                  ,20 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,30 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,40 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,50 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,60 => "and a.id_usuario='$usuario[id_usuario]'"
                   ));
		#$desc 	= ($desc)?" DESC":' ASC';
		$grupo 	= ($grupo)?"GROUP BY $grupo ":'GROUP BY a.id_empresa, anio_fecha ';
		#$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_empresa, anio_fecha'.$desc;
		$sql = "SELECT 
					 a.id_empresa
					,g.nombre as empresa
					,g.siglas
					,DATE_FORMAT(a.fecha,'%Y') as anio_fecha
					-- ,SUM((DATE_FORMAT(a.horas,'%H'))) AS horas_capturadas
					,SUM(IF(d.horas IS NOT NULL,DATE_FORMAT(d.horas,'%H'),DATE_FORMAT(a.horas,'%H'))) AS horas_capturadas
					,SUM((IF(d.id_concepto IS NULL,DATE_FORMAT(a.horas,'%H'),0))) AS horas_pendientes
					,SUM((IF(d.id_concepto IS NOT NULL and d.id_concepto>0,d.horas/10000,0))) AS horas_autorizadas
					,SUM((IF(d.id_concepto=0,d.horas/10000,0))) AS horas_rechazadas
					/*,SUM((IF(d.id_concepto=1,d.horas/10000,0))) AS horas_simples*/
					,SUM((IF(d.id_concepto=2,d.horas/10000,0))) AS horas_dobles
					,SUM((IF(d.id_concepto=3,d.horas/10000,0))) AS horas_triples
					/*,COUNT(DISTINCT d.semana) AS tot_semanas*/
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				LEFT JOIN $db[tbl_autorizaciones_nomina] d ON a.id_horas_extra=d.id_horas_extra
				LEFT JOIN $db[tbl_usuarios] f ON d.id_usuario=f.id_usuario
				LEFT JOIN $db[tbl_empresas] g ON a.id_empresa=g.id_empresa
				WHERE 1 
				$filtro $grupo 
				";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}

function empresas($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$activo = (is_array($data[activo]))?implode(',',$data[activo]):$data[activo];
		$filtro .= ($id_empresa)?" AND a.id_empresa IN ($id_empresa)":'';
		$filtro .= ($activo)?" AND b.activo IN ($activo)":'';
		$filtro.=filtro_grupo(array(
                   10 => ''
                  ,20 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,30 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,40 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,50 => "and a.id_empresa='$usuario[id_empresa]'"
                  ,60 => "and a.id_usuario='$usuario[id_usuario]'"
                   ));
		$sql = "SELECT 
					 a.id_empresa
					,b.nombre AS empresa
					,b.siglas
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_empresas] b ON a.id_empresa=b.id_empresa
				WHERE 1 $filtro
				GROUP BY b.nombre
				ORDER BY b.id_empresa
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	}else{
		$resultado = false;
	}
	return $resultado;
}

function anios($data=array()){
	if($data[auth]){
		global $db; 
		$anio = (is_array($data[anio]))?implode(',',$data[anio]):$data[anio];
		$id_empresa = (is_array($data[id_empresa]))?implode(',',$data[id_empresa]):$data[id_empresa];
		$filtro .= ($anio)?" AND DATE_FORMAT(fecha,'%Y') IN ($anio)":'';
		$filtro .= ($id_empresa)?" AND id_empresa IN ($id_empresa)":'';
		$sql = "SELECT DATE_FORMAT(fecha,'%Y') AS anio 
				FROM $db[tbl_horas_extra] 
				WHERE 1 $filtro 
				GROUP BY anio DESC;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;
	}else{
		$resultado = false;
	}
	return $resultado;
}
/*O3M*/

function historial_usuario(){
	global $db,$usuario;
	$sql="SELECT 
			he_horas_extra.id_horas_extra,
			he_horas_extra.id_personal,
			he_horas_extra.fecha,
			he_horas_extra.horas as hora_extra,
			he_horas_extra.id_usuario,
			he_horas_extra.timestamp,
			he_horas_extra.id_empresa,
			he_horas_extra.estatus_fecha, 
			he_horas_extra.estatus,
			he_autorizaciones_nomina.id_horas_extra as id_hora_autoizacion,
			he_autorizaciones_nomina.id_autorizacion,
			he_autorizaciones_nomina.aut_estatus,
			he_autorizaciones_nomina.timestamp,
			TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(he_autorizaciones_nomina.id_concepto=0,he_autorizaciones_nomina.horas,NULL)))),'%H:%i') AS horas_rechazadas,
			TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(he_autorizaciones_nomina.id_concepto=1,he_autorizaciones_nomina.horas,NULL)))),'%H:%i') AS horas_simples,
			TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(he_autorizaciones_nomina.id_concepto=2,he_autorizaciones_nomina.horas,NULL)))),'%H:%i') AS horas_dobles,
			TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(he_autorizaciones_nomina.id_concepto=3,he_autorizaciones_nomina.horas,NULL)))),'%H:%i') AS horas_triples,
			he_personal.nombre,
			he_personal.paterno,
			he_personal.id_personal
		FROM 
			he_horas_extra
			LEFT JOIN 
				he_autorizaciones_nomina
				ON 
					he_horas_extra.id_horas_extra=he_autorizaciones_nomina.id_horas_extra
			LEFT JOIN 
				he_personal
				ON
					he_horas_extra.id_personal=he_personal.id_personal
		WHERE 
			he_horas_extra.id_personal=$usuario[id_usuario]
		GROUP BY 
			he_horas_extra.id_horas_extra";
					//echo $sql;
	$resultado = SQLQuery($sql);
	$resultado = (count($resultado)) ? $resultado : false ;
	return $resultado;
}
?>