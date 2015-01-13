//O3M//
$(document).ready(function(){
	// scriptJs_Enter('autorizacion'); //Carga detección de ENTER
	jgrid('jGrid');
});

function btnSubmit(){
	sincronizar();
}

function sincronizar(){		
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'sincronizar'
		}
		,beforeSend: function(){ 
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			var txt = "Enviando petición, por favor espere...";	    	
			ventana = popup('Sincronizando...',popup_ico+txt,0,0,3);		
		}
		,success: function(respuesta){ 				
			$("#"+ventana).dialog("close");	
			if(respuesta.success){
				popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
				txt = "<div class='popup-txt'>La información ha sido sincronizada correctamente.</div>";
				ventana = popup('Éxito',popup_ico+txt,0,0,3);				
				//setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}							
		}
		,complete: function(){ 
		   /* setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);					
				}, 2000);*/
		}
    });
}
//O3M//
function alta_usuario(){
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";
	var nombre			 =	$('#nombre').val();
	var apellido_paterno =	$('#apellido_paterno').val();
	var apellido_materno =	$('#apellido_materno').val();
	var correo			 =	$('#correo').val();
	var rfc				 =	$('#rfc').val();
	var nss  			 =	$('#nss').val();
	var sucursal 		 =	$('#sucursal').val();
	var puesto 			 =	$('#puesto').val();
	var no_empleado	     =	$('#no_empleado').val();
	var id_empresa 		 =  $( "#empresa option:selected" ).val();
	var id_usuario 		 =  $( "#usuario option:selected" ).val();

	if(nombre==''|| apellido_materno==''|| sucursal==''|| puesto==''|| no_empleado ==''||id_empresa=='' ||id_usuario==''){
		alert("Los campos con punto rojo son obligatorios");
		return false;
	}
	if(correo!=''){
		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	    if ( !expr.test(correo) ){
	        alert("Error: La dirección de correo " + correo + " es incorrecta.");
	        return false
		}
	}

	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";

	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'nuevo_usuario',
			nombre : nombre,
			apellido_paterno : apellido_paterno,
			apellido_materno : apellido_materno,
			correo : correo,
			rfc	   : rfc,
			nss    : nss,
			sucursal : sucursal,
			puesto   : puesto,
			no_empleado : no_empleado,
			id_empresa  : id_empresa,
			id_usuario 	: id_usuario
		}
		,beforeSend: function(){ 
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			var txt = "Enviando petición, por favor espere...";	    	
			ventana = popup('Sincronizando...',popup_ico+txt,0,0,3);		
		}
		,success: function(respuesta){ 				
			$("#"+ventana).dialog("close");	
			if(respuesta.success){
				popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
				txt = "<div class='popup-txt'>La información ha sido sincronizada correctamente.</div>";
				ventana = popup('Éxito',popup_ico+txt,0,0,3);				
				//setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}							
		}
		,complete: function(){ 
		   /* setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);					
				}, 2000);*/
		}
    });
}

function sincronizar_empresa(){
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'sincronizar_empresa'
		}
		,beforeSend: function(){ 
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			var txt = "Enviando petición, por favor espere...";	    	
			ventana = popup('Sincronizando...',popup_ico+txt,0,0,3);		
		}
		,success: function(respuesta){ 				
			$("#"+ventana).dialog("close");	
			if(respuesta.success){
				popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
				txt = "<div class='popup-txt'>La información ha sido sincronizada correctamente.</div>";
				ventana = popup('Éxito',popup_ico+txt,0,0,3);				
				//setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}							
		}
		,complete: function(){ 
		   /* setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);					
				}, 2000);*/
		}
    });
}