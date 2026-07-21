<?php
require_once __DIR__ . '/../includes/autenticacao_usuario.php';
require_once __DIR__ . '/../includes/conexao_banco.php';

$stmt = $pdo->prepare("
    SELECT a.*, u.usuario_nome_exibicao 
    FROM artigo a 
    JOIN usuario u ON a.artigo_autor = u.usuario_id 
    WHERE a.artigo_status = 'aprovado' 
    ORDER BY a.artigo_data_criacao DESC
");
$stmt->execute();
$artigos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indexpedia - Enciclopédia Online</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index_style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="header">
        <h1>Indexpedia</h1>
        <p>A sua enciclopédia livre e colaborativa</p>
    </div>

    <div class="container">
        <div class="nav-actions">
            <a href="criar_artigo.php" class="btn-create">Criar Novo Artigo +</a>
        </div>

        <div class="artigos-grid">
            <?php if (empty($artigos)): ?>
                <div class="no-artigos">
                    <p>Nenhum artigo aprovado encontrado no momento.</p>
                </div>
            <?php else: ?>
                <?php foreach ($artigos as $artigo): ?>
                    <div class="artigo-card">
                        <div class="artigo-content">
                            <h2 class="artigo-title"><?= htmlspecialchars($artigo['artigo_titulo']) ?></h2>
                            <p class="artigo-desc"><?= htmlspecialchars($artigo['artigo_breve_descricao']) ?></p>
                            <div class="artigo-meta">
                                Por: <?= htmlspecialchars($artigo['usuario_nome_exibicao']) ?> | 
                                <?= date('d/m/Y', strtotime($artigo['artigo_data_criacao'])) ?>
                            </div>
                        </div>
                        <a href="artigo.php?id=<?= $artigo['artigo_id'] ?>" class="btn-read">Ler Artigo</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
