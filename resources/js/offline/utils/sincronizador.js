// src/scripts/sincronizador.js
import { getAllDataFromStore, clearStore } from '../store/indexeddb' // Asegúrate de que la ruta sea correcta

// Definición de los endpoints para cada tipo de dato.
// Estos URLs deben coincidir con las rutas de tu API en Laravel para la sincronización.
const endpoints = {
    area: '/api/offline-sync/areas',
    fertilizacion: '/api/offline-sync/fertilizaciones',
    polinizacion: '/api/offline-sync/polinizaciones',
    sanidad: '/api/offline-sync/sanidades',
    suelo: '/api/offline-sync/suelos',
    labores_cultivo: '/api/offline-sync/labores',
    cierre_visitas: '/api/offline-sync/cierre-visitas', // Asegúrate de que este endpoint sea correcto
    evaluacion_cosecha: '/api/offline-sync/evaluacion',
    estado: '/visitas/update-status',
}

/**
 * Función auxiliar para leer un objeto File/Blob como una cadena Base64.
 * @param {File|Blob} file El objeto File o Blob a convertir.
 * @returns {Promise<string>} Una promesa que se resuelve con la cadena Base64.
 */
function readFileAsBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
        reader.readAsDataURL(file);
    });
}

/**
 * Sincroniza los datos almacenados offline en IndexedDB con el servidor.
 * Itera sobre cada tipo de store, envía los registros al backend y los limpia si la sincronización es exitosa.
 * @returns {Promise<void>} Una promesa que se resuelve cuando la sincronización ha finalizado.
 */
