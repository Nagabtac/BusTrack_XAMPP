<?php
header('Content-Type: application/json');
require_once '../db.php';

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
    // First check if driver has assigned routes
    $stmt = $conn->prepare("SELECT COUNT(*) as assignment_count FROM bus_route_assignments WHERE driver_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if($row['assignment_count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete driver with active route assignments. Please reassign routes first.']);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM drivers WHERE driver_id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Driver deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting driver: ' . $conn->error]);
    }
}
?>