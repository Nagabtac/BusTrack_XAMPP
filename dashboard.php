<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTrack | Fleet Management</title>
    <style>
        :root {
            --primary: #6366f1; /* Indigo 500 */
            --primary-dark: #4f46e5;
            --sidebar-bg: #0f172a; /* Slate 900 */
            --bg-main: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            background: var(--bg-main);
            color: var(--text-main);
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            padding: 24px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 40px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            padding: 12px 16px;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            color: #94a3b8;
        }

        .sidebar li:hover {
            background: #1e293b;
            color: white;
        }

        .sidebar li.active-nav {
            background: var(--primary);
            color: white;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }

        .logout-btn {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .card {
            background: var(--white);
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
        }

        .card h3 {
            margin: 0 0 8px 0;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .card p {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Tables */
        .table-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            padding: 16px;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }

        tr:last-child td { border-bottom: none; }

        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-running { background: #dcfce7; color: #166534; }
        .status-stopped { background: #f1f5f9; color: #475569; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-maintenance { background: #fef3c7; color: #92400e; }

        /* Page Management */
        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Map UI */
        .map-placeholder {
            height: 450px;
            background: #e2e8f0;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            border: 2px dashed #cbd5e1;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        /* Form */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
            color: var(--text-main);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Table Actions */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🚌 BusTrack</h2>
    <ul>
        <li class="active-nav" onclick="showPage(this, 'dashboard')">Dashboard</li>
        <li onclick="showPage(this, 'buses')">Buses</li>
        <li onclick="showPage(this, 'drivers')">Drivers</li>
        <li onclick="showPage(this, 'tracking')">Live Tracking</li>
        <li onclick="showPage(this, 'reports')">Reports</li>
    </ul>
</div>

<div class="main">
    <div class="header">
        <h1 id="page-title">Dashboard</h1>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <div id="dashboard" class="page active">
        <div class="cards">
            <div class="card"><h3>Total Buses</h3><p id="totalBuses">-</p></div>
            <div class="card"><h3>Total Drivers</h3><p id="totalDrivers">-</p></div>
            <div class="card"><h3>Active Routes</h3><p id="activeRoutes">-</p></div>
            <div class="card"><h3>Buses Running</h3><p id="runningBuses">-</p></div>
        </div>
    </div>

    <div id="buses" class="page">
        <div class="table-container">
            <div class="table-header">
                <h3>Bus Management</h3>
                <button class="btn btn-primary" onclick="openBusModal()">Add New Bus</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Bus Number</th>
                        <th>Plate Number</th>
                        <th>Model</th>
                        <th>Capacity</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="busesTableBody">
                    <tr><td colspan="8" style="text-align: center;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="drivers" class="page">
        <div class="table-container">
            <div class="table-header">
                <h3>Driver Management</h3>
                <button class="btn btn-primary" onclick="openDriverModal()">Add New Driver</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>License Number</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Assigned Routes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="driversTableBody">
                    <tr><td colspan="6" style="text-align: center;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function showPage(element, pageId) {
        // Update Navigation UI
        document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove('active-nav'));
        element.classList.add('active-nav');

        // Switch Pages
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.getElementById(pageId).classList.add('active');

        // Update Title
        document.getElementById("page-title").innerText = 
            pageId.charAt(0).toUpperCase() + pageId.slice(1);
    }

function logout() {
    if (confirm("Are you sure you want to end your session?")) {
        alert("Logging out...");
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>
    <div id="tracking" class="page">
        <div class="map-placeholder">
            <p>🛰️ Initializing GPS Stream...</p>
            <small>Mapbox/Google Maps API connection pending</small>
        </div>
    </div>

    <div id="reports" class="page"><h2>Fleet Reports</h2></div>
</div>

<!-- Bus Modal -->
<div id="busModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="busModalTitle">Add New Bus</h3>
            <span class="close" onclick="closeBusModal()">&times;</span>
        </div>
        <form id="busForm">
            <input type="hidden" id="busId" name="bus_id">
            <div class="form-group">
                <label for="busNumber">Bus Number</label>
                <input type="text" id="busNumber" name="bus_number" required>
            </div>
            <div class="form-group">
                <label for="plateNumber">Plate Number</label>
                <input type="text" id="plateNumber" name="plate_number" required>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" id="model" name="model">
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" min="1" max="100" value="50">
            </div>
            <div class="form-group">
                <label for="busStatus">Status</label>
                <select id="busStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">Save Bus</button>
                <button type="button" class="btn btn-secondary" onclick="closeBusModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Driver Modal -->
<div id="driverModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="driverModalTitle">Add New Driver</h3>
            <span class="close" onclick="closeDriverModal()">&times;</span>
        </div>
        <form id="driverForm">
            <input type="hidden" id="driverIdEdit" name="driver_id">
            <div class="form-group">
                <label for="driverName">Full Name</label>
                <input type="text" id="driverName" name="driver_name" required>
            </div>
            <div class="form-group">
                <label for="licenseNumber">License Number</label>
                <input type="text" id="licenseNumber" name="license_number" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="driverStatus">Status</label>
                <select id="driverStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">Save Driver</button>
                <button type="button" class="btn btn-secondary" onclick="closeDriverModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
let buses = [];
let drivers = [];

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadBuses();
    loadDrivers();
});

// Dashboard functions
function loadDashboardStats() {
    fetch('api/dashboard.php')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('totalBuses').textContent = data.data.totalBuses;
                document.getElementById('totalDrivers').textContent = data.data.totalDrivers;
                document.getElementById('activeRoutes').textContent = data.data.activeRoutes;
                document.getElementById('runningBuses').textContent = data.data.runningBuses;
            }
        })
        .catch(error => console.error('Error loading dashboard stats:', error));
}

