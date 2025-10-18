
function mostrarRecibidos() {
    document.getElementById('recibidos').style.display = 'block';
    document.getElementById('enviados').style.display = 'none';
    document.getElementById('btnRecibidos').classList.add('active');
    document.getElementById('btnEnviados').classList.remove('active');
}

function mostrarEnviados() {
    document.getElementById('recibidos').style.display = 'none';
    document.getElementById('enviados').style.display = 'block';
    document.getElementById('btnRecibidos').classList.remove('active');
    document.getElementById('btnEnviados').classList.add('active');
}
