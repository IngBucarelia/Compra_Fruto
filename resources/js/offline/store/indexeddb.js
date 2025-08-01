export async function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('visitas-db', 1)

    request.onupgradeneeded = function (event) {
      const db = event.target.result
      if (!db.objectStoreNames.contains('submissions')) {
        db.createObjectStore('submissions', { keyPath: 'id', autoIncrement: true })
      }
    }

    request.onsuccess = function (event) {
      resolve(event.target.result)
    }

    request.onerror = function (event) {
      reject('Error abriendo la BD:', event.target.errorCode)
    }
  })
}

export async function getFormDataByVisita(formName, visitaId) {
  const db = await openDB();
  return new Promise((resolve, reject) => {
    const tx = db.transaction('submissions', 'readonly');
    const store = tx.objectStore('submissions');
    const request = store.getAll();

    request.onsuccess = () => {
      const all = request.result || [];
      const match = all.find(item => item.formName === formName && item.formData.visita_id == visitaId);
      resolve(match ? match.formData : null);
    };

    request.onerror = () => reject(request.error);
  });
}



export async function saveFormData(formName, formData) {
  const db = await openDB()
  const tx = db.transaction('submissions', 'readwrite')
  const store = tx.objectStore('submissions')

  await store.add({
    formName,
    formData: JSON.parse(JSON.stringify(formData)),
    synced: false,
    created_at: new Date()
  })

  await tx.done
}

// Obtener todos los registros por storeName
export async function getAllDataFromStore(formName) {
  const db = await openDB();
  return new Promise((resolve, reject) => {
    const tx = db.transaction('submissions', 'readonly');
    const store = tx.objectStore('submissions');
    const request = store.getAll();

    request.onsuccess = () => {
      const results = request.result || [];
      const filtered = results
        .filter(item => item.formName === formName)
        .map(item => item.formData);
      resolve(filtered);
    };

    request.onerror = () => reject(request.error);
  });
}

// Eliminar todos los registros de un formulario sincronizado
export async function clearStore(formName) {
  const db = await openDB();
  const tx = db.transaction('submissions', 'readwrite');
  const store = tx.objectStore('submissions');

  const request = store.getAll();

  request.onsuccess = () => {
    const results = request.result || [];
    results.forEach(item => {
      if (item.formName === formName) {
        store.delete(item.id);
      }
    });
  };

  request.onerror = () => console.error('Error limpiando store', request.error);
}

// En tu archivo store/indexeddb.js
export async function clearAllStores() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('tu-base-de-datos'); // Usa el nombre de tu DB
    
    request.onsuccess = (event) => {
      const db = event.target.result;
      const transaction = db.transaction(db.objectStoreNames, 'readwrite');
      
      let storesCleared = 0;
      const totalStores = db.objectStoreNames.length;
      
      if (totalStores === 0) {
        resolve();
        return;
      }
      
      Array.from(db.objectStoreNames).forEach(storeName => {
        const store = transaction.objectStore(storeName);
        const clearRequest = store.clear();
        
        clearRequest.onsuccess = () => {
          storesCleared++;
          if (storesCleared === totalStores) {
            resolve();
          }
        };
        
        clearRequest.onerror = (error) => {
          console.error(`Error limpiando store ${storeName}:`, error);
          reject(error);
        };
      });
    };
    
    request.onerror = (event) => {
      reject(event.target.error);
    };
  });
}


