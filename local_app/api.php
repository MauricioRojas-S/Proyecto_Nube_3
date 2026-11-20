<?php

// ==========================================
// CONFIGURACIÓN
// ==========================================

require_once __DIR__ . '/config/api.php';

header('Content-Type: application/json; charset=utf-8');

// ==========================================
// IDENTIFICAR SOLICITUD
// ==========================================

$ruta = trim($_GET['ruta'] ?? '', '/');

$metodo = strtoupper(
    $_SERVER['REQUEST_METHOD'] ?? 'GET'
);

// ==========================================
// VALIDAR RUTAS
// ==========================================

$rutasPermitidas = [
    'salud',
    'salud/base-datos',
    'productos'
];

$esProductoIndividual = preg_match(
    '/^productos\/[1-9][0-9]*$/',
    $ruta
);

$esRutaPermitida = (
    in_array($ruta, $rutasPermitidas, true)
    || $esProductoIndividual
);

if (!$esRutaPermitida) {
    http_response_code(404);

    echo json_encode([
        'error' => 'La ruta solicitada no está permitida.'
    ]);

    exit;
}

// ==========================================
// VALIDAR MÉTODOS HTTP POR RUTA
// ==========================================

$metodosPorRuta = [
    'salud' => ['GET'],
    'salud/base-datos' => ['GET'],
    'productos' => ['GET', 'POST']
];

if ($esProductoIndividual) {
    $metodosPermitidos = ['GET', 'PUT', 'DELETE'];
} else {
    $metodosPermitidos = $metodosPorRuta[$ruta];
}

if (!in_array($metodo, $metodosPermitidos, true)) {
    http_response_code(405);

    echo json_encode([
        'error' => 'Método HTTP no permitido para esta ruta.'
    ]);

    exit;
}

// ==========================================
// CONSTRUIR URL
// ==========================================

$url = URL_PRODUCTOS . '/' . $ruta;

// Conservar parámetros adicionales.

$parametros = $_GET;

unset($parametros['ruta']);

if (!empty($parametros)) {
    $url .= '?' . http_build_query($parametros);
}

// ==========================================
// CONFIGURAR CURL
// ==========================================

$curl = curl_init($url);

$opciones = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => $metodo,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Content-Type: application/json'
    ]
];

// ==========================================
// ENVIAR DATOS POST Y PUT
// ==========================================

if (in_array($metodo, ['POST', 'PUT'], true)) {

    $contenido = file_get_contents('php://input');

    $opciones[CURLOPT_POSTFIELDS] = $contenido;
}

// ==========================================
// EJECUTAR SOLICITUD
// ==========================================

curl_setopt_array($curl, $opciones);

$respuesta = curl_exec($curl);

$error = curl_error($curl);

$codigoHTTP = curl_getinfo(
    $curl,
    CURLINFO_HTTP_CODE
);

curl_close($curl);

// ==========================================
// MANEJAR ERRORES DE CONEXIÓN
// ==========================================

if ($respuesta === false || $codigoHTTP === 0) {

    error_log('Error de conexión con Products Service: ' . $error);

    http_response_code(502);

    echo json_encode([
        'error' => 'No fue posible comunicarse con el microservicio de productos.'
    ]);

    exit;
}

// ==========================================
// DEVOLVER RESPUESTA
// ==========================================

http_response_code($codigoHTTP);

echo $respuesta;