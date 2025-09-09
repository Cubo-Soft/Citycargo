$(document).ready(function () {

    //input type="number"
    $("#valor").on('blur',function(){
        
    });

    $("#valor").onblur(function(){

    });
    //<input type="number" id="valor" />

    var tabla = 'construyo el código de la tabla ';

    for (let index = 0; index < obj.length; index++) {
        
        tabla +='<input type="number" id="v_'+obj[index]["id"]+'" value="'+obj[index]["valor"]+"' onblur='modificarValor()' ";
    }
    

});