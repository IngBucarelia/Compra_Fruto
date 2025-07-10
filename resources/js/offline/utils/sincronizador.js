// src/scripts/sincronizador.js (o donde tengas este script)
import { getAllDataFromStore, clearStore } from '../store/indexeddb' // Asegúrate de que la ruta sea correcta

const endpoints = {
  area: '/api/offline-sync/areas', // ✅ CAMBIO AQUÍ
  fertilizacion: '/api/offline-sync/fertilizaciones', // ✅ CAMBIO AQUÍ
  polinizacion: '/api/offline-sync/polinizaciones', // ✅ CAMBIO AQUÍ
  sanidad: '/api/offline-sync/sanidades', // ✅ CAMBIO AQUÍ
  suelo: '/api/offline-sync/suelos', // ✅ CAMBIO AQUÍ
  labores_cultivo: '/api/offline-sync/labores', // ✅ CAMBIO AQUÍ
  evaluacion_cosecha: '/api/offline-sync/evaluacion', // ✅ CAMBIO AQUÍ
  cierre_visitas: '/api/offline-sync/cierre-visitas' // ✅ CAMBIO AQUÍ
}

export async function sincronizarDatosOffline() {
  let totalSincronizados = 0
  let errores = []

  for (const [storeName, url] of Object.entries(endpoints)) {
    const registros = await getAllDataFromStore(storeName)

    for (const registro of registros) {
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // Si necesitas enviar tokens de autenticación para APIs, irían aquí:
            // 'Authorization': `Bearer ${tuTokenDeAuth}`
          },
          credentials: 'same-origin',
          body: JSON.stringify(registro)
        })

        if (!response.ok) {
          // Intenta parsear la respuesta de error si Laravel devuelve un JSON con detalles
          let errorData = await response.json().catch(() => ({ message: response.statusText }));
          throw new Error(`Error al sincronizar ${storeName} (HTTP ${response.status}): ${errorData.message || response.statusText}`);
        }

        console.log(`✅ ${storeName}: registro sincronizado`)
        totalSincronizados++
      } catch (error) {
        console.error(`❌ Error en ${storeName}`, error)
        errores.push({ storeName, error: error.message }) // Guardar solo el mensaje para legibilidad
      }
    }

    // Solo limpia el store si no hubo errores para ese tipo de registro
    if (errores.filter(e => e.storeName === storeName).length === 0) {
      await clearStore(storeName)
    }
  }

  if (errores.length === 0) {
    alert(`✅ Todos los registros sincronizados con éxito: ${totalSincronizados}`)
  } else {
    alert(`⚠️ Algunos errores al sincronizar. Ver consola para detalles.\nSincronizados: ${totalSincronizados}`)
  }
}