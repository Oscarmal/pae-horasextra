//O3M//
$(document).ready(function(){
	scriptJs_Enter(); //Carga detección de ENTER
	jgrid('jGrid');
});

function btnSubmit(){
	obtenerCampos();
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
    // Construimos el array assoc: campo => valor
	// var data=[];
	// var campo='';
	// var valor='';
	// for (var i=0; i<array.length; ++i) {
	// 	var a = array[i].split('=');
	//     data[a[0]] = a[1];
	// }
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
					// Link para descargar archivo de excel
					popup_ico = "<img src='"+raiz+"common/img/popup/info.png' class='popup-ico'>&nbsp";
					var linkXls = '<div class="xls"><ul><li><a href="'+respuesta.xls+'" target="_self" title="'+respuesta.archivo+'">Descarga Archivo</a></li></ul></div>';
					var boton = buildBtn('btnCerrar','CERRAR','location.reload(true);');
					var btnCerrar = '<div id="btn-xls">'+boton+'</div>';
					txt = "<div class='popup-txt'><p>La información ha sido guardada correctamente: </p></div>";
					ventana = popup('Éxito',popup_ico+txt+linkXls+btnCerrar,0,0,3);				
					// setTimeout(function(){location.reload(true);}, 2000);
				}else if(respuesta.success){
					txt = respuesta.error;
					ventana = popup('Error',popup_ico+txt,0,0,3);
				}				
			}
			// ,complete: function(){ 
			// 	setTimeout(function(){
			// 		$("#"+ventana).dialog("close");
			// 		$(location).attr('href', 'index.php?m=e893515facb496962eb10c96de1ca208&s=7d1bf948636232e0a8702ea5abbc4965');
			// 	}, 2000);
			// }
	    });
	}else{
		popup_ico = "<img src='"+raiz+"common/img/popup/alert.png' valign='middle' align='texttop'>&nbsp";
		var txt = "No hay datos para guardar.";		    
	    ventana = popup('Mensaje!',popup_ico+txt,0,0,3);
		setTimeout(function(){			
			$(location).attr('href', 'index.php?m=e893515facb496962eb10c96de1ca208&s=7d1bf948636232e0a8702ea5abbc4965');
		}, 2000);
	}
}
//O3M//