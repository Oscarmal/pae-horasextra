<?php session_name('o3m_he'); session_start(); include_once($_SESSION['header_path']);
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function captura_select($auth=0, $id_horas_extra=0, $id_personal=0, $empleado_num=0, $grupo=0, $orden=0, $desc=0){
	if($auth){
		global $db, $usuario;
		$filtro	= ($id_horas_extra)?" and a.id_horas_extra='$id_horas_extra'":'';
		$filtro.= ($id_personal)?" and a.id_personal='$id_personal'":'';
		$filtro.= ($empleado_num)?" and b.empleado_num='$empleado_num'":'';
		$desc 	= ($desc)?" DESC":' ASC';
		$grupo 	= ($grupo)?"GROUP BY $grupo":'GROUP BY a.id_horas_extra';
		$orden 	= ($orden)?"ORDER BY $orden".$desc:'ORDER BY a.id_horas_extra'.$desc;
		$sql = "SELECT a.id_horas_extra
					,CONCAT(b.nombre,' ', b.paterno,' ',b.materno) as nombre_completo
					,b.empleado_num
					,DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha
					,DATE_FORMAT(a.horas,'%H:%i') as horas
					,c.usuario as capturado_por
					,a.timestamp as capturado_el
				FROM $db[tbl_horas_extra] a
				LEFT JOIN $db[tbl_personal] b ON a.id_personal=b.id_personal
				LEFT JOIN $db[tbl_usuarios] c ON a.id_usuario=c.id_usuario
				WHERE 1=1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	}else{
		$resultado = false;
	}
	return $resultado;
}

function captura_insert($auth=0, $id_personal=0, $fecha='', $horas=0){
	if($auth){
		global $db, $usuario;
		$h = explode(':', $horas);
		$horas = (!$h[1]) ? $h[0].':00' : $h[0].':'.$h[1];
		$timestamp = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_horas_extra] SET
					id_personal='$id_personal',
					fecha = '$fecha',
					horas ='$horas',
					id_usuario = '$usuario[id_usuario]',
					timestamp = '$timestamp'
					;";
		$resultado = (SQLDo($sql))?true:false;
	}else{
		$resultado = false;
	}
	return $resultado;
}
/*O3M*/
?>