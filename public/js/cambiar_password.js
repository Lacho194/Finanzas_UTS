document.getElementById("formCambiarPassword").addEventListener("submit", async function (e) {
  e.preventDefault();

  const userId = localStorage.getItem("user_id");
  const password_actual = document.getElementById("password_actual").value;
  const nueva_password = document.getElementById("nueva_password").value;
  const confirmar_password = document.getElementById("confirmar_password").value;

  const mensaje = document.getElementById("mensajeCambio");
  mensaje.textContent = "";
  mensaje.style.color = "red";

  // Validaciones
  if (!userId) {
    mensaje.textContent = "Error: ID de usuario no encontrado.";
    return;
  }

  if (nueva_password !== confirmar_password) {
    mensaje.textContent = "La nueva contraseña y la confirmación no coinciden.";
    return;
  }

  try {
    const res = await fetch("../routes/index.php?action=cambiar_password", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `user_id=${encodeURIComponent(userId)}&password_actual=${encodeURIComponent(password_actual)}&nueva_password=${encodeURIComponent(nueva_password)}`
    });

    const data = await res.json();

    if (data.success) {
      mensaje.style.color = "green";
      mensaje.textContent = data.message || "Contraseña cambiada correctamente.";
      document.getElementById("formCambiarPassword").reset();
    } else {
      mensaje.textContent = data.message || "Error al cambiar la contraseña.";
    }

  } catch (error) {
    mensaje.textContent = "Error de conexión con el servidor.";
    console.error("Error:", error);
  }
});
