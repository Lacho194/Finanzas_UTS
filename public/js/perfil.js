document.addEventListener("DOMContentLoaded", () => {
  const userId = localStorage.getItem("user_id");

  if (!userId) {
    alert("No has iniciado sesión");
    window.location.href = "index.html";
    return;
  }

  fetch(`../routes/index.php?action=ver_perfil&id=${userId}`)
    .then(res => res.json())
    .then(data => {
        console.log("Perfil recibido:", data);
        
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      document.getElementById("nombres").textContent = data.nombres;
      document.getElementById("apellidos").textContent = data.apellidos;
      document.getElementById("programa").textContent = data.programa;
      document.getElementById("semestre").textContent = data.semestre;
      document.getElementById("intereses").textContent = data.intereses;
    })
    .catch(error => console.error("Error al cargar perfil:", error));
});



function exportarPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  const nombre = document.getElementById("nombres").textContent;
  const apellido = document.getElementById("apellidos").textContent;
  const programa = document.getElementById("programa").textContent;
  const semestre = document.getElementById("semestre").textContent;
  const intereses = document.getElementById("intereses").textContent;

  doc.text("Perfil del Usuario - Finanzas UTS", 10, 10);
  doc.text(`Nombre(s): ${nombre}`, 10, 20);
  doc.text(`Apellido(s): ${apellido}`, 10, 30);
  doc.text(`Programa: ${programa}`, 10, 40);
  doc.text(`Semestre: ${semestre}`, 10, 50);
  doc.text(`Intereses: ${intereses}`, 10, 60);

  doc.save("perfil_uts.pdf");
}
