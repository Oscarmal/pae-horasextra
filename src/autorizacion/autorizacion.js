//O3M//
$(document).ready(function(){
	jgrid('jGrid');
});

function ok(Objeto){
/**
* Detecta seleccion SI/NO y muestra imagen cambiando la clase
*/
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

function obtenerCampos(valor){
/**
* Obtiene los datos seleccionados de un grid
*/
	// Mostrar todos los registros del Grid
    $('#jGridViewsList option:contains("Todos")').prop('selected', true);
	$("#jGridViewsList").change();
	// Obtener todos los Selects capturados
	var separador = '|';
    var valores = '';
    var id = '';
    var j=1;
    if(valor==4){
    	$(".campos").each(function(){
	       if($(this).val()!=''){
	          valores = valores + separador +  $(this).serialize() + '='+$("#id_empresa_"+j).val() + '=' + $("#id_personal_"+j).val() + '=' + $("#horas_rechazadas_"+j).val() + '='+ $("#horas_dobles_"+j).val() + '='+ $("#horas_triples_"+j).val()+ '='+ $("#empleado_num_"+j).val();
	       }
	       j++;
	    });
    }
    else{
    	$(".campos").each(function(){
	       if($(this).val()!=''){
	          valores = valores + separador  +  $(this).serialize();
	       }
	       j++;
	    });
    }
    // Metemos resultados a un array
    var array = valores.split(separador);
    array.splice(0, 1);	// Quita indice 0 que no trae datos
    var data = array.join(separador);
    // Mostrar el mínimo de registros en el Grid
    $('#jGridViewsList option[value="10"]').prop('selected', true);
	$("#jGridViewsList").change(); 
	if(valor==1){
		guardar_autorizacion_1(data);
	}
	else if(valor==2){
		guardar_autorizacion_2(data);
	}
}

/**
* Autorización nivel 1
*/
function btnSubmit_autorizacion_1(){
	obtenerCampos(1);
	
}
function guardar_autorizacion_1(array){
/**
* AJAX: Envia datoa para guardar en tabla
*/
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
				accion : 'guardar_autorizacion_1',
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
/*1*/


/**
* Autorización nivel 2
*/
function btnSubmit_autorizacion_2(){
	obtenerCampos(2);
}

function guardar_autorizacion_2(array){
/**
* AJAX: Envia datoa para guardar en tabla
*/
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
				accion : 'guardar_autorizacion_2',
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
					 setTimeout(function(){location.reload(true);}, 2000);
				}else if(respuesta.success){
					txt = respuesta.error;
					ventana = popup('Error',popup_ico+txt,0,0,3);
				}				
			}
			// ,complete: function(){ 
			// 	setTimeout(function(){
			// 		$("#"+ventana).dialog("close");
			// 		location.reload(true);
			// 	}, 2000);
			// }
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
/*2*/


/**
* Autorización nivel 3
*/

/*3*/


/**
* Autorización nivel 4
*/

/*4*/


/**
* Autorización nivel 5
*/

/*5*/


/*O3M*/

//O3M//