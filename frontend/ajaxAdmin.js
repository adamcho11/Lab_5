function cargardatos() {
    console.log('ingresaste a la function');
    fetch('../backend/verificarlogin.php')
        .then(Response => Response.json())
        .then(data => {
            if (data.success) {
                alert('Bienvenido: ' + data.nombre);
                var user = document.getElementById('NombreUser');
                user.textContent = data.nombre;
                mostrartabla();
            } else {
                alert(data.message);
                window.location.href = 'index.html';
            }
        })
}

function abrirmodal(boton) {
    const idHabitacion = boton;
    console.log(idHabitacion);
    fetch('formCrear.html')
        .then(response => response.text())
        .then(data => {
            var modal = document.getElementById('modal');
            modal.style.display = 'block';
            var contenido = document.getElementById('contenidomodal');
            contenido.innerHTML = data;

            var formH = document.getElementById('datosH');
            var formTH = document.getElementById('datosTH');
            var formFH = document.getElementById('datosFH');

            if (boton == "H") {
                formH.style.display = "block";
                formTH.style.display = 'none';
                formFH.style.display = 'none';
                mostrarTH();
            } else if (boton == "TH") {
                formH.style.display = "none";
                formTH.style.display = "block";
                formFH.style.display = "none";
            } else {
                formH.style.display = "none";
                formTH.style.display = "none";
                formFH.style.display = "block";
                document.getElementById('inputIdHabitacion').value = idHabitacion;
            }

            document.querySelector('#cerrar').addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        })
}

function selectUser() {
    console.log('ingresaste a esta function');
    const select = document.getElementById("Option");
    const valorSeleccionado = select.value;

    if (valorSeleccionado === "cerrarLog") {
        cerrarcesion();
    }
}

function mostrartabla() {
    fetch('../api/usuarios/mostrar.php')
        .then(Response => Response.json())
        .then(data => {
            var cont1 = document.getElementById('contenido_222');
            var div1 = document.createElement('div');
            var div2 = document.createElement('div');
            div2.id = "TUser";
            div2.style.padding = "20px";
            var h = document.createElement('h1');
            h.textContent = 'Lista de Usuarios';
            div2.appendChild(h);
            console.log(data);
            var div3 = document.createElement('div');
            div3.style.padding = "20px";
            var tabla = generartabla(data);
            div3.innerHTML = tabla;
            div1.appendChild(div2);
            div1.appendChild(div3);
            cont1.innerHTML = '';
            cont1.appendChild(div1);
        })
}

function generartabla(objeto) {
    let user = objeto;
    let tabla = `<table id="tablaUser"><tr>
    <th>Nombre del Usuario</th>
    <th>Correo Electronico</th>
    <th>Rol</th>
    <th>Operaciones</th>
    </tr>`;
    user.forEach(usuarios => {
        tabla += `<tr>
        <td>${usuarios.nombre}</td>
        <td>${usuarios.email}</td>
        <td><button onclick="javascript:AdmCliente('${usuarios.id}','${usuarios.rol}')">${usuarios.rol}</button></td>
        <td><button onclick="javascript:habilSus('${usuarios.id}','${usuarios.estado}')" style=";">${usuarios.estado}</button></td></tr>`;
    });
    return tabla += '</table>';
}

function habilSus(id, estado) {
    let nuevoEstado;
    console.log('habilSus llamada con:', { id: id, estado: estado });
    if (estado === 'activo') {
        nuevoEstado = 'suspendido';
    } else {
        nuevoEstado = 'activo';
    }
    console.log('Nuevo estado determinado:', nuevoEstado);
    fetch(`../api/usuarios/estadoCuenta.php?id=${id}&estado=${nuevoEstado}`)
        .then(Response => {
            console.log('Respuesta de la API (raw):', Response);
            return Response.json();
        })
        .then(data => {
            console.log('Respuesta de la API (parsed):', data);
            if (data.success) {
                alert(data.message);
                mostrartabla();
            } else {
                alert('Error del servidor: ' + data.message);
            };
        })
        .catch(error => {
            console.error('Error en la solicitud Fetch:', error);
            alert('Ocurrió un error en la solicitud: ' + error.message);
        });
}

function AdmCliente(id, rol) {
    let rolActual = 'cliente';
    console.log(id, rol);
    if (rol == 'cliente') {
        rolActual = 'administrador';
    }
    fetch(`../api/usuarios/cambiarrol.php?id=${id}&rol=${rolActual}`)
        .then(Response => Response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                alert(data.message);
                mostrartabla();
            } else {
                alert(data.message);
            };
        })
}

