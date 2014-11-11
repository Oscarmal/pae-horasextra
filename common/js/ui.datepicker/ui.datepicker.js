function jquery_fecha(id_objeto, formato, todaylimit){
    switch(formato) {
        case 1: formato = 'dd/mm/yy'; break;
        default:formato = 'yy-mm-dd'; break;
    }
    todaylimit = (!todaylimit)?'':'0';
    $.datepicker.regional['es'] = {
         closeText: 'Cerrar',
         prevText: '<< Anterior',
         nextText: 'Siguiente >>',
         currentText: 'Hoy',
         monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
         monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
         dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
         dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
         dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
         weekHeader: 'Sm',
         dateFormat: formato,
         firstDay: 0,
         isRTL: false,
         showMonthAfterYear: false,
         yearSuffix: '',
         maxDate: todaylimit,
         showAnim: "fadeIn" 
         };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(function () {
        $("#"+id_objeto).datepicker();
    });
}
/*O3M*/