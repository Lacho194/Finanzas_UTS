document.getElementById('formTransferencia').addEventListener('submit', async function (e) {
  e.preventDefault();

  const origen_id = localStorage.getItem('user_id'); // ID del usuario logueado
  const destino_id = document.getElementById('destinatario').value.trim();
  const cantidad = parseFloat(document.getElementById('cantidad').value);
  const descripcion = document.getElementById('descripcion').value.trim();
  const mensaje = document.getElementById('mensaje');

  // Validaciones básicas
  if (!origen_id) {
    mensaje.textContent = "⚠️ Usuario no identificado. Por favor inicia sesión.";
    return;
  }

  if (!destino_id || isNaN(destino_id)) {
    mensaje.textContent = "⚠️ Ingresa un ID de destinatario válido.";
    return;
  }

  if (origen_id === destino_id) {
    mensaje.textContent = "⚠️ No puedes transferirte DUTS a ti mismo.";
    return;
  }

  if (isNaN(cantidad) || cantidad <= 0) {
    mensaje.textContent = "⚠️ La cantidad debe ser mayor a 0.";
    return;
  }

  try {
    const response = await fetch('../routes/index.php?action=transferir', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        origen_id,
        destino_id,
        cantidad,
        descripcion
      })
    });

    const data = await response.json();

    if (data.success) {
      mensaje.textContent = "✅ " + data.message;
      mensaje.style.color = "green";
      document.getElementById('formTransferencia').reset();
    } else {
      mensaje.textContent = "❌ " + data.message;
      mensaje.style.color = "red";
    }
  } catch (error) {
    console.error("Error al realizar la transferencia:", error);
    mensaje.textContent = "❌ Error de red o del servidor.";
    mensaje.style.color = "red";
  }
});
