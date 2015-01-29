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
	var nivel1	 		 =  $( "#nivel1 option:selected" ).val();
	var nivel2	 		 =  $( "#nivel2 option:selected" ).val();
	var nivel3	 		 =  $( "#nivel3 option:selected" ).val();
	var nivel4	 		 =  $( "#nivel4 option:selected" ).val();
	var nivel5	 		 =  $( "#nivel5 option:selected" ).val();

	if(nombre==''|| apellido_paterno==''|| sucursal==''|| puesto==''|| no_empleado ==''||id_empresa=='' || correo=='' || id_usuario=='' || nivel1=='' || nivel2=='' || nivel3=='' || nivel4=='' || nivel5==''){
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
			id_usuario 	: id_usuario,
			nivel1 : nivel1,
			nivel2 : nivel2,
			nivel3 : nivel3,
			nivel4 : nivel4,
			nivel5 : nivel5
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
				setTimeout(function(){location.reload(true);}, 2000);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
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

/**
* Layout
*/
function layout(id_horas_extra){
/**
* AJAX: Genera popup para validación de hotras extra
*/
	$("#layout-popup").empty();
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";	
	var contenidoHtml = '<div id="layout-popup"></div>';
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'layout-popup',
			id_horas_extra : id_horas_extra
		}		
		,success: function(respuesta){ 
			if(respuesta.success){
				var vistaHTML = respuesta.html;				
				ventana = popup('Layout',contenidoHtml,550,500,3);
				$("#layout-popup").html(vistaHTML);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		}
    });
}


/*XLS*/
function genera_xls(){
	$("#xls-popup").empty();
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var contenidoHtml = '<div id="xls-popup"></div>';	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'genera-xls-nomina'
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
					// location.reload(true);
				}, 2000);
			}else{
				popup_ico = "<img src='"+raiz+"common/img/popup/error.png' valign='middle' align='texttop'>&nbsp";
				var txt = "Se ha generado un error.";		    
			    ventana = popup('Error!',popup_ico+txt,0,0,3);
				setTimeout(function(){	
					// location.reload(true);
				}, 2000);
			}				
		}
    });
}
function genera_xls_rebuild(accion){
	$("#xls-popup").empty();
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var contenidoHtml = '<div id="xls-popup"></div>';	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";
	accion = (!accion)?'regenera-xls-nomina':accion;
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : accion
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
					// location.reload(true);
				}, 2000);
			}else{
				popup_ico = "<img src='"+raiz+"common/img/popup/error.png' valign='middle' align='texttop'>&nbsp";
				var txt = "Se ha generado un error.";		    
			    ventana = popup('Error!',popup_ico+txt,0,0,3);
				setTimeout(function(){	
					// location.reload(true);
				}, 2000);
			}				
		}
    });
}
/*Layout*/

function supervisores(id_personal,id_empresa){
	/**
* AJAX: Genera popup para validación de hotras extra
*/
	$("#layout-popup").empty();
	var raiz = raizPath();
	var modulo = $("#mod").val().toLowerCase(); // <-- Modulo actual del sistema
	var seccion = $("#sec").val();	
	var ajax_url = raiz+"src/"+modulo+"/admin.php";	
	var contenidoHtml = '<div id="layout-popup"></div>';
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'supervisor-popup',
			id_personal : id_personal,
			id_empresa : id_empresa
		}		
		,success: function(respuesta){ 
			if(respuesta.success){
				var vistaHTML = respuesta.html;				
				ventana = popup('Supervisor',contenidoHtml,550,200,3);
				$("#layout-popup").html(vistaHTML);
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		}
    });
}
