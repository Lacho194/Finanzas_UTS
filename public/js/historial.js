document.addEventListener("DOMContentLoaded", () => {
  const userId = localStorage.getItem("user_id");

  if (!userId || isNaN(userId)) {
    alert("Usuario no identificado.");
    return;
  }

  cargarHistorialDUTS(userId);
  cargarTransferencias(userId);
});

// Cargar historial de movimientos DUTS
function cargarHistorialDUTS(userId) {
  fetch(`../routes/index.php?action=historial_duts&user_id=${userId}`)
    .then(res => res.json())
    .then(duts => {
      const tbody = document.getElementById("tablaDUTS");
      tbody.innerHTML = "";

      if (!Array.isArray(duts)) {
        const mensaje = duts.message || duts.error || "Error desconocido al cargar DUTS";
        tbody.innerHTML = `<tr><td colspan='3'>${mensaje}</td></tr>`;
        return;
      }

      if (duts.length === 0) {
        tbody.innerHTML = "<tr><td colspan='3'>No hay historial de DUTS.</td></tr>";
        return;
      }

      duts.forEach(item => {
        tbody.innerHTML += `
          <tr>
            <td>${item.cantidad}</td>
            <td>${item.descripcion || "Sin descripción"}</td>
            <td>${item.fecha}</td>
          </tr>`;
      });
    })
    .catch(err => {
      console.error("Error al obtener historial de DUTS:", err);
      alert("Error al cargar historial de DUTS");
    });
}

// Cargar historial de transferencias
function cargarTransferencias(userId) {
  fetch(`../routes/index.php?action=historial_transferencias&user_id=${userId}`)
    .then(res => res.json())
    .then(transferencias => {
      const tbody = document.getElementById("tablaTransferencias");
      tbody.innerHTML = "";

      if (!Array.isArray(transferencias)) {
        const mensaje = transferencias.message || transferencias.error || "Error desconocido al cargar transferencias";
        tbody.innerHTML = `<tr><td colspan='4'>${mensaje}</td></tr>`;
        return;
      }

      if (transferencias.length === 0) {
        tbody.innerHTML = "<tr><td colspan='4'>No hay transferencias realizadas.</td></tr>";
        return;
      }

      transferencias.forEach(t => {
        tbody.innerHTML += `
          <tr>
            <td>${t.origen}</td>
            <td>${t.destino}</td>
            <td>${t.cantidad}</td>
            <td>${t.fecha}</td>
          </tr>`;
      });
    })
    .catch(err => {
      console.error("Error al obtener historial de transferencias:", err);
      alert("Error al cargar historial de transferencias");
    });
}
