
function cambio_contraseña(){
	var modulo 	      = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion 	  = $("#sec").val();
	var raiz 		  = raizPath();
	var ajax_url 	  = raiz+"src/"+modulo+"/login.php";
	var user 		= $("#user").val();
	var pass  		= $("#pass").val();
	if(user==''||pass==''){
		alert('No puede estar ningún campo vacío');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth    : 1,
			modulo  : modulo,
			accion 	: 'actualizacion_pass',
			s 	    : seccion,
			user 	: user,
			pass   	: pass
		},
		beforeSend: function(){
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			txt = "Actualizando información, por favor espere...";
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