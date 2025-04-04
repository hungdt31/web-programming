<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        .storage-item {
            background-color: #f8f9fa;
            border-radius: 5px;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #dee2e6;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }
        .storage-info {
            flex: 1;
            min-width: 200px;
            word-break: break-all;
        }
        .btn-group {
            white-space: nowrap;
        }
        .form-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }
        @media (max-width: 576px) {
            .storage-item {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-group {
                display: flex;
                justify-content: flex-start;
            }
            .container {
                padding: 10px;
            }
            .form-section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Storage Management</h1>

        <!-- Storage Type Selector -->
        <ul class="nav nav-pills mb-4 justify-content-center" id="storageTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#localStorage">LocalStorage</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#indexedDB">IndexedDB</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- LocalStorage Section -->
            <div class="tab-pane fade show active" id="localStorage">
                <div class="form-section">
                    <h2 class="h4 mb-3">Current LocalStorage Items</h2>
                    <div id="localStorageList" class="mb-3"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <h2 class="h4 mb-3">Add New Item (LocalStorage)</h2>
                            <div class="mb-3">
                                <input type="text" id="localKey" class="form-control mb-2" placeholder="Key">
                                <input type="text" id="localValue" class="form-control mb-2" placeholder="Value">
                                <button onclick="addLocalStorage()" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="localEditForm" style="display: none;" class="form-section">
                            <h2 class="h4 mb-3">Edit Item (LocalStorage)</h2>
                            <div class="mb-3">
                                <input type="text" id="editLocalKey" class="form-control mb-2" readonly>
                                <input type="text" id="editLocalValue" class="form-control mb-2" placeholder="New Value">
                                <div class="btn-group">
                                    <button onclick="updateLocalStorage()" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Update
                                    </button>
                                    <button onclick="cancelLocalEdit()" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IndexedDB Section -->
            <div class="tab-pane fade" id="indexedDB">
                <div class="form-section">
                    <h2 class="h4 mb-3">Current IndexedDB Items</h2>
                    <div id="indexedDBList" class="mb-3"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <h2 class="h4 mb-3">Add New Item (IndexedDB)</h2>
                            <div class="mb-3">
                                <input type="text" id="dbKey" class="form-control mb-2" placeholder="Key">
                                <input type="text" id="dbValue" class="form-control mb-2" placeholder="Value">
                                <input type="date" id="dbExpiry" class="form-control mb-2" placeholder="Expiry Date">
                                <button onclick="addIndexedDB()" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="dbEditForm" style="display: none;" class="form-section">
                            <h2 class="h4 mb-3">Edit Item (IndexedDB)</h2>
                            <div class="mb-3">
                                <input type="text" id="editDBKey" class="form-control mb-2" readonly>
                                <input type="text" id="editDBValue" class="form-control mb-2" placeholder="New Value">
                                <input type="date" id="editDBExpiry" class="form-control mb-2" placeholder="New Expiry Date">
                                <div class="btn-group">
                                    <button onclick="updateIndexedDB()" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Update
                                    </button>
                                    <button onclick="cancelDBEdit()" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button onclick="refreshStorageLists()" class="btn btn-info me-2">
                <i class="bi bi-arrow-clockwise"></i> Refresh Lists
            </button>
            <button onclick="clearAllStorage()" class="btn btn-danger">
                <i class="bi bi-trash"></i> Clear All Storage
            </button>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // IndexedDB setup
        let db;
        const dbName = "storageDB";
        const dbVersion = 1;
        const storeName = "items";

        const request = indexedDB.open(dbName, dbVersion);

        request.onerror = (event) => {
            console.error("IndexedDB error:", event.target.error);
        };

        request.onupgradeneeded = (event) => {
            db = event.target.result;
            if (!db.objectStoreNames.contains(storeName)) {
                const store = db.createObjectStore(storeName, { keyPath: "key" });
                store.createIndex("expiry", "expiry");
            }
        };

        request.onsuccess = (event) => {
            db = event.target.result;
            refreshIndexedDBList();
        };

        // LocalStorage Functions
        function refreshLocalStorageList() {
            const list = document.getElementById('localStorageList');
            list.innerHTML = '';

            if (localStorage.length === 0) {
                list.innerHTML = '<div class="alert alert-info">No items in LocalStorage</div>';
                return;
            }

            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                const value = localStorage.getItem(key);
                const item = document.createElement('div');
                item.className = 'storage-item';
                item.innerHTML = `
                    <div class="storage-info">
                        <strong>${key}</strong> = ${value}
                    </div>
                    <div class="btn-group">
                        <button onclick="editLocalStorage('${key}', '${value}')" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button onclick="deleteLocalStorage('${key}')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                `;
                list.appendChild(item);
            }
        }

        function addLocalStorage() {
            const key = document.getElementById('localKey').value;
            const value = document.getElementById('localValue').value;

            if (!key || !value) {
                alert('Please fill in both key and value');
                return;
            }

            localStorage.setItem(key, value);
            refreshLocalStorageList();

            // Clear form
            document.getElementById('localKey').value = '';
            document.getElementById('localValue').value = '';
        }

        function editLocalStorage(key, value) {
            document.getElementById('localEditForm').style.display = 'block';
            document.getElementById('editLocalKey').value = key;
            document.getElementById('editLocalValue').value = value;
        }

        function updateLocalStorage() {
            const key = document.getElementById('editLocalKey').value;
            const value = document.getElementById('editLocalValue').value;

            localStorage.setItem(key, value);
            document.getElementById('localEditForm').style.display = 'none';
            refreshLocalStorageList();
        }

        function cancelLocalEdit() {
            document.getElementById('localEditForm').style.display = 'none';
        }

        function deleteLocalStorage(key) {
            localStorage.removeItem(key);
            refreshLocalStorageList();
        }

        // IndexedDB Functions
        function refreshIndexedDBList() {
            const list = document.getElementById('indexedDBList');
            list.innerHTML = '';

            const transaction = db.transaction([storeName], "readonly");
            const store = transaction.objectStore(storeName);
            const request = store.getAll();

            request.onsuccess = () => {
                const items = request.result;
                if (items.length === 0) {
                    list.innerHTML = '<div class="alert alert-info">No items in IndexedDB</div>';
                    return;
                }

                items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'storage-item';
                    itemElement.innerHTML = `
                        <div class="storage-info">
                            <strong>${item.key}</strong> = ${item.value}<br>
                            <small>Expires: ${new Date(item.expiry).toLocaleDateString()}</small>
                        </div>
                        <div class="btn-group">
                            <button onclick='editIndexedDB("${item.key}", ${JSON.stringify(item)})' class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button onclick='deleteIndexedDB("${item.key}")' class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    `;
                    list.appendChild(itemElement);
                });
            };
        }

        function addIndexedDB() {
            const key = document.getElementById('dbKey').value;
            const value = document.getElementById('dbValue').value;
            const expiry = new Date(document.getElementById('dbExpiry').value).getTime();

            if (!key || !value || !expiry) {
                alert('Please fill in all fields');
                return;
            }

            const transaction = db.transaction([storeName], "readwrite");
            const store = transaction.objectStore(storeName);
            store.put({ key, value, expiry });

            transaction.oncomplete = () => {
                refreshIndexedDBList();
                // Clear form
                document.getElementById('dbKey').value = '';
                document.getElementById('dbValue').value = '';
                document.getElementById('dbExpiry').value = '';
            };
        }

        function editIndexedDB(key, item) {
            document.getElementById('dbEditForm').style.display = 'block';
            document.getElementById('editDBKey').value = key;
            document.getElementById('editDBValue').value = item.value;
            document.getElementById('editDBExpiry').value = new Date(item.expiry).toISOString().split('T')[0];
        }

        function updateIndexedDB() {
            const key = document.getElementById('editDBKey').value;
            const value = document.getElementById('editDBValue').value;
            const expiry = new Date(document.getElementById('editDBExpiry').value).getTime();

            const transaction = db.transaction([storeName], "readwrite");
            const store = transaction.objectStore(storeName);
            store.put({ key, value, expiry });

            transaction.oncomplete = () => {
                document.getElementById('dbEditForm').style.display = 'none';
                refreshIndexedDBList();
            };
        }

        function cancelDBEdit() {
            document.getElementById('dbEditForm').style.display = 'none';
        }

        function deleteIndexedDB(key) {
            const transaction = db.transaction([storeName], "readwrite");
            const store = transaction.objectStore(storeName);
            store.delete(key);

            transaction.oncomplete = () => {
                refreshIndexedDBList();
            };
        }

        // General Functions
        function refreshStorageLists() {
            refreshLocalStorageList();
            refreshIndexedDBList();
        }

        function clearAllStorage() {
            // Clear LocalStorage
            localStorage.clear();
            
            // Clear IndexedDB
            const transaction = db.transaction([storeName], "readwrite");
            const store = transaction.objectStore(storeName);
            store.clear();

            transaction.oncomplete = () => {
                refreshStorageLists();
            };
        }

        // Initial display
        refreshLocalStorageList();
    </script>
</body>
</html>
