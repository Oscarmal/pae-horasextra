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