// Bus functions
function loadBuses() {
    fetch('api/buses.php?action=list')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                buses = data.data;
                renderBusesTable();
            }
        })
        .catch(error => console.error('Error loading buses:', error));
}

function renderBusesTable() {
    const tbody = document.getElementById('busesTableBody');
    tbody.innerHTML = '';
    
    buses.forEach(bus => {
        const statusClass = bus.status === 'active' ? 'status-running' : 
                           bus.status === 'maintenance' ? 'status-warning' : 'status-stopped';
        
        tbody.innerHTML += `
            <tr>
                <td>${bus.bus_number}</td>
                <td>${bus.plate_number}</td>
                <td>${bus.model || 'N/A'}</td>
                <td>${bus.capacity}</td>
                <td>${bus.route_description || 'No route assigned'}</td>
                <td>${bus.driver_name || 'No driver'}</td>
                <td><span class="status-pill ${statusClass}">${bus.status}</span></td>
                <td class="action-buttons">
                    <button class="btn btn-warning btn-sm" onclick="editBus(${bus.bus_id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteBus(${bus.bus_id})">Delete</button>
                </td>
            </tr>
        `;
    });
}

function openBusModal(busId = null) {
    const modal = document.getElementById('busModal');
    const title = document.getElementById('busModalTitle');
    const form = document.getElementById('busForm');
    
    if(busId) {
        title.textContent = 'Edit Bus';
        const bus = buses.find(b => b.bus_id == busId);
        if(bus) {
            document.getElementById('busId').value = bus.bus_id;
            document.getElementById('busNumber').value = bus.bus_number;
            document.getElementById('plateNumber').value = bus.plate_number;
            document.getElementById('model').value = bus.model || '';
            document.getElementById('capacity').value = bus.capacity;
            document.getElementById('busStatus').value = bus.status;
        }
    } else {
        title.textContent = 'Add New Bus';
        form.reset();
        document.getElementById('busId').value = '';
    }
    
    modal.style.display = 'block';
}

function closeBusModal() {
    document.getElementById('busModal').style.display = 'none';
}

function editBus(id) {
    openBusModal(id);
}

