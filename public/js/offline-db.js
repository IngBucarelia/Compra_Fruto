const DB_NAME = 'compra_fruto';
const DB_VERSION = 1;
const STORE_NAME = 'areas';

function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = function (event) {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'visita_id' });
            }
        };

        request.onsuccess = function (event) {
            resolve(event.target.result);
        };

        request.onerror = function (event) {
            reject('Error abriendo IndexedDB');
        };
    });
}

async function guardarAreaOffline(data) {
    const db = await openDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    tx.objectStore(STORE_NAME).put(data);
    return tx.complete;
}

async function obtenerAreaOffline(visitaId) {
    const db = await openDB();
    const tx = db.transaction(STORE_NAME, 'readonly');
    return tx.objectStore(STORE_NAME).get(Number(visitaId));
}

async function eliminarAreaOffline(visitaId) {
    const db = await openDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    tx.objectStore(STORE_NAME).delete(Number(visitaId));
    return tx.complete;
}

async function sincronizarAreas() {
    const db = await openDB();
    const tx = db.transaction(STORE_NAME, 'readonly');
    const store = tx.objectStore(STORE_NAME);

    const cursor = store.openCursor();
    cursor.onsuccess = async function (event) {
        const result = event.target.result;
        if (result) {
            const area = result.value;
            try {
                const res = await fetch('/api/sync-area', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(area)
                });

                if (res.ok) {
                    console.log('✅ Área sincronizada', area);
                    await eliminarAreaOffline(area.visita_id);
                } else {
                    console.error('❌ Falló la sincronización de:', area);
                }
            } catch (e) {
                console.warn('⚠️ Sin conexión, reintentando después...');
            }
            result.continue();
        }
    };
}