function mostrarHabitaciones() {
    fetch('../api/habitaciones/mostrar.php')
        .then(response => response.json())
        .then(data => {
            console.log(data)
            const cont1 = document.getElementById('contenido_222');
            const div1 = document.createElement('div');
            const div2 = document.createElement('div');
            div2.id = "THabit";
            div2.style.padding = "20px";

            const h = document.createElement('h1');
            h.textContent = 'Lista de Habitaciones';

            const btnH = document.createElement('button');
            btnH.textContent = "+ Nueva Habitacion";
            btnH.style.backgroundColor = "rgb(114, 87, 29)";
            btnH.style.color = "white";
            btnH.addEventListener('click', () => abrirmodal("H"));

            div2.appendChild(h);
            div2.appendChild(btnH);

            const div3 = document.createElement('div');
            div3.style.padding = "20px";
            div3.innerHTML = generarcarts(data);

            div1.appendChild(div2);
            div1.appendChild(div3);
            cont1.innerHTML = '';
            cont1.appendChild(div1);
        });
}

function generarcarts(habitaciones) {
    let card = '<div id="card" style="display: flex; flex-wrap: wrap; gap: 20px;">';
    habitaciones.forEach(h => {
        card += `<div style="border: 1px solid #ccc; padding: 10px; width: 300px;">`;
        if (h.fotografia) {
            card += `<img src="imgHabit/${h.fotografia}" alt="Foto habitación" style="width:100%; height:150px; object-fit:cover;">`;
        } else {
            card += `<button onclick="abrirmodal('${h.id}')">Agregar Imagen</button>`;
        }
        card += `
            <div><h2>${h.nombre_tipo}</h2>
            <div id="Descript">
                <p>Ubicado en el Piso ${h.piso}</p>
                <p>Cantidad de Camas: ${h.numero_camas}</p>
            </div>
            <h4>La Superficie del cuarto es: ${h.superficie} m²</h4>
            <button onclick="abrirModalDetalle('${h.id}')">Informe de Reserva</button>
            <button onclick="modalEdit('FH', '${h.foto_id}')">Editar Foto</button>
            <button onclick="modalEdit('H', '${h.id}')">Editar Habitacion</button></div>
            <button onclick="eliminarHabitacion(${h.id})">Eliminar Habitacion</button>
        </div>`;
    });
    card += '</div>';
    return card;
}

function crearhabitacion() {
    const TpH = document.getElementById('TipoH');
    const idTpH = TpH.value
    const form = document.getElementById('datosH');
    const datos = new FormData(form);

    fetch(`../api/habitaciones/crearhabitacion.php?idTipo = ${idTpH}`, {
        method: 'POST',
        body: datos
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            modal.style.display = 'none';
            mostrarHabitaciones();
        })
}

function mostrarTH() {
    fetch('../api/habitaciones/mostrarTipoH.php')
        .then(response => response.json())
        .then(data => {
            var idTh 
            var selectTH = document.getElementById('TipoH');
            data.forEach(Th => {
                var option = document.createElement('option');
                option.value = Th.id;
                var nt = Th.nombre_tipo;
                var supt = Th.superficie;
                option.textContent = nt + " - " + supt + "metros";
                selectTH.appendChild(option);
            });
            var btonE = document.getElementById('BtnTHE');
            btonE.addEventListener('click', ()=>{
                var idTh = selectTH.value;
                modalEdit('TH', idTh)
            })
        })
}

function crearTipoHabitacion() {
    const form = document.getElementById('datosTH');
    const datos = new FormData(form);
    fetch('../api/habitaciones/crearTipoHabitacion.php', {
        method: 'POST',
        body: datos
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            modal.style.display = 'none';
            abrirmodal("H");
        })
}

function agregarImagen() {
    const form = document.getElementById('datosFH');
    const datos = new FormData(form);
    fetch('../api/habitaciones/crearFotoHabitacion.php', {
        method: 'POST',
        body: datos
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            modal.style.display = 'none';
            mostrarHabitaciones();
        })
}

function eliminarHabitacion(idHabitacion) {
    if (!confirm('¿Estás seguro de eliminar esta habitación?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', idHabitacion);

    fetch('../api/habitaciones/eliminarHabitacion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            mostrarHabitaciones();
        }
    });
}

function cerrarcesion() {
    fetch('../backend/logout.php')
        .then(Response => Response.json())
        .then(data => {
            window.location.href = 'index.html';
        })
}

