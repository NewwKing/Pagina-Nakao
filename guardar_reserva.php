<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['nombre'], $data['fecha'], $data['hora'], $data['personas'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

if (strtotime($data['fecha']) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'La fecha no puede ser anterior a hoy']);
    exit;
}

// --- CONEXIÓN MYSQL ---
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "nakao_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión SQL']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO reservas (nombre, fecha, hora, personas, notas) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param(
    "sssss",
    $data['nombre'],
    $data['fecha'],
    $data['hora'],
    $data['personas'],
    $data['notas']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
}

$stmt->close();
$conn->close();
?>
