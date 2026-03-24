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
            getBuses();
        } elseif($action === 'get' && isset($_GET['id'])) {
            getBus($_GET['id']);
        }
        break;
    case 'POST':
        if($action === 'create') {
            createBus();
        }
        break;
    case 'PUT':
        if($action === 'update' && isset($_GET['id'])) {
            updateBus($_GET['id']);
        }
        break;
    case 'DELETE':
        if($action === 'delete' && isset($_GET['id'])) {
            deleteBus($_GET['id']);
        }
        break;
}

function getBuses() {
    global $conn;
    
    // Clear any previous output
    ob_clean();
    
    $sql = "SELECT b.*, 
                   d.driver_name,
                   r.route_name,
                   CONCAT(start_dest.destination_name, ' - ', end_dest.destination_name) as route_description
            FROM buses b 
            LEFT JOIN bus_route_assignments bra ON b.bus_id = bra.bus_id 
            LEFT JOIN drivers d ON bra.driver_id = d.driver_id
            LEFT JOIN routes r ON bra.route_id = r.route_id
            LEFT JOIN destinations start_dest ON r.start_destination_id = start_dest.destination_id
            LEFT JOIN destinations end_dest ON r.end_destination_id = end_dest.destination_id
            ORDER BY b.bus_id";
    $result = $conn->query($sql);
    
    $buses = [];
    while($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $buses]);
    exit;
}

function getBus($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM buses WHERE bus_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($bus = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $bus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Bus not found']);
    }
}

function createBus() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO buses (bus_number, plate_number, capacity, model, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", 
        $data['bus_number'], 
        $data['plate_number'], 
        $data['capacity'], 
        $data['model'], 
        $data['status']
    );
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Bus created successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating bus: ' . $conn->error]);
    }
}

function updateBus($id) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("UPDATE buses SET bus_number=?, plate_number=?, capacity=?, model=?, status=? WHERE bus_id=?");
    $stmt->bind_param("ssissi", 
        $data['bus_number'], 
        $data['plate_number'], 
        $data['capacity'], 
        $data['model'], 
        $data['status'],
        $id
    );
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Bus updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating bus: ' . $conn->error]);
    }
}

function deleteBus($id) {
    global $conn;
    
    // Check if force delete is requested
    $forceDelete = isset($_GET['force']) && $_GET['force'] === 'true';
    
    if (!$forceDelete) {
        // First check if bus has route assignments
        $stmt = $conn->prepare("SELECT COUNT(*) as assignment_count FROM bus_route_assignments WHERE bus_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if($row['assignment_count'] > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Bus has active route assignments. Delete anyway?',
                'hasAssignments' => true,
                'assignmentCount' => $row['assignment_count']
            ]);
            return;
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete all route assignments for this bus
        $stmt = $conn->prepare("DELETE FROM bus_route_assignments WHERE bus_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Then delete the bus
        $stmt = $conn->prepare("DELETE FROM buses WHERE bus_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Bus deleted successfully']);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error deleting bus: ' . $e->getMessage()]);
    }
}
?>