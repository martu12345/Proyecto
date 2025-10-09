// guarda lo que la persona busca en la barra de búsqueda
// mantiene lo que busco la persona y  los resultados al volver atrás 

(function () {
    function restoreState(form, buscador, resultados) {
        const valorGuardado = sessionStorage.getItem('ultimaBusqueda');
        const resultadosGuardados = sessionStorage.getItem('ultimosResultados');

        if (valorGuardado) buscador.value = valorGuardado;

        if (resultados && resultadosGuardados && !resultados.innerHTML.trim()) {
            resultados.innerHTML = resultadosGuardados;
        }
    }

    function init() {
        const buscador = document.querySelector('.busqueda input[name="q"]');
        if (!buscador) return;

        const form = buscador.closest('form');
        if (!form) return;

        const resultados = document.querySelector('#resultados');

        // 🔹 Limpiar buscador solo si estamos en home y no hay búsqueda previa
        if (
            (window.location.pathname.includes("home_cliente.php") ||
            window.location.pathname.includes("home_empresa.php")) &&
            !sessionStorage.getItem('ultimaBusqueda')
        ) {
            buscador.value = "";
            sessionStorage.removeItem('ultimosResultados');
        }

        // Restaurar estado si existe
        restoreState(form, buscador, resultados);

        // Guardar al enviar
        form.addEventListener('submit', () => {
            sessionStorage.setItem('ultimaBusqueda', buscador.value);
            if (resultados) {
                sessionStorage.setItem('ultimosResultados', resultados.innerHTML);
            }
        });

        // Actualizar resultados en tiempo real
        if (resultados) {
            const observer = new MutationObserver(() => {
                sessionStorage.setItem('ultimosResultados', resultados.innerHTML);
            });
            observer.observe(resultados, { childList: true, subtree: true });
        }

        // Manejar "volver atrás" desde bfcache
        window.addEventListener('pageshow', () => restoreState(form, buscador, resultados));
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') restoreState(form, buscador, resultados);
        });

        // 🔹 Manejar clic en categorías del home
        document.querySelectorAll('.cuadro').forEach(cuadro => {
            cuadro.addEventListener('click', () => {
                const categoria = cuadro.dataset.busqueda;
                buscador.value = categoria;

                // Guardar búsqueda para que se mantenga en la página de resultados
                sessionStorage.setItem('ultimaBusqueda', categoria);
                sessionStorage.removeItem('ultimosResultados');

                form.submit();
            });
        });
    }

    document.addEventListener('DOMContentLoaded', init);
    
})();
