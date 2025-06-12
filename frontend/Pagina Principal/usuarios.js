document.addEventListener("DOMContentLoaded", () => {
    cargarPerfil();
    cargarReservas();

    document.getElementById("formPerfil").onsubmit = actualizarPerfil;
});

function cargarPerfil() {
    // Obtener el userId del localStorage, sessionStorage, o cookie según tu implementación
    // Por ejemplo, si lo guardaste en localStorage:
    fetch(`../../api/usuarios/detalle.php?`)
    .then(r => r.json())
    .then(data => {
        console.log(data);
        document.getElementById("nombre").value = data.nombre;
        document.getElementById("email").value = data.email;
    })
}

function actualizarPerfil(e) {
    e.preventDefault();
    const nombre = document.getElementById("nombre").value;
    const password = document.getElementById("password").value;
    const email = document.getElementById("email").value;
    if (!nombre || !email) {
        document.getElementById("msgPerfil").textContent = "Nombre y email son obligatorios.";
        return;
    }
    if (password && password.length < 6) {
        document.getElementById("msgPerfil").textContent = "La contraseña debe tener al menos 6 caracteres.";
        return;
    }
    const userId = localStorage.getItem("userId");
    fetch("../../api/usuarios/actualizar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            id: userId,
            nombre,
            password,
            email
        })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById("msgPerfil").textContent = data.success ? "Datos actualizados" : data.mensaje || "Error";
        if (data.success) document.getElementById("password").value = "";
    });
}

function cargarReservas() {
    fetch("./../api/reservas/mostrar.php")
    .then(r => r.json())
    .then(reservas => {
        const tbody = document.getElementById("tablaReservas");
        tbody.innerHTML = "";
        if (!reservas || reservas.length === 0) {
            tbody.innerHTML = "<tr><td colspan='6'>No hay reservas.</td></tr>";
            return;
        }
        reservas.forEach(r => {
            tbody.innerHTML += `
                <tr>
                    <td>${r.id}</td>
                    <td>${r.habitacion}</td>
                    <td>${r.fecha_ingreso}</td>
                    <td>${r.fecha_salida}</td>
                    <td>${r.estado}</td>
                    <td class="acciones">
                        ${r.estado !== "cancelada" ? `<button class="btn" onclick="cancelarReserva(${r.id})">Cancelar</button>` : ""}
                    </td>
                </tr>`;
        });
    });
}

function cancelarReserva(id) {
    if (!confirm("¿Seguro que deseas cancelar esta reserva?")) return;
    fetch("../../api/reservas/cancelar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById("msgReserva").textContent = data.success ? "Reserva cancelada." : data.mensaje || "Error";
        cargarReservas();
    });
}

function logout() {
    fetch("../../backend/logout.php")
        .then(() => window.location.href = "index.html");
}