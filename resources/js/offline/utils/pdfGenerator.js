import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export async function generarResumenPDF({ 
  areas, // Cambiado de 'area' a 'areas'
  fertilizaciones, 
  polinizaciones, 
  sanidad, 
  suelo, 
  laboresCultivo, // Cambiado de 'labores' a 'laboresCultivo'
  evaluacionesCosecha, // Cambiado de 'evaluacion' a 'evaluacionesCosecha'
  cierreVisita // Ahora se pasa todo el objeto cierreVisita
}) {
  try {
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

    // Función auxiliar para capitalizar la primera letra
    const ucfirst = (str) => {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1);
    };

    // Función auxiliar para formatear fechas
    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return date.toLocaleDateString('es-ES', options);
      } catch (e) {
        return dateString;
      }
    };

    // Función auxiliar para formatear porcentajes
    const formatPercentage = (value) => {
      return (value !== null && value !== undefined) ? `${value}%` : 'N/A';
    };

    // Función auxiliar para formatear "si"/"no"
    const formatSiNo = (value) => {
      if (value === 'si') return 'Sí';
      if (value === 'no') return 'No';
      return 'N/A';
    };

    // --- ÁREAS
    if (areas && areas.length > 0) {
      addTitulo('📍 Información de Áreas')
      areas.forEach((area, index) => {
        saltarSiEsNecesario(80); // Estimar espacio para cada área
        doc.setFontSize(12);
        doc.text(`Área #${index + 1}`, 10, y);
        y += 5;

        autoTable(doc, {
          startY: y,
          head: [['Campo', 'Valor']],
          body: [
            ['Material', area.material || 'N/A'],
            ['Estado', area.estado || 'N/A'],
            ['Año Siembra', formatDate(area.anio_siembra)],
            ['Área (m²)', area.area || 'N/A'],
            ['Área Total Finca (Ha)', area.area_total_finca_hectareas || 'N/A'],
            ['N° Palmas Total Finca', area.numero_palmas_total_finca || 'N/A'],
            ['Área Palmas Desarrollo (Ha)', area.area_palmas_desarrollo_hectareas || 'N/A'],
            ['N° Palmas Desarrollo', area.numero_palmas_desarrollo || 'N/A'],
            ['Área Palmas Producción (Ha)', area.area_palmas_produccion_hectareas || 'N/A'],
            ['N° Palmas Producción', area.numero_palmas_produccion || 'N/A'],
            ['Ciclos de Cosecha', area.ciclos_cosecha || 'N/A'],
            ['Producción (Toneladas/Mes)', area.produccion_toneladas_por_mes || 'N/A'],
            ['Aplica Orden Plantis', formatSiNo(area.aplica_orden_plantis ? 'si' : 'no')],
            ...(area.aplica_orden_plantis ? [
              ['Orden Plantis N°', area.orden_plantis_numero || 'N/A'],
              ['Estado Orden Plantis', area.estado_oren_plantis || 'N/A'],
              ['N° Plantas Orden Plantis', area.numero_plantas_orden_plantis || 'N/A']
            ] : [])
          ],
          theme: 'grid',
          styles: { fontSize: 10, cellPadding: 2 },
          columnStyles: { 0: { fontStyle: 'bold' } }
        });
        y = doc.lastAutoTable.finalY + 10;
      });
    }


    // --- FERTILIZACIONES
    if (fertilizaciones && fertilizaciones.length > 0) {
      addTitulo('💧 Fertilizaciones')
      fertilizaciones.forEach((f) => {
        saltarSiEsNecesario(30); // Estimar espacio para cada fertilización
        doc.setFontSize(12);
        doc.text(`Fecha: ${formatDate(f.fecha_fertilizacion)}`, 10, y);
        y += 5;

        autoTable(doc, {
          startY: y,
          head: [['Fertilizante', 'Cantidad', 'Unidad', 'Fecha Aplicación']],
          body: f.fertilizantes.map(item => [
            item.nombre || 'N/A',
            item.cantidad || 'N/A',
            item.unidad_medida || 'N/A',
            formatDate(item.fecha_aplicacion)
          ]),
          theme: 'grid',
          styles: { fontSize: 10, cellPadding: 2 }
        });
        y = doc.lastAutoTable.finalY + 10;
      });
    }

    // --- POLINIZACIONES
    if (polinizaciones && polinizaciones.length > 0) {
      addTitulo('🌸 Polinizaciones')
      polinizaciones.forEach((p, index) => {
        saltarSiEsNecesario(50); // Estimar espacio
        doc.setFontSize(12);
        doc.text(`Polinización #${index + 1}`, 10, y);
        y += 5;

        autoTable(doc, {
          startY: y,
          head: [['Campo', 'Valor']],
          body: [
            ['Fecha', formatDate(p.fecha)],
            ['N° Pases', p.n_pases || 'N/A'],
            ['Ciclos', p.ciclos_ronda || 'N/A'],
            ['ANA', `${p.ana || 'N/A'} (${p.tipo_ana || 'N/A'})`],
            ['Talco', `${p.talco || 'N/A'} kg`],
          ],
          theme: 'grid',
          styles: { fontSize: 10, cellPadding: 2 },
          columnStyles: { 0: { fontStyle: 'bold' } }
        });
        y = doc.lastAutoTable.finalY + 10;
      });
    }

    // --- SANIDAD
    if (sanidad) {
      addTitulo('🦠 Sanidad')
      saltarSiEsNecesario(80); // Estimar espacio
      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: [
          ['Opsophanes', formatPercentage(sanidad.opsophanes)],
          ['Pudrición Cogollo', formatPercentage(sanidad.pudricion_cogollo)],
          ['Raspador', formatPercentage(sanidad.raspador)],
          ['Palmarum', formatPercentage(sanidad.palmarum)],
          ['Strategus', formatPercentage(sanidad.strategus)],
          ['Leptopharsa', formatPercentage(sanidad.leptopharsa)],
          ['Pestalotiopsis', formatPercentage(sanidad.pestalotiopsis)],
          ['Pudrición Basal', formatPercentage(sanidad.pudricion_basal)],
          ['Pudrición Estípite', formatPercentage(sanidad.pudricion_estipe)],
          ['Otros', sanidad.otros || 'N/A'],
        ],
        theme: 'grid',
        styles: { fontSize: 10, cellPadding: 2 },
        columnStyles: { 0: { fontStyle: 'bold' } }
      });
      y = doc.lastAutoTable.finalY + 5;
      if (sanidad.observaciones) {
        doc.setFontSize(10);
        doc.text('Observaciones:', 10, y);
        y += 5;
        doc.text(doc.splitTextToSize(sanidad.observaciones, 180), 10, y);
        y += 10;
      }
    }

    // --- SUELO
    if (suelo) {
      addTitulo('🧪 Análisis de Suelo')
      saltarSiEsNecesario(40); // Estimar espacio
      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: [
          ['Análisis Foliar', formatSiNo(suelo.analisis_foliar)],
          ['Análisis Suelo', formatSiNo(suelo.alanalisis_suelo)],
          ['Tipo Suelo', suelo.tipo_suelo || 'N/A']
        ],
        theme: 'grid',
        styles: { fontSize: 10, cellPadding: 2 },
        columnStyles: { 0: { fontStyle: 'bold' } }
      });
      y = doc.lastAutoTable.finalY + 10;
    }

    // --- LABORES DE CULTIVO
    if (laboresCultivo && laboresCultivo.length > 0) {
      addTitulo('🚜 Labores de Cultivo')
      laboresCultivo.forEach((labor, index) => {
        saltarSiEsNecesario(100); // Estimar espacio para cada labor
        doc.setFontSize(12);
        doc.text(`Labor #${index + 1} (${formatDate(labor.created_at)})`, 10, y);
        y += 5;

        const laborData = [
          ['Tipo Planta', ucfirst(labor.tipo_planta) || 'N/A'],
          ['Polinización', formatPercentage(labor.polinizacion)],
          ['Limpieza Calle', formatPercentage(labor.limpieza_calle)],
          ['Limpieza Plato', formatPercentage(labor.limpieza_plato)],
          ['Poda', formatPercentage(labor.poda)],
          ['Fertilización', formatPercentage(labor.fertilizacion)],
          ['Enmiendas', formatPercentage(labor.enmiendas)],
          ['Ubicación Tusa/Fibra', formatPercentage(labor.ubicacion_tusa_fibra)],
          ['Hoja en Barrera', formatPercentage(labor.ubicacion_hoja)],
          ['Hoja en Plato', formatPercentage(labor.lugar_ubicacion_hoja)],
          ['Plantas Nectaríferas', formatPercentage(labor.plantas_nectariferas)],
          ['Cobertura', formatPercentage(labor.cobertura)],
          ['Labor Cosecha', formatPercentage(labor.labor_cosecha)],
          ['Calidad Fruta', formatPercentage(labor.calidad_fruta)],
          ['Recolección Fruta', formatPercentage(labor.recoleccion_fruta)],
          ['Drenajes', formatPercentage(labor.drenajes)],
        ];

        autoTable(doc, {
          startY: y,
          head: [['Campo', 'Valor']],
          body: laborData,
          theme: 'grid',
          styles: { fontSize: 10, cellPadding: 2 },
          columnStyles: { 0: { fontStyle: 'bold' } }
        });
        y = doc.lastAutoTable.finalY + 5;
        if (labor.observaciones) {
          doc.setFontSize(10);
          doc.text('Observaciones:', 10, y);
          y += 5;
          doc.text(doc.splitTextToSize(labor.observaciones, 180), 10, y);
          y += 10;
        }
      });
    }

    // --- EVALUACIÓN DE COSECHA
    if (evaluacionesCosecha && evaluacionesCosecha.length > 0) {
      addTitulo('🌴 Evaluación de Cosecha')
      evaluacionesCosecha.forEach((evaluacion, index) => {
        saltarSiEsNecesario(60); // Estimar espacio
        doc.setFontSize(12);
        doc.text(`Evaluación #${index + 1}`, 10, y);
        y += 5;

        autoTable(doc, {
          startY: y,
          head: [['Campo', 'Valor']],
          body: [
            ['Variedad Fruto', ucfirst(evaluacion.variedad_fruto) || 'N/A'],
            ['Cantidad Racimos', evaluacion.cantidad_racimos || 'N/A'],
            ['Verde', formatPercentage(evaluacion.verde)],
            ['Maduro', formatPercentage(evaluacion.maduro)],
            ['Sobremaduro', formatPercentage(evaluacion.sobremaduro)],
            ['Pedúnculo', formatPercentage(evaluacion.pedunculo)],
            ['Conformación', evaluacion.conformacion || 'N/A'],
            ['Observaciones', evaluacion.observaciones || 'N/A']
          ],
          theme: 'grid',
          styles: { fontSize: 10, cellPadding: 2 },
          columnStyles: { 0: { fontStyle: 'bold' } }
        });
        y = doc.lastAutoTable.finalY + 10;
      });
    }

    // --- CIERRE DE VISITA
    if (cierreVisita && cierreVisita.fecha_cierre) {
      addTitulo('✅ Cierre de Visita');
      saltarSiEsNecesario(40); // Estimar espacio
      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: [
          ['Fecha de Cierre', formatDate(cierreVisita.fecha_cierre)],
          ['Estado', cierreVisita.estado_visita || 'N/A'],
          ['Observaciones', cierreVisita.observaciones || 'Ninguna'],
          ['Recomendaciones', cierreVisita.recomendaciones || 'Ninguna']
        ],
        theme: 'grid',
        styles: { fontSize: 10, cellPadding: 2 },
        columnStyles: { 0: { fontStyle: 'bold' } }
      });
      y = doc.lastAutoTable.finalY + 10;
    }

    // --- FIRMAS
    const addFirma = (titulo, imgData) => {
      if (!imgData) return;
      saltarSiEsNecesario(30);
      doc.setFontSize(12);
      doc.text(titulo, 10, y);
      y += 2;
      // Asegúrate de que imgData sea una cadena Base64 válida
      doc.addImage(imgData, 'PNG', 10, y + 2, 60, 30); // Asumiendo PNG para firmas
      y += 35;
    };

    if (cierreVisita && (cierreVisita.firma_responsable || cierreVisita.firma_recibe || cierreVisita.firma_testigo)) {
      addTitulo('🖋️ Firmas de la Visita');
      addFirma('Firma quien realiza', cierreVisita.firma_responsable); // Acceder desde cierreVisita
      addFirma('Firma quien recibe', cierreVisita.firma_recibe);     // Acceder desde cierreVisita
      addFirma('Firma testigo', cierreVisita.firma_testigo);         // Acceder desde cierreVisita
    }

    // --- IMÁGENES
    if (cierreVisita && cierreVisita.imagenes && cierreVisita.imagenes.length > 0) {
      doc.addPage();
      y = 10;
      doc.setFontSize(14);
      doc.text('📸 Imágenes de la Visita', 10, y);
      y += 10;

      let x = 10;
      const ancho = 60, alto = 45;

      cierreVisita.imagenes.forEach((img, idx) => {
        saltarSiEsNecesario(alto + 5); // Asegurarse de que hay espacio para la imagen
        // Detectar el tipo de imagen desde la cadena Base64 si es posible, o usar un tipo común
        const imgType = img.startsWith('data:image/png') ? 'PNG' : 'JPEG'; 
        doc.addImage(img, imgType, x, y, ancho, alto);
        x += ancho + 5;
        if (x + ancho > 200) { // Si la siguiente imagen excede el ancho de la página
          x = 10;
          y += alto + 5;
          if (y + alto > espacioSeguro) { // Si la siguiente fila excede el alto de la página
            doc.addPage();
            y = 10;
          }
        }
      });
    }

    doc.save('resumen_visita.pdf')
  } catch (error) {
    console.error('Error al generar el PDF:', error)
    alert('Hubo un error al generar el PDF. Por favor, revisa la consola para más detalles.')
  }
}
