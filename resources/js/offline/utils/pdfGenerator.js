import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export async function generarResumenPDF({ area, fertilizaciones, polinizaciones, sanidad, suelo, labores, evaluacion, firmas, imagenes }) {
  const doc = new jsPDF()
  let y = 10

  const espacioSeguro = 280 // límite vertical de la página

  const saltarSiEsNecesario = (espacio) => {
    if (y + espacio > espacioSeguro) {
      doc.addPage()
      y = 10
    }
  }

  const addTitulo = (texto) => {
    saltarSiEsNecesario(10)
    doc.setFontSize(14)
    doc.text(texto, 10, y)
    y += 8
  }

  // --- ÁREA
  if (area) {
    addTitulo('📍 Información de Área')
    autoTable(doc, {
      startY: y,
      head: [['Campo', 'Valor']],
      body: [
        ['Material', area.material],
        ['Estado', area.estado],
        ['Año Siembra', area.anio_siembra],
        ['Área (m2)', area.area],
        ['Orden Plantis', area.orden_plantis_numero],
        ['Estado Orden Plantis', area.estado_oren_plantis]
      ]
    })
    y = doc.lastAutoTable.finalY + 10
  }

  // --- FERTILIZACIONES
  if (fertilizaciones.length) {
    addTitulo('💧 Fertilizaciones')
    fertilizaciones.forEach((f) => {
      autoTable(doc, {
        startY: y,
        head: [[`Fecha: ${f.fecha_fertilizacion}`]],
        body: f.fertilizantes.map(item => [`${item.nombre}: ${item.cantidad} kg`]),
        theme: 'plain'
      })
      y = doc.lastAutoTable.finalY + 5
    })
  }

  // --- POLINIZACIONES
  if (polinizaciones.length) {
    addTitulo('🌸 Polinizaciones')
    polinizaciones.forEach((p) => {
      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: [
          ['Fecha', p.fecha],
          ['N° Pases', p.n_pases],
          ['Ciclos Ronda', p.ciclos_ronda],
          ['ANA', `${p.ana} (${p.tipo_ana})`],
          ['Talco', `${p.talco} kg`],
        ]
      })
      y = doc.lastAutoTable.finalY + 10
    })
  }

  // --- SANIDAD
  if (sanidad) {
    addTitulo('🦠 Sanidad')
    autoTable(doc, {
      startY: y,
      head: [['Campo', 'Valor']],
      body: Object.entries(sanidad).filter(([k]) => k !== 'observaciones' && k !== 'visita_id').map(([k, v]) => [k, `${v}%`])
    })
    y = doc.lastAutoTable.finalY + 5
    if (sanidad.observaciones) {
      doc.setFontSize(12)
      doc.text('Observaciones:', 10, y)
      y += 5
      doc.text(doc.splitTextToSize(sanidad.observaciones, 180), 10, y)
      y += 10
    }
  }

  // --- SUELO
  if (suelo) {
    addTitulo('🧪 Análisis de Suelo')
    autoTable(doc, {
      startY: y,
      head: [['Campo', 'Valor']],
      body: [
        ['Análisis Foliar', suelo.analisis_foliar],
        ['Análisis Suelo', suelo.alanalisis_suelo],
        ['Tipo Suelo', suelo.tipo_suelo]
      ]
    })
    y = doc.lastAutoTable.finalY + 10
  }

  // --- LABORES
  if (labores) {
    addTitulo('🚜 Labores de Cultivo')
    autoTable(doc, {
      startY: y,
      head: [['Labor', 'Porcentaje']],
      body: Object.entries(labores).filter(([k]) => k !== 'visita_id').map(([k, v]) => [k.replace(/_/g, ' '), `${v}%`])
    })
    y = doc.lastAutoTable.finalY + 10
  }

  // --- EVALUACIÓN DE COSECHA
  if (evaluacion) {
    addTitulo('🌴 Evaluación de Cosecha')
    autoTable(doc, {
      startY: y,
      head: [['Campo', 'Valor']],
      body: [
        ['Variedad Fruto', evaluacion.variedad_fruto],
        ['Cantidad Racimos', evaluacion.cantidad_racimos],
        ['Verde', evaluacion.verde + '%'],
        ['Maduro', evaluacion.maduro + '%'],
        ['Sobremaduro', evaluacion.sobremaduro + '%'],
        ['Pedúnculo', evaluacion.pedunculo + '%'],
        ['Observaciones', evaluacion.observaciones || '']
      ]
    })
    y = doc.lastAutoTable.finalY + 10
  }

  // --- FIRMAS
  const addFirma = (titulo, imgData) => {
    if (!imgData) return
    saltarSiEsNecesario(30)
    doc.setFontSize(12)
    doc.text(titulo, 10, y)
    y += 2
    doc.addImage(imgData, 'PNG', 10, y + 2, 60, 30)
    y += 35
  }

  addTitulo('🖋️ Firmas de la Visita')
  addFirma('Firma quien realiza', firmas?.firma_realiza)
  addFirma('Firma quien recibe', firmas?.firma_recibe)
  addFirma('Firma testigo', firmas?.firma_testigo)

  // --- IMÁGENES
  if (imagenes.length) {
    doc.addPage()
    y = 10
    doc.setFontSize(14)
    doc.text('📸 Imágenes de la Visita', 10, y)
    y += 10

    let x = 10
    let maxH = 50
    const ancho = 60, alto = 45

    imagenes.forEach((img, idx) => {
      doc.addImage(img, 'JPEG', x, y, ancho, alto)
      x += ancho + 5
      if (x + ancho > 200) {
        x = 10
        y += alto + 5
        if (y + alto > 280) {
          doc.addPage()
          y = 10
        }
      }
    })
  }

  doc.save('resumen_visita.pdf')
}
