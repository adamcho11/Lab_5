document.addEventListener("DOMContentLoaded", () => {
    cargarPerfil();
    cargarReservas();

    document.getElementById("formPerfil").onsubmit = actualizarPerfil;
});

function cargarPerfil() {
    // The userId is now managed by an HttpOnly cookie on the backend, so we don't retrieve it here.
    // The backend API should read the cookie directly.
    fetch(`../api/usuarios/detalle.php`)
    .then(r => {
        if (r.status === 401) { // Unauthorized, session expired or not logged in
            alert("Sesión expirada o no autenticado.");
            window.location.href = "index.html"; // Redirect to login
            return Promise.reject("Unauthorized");
        }
        return r.json();
    })
    .then(data => {
        if (!data || !data.id) {
            alert("Error al cargar perfil: " + (data && data.mensaje ? data.mensaje : "No permitido"));
            window.location.href = "index.html";
            return;
        }
        document.getElementById("nombre").value = data.nombre;
        document.getElementById("email").value = data.email;
    })
    .catch(error => {
        if (error !== "Unauthorized") { // Avoid showing alert twice for unauthorized
            alert("Error de red o del servidor: " + error);
        }
        window.location.href = "index.html";
    });
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
    // userId is now managed by an HttpOnly cookie on the backend
    fetch("../api/usuarios/actualizar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
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
    fetch("../api/reservas/mostrar.php")
    .then(r => r.json())
    .then(reservas => {
        const tbody = document.getElementById("tablaReservas");
        tbody.innerHTML = "";
        if (!reservas || reservas.length === 0) {
            tbody.innerHTML = "<tr><td colspan='6'>No hay reservas.</td></tr>";
            return;
        }
        reservas.forEach(r => {
            if (r && r.habitacion !== undefined && r.habitacion !== null) {
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
            }
        });
    });
}

function cancelarReserva(id) {
    if (!confirm("¿Seguro que deseas cancelar esta reserva?")) return;
    fetch("../api/reservas/cancelar.php", {
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
    fetch("../backend/logout.php")
        .then(() => {
            // Remove the userId cookie on logout
            document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
            window.location.href = "index.html";
        });
}
