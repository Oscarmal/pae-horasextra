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
	var horas = $('#txtHoras').val();
	var fecha = $('#txtFecha').val();
	var msj = '';
	if(horas == ''){
		msj = 'Ingrese la cantidad de horas extra trabajadas, por favor...';
		popup('Horas extra',msj,0,0,1,'txtHoras');
		$("#txtHoras").focus();
		return false;
	}
	if(fecha == ''){
		msj = 'Seleccione la fecha en la que se trabajó, por favor...';
		popup('Fecha',msj,0,0,1,'txtFecha');
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
	popup_ventana = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
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
			var txt = "Guardando información, por favor espere...";		    
		    ventana = popup('Guardando...',popup_ventana+txt,0,0,3);		    
		},
		success: function(respuesta){ 
			$("#"+ventana).dialog( "close" );
			if(respuesta.success){				
				txt = "La información ha sido guardada correctamente.";
				ventana = popup('Éxito',popup_ventana+txt,0,0,3);				
				setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				txt = respuesta.error;
				ventana = popup('Error',popup_ventana+txt,0,0,3);
			}				
		},
		complete: function(){ 
			setTimeout(function(){
				$("#"+ventana).dialog("close");
				$(location).attr('href', 'index.php?m=d3df3bcb86e5dab0114773964cfab1f4&s=7d1bf948636232e0a8702ea5abbc4965');
			}, 2000);
		}
    });
}
//O3M//