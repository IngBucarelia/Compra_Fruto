// src/scripts/sincronizadorSocial.js
import { getAllDataFromStore, clearStore } from '../store/indexeddb';

// Definición de los endpoints para datos sociales
const endpoints = {
    agua_captacion_legal: '/api/offline-sync/agua-captacion',
    agua_uso_eficiente: '/api/offline-sync/agua-uso',
    suelo_conservacion: '/api/offline-sync/suelo-conservacion',
    energia_uso_eficiente: '/api/offline-sync/energia',
    gobernanza_hidrica: '/api/offline-sync/gobernanza',
    emisiones_gei: '/api/offline-sync/emisiones',
    residuos_manejo: '/api/offline-sync/residuos',
    sustancias_manejo: '/api/offline-sync/sustancias',
    vertimientos_manejo: '/api/offline-sync/vertimientos',
    hmp_manejo: '/api/offline-sync/hmp',
    avc_control: '/api/offline-sync/avc',
    ecosistema_proteccion: '/api/offline-sync/ecosistema',
    no_deforestacion_ambiental: '/api/offline-sync/no-deforestacion',
    cierre_visita_ambiental: '/api/offline-sync/cierre-ambiental',
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
        registroMapeado.visita_ambiental_id = registroMapeado.visita_id;
        // No eliminar visita_id para mantener compatibilidad
    }
    
    return registroMapeado;
}

/**
 * Sincroniza los datos sociales almacenados offline en IndexedDB con el servidor.
 */
export async function sincronizadorAmbiental(visitaId, callbackProgreso = null) {
    let totalSincronizados = 0;
    let errores = [];

    console.log(`Iniciando sincronización de datos sociales para visita: ${visitaId}`);

    const storesAmbientales = [
        'agua_captacion_legal',
        'agua_uso_eficiente',
        'suelo_conservacion',
        'energia_uso_eficiente',
        'gobernanza_hidrica',
        'emisiones_gei',
        'residuos_manejo',
        'sustancias_manejo',
        'vertimientos_manejo',
        'hmp_manejo',
        'avc_control',
        'ecosistema_proteccion',
        'no_deforestacion_ambiental',
        'cierre_visita_ambiental'
    ];

    const totalStores = storesAmbientales.length;
    
    for (let i = 0; i < totalStores; i++) {
        const storeName = storesAmbientales[i];
        
        if (callbackProgreso) {
            const porcentaje = Math.round((i / totalStores) * 100);
            const mensajes = {
                'agua_captacion_legal':'sincronizando datos para agua_captacion_legal',
                'agua_uso_eficiente':'sincronizando datos para agua_uso_eficiente',
                'suelo_conservacion':'sincronizando datos para suelo_conservacion',
                'energia_uso_eficiente':'sincronizando datos para energia_uso_eficiente',
                'gobernanza_hidrica':'sincronizando datos para gobernanza_hidrica',
                'emisiones_gei':'sincronizando datos para emisiones_gei',
                'residuos_manejo':'sincronizando datos para residuos_manejo',
                'sustancias_manejo':'sincronizando datos para sustancias_manejo',
                'vertimientos_manejo':'sincronizando datos para vertimientos_manejo',
                'hmp_manejo':'sincronizando datos para hmp_manejo',
                'avc_control':'sincronizando datos para avc_control',
                'ecosistema_proteccion':'sincronizando datos para ecosistema_proteccion',
                'no_deforestacion_ambiental':'sincronizando datos para no_deforestacion_ambiental',
                'cierre_visita_ambiental':'sincronizando datos para cierre_visita_ambiental'
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

            /* =========================
            AGUA CAPTACIÓN LEGAL
            ========================== */
            if (storeName === 'agua_captacion_legal') {

                const registrosParaEnviar = registros.map(r => ({
                    ...mapearCamposVisita(r),
                    local_id: r.local_id || r.id
                }));

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }

            /* =========================
            AGUA USO EFICIENTE
            ========================== */
            } else if (storeName === 'agua_uso_eficiente') {

                const registrosParaEnviar = registros.map(r => ({
                    ...mapearCamposVisita(r),
                    local_id: r.local_id || r.id
                }));

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }

            /* =========================
            SUELO CONSERVACIÓN
            ========================== */
            } else if (storeName === 'suelo_conservacion') {

                const registrosParaEnviar = registros.map(r => ({
                    ...mapearCamposVisita(r),
                    porcentaje: Number(r.porcentaje) || 0,
                    local_id: r.local_id || r.id
                }));

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }

            /* =========================
            ENERGÍA USO EFICIENTE
            ========================== */
            } else if (storeName === 'energia_uso_eficiente') {

                const registrosParaEnviar = registros.map(r => ({
                    ...mapearCamposVisita(r),
                    local_id: r.local_id || r.id
                }));

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }

            /* =========================
            RESIDUOS / SUSTANCIAS / ETC
            ========================== */
            } else if (
                storeName === 'residuos_manejo' ||
                storeName === 'sustancias_manejo' ||
                storeName === 'vertimientos_manejo' ||
                storeName === 'hmp_manejo' ||
                storeName === 'avc_control' ||
                storeName === 'ecosistema_proteccion' ||
                storeName === 'no_deforestacion_ambiental'
            ) {

                const registrosParaEnviar = registros.map(r => ({
                    ...mapearCamposVisita(r),
                    local_id: r.local_id || r.id
                }));

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }

            /* =========================
            CIERRE VISITA AMBIENTAL
            ========================== */
            } else if (storeName === 'cierre_visita_ambiental') {

                const registrosParaEnviar = [];

                for (const r of registros) {
                    let registro = mapearCamposVisita(r);

                    if (registro.firma_responsable instanceof File || registro.firma_responsable instanceof Blob) {
                        registro.firma_responsable = await readFileAsBase64(registro.firma_responsable);
                    }

                    if (Array.isArray(registro.imagenes)) {
                        const imgs = [];
                        for (const img of registro.imagenes) {
                            if (img instanceof File || img instanceof Blob) {
                                imgs.push(await readFileAsBase64(img));
                            } else if (typeof img === 'string') {
                                imgs.push(img);
                            }
                        }
                        registro.imagenes = imgs;
                    }

                    registrosParaEnviar.push({
                        ...registro,
                        local_id: registro.local_id || registro.id
                    });
                }

                const responseData = await safeFetch(endpoints[storeName], {
                    method: 'POST',
                    body: JSON.stringify({ submissions: registrosParaEnviar })
                });

                if (responseData.success) {
                    await eliminarRegistrosVisitaDelStore(storeName, visitaId);
                    totalSincronizados += responseData.sincronizados || registrosParaEnviar.length;
                } else {
                    throw new Error(responseData.message);
                }
            }

        } catch (error) {
                console.error(`❌ Error sincronizando ${storeName}:`, error);
                storeErrores.push(error.message || error);
                errores.push({
                    store: storeName,
                    error: error.message || error
                });
            }


        // Limpiar store si no hubo errores
        
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

