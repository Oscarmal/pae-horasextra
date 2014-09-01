//O3M//
$(document).ready(function(){
	scriptJs_Enter(); //Carga detección de ENTER
});

function btnSubmit(){
	var usuario = $('#txtUsuario').val();
	var clave = $('#txtClave').val();
	var msj = '';
	if(usuario == ''){
		msj = 'Ingrese su Usuario, por favor...';
		popup('Usuario',msj,0,0,1,'txtUsuario');
		$("#txtUsuario").focus();
		return false;
	}
	if(clave == ''){
		msj = 'Ingrese su Clave, por favor...';
		popup('Clave',msj,0,0,1,'txtClave');
		$("#txtClave").focus();
		return false;
	}	
	login(usuario, clave);
}

function login(usuario, clave){	
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();
	var raiz = raizPath();
	var ajax_url = raiz+"src/"+modulo+"/login.php";

	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			s : seccion,
			usuario : usuario,
			clave : clave
		},
		beforeSend: function(){    
			txt = "Validando credenciales, por favor espere...";
		    msj = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp "+txt;
		    popup('Autentificando',msj,0,0,3);  		    
		},
		success: function(respuesta){ 
			setTimeout(function(){	$(location).attr('href', respuesta.url)	}, 2000);
		},
		complete: function(){    			
		    $("#popups-alerts").empty();
		}
    });
}
//O3M//