// src/scripts/sincronizadorSocial.js
import { getAllDataFromStore, clearStore, deleteDataFromStore } from '../store/indexeddb';

// Definición de los endpoints para datos sociales
const endpoints = {
    datos_personales_social: '/api/offline-sync/datos-personales-social',
    miembros_hogar_social: '/api/offline-sync/miembros-hogar-social',
    datos_predio_social: '/api/offline-sync/datos-predio-social',
    fuerza_laboral_social: '/api/offline-sync/fuerza-laboral-social',
    organizacion_social: '/api/offline-sync/organizacion-social',
    cierre_visita_social: '/api/offline-sync/cierre-visita-social',
}

/**
 * Función auxiliar para leer un objeto File/Blob como una cadena Base64.
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
 * Obtener el token CSRF de forma segura
 */
function getCSRFToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.content : '';
}

/**
 * Función mejorada para hacer fetch con manejo de errores
 */
async function safeFetch(url, options = {}) {
    const csrfToken = getCSRFToken();
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    };

    // Solo agregar CSRF token si existe
    if (csrfToken) {
        defaultOptions.headers['X-CSRF-TOKEN'] = csrfToken;
    }

    const finalOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    };

    try {
        const response = await fetch(url, finalOptions);
        
        if (!response.ok) {
            if (response.status === 404) {
                throw new Error(`La ruta ${url} no fue encontrada en el servidor (404)`);
            }
            
            let errorData = {};
            try {
                errorData = await response.json();
            } catch (jsonError) {
                errorData = { message: response.statusText };
            }
            throw new Error(`HTTP ${response.status}: ${errorData.message || 'Error desconocido'}`);
        }
        
        return await response.json();
    } catch (error) {
        if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
            throw new Error(`Error de conexión: No se pudo conectar con el servidor`);
        }
        throw error;
    }
}

/**
 * Función para mapear campos visita_id -> visita_social_id
 */
function mapearCamposVisita(registro) {
    const registroMapeado = { ...registro };
    
    // Si existe visita_id, mapearlo a visita_social_id
    if (registroMapeado.visita_id !== undefined) {
        registroMapeado.visita_social_id = registroMapeado.visita_id;
        // No eliminar visita_id para mantener compatibilidad
    }
    
    return registroMapeado;
}

/**
 * Sincroniza los datos sociales almacenados offline en IndexedDB con el servidor.
 */
