import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'


// Función para cargar imágenes
const loadImage = async (src) => {
  return new Promise((resolve, reject) => {
    const img = new Image()
    img.crossOrigin = 'anonymous'
    img.onload = () => resolve(img)
    img.onerror = () => {
      console.warn(`No se pudo cargar la imagen: ${src}`)
      resolve(null)
    }
    img.src = src
  })
}

// Funciones auxiliares
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })
  } catch (e) {
    return dateString
  }
}

const formatSiNo = (value) => {
  if (value === true || value === 'true' || value === 1 || value === 'si' || value === 'Sí') return 'Sí'
  if (value === false || value === 'false' || value === 0 || value === 'no' || value === 'No') return 'No'
  return value || 'N/A'
}

const formatDecimal = (value, decimals = 2) => {
  if (value === null || value === undefined) return 'N/A'
  return parseFloat(value).toFixed(decimals)
}

export async function generarResumenPDF({ 
  areas = [],
  fertilizaciones = [],
  polinizaciones = [],
  sanidad = null,
  suelo = null,
  laboresCultivo = [],
  evaluacionesCosecha = [],
  cierreVisita = {},
  visitaInfo = {},
  headerImagePath = '/images/header.png',
  footerImagePath = '/images/footer.png'
}) {
  try {
    console.log('🔄 Generando PDF según estructura del informe...')
    
    // Cargar imágenes del header y footer
    let headerImg = null
    let footerImg = null
    
    try {
      if (headerImagePath) {
        console.log(`📥 Cargando header desde: ${headerImagePath}`)
        headerImg = await loadImage(headerImagePath)
      }
      
      if (footerImagePath) {
        console.log(`📥 Cargando footer desde: ${footerImagePath}`)
        footerImg = await loadImage(footerImagePath)
      }
    } catch (error) {
      console.warn('Advertencia al cargar imágenes de header/footer:', error)
    }
    
    // Extraer información con valores por defecto
    const infoProveedor = visitaInfo?.proveedor?.proveedor_nombre || "No especificado"
    const infoFinca = visitaInfo?.plantacion?.nombre || "No especificado"
    const infoUbicacion = visitaInfo?.ubicacion || "No especificada"
    const infoTecnico = visitaInfo?.tecnico_campo || "No asignado"
    const infoFechaVisita = formatDate(visitaInfo?.fecha) || formatDate(new Date())
    
    // Inicializar PDF
    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4',
      compress: true
    })
    
    const pageWidth = doc.internal.pageSize.width
    const pageHeight = doc.internal.pageSize.height
    
    // DIMENSIONES DE HEADER Y FOOTER (basadas en imágenes)
    const headerHeight = 45  // Altura de la imagen del header
    const footerHeight = 25  // Altura de la imagen del footer
    
    // MÁRGENES FIJOS que respetan las imágenes
    const margin = {
      left: 15,
      right: 15,
      top: headerHeight + 5,  // Espacio después del header
      bottom: footerHeight + 10  // Espacio antes del footer
    }
    
    // Dimensiones del contenido seguro (área donde NO va header/footer)
    const contentWidth = pageWidth - margin.left - margin.right
    const contenidoMaxY = pageHeight - margin.bottom  // Límite máximo antes del footer
    
    let y = margin.top
    
    // Función para dibujar el header (imagen o fallback)
    const drawHeader = () => {
      if (headerImg) {
        try {
          // Centrar la imagen del header
          const headerX = (pageWidth - contentWidth) / 2
          doc.addImage(headerImg, 'PNG', headerX, 5, contentWidth, headerHeight)
        } catch (e) {
          console.warn('No se pudo agregar header image:', e)
          drawHeaderFallback()
        }
      } else {
        drawHeaderFallback()
      }
    }
    
    // Header de respaldo (si no hay imagen)
    const drawHeaderFallback = () => {
      doc.setFillColor(0, 100, 0)
      doc.rect(0, 0, pageWidth, 25, 'F')
      doc.setTextColor(255, 255, 255)
      doc.setFontSize(16)
      doc.setFont('helvetica', 'bold')
      doc.text('INFORME DE VISITA TÉCNICA', pageWidth / 2, 15, { align: 'center' })
      doc.setFontSize(10)
      doc.text('Palmas Oleaginosas Bucarelia', pageWidth / 2, 22, { align: 'center' })
      doc.setTextColor(0, 0, 0)
    }
    
    // Función para dibujar el footer (imagen o fallback)
    const drawFooter = (pageNum, totalPages) => {
      const footerY = pageHeight - footerHeight - 5
      
      if (footerImg) {
        try {
          // Centrar la imagen del footer
          const footerX = (pageWidth - contentWidth) / 2
          doc.addImage(footerImg, 'PNG', footerX, footerY, contentWidth, footerHeight)
        } catch (e) {
          console.warn('No se pudo agregar footer image:', e)
          drawFooterFallback(footerY, pageNum, totalPages)
        }
      } else {
        drawFooterFallback(footerY, pageNum, totalPages)
      }
    }
    
    // Footer de respaldo (si no hay imagen)
    const drawFooterFallback = (footerY, pageNum, totalPages) => {
      doc.setFillColor(0, 100, 0)
      doc.rect(0, footerY, pageWidth, footerHeight, 'F')
      doc.setTextColor(255, 255, 255)
      doc.setFontSize(9)
      doc.text('BucarellaOficial | pqrs@bucarella.com.co | www.bucarella.com.co', 
              pageWidth / 2, footerY + 8, { align: 'center' })
      doc.text(`Página ${pageNum} de ${totalPages}`, pageWidth - margin.right, footerY + 18, { align: 'right' })
      doc.setTextColor(0, 0, 0)
    }
    
    // Función para manejar saltos de página CON RESPETO A HEADER/FOOTER
    const checkPageBreak = (neededHeight) => {
      if (y + neededHeight > contenidoMaxY) {
        console.log(`📄 Nueva página necesaria! Y actual: ${y}mm, Necesita: ${neededHeight}mm, Máximo: ${contenidoMaxY}mm`)
        
        doc.addPage()
        y = margin.top
        
        // Agregar header en nuevas páginas
        drawHeader()
        return true
      }
      return false
    }
    
    // =========== PÁGINA 1 ===========
    
    // 1. HEADER (imagen)
    drawHeader()
    y = margin.top + 8  // Espacio después del header
    
    // 2. TÍTULO PRINCIPAL
    doc.setFontSize(14)
    doc.setFont('helvetica', 'bold')
    doc.text('INFORME DE VISITA TÉCNICA', pageWidth / 2, y, { align: 'center' })
    y += 10
    
    // 3. TABLA INFORMACIÓN GENERAL
    autoTable(doc, {
      startY: y,
      head: [['PROVEEDOR', 'PLANTACIÓN', 'UBICACIÓN', 'TÉCNICO', 'FECHA']],
      body: [[
        infoProveedor,
        infoFinca,
        infoUbicacion,
        infoTecnico,
        infoFechaVisita
      ]],
      theme: 'grid',
      styles: {
        fontSize: 9,
        cellPadding: 3,
        fontStyle: 'bold',
        minCellHeight: 8
      },
      headStyles: {
        fillColor: [0, 100, 0],
        textColor: [255, 255, 255],
        fontStyle: 'bold'
      },
      margin: { 
        left: margin.left, 
        right: margin.right 
      },
      tableWidth: contentWidth
    })
    y = doc.lastAutoTable.finalY + 15
    
    // 4. INTRODUCCIÓN (texto estático)
    checkPageBreak(50)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('1. INTRODUCCIÓN', margin.left, y)
    y += 7
    
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    const introText = "Palmas Oleaginosas Bucarelia, en pro de seguir apoyando a sus proveedores de fruto en el fortalecimiento de ser productivos y sostenibles, ha decidido continuar en el año 2025, el convenio con el centro de investigación de Cenipalma, con el fin de generar un impacto positivo en los proveedores de racimo de fruto fresca (RFF) de la compañía. Este convenio tiene como finalidad aumentar la productividad de nuestros proveedores de RFF, basados en una agricultura sostenible y amigable con el medio ambiente. Con este objetivo se continúa con las visitas de acompañamiento técnico, agronómico, social y ambiental, brindando apoyo en las labores relacionadas con estos componentes; así mismo impulsar a los proveedores a la adopción de nuevas tecnologías en sus plantaciones, con el único de fin de alcanzar las metas propuestas y alcanzar la sostenibilidad de sus cultivos."
    
    const introLines = doc.splitTextToSize(introText, contentWidth)
    doc.text(introLines, margin.left, y)
    y += introLines.length * 5 + 15
    
    // 5. INFORMACIÓN GENERAL DE LA FINCA
    checkPageBreak(40)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('2. INFORMACIÓN GENERAL DE LA FINCA', margin.left, y)
    y += 7
    
    // Calcular totales de áreas
    let totalPalmas = 0
    let palmasDesarrollo = 0
    let palmasProduccion = 0
    let palmasOrdenPlantis = 0
    let produccionTotal = 0
    let areaTotal = 0
    
    areas.forEach(area => {
      totalPalmas += parseInt(area.numero_palmas_total_finca) || 0
      palmasDesarrollo += parseInt(area.numero_palmas_desarrollo) || 0
      palmasProduccion += parseInt(area.numero_palmas_produccion) || 0
      
      if (area.aplica_orden_plantis) {
        palmasOrdenPlantis += parseInt(area.numero_plantas_orden_plantis) || 0
      }
      
      produccionTotal += parseFloat(area.produccion_toneladas_por_mes) || 0
      areaTotal += parseFloat(area.area_total_finca_hectareas) || 0
    })
    
    // Usar valores del primer área o calcular
    const ciclosCosecha = areas[0]?.ciclos_cosecha || 'N/A'
    const areaTotalDisplay = areaTotal > 0 ? `${formatDecimal(areaTotal)} Ha` : 'N/A'
    const produccionTotalDisplay = produccionTotal > 0 ? `${formatDecimal(produccionTotal)} Ton/Mes` : 'N/A'
    
    autoTable(doc, {
      startY: y,
      body: [
        ['Área Total Finca', areaTotalDisplay],
        ['Total de Palmas', `${totalPalmas} (incluye Orden Plantis)`],
        ['Ciclos de Cosecha', ciclosCosecha],
        ['Producción Total', produccionTotalDisplay],
        ['Palmas en Desarrollo', palmasDesarrollo],
        ['Palmas en Producción', palmasProduccion],
        ['Palmas Orden Plantis', palmasOrdenPlantis]
      ],
      theme: 'grid',
      styles: {
        fontSize: 10,
        cellPadding: 3,
        fontStyle: 'normal'
      },
      columnStyles: {
        0: {
          fontStyle: 'bold',
          fillColor: [240, 240, 240],
          cellWidth: 60
        }
      },
      margin: { left: margin.left, right: margin.right }
    })
    y = doc.lastAutoTable.finalY + 15
    
    // =========== PÁGINA 2 ===========
    
    // 6. ÁREAS INDIVIDUALES
    checkPageBreak(30)
    
    // Encabezado de empresa (como en página 2 del ejemplo)
    doc.setFontSize(14)
    doc.setFont('helvetica', 'bold')
    doc.text('Palmas Oleaginosas', pageWidth / 2, y, { align: 'center' })
    y += 6
    doc.setFontSize(12)
    doc.text('BUCARELIA S.A.S', pageWidth / 2, y, { align: 'center' })
    y += 5
    doc.setFontSize(10)
    doc.text('Nit. 860.009.787-9', pageWidth / 2, y, { align: 'center' })
    y += 10
    
    // Título de sección
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('3. ÁREAS INDIVIDUALES', margin.left, y)
    y += 10
    
    // Procesar cada área
    areas.forEach((area, index) => {
      checkPageBreak(80)
      
      // Título del área
      doc.setFontSize(11)
      doc.setFont('helvetica', 'bold')
      const areaTitle = `Área #${index + 1} - ${area.variedad || 'híbrido'}`
      doc.text(areaTitle, margin.left, y)
      y += 6
      
      // Tabla de datos del área
      const tableData = [
        ['Variedad', area.variedad || 'híbrido'],
        ['Material', area.material || 'N/A'],
        ['Estado', area.estado === 'produccion' ? 'Producción' : 'Desarrollo'],
        ['Año Siembra', area.anio_siembra ? String(area.anio_siembra) : '—'],
        ['Área (m²)', area.area || 'N/A'],
        ['Área Total Finca (Ha)', formatDecimal(area.area_total_finca_hectareas) || 'N/A'],
        ['N° Palmas Total Finca', area.numero_palmas_total_finca || 'N/A'],
        ['Área Desarrollo (Ha)', formatDecimal(area.area_palmas_desarrollo_hectareas) || 'N/A'],
        ['N° Palmas Desarrollo', area.numero_palmas_desarrollo || 'N/A'],
        ['Aplica Orden Plantis', formatSiNo(area.aplica_orden_plantis)],
        ['Orden Plantis N°', area.orden_plantis_numero || 'N/A'],
        ['Estado Orden Plantis', area.estado_oren_plantis || 'N/A'],
        ['N° Plantas Orden Plantis', area.numero_plantas_orden_plantis || 'N/A']
      ]
      
      autoTable(doc, {
        startY: y,
        body: tableData,
        theme: 'grid',
        styles: {
          fontSize: 9,
          cellPadding: 2.5,
          fontStyle: 'normal'
        },
        columnStyles: {
          0: {
            fontStyle: 'bold',
            fillColor: [240, 240, 240],
            cellWidth: 65
          }
        },
        margin: { left: margin.left, right: margin.right }
      })
      
      y = doc.lastAutoTable.finalY + 12
      
      // Línea separadora
      doc.setDrawColor(200, 200, 200)
      doc.line(margin.left, y, pageWidth - margin.right, y)
      y += 8
    })
    
    // =========== PÁGINA 3 ===========
    
    // 7. NUTRICIÓN (texto estático)
    checkPageBreak(50)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('4. NUTRICIÓN EN EL CULTIVO DE PALMA DE ACEITE', margin.left, y)
    y += 8
    
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    const nutricionText = "En el cultivo de palma de aceite, uno de los componentes más importantes es la nutrición, ya que de ello depende directamente la sostenibilidad productiva del cultivo a corto, mediano y largo plazo. Para alcanzar las metas y rendimientos esperados en producción, es necesario realizar y dar cumplimiento al plan de nutrición. De esta manera también fortalecemos la tolerancia del cultivo a diversos ataques relacionados con plagas y enfermedades y o factores climáticos."
    
    const nutricionLines = doc.splitTextToSize(nutricionText, contentWidth)
    doc.text(nutricionLines, margin.left, y)
    y += nutricionLines.length * 5 + 12
    
    // 8. FERTILIZACIONES
    if (fertilizaciones && fertilizaciones.length > 0) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('4.1. FERTILIZACIONES APLICADAS', margin.left, y)
      y += 8
      
      fertilizaciones.forEach((fertilizacion, index) => {
        checkPageBreak(50)
        
        doc.setFontSize(11)
        doc.setFont('helvetica', 'bold')
        doc.text(`Aplicación #${index + 1} - ${formatDate(fertilizacion.fecha_fertilizacion)}`, margin.left, y)
        y += 6
        
        // Verificar si tiene fertilizantes
        const fertilizantes = fertilizacion.fertilizantes || []
        
        if (fertilizantes.length > 0) {
          autoTable(doc, {
            startY: y,
            head: [['Fertilizante', 'Cantidad', 'Unidad', 'Fecha Aplicación']],
            body: fertilizantes.map(f => [
              f.fertilizante || f.nombre || 'N/A',
              f.cantidad || 'N/A',
              f.unidad_medida || 'N/A',
              formatDate(f.fecha_aplicacion)
            ]),
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 2.5 },
            headStyles: {
              fillColor: [0, 100, 0],
              textColor: [255, 255, 255],
              fontStyle: 'bold'
            },
            margin: { left: margin.left, right: margin.right }
          })
          y = doc.lastAutoTable.finalY + 12
        }
      })
    }
    
    // 9. POLINIZACIONES
    if (polinizaciones && polinizaciones.length > 0) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('4.2. POLINIZACIONES', margin.left, y)
      y += 8
      
      polinizaciones.forEach((polinizacion, index) => {
        checkPageBreak(40)
        
        const polData = [
          ['Fecha', formatDate(polinizacion.fecha)],
          ['N° Pases', polinizacion.n_pases || 'N/A'],
          ['Ciclos', polinizacion.ciclos_ronda || 'N/A'],
          ['ANA', `${polinizacion.ana || 'N/A'} (${polinizacion.tipo_ana || 'N/A'})`],
          ['Talco', `${polinizacion.talco || 'N/A'} kg`]
        ]
        
        autoTable(doc, {
          startY: y,
          head: [['Campo', 'Valor']],
          body: polData,
          theme: 'grid',
          styles: { fontSize: 9, cellPadding: 2.5 },
          columnStyles: {
            0: { fontStyle: 'bold', fillColor: [240, 240, 240], cellWidth: 50 }
          },
          headStyles: {
            fillColor: [0, 100, 0],
            textColor: [255, 255, 255],
            fontStyle: 'bold'
          },
          margin: { left: margin.left, right: margin.right }
        })
        
        y = doc.lastAutoTable.finalY + 12
      })
    }
    
    // 10. SANIDAD
    if (sanidad) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('5. ESTADO SANITARIO', margin.left, y)
      y += 8
      
      const sanidadData = []
      if (sanidad.censo_enfermedades !== undefined) sanidadData.push(['Censo de enfermedades', formatSiNo(sanidad.censo_enfermedades)])
      if (sanidad.ciclos_lectura_enfermedades) sanidadData.push(['Ciclos lectura enfermedades', sanidad.ciclos_lectura_enfermedades])
      if (sanidad.ciclos_lectura_plagas) sanidadData.push(['Ciclos lectura plagas', sanidad.ciclos_lectura_plagas])
      if (sanidad.otros) sanidadData.push(['Otros', sanidad.otros])
      
      if (sanidadData.length > 0) {
        autoTable(doc, {
          startY: y,
          body: sanidadData,
          theme: 'grid',
          styles: { fontSize: 9, cellPadding: 2.5 },
          columnStyles: {
            0: { fontStyle: 'bold', fillColor: [240, 240, 240], cellWidth: 60 }
          },
          margin: { left: margin.left, right: margin.right }
        })
        y = doc.lastAutoTable.finalY + 12
      }
      
      // Enfermedades
      if (sanidad.enfermedades && sanidad.enfermedades.length > 0) {
        checkPageBreak(30)
        doc.setFontSize(11)
        doc.setFont('helvetica', 'bold')
        doc.text('Enfermedades Detectadas:', margin.left, y)
        y += 6
        
        autoTable(doc, {
          startY: y,
          head: [['Nombre', 'Estado (%)', 'Observaciones']],
          body: sanidad.enfermedades.map(enf => [
            enf.nombre || '-',
            enf.estado || '-',
            enf.observaciones || '-'
          ]),
          theme: 'grid',
          styles: { fontSize: 9, cellPadding: 2.5 },
          headStyles: {
            fillColor: [150, 0, 0],
            textColor: [255, 255, 255],
            fontStyle: 'bold'
          },
          margin: { left: margin.left, right: margin.right }
        })
        y = doc.lastAutoTable.finalY + 12
      }
      
      // Plagas
      if (sanidad.plagas && sanidad.plagas.length > 0) {
        checkPageBreak(30)
        doc.setFontSize(11)
        doc.setFont('helvetica', 'bold')
        doc.text('Plagas Detectadas:', margin.left, y)
        y += 6
        
        autoTable(doc, {
          startY: y,
          head: [['Nombre', 'Estado', 'Instar']],
          body: sanidad.plagas.map(pla => [
            pla.nombre || '-',
            pla.estado || '-',
            pla.instar || 'No es Estado Larva ó No Registra Instar'
          ]),
          theme: 'grid',
          styles: { fontSize: 9, cellPadding: 2.5 },
          headStyles: {
            fillColor: [150, 0, 0],
            textColor: [255, 255, 255],
            fontStyle: 'bold'
          },
          margin: { left: margin.left, right: margin.right }
        })
        y = doc.lastAutoTable.finalY + 12
      }
      
      // Trampas
      if (sanidad.trampas && sanidad.trampas.length > 0) {
        checkPageBreak(30)
        doc.setFontSize(11)
        doc.setFont('helvetica', 'bold')
        doc.text('Trampas de Palmarum:', margin.left, y)
        y += 6
        
        autoTable(doc, {
          startY: y,
          head: [['Ciclos', 'Machos Capturados', 'Hembras Capturadas']],
          body: sanidad.trampas.map(trampa => [
            trampa.ciclos || '-',
            trampa.machos || '-',
            trampa.hembras || '-'
          ]),
          theme: 'grid',
          styles: { fontSize: 9, cellPadding: 2.5 },
          headStyles: {
            fillColor: [100, 100, 100],
            textColor: [255, 255, 255],
            fontStyle: 'bold'
          },
          margin: { left: margin.left, right: margin.right }
        })
        y = doc.lastAutoTable.finalY + 12
      }
      
      // Observaciones
      if (sanidad.observaciones) {
        checkPageBreak(20)
        doc.setFontSize(10)
        doc.setFont('helvetica', 'bold')
        doc.text('Observaciones de Sanidad:', margin.left, y)
        y += 6
        doc.setFont('helvetica', 'normal')
        const obsLines = doc.splitTextToSize(sanidad.observaciones, contentWidth)
        doc.text(obsLines, margin.left, y)
        y += obsLines.length * 5 + 10
      }
    }
    
    // =========== PÁGINA 4 ===========
    
    // 11. DESCRIPCIÓN DE LA VISITA (texto estático)
    checkPageBreak(40)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('9. DESCRIPCIÓN DE LA VISITA', margin.left, y)
    y += 8
    
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    const descripcionText = `La visita se realizó una visita de campo el ${infoFechaVisita}, en compañía del administrador y representantes de la unidad de asistencia. La visita se realizó en la plantación ${infoFinca} con el objetivo de hacer un diagnóstico de las labores del cultivo relacionadas con la cosecha, labores de mantenimiento, y sanidad del cultivo.`
    
    const descLines = doc.splitTextToSize(descripcionText, contentWidth)
    doc.text(descLines, margin.left, y)
    y += descLines.length * 5 + 15
    
    // 12. LABORES DE CULTIVO
    if (laboresCultivo && laboresCultivo.length > 0) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('7. LABORES DE CULTIVO', margin.left, y)
      y += 8
      
      laboresCultivo.forEach((labor, index) => {
        checkPageBreak(60)
        
        const laborData = []
        if (labor.tipo_planta) laborData.push(['Tipo Planta', labor.tipo_planta])
        if (labor.polinizacion !== undefined) laborData.push(['Polinización', `${labor.polinizacion}%`])
        if (labor.limpieza_calle !== undefined) laborData.push(['Limpieza Calle', `${labor.limpieza_calle}%`])
        if (labor.limpieza_plato !== undefined) laborData.push(['Limpieza Plato', `${labor.limpieza_plato}%`])
        if (labor.poda !== undefined) laborData.push(['Poda', `${labor.poda}%`])
        if (labor.fertilizacion !== undefined) laborData.push(['Fertilización', `${labor.fertilizacion}%`])
        if (labor.enmiendas !== undefined) laborData.push(['Enmiendas', `${labor.enmiendas}%`])
        if (labor.cobertura !== undefined) laborData.push(['Cobertura', `${labor.cobertura}%`])
        if (labor.drenajes !== undefined) laborData.push(['Drenajes', `${labor.drenajes}%`])
        if (labor.plantas_nectariferas !== undefined) laborData.push(['Plantas Nectaríferas', `${labor.plantas_nectariferas}%`])
        if (labor.labor_cosecha) laborData.push(['Labor Cosecha', labor.labor_cosecha])
        if (labor.calidad_fruta) laborData.push(['Calidad Fruta', labor.calidad_fruta])
        
        if (laborData.length > 0) {
          autoTable(doc, {
            startY: y,
            body: laborData,
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 2.5 },
            columnStyles: {
              0: { fontStyle: 'bold', fillColor: [240, 240, 240], cellWidth: 50 }
            },
            margin: { left: margin.left, right: margin.right }
          })
          y = doc.lastAutoTable.finalY + 8
        }
        
        if (labor.observaciones) {
          doc.setFontSize(9)
          doc.setFont('helvetica', 'italic')
          doc.text(`Observaciones: ${labor.observaciones}`, margin.left, y)
          y += 8
        }
      })
    }
    
    // 13. EVALUACIÓN DE COSECHA
    if (evaluacionesCosecha && evaluacionesCosecha.length > 0) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('8. EVALUACIÓN DE COSECHA', margin.left, y)
      y += 8
      
      evaluacionesCosecha.forEach((evaluacion, index) => {
        checkPageBreak(40)
        
        const evalData = []
        if (evaluacion.variedad_fruto) evalData.push(['Variedad Fruto', evaluacion.variedad_fruto])
        if (evaluacion.cantidad_racimos) evalData.push(['Cantidad Racimos', evaluacion.cantidad_racimos])
        if (evaluacion.verde !== undefined) evalData.push(['Verde', `${evaluacion.verde}%`])
        if (evaluacion.maduro !== undefined) evalData.push(['Maduro', `${evaluacion.maduro}%`])
        if (evaluacion.sobremaduro !== undefined) evalData.push(['Sobremaduro', `${evaluacion.sobremaduro}%`])
        if (evaluacion.pedunculo !== undefined) evalData.push(['Pedúnculo', `${evaluacion.pedunculo}%`])
        if (evaluacion.conformacion) evalData.push(['Conformación', evaluacion.conformacion])
        
        if (evalData.length > 0) {
          autoTable(doc, {
            startY: y,
            body: evalData,
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 2.5 },
            columnStyles: {
              0: { fontStyle: 'bold', fillColor: [240, 240, 240], cellWidth: 50 }
            },
            margin: { left: margin.left, right: margin.right }
          })
          y = doc.lastAutoTable.finalY + 8
        }
        
        if (evaluacion.observaciones) {
          doc.setFontSize(9)
          doc.text(`Observaciones: ${evaluacion.observaciones}`, margin.left, y)
          y += 8
        }
      })
    }
    
    // 14. OBSERVACIONES FINALES
    if (cierreVisita?.observaciones_finales) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('10. OBSERVACIONES FINALES', margin.left, y)
      y += 8
      
      doc.setFontSize(10)
      doc.setFont('helvetica', 'normal')
      const obsLines = doc.splitTextToSize(cierreVisita.observaciones_finales, contentWidth)
      doc.text(obsLines, margin.left, y)
      y += obsLines.length * 5 + 12
    }
    
    // 15. RECOMENDACIONES
    if (cierreVisita?.recomendaciones) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('11. RECOMENDACIONES', margin.left, y)
      y += 8
      
      doc.setFontSize(10)
      doc.setFont('helvetica', 'normal')
      const recLines = doc.splitTextToSize(cierreVisita.recomendaciones, contentWidth)
      doc.text(recLines, margin.left, y)
      y += recLines.length * 5 + 12
    }
    
    // 16. FIRMAS (si existen)
    const firmas = []
    if (cierreVisita?.firma_responsable) firmas.push({ img: cierreVisita.firma_responsable, titulo: 'Técnico Responsable' })
    if (cierreVisita?.firma_recibe) firmas.push({ img: cierreVisita.firma_recibe, titulo: 'Representante Finca' })
    if (cierreVisita?.firma_testigo) firmas.push({ img: cierreVisita.firma_testigo, titulo: 'Testigo' })
    
    if (firmas.length > 0) {
      checkPageBreak(60)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('FIRMAS', pageWidth / 2, y, { align: 'center' })
      y += 12
      
      const colWidth = pageWidth / firmas.length
      let x = colWidth / 2
      
      firmas.forEach((firma, index) => {
        doc.setFontSize(10)
        doc.setFont('helvetica', 'normal')
        doc.text(firma.titulo, x, y, { align: 'center' })
        
        // Línea para firma
        doc.setLineWidth(0.5)
        doc.line(x - 20, y + 25, x + 20, y + 25)
        
        if (firma.img) {
          try {
            // Ajustar tamaño de la firma
            doc.addImage(firma.img, 'PNG', x - 15, y + 5, 30, 15)
          } catch (e) {
            console.warn(`No se pudo agregar firma ${firma.titulo}:`, e)
          }
        }
        
        x += colWidth
      })
      y += 40
    }
    
    // 17. IMÁGENES DE LA VISITA
    if (cierreVisita?.imagenes && cierreVisita.imagenes.length > 0) {
      doc.addPage()
      y = margin.top
      
      // Header en página de imágenes
      drawHeader()
      y = margin.top + 20
      
      doc.setFontSize(14)
      doc.setFont('helvetica', 'bold')
      doc.text('REGISTRO FOTOGRÁFICO DE LA VISITA', pageWidth / 2, y, { align: 'center' })
      y += 15
      
      const imgWidth = 85
      const imgHeight = 60
      const marginX = 12.5
      
      cierreVisita.imagenes.forEach((img, index) => {
        if (y + imgHeight > contenidoMaxY) {
          doc.addPage()
          y = margin.top
          drawHeader()
          y = margin.top + 20
        }
        
        const col = index % 2
        const row = Math.floor(index / 2)
        
        const x = marginX + (col * (imgWidth + 5))
        const currentY = y + (row * (imgHeight + 20))
        
        if (currentY + imgHeight > contenidoMaxY) {
          doc.addPage()
          y = margin.top
          drawHeader()
          y = margin.top + 20
          const newCol = index % 2
          const newX = marginX + (newCol * (imgWidth + 5))
          try {
            doc.addImage(img, 'JPEG', newX, y, imgWidth, imgHeight)
            doc.setFontSize(9)
            doc.text(`Foto ${index + 1}`, newX + 5, y + imgHeight + 5)
          } catch (e) {
            console.warn(`No se pudo agregar foto ${index + 1}:`, e)
          }
        } else {
          try {
            doc.addImage(img, 'JPEG', x, currentY, imgWidth, imgHeight)
            doc.setFontSize(9)
            doc.text(`Foto ${index + 1}`, x + 5, currentY + imgHeight + 5)
          } catch (e) {
            console.warn(`No se pudo agregar foto ${index + 1}:`, e)
          }
        }
      })
    }
    
    // =========== AGREGAR FOOTER EN TODAS LAS PÁGINAS ===========
    const totalPages = doc.internal.getNumberOfPages()
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i)
      drawFooter(i, totalPages)
    }
    
    // Guardar el PDF
    const fileName = `Informe_Visita_${infoFinca.replace(/\s+/g, '_')}_${infoFechaVisita.replace(/\//g, '-')}.pdf`
    doc.save(fileName)
    
    console.log('✅ PDF generado exitosamente')
    return true
    
  } catch (error) {
    console.error('❌ Error al generar el PDF:', error)
    throw error
  }
}