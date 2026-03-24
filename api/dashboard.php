<?php
// Suppress any PHP warnings/notices that might interfere with JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering to catch any unexpected output
ob_start();

header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

function getDashboardStats() {
    global $conn;
    
    // Clear any previous output
    ob_clean();
    
    // Get total buses
    $result = $conn->query("SELECT COUNT(*) as total FROM buses");
    $totalBuses = $result->fetch_assoc()['total'];
    
    // Get total drivers
    $result = $conn->query("SELECT COUNT(*) as total FROM drivers");
    $totalDrivers = $result->fetch_assoc()['total'];
    
    // Get active routes
    $result = $conn->query("SELECT COUNT(*) as total FROM routes WHERE status = 'active'");
    $activeRoutes = $result->fetch_assoc()['total'];
    
    // Get active buses (assuming active status means running)
    $result = $conn->query("SELECT COUNT(*) as total FROM buses WHERE status = 'active'");
    $activeBuses = $result->fetch_assoc()['total'];
    
    // Get active drivers
    $result = $conn->query("SELECT COUNT(*) as total FROM drivers WHERE status = 'active'");
    $activeDrivers = $result->fetch_assoc()['total'];
    
    // Get current assignments (buses with routes today)
    $result = $conn->query("SELECT COUNT(DISTINCT bus_id) as total FROM bus_route_assignments WHERE assigned_date = CURDATE()");
    $todayAssignments = $result->fetch_assoc()['total'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'totalBuses' => $totalBuses,
            'totalDrivers' => $totalDrivers,
            'activeRoutes' => $activeRoutes,
            'runningBuses' => $todayAssignments,
            'activeDrivers' => $activeDrivers,
            'activeBuses' => $activeBuses
        ]
    ]);
    exit;
}

getDashboardStats();
?>