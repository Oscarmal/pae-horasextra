//O3M//
$(document).ready(function(){
	$("#txtUsuario").focus();
	scriptJs_Enter(); //Carga detección de ENTER
});

function btnSubmit(){
	var raiz = raizPath();
	var usuario = $('#txtUsuario').val();
	var clave = $('#txtClave').val();
	var msj = '';
	var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' valign='middle' align='texttop'>&nbsp";
	if(usuario == ''){		
		msj = 'Ingrese su Usuario, por favor...';
		popup('Usuario',popup_ico+msj,0,0,1,'txtUsuario');
		$("#txtUsuario").focus();
		return false;
	}
	if(clave == ''){
		msj = 'Ingrese su Clave, por favor...';
		popup('Clave',popup_ico+msj,0,0,1,'txtClave');
		$("#txtClave").focus();
		return false;
	}	
	login2(usuario, clave);
}

/*function login(usuario, clave){	
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
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			txt = "Validando credenciales, por favor espere...";
		    popup('Autentificando',popup_ico+txt,0,0,3);  		    
		},
		success: function(respuesta){ 
			setTimeout(function(){	$(location).attr('href', respuesta.url)	}, 2000);
		},
		complete: function(){    			
		    $("#popups-alerts").empty();
		}
    });
}*/
//O3M//

function login2(usuario, clave){
	var modulo 	      = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion 	  = $("#sec").val();
	var raiz 		  = raizPath();
	var ajax_url 	  = raiz+"src/"+modulo+"/login.php";
	var contenidoHtml = '<div id="logueo-popup"></div>';
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth    : 1,
			modulo  : modulo,
			s 	    : seccion,
			usuario : usuario,
			clave   : clave
		},
		beforeSend: function(){
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			txt = "Validando credenciales, por favor espere...";
		    ventana=popup('Autentificando',popup_ico+txt,0,0,3);  		    
		},
		success: function(respuesta){ 
			if(respuesta.success==='logueo'){
				$("#"+ventana).dialog("close");
				var vistaHTML = respuesta.url;
				ventana = popup('Logueo',contenidoHtml,400,250,3);
				$("#logueo-popup").html(vistaHTML);
			}
			else if(respuesta.success){
				setTimeout(function(){	$(location).attr('href', respuesta.url)	}, 2000);
			}
		},
		complete: function(){    			
		    $("#popups-alerts").empty();
		}
    });
}