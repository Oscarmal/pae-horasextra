//O3M//
$(document).ready(function(){
	slider_semana();
});

function slider_semana(){	
// Contruye sliders con valores iniciales
	var f = new Date();
	var d = f.getDate();
	var m = f.getMonth() + 1;
	var y = f.getFullYear();
	semanaActual = semanaNum(y,m,d);
	build_slider("slider-semana", semanaActual, 53, 0, "semana");
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
		// rebuild_slider(ui.value);
	  }
	});
	var valActual = $("#"+id_Objeto).slider("value");
	$("#"+idMuestra).val(valActual);
}


function btnSubmit(){
	var raiz = raizPath();
	var semana = parseInt($('#semana').val());
	var msj = '';
	var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
	if(!semana){
		msj = "<div class='popup-txt'>La semana no puede esta en <b>cero</b>.</div>";
		ventana = popup('Validación',popup_ico+msj,0,0,1,'horas');
		setTimeout(function(){$("#"+ventana).dialog("close");}, 2000);
		$("#semana").focus();
		return false;
	}
	genera_xls(semana);
}

function genera_xls(semana){
	$("#autorizar-popup").empty();
	var raiz = raizPath();
	var modulo = 'autorizacion';
	var contenidoHtml = '<div id="autorizar-popup"></div>';	
	var ajax_url = raiz+"src/"+modulo+"/autorizacion.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			semana : semana,
			accion : 'genera-xls'
		}
		,beforeSend: function(){ 
			popup_ico = "<img src='"+raiz+"common/img/popup/load.gif' valign='middle' align='texttop'>&nbsp";
			var txt = "Generando archivo, por favor espere...";
	    	ventana = popup('Generando...',popup_ico+txt,0,0,3);
		}
		,success: function(respuesta){ 
			$("#"+ventana).dialog("close");
			if(respuesta.success){
				// Link para descargar archivo de excel
				popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
				var linkXls = '<div class="xls"><ul><li><a href="'+respuesta.xls+'" target="_self" title="'+respuesta.archivo+'">'+respuesta.archivo+'</a></li></ul></div>';
				var boton = buildBtn('btnCerrar','CERRAR','location.reload(true);');
				var btnCerrar = '<br/><div id="btn-xls">'+boton+'</div>';
				txt = "<div class='popup-txt'><p>Descargar el archivo: </p></div>";
				ventana = popup('Éxito',popup_ico+txt+linkXls+btnCerrar,0,220,3);	
			}else if(respuesta.nodata){
				popup_ico = "<img src='"+raiz+"common/img/popup/alert.png' valign='middle' align='texttop'>&nbsp";
				var txt = "No hay datos pendientes.";		    
			    ventana = popup('Mensaje!',popup_ico+txt,0,0,3);
				setTimeout(function(){
					location.reload(true);
				}, 2000);
			}else{
				popup_ico = "<img src='"+raiz+"common/img/popup/error.png' valign='middle' align='texttop'>&nbsp";
				var txt = "Se ha generado un error.";		    
			    ventana = popup('Error!',popup_ico+txt,0,0,3);
				setTimeout(function(){	
					location.reload(true);
				}, 2000);
			}				
		}
		,complete: function(){ 
			setTimeout(function(){
				$("#"+ventana).dialog("close");
				location.reload(true);
			}, 5000);
		}
    });
}
//O3M//