import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

/* =========================
   FUNCIONES AUXILIARES
========================= */

const loadImage = async (src) => {
  return new Promise((resolve) => {
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
  if (value === true || value === 'true' || value === 1 || value === 'si' || value === 'Sí' || value === 'Yes') return 'Sí'
  if (value === false || value === 'false' || value === 0 || value === 'no' || value === 'No') return 'No'
  return value || 'N/A'
}

/* =========================
   GENERADOR PDF SOCIAL CORREGIDO
========================= */

export async function generarResumenPDFSocial({
  // Nuevos parámetros para compatibilidad con tu estructura
  datosPersonales = {},
  miembrosHogar = [],
  datosPredio = {},
  datosFuerzaLaboral = {},
  datosOrganizacion = {},
  datosCierre = {},
  visitaId = '',
  proveedorNombre = '',
  
  // Parámetros originales (mantenidos para compatibilidad)
  visitaInfo = {},
  socialInfo = {},
  familia = [],
  vivienda = {},
  educacion = [],
  salud = {},
  ingresos = {},
  observaciones = '',
  recomendaciones = '',
  firmas = [],
  headerImagePath = '/images/header.png',
  footerImagePath = '/images/footer.png'
}) {
  try {
    console.log('🔄 Generando PDF de Informe Social...')
    
    // ============================================
    // MAPEO COMPLETO DE LOS NUEVOS PARÁMETROS
    // ============================================
    
    if (datosPersonales || proveedorNombre) {
      console.log('📋 Detectados nuevos parámetros, mapeando a estructura original...')
      console.log('Datos personales recibidos:', datosPersonales)
      console.log('Datos predio recibidos:', datosPredio)
      console.log('Datos fuerza laboral recibidos:', datosFuerzaLaboral)
      console.log('Datos organización recibidos:', datosOrganizacion)
      console.log('Datos cierre recibidos:', datosCierre)
      
      // MAPEAR DATOS PERSONALES
      socialInfo = {
        nombre: datosPersonales?.nombre || proveedorNombre || 'No especificado',
        documento: datosPersonales?.documento || datosPersonales?.documento_identidad || 'N/A',
        fecha_nacimiento: datosPersonales?.fecha_nacimiento,
        edad: datosPersonales?.edad || 'N/A',
        estado_civil: datosPersonales?.estado_civil || 'N/A',
        telefono: datosPersonales?.telefono || 'N/A',
        direccion: datosPersonales?.direccion_residencia || 'N/A',
        nivel_educativo: datosPersonales?.nivel_estudio || datosPersonales?.nivel_educativo || 'N/A',
        ocupacion: datosPersonales?.ocupacion_principal || 'N/A',
        afiliacion_salud: datosPersonales?.regimen_salud || datosPersonales?.afiliacion_salud || 'N/A',
        eps_ars: datosPersonales?.eps || datosPersonales?.eps_ars || 'N/A',
        rnp: datosPersonales?.rnp || 'N/A',
        fedepalma: datosPersonales?.fedepalma || 'N/A',
        sexo: datosPersonales?.sexo || 'N/A',
        alfabetizado: datosPersonales?.alfabetizado || 'N/A',
        grupo_poblacional: datosPersonales?.grupo_poblacional || 'N/A',
        anios_palmicultura: datosPersonales?.anios_palmicultura || 'N/A',
        reside_predio: datosPersonales?.reside_predio || 'N/A',
        administra_cultivo: datosPersonales?.administra_cultivo || 'N/A',
        internet: datosPersonales?.internet || 'N/A',
        red_social: datosPersonales?.red_social || 'N/A'
      }
      
      // MAPEAR MIEMBROS DEL HOGAR (familia)
      familia = Array.isArray(miembrosHogar) 
        ? miembrosHogar.map(miembro => ({
            nombre: miembro.nombre || 'N/A',
            parentesco: miembro.parentezco || miembro.parentesco || 'N/A',
            edad: miembro.edad || 'N/A',
            ocupacion: miembro.ocupacion || 'N/A',
            nivel_educativo: miembro.nivel_estudio || 'N/A',
            documento: miembro.documento || 'N/A',
            sexo: miembro.sexo || 'N/A',
            reside_predio: miembro.reside_predio || 'N/A',
            participa_labores: miembro.participa_labores || 'N/A'
          }))
        : []
      
      // MAPEAR DATOS DEL PREDIO (vivienda)
      vivienda = {
        tipo: 'Predio agrícola',
        tenencia: datosPredio?.forma_tenencia || 'N/A',
        servicios: Array.isArray(datosPredio?.servicios_publicos) 
          ? datosPredio.servicios_publicos.join(', ') 
          : datosPredio?.servicios_publicos || 'N/A',
        nombre_finca: datosPredio?.nombre_finca || 'N/A',
        municipio: datosPredio?.municipio || 'N/A',
        vereda: datosPredio?.vereda || 'N/A',
        registrado_ica: datosPredio?.registrado_ica || 'N/A',
        vive_predio: datosPredio?.vive_predio || 'N/A',
        infraestructura_predio: datosPredio?.infraestructura_predio || 'N/A',
        infraestructura_vial: Array.isArray(datosPredio?.infraestructura_vial)
          ? datosPredio.infraestructura_vial.join(', ')
          : datosPredio?.infraestructura_vial || 'N/A',
        observaciones: datosPredio?.observaciones || 'N/A'
      }
      
      // MAPEAR DATOS DE EDUCACIÓN (de miembros del hogar)
      educacion = Array.isArray(miembrosHogar)
        ? miembrosHogar
            .filter(m => m.nivel_estudio)
            .map(m => ({
              nombre: m.nombre || 'N/A',
              nivel_educativo: m.nivel_estudio || 'N/A',
              institucion: 'N/A',
              grado: 'N/A',
              asistencia_regular: m.participa_labores || 'N/A'
            }))
        : []
      
      // MAPEAR DATOS DE SALUD (ya incluidos en socialInfo)
      salud = {
        afiliacion_salud: datosPersonales?.regimen_salud || datosPersonales?.afiliacion_salud || 'N/A',
        eps_ars: datosPersonales?.eps || datosPersonales?.eps_ars || 'N/A',
        enfermedades_cronicas: datosPersonales?.enfermedades_cronicas || 'N/A',
        discapacidad: datosPersonales?.discapacidad || 'N/A',
        tipo_discapacidad: datosPersonales?.tipo_discapacidad || 'N/A'
      }
      
      // MAPEAR DATOS DE INGRESOS Y FUERZA LABORAL
      ingresos = {
        num_trabajadores: datosFuerzaLaboral?.num_trabajadores || 'N/A',
        num_hombres: datosFuerzaLaboral?.num_hombres || 'N/A',
        num_mujeres: datosFuerzaLaboral?.num_mujeres || 'N/A',
        forma_contratacion: Array.isArray(datosFuerzaLaboral?.forma_contratacion)
          ? datosFuerzaLaboral.forma_contratacion.join(', ')
          : datosFuerzaLaboral?.forma_contratacion || 'N/A',
        contrato_formal: datosFuerzaLaboral?.contrato_formal || 'N/A',
        seguridad_social: datosFuerzaLaboral?.seguridad_social || 'N/A',
        sg_sst: datosFuerzaLaboral?.sg_sst || 'N/A',
        dotacion: datosFuerzaLaboral?.dotacion || 'N/A',
        observaciones: datosFuerzaLaboral?.observaciones || 'N/A'
      }
      
      // MAPEAR DATOS DE ORGANIZACIÓN SOCIAL
      const organizacionData = {
        pertenece_jac: datosOrganizacion?.pertenece_jac || 'N/A',
        pertenece_asociacion: datosOrganizacion?.pertenece_asociacion || 'N/A',
        nombre_asociacion: datosOrganizacion?.nombre_asociacion || 'N/A',
        participa_otras_organizaciones: datosOrganizacion?.participa_otras_organizaciones || 'N/A',
        cargos_directivos: datosOrganizacion?.cargos_directivos || 'N/A',
        frecuencia_participacion: datosOrganizacion?.frecuencia_participacion || 'N/A',
        beneficios_participacion: Array.isArray(datosOrganizacion?.beneficios_participacion)
          ? datosOrganizacion.beneficios_participacion.join(', ')
          : datosOrganizacion?.beneficios_participacion || 'N/A',
        descripcion_cargos: datosOrganizacion?.descripcion_cargos || 'N/A',
        observaciones: datosOrganizacion?.observaciones || 'N/A'
      }
      
      // MAPEAR OBSERVACIONES Y RECOMENDACIONES
      observaciones = datosCierre?.observaciones_finales || observaciones
      recomendaciones = datosCierre?.recomendaciones || recomendaciones
      
      // MAPEAR FIRMAS SI EXISTEN
      if (datosCierre) {
        firmas = []
        if (datosCierre.firma_responsable) {
          firmas.push({
            img: datosCierre.firma_responsable,
            titulo: 'Responsable'
          })
        }
        if (datosCierre.firma_recibe) {
          firmas.push({
            img: datosCierre.firma_recibe,
            titulo: 'Recibe'
          })
        }
        if (datosCierre.firma_testigo) {
          firmas.push({
            img: datosCierre.firma_testigo,
            titulo: 'Testigo'
          })
        }
      }
      
      // CREAR VISITAINFO
      visitaInfo = {
        proveedor: proveedorNombre || socialInfo.nombre || 'No especificado',
        finca: datosPredio?.nombre_finca || 'N/A',
        ubicacion: `${datosPredio?.municipio || ''} ${datosPredio?.vereda ? `- Vereda ${datosPredio.vereda}` : ''}`.trim(),
        fecha: datosCierre?.fecha_cierre || new Date().toISOString().split('T')[0],
        tecnico_campo: 'N/A'
      }
      
      console.log('✅ Mapeo completado:', { 
        socialInfo, 
        familiaCount: familia.length,
        vivienda, 
        educacionCount: educacion.length,
        salud, 
        ingresos,
        organizacionData,
        visitaInfo 
      })
    }
    
    // ============================================
    // CARGAR IMÁGENES DE HEADER Y FOOTER
    // ============================================
    
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
    const infoProveedor = visitaInfo?.proveedor || socialInfo?.nombre || "No especificado"
    const infoFinca = visitaInfo?.finca || datosPredio?.nombre_finca || "No especificado"
    const infoUbicacion = visitaInfo?.ubicacion || 
                         `${datosPredio?.municipio || ''} ${datosPredio?.vereda ? `- Vereda ${datosPredio.vereda}` : ''}`.trim() || 
                         "No especificada"
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
    const headerHeight = 45
    const footerHeight = 25
    
    // MÁRGENES FIJOS que respetan las imágenes
    const margin = {
      left: 15,
      right: 15,
      top: headerHeight + 5,
      bottom: footerHeight + 10
    }
    
    // Dimensiones del contenido seguro (área donde NO va header/footer)
    const contentWidth = pageWidth - margin.left - margin.right
    const contenidoMaxY = pageHeight - margin.bottom
    let y = margin.top
    
    /* =========================
       HEADER / FOOTER FUNCTIONS
    ========================= */
    
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
    
    const drawHeaderFallback = () => {
      doc.setFillColor(0, 100, 0)
      doc.rect(0, 0, pageWidth, 25, 'F')
      doc.setTextColor(255, 255, 255)
      doc.setFontSize(16)
      doc.setFont('helvetica', 'bold')
      doc.text('INFORME SOCIAL DE VISITA', pageWidth / 2, 15, { align: 'center' })
      doc.setFontSize(10)
      doc.text('Palmas Oleaginosas Bucarelia', pageWidth / 2, 22, { align: 'center' })
      doc.setTextColor(0, 0, 0)
    }
    
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
    
    /* =========================
       PÁGINA 1
    ========================= */
    
    // 1. HEADER (imagen)
    drawHeader()
    y = margin.top + 8
    
    // 2. TÍTULO PRINCIPAL
    doc.setFontSize(14)
    doc.setFont('helvetica', 'bold')
    doc.text('INFORME SOCIAL DE VISITA', pageWidth / 2, y, { align: 'center' })
    y += 10
    
    // 3. TABLA INFORMACIÓN GENERAL
    autoTable(doc, {
      startY: y,
      head: [['PROVEEDOR', 'FINCA', 'UBICACIÓN', 'FECHA']],
      body: [[
        infoProveedor,
        infoFinca,
        infoUbicacion,
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
    
    // 4. INTRODUCCIÓN
    checkPageBreak(50)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('1. INTRODUCCIÓN', margin.left, y)
    y += 7
    
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    const introText = "El presente informe social tiene como objetivo caracterizar las condiciones sociales, familiares y económicas del proveedor visitado. Esta evaluación permite identificar necesidades, fortalezas y oportunidades de mejora en el bienestar del productor y su familia, contribuyendo al desarrollo sostenible de las comunidades vinculadas a la cadena de valor de la palma de aceite."
    
    const introLines = doc.splitTextToSize(introText, contentWidth)
    doc.text(introLines, margin.left, y)
    y += introLines.length * 5 + 15
    
    // 5. INFORMACIÓN SOCIAL DEL PROVEEDOR (DATOS PERSONALES COMPLETOS)
    checkPageBreak(60)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('2. INFORMACIÓN SOCIAL DEL PROVEEDOR', margin.left, y)
    y += 7
    
    const datosPersonalesCompletos = [
      ['Nombre Completo', socialInfo.nombre || 'N/A'],
      ['Fecha de Nacimiento', formatDate(socialInfo.fecha_nacimiento)],
      ['Sexo', socialInfo.sexo || 'N/A'],
      ['Teléfono/Celular', socialInfo.telefono || 'N/A'],
      ['Alfabetizado', formatSiNo(socialInfo.alfabetizado)],
      ['Nivel Educativo', socialInfo.nivel_educativo || 'N/A'],
      ['Grupo Poblacional', socialInfo.grupo_poblacional || 'N/A'],
      ['Años en Palmicultura', socialInfo.anios_palmicultura || 'N/A'],
      ['Reside en el Predio', formatSiNo(socialInfo.reside_predio)],
      ['Administra el Cultivo', formatSiNo(socialInfo.administra_cultivo)],
      ['Acceso a Internet', formatSiNo(socialInfo.internet)],
      ['Red Social Principal', socialInfo.red_social || 'N/A'],
      ['Afiliación en Salud', socialInfo.afiliacion_salud || 'N/A'],
      ['EPS/ARS', socialInfo.eps_ars || 'N/A'],
      ['RNP', socialInfo.rnp || 'N/A'],
      ['Fedepalma', socialInfo.fedepalma || 'N/A']
    ]
    
    autoTable(doc, {
      startY: y,
      body: datosPersonalesCompletos,
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
          cellWidth: 70
        }
      },
      margin: { left: margin.left, right: margin.right }
    })
    y = doc.lastAutoTable.finalY + 15
    
    /* =========================
       PÁGINA 2
    ========================= */
    
    // 6. ENCABEZADO DE EMPRESA
    checkPageBreak(30)
    
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
    
    // 7. COMPOSICIÓN FAMILIAR
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('3. COMPOSICIÓN FAMILIAR', margin.left, y)
    y += 8
    
    if (familia && familia.length > 0) {
      autoTable(doc, {
        startY: y,
        head: [['Nombre', 'Parentesco', 'Edad', 'Sexo', 'Ocupación', 'Nivel Educativo', 'Reside en Predio', 'Participa en Labores']],
        body: familia.map(f => [
          f.nombre || 'N/A',
          f.parentesco || 'N/A',
          f.edad || 'N/A',
          f.sexo || 'N/A',
          f.ocupacion || 'N/A',
          f.nivel_educativo || 'N/A',
          formatSiNo(f.reside_predio),
          formatSiNo(f.participa_labores)
        ]),
        theme: 'grid',
        styles: {
          fontSize: 8,
          cellPadding: 2,
          fontStyle: 'normal'
        },
        headStyles: {
          fillColor: [0, 100, 0],
          textColor: [255, 255, 255],
          fontStyle: 'bold'
        },
        margin: { left: margin.left, right: margin.right }
      })
      y = doc.lastAutoTable.finalY + 12
    } else {
      doc.setFontSize(10)
      doc.setFont('helvetica', 'normal')
      doc.text('No se registró información de composición familiar.', margin.left, y)
      y += 8
    }
    
    // 8. CONDICIONES DEL PREDIO/VIVIENDA
    checkPageBreak(30)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('4. CONDICIONES DEL PREDIO', margin.left, y)
    y += 8
    
    const predioData = []
    if (vivienda.nombre_finca) predioData.push(['Nombre de la Finca', vivienda.nombre_finca])
    if (vivienda.tenencia) predioData.push(['Forma de Tenencia', vivienda.tenencia])
    if (vivienda.municipio) predioData.push(['Municipio', vivienda.municipio])
    if (vivienda.vereda) predioData.push(['Vereda', vivienda.vereda])
    if (vivienda.registrado_ica) predioData.push(['Registrado en ICA', vivienda.registrado_ica])
    if (vivienda.vive_predio) predioData.push(['Vive en el Predio', formatSiNo(vivienda.vive_predio)])
    if (vivienda.infraestructura_predio) predioData.push(['Infraestructura del Predio', vivienda.infraestructura_predio])
    if (vivienda.infraestructura_vial) predioData.push(['Infraestructura Vial', vivienda.infraestructura_vial])
    if (vivienda.servicios) predioData.push(['Servicios Públicos', vivienda.servicios])
    
    if (predioData.length > 0) {
      autoTable(doc, {
        startY: y,
        body: predioData,
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
    }
    
    if (vivienda.observaciones) {
      checkPageBreak(20)
      doc.setFontSize(10)
      doc.setFont('helvetica', 'bold')
      doc.text('Observaciones del Predio:', margin.left, y)
      y += 5
      doc.setFont('helvetica', 'normal')
      const obsLines = doc.splitTextToSize(vivienda.observaciones, contentWidth)
      doc.text(obsLines, margin.left, y)
      y += obsLines.length * 5 + 8
    }
    
    /* =========================
       PÁGINA 3
    ========================= */
    
    // 9. FUERZA LABORAL
    checkPageBreak(30)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('5. FUERZA LABORAL', margin.left, y)
    y += 8
    
    const fuerzaLaboralData = []
    if (ingresos.num_trabajadores) fuerzaLaboralData.push(['Total de Trabajadores', ingresos.num_trabajadores])
    if (ingresos.num_hombres) fuerzaLaboralData.push(['Número de Hombres', ingresos.num_hombres])
    if (ingresos.num_mujeres) fuerzaLaboralData.push(['Número de Mujeres', ingresos.num_mujeres])
    if (ingresos.forma_contratacion) fuerzaLaboralData.push(['Formas de Contratación', ingresos.forma_contratacion])
    if (ingresos.contrato_formal) fuerzaLaboralData.push(['Contrato Formal', formatSiNo(ingresos.contrato_formal)])
    if (ingresos.seguridad_social) fuerzaLaboralData.push(['Seguridad Social', formatSiNo(ingresos.seguridad_social)])
    if (ingresos.sg_sst) fuerzaLaboralData.push(['Sistema de Gestión SST', formatSiNo(ingresos.sg_sst)])
    if (ingresos.dotacion) fuerzaLaboralData.push(['Dotación', formatSiNo(ingresos.dotacion)])
    
    if (fuerzaLaboralData.length > 0) {
      autoTable(doc, {
        startY: y,
        body: fuerzaLaboralData,
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
    }
    
    if (ingresos.observaciones) {
      checkPageBreak(20)
      doc.setFontSize(10)
      doc.setFont('helvetica', 'bold')
      doc.text('Observaciones de Fuerza Laboral:', margin.left, y)
      y += 5
      doc.setFont('helvetica', 'normal')
      const obsLines = doc.splitTextToSize(ingresos.observaciones, contentWidth)
      doc.text(obsLines, margin.left, y)
      y += obsLines.length * 5 + 8
    }
    
    // 10. ORGANIZACIÓN SOCIAL
    if (datosOrganizacion && Object.keys(datosOrganizacion).length > 0) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('6. ORGANIZACIÓN SOCIAL', margin.left, y)
      y += 8
      
      const organizacionData = []
      if (datosOrganizacion.pertenece_jac) organizacionData.push(['Pertenece a JAC', formatSiNo(datosOrganizacion.pertenece_jac)])
      if (datosOrganizacion.pertenece_asociacion) organizacionData.push(['Pertenece a Asociación', formatSiNo(datosOrganizacion.pertenece_asociacion)])
      if (datosOrganizacion.nombre_asociacion) organizacionData.push(['Nombre de la Asociación', datosOrganizacion.nombre_asociacion])
      if (datosOrganizacion.participa_otras_organizaciones) organizacionData.push(['Participa en Otras Organizaciones', formatSiNo(datosOrganizacion.participa_otras_organizaciones)])
      if (datosOrganizacion.cargos_directivos) organizacionData.push(['Cargos Directivos', formatSiNo(datosOrganizacion.cargos_directivos)])
      if (datosOrganizacion.frecuencia_participacion) organizacionData.push(['Frecuencia de Participación', datosOrganizacion.frecuencia_participacion])
      if (datosOrganizacion.beneficios_participacion) organizacionData.push(['Beneficios de la Participación', datosOrganizacion.beneficios_participacion])
      
      if (organizacionData.length > 0) {
        autoTable(doc, {
          startY: y,
          body: organizacionData,
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
              cellWidth: 70
            }
          },
          margin: { left: margin.left, right: margin.right }
        })
        y = doc.lastAutoTable.finalY + 12
      }
      
      if (datosOrganizacion.descripcion_cargos) {
        checkPageBreak(20)
        doc.setFontSize(10)
        doc.setFont('helvetica', 'bold')
        doc.text('Descripción de Cargos:', margin.left, y)
        y += 5
        doc.setFont('helvetica', 'normal')
        const cargoLines = doc.splitTextToSize(datosOrganizacion.descripcion_cargos, contentWidth)
        doc.text(cargoLines, margin.left, y)
        y += cargoLines.length * 5 + 8
      }
      
      if (datosOrganizacion.observaciones) {
        checkPageBreak(20)
        doc.setFontSize(10)
        doc.setFont('helvetica', 'bold')
        doc.text('Observaciones de Organización Social:', margin.left, y)
        y += 5
        doc.setFont('helvetica', 'normal')
        const obsLines = doc.splitTextToSize(datosOrganizacion.observaciones, contentWidth)
        doc.text(obsLines, margin.left, y)
        y += obsLines.length * 5 + 8
      }
    }
    
    /* =========================
       PÁGINA 4
    ========================= */
    
    // 11. SALUD Y BIENESTAR
    checkPageBreak(30)
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('7. SALUD Y BIENESTAR', margin.left, y)
    y += 8
    
    const saludData = []
    if (salud.afiliacion_salud) saludData.push(['Afiliación en Salud', salud.afiliacion_salud])
    if (salud.eps_ars) saludData.push(['EPS/ARS', salud.eps_ars])
    if (salud.enfermedades_cronicas) saludData.push(['Enfermedades Crónicas', salud.enfermedades_cronicas])
    if (salud.discapacidad) saludData.push(['Discapacidad', formatSiNo(salud.discapacidad)])
    if (salud.tipo_discapacidad) saludData.push(['Tipo de Discapacidad', salud.tipo_discapacidad])
    
    if (saludData.length > 0) {
      autoTable(doc, {
        startY: y,
        body: saludData,
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
            cellWidth: 60
          }
        },
        margin: { left: margin.left, right: margin.right }
      })
      y = doc.lastAutoTable.finalY + 12
    }
    
    // 12. OBSERVACIONES FINALES
    if (observaciones) {
      checkPageBreak(40)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('8. OBSERVACIONES FINALES', margin.left, y)
      y += 8
      
      doc.setFontSize(10)
      doc.setFont('helvetica', 'normal')
      const obsLines = doc.splitTextToSize(observaciones, contentWidth)
      doc.text(obsLines, margin.left, y)
      y += obsLines.length * 5 + 15
    }
    
    // 13. RECOMENDACIONES
    if (recomendaciones) {
      checkPageBreak(30)
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      doc.text('9. RECOMENDACIONES', margin.left, y)
      y += 8
      
      doc.setFontSize(10)
      doc.setFont('helvetica', 'normal')
      const recLines = doc.splitTextToSize(recomendaciones, contentWidth)
      doc.text(recLines, margin.left, y)
      y += recLines.length * 5 + 15
    }
    
    // 14. FIRMAS
    if (firmas && firmas.length > 0) {
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
        doc.text(firma.titulo || `Firma ${index + 1}`, x, y, { align: 'center' })
        
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
    
    // 15. IMÁGENES ADICIONALES (si existen en datosCierre)
    if (datosCierre?.imagenes && datosCierre.imagenes.length > 0) {
      doc.addPage()
      y = margin.top
      
      // Header en página de imágenes
      drawHeader()
      y = margin.top + 20
      
      doc.setFontSize(14)
      doc.setFont('helvetica', 'bold')
      doc.text('REGISTRO FOTOGRÁFICO DE LA VISITA SOCIAL', pageWidth / 2, y, { align: 'center' })
      y += 15
      
      const imgWidth = 85
      const imgHeight = 60
      const marginX = 12.5
      
      datosCierre.imagenes.forEach((img, index) => {
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
    
    /* =========================
       AGREGAR FOOTER EN TODAS LAS PÁGINAS
    ========================= */
    const totalPages = doc.internal.getNumberOfPages()
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i)
      drawFooter(i, totalPages)
    }
    
    // Guardar el PDF
    const fileName = `Informe_Social_${infoProveedor.replace(/\s+/g, '_')}_${infoFechaVisita.replace(/\//g, '-')}.pdf`
    doc.save(fileName)
    
    console.log('✅ PDF Social generado exitosamente con TODOS los datos')
    return true
    
  } catch (error) {
    console.error('❌ Error al generar el PDF Social:', error)
    throw error
  }
}