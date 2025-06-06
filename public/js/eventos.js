document.addEventListener("DOMContentLoaded", cargarEventos);

function cargarEventos(fecha = null) {
  let url = "../../router/index.php?action=listar_eventos";

  if (fecha) {
    url = `../../router/index.php?action=filtrar_eventos&fecha=${fecha}`;
  }

  fetch(url)
    .then(res => res.json())
    .then(eventos => mostrarEventos(eventos))
    .catch(err => console.error("Error cargando eventos:", err));
}

function mostrarEventos(eventos) {
  const container = document.getElementById("eventosContainer");
  container.innerHTML = "";

  if (!eventos || eventos.length === 0) {
    container.innerHTML = "<p>No hay eventos disponibles.</p>";
    return;
  }

  eventos.forEach(evento => {
    const div = document.createElement("div");
    div.className = "evento";
    div.innerHTML = `
      <h3>${evento.nombre}</h3>
      <p>${evento.descripcion}</p>
      <p><strong>Fecha:</strong> ${evento.fecha}</p>
      <p><strong>Lugar:</strong> ${evento.lugar}</p>
      <button onclick="registrar(${evento.id})">Registrarme</button>
      <button onclick="cancelar(${evento.id})">Darse de baja</button>
    `;
    container.appendChild(div);
  });
}

function registrar(eventoId) {
  const userId = sessionStorage.getItem("user_id"); // Asegúrate que esté guardado en login
  if (!userId) return alert("Debes iniciar sesión.");

  fetch("../../router/index.php?action=registrarse_evento", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ evento_id: eventoId, user_id: userId })
  })
  .then(res => res.json())
  .then(data => alert(data.message || data.mensaje))
  .catch(() => alert("Error al registrarse."));
}

function cancelar(eventoId) {
  const userId = sessionStorage.getItem("user_id");
  if (!userId) return alert("Debes iniciar sesión.");

  fetch("../../router/index.php?action=baja_evento", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ evento_id: eventoId, user_id: userId })
  })
  .then(res => res.json())
  .then(data => alert(data.message || data.mensaje))
  .catch(() => alert("Error al cancelar la inscripción."));
}

function filtrarPorFecha() {
  const fecha = document.getElementById("fechaFiltro").value;
  if (!fecha) {
    alert("Selecciona una fecha.");
    return;
  }
  cargarEventos(fecha);
}
