<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}

$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home','buses','drivers','passengers','tracking','reports'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BusTrack | Fleet Management</title>

<style>
* {
    box-sizing: border-box;
}

html, body {
    height: 100%;
}

body {
    margin: 0;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    min-height: 100vh;
    align-items: stretch;
    background: #f8fafc;
    color: #0f172a;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background: #0f172a;
    color: white;
    padding: 20px;
    min-height: 100vh;
    flex-shrink: 0;
}

.sidebar a {
    display: block;
    padding: 10px;
    color: #94a3b8;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 5px;
}

.sidebar a:hover,
.sidebar a.active {
    background: #6366f1;
    color: white;
}

/* Main */
.main {
    flex: 1;
    padding: 30px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    font-size: 28px;
}

.page-content {
    flex: 1;
}

.btn {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    background: #4f46e5;
    color: #ffffff;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn:hover {
    background: #4338ca;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
}

.card h3 {
    margin: 0 0 8px;
    color: #475569;
    font-size: 15px;
    font-weight: 600;
}

.card p {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
}

.section-title {
    margin: 0 0 14px;
    font-size: 22px;
}

.table-wrap {
    margin-top: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f8fafc;
}

.data-table th,
.data-table td {
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-transform: capitalize;
    background: #e2e8f0;
    color: #334155;
}

.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.search-input {
    width: 100%;
    max-width: 320px;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
}

.search-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>🚌 BusTrack</h2>

    <a href="dashboard.php?page=home" class="<?= $page=='home'?'active':'' ?>">Dashboard</a>
    <a href="dashboard.php?page=buses" class="<?= $page=='buses'?'active':'' ?>">Buses</a>
    <a href="dashboard.php?page=drivers" class="<?= $page=='drivers'?'active':'' ?>">Drivers</a>
    <a href="dashboard.php?page=passengers" class="<?= $page=='passengers'?'active':'' ?>">Passengers</a>
    <a href="dashboard.php?page=tracking" class="<?= $page=='tracking'?'active':'' ?>">Live Tracking</a>
    <a href="dashboard.php?page=reports" class="<?= $page=='reports'?'active':'' ?>">Reports</a>
</div>

<div class="main">
    <div class="header">
        <h1><?= ucfirst($page) ?></h1>
        <button class="btn" onclick="logout()">Logout</button>
    </div>

<div class="page-content">
<?php
if(in_array($page, $allowed_pages)){
    include "pages/$page.php";
} else {
    echo "<h2>Page not found</h2>";
}
?>
</div>
</div>

<script>
function logout(){
    if(confirm("Logout?")){
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>