function modalEdit(tipoform, id) {
    fetch('formEdit.html')
        .then(response => response.text())
        .then(html => {
            var modal = document.getElementById('modal');
            modal.style.display = 'block';
            var contenido = document.getElementById('contenidomodal');
            contenido.innerHTML = html;

            var formH = document.getElementById('datosH');
            var formTH = document.getElementById('datosTH');
            var formFH = document.getElementById('datosFH');

            if (tipoform === "H") {
                formH.style.display = "block";
                formTH.style.display = "none";
                formFH.style.display = "none";
                mostrarTH();

                fetch(`../api/habitaciones/obtenerHabitacion.php?id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('nombreH').value = data.numero;
                        document.getElementById('piso').value = data.piso;
                        document.getElementById('TipoH').value = data.tipo_id;
                        formH.onsubmit = () => editarHabitacion(id);
                    });

            } else if (tipoform === "TH") {
                formH.style.display = "none";
                formTH.style.display = "block";
                formFH.style.display = "none";

                fetch(`../api/habitaciones/obtenerTipoHabitacion.php?id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('nombretipo').value = data.nombre_tipo;
                        document.getElementById('superficie').value = data.superficie;
                        document.getElementById('ncamas').value = data.numero_camas;
                        formTH.onsubmit = () => editarTipoHabitacion(id);
                    });

            } else if (tipoform === "FH") {
                formH.style.display = "none";
                formTH.style.display = "none";
                formFH.style.display = "block";
                document.getElementById('inputIdHabitacion').value = id;
                formFH.onsubmit = () => editarFotografia(id);
            }

            document.querySelector('#cerrar').addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
}

function editarHabitacion(id) {
    const datos = new FormData(document.getElementById('datosH'));
    datos.append('id', id);
    fetch('../api/habitaciones/editarHabitacion.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        document.getElementById('modal').style.display = 'none';
        mostrarHabitaciones();
    });
}

function editarTipoHabitacion(id) {
    const datos = new FormData(document.getElementById('datosTH'));
    datos.append('id', id);
    fetch('../api/habitaciones/editarTipoHabitacion.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        document.getElementById('modal').style.display = 'none';
        mostrarTH();
    });
}

function editarFotografia(id) {
    const datos = new FormData(document.getElementById('datosFH'));
    datos.append('id', id);
    fetch('../api/habitaciones/editarFotoHabitacion.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        document.getElementById('modal').style.display = 'none';
        mostrarHabitaciones();
    });
}

function abrirModalDetalle(id) {
    var modal = document.getElementById('modal');
    modal.style.display = 'block';
    var contenido = document.getElementById('contenidomodal');

    fetch(`../api/habitaciones/infoHabitacion.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.error){
                contenido.innerHTML = '<h1>Habitacion Disponible</h1>';
            }else{
                contenido.innerHTML = `
                <h2>Detalle de Reserva</h2>
                <table border="1" cellpadding="10">
                    <tr><th>ID Reserva</th><td>${data.id}</td></tr>
                    <tr><th>Usuario</th><td>${data.nombre} (ID: ${data.id_user})</td></tr>
                    <tr><th>Habitación</th><td>${data.numero} (ID: ${data.id_habit})</td></tr>
                    <tr><th>Fecha Ingreso</th><td>${data.fecha_ingreso}</td></tr>
                    <tr><th>Fecha Salida</th><td>${data.fecha_salida}</td></tr>
                    <tr><th>Estado</th><td id="estadoCelda">
                        <button onclick="mostrarOpcionesEstado(${data.id})">${data.estado}</button>
                    </td></tr>
                </table>`;
            }

            document.querySelector('#cerrar').addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
}

function mostrarOpcionesEstado(idReserva) {
    const celda = document.getElementById('estadoCelda');
    celda.innerHTML = `
        <button onclick="cambiarEstado('${idReserva}', 'confirmada')">Confirmar</button>
        <button onclick="cambiarEstado('${idReserva}', 'cancelada')">Cancelar</button>
    `;
}

function cambiarEstado(idReserva, nuevoEstado) {
    const formData = new FormData();
    formData.append('id', idReserva);
    formData.append('estado', nuevoEstado);

    fetch('../api/reservas/cambiarEstado.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            abrirModalDetalle(idReserva);
        } else {
            alert(data.message);
        }
    });
}

const menuToggleButton = document.getElementById('BtnMenu');
let isSidebarOpen = false;

menuToggleButton.addEventListener('click', () => {
    const sidebar = document.getElementById('contenido_21');
    if (!isSidebarOpen) {
        sidebar.style.display = "block";
    } else {
        sidebar.style.display = "none";
    }
    isSidebarOpen = !isSidebarOpen;
});