function verificarsesion() {
    fetch('backend/api/verificarlogin.php')
        .then(Response => Response.json())
        .then(data => {
            if (data.success) {
                if (data.estado === "habilitado") {
                    alert(data.message);
                    verificarrol();
                } else {
                    alert('la Cuenta se encuentra Suspendida');
                    modal.style.display = 'none';
                }
            } else {
                alert(data.message);
                abrirmodal();
            }
        })
}

function verificarrol() {
    fetch('../../backend/verificarrol.php')
        .then(Response => Response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '../Pagina Admin/indexAdmin.html';
            } else {
                alert(data.message);
            }
        })
}

function autenticarsesion() {
    const form = document.getElementById('I');  // Obtén el formulario

    // Itera sobre todos los elementos dentro del formulario
    /*Array.from(form.elements).forEach(element => {
        alert(element.name + ': ' + element.value);  // Muestra el nombre y valor de cada elemento
    });*/

    const dataform = new FormData(form);

    fetch('../../backend/autenticar.php', {
        method: 'POST',
        body: dataform
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.estado_cuenta === 'habilitado') {
                    alert(data.message);
                    verificarrol();
                    modal.style.display = 'none';
                } else {
                    alert('la cuenta esta suspendida');
                }
            } else {
                alert(data.message);
            }
        })
}

function abrirmodal() {
    fetch('formlogin.html')
        .then(response => response.text())  // Cargar como texto, no JSON
        .then(data => {
            var modal = document.getElementById('modal');
            modal.style.display = 'block';  // Mostrar el modal
            var contenido = document.getElementById('contenidomodal');
            contenido.innerHTML = data;  // Insertar el contenido HTML dentro del modal
            // Obtén los elementos del DOM
            const RegIni = document.getElementById('RegIni');
            let isRegistrarse = false; // Esta variable controlará qué formulario se muestra

            // Añadir el evento al botón
            RegIni.addEventListener('click', () => {
                // Alternar la visibilidad de los formularios
                if (isRegistrarse) {
                    RegIni.textContent = "Registrarse"; // Cambiar el texto del botón
                    document.getElementById('ConstRegistrarse').style.display = "none";
                    document.getElementById('ConstIniciarSesion').style.display = "block";
                } else {
                    RegIni.textContent = "Iniciar Sesion"; // Cambiar el texto del botón
                    document.getElementById('ConstIniciarSesion').style.display = "none";
                    document.getElementById('ConstRegistrarse').style.display = "block";
                }
                isRegistrarse = !isRegistrarse; // Alternar el valor de la variable
            });

            document.querySelector('#cerrar').addEventListener('click', () => {
                modal.style.display = 'none';
            })
        })
}

function RegistrarseUser() {
    const form = document.getElementById('R');  // Obtén el formulario

    // Itera sobre todos los elementos dentro del formulario
    Array.from(form.elements).forEach(element => {
        console.log(element.name + ': ' + element.value);  // Muestra el nombre y valor de cada elemento
    });

    const dataform = new FormData(form);

    fetch('backend/api/registrar.php', {
        method: 'POST',
        body: dataform
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
                window.location.href = 'frontend/usuarios/indexUser.html';
            } else {
                console.log(data.message);
            }
        })
}

//Habitaciones y funciones del Index Adam
//Habitaciones y principal

