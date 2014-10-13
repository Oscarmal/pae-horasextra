//O3M//
$(document).ready(function(){
	scriptJs_Enter(); //Carga detección de ENTER
	jquery_fecha('txtFecha');
	jgrid('jGrid');

	// Campo de Horas
	// $(function(){
	//     $('#hora').clockface({
	//         format: 'HH:mm',
	//         trigger: 'manual'
	//     });   
	 
	//     $('#toggle-btn').click(function(e){   
	//         e.stopPropagation();
	//         $('#hora').clockface('toggle');
	//     });
	// });
});

function btnSubmit(){
	var raiz = raizPath();
	var horas = $('#txtHoras').val();
	var fecha = $('#txtFecha').val();
	var msj = '';
	var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
	if(horas == ''){
		msj = "<div class='popup-txt'>Ingrese la cantidad de horas extra trabajadas, por favor...</div>";
		popup('Horas extra',popup_ico+msj,0,0,1,'txtHoras');
		$("#txtHoras").focus();
		return false;
	}
	if(fecha == ''){
		msj = "<div class='popup-txt'>Seleccione la fecha en la que se trabajó, por favor...</div>";
		popup('Fecha',popup_ico+msj,0,0,1,'txtFecha');
		$("#txtFecha").focus();
		return false;
	}	
	guardar(horas, fecha);
}

function guardar(horas, fecha){		
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();
	var id_usuario = $("#id_usuario").val();
	var raiz = raizPath();
	var ajax_url = raiz+"src/"+modulo+"/captura.php";
	popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			seccion : seccion,
			accion : 'insert',
			id_usuario: id_usuario,
			horas : horas,
			fecha : fecha
		},
		beforeSend: function(){    
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			var txt = "<div class='popup-txt'>Guardando información, por favor espere...</div>";		    
		    ventana = popup('Guardando...',popup_ico+txt,0,0,3);		    
		},
		success: function(respuesta){ 
			$("#"+ventana).dialog( "close" );
			if(respuesta.success){				
				txt = "<div class='popup-txt'>La información ha sido guardada correctamente.</div>";
				ventana = popup('Éxito',popup_ico+txt,0,0,3);				
				setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		},
		complete: function(){ 
			setTimeout(function(){
				$("#"+ventana).dialog("close");
				$(location).attr('href', 'index.php?m=d3df3bcb86e5dab0114773964cfab1f4&s=d3df3bcb86e5dab0114773964cfab1f4');
			}, 2000);
		}
    });
}
//O3M//