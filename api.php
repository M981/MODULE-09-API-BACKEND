<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "apimodulelatest"; 
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getTasks') {
    $sql = "SELECT * FROM todos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tasks = array();
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } else {
        echo json_encode(array('message' => 'Geen taken gevonden'));
    }
}

$conn->close();
?>
