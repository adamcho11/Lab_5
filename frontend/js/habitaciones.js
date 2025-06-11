document.addEventListener("DOMContentLoaded", function() {
    cargarTodas();
});

function cargarTodas() {
    fetch("../api/habitaciones/mostrar.php")
        .then(r => r.json())
        .then(habs => {
            let html = "";
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones registradas.</div>";
            } else {
                habs.forEach(h => {
                    const img = obtenerPrimeraImagen(h);
                    html += `<div class="hab-card">
                        <img src="${img}" alt="Imagen habitación" class="hab-img">
                        <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                        <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                        <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                    </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        })
        .catch(error => {
            document.getElementById("resultados").innerHTML = "<div style='color:#a00'>Error al cargar las habitaciones.</div>";
            console.error('Error:', error);
        });
}

function buscarHabitaciones() {
    const fecha_ingreso = document.getElementById("fecha_ingreso").value;
    const fecha_salida = document.getElementById("fecha_salida").value;
    
    if (!fecha_ingreso || !fecha_salida) {
        document.getElementById("resultados").innerHTML = "<div style='color:#a00'>Selecciona fechas de ingreso y salida.</div>";
        return;
    }

    fetch(`../api/habitaciones/disponibilidad.php?fecha_ingreso=${fecha_ingreso}&fecha_salida=${fecha_salida}`)
        .then(r => r.json())
        .then(habs => {
            let html = `<h2 style='color:#9b8137'>Disponibles del ${fecha_ingreso} al ${fecha_salida}</h2>`;
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones disponibles en esas fechas.</div>";
            } else {
                habs.forEach(h => {
                    const img = obtenerPrimeraImagen(h);
                    html += `<div class="hab-card">
                        <img src="${img}" alt="Imagen habitación" class="hab-img">
                        <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                        <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                        <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                    </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        })
        .catch(error => {
            document.getElementById("resultados").innerHTML = "<div style='color:#a00'>Error al buscar habitaciones disponibles.</div>";
            console.error('Error:', error);
        });
}

function reiniciarFiltros() {
    document.getElementById("fecha_ingreso").value = "";
    document.getElementById("fecha_salida").value = "";
    cargarTodas();
}

function obtenerPrimeraImagen(h) {
    if (h.fotografia) {
        return `imghabit/${h.fotografia}`;
    }
    return "imghabit/no-image.png";
}

function verDetalle(id) {
    fetch(`../api/habitaciones/detalle.php?id=${id}`)
        .then(r => r.json())
        .then(data => {
            const hab = data.habitacion;
            const fotos = data.fotos && data.fotos.length ? data.fotos : [{ fotografia: "no-image.png" }];
            let current = 0;
            function renderCarrusel() {
                let html = `
                    <div style="text-align:center;">
                        <img src="imghabit/${fotos[current].fotografia}" style="max-width:400px;max-height:300px;border-radius:8px;box-shadow:0 2px 8px #0002;">
                        <div style="margin:10px 0;">
                            <button id="prevFoto" ${current === 0 ? "disabled" : ""}>&lt;</button>
                            <span>${current + 1} / ${fotos.length}</span>
                            <button id="nextFoto" ${current === fotos.length - 1 ? "disabled" : ""}>&gt;</button>
                        </div>
                    </div>
                `;
                document.getElementById("carrusel-fotos").innerHTML = html;
                document.getElementById("prevFoto")?.addEventListener("click", () => { current--; renderCarrusel(); });
                document.getElementById("nextFoto")?.addEventListener("click", () => { current++; renderCarrusel(); });
            }

            let info = `
                <h2>Habitación #${hab.numero} (${hab.nombre_tipo})</h2>
                <div><b>Piso:</b> ${hab.piso}</div>
                <div><b>Camas:</b> ${hab.numero_camas}</div>
                <div><b>Superficie:</b> ${hab.superficie} m²</div>
                <div id="disponibilidad"></div>
                <div id="carrusel-fotos"></div>
                <button id="btnReservar" class="btn" style="margin-top:15px;">Reservar</button>
                <button onclick="document.getElementById('modal-fotos').remove()" style="margin-top:15px;">Cerrar</button>
            `;

            let modal = document.createElement('div');
            modal.id = 'modal-fotos';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100vw';
            modal.style.height = '100vh';
            modal.style.background = 'rgba(0,0,0,0.6)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '9999';
            modal.innerHTML = `<div id="modal-content" style="background:#fff;padding:20px;border-radius:8px;max-width:90vw;max-height:90vh;overflow:auto;position:relative;">${info}</div>`;
            document.body.appendChild(modal);

            renderCarrusel();

            // Puedes personalizar la disponibilidad aquí
            document.getElementById("disponibilidad").innerHTML = `<b>Disponible:</b> Sí`;

            // Botón de reservar
            document.getElementById("btnReservar").onclick = function() {
                let usuario = null;
                try {
                    usuario = JSON.parse(localStorage.getItem('usuario'));
                } catch (e) {
                    usuario = null;
                }
                if (!usuario || !usuario.id) {
                    if (typeof abrirmodal === "function") {
                        // Aquí el modal de login
                        document.getElementById('modal-fotos').remove(); // Cierra el modal de detalle antes de abrir el de login
                        abrirmodal();
                    } else {
                        alert("Debes iniciar sesión para reservar.");
                    }
                    return;
                }
                alert("¡Reserva realizada para la habitación #" + hab.numero + "!");
            };
        });
}