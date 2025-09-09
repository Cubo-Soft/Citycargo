function retornarFechaActual() {
    var currentdate = new Date();
    var finalDate = currentdate.getFullYear();
    var month=(currentdate.getMonth() + 1);
    var day = currentdate.getDate();    
    var hour = currentdate.getHours();
    var minutes= currentdate.getMinutes();
    var seconds= currentdate.getSeconds();
    
    if(month<=9){
        finalDate+='-0'+month;
    }else{
        finalDate+='-'+month;
    }
    
    if(day<=9){
        finalDate+='-0'+day;
    }else{
        finalDate+='-'+day;
    }    
    
    if(hour<=9){
        finalDate+=' 0'+hour;
    }else{
        finalDate+=' '+hour;
    }    
    
    if(minutes<=9){
        finalDate+='-0'+minutes;
    }else{
        finalDate+='-'+minutes;
    }    
    
    if(seconds<=9){
        finalDate+='-0'+seconds;
    }else{
        finalDate+='-'+seconds;
    }        
    
    return finalDate;

}