export async function sincronizarDatosOffline() {
    let totalSincronizados = 0; // Contador de registros sincronizados exitosamente
    let errores = []; // Array para almacenar los errores encontrados durante la sincronización

    // Itera sobre cada store definido en los 'endpoints'
    for (const [storeName, url] of Object.entries(endpoints)) {
        console.log(`Iniciando sincronización para: ${storeName}`);
        const registros = await getAllDataFromStore(storeName);

        if (registros.length === 0) {
            console.log(`No hay registros para sincronizar en ${storeName}.`);
            continue;
        }

        // En la función sincronizarDatosOffline, modificar la parte de áreas:
        if (storeName === 'area') {
        try {
            // Prepara los datos en el formato que espera el backend
            const registrosParaEnviar = registros.map(registro => {
            return {
                formName: 'area',
                formData: {
                ...registro,
                // Asegurar que los campos condicionales sean null si aplica_orden_plantis es false
                orden_plantis_numero: registro.aplica_orden_plantis ? registro.orden_plantis_numero : null,
                estado_oren_plantis: registro.aplica_orden_plantis ? registro.estado_oren_plantis : null,
                numero_plantas_orden_plantis: registro.aplica_orden_plantis ? registro.numero_plantas_orden_plantis : null
                },
                local_id: registro.local_id || registro.id // Usar local_id si existe, sino el id de IndexedDB
            };
            });

            console.log(`Enviando áreas:`, registrosParaEnviar);
            const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ submissions: registrosParaEnviar })
            });

            if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`Error al sincronizar áreas: ${errorData.message || 'Error desconocido'}`);
            }

            const responseData = await response.json();
            console.log('Respuesta del servidor:', responseData);
            
            // Verificar si todos los envíos fueron exitosos
            const allSuccess = responseData.results.every(r => r.success);
            if (allSuccess) {
            await clearStore(storeName);
            console.log('Áreas sincronizadas y store limpiado');
            } else {
            console.warn('Algunas áreas no se sincronizaron correctamente', responseData.results);
            }

            totalSincronizados += registrosParaEnviar.length;
        } catch (error) {
            console.error('Error sincronizando áreas:', error);
            errores.push({ storeName, error: error.message });
        }
        continue;
        }

        // En la función sincronizarDatosOffline, agregar este caso para labores_cultivo:
        if (storeName === 'labores_cultivo') {
        try {
            // Prepara los datos en el formato que espera el backend
            const registrosParaEnviar = registros.map(registro => {
            const labor = {
                ...registro,
                // Renombrar local_id a indexeddb_id para coincidir con el backend
                indexeddb_id: registro.local_id || registro.id,
                // Asegurar que los campos numéricos sean números
                polinizacion: registro.polinizacion ? parseInt(registro.polinizacion) : null,
                limpieza_calle: registro.limpieza_calle ? parseInt(registro.limpieza_calle) : null,
                limpieza_plato: registro.limpieza_plato ? parseInt(registro.limpieza_plato) : null,
                poda: registro.poda ? parseInt(registro.poda) : null,
                fertilizacion: registro.fertilizacion ? parseInt(registro.fertilizacion) : null,
                enmiendas: registro.enmiendas ? parseInt(registro.enmiendas) : null,
                ubicacion_tusa_fibra: registro.ubicacion_tusa_fibra ? parseInt(registro.ubicacion_tusa_fibra) : null,
                ubicacion_hoja: registro.ubicacion_hoja ? parseInt(registro.ubicacion_hoja) : null,
                lugar_ubicacion_hoja: registro.lugar_ubicacion_hoja ? parseInt(registro.lugar_ubicacion_hoja) : null,
                plantas_nectariferas: registro.plantas_nectariferas ? parseInt(registro.plantas_nectariferas) : null,
                cobertura: registro.cobertura ? parseInt(registro.cobertura) : null,
                labor_cosecha: registro.labor_cosecha ? parseInt(registro.labor_cosecha) : null,
                calidad_fruta: registro.calidad_fruta ? parseInt(registro.calidad_fruta) : null,
                recoleccion_fruta: registro.recoleccion_fruta ? parseInt(registro.recoleccion_fruta) : null,
                drenajes: registro.drenajes ? parseInt(registro.drenajes) : null
            };
            
            // Eliminar local_id si existe para evitar duplicados
            delete labor.local_id;
            return labor;
            });

            console.log(`Enviando labores de cultivo:`, registrosParaEnviar);
            const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(registrosParaEnviar) // Enviar array directo
            });

            if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`Error al sincronizar labores: ${errorData.message || 'Error desconocido'}`);
            }

            const responseData = await response.json();
            console.log('Respuesta del servidor:', responseData);
            
            if (responseData.sincronizados === registrosParaEnviar.length) {
            await clearStore(storeName);
            console.log('Labores sincronizadas y store limpiado');
            } else {
            console.warn('Algunas labores no se sincronizaron correctamente', responseData.errores_validacion);
            }

            totalSincronizados += responseData.sincronizados || 0;
        } catch (error) {
            console.error('Error sincronizando labores:', error);
            errores.push({ storeName, error: error.message });
        }
        continue;
        }

        // En la función sincronizarDatosOffline, agregar este caso para evaluacion_cosecha:
            if (storeName === 'evaluacion_cosecha') {
            try {
                // Prepara los datos en el formato que espera el backend
                const registrosParaEnviar = registros.map(registro => {
                const evaluacion = {
                    ...registro,
                    // Renombrar local_id a indexeddb_id para coincidir con el backend
                    indexeddb_id: registro.local_id || registro.id,
                    // Asegurar que conformacion sea null si no es híbrido
                    conformacion: registro.variedad_fruto === 'hibrido' ? registro.conformacion : null,
                    // Convertir campos numéricos
                    cantidad_racimos: parseInt(registro.cantidad_racimos) || 0,
                    verde: parseInt(registro.verde) || null,
                    maduro: parseInt(registro.maduro) || null,
                    sobremaduro: parseInt(registro.sobremaduro) || null,
                    pedunculo: parseInt(registro.pedunculo) || null
                };
                
                // Eliminar campos no necesarios
                delete evaluacion.local_id;
                delete evaluacion.id;
                
                return evaluacion;
                });

                console.log(`Enviando evaluaciones de cosecha:`, registrosParaEnviar);
                const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(registrosParaEnviar)
                });

                if (!response.ok) {
                const errorData = await response.json();
                throw new Error(`Error al sincronizar evaluaciones: ${errorData.message || 'Error desconocido'}`);
                }

                const responseData = await response.json();
                console.log('Respuesta del servidor:', responseData);
                
                if (responseData.sincronizados === registrosParaEnviar.length) {
                await clearStore(storeName);
                console.log('Evaluaciones sincronizadas y store limpiado');
                } else {
                console.warn('Algunas evaluaciones no se sincronizaron correctamente', responseData.errores);
                }

                totalSincronizados += responseData.sincronizados || 0;
            } catch (error) {
                console.error('Error sincronizando evaluaciones:', error);
                errores.push({ storeName, error: error.message });
            }
            continue;
            }

        // --- Lógica general para otros stores (procesamiento de un registro a la vez) ---
        let storeErrores = []; // Array para almacenar errores específicos de este store (para decidir si limpiar o no)
        for (const registroOriginal of registros) {
            let registroParaEnviar = { ...registroOriginal }; // ✅ Clonar el registro para no modificar el objeto original de IndexedDB

            // ✅ NUEVA LÓGICA: Asegurar que 'local_id' esté presente para 'sanidad'
            // Esto es crucial si tu backend de Laravel espera esta clave para identificar los registros.
            if (storeName === 'sanidad' && !registroParaEnviar.local_id) {
                // Asume que 'registroOriginal' tiene una propiedad 'id' que es la clave de IndexedDB.
                // Si tu clave de IndexedDB es diferente (ej. 'uuid', 'key'), ajusta 'registroOriginal.id' aquí.
                registroParaEnviar.local_id = registroOriginal.id || crypto.randomUUID(); // Fallback a UUID si no hay ID
                console.log(`[${storeName}] Añadiendo local_id: ${registroParaEnviar.local_id} al registro.`);
            }

            try {
                // ✅ Lógica de conversión a Base64 para el store 'cierre_visitas'
                if (storeName === 'cierre_visitas') {
                    console.log(`Procesando firmas e imágenes para ${storeName}...`);

                    // Convertir firma_responsable si es un objeto File/Blob
                    if (registroParaEnviar.firma_responsable instanceof File || registroParaEnviar.firma_responsable instanceof Blob) {
                        registroParaEnviar.firma_responsable = await readFileAsBase64(registroParaEnviar.firma_responsable);
                    }
                    // Convertir firma_recibe si es un objeto File/Blob
                    if (registroParaEnviar.firma_recibe instanceof File || registroParaEnviar.firma_recibe instanceof Blob) {
                        registroParaEnviar.firma_recibe = await readFileAsBase64(registroParaEnviar.firma_recibe);
                    }
                    // Convertir firma_testigo si es un objeto File/Blob
                    if (registroParaEnviar.firma_testigo instanceof File || registroParaEnviar.firma_testigo instanceof Blob) {
                        registroParaEnviar.firma_testigo = await readFileAsBase64(registroParaEnviar.firma_testigo);
                    }

                    // Convertir imágenes si es un array de File/Blob
                    if (Array.isArray(registroParaEnviar.imagenes)) {
                        const convertedImages = [];
                        for (const img of registroParaEnviar.imagenes) {
                            if (img instanceof File || img instanceof Blob) {
                                convertedImages.push(await readFileAsBase64(img));
                            } else if (typeof img === 'string' && img.startsWith('data:image')) {
                                // Si ya es una cadena Base64, simplemente la mantenemos
                                convertedImages.push(img);
                            } else {
                                console.warn(`Imagen inesperada en ${storeName}:`, img);
                                // Si no es File/Blob ni Base64, podrías decidir qué hacer (ej. omitirla o lanzar error)
                            }
                        }
                        registroParaEnviar.imagenes = convertedImages;
                    }
                }

                console.log(`Intentando sincronizar registro de ${storeName}:`, registroParaEnviar);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Si necesitas un token de autorización (ej. JWT), descomenta y configura la siguiente línea:
                        // 'Authorization': `Bearer ${localStorage.getItem('yourAuthToken')}`
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(registroParaEnviar) // Envía el registro modificado
                });

                if (!response.ok) {
                    let errorData = {};
                    await clearStore(storeName);

                    try {
                        errorData = await response.json();
                    } catch (jsonError) {
                        errorData.message = response.statusText;
                    }
                    throw new Error(`Error al sincronizar ${storeName} (HTTP ${response.status}): ${errorData.message || 'Error desconocido'}. Detalles: ${JSON.stringify(errorData)}`);
                }

                console.log(`✅ ${storeName}: registro sincronizado exitosamente.`, registroOriginal);
                totalSincronizados++; // Incrementa el contador de registros sincronizados
            } catch (error) {
                console.error(`❌ Error al sincronizar un registro de ${storeName}:`, error);
                storeErrores.push({ storeName, error: error.message, registro: registroOriginal }); // Agrega al array de errores del store actual
                errores.push({ storeName, error: error.message, registro: registroOriginal }); // Agrega al array global de errores
            }
        }

        // Limpia el store solo si no hubo errores para este store específico
        if (storeErrores.length === 0) {
            await clearStore(storeName);
            console.log(`🗑️ ${storeName}: store limpiado después de una sincronización exitosa.`);
        } else {
            console.warn(`⚠️ ${storeName}: no se limpió el store debido a errores de sincronización.`);
        }
    }

    // Mensaje final de la sincronización global
    if (errores.length === 0) {
        alert(`✅ ¡Sincronización completa! Todos los registros (${totalSincronizados}) se sincronizaron con éxito.`);
    } else {
        alert(`⚠️ Sincronización finalizada con algunos errores. Registros sincronizados: ${totalSincronizados}. Por favor, revisa la consola del navegador para ver los detalles de los errores.`);
        console.error('Resumen de errores de sincronización:', errores);
    }
}
