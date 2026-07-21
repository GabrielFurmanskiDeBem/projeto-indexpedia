<?php
    require_once __DIR__ . '/../includes/conexao_banco.php';
    require_once __DIR__ . '/../../actions/controllers/UserController.php';

    $codigo = $_GET['code'] ?? '';

    if ($codigo) {
        $controller = new UserController($pdo);
        $mensagem = $controller->verificar($codigo);
        echo "<script>alert('$mensagem'); window.location.href = 'autenticacao.php';</script>";
    } else {
        header('Location: autenticacao.php');
    }
?>
