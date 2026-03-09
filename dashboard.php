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
            <div class="card"><h3>Total Buses</h3><p>25</p></div>
            <div class="card"><h3>Total Drivers</h3><p>18</p></div>
            <div class="card"><h3>Active Routes</h3><p>12</p></div>
            <div class="card"><h3>Buses Running</h3><p>20</p></div>
        </div>
    </div>

    <div id="buses" class="page">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bus Name</th>
                        <th>Route</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>BUS-01</td><td>City Express</td><td>Route A</td><td><span class="status-pill status-running">Running</span></td></tr>
                    <tr><td>BUS-02</td><td>Metro Bus</td><td>Route B</td><td><span class="status-pill status-stopped">Stopped</span></td></tr>
                    <tr><td>BUS-03</td><td>Rapid Line</td><td>Route C</td><td><span class="status-pill status-running">Running</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tracking" class="page">
        <div class="map-placeholder">
            <p>🛰️ Initializing GPS Stream...</p>
            <small>Mapbox/Google Maps API connection pending</small>
        </div>
    </div>

    <div id="drivers" class="page"><h2>Driver Management</h2></div>
    <div id="reports" class="page"><h2>Fleet Reports</h2></div>
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