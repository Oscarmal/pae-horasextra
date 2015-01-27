<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
set_time_limit(0);
require_once($Path[src].MODULO.'/dao.'.strtolower(MODULO).'.php');
require_once($Path[src].MODULO.'/'.'layout.xls.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
global $usuario,$db;
// Lógica de negocio

if($in[auth]){
	if($ins[accion]=='sincronizar'){
		if($usuario[id_grupo]<20){
			$filtrado=false;
			$success=select_view_vista_credenciales($filtrado,$vacio);
		}
		else{
			$filtrado=true;
			$id_empresa=$usuario[id_empresa_nomina];
			$success=select_view_vista_credenciales($filtrado,$id_empresa);
		}
		//dump_var($success);
		$msj = ($success)?'Guardado':'No guardó';
			if($msj=='Guardado'){
				$valor=count($success);
				$query='';
				for($i=0; $i<=$valor-1; $i++){
				
					$id_empresa 			=	$success[$i][id_empresa];
					$id_number				=	$success[$i][id_number];
					$nombre					=	$success[$i][nombre_empleado];
					$paterno				=	$success[$i][apellido_paterno_empleado];
					$materno				=	$success[$i][apellido_materno_empleado];
					$email					=	(strlen($success[$i][correo_electronico])>3) ? $success[$i][correo_electronico] : '';
					$position				=	$success[$i][position];
					$area					=	$success[$i][area];
					$rfc					=	$success[$i][rfc];
					$imss					=	$success[$i][imss];
					$ingreso				=	$success[$i][ingreso];
					$empresa 				=	$success[$i][empresa];
					$empresa_razon_social	=	$success[$i][empresa_razon_social];
					$id_empleado			=	$success[$i][id_empleado];

					$query.="INSERT INTO
							$db[view_nomina]
						SET
							id_empresa 					=	(SELECT id_empresa FROM he_empresas WHERE id_nomina=$id_empresa),
							id_number 					=	'$id_number',
							nombre_empleado				=	'$nombre',
							apellido_paterno_empleado 	=	'$paterno',
							apellido_materno_empleado 	=	'$materno',
							correo_electronico			= 	'$email',
							position 					=	'$position',
							area 						=	'$area',
							rfc 						=	'$rfc',
							imss 						=	'$imss',
							ingreso 					=	'$ingreso',
							empresa 					=	'$empresa',
							empresa_razon_social 		=	'$empresa_razon_social',
							id_empleado 				=	'$id_empleado';\r\n";
				}
				$archivo=$Path[tmp].'insert_'.date('Ymd-His').'.sql';
				$file=fopen($archivo, 'w');
				$query2=count($query);
				fwrite($file,$query);
				fclose($file);
				
				$host = $db[db_local_host];
				$user = $db[db_local_user];
				$pass = $db[db_local_pass];
				$base = $db[db_local_db1];
				truncate_vista_nomina();

				$insert=exec("mysql -h ".$host." -u ".$user." -p".$pass." --default-character-set=latin1 ".$base." < ".$archivo);

				if ($insert === FALSE) {
				  $msj='No guardó';
				}
				else{
					//unlink($archivo);
					insert_sincronizacion_update();
					$msj='Guardado';
				}
			}
			else{
				$msj='No guardó';
			}
		$data = array(success => $msj, message => $msj);
	}
	elseif($in[accion]=='nuevo_usuario'){
		$nombre				=	mb_strtoupper($in[nombre], 'UTF-8');
		$apellido_paterno	=	mb_strtoupper($in[apellido_paterno], 'UTF-8');
		$apellido_materno	=	mb_strtoupper($in[apellido_materno], 'UTF-8');
		$correo				=	$in[correo];
		$rfc				=	mb_strtoupper($in[rfc], 'UTF-8');
		$nss				=	mb_strtoupper($in[nss], 'UTF-8');
		$sucursal			=	mb_strtoupper($in[sucursal], 'UTF-8');
		$puesto				=	mb_strtoupper($in[puesto], 'UTF-8');
		$no_empleado		=	mb_strtoupper($in[no_empleado], 'UTF-8');
		$id_empresa 		=	$in[id_empresa];
		$id_usuario_grupo 	=	$in[id_usuario];
		$timestamp 			= 	date('Y-m-d H:i:s');
		
		insert_nuevo_registro($nombre,$apellido_paterno,$apellido_materno,$correo,$rfc,$nss,$sucursal,$puesto,$no_empleado,$id_empresa,$id_usuario_grupo,$timestamp);
		$data = array(success => $msj, message => $msj);		
	}
	elseif($in[accion]=='sincronizar_empresa'){
		$select=select_he_empresas();
		if(count($select[id_empresa])==1){			
			$campo=$select[id_nomina];
		}
		else{
			$empresas=count($select);
			for($j=0;$j<$empresas;$j++){
				$campo.=$select[$j][id_nomina].',';
			}
		}
		$id_empresa=trim($campo, ',');
		$success=select_empresas_nomina($id_empresa);

		$msj = ($success)?'Guardado':'No guardó';
			if($msj=='Guardado'){
				$valor=count($success);
				$query='';
				$query.="CREATE TABLE IF NOT EXISTS `tmp_empresas_nomina` (
							  `id_empresa` smallint(4) NOT NULL AUTO_INCREMENT,
							  `nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
							  `siglas` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
							  `rfc` varchar(18) COLLATE utf8_spanish_ci DEFAULT NULL,
							  `razon` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
							  `direccion` text COLLATE utf8_spanish_ci,
							  `pais` varchar(15) COLLATE utf8_spanish_ci DEFAULT 'MX',
							  `email` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
							  `timestamp` datetime DEFAULT NULL,
							  `id_usuario` int(11) DEFAULT NULL,
							  `activo` tinyint(1) DEFAULT '0',
							  `id_nomina` int(11) DEFAULT NULL,
							  PRIMARY KEY (`id_empresa`),
							  KEY `i_nomina` (`id_nomina`)
							);";
				for($i=0; $i<=$valor-1; $i++){
				
					$id_nomina 		=	$success[$i][id_empresa];
					$empresa		=	$success[$i][empresa_razon_social];
					$siglas			=	$success[$i][empresa];
					$timestamp 		= 	date('Y-m-d H:i:s');
					$id_usuario 	= 	$usuario[id_usuario];

					$query.="INSERT INTO
							tmp_empresas_nomina
						SET
							nombre 			=	'$empresa',
							siglas 			=	'$siglas',
							razon 			=	'$empresa',
							timestamp 		=	'$timestamp',
							id_usuario 		=	$id_usuario,
							id_nomina 		=	'$id_nomina';\r\n";
				}
				$archivo=$Path[tmp].'insert_empresas'.date('Ymd-His').'.sql';
				$file=fopen($archivo, 'w');
				$query2=count($query);
				fwrite($file,$query);
				fclose($file);
				
				$host = $db[db_local_host];
				$user = $db[db_local_user];
				$pass = $db[db_local_pass];
				$base = $db[db_local_db1];

				$insert=exec("mysql -h ".$host." -u ".$user." -p".$pass." --default-character-set=latin1 ".$base." < ".$archivo);

				if ($insert === FALSE) {
				  $msj='No guardó';
				}
				else{
					$msj='Guardado';
					$success=insert_empresa_nomina_tmp();
					eliminar_tmp_empresa_nomina();
					unlink($archivo);
				}
			}
			else{
				 $msj='No guardó';
			}
		$data = array(success => $msj, message => $msj);
	}
	elseif($in[accion]=='layout-popup'){
		// Extraccion de datos
		$sqlData = array(
			 auth 			=> true
			,id_horas_extra	=> $in[id_horas_extra]
		);
		$datos = select_layout($sqlData);
		// Deteccion de semana del año ISO8601
		$datos_semama = select_acumulado_semanal(array(
			 auth 			=> 1
			,id_empresa 	=> $datos[id_empresa]
			,id_personal	=> $datos[id_personal]
			,fecha 			=> $datos[fecha]
		));
		$semana_iso8601 = ($datos_semama[semana_iso8601])?$datos_semama[semana_iso8601]:$datos[semana_iso8601];
		$semana_horas	= ($datos_semama[tot_horas])?$datos_semama[tot_horas]:0;
		$periodo = pgsql_select_periodo_activo(array(auth => 1));
		// Impresion de vista
		$vista_new 	= 'admin/layout_popup.html';
		$tpl_data = array(
				 MORE 			=> incJs($Path[srcjs].strtolower(MODULO).'/layout_popup.js')
				,id 	 		=> $datos[id_horas_extra]
				,nombre	 		=> $datos[nombre_completo]
				,clave	 		=> $datos[empleado_num]
				,fecha	 		=> $datos[fecha]
				,horas	 		=> $datos[horas]
				,periodo_anio	=> $periodo[periodo_anio]
				,periodo 		=> $periodo[periodo]
				,periodo_especial => $periodo[periodo_especial]
				,semana_iso 	=> $semana_iso8601
				,tot_horas		=> $semana_horas.' hrs.'
				,guardar 		=> 'Guardar'			
				,cerrar	 		=> 'Cerrar'			
				);		
		$CONTENIDO 	= contenidoHtml($vista_new, $tpl_data);
		// Envio de resultado
		$success = true;
		$msj = ($success)?'Popup OK':'Popup Fail';
		$data = array(success => $success, message => $msj, html => $CONTENIDO);			
	}
	elseif($in[accion]=='layout-guardar'){
		if(!empty($ins[datos])){				
			$datos = explode('|',$in[datos]);
			foreach($datos as $dato){
				$data = explode('=',$dato);	
				$data_arr[$data[0]]=$data[1];
				// id_conceptos OJO:hardcode
				$id_concepto[0]=($data[0]=='rechazadas')?0:$id_concepto[0];
				$id_concepto[1]=($data[0]=='simples')?1:$id_concepto[1];
				$id_concepto[2]=($data[0]=='dobles')?2:$id_concepto[2];
				$id_concepto[3]=($data[0]=='triples')?3:$id_concepto[3];
			}
			$id_horas_extra 	= $data_arr['id_horas_extra'];
			$anio     			= $data_arr['anio'];			
			$periodo   			= $data_arr['periodo'];
			$periodo_especial 	= $data_arr['periodo_especial'];
			$semana   			= $data_arr['semana'];
			$horas[0] 			= $data_arr['rechazadas'];
			$horas[1] 			= $data_arr['simples'];
			$horas[2] 			= $data_arr['dobles'];
			$horas[3] 			= $data_arr['triples'];	
			for($i=0; $i<=count($data_arr)-1; $i++){	
				if($horas[$i]){				
					// Save data in SQL
					$sqlData = array(
						 auth 				=> true
						,id_horas_extra		=> $id_horas_extra
						,anio				=> $anio
						,periodo			=> $periodo
						,periodo_especial 	=> $periodo_especial
						,semana				=> $semana
						,horas 				=> $horas[$i]
						,id_concepto 		=> $id_concepto[$i]
					);
					$success  =  insert_layout($sqlData);
				}
			}
			$msj = ($success)?'Guardado':'No guardó';			
			$data = array(success => $success, message => $msj);
		}else{
			$success = false;
			$msj = "Sin guardar por falta de datos.";
		}		
	}
	elseif($in[accion]=='genera-xls-nomina'){
		$success = false;
		$nodata = true;
		if($success = ($xls = xsl_nomina())?true:false){
			$msj = "Archivo generado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
	}
	elseif($in[accion]=='regenera-xls-nomina'){
		$success = false;
		$nodata = true;
		if($success = ($xls = xls_nomina_rebuild())?true:false){
			$msj = "Archivo generado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
	}
	elseif($in[accion]=='regenera-xls-resumen'){
		$success = false;
		$nodata = true;
		if($success = ($xls = xsl_resumen())?true:false){
			$msj = "Archivo generado";
			$nodata = false;
		}else{
			$msj = "Sin datos";
		}
		$data = array(success => $success, message => $msj, xls => $xls[url], archivo => $xls[filename], nodata => $nodata);
	}
	elseif(!$ins[accion]){
		$error = array(error => 'Sin accion');		
	}
	$data = json_encode($data);
}else{
	$error = array(error => 'Sin autorización');
	$data = json_encode($error);
}
// Resultado
echo $data;
/*O3M*/
?>