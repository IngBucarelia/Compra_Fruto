import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export async function generarResumenPDFSocial({ 
  datosPersonales,
  miembrosHogar, 
  datosPredio, 
  datosFuerzaLaboral, 
  datosOrganizacion, 
  datosCierre,
  visitaId,
  proveedorNombre
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

    // Función auxiliar para formatear fecha y hora
    const formatDateTime = (dateString) => {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return date.toLocaleString('es-ES');
      } catch (e) {
        return dateString;
      }
    };

    // --- ENCABEZADO
    doc.setFontSize(16);
    doc.setTextColor(40, 40, 40);
    doc.text('📋 RESUMEN DE VISITA SOCIAL', 10, y);
    y += 8;
    
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Proveedor: ${proveedorNombre || 'N/A'}`, 10, y);
    doc.text(`Visita ID: #${visitaId || 'N/A'}`, 100, y);
    doc.text(`Fecha: ${new Date().toLocaleDateString('es-ES')}`, 150, y);
    y += 15;

    // --- DATOS PERSONALES
    if (datosPersonales) {
      addTitulo('👤 DATOS PERSONALES DEL PRODUCTOR');
      saltarSiEsNecesario(80);

      const datosPersonalesBody = [
        ['Teléfono', datosPersonales.telefono || 'N/A'],
        ['Sexo', datosPersonales.sexo || 'N/A'],
        ['Fecha Nacimiento', formatDate(datosPersonales.fecha_nacimiento)],
        ['RNP', datosPersonales.rnp || 'N/A'],
        ['Fedepalma', datosPersonales.fedepalma || 'N/A'],
        ['Alfabetizado', datosPersonales.alfabetizado || 'N/A'],
        ['Nivel Estudio', datosPersonales.nivel_estudio || 'N/A'],
        ['Otras Líneas Negocio', datosPersonales.otras_lineas || 'N/A'],
        ['Grupo Poblacional', datosPersonales.grupo_poblacional || 'N/A'],
        ['Reside en Predio', datosPersonales.reside_predio || 'N/A'],
        ['Administra Cultivo', datosPersonales.administra_cultivo || 'N/A'],
        ['Supervisa Cultivo', datosPersonales.supervisa_cultivo || 'N/A'],
        ['Realiza Cultivo', datosPersonales.realiza_cultivo || 'N/A'],
        ['Años en Palmicultura', datosPersonales.anios_palmicultura || 'N/A'],
        ['Acceso a Internet', datosPersonales.internet || 'N/A'],
        ['Tipo Persona', datosPersonales.tipo_persona || 'N/A'],
        ['Red Social Principal', datosPersonales.red_social || 'N/A'],
        ['Régimen Salud', datosPersonales.regimen_salud || 'N/A']
      ];

      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: datosPersonalesBody,
        theme: 'grid',
        styles: { fontSize: 9, cellPadding: 2 },
        columnStyles: { 
          0: { fontStyle: 'bold', cellWidth: 70 },
          1: { cellWidth: 110 }
        }
      });
      y = doc.lastAutoTable.finalY + 10;
    }

    // --- MIEMBROS DEL HOGAR
    if (miembrosHogar && miembrosHogar.length > 0) {
      addTitulo(`👨‍👩‍👧‍👦 MIEMBROS DEL HOGAR (${miembrosHogar.length})`);
      saltarSiEsNecesario(30);

      const miembrosBody = miembrosHogar.map(miembro => [
        miembro.nombre || 'N/A',
        miembro.documento || 'N/A',
        miembro.sexo || 'N/A',
        miembro.parentezco || 'N/A',
        miembro.reside_predio ? 'Sí' : 'No',
        miembro.nivel_estudio || 'N/A',
        miembro.sabe_leer ? 'Sí' : 'No',
        miembro.participa_labores ? 'Sí' : 'No',
        miembro.edad || 'N/A'
      ]);

      autoTable(doc, {
        startY: y,
        head: [['Nombre', 'Documento', 'Sexo', 'Parentezco', 'Reside', 'Estudio', 'Alfabetizado', 'Participa', 'Edad']],
        body: miembrosBody,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 1 },
        headStyles: { fontSize: 7 },
        margin: { top: 10 }
      });
      y = doc.lastAutoTable.finalY + 10;

      // Observaciones de miembros
      const miembrosConObservaciones = miembrosHogar.filter(m => m.observaciones);
      if (miembrosConObservaciones.length > 0) {
        doc.setFontSize(10);
        doc.text('Observaciones de Miembros:', 10, y);
        y += 5;
        
        miembrosConObservaciones.forEach(miembro => {
          const textoObservacion = `${miembro.nombre}: ${miembro.observaciones}`;
          const lineas = doc.splitTextToSize(textoObservacion, 180);
          lineas.forEach(linea => {
            saltarSiEsNecesario(5);
            doc.setFontSize(8);
            doc.text(linea, 15, y);
            y += 4;
          });
          y += 2;
        });
        y += 5;
      }
    }

    // --- DATOS DEL PREDIO
    if (datosPredio) {
      addTitulo('🏡 DATOS DEL PREDIO');
      saltarSiEsNecesario(60);

      const datosPredioBody = [
        ['Nombre Finca', datosPredio.nombre_finca || 'N/A'],
        ['Forma Tenencia', datosPredio.forma_tenencia || 'N/A'],
        ['Municipio', datosPredio.municipio || 'N/A'],
        ['Vereda', datosPredio.vereda || 'N/A'],
        ['Registro ICA', datosPredio.registrado_ica || 'N/A'],
        ['Vive en Predio', datosPredio.vive_predio || 'N/A'],
        ['Infraestructura', datosPredio.infraestructura_predio || 'N/A'],
        ['Tipo Vivienda', datosPredio.tipo_vivienda || 'N/A'],
        ['Material Vivienda', datosPredio.material_vivienda || 'N/A']
      ];

      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: datosPredioBody,
        theme: 'grid',
        styles: { fontSize: 9, cellPadding: 2 },
        columnStyles: { 
          0: { fontStyle: 'bold', cellWidth: 60 },
          1: { cellWidth: 120 }
        }
      });
      y = doc.lastAutoTable.finalY + 5;

      // Infraestructura vial
      if (datosPredio.infraestructura_vial && datosPredio.infraestructura_vial.length > 0) {
        doc.setFontSize(10);
        doc.text('Vías de Acceso:', 10, y);
        y += 5;
        const viasTexto = Array.isArray(datosPredio.infraestructura_vial) 
          ? datosPredio.infraestructura_vial.join(', ')
          : datosPredio.infraestructura_vial;
        doc.setFontSize(9);
        doc.text(viasTexto, 15, y);
        y += 10;
      }

      // Servicios públicos
      if (datosPredio.servicios_publicos && datosPredio.servicios_publicos.length > 0) {
        doc.setFontSize(10);
        doc.text('Servicios Públicos:', 10, y);
        y += 5;
        const serviciosTexto = datosPredio.servicios_publicos.join(', ');
        doc.setFontSize(9);
        doc.text(serviciosTexto, 15, y);
        y += 10;
      }

      // Observaciones del predio
      if (datosPredio.observaciones) {
        doc.setFontSize(10);
        doc.text('Observaciones del Predio:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosPredio.observaciones, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }
    }

    // --- FUERZA LABORAL
    if (datosFuerzaLaboral) {
      addTitulo('🧑‍🌾 FUERZA LABORAL');
      saltarSiEsNecesario(50);

      const fuerzaLaboralBody = [
        ['Total Trabajadores', datosFuerzaLaboral.num_trabajadores || 'N/A'],
        ['Hombres', datosFuerzaLaboral.num_hombres || 'N/A'],
        ['Mujeres', datosFuerzaLaboral.num_mujeres || 'N/A'],
        ['Contrato Formal', datosFuerzaLaboral.contrato_formal || 'N/A'],
        ['Seguridad Social', datosFuerzaLaboral.seguridad_social || 'N/A'],
        ['Tipo Contrato', datosFuerzaLaboral.tipo_contrato || 'N/A'],
        ['Contrato Firmado', datosFuerzaLaboral.contrato_firmado || 'N/A'],
        ['SG-SST', datosFuerzaLaboral.sg_sst || 'N/A'],
        ['Exámenes Médicos', datosFuerzaLaboral.examenes_medicos || 'N/A'],
        ['Trabajadores Migrantes', datosFuerzaLaboral.trabajadores_migrantes || 'N/A'],
        ['Comprobantes Pago', datosFuerzaLaboral.comprobantes_pago || 'N/A'],
        ['Dotación', datosFuerzaLaboral.dotacion || 'N/A']
      ];

      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: fuerzaLaboralBody,
        theme: 'grid',
        styles: { fontSize: 9, cellPadding: 2 },
        columnStyles: { 
          0: { fontStyle: 'bold', cellWidth: 70 },
          1: { cellWidth: 110 }
        }
      });
      y = doc.lastAutoTable.finalY + 5;

      // Formas de contratación
      if (datosFuerzaLaboral.forma_contratacion && datosFuerzaLaboral.forma_contratacion.length > 0) {
        doc.setFontSize(10);
        doc.text('Formas de Contratación:', 10, y);
        y += 5;
        const formasTexto = Array.isArray(datosFuerzaLaboral.forma_contratacion)
          ? datosFuerzaLaboral.forma_contratacion.join(', ')
          : datosFuerzaLaboral.forma_contratacion;
        doc.setFontSize(9);
        doc.text(formasTexto, 15, y);
        y += 10;
      }

      // Observaciones de fuerza laboral
      if (datosFuerzaLaboral.observaciones) {
        doc.setFontSize(10);
        doc.text('Observaciones:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosFuerzaLaboral.observaciones, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }
    }

    // --- ORGANIZACIÓN SOCIAL
    if (datosOrganizacion) {
      addTitulo('👥 ORGANIZACIÓN SOCIAL');
      saltarSiEsNecesario(50);

      const organizacionBody = [
        ['Pertenece a JAC', datosOrganizacion.pertenece_jac || 'N/A'],
        ['Pertenece a Asociación', datosOrganizacion.pertenece_asociacion || 'N/A'],
        ['Nombre Asociación', datosOrganizacion.nombre_asociacion || 'N/A'],
        ['Participa Otras Organizaciones', datosOrganizacion.participa_otras_organizaciones || 'N/A'],
        ['Cargos Directivos', datosOrganizacion.cargos_directivos || 'N/A'],
        ['Frecuencia Participación', datosOrganizacion.frecuencia_participacion || 'N/A']
      ];

      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: organizacionBody,
        theme: 'grid',
        styles: { fontSize: 9, cellPadding: 2 },
        columnStyles: { 
          0: { fontStyle: 'bold', cellWidth: 80 },
          1: { cellWidth: 100 }
        }
      });
      y = doc.lastAutoTable.finalY + 5;

      // Tipo de organizaciones
      if (datosOrganizacion.tipo_organizaciones && datosOrganizacion.tipo_organizaciones.length > 0) {
        doc.setFontSize(10);
        doc.text('Tipo de Organizaciones:', 10, y);
        y += 5;
        const tiposTexto = datosOrganizacion.tipo_organizaciones.join(', ');
        doc.setFontSize(9);
        doc.text(tiposTexto, 15, y);
        y += 10;
      }

      // Beneficios
      if (datosOrganizacion.beneficios_participacion && datosOrganizacion.beneficios_participacion.length > 0) {
        doc.setFontSize(10);
        doc.text('Beneficios de Participación:', 10, y);
        y += 5;
        const beneficiosTexto = datosOrganizacion.beneficios_participacion.join(', ');
        doc.setFontSize(9);
        doc.text(beneficiosTexto, 15, y);
        y += 10;
      }

      // Descripción de cargos
      if (datosOrganizacion.descripcion_cargos) {
        doc.setFontSize(10);
        doc.text('Cargos Desempeñados:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosOrganizacion.descripcion_cargos, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }

      // Observaciones de organización
      if (datosOrganizacion.observaciones) {
        doc.setFontSize(10);
        doc.text('Observaciones:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosOrganizacion.observaciones, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }
    }

    // --- CIERRE DE VISITA
    if (datosCierre) {
      addTitulo('✅ CIERRE DE VISITA SOCIAL');
      saltarSiEsNecesario(40);

      const cierreBody = [
        ['Fecha Cierre', formatDate(datosCierre.fecha_cierre)],
        ['Estado Visita', datosCierre.estado_visita || 'N/A'],
        ['Finalizada En', formatDate(datosCierre.finalizada_en)],
        ['Guardado', formatDateTime(datosCierre.timestamp)],
        ['Imágenes', datosCierre.imagenes ? datosCierre.imagenes.length : 0]
      ];

      autoTable(doc, {
        startY: y,
        head: [['Campo', 'Valor']],
        body: cierreBody,
        theme: 'grid',
        styles: { fontSize: 9, cellPadding: 2 },
        columnStyles: { 
          0: { fontStyle: 'bold', cellWidth: 50 },
          1: { cellWidth: 130 }
        }
      });
      y = doc.lastAutoTable.finalY + 5;

      // Observaciones finales
      if (datosCierre.observaciones_finales) {
        doc.setFontSize(10);
        doc.text('Observaciones Finales:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosCierre.observaciones_finales, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }

      // Recomendaciones
      if (datosCierre.recomendaciones) {
        doc.setFontSize(10);
        doc.text('Recomendaciones:', 10, y);
        y += 5;
        const lineas = doc.splitTextToSize(datosCierre.recomendaciones, 180);
        lineas.forEach(linea => {
          saltarSiEsNecesario(5);
          doc.setFontSize(9);
          doc.text(linea, 15, y);
          y += 4;
        });
        y += 5;
      }
    }

    // --- FIRMAS
    const addFirma = (titulo, imgData) => {
      if (!imgData) return;
      saltarSiEsNecesario(40);
      doc.setFontSize(10);
      doc.text(titulo, 10, y);
      y += 2;
      
      try {
        // Detectar el tipo de imagen desde la cadena Base64
        let imgType = 'JPEG';
        if (imgData.startsWith('data:image/png')) imgType = 'PNG';
        else if (imgData.startsWith('data:image/jpeg')) imgType = 'JPEG';
        
        doc.addImage(imgData, imgType, 10, y + 2, 60, 30);
        y += 35;
      } catch (error) {
        console.error('Error al agregar firma:', error);
        doc.text('(Error al cargar firma)', 10, y);
        y += 10;
      }
    };

    if (datosCierre && (datosCierre.firma_responsable || datosCierre.firma_recibe || datosCierre.firma_testigo)) {
      addTitulo('✍️ FIRMAS REGISTRADAS');
      addFirma('Firma del Responsable', datosCierre.firma_responsable);
      addFirma('Firma de Quien Recibe', datosCierre.firma_recibe);
      addFirma('Firma del Testigo', datosCierre.firma_testigo);
    }

    // --- IMÁGENES
    if (datosCierre && datosCierre.imagenes && datosCierre.imagenes.length > 0) {
      doc.addPage();
      y = 10;
      doc.setFontSize(14);
      doc.text('📸 IMÁGENES DE LA VISITA SOCIAL', 10, y);
      y += 15;

      let x = 10;
      const ancho = 60, alto = 45;

      datosCierre.imagenes.forEach((img, idx) => {
        saltarSiEsNecesario(alto + 10);
        
        try {
          // Detectar el tipo de imagen
          let imgType = 'JPEG';
          if (img.startsWith('data:image/png')) imgType = 'PNG';
          else if (img.startsWith('data:image/jpeg')) imgType = 'JPEG';
          
          doc.addImage(img, imgType, x, y, ancho, alto);
          doc.setFontSize(8);
          doc.text(`Imagen ${idx + 1}`, x, y + alto + 5);
        } catch (error) {
          console.error('Error al agregar imagen:', error);
          doc.setFontSize(8);
          doc.text(`Error imagen ${idx + 1}`, x, y + alto / 2);
        }
        
        x += ancho + 5;
        if (x + ancho > 200) {
          x = 10;
          y += alto + 15;
          if (y + alto > espacioSeguro) {
            doc.addPage();
            y = 10;
            x = 10;
          }
        }
      });
    }

    // Guardar PDF
    const nombreArchivo = `visita_social_${visitaId || 'sin_id'}_${new Date().toISOString().split('T')[0]}.pdf`;
    doc.save(nombreArchivo);
    
  } catch (error) {
    console.error('Error al generar el PDF social:', error);
    alert('Hubo un error al generar el PDF de la visita social. Por favor, revisa la consola para más detalles.');
  }
}