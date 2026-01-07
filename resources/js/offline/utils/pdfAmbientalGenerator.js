import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

/* ======================================================
   CONFIGURACIÓN GENERAL
====================================================== */

const MARGIN_X = 14
const CONTENT_WIDTH = 210 - (MARGIN_X * 2)

const introText = `
Palmas Oleaginosas Bucarelia, comprometida con el fortalecimiento de una producción responsable y sostenible,
continúa en el año 2025 con el acompañamiento técnico a sus proveedores de fruto, en articulación con el
Centro de Investigación de Cenipalma.

En el marco de este proceso, se desarrollan visitas de seguimiento ambiental orientadas a verificar el
cumplimiento normativo, la protección de los recursos naturales, la mitigación de impactos ambientales y la
adopción de buenas prácticas asociadas al uso eficiente del agua, manejo de residuos, conservación del suelo,
protección de ecosistemas y no deforestación.

Este acompañamiento busca promover una agricultura sostenible y ambientalmente responsable, asegurando la
viabilidad productiva de las plantaciones y el cumplimiento de los compromisos ambientales de la compañía y
sus proveedores.
`


function siNo(valor) {
  if (valor === 1 || valor === '1' || valor === true || valor === 'si') return 'Sí'
  if (valor === 0 || valor === '0' || valor === false || valor === 'no') return 'No'
  return 'N/A'
}

function drawProportionalImage(doc, img, x, y, maxWidth) {
  const ratio = img.width / img.height
  const width = maxWidth
  const height = width / ratio

  doc.addImage(img, 'JPEG', x, y, width, height)

  return y + height + 6 // margen inferior pequeño
}

function ensureSpace(doc, y, requiredHeight) {
  const pageHeight = 297
  const bottomMargin = 20

  if (y + requiredHeight > pageHeight - bottomMargin) {
    doc.addPage()
    return 30
  }

  return y
}



function formatDate(date) {
  if (!date) return 'N/A'
  try {
    return new Date(date).toLocaleDateString()
  } catch {
    return date
  }
}

async function loadImage(src) {
  return new Promise(resolve => {
    const img = new Image()
    img.onload = () => resolve(img)
    img.onerror = () => resolve(null)
    img.src = src
  })
}

function sectionTitle(doc, text, y) {
  doc.setFontSize(13)
  doc.setFont(undefined, 'bold')
  doc.text(text, MARGIN_X, y)
  return y + 5
}

function drawTable(doc, head, body, y) {
  autoTable(doc, {
    startY: y,
    head: [head],
    body,
    theme: 'grid',
    styles: {
      fontSize: 9,
      cellPadding: 3
    },
    headStyles: {
      fillColor: [46, 125, 50],
      textColor: 255,
      fontStyle: 'bold'
    },
    margin: { left: MARGIN_X, right: MARGIN_X }
  })

  return doc.lastAutoTable.finalY + 8
}

/* ======================================================
   GENERADOR PRINCIPAL
====================================================== */

