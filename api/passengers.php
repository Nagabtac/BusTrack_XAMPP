<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'list') {
    getPassengers();
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;

function getPassengers() {
    global $conn;

    ob_clean();

    $search = trim($_GET['q'] ?? '');
    $sql = "SELECT passenger_id, full_name, age, gender, contact_number, address, pickup_location, dropoff_location, travel_date, payment_method, fare_amount
            FROM passengers";

    if ($search !== '') {
        $sql .= " WHERE passenger_id LIKE ? 
                  OR full_name LIKE ? 
                  OR contact_number LIKE ?
                  OR pickup_location LIKE ?
                  OR dropoff_location LIKE ?";
    }

    $sql .= " ORDER BY travel_date DESC, full_name ASC";

    if ($search !== '') {
        $stmt = $conn->prepare($sql);
        $searchValue = '%' . $search . '%';
        $stmt->bind_param("sssss", $searchValue, $searchValue, $searchValue, $searchValue, $searchValue);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    $passengers = [];
    while ($row = $result->fetch_assoc()) {
        $passengers[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $passengers]);
    exit;
}
?>
