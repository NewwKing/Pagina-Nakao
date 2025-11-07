<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['nombre']) || !isset($data['fecha']) || !isset($data['hora']) || !isset($data['personas'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

if (strtotime($data['fecha']) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'La fecha no puede ser anterior a hoy']);
    exit;
}

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

$filePath = $dataDir . '/reservas.json';
$reservas = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

$reservas[] = [
    'id' => uniqid('nakao_'),
    'nombre' => htmlspecialchars(strip_tags($data['nombre'])),
    'fecha' => $data['fecha'],
    'hora' => $data['hora'],
    'personas' => $data['personas'],
    'notas' => isset($data['notas']) ? htmlspecialchars(strip_tags($data['notas'])) : '',
    'timestamp' => date('Y-m-d H:i:s')
];

if (file_put_contents($filePath, json_encode($reservas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar']);
}
?>