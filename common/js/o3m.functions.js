//O3M//
$(document).ready(function(){
    // Quitar estilo rojo a inputs
    $('input').change(function(){
          inputFocus(this.id);
    });
    reloj('txtReloj');
    // Bootstrap
    // $('input[type=checkbox],input[type=radio],input[type=file]').uniform();
    // $('select').select2();
    // $('.colorpicker').colorpicker();
    // $('.datepicker').datepicker();
    // --

    // JQueri UI
    jQuery.datepicker.setDefaults($.datepicker.regional["es"]);
    // $( ".selector" ).datepicker({ monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ] });
    // $( ".selector" ).datepicker({ monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "Maj", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ] });
    // $( ".selector" ).datepicker({ dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ] });
    // $( ".selector" ).datepicker({ dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ] });
    // --
});

function changeCss(archivo) {
    var raiz = raizPath();
    var archivo = raiz + 'common/css/' + archivo;
    $('#archivoCss').attr('href', archivo);
}

function modal(idObjeto,w,h,tipo){
// Contruye Popup con diferentes efectos (jQueryUI)
    switch(tipo) {
    case 1:
        var Show        = "scale";
        var Hide        = "scale";
        var Resizable   = "false"; 
        var Position    = "center";
        var Modal       = "true";
        break;
    case 2:
        var Show        = "blind";
        var Hide        = "shake";
        var Resizable   = "false"; 
        var Position    = "center";
        var Modal       = "true";
        break;
    case 3:
        var Show        = "scale";
        var Hide        = "scale";
        var Resizable   = "false"; 
        var Position    = "center";
        var Modal       = "true";
        var Close       = 1;
        break;
    default:
        var Show        = "scale";
        var Hide        = "scale";
        var Resizable   = "false"; 
        var Position    = "center";
    }

    if(!w){var w = 300;}
    if(!h){var h = 200;}

    $("#"+idObjeto).dialog({
        autoOpen: false,
        width:      w,
        height:     h,
        show:       Show,
        hide:       Hide,
        resizable:  Resizable,
        // position:   Position,
        modal:      Modal
    });
    
    $("#"+idObjeto).dialog( "open" );
    if(Close){jQuery("a.ui-dialog-titlebar-close").hide();}
}

function popup(Titulo,Contenido,w,h,Tipo,idInput,idObjeto){
// Crea Ventana con contenido HTML para usar como Popup -> modal(idObjeto,w,h,tipo)
    var ClaseCss = 'modal';
    if(!idObjeto){ var idObjeto = 'o3m-popoup_' + getRandomInt(1, 100);}
    if(!Tipo){var Tipo = 0;}
    if(!w || w <= 0){var w = 400;}
    if(!h || h <= 0){var h = 200;}
    if(!Titulo){var Titulo = 'Sin titulo';}
    if(!Contenido){var Contenido = 'Sin contenido...';}
    var ventana = '<div id="' + idObjeto + '" class="' + ClaseCss + '" title="' + Titulo + '">' + Contenido + '</div>';
    $("#o3m-popups-alerts").empty();
    $("#o3m-popups-alerts").append(ventana);
    modal(idObjeto,w,h,Tipo);

    // Resaltar input
    if(idInput!=''){
        $("#"+idInput).addClass('input-error');
    }
    return idObjeto;
}

function getRandomInt(min, max) {
// Regresa un entrero entre min y max
  return Math.floor(Math.random() * (max - min)) + min;
}

function inputFocus(idInput){
// Remueve clase que resalta inputs
    $("#"+idInput).removeClass("input-error");
}

function scriptJs_Enter(){
// Carga script externo para deteccion de ENTER y ejecuta => btnSubmit()
    var raiz = raizPath();
    $.getScript(raiz + "common/js/inc.enter.js", function(){
    });
}

function raizPath(Folder){
// Obtiene Carpeta raiz
    if(!Folder){Folder='site';}
    Folrder = Folder + '/';
    var pathname = window.location.pathname;
    var raiz = window.location.pathname.split(Folder);
    var file = raiz[1];
    var niveles = file.split('/');
    var n = niveles.length -1;
    var raiz = '';
    for (var i = 1; i <= n; i++) { 
        raiz += '../';
    }
    return raiz;
}

function jquery_fecha(id_objeto){
    $(function() {
        $("#"+id_objeto).datepicker();
    });
}

function reloj(objName){ 
/**
* Muestra hora actual en vivo
* <body onload="reloj('objName')">
* <div id="reloj" onload="reloj('reloj')"></div>
*
*/
    var horaActual = new Date(); 
    var hora = horaActual.getHours();
    var minuto = horaActual.getMinutes(); 
    var segundo = horaActual.getSeconds(); 
    var str_segundo = new String (segundo); 
    if (str_segundo.length == 1) {
         segundo = "0" + segundo;
    }
    var str_minuto = new String (minuto); 
    if (str_minuto.length == 1) {
         minuto = "0" + minuto; 
    }
    var str_hora = new String (hora);
    if (str_hora.length == 1) {
         hora = "0" + hora;     
    }
    // horaUTC = horaActual.getUTCHours();
    if(str_hora>=12){
        var txt = 'pm';
    }else{ 
        var txt = 'am';
    }
    horaImprimible = hora + ":" + minuto + ":" + segundo + ' ' + txt; 
    document.getElementById(objName).innerHTML=horaImprimible;
    setTimeout("reloj('"+objName+"')",1000);
}

function buildBtn(idObjeto,texto,evento,clase){
    if(idObjeto){
        if(!texto){ texto = 'Botón';}       
        if(!clase){ clase = 'btn';}
        if(!evento){ evento = 'btnSubmit()';}
        var objeto = '<input type="button" id="'+idObjeto+'" name="'+idObjeto+'" class="'+clase+'" value="'+texto+'" onclick="'+evento+'">';
        return objeto;
    }else{
        return false;
    }
}

//O3M//