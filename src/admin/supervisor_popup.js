function guardar_supervisor(){
	
	var id_supervisor=$( "#nivel_supervisor option:selected" ).val();
	var nivel;
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";	
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	if(id_supervisor==8){
		nivel=1;
	}
	else if(id_supervisor==7){
		nivel=2;
	}
	else if(id_supervisor==6){
		nivel=3;	
	}
	else if(id_supervisor==5){
		nivel=4;
	}
	else if(id_supervisor==4){
		nivel=5;
	}

	$.ajax({
			type: 'POST',
			url: ajax_url,
			dataType: "json",
			data: {      
				auth : 1,
				modulo : modulo,
				seccion : seccion,
				accion : 'supervisor-guardar',
				nivel : nivel,
				id_supervisor: id_supervisor
			}
			,beforeSend: function(){ 
				popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
				var txt = "Guardando información, por favor espere...";
		    	ventana = popup('Guardando...',popup_ico+txt,0,0,3);		    	
			}
			,success: function(respuesta){ 
				$("#"+ventana).dialog("close");
				if(respuesta.success){
					popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
					txt = "<div class='popup-txt'>La información ha sido guardada correctamente.</div>";
					ventana = popup('Éxito',popup_ico+txt,0,0,3);				
					//setTimeout(function(){location.reload(true);}, 2000);
				}else if(respuesta.success){
					txt = respuesta.error;
					ventana = popup('Error',popup_ico+txt,0,0,3);
				}				
			}
			,complete: function(){ 
				/*setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);
				}, 2000);*/
			}
	    });
}