const userId = localStorage.getItem("user_id");

if (!userId) {
  alert("Usuario no identificado. Redirigiendo al login.");
  window.location.href = "index.html";
} else {
  fetch(`../routes/dashboard_data.php?user_id=${userId}`)
    .then(res => {
      if (!res.ok) throw new Error("Respuesta incorrecta del servidor.");
      return res.json();
    })
    .then(data => {
      document.getElementById("nombreUsuario").textContent = data.nombre;
      document.getElementById("dutsActuales").textContent = data.duts_actuales;
      document.getElementById("promedioDia").textContent = data.promedio_dia;
      document.getElementById("promedioSemana").textContent = data.promedio_semana;
      document.getElementById("promedioMes").textContent = data.promedio_mes;
      document.getElementById("promedioSemestre").textContent = data.promedio_semestre;
      document.getElementById("promedioAnual").textContent = data.promedio_anual;
    })
    .catch(err => {
      console.error("Error cargando dashboard:", err);
      alert("Hubo un error al cargar tus datos.");
    });
}
