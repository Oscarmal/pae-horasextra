//O3M//
$(document).ready(function(){
	// scriptJs_Enter('autorizacion'); //Carga detección de ENTER
	jgrid('jGrid');
});

function btnSubmit(){
	obtenerCampos();
	// genera_xls();
}

function ok(Objeto){
	var Id = Objeto.id.split("_");
	$('#ico-'+Id[1]).removeClass();
	if(Objeto.value=='no'){
		var Valor = false;
		var ClaseCss = 'ico-autorizacion-no';
		var Texto = 'Rechazado';
	}else if(Objeto.value){
		var Valor = true;
		var ClaseCss = 'ico-autorizacion-si';
		var Texto = 'Autorizado';
	}else{
		var Valor = false;
		var ClaseCss = 'ico-autorizacion';
		var Texto = 'Pendiente';
	}
	$('#ok_'+Id[1]).prop('checked', Valor);
	$('#ico-'+Id[1]).addClass(ClaseCss);
	$('#ico-'+Id[1]).attr('title', Texto);
}

function obtenerCampos(){
	// Mostrar todos los registros del Grid
    $('#jGridViewsList option:contains("Todos")').prop('selected', true);
	$("#jGridViewsList").change();
	// Obtener todos los Selects capturados
	var separador = '|';
    var valores = '';
    $(".campos").each(function(){
       if($(this).val()!=''){
          valores = valores + separador + $(this).serialize();
       }
    });
    // Metemos resultados a un array
    var array = valores.split(separador);
    array.splice(0, 1);	// Quita indice 0 que no trae datos
    var data = array.join(separador);
    // Mostrar el mínimo de registros en el Grid
    $('#jGridViewsList option[value="10"]').prop('selected', true);
	$("#jGridViewsList").change();

	guardar(data);
}

function guardar(array){	
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
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
				accion : 'insert',
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
					txt = "<div class='popup-txt'><p>La información ha sido guardada correctamente. </p></div>";
					ventana = popup('Éxito',popup_ico+txt,0,0,3);				
					// setTimeout(function(){location.reload(true);}, 2000);
				}else if(respuesta.success){
					txt = respuesta.error;
					ventana = popup('Error',popup_ico+txt,0,0,3);
				}				
			}
			,complete: function(){ 
				setTimeout(function(){
					$("#"+ventana).dialog("close");
					location.reload(true);
				}, 2000);
			}
	    });
	}else{
		popup_ico = "<img src='"+raiz+"common/img/popup/alert.png' valign='middle' align='texttop'>&nbsp";
		var txt = "No hay datos para guardar.";		    
	    ventana = popup('Mensaje!',popup_ico+txt,0,0,3);
		setTimeout(function(){			
			location.reload(true);
		}, 2000);
	}
}


// ---- Ver 1
function autorizar(id_horas_extra){
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
			accion : 'autorizacion-popup',
			id_horas_extra : id_horas_extra
		}		
		,success: function(respuesta){ 
			if(respuesta.success){
				var vistaHTML = respuesta.html;
				ventana = popup('Autorizar',contenidoHtml,500,500,3);
				$("#autorizar-popup").html(vistaHTML);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		}
    });
}

function genera_xls(){
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
    });
}

function layout(){
	$("#layout-popup").empty();
	var raiz = raizPath();
	var modulo = 'autorizacion';
	var contenidoHtml = '<div id="layout-popup"></div>';	
	var ajax_url = raiz+"src/"+modulo+"/autorizacion.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'layout-popup'
		}		
		,success: function(respuesta){ 
			if(respuesta.success){
				var vistaHTML = respuesta.html;
				ventana = popup('Layout',contenidoHtml,0,300,3);
				$("#layout-popup").html(vistaHTML);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		}
    });
}
//O3M//