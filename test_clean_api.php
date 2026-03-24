<?php
// Test the cleaned APIs
echo "Testing Dashboard API:\n";
$_GET['action'] = 'list';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Test dashboard
$url = 'http://localhost/BusTrack_XAMPP-1/api/dashboard.php';
$response = file_get_contents($url);
echo "Dashboard Response: " . $response . "\n\n";

// Test buses
$url = 'http://localhost/BusTrack_XAMPP-1/api/buses.php?action=list';
$response = file_get_contents($url);
echo "Buses Response: " . $response . "\n\n";

// Test drivers
$url = 'http://localhost/BusTrack_XAMPP-1/api/drivers.php?action=list';
$response = file_get_contents($url);
echo "Drivers Response: " . $response . "\n";
?>