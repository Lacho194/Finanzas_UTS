document.addEventListener("DOMContentLoaded", cargarRanking);

function cargarRanking() {
  fetch("../../routes/index.php?action=ranking_duts")
    .then(res => res.json())
    .then(data => {
  console.log("Ranking recibido:", data); // <-- Esto es clave

  const tbody = document.getElementById("tablaRanking");
  tbody.innerHTML = "";

  if (!Array.isArray(data) || data.length === 0) {
    tbody.innerHTML = "<tr><td colspan='4'>No hay usuarios en el ranking.</td></tr>";
    return;
  }

  data.forEach((usuario, index) => {
    tbody.innerHTML += `
      <tr>
        <td>${index + 1}</td>
        <td>${usuario.nombres} ${usuario.apellidos}</td>
        <td>${usuario.programa}</td>
        <td>${usuario.total_duts}</td>
      </tr>
    `;
  });
})

    .catch(() => {
      alert("Error al cargar el ranking de usuarios.");
    });
}
