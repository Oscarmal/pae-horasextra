<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
// Define modulo del sistema
define(MODULO, $in[modulo]);
// Archivo DAO
require_once($Path[src].MODULO.'/dao.login.php');
require_once($Path[src].'views.vars.'.MODULO.'.php');
// Lógica de negocio
if(!empty($ins[usuario]) && !empty($ins[clave])){
	if($usuario = login($ins[usuario], $ins[clave])){
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('INICIO',1);
		$_SESSION[user]['id_usuario'] 		= $usuario[id_usuario];
		$_SESSION[user]['usuario'] 			= $usuario[usuario];
		$_SESSION[user]['activo'] 			= $usuario[activo];
		$_SESSION[user]['id_grupo']			= $usuario[id_grupo];
		$_SESSION[user]['id_personal']		= $usuario[id_personal];
		$_SESSION[user]['nombre']			= $usuario[nombreCompleto];
		$_SESSION[user]['empleado_num']  	= $usuario[empleado_num]; 
		$_SESSION[user]['email'] 			= $usuario[email];
		$_SESSION[user]['id_empresa'] 		= $usuario[id_empresa];		
		$_SESSION[user]['id_empresa_nomina']= $usuario[id_empresa_nomina];	
		$_SESSION[user]['empresa'] 			= $usuario[empresa];
		$_SESSION[user]['pais'] 			= $usuario[pais];
		$_SESSION[user]['accesos']['mod1']	= $usuario[mod1];
		$_SESSION[user]['accesos']['mod2']	= $usuario[mod2];
		$_SESSION[user]['accesos']['mod3']	= $usuario[mod3];
		$_SESSION[user]['accesos']['mod4']	= $usuario[mod4];
		$_SESSION[user]['accesos']['mod5']	= $usuario[mod5];
		$_SESSION[user]['accesos']['mod6']	= $usuario[mod6];
		$_SESSION[user]['accesos']['mod7']	= $usuario[mod7];
		$_SESSION[user]['accesos']['mod8']	= $usuario[mod8];
		$_SESSION[user]['accesos']['mod9']	= $usuario[mod9];
		$_SESSION[user]['accesos']['mod10']	= $usuario[mod10];	
		
		if($usuario[login]==1){
			$CONTENIDO = "?m=$modulo&s=$seccion";
			$success = true;
		}else{
			$success = 'logueo';
			$CONTENIDO = build_contrasenia_popup('primer_logueo');
		}
	}else{
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('LOGIN',1);
		$CONTENIDO = "?m=$modulo&s=$seccion&e=1";
		$success = false;		
	}
	$data = array(success => $success, url => $CONTENIDO);
}
if($in[accion]=='primer_logueo'){	
	$datos=validar_contrasenia();
	if($datos[clave]==$in[pass]){
		$success = 'igual';
		$CONTENIDO=0;
	}
	else{
		$success=update_pass_user($in[pass]);
		if($success){
			$modulo = encrypt('GENERAL',1);
			$seccion = encrypt('INICIO',1);
			$CONTENIDO = "?m=$modulo&s=$seccion";
		}
		else{
			$modulo = encrypt('GENERAL',1);
			$seccion = encrypt('LOGIN',1);
			$CONTENIDO = "?m=$modulo&s=$seccion&e=1";
			$success = true;
		}
	}
	$data = array(success => $success, url => $CONTENIDO);	
}

elseif($in[accion]=='contrasenia_popup'){	
	$CONTENIDO = build_contrasenia_popup('contrasenia_cambio');
	if($CONTENIDO){$success = true;}
	$data = array(success => $success, html => $CONTENIDO);
}

elseif($in[accion]=='contrasenia_cambio'){	
	$success=update_pass_user($in[pass]);
	if($success){
		$modulo = encrypt('GENERAL',1);
		$seccion = encrypt('INICIO',1);
		$CONTENIDO = "?m=$modulo&s=$seccion";
	}
	$data = array(success => $success, url => $CONTENIDO);
}

function build_contrasenia_popup($accion){
	global $Path;
	$vista_new 	= 'general/login_popup.html';
	$tpl_data = array(
			 MORE 	 => incJs($Path[srcjs].'general/login_popup.js')
			,id 	 	 	=> 1
			,nombre	 	=> 'USUARIO'
			,clave	 	=> 'CLAVE'
			,guardar 	=> 'Guardar'			
			,cerrar	 	=> 'Cerrar'
			,accion		=> $accion	
			);		
	return contenidoHtml($vista_new, $tpl_data);
}

// Resultado
$data = json_encode($data);
echo $data;
/*O3M*/
?>