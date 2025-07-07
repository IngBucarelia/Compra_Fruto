
import { getAllDataFromStore, clearStore } from '../store/indexeddb'

const endpoints = {
  area: '/offline-sync/areas',
  fertilizacion: '/offline-sync/fertilizaciones',
  polinizacion: '/offline-sync/polinizaciones',
  sanidad: '/offline-sync/sanidades',
  suelo: '/offline-sync/suelos',
  labores_cultivo: '/offline-sync/labores',
  evaluacion_cosecha: '/offline-sync/evaluacion',
  cierre_visitas: '/offline-sync/cierre-visitas' // ← FIRMAS + imágenes + cierre
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
            'Accept': 'application/json'
          },
          body: JSON.stringify(registro)
        })

        if (!response.ok) {
          throw new Error(`Error al sincronizar ${storeName}: ${response.statusText}`)
        }

        console.log(`✅ ${storeName}: registro sincronizado`)
        totalSincronizados++
      } catch (error) {
        console.error(`❌ Error en ${storeName}`, error)
        errores.push({ storeName, error })
      }
    }

    // Limpiar solo si no hubo errores en ese store
    if (errores.filter(e => e.storeName === storeName).length === 0) {
      await clearStore(storeName)
    }
  }

  if (errores.length === 0) {
    alert(`✅ Todos los registros sincronizados con éxito: ${totalSincronizados}`)
  } else {
    alert(`⚠️ Algunos errores al sincronizar. Ver consola.\nSincronizados: ${totalSincronizados}`)
  }
}
