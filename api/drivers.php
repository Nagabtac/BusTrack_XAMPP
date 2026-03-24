<?php
// Suppress any PHP warnings/notices that might interfere with JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering to catch any unexpected output
ob_start();

header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch($method) {
    case 'GET':
        if($action === 'list') {
            getDrivers();
        } elseif($action === 'get' && isset($_GET['id'])) {
            getDriver($_GET['id']);
        }
        break;
    case 'POST':
        if($action === 'create') {
            createDriver();
        }
        break;
    case 'PUT':
        if($action === 'update' && isset($_GET['id'])) {
            updateDriver($_GET['id']);
        }
        break;
    case 'DELETE':
        if($action === 'delete' && isset($_GET['id'])) {
            deleteDriver($_GET['id']);
        }
        break;
}

function getDrivers() {
    global $conn;
    
    // Clear any previous output
    ob_clean();
    
    $sql = "SELECT d.*, COUNT(bra.assignment_id) as assigned_buses 
            FROM drivers d 
            LEFT JOIN bus_route_assignments bra ON d.driver_id = bra.driver_id 
            GROUP BY d.driver_id 
            ORDER BY d.driver_id";
    $result = $conn->query($sql);
    
    $drivers = [];
    while($row = $result->fetch_assoc()) {
        $drivers[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $drivers]);
    exit;
}

function getDriver($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM drivers WHERE driver_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($driver = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $driver]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Driver not found']);
    }
}

function createDriver() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO drivers (driver_name, license_number, phone, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", 
        $data['driver_name'], 
        $data['license_number'], 
        $data['phone'], 
        $data['status']
    );
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Driver created successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating driver: ' . $conn->error]);
    }
}

function updateDriver($id) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("UPDATE drivers SET driver_name=?, license_number=?, phone=?, status=? WHERE driver_id=?");
    $stmt->bind_param("ssssi", 
        $data['driver_name'], 
        $data['license_number'], 
        $data['phone'], 
        $data['status'],
        $id
    );
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Driver updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating driver: ' . $conn->error]);
    }
}

function deleteDriver($id) {
    global $conn;
    
    // Check if force delete is requested
    $forceDelete = isset($_GET['force']) && $_GET['force'] === 'true';
    
    if (!$forceDelete) {
        // First check if driver has assigned routes
        $stmt = $conn->prepare("SELECT COUNT(*) as assignment_count FROM bus_route_assignments WHERE driver_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if($row['assignment_count'] > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Driver has active route assignments. Delete anyway?',
                'hasAssignments' => true,
                'assignmentCount' => $row['assignment_count']
            ]);
            return;
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete all route assignments for this driver
        $stmt = $conn->prepare("DELETE FROM bus_route_assignments WHERE driver_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Then delete the driver
        $stmt = $conn->prepare("DELETE FROM drivers WHERE driver_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Driver deleted successfully']);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error deleting driver: ' . $e->getMessage()]);
    }
}
?>