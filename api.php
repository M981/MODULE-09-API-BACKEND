<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addTask') {
    // Controleer of alle vereiste parameters zijn ontvangen
    if (isset($_POST['user_id']) && isset($_POST['task']) && isset($_POST['startdate']) && isset($_POST['enddate'])) {
        $user_id = $_POST['user_id'];
        $task = $_POST['task'];
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        
        if (strtotime($startdate) === false || strtotime($enddate) === false) {
            echo json_encode(array('message' => 'Ongeldige datum. Gebruik het formaat YYYY-MM-DD.'));
        } else {
            $sql = "INSERT INTO todos (user_id, task, startdate, enddate, done) VALUES ('$user_id', '$task', '$startdate', '$enddate', FALSE)";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(array('message' => 'Taak succesvol toegevoegd'));
            } else {
                echo json_encode(array('message' => 'Er is een fout opgetreden bij het toevoegen van de taak'));
            }
        }
    } else {
        echo json_encode(array('message' => 'Niet alle vereiste parameters zijn ontvangen'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'updateTask') {
    // Controleer of alle vereiste parameters zijn ontvangen
    parse_str(file_get_contents("php://input"), $put_vars);
    if (isset($put_vars['id'])) {
        $task_id = $put_vars['id'];
        
        $sql = "UPDATE todos SET done = TRUE WHERE id = '$task_id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array('message' => 'Taak succesvol gemarkeerd als voltooid'));
        } else {
            echo json_encode(array('message' => 'Er is een fout opgetreden bij het bijwerken van de taak'));
        }
    } else {
        echo json_encode(array('message' => 'Taak-ID is niet ontvangen'));
    }
}

$conn->close();
?>
