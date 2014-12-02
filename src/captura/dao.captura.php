<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
/**
* 				Funciones "DAO"
* Descripcion:	Ejecuta consultas SQL y devuelve el resultado.
* Creación:		2014-08-27
* @author 		Oscar Maldonado
*/
function captura_select($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_horas_extra = $data[id_horas_extra];
		$id_personal 	= $data[id_personal];
		$empleado_num 	= $data[empleado_num];
		$estatus		= $data[estatus];
		$grupo 			= $data[grupo];
		$orden 			= $data[orden];
		$desc 			= $data[desc];
		$filtro	= ($id_horas_extra)?" and a.id_horas_extra='$id_horas_extra'":'';
		$filtro.= ($id_personal)?" and a.id_personal='$id_personal'":'';
		$filtro.= ($empleado_num)?" and b.empleado_num='$empleado_num'":'';
		if($status && $status!=1){
			$filtro.=" and d.estatus='$estatus'";
		}elseif($estatus){
			$filtro.=" and d.estatus IS NULL";
		}
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
				LEFT JOIN $db[tbl_autorizaciones] d ON a.id_horas_extra=d.id_horas_extra
				WHERE 1 
				$filtro $grupo $orden
				;";
		$resultado = SQLQuery($sql);
		$resultado = (count($resultado)) ? $resultado : false ;

	}else{
		$resultado = false;
	}
	return $resultado;
}

function captura_insert($data=array()){
	if($data[auth]){
		global $db, $usuario;
		$id_personal 	= $data[id_personal];
		$id_empresa 	= $data[id_empresa];
		$fecha 			= $data[fecha];
		$horas 			= horas_int($data[horas]);
		$timestamp = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $db[tbl_horas_extra] SET
					id_personal='$id_personal',
					id_empresa='$id_empresa',
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