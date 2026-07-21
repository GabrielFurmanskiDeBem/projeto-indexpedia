<?php
    $servidor = "localhost";
    $nome = "root";
    $senha = ""; 
    $banco = "sistema_bd_indexpedia";

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $nome, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Falha na conexão: " . $e->getMessage());
    }
?>