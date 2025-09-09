
//Dependiendo de lo que escoja la persona en registro le muestra de empresa o cliente
function mostrarFormulario(tipo) {
    document.getElementById('tipo-cuenta-box').style.display = 'none';

    if (tipo === 'cliente') {
        document.getElementById('formulario-cliente').style.display = 'block';
    } else if (tipo === 'empresa') {
        document.getElementById('formulario-empresa').style.display = 'block';
    }
}
