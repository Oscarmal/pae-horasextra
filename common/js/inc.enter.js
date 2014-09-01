$(document).ready(function(){
    // Detectar ENTER
    $(document).keypress(function(e) {
        if(e.which == 13) {
            btnSubmit();
        }
    });
});

function test(){
    alert('Script externo cargado!');
}