export async function sincronizarDatosSocialesOffline(visitaId, callbackProgreso = null) {
    let totalSincronizados = 0;
    let errores = [];

    console.log(`Iniciando sincronización de datos sociales para visita: ${visitaId}`);

    const storesSociales = [
        'datos_personales_social',
        'miembros_hogar_social', 
        'datos_predio_social',
        'fuerza_laboral_social',
        'organizacion_social',
        'cierre_visita_social'
    ];

    const totalStores = storesSociales.length;
    
    for (let i = 0; i < totalStores; i++) {
        const storeName = storesSociales[i];
        
        if (callbackProgreso) {
            const porcentaje = Math.round((i / totalStores) * 100);
            const mensajes = {
                'datos_personales_social': 'Sincronizando datos personales...',
                'miembros_hogar_social': 'Enviando información del hogar...',
                'datos_predio_social': 'Procesando datos del predio...',
                'fuerza_laboral_social': 'Sincronizando fuerza laboral...',
                'organizacion_social': 'Enviando organización social...',
                'cierre_visita_social': 'Finalizando con cierre de visita...'
            };
            
            callbackProgreso({
                porcentaje: porcentaje,
                mensaje: mensajes[storeName] || `Sincronizando ${storeName}...`,
                storeActual: storeName
            });
        }

        console.log(`Iniciando sincronización para: ${storeName}`);
        
        const todosRegistros = await getAllDataFromStore(storeName);
        const registros = todosRegistros.filter(registro => {
            const registroVisitaId = Number(registro.visita_id) || registro.visita_id;
            return registroVisitaId == visitaId;
        });

        if (registros.length === 0) {
            console.log(`No hay registros para sincronizar en ${storeName} para visita ${visitaId}.`);
            continue;
        }

        let storeErrores = [];

        try {
            // Lógica específica para cada store
            if (storeName === 'datos_personales_social') {
                const registrosParaEnviar = registros.map(registro => {
                    const registroMapeado = mapearCamposVisita(registro);
                    
                    return {
                        ...registroMapeado,
                        fecha_nacimiento: registroMapeado.fecha_nacimiento instanceof Date ? 
                            registroMapeado.fecha_nacimiento.toISOString().split('T')[0] : 
                            registroMapeado.fecha_nacimiento,
                        alfabetizado: Boolean(registroMapeado.alfabetizado),
                        reside_predio: Boolean(registroMapeado.reside_predio),
                        administra_cultivo: Boolean(registroMapeado.administra_cultivo),
                        local_id: registroMapeado.local_id || registroMapeado.id
                    };
                });

                console.log(`Enviando datos personales:`, registrosParaEnviar);
                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                console.log('Respuesta del servidor:', responseData);
                
                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message || 'Error en la respuesta del servidor');
                }

            } else if (storeName === 'miembros_hogar_social') {
                const registrosParaEnviar = registros.map(registro => {
                    const registroMapeado = mapearCamposVisita(registro);
                    
                    return {
                        ...registroMapeado,
                        reside_predio: Boolean(registroMapeado.reside_predio),
                        participa_labores: Boolean(registroMapeado.participa_labores),
                        local_id: registroMapeado.local_id || registroMapeado.id
                    };
                });

                console.log(`Enviando miembros del hogar:`, registrosParaEnviar);
                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify(registrosParaEnviar)
                });

                console.log('Respuesta del servidor:', responseData);
                
                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message || 'Error en la respuesta del servidor');
                }

            } else if (storeName === 'cierre_visita_social') {
                let registrosParaEnviar = [];
                
                for (const registro of registros) {
                    let registroProcesado = mapearCamposVisita(registro);
                    
                    // Procesar firmas
                    if (registroProcesado.firma_responsable instanceof File || registroProcesado.firma_responsable instanceof Blob) {
                        registroProcesado.firma_responsable = await readFileAsBase64(registroProcesado.firma_responsable);
                    }
                    if (registroProcesado.firma_recibe instanceof File || registroProcesado.firma_recibe instanceof Blob) {
                        registroProcesado.firma_recibe = await readFileAsBase64(registroProcesado.firma_recibe);
                    }
                    if (registroProcesado.firma_testigo instanceof File || registroProcesado.firma_testigo instanceof Blob) {
                        registroProcesado.firma_testigo = await readFileAsBase64(registroProcesado.firma_testigo);
                    }

                    // Procesar imágenes
                    if (Array.isArray(registroProcesado.imagenes)) {
                        const imagenesProcesadas = [];
                        for (const img of registroProcesado.imagenes) {
                            if (img instanceof File || img instanceof Blob) {
                                imagenesProcesadas.push(await readFileAsBase64(img));
                            } else if (typeof img === 'string' && img.startsWith('data:image')) {
                                imagenesProcesadas.push(img);
                            }
                        }
                        registroProcesado.imagenes = imagenesProcesadas;
                    }
                    
                    registrosParaEnviar.push({
                        ...registroProcesado,
                        local_id: registroProcesado.local_id || registroProcesado.id
                    });
                }

                console.log(`Enviando cierre de visita social:`, registrosParaEnviar);
                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify(registrosParaEnviar)
                });

                console.log('Respuesta del servidor:', responseData);
                
                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message || 'Error en la respuesta del servidor');
                }

            } else {
                // Lógica para stores individuales (un registro a la vez)
                for (const registroOriginal of registros) {
                    let registroParaEnviar = mapearCamposVisita(registroOriginal);

                    // Procesamiento específico
                    if (storeName === 'datos_predio_social') {
                        if (registroParaEnviar.servicios_publicos && !Array.isArray(registroParaEnviar.servicios_publicos)) {
                            registroParaEnviar.servicios_publicos = [registroParaEnviar.servicios_publicos];
                        }
                        if (registroParaEnviar.infraestructura_vial && !Array.isArray(registroParaEnviar.infraestructura_vial)) {
                            registroParaEnviar.infraestructura_vial = [registroParaEnviar.infraestructura_vial];
                        }
                        registroParaEnviar.registrado_ica = Boolean(registroParaEnviar.registrado_ica);
                        registroParaEnviar.vive_predio = Boolean(registroParaEnviar.vive_predio);
                    }

                    if (storeName === 'organizacion_social') {
                        if (registroParaEnviar.beneficios_participacion && !Array.isArray(registroParaEnviar.beneficios_participacion)) {
                            registroParaEnviar.beneficios_participacion = [registroParaEnviar.beneficios_participacion];
                        }
                        registroParaEnviar.pertenece_jac = Boolean(registroParaEnviar.pertenece_jac);
                        registroParaEnviar.pertenece_asociacion = Boolean(registroParaEnviar.pertenece_asociacion);
                        registroParaEnviar.participa_otras_organizaciones = Boolean(registroParaEnviar.participa_otras_organizaciones);
                    }

                    if (storeName === 'fuerza_laboral_social') {
                        registroParaEnviar.num_trabajadores = parseInt(registroParaEnviar.num_trabajadores) || 0;
                        registroParaEnviar.num_hombres = parseInt(registroParaEnviar.num_hombres) || 0;
                        registroParaEnviar.num_mujeres = parseInt(registroParaEnviar.num_mujeres) || 0;
                        registroParaEnviar.contrato_formal = Boolean(registroParaEnviar.contrato_formal);
                        registroParaEnviar.seguridad_social = Boolean(registroParaEnviar.seguridad_social);
                        registroParaEnviar.sg_sst = Boolean(registroParaEnviar.sg_sst);
                        registroParaEnviar.dotacion = Boolean(registroParaEnviar.dotacion);
                    }

                    if (!registroParaEnviar.local_id) {
                        registroParaEnviar.local_id = registroOriginal.id || crypto.randomUUID();
                    }

                    console.log(`Intentando sincronizar registro de ${storeName}:`, registroParaEnviar);
                    const responseData = await safeFetch(endpoints[storeName], {
                        method: 'POST',
                        body: JSON.stringify(registroParaEnviar)
                    });

                    console.log(`✅ ${storeName}: registro sincronizado exitosamente.`, responseData);
                    totalSincronizados++;
                }
            }

        } catch (error) {
            console.error(`Error sincronizando ${storeName}:`, error);
            storeErrores.push({ storeName, error: error.message });
            errores.push({ storeName, error: error.message });
        }

        // Limpiar store si no hubo errores
        if (storeErrores.length === 0) {
            await eliminarRegistrosVisitaDelStore(storeName, visitaId);
            console.log(`🗑️ ${storeName}: registros eliminados exitosamente.`);
        }
    }

    // Resultado final
    if (errores.length === 0) {
        console.log(`✅ ¡Sincronización social completa! ${totalSincronizados} registros sincronizados.`);
    } else {
        console.error(`⚠️ Sincronización con errores. Sincronizados: ${totalSincronizados}. Errores:`, errores);
        throw new Error(`Sincronización incompleta. ${errores.length} errores. ${totalSincronizados} registros sincronizados.`);
    }

    return totalSincronizados;
}

/**
 * Elimina registros específicos de una visita
 */
async function eliminarRegistrosVisitaDelStore(storeName, visitaId) {
    try {
        if (typeof deleteDataFromStore === 'function') {
            await deleteDataFromStore(storeName, visitaId);
        } else {
            console.warn(`deleteDataFromStore no disponible, usando clearStore para ${storeName}`);
            await clearStore(storeName);
        }
    } catch (error) {
        console.error(`Error eliminando registros de ${storeName}:`, error);
    }
}