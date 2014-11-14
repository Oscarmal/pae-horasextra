//O3M//
$(document).ready(function(){
	scriptJs_Enter(); //Carga detección de ENTER
	jgrid('jGrid');
});

function xls(xls){
	var raiz = raizPath();
	var modulo = 'autorizacion';	
	var ajax_url = raiz+"src/autorizacion/autorizacion.php";
	popup_ico = "<img src='"+raiz+"common/img/wait.gif' valign='middle' align='center'>&nbsp";
	$.ajax({
		type: 'POST',
		url: ajax_url,
		dataType: "json",
		data: {      
			auth : 1,
			modulo : modulo,
			accion : 'regenera-xls',
			xls : xls
		}		
		,success: function(respuesta){ 
			if(respuesta.success){
				
			}else if(respuesta.success){
				var popup_ico = "<img src='"+raiz+"common/img/popup/error.png' class='popup-ico'>&nbsp";
				txt = respuesta.error;
				ventana = popup('Error',popup_ico+txt,0,0,3);
			}				
		}
    });
}
//O3M//