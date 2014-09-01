//O3M//
$(document).ready(function(){
    // Quitar estilo rojo a inputs
    $('input').change(function(){
          inputFocus(this.id);
    });
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
    if(!idObjeto){ var idObjeto = 'popoup_' + getRandomInt(1, 100);}
    if(!Tipo){var Tipo = 0;}
    if(!w || w <= 0){var w = 400;}
    if(!h || h <= 0){var h = 200;}
    if(!Titulo){var Titulo = 'Sin titulo';}
    if(!Contenido){var Contenido = 'Sin contenido...';}
    var ventana = '<div id="' + idObjeto + '" class="' + ClaseCss + '" title="' + Titulo + '">' + Contenido + '</div>';
    $("#popups-alerts").empty();
    $("#popups-alerts").append(ventana);
    modal(idObjeto,w,h,Tipo);

    // Resaltar input
    if(idInput!=''){
        $("#"+idInput).addClass('input-error');
    }
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

//O3M//