function deleteBus(id) {
    if(confirm('Are you sure you want to delete this bus?')) {
        fetch(`api/buses.php?action=delete&id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Bus deleted successfully');
                loadBuses();
                loadDashboardStats();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting bus');
        });
    }
}

// Driver functions
function loadDrivers() {
    fetch('api/drivers.php?action=list')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                drivers = data.data;
                renderDriversTable();
            }
        })
        .catch(error => console.error('Error loading drivers:', error));
}

function renderDriversTable() {
    const tbody = document.getElementById('driversTableBody');
    tbody.innerHTML = '';
    
    drivers.forEach(driver => {
        const statusClass = driver.status === 'active' ? 'status-running' : 'status-stopped';
        
        tbody.innerHTML += `
            <tr>
                <td>${driver.driver_name}</td>
                <td>${driver.license_number}</td>
                <td>${driver.phone || 'N/A'}</td>
                <td><span class="status-pill ${statusClass}">${driver.status}</span></td>
                <td>${driver.assigned_buses}</td>
                <td class="action-buttons">
                    <button class="btn btn-warning btn-sm" onclick="editDriver(${driver.driver_id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteDriver(${driver.driver_id})">Delete</button>
                </td>
            </tr>
        `;
    });
}

function openDriverModal(driverId = null) {
    const modal = document.getElementById('driverModal');
    const title = document.getElementById('driverModalTitle');
    const form = document.getElementById('driverForm');
    
    if(driverId) {
        title.textContent = 'Edit Driver';
        const driver = drivers.find(d => d.driver_id == driverId);
        if(driver) {
            document.getElementById('driverIdEdit').value = driver.driver_id;
            document.getElementById('driverName').value = driver.driver_name;
            document.getElementById('licenseNumber').value = driver.license_number;
            document.getElementById('phone').value = driver.phone || '';
            document.getElementById('driverStatus').value = driver.status;
        }
    } else {
        title.textContent = 'Add New Driver';
        form.reset();
        document.getElementById('driverIdEdit').value = '';
    }
    
    modal.style.display = 'block';
}

function closeDriverModal() {
    document.getElementById('driverModal').style.display = 'none';
}

function editDriver(id) {
    openDriverModal(id);
}

function deleteDriver(id) {
    if(confirm('Are you sure you want to delete this driver?')) {
        fetch(`api/drivers.php?action=delete&id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Driver deleted successfully');
                loadDrivers();
                loadDashboardStats();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting driver');
        });
    }
}


// Form submissions
document.getElementById('busForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const isEdit = data.bus_id !== '';
    
    const url = isEdit ? `api/buses.php?action=update&id=${data.bus_id}` : 'api/buses.php?action=create';
    const method = isEdit ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            closeBusModal();
            loadBuses();
            loadDashboardStats();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving bus');
    });
});

document.getElementById('driverForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const isEdit = data.driver_id !== '';
    
    const url = isEdit ? `api/drivers.php?action=update&id=${data.driver_id}` : 'api/drivers.php?action=create';
    const method = isEdit ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            closeDriverModal();
            loadDrivers();
            loadDashboardStats();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving driver');
    });
});

function showPage(element, pageId) {
    // Update Navigation UI
    document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove('active-nav'));
    element.classList.add('active-nav');

    // Switch Pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(pageId).classList.add('active');

    // Update Title
    document.getElementById("page-title").innerText = 
        pageId.charAt(0).toUpperCase() + pageId.slice(1);
        
    // Load data when switching to specific pages
    if(pageId === 'buses') {
        loadBuses();
    } else if(pageId === 'drivers') {
        loadDrivers();
    } else if(pageId === 'dashboard') {
        loadDashboardStats();
    }
}

function logout() {
    if (confirm("Are you sure you want to end your session?")) {
        window.location.href = "logout.php";
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const busModal = document.getElementById('busModal');
    const driverModal = document.getElementById('driverModal');
    
    if (event.target == busModal) {
        busModal.style.display = "none";
    }
    if (event.target == driverModal) {
        driverModal.style.display = "none";
    }
}
</script>

</body>
</html>