export async function generarResumenAmbientalPDF(payload) {
  const {
    visitaInfo,
    aguaCaptacion,
    aguaUso,
    suelo,
    energia,
    gobernanza,
    emisiones,
    residuos,
    sustancias,
    vertimientos,
    hmp,
    avc,
    ecosistema,
    noDeforestacion,
    cierreAmbiental,
    headerImagePath,
    footerImagePath
  } = payload

  const doc = new jsPDF('p', 'mm', 'a4')
  let y = 30

  /* ================= HEADER ================= */
  if (headerImagePath) {
    const img = await loadImage(headerImagePath)
    if (img) doc.addImage(img, 'PNG', 0, 0, 210, 25)
  }

  /* ================= PORTADA ================= */
  doc.setFontSize(16)
  doc.setFont(undefined, 'bold')
  doc.text(
    'Palmas Oleaginosas Bucarelia S.A.S n/ PDF Visita Ambiental',
    105,
    y,
    { align: 'center' }
  )

  y += 12

  doc.setFontSize(9)
  doc.setFont(undefined, 'normal')

  const textWidth = 180
  const lineHeight = 4.5
  const introLines = doc.splitTextToSize(introText, textWidth)

  doc.text(introLines, MARGIN_X, y)

  y += introLines.length * lineHeight
  y += 10 // ← margen seguro para que la tabla no se pegue


  y = drawTable(doc,
    ['Proveedor', 'Plantación', 'Fecha visita'],
    [[
      visitaInfo?.proveedor?.nombre || 'N/A',
      visitaInfo?.plantacion?.nombre || 'N/A',
      formatDate(visitaInfo?.fecha)
    ]],
    y
  )

  /* ================= AGUA ================= */
  if (aguaCaptacion) {
    y = sectionTitle(doc, ' AGUA – CAPTACIÓN LEGAL', y)
    y = drawTable(doc,
      ['Permiso concesión', 'Ocupación cauce', 'Permisos captación', 'Registro agua'],
      [[
        siNo(aguaCaptacion.permiso_concesion),
        siNo(aguaCaptacion.permiso_ocupacion_cauce),
        siNo(aguaCaptacion.permisos_captacion),
        siNo(aguaCaptacion.registro_agua)
      ]],
      y
    )
  }

  if (aguaUso) {
    y = sectionTitle(doc, ' AGUA – USO EFICIENTE', y)
    y = drawTable(doc,
      ['Plan ahorro', 'Mantenimiento', 'Consumo'],
      [[
        siNo(aguaUso.plan_ahorro),
        siNo(aguaUso.mantenimiento_sistemas),
        aguaUso.consumo_agua ?? 'N/A'
      ]],
      y
    )
  }

  /* ================= SUELO ================= */
  if (suelo) {
    y = sectionTitle(doc, ' SUELO – CONSERVACIÓN', y)
    y = drawTable(doc,
      ['Prácticas', 'Erosión', 'Cobertura', 'Área intervenida'],
      [[
        siNo(suelo.practicas_conservacion),
        siNo(suelo.control_erosion),
        siNo(suelo.cobertura_vegetal),
        `${suelo.area_intervenida} ha`
      ]],
      y
    )

    if (suelo.area_total_usada || suelo.porcentaje) {
      y = drawTable(doc,
        ['Área total predio', 'Porcentaje intervenido'],
        [[
          suelo.area_total_usada ?? 'N/A',
          suelo.porcentaje ? `${suelo.porcentaje} %` : 'N/A'
        ]],
        y
      )
    }

    if (suelo.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[suelo.observaciones]], y)
    }
  }

  /* ================= ENERGÍA ================= */
  if (energia) {
    y = sectionTitle(doc, ' ENERGÍA – USO EFICIENTE', y)
    y = drawTable(doc,
      ['Registro', 'Plan', 'Consumo kWh', 'Seguimiento'],
      [[
        siNo(energia.registro_consumo_combustible),
        siNo(energia.plan_uso_eficiente),
        energia.consumo_energia_kwh ?? 'N/A',
        siNo(energia.seguimiento_indicadores)
      ]],
      y
    )

    if (energia.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[energia.observaciones]], y)
    }
  }

  /* ================= GOBERNANZA ================= */
  if (gobernanza) {
    y = sectionTitle(doc, ' GOBERNANZA HÍDRICA', y)
    y = drawTable(doc,
      ['Canales', 'Identifica actores', 'Participa gestión'],
      [[
        siNo(gobernanza.canales_comunicacion),
        siNo(gobernanza.identifica_actores_afectados),
        siNo(gobernanza.participa_actividades_gestion)
      ]],
      y
    )
  }

  /* ================= EMISIONES ================= */
  if (emisiones) {
    y = sectionTitle(doc, ' EMISIONES GEI', y)
    y = drawTable(doc,
      ['Cuantifica', 'Combustible', 'Distancia', 'Huella'],
      [[
        siNo(emisiones.cuantifica_emisiones),
        emisiones.combustible ?? 'N/A',
        emisiones.distancia ?? 'N/A',
        emisiones.huella_carbono ?? 'N/A'
      ]],
      y
    )

    y = drawTable(doc,
      ['Implementa reducción', 'Acciones'],
      [[
        siNo(emisiones.implementa_acciones_reduccion),
        emisiones.acciones_reduccion ?? 'N/A'
      ]],
      y
    )

    if (emisiones.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[emisiones.observaciones]], y)
    }
  }

  /* ================= RESIDUOS ================= */
  if (residuos) {
    y = sectionTitle(doc, ' RESIDUOS – MANEJO', y)
    y = drawTable(doc,
      ['Personas', 'Capacita', 'Capacitadas', '%'],
      [[
        residuos.personas_manipulan ?? 'N/A',
        siNo(residuos.capacita_personal),
        residuos.personas_capacitadas ?? 'N/A',
        residuos.porcentaje_capacitadas ?? 'N/A'
      ]],
      y
    )

    if (residuos.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[residuos.observaciones]], y)
    }
  }

  /* ================= SUSTANCIAS ================= */
if (sustancias) {
  y = sectionTitle(doc, 'SUSTANCIAS – MANEJO', y)

  y = drawTable(
    doc,
    ['Cuenta POES', 'Personal capacitado', 'Almacenamiento adecuado'],
    [[
      siNo(sustancias.cuenta_poes),
      siNo(sustancias.personal_capacitado),
      siNo(sustancias.almacenamiento_adecuado)
    ]],
    y
  )

  if (sustancias.observaciones) {
    doc.setFontSize(9)
    doc.setFont(undefined, 'normal')
    doc.text(
      `Observaciones: ${sustancias.observaciones}`,
      MARGIN_X,
      y,
      { maxWidth: CONTENT_WIDTH }
    )
    y += 10
  }

  /* ===== POES: IMAGEN EN PÁGINA EXCLUSIVA ===== */
  if (sustancias.poes_preview) {
  const img = await loadImage(sustancias.poes_preview)

  if (img) {
    const maxWidth = 120
    const maxHeight = 70

    let imgWidth = maxWidth
    let imgHeight = imgWidth * (img.height / img.width)

    if (imgHeight > maxHeight) {
      imgHeight = maxHeight
      imgWidth = imgHeight * (img.width / img.height)
    }

    // 👇 SOLO CREA PÁGINA SI NO CABE
    y = ensureSpace(doc, y, imgHeight + 15)

    doc.setFontSize(11)
    doc.setFont(undefined, 'bold')
    doc.text('Evidencia POES', MARGIN_X, y)
    y += 6

    const x = (210 - imgWidth) / 2
    doc.addImage(img, 'JPEG', x, y, imgWidth, imgHeight)

    y += imgHeight + 10
  }
}

}



  /* ================= VERTIMIENTOS ================= */
  if (vertimientos) {
    y = sectionTitle(doc, ' VERTIMIENTOS', y)
    y = drawTable(doc,
      [
        'Permiso',
        'Permitidos',
        'Totales',
        'Doméstico',
        'Agroquímicos',
        'Cumple',
        'Gestión',
        'Triple lavado'
      ],
      [[
        vertimientos.permiso_vertimientos,
        vertimientos.numero_vertimientos_permitidos,
        vertimientos.numero_vertimientos_totales,
        vertimientos.sistema_agua_domestica,
        vertimientos.sistema_agroquimicos,
        vertimientos.cumple_permiso,
        vertimientos.gestion_permiso,
        vertimientos.triple_lavado
      ]],
      y
    )

    if (vertimientos.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[vertimientos.observaciones]], y)
    }
  }

  /* ================= HMP ================= */
  if (hmp) {
    y = sectionTitle(doc, ' HMP', y)
    y = drawTable(doc,
      ['Implementa', 'Hectáreas', 'Porcentaje', 'Incluye diseño'],
      [[
        hmp.implementa_hmp,
        hmp.hectareas_hmp,
        hmp.porcentaje_hmp,
        hmp.incluye_hmp_disenio
      ]],
      y
    )

    if (hmp.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[hmp.observaciones]], y)
    }
  }

  /* ================= AVC ================= */
  if (avc) {
    y = sectionTitle(doc, ' AVC / ARC', y)
    y = drawTable(doc,
      ['Registros', 'Identifica', 'Especies', 'Fecha', 'Ubicación'],
      [[
        avc.registros_avistamientos,
        avc.identifica_avc_arc,
        avc.especies_identificadas ?? 'N/A',
        formatDate(avc.fecha_identificacion),
        avc.ubicacion_identificacion ?? 'N/A'
      ]],
      y
    )
  }

  /* ================= ECOSISTEMA ================= */
  if (ecosistema) {
    y = sectionTitle(doc, ' ECOSISTEMA – PROTECCIÓN', y)
    y = drawTable(doc,
      ['Planes', 'Fragmentos', 'Manejo', 'Ronda hídrica'],
      [[
        ecosistema.planes_manejo_diferenciados,
        ecosistema.acciones_conservacion_fragmentos,
        ecosistema.implementa_planes_manejo_diferenciado,
        ecosistema.respeta_distancias_ronda_hidrica
      ]],
      y
    )

    if (ecosistema.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[ecosistema.observaciones]], y)
    }
  }

  /* ================= NO DEFORESTACIÓN ================= */
  if (noDeforestacion) {
    y = sectionTitle(doc, ' NO DEFORESTACIÓN', y)
    y = drawTable(doc,
      [
        'Área total',
        'Estudios AVC/ARC',
        'Evidencias',
        'Permiso forestal',
        'Frontera agrícola'
      ],
      [[
        `${noDeforestacion.area_total} ha`,
        siNo(noDeforestacion.cuenta_estudios_avc_arc),
        siNo(noDeforestacion.evidencias_no_reemplazo_bosques),
        siNo(noDeforestacion.permiso_aprovechamiento_forestal),
        siNo(noDeforestacion.dentro_frontera_agricola)
      ]],
      y
    )

    if (noDeforestacion.restauracion_compensacion == 1) {
      y = drawTable(doc,
        ['Hectáreas', 'Fecha', 'Tipo', 'Porcentaje'],
        [[
          noDeforestacion.hectareas_restauracion,
          formatDate(noDeforestacion.fecha_restauracion),
          noDeforestacion.tipo_restauracion,
          noDeforestacion.porcentaje ? `${noDeforestacion.porcentaje} %` : 'N/A'
        ]],
        y
      )
    }

    if (noDeforestacion.observaciones) {
      y = drawTable(doc, ['Observaciones'], [[noDeforestacion.observaciones]], y)
    }
  }

  /* ================= CIERRE ================= */
  if (cierreAmbiental) {
    doc.addPage()
    y = 30
    y = sectionTitle(doc, ' CIERRE DE VISITA AMBIENTAL', y)

    y = drawTable(doc,
      ['Fecha', 'Estado', 'Observaciones', 'Recomendaciones'],
      [[
        formatDate(cierreAmbiental.fecha_cierre),
        cierreAmbiental.estado_visita,
        cierreAmbiental.observaciones_finales ?? 'N/A',
        cierreAmbiental.recomendaciones ?? 'N/A'
      ]],
      y
    )
  }

  /* ================= FIRMAS ================= */
  const firmas = [
    cierreAmbiental?.firma_responsable,
    cierreAmbiental?.firma_recibe,
    cierreAmbiental?.firma_testigo
  ].filter(Boolean)

  if (firmas.length) {
    doc.addPage()
    doc.text('FIRMAS', MARGIN_X, 30)

    let x = MARGIN_X
    for (const firma of firmas) {
      const img = await loadImage(firma)
      if (img) {
        doc.addImage(img, 'PNG', x, 40, 50, 30)
        x += 60
      }
    }
  }

  /* ================= EVIDENCIAS ================= */
  if (cierreAmbiental?.imagenes?.length) {
    doc.addPage()
    doc.text('EVIDENCIAS FOTOGRÁFICAS', MARGIN_X, 30)

    let x = MARGIN_X
    let yImg = 40
    let col = 0

    for (const img64 of cierreAmbiental.imagenes) {
      const img = await loadImage(img64)
      if (!img) continue

      doc.addImage(img, 'JPEG', x, yImg, 50, 40)
      col++
      x += 60

      if (col === 3) {
        col = 0
        x = MARGIN_X
        yImg += 45
      }
    }
  }

  /* ================= FOOTER ================= */
  if (footerImagePath) {
    const img = await loadImage(footerImagePath)
    if (img) doc.addImage(img, 'PNG', 0, 277, 210, 20)
  }

  doc.save(`revision_ambiental_${Date.now()}.pdf`)
}