function cargarTodas() {
    fetch("../../api/habitaciones/mostrar.php")

        .then(r => r.json())
        .then(habs => {
            let html = "<h2 style='color:#9b8137'>Todas las habitaciones</h2>";
            html += "<br>";
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones registradas.</div>";
            } else {
                habs.forEach(h => {
                    html += `<div class="hab-card">
                            <img src="../Pagina Admin/imgHabit/${h.fotografia}" alt="Foto habitación" style="width:100%; height:150px; object-fit:cover;">
                            <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                            <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                            <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                        </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        });
}

function buscarHabitaciones() {
    const fecha_ingreso = document.getElementById("fecha_ingreso").value;
    const fecha_salida = document.getElementById("fecha_salida").value;
    if (!fecha_ingreso || !fecha_salida) {
        document.getElementById("resultados").innerHTML = "<div style='color:#a00'>Selecciona fechas de ingreso y salida.</div>";
        return;
    }
    fetch(`../../api/habitaciones/disponibilidad.php?fecha_ingreso=${fecha_ingreso}&fecha_salida=${fecha_salida}`)
        .then(r => r.json())
        .then(habs => {
            let html = `<h2 style='color:#9b8137'>Disponibles del ${fecha_ingreso} al ${fecha_salida}</h2>`;
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones disponibles en esas fechas.</div>";
            } else {
                habs.forEach(h => {
                    html += `<div class="hab-card">
                            <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                            <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                            <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                        </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        });
}

function verDetalle(id) {
    window.location.href = `detalle.html?id=${id}`;
}

function reiniciarFiltros() {
    document.getElementById("fecha_ingreso").value = "";
    document.getElementById("fecha_salida").value = "";
    cargarTodas();
}
/**
 * Modifica las funciones para mostrar la primera imagen de cada habitación.
 * Se asume que la API devuelve un campo `imagenes` como array de URLs o un string separado por comas.
 */

// Helper para obtener la URL de la primera imagenx
function obtenerPrimeraImagen(h) {
    if (Array.isArray(h.imagenes) && h.imagenes.length > 0) {
        return h.imagenes[0];
    }
    if (typeof h.imagenes === "string" && h.imagenes.trim() !== "") {
        return h.imagenes.split(",")[0].trim();
    }
    // Imagen por defecto si no hay ninguna
    return "img/no-image.png";
}

// Sobrescribe cargarTodas para incluir la imagen
const cargarTodasOriginal = cargarTodas;
cargarTodas = function () {
    fetch("../../api/habitaciones/mostrar.php")
        .then(r => r.json())
        .then(habs => {
            let html = "<h2 style='color:#9b8137'>Todas las habitaciones</h2>";
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones registradas.</div>";
            } else {
                habs.forEach(h => {
                    const img = obtenerPrimeraImagen(h);
                    html += `<div class="hab-card">
                            <img src="${img}" alt="Imagen habitación" class="hab-img" style="width:100%;max-width:220px;display:block;margin-bottom:8px;">
                            <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                            <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                            <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                        </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        });
};

// Sobrescribe buscarHabitaciones para incluir la imagen
const buscarHabitacionesOriginal = buscarHabitaciones;
buscarHabitaciones = function () {
    const fecha_ingreso = document.getElementById("fecha_ingreso").value;
    const fecha_salida = document.getElementById("fecha_salida").value;
    if (!fecha_ingreso || !fecha_salida) {
        document.getElementById("resultados").innerHTML = "<div style='color:#a00'>Selecciona fechas de ingreso y salida.</div>";
        return;
    }
    fetch(`../../api/habitaciones/disponibilidad.php?fecha_ingreso=${fecha_ingreso}&fecha_salida=${fecha_salida}`)
        .then(r => r.json())
        .then(habs => {
            let html = `<h2 style='color:#9b8137'>Disponibles del ${fecha_ingreso} al ${fecha_salida}</h2>`;
            if (!habs || habs.length === 0) {
                html += "<div>No hay habitaciones disponibles en esas fechas.</div>";
            } else {
                habs.forEach(h => {
                    const img = obtenerPrimeraImagen(h);
                    html += `<div class="hab-card">
                            <img src="${img}" alt="Imagen habitación" class="hab-img" style="width:100%;max-width:220px;display:block;margin-bottom:8px;">
                            <h3>Habitación #${h.numero} (${h.nombre_tipo})</h3>
                            <div><b>Piso:</b> ${h.piso} &nbsp; <b>Camas:</b> ${h.numero_camas}</div>
                            <button class="btn" onclick="verDetalle(${h.id})">Ver detalle</button>
                        </div>`;
                });
            }
            document.getElementById("resultados").innerHTML = html;
        });
};
