/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * From: https://www.yoelprogramador.com/formatear-numeros-con-javascript/
 * Use: 
 * formatNumber.new(123456779.18, "$") // retorna "$123.456.779,18"
 * formatNumber.new(123456779.18) // retorna "123.456.779,18"
 * formatNumber.new(123456779) // retorna "$123.456.779"
 */

var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    formatear: function (num) {
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft + splitRight;
    },
    new : function (num, simbol) {
        this.simbol = simbol || '';
        return this.formatear(num);
    }
};