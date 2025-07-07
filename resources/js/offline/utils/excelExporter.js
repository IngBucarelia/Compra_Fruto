// resources/js/offline/js/excelExporter.js

import * as XLSX from 'xlsx'

export function exportarResumenExcel({ area, fertilizaciones, polinizaciones, sanidad, suelo, labores, evaluacion, firmas }) {
  const wb = XLSX.utils.book_new()

  // 1. Área
  if (area) {
    const wsArea = XLSX.utils.json_to_sheet([area])
    XLSX.utils.book_append_sheet(wb, wsArea, 'Área')
  }

  // 2. Fertilizaciones
  if (fertilizaciones.length) {
    const fertData = fertilizaciones.map(f => ({
      fecha: f.fecha_fertilizacion,
      ...Object.fromEntries(f.fertilizantes.map(fert => [fert.nombre, fert.cantidad]))
    }))
    const wsFert = XLSX.utils.json_to_sheet(fertData)
    XLSX.utils.book_append_sheet(wb, wsFert, 'Fertilizaciones')
  }

  // 3. Polinizaciones
  if (polinizaciones.length) {
    const poliData = polinizaciones.map(p => ({
      fecha: p.fecha,
      pases: p.n_pases,
      ciclos: p.ciclos_ronda,
      ana: `${p.ana} (${p.tipo_ana})`,
      talco: `${p.talco} kg`
    }))
    const wsPoli = XLSX.utils.json_to_sheet(poliData)
    XLSX.utils.book_append_sheet(wb, wsPoli, 'Polinizaciones')
  }

  // 4. Sanidad
  if (sanidad) {
    const { observaciones, ...rest } = sanidad
    const sanidadData = [{ ...rest, observaciones }]
    const wsSan = XLSX.utils.json_to_sheet(sanidadData)
    XLSX.utils.book_append_sheet(wb, wsSan, 'Sanidad')
  }

  // 5. Suelo
  if (suelo) {
    const wsSuelo = XLSX.utils.json_to_sheet([suelo])
    XLSX.utils.book_append_sheet(wb, wsSuelo, 'Suelo')
  }

  // 6. Labores
  if (labores) {
    const wsLabores = XLSX.utils.json_to_sheet([labores])
    XLSX.utils.book_append_sheet(wb, wsLabores, 'Labores')
  }

  // 7. Evaluación
  if (evaluacion) {
    const wsEval = XLSX.utils.json_to_sheet([evaluacion])
    XLSX.utils.book_append_sheet(wb, wsEval, 'Evaluación')
  }

  // 8. Firmas (solo nombres base64)
  if (firmas) {
    const firmasSheet = XLSX.utils.json_to_sheet([{
      firma_realiza: firmas.firma_realiza ? 'Incluida (base64)' : 'No',
      firma_recibe: firmas.firma_recibe ? 'Incluida (base64)' : 'No',
      firma_testigo: firmas.firma_testigo ? 'Incluida (base64)' : 'No',
    }])
    XLSX.utils.book_append_sheet(wb, firmasSheet, 'Firmas')
  }

  // 📥 Descargar archivo
  XLSX.writeFile(wb, 'resumen_visita.xlsx')
}
