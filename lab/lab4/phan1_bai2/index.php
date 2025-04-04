<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Management</title>
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
        }

        .cookie-item {
            background-color: #f8f9fa;
            border-radius: 5px;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Cookie Management</h1>

        <div class="form-section">
            <h2 class="h4 mb-3">Current Cookies</h2>
            <div id="cookieList" class="mb-3"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <h2 class="h4 mb-3">Add New Cookie</h2>
                    <div class="mb-3">
                        <input type="text" id="cookieName" class="form-control mb-2" placeholder="Cookie Name">
                        <input type="text" id="cookieValue" class="form-control mb-2" placeholder="Cookie Value">
                        <input type="text" id="cookieDomain" class="form-control mb-2" placeholder="Domain (optional)">
                        <input type="text" id="cookiePath" class="form-control mb-2" placeholder="Path (optional)">
                        <input type="date" id="cookieExpires" class="form-control mb-2" placeholder="Expires">
                        <div class="form-check mb-3">
                            <input type="checkbox" id="cookieSecure" class="form-check-input">
                            <label class="form-check-label" for="cookieSecure">Secure</label>
                        </div>
                        <button onclick="addCookie()" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Cookie
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div id="editForm" style="display: none;" class="form-section">
                    <h2 class="h4 mb-3">Edit Cookie</h2>
                    <div class="mb-3">
                        <input type="text" id="editCookieName" class="form-control mb-2" readonly>
                        <input type="text" id="editCookieValue" class="form-control mb-2" placeholder="New Cookie Value">
                        <input type="text" id="editCookieDomain" class="form-control mb-2" placeholder="Domain (optional)">
                        <input type="text" id="editCookiePath" class="form-control mb-2" placeholder="Path (optional)">
                        <input type="date" id="editCookieExpires" class="form-control mb-2" placeholder="Expires">
                        <div class="form-check mb-3">
                            <input type="checkbox" id="editCookieSecure" class="form-check-input">
                            <label class="form-check-label" for="editCookieSecure">Secure</label>
                        </div>
                        <div class="btn-group">
                            <button onclick="updateCookie()" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Update
                            </button>
                            <button onclick="cancelEdit()" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </div>

        <div class="text-center mt-4">
            <button onclick="refreshCookieList()" class="btn btn-info me-2">
                <i class="bi bi-arrow-clockwise"></i> Refresh Cookie List
            </button>
            <button onclick="deleteAllCookies()" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete All Cookies
            </button>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display all cookies with edit and delete buttons
        function refreshCookieList() {
            const cookieList = document.getElementById('cookieList');
            cookieList.innerHTML = '';

            const cookies = document.cookie.split(';');
            if (cookies[0] === '') {
                cookieList.innerHTML = '<div class="alert alert-info">No cookies found</div>';
                return;
            }

            cookies.forEach(cookie => {
                const [name, value] = cookie.split('=').map(part => part.trim());
                const cookieItem = document.createElement('div');
                cookieItem.className = 'cookie-item';
                cookieItem.innerHTML = `
                    <div class="cookie-info">
                        <strong>${name}</strong> = ${value}
                    </div>
                    <div class="btn-group">
                        <button onclick="editCookie('${name}', '${value}')" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button onclick="deleteCookie('${name}')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                `;
                cookieList.appendChild(cookieItem);
            });
        }

        // Show edit form for a specific cookie
        function editCookie(name, value) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editCookieName').value = name;
            document.getElementById('editCookieValue').value = decodeURIComponent(value);
        }

        // Cancel editing
        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }

        // Update existing cookie
        function updateCookie() {
            const name = document.getElementById('editCookieName').value;
            const value = document.getElementById('editCookieValue').value;
            const domain = document.getElementById('editCookieDomain').value;
            const path = document.getElementById('editCookiePath').value;
            const expires = document.getElementById('editCookieExpires').value;
            const secure = document.getElementById('editCookieSecure').checked;

            let cookieString = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;

            if (domain) cookieString += `; domain=${domain}`;
            if (path) cookieString += `; path=${path}`;
            if (expires) cookieString += `; expires=${new Date(expires).toUTCString()}`;
            if (secure) cookieString += '; secure';

            document.cookie = cookieString;
            document.getElementById('editForm').style.display = 'none';
            refreshCookieList();
        }

        // Add a new cookie
        function addCookie() {
            const name = document.getElementById('cookieName').value;
            const value = document.getElementById('cookieValue').value;
            const domain = document.getElementById('cookieDomain').value;
            const path = document.getElementById('cookiePath').value;
            const expires = document.getElementById('cookieExpires').value;
            const secure = document.getElementById('cookieSecure').checked;

            let cookieString = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;
            
            if (domain) cookieString += `; domain=${domain}`;
            if (path) cookieString += `; path=${path}`;
            if (expires) cookieString += `; expires=${new Date(expires).toUTCString()}`;
            if (secure) cookieString += '; secure';

            document.cookie = cookieString;
            refreshCookieList();
            
            // Clear form
            document.getElementById('cookieName').value = '';
            document.getElementById('cookieValue').value = '';
            document.getElementById('cookieDomain').value = '';
            document.getElementById('cookiePath').value = '';
            document.getElementById('cookieExpires').value = '';
            document.getElementById('cookieSecure').checked = false;
        }

        // Delete a specific cookie
        function deleteCookie(name) {
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT';
            refreshCookieList();
        }

        // Delete all cookies
        function deleteAllCookies() {
            const cookies = document.cookie.split(';');
            
            for (let cookie of cookies) {
                const eqPos = cookie.indexOf('=');
                const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT';
            }
            
            refreshCookieList();
        }

        // Initial display of cookies
        refreshCookieList();
    </script>
</body>

</html>