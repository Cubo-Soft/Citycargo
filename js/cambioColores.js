var boton_ = null;
function colorEntra(boton) {
    boton_ = "#" + boton.id;
    $(boton_).addClass("btn-success agrandarBoton");
}

function colorSale(boton) {
    boton_ = "#" + boton.id;
    $(boton_).removeClass("btn-success agrandarBoton");
}

function colorIndexEntra(boton){
    boton_ = "#" + boton.id;
    $(boton_).addClass("btn-success agrandarBotonIndex");
}

function colorIndexSale(boton){
    boton_ = "#" + boton.id;
    $(boton_).removeClass("btn-success agrandarBotonIndex");
}