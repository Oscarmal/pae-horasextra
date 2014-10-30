//O3M//
$(document).ready(function(){
	$('#btnGuardar').hide();
	slider_horas();
	$('#restan').val(parseInt($("#horas").val()));

	$('#reload').click(function(){
		reset_slider();	
	});
});

function reset_slider(){
	$('#btnGuardar').hide();
	slider_horas();	
	$('#restan').val(parseInt($("#horas").val()));	
}

function slider_horas(){	
// Contruye sliders con valores iniciales
	var horas = $("#horas").val();	
	horas = parseInt(horas);
	var fecha = $("#fecha").val(); 
	var f = fecha.split('/');
	var semana = semanaNum('"'+f[2]+'-'+f[1]+'-'+f[0]+'"');
	build_slider("slider-semana", semana, 53, 1, "semana");
	build_slider("slider-dobles", 0, horas, 0, "dobles");
	build_slider("slider-triples", 0, horas, 0, "triples");
	build_slider("slider-rechazadas", 0, horas, 0, "rechazadas");
}

function build_slider(id_Objeto, valor, max, min, idMuestra) {
// Funcion para contruir un slider
	valor = parseInt(valor);
	$("#"+id_Objeto).slider({
	  range: "min",
	  value: valor,
	  min: min,
	  max: max,
	  step: 1,
      animate: 100,
	  slide: function(event, ui) {
	    $("#"+idMuestra).val(ui.value);
	  },
	  stop: function(event,ui){
		rebuild_slider(ui.value);
	  }
	});
	var valActual = $("#"+id_Objeto).slider("value");
	$("#"+idMuestra).val(valActual);
}

function rebuild_slider(horas){
// Validacion de valores y recontruccion de sliders
	var maximo = parseInt($('#horas').val());
	var dobles = parseInt($('#dobles').val());
	var triples = parseInt($('#triples').val());
	var rechazadas = parseInt($('#rechazadas').val());
	var restan = maximo-(dobles+triples+rechazadas);
	if(restan){
		// dobles
		if(dobles < restan)
		$('#slider-dobles').slider( "option", "max", restan );
		// triples
		if(triples < restan)
		$('#slider-triples').slider( "option", "max", restan );
		// rechazadas
		if(rechazadas < restan)
		$('#slider-rechazadas').slider( "option", "max", restan );			
	}
	// restan
	$('#restan').val(restan);
	// Boton Guardar
	if(restan==0){
		$('#btnGuardar').show();
	}else{
		$('#btnGuardar').hide();
	}
}

function btnSubmit(){
	var raiz = raizPath();
	var semana = parseInt($('#semana').val());
	var maximo = parseInt($('#horas').val());
	var dobles = parseInt($('#dobles').val());
	var triples = parseInt($('#triples').val());
	var rechazadas = parseInt($('#rechazadas').val());
	var restan = maximo-(dobles+triples+rechazadas);
	var msj = '';
	var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
	if(semana>=1){
		confirmar=confirm("Confirme que el número de semana es: "+semana+"");
    	if(!confirmar){
			$("#semana").focus();
			return false;
		}
	}else{return false;}
	if(restan!=0){
		msj = "<div class='popup-txt'>Aún tiene <b>"+restan+"</b> horas por asignar en este registro...</div>";
		popup('Validación',popup_ico+msj,0,0,1,'horas');
		$("#horas").focus();
		return false;
	}	
	obtenerCampos();
}

function obtenerCampos(){
	var id_horas_extra = $("#id_horas_extra").val();
	var semana = parseInt($('#semana').val());
	var dobles = parseInt($('#dobles').val());
	var triples = parseInt($('#triples').val());
	var rechazadas = parseInt($('#rechazadas').val());
	// Creación de array con todos los datos capturados
	var array = [
		'id_horas_extra=' + id_horas_extra,
		'semana=' + semana,
		'dobles=' + dobles,
		'triples=' + triples,
		'rechazadas=' + rechazadas
	];    
	// Metemos creamos cadena con namescapes
	var separador = '|';
    var data = array.join(separador);
    // 
	guardar(data);
}

function guardar(array){	
	var modulo = $("#mod").val().toLowerCase();
	var seccion = $("#sec").val();
	var raiz = raizPath();
	var ajax_url = raiz+"src/"+modulo+"/autorizacion.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	if(array){
		$.ajax({
			type: 'POST',
			url: ajax_url,
			dataType: "json",
			data: {      
				auth : 1,
				modulo : modulo,
				seccion : seccion,
				accion : 'autorizacion-guardar',
				datos : array
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
					setTimeout(function(){location.reload(true);}, 2000);
				}else if(respuesta.success){
					txt = respuesta.error;
					ventana = popup('Error',popup_ico+txt,0,0,3);
				}				
			}
			,complete: function(){ 
				setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);
					// $(location).attr('href', 'index.php?m=e893515facb496962eb10c96de1ca208&s=7d1bf948636232e0a8702ea5abbc4965');
				}, 2000);
			}
	    });
	}else{
		popup_ico = "<img src='"+raiz+"common/img/popup/alert.png' valign='middle' align='texttop'>&nbsp";
		var txt = "No hay datos para guardar.";		    
	    ventana = popup('Mensaje!',popup_ico+txt,0,0,3);
		setTimeout(function(){			
			location.reload(true);
			// $(location).attr('href', 'index.php?m=e893515facb496962eb10c96de1ca208&s=7d1bf948636232e0a8702ea5abbc4965');
		}, 2000);
	}
}
//O3M//