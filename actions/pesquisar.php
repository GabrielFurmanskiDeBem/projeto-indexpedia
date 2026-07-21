<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/../assets/includes/conexao_banco.php';
    require_once __DIR__ . '/controllers/SearchController.php';

    $termo = $_GET['q'] ?? '';

    $controller = new SearchController($pdo);
    $resultado = $controller->pesquisar($termo);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    exit;
?>
