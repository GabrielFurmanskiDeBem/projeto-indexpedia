<?php
require_once __DIR__ . '/../includes/autenticacao_usuario.php';
require_once __DIR__ . '/../includes/conexao_banco.php';

$artigo_id = $_GET['id'] ?? 0;

if (!$artigo_id) {
    header('Location: index.php');
    exit;
}

// 1. Buscar dados do artigo
$stmtArtigo = $pdo->prepare("
    SELECT a.*, u.usuario_nome_exibicao 
    FROM artigo a 
    JOIN usuario u ON a.artigo_autor = u.usuario_id 
    WHERE a.artigo_id = ? AND a.artigo_status = 'aprovado'
");
$stmtArtigo->execute([$artigo_id]);
$artigo = $stmtArtigo->fetch(PDO::FETCH_ASSOC);

if (!$artigo) {
    echo "Artigo não encontrado ou ainda não aprovado.";
    exit;
}

// 2. Buscar blocos da versão mais recente (ou versão atual)
$stmtVersao = $pdo->prepare("SELECT versao_id FROM versoes WHERE artigo_id = ? ORDER BY numero_versao DESC LIMIT 1");
$stmtVersao->execute([$artigo_id]);
$versao = $stmtVersao->fetch(PDO::FETCH_ASSOC);

$blocos = [];
if ($versao) {
    $stmtBlocos = $pdo->prepare("
        SELECT * FROM artigobloco 
        WHERE artigo_id = ? AND bloco_versao_id = ? 
        ORDER BY bloco_ordem_atual ASC
    ");
    $stmtBlocos->execute([$artigo_id, $versao['versao_id']]);
    $blocos = $stmtBlocos->fetchAll(PDO::FETCH_ASSOC);
}

// 3. Buscar categorias
$stmtCat = $pdo->prepare("
    SELECT c.categoria_nome 
    FROM artigocategorias ac 
    JOIN categoriaartigos c ON ac.categoria_id = c.categoria_id 
    WHERE ac.artigo_id = ?
");
$stmtCat->execute([$artigo_id]);
$categorias = $stmtCat->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artigo['artigo_titulo']) ?> - Indexpedia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/artigo_style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container">
        <a href="index.php" class="back-link">← Voltar para a página inicial</a>
        
        <h1><?= htmlspecialchars($artigo['artigo_titulo']) ?></h1>
        
        <div class="artigo-meta">
            Publicado por <strong><?= htmlspecialchars($artigo['usuario_nome_exibicao']) ?></strong> em 
            <?= date('d/m/Y H:i', strtotime($artigo['artigo_data_criacao'])) ?>
        </div>

        <?php if (!empty($categorias)): ?>
            <div class="categorias-tags">
                <?php foreach ($categorias as $cat): ?>
                    <span class="tag"><?= htmlspecialchars($cat) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="artigo-conteudo">
            <?php foreach ($blocos as $bloco): ?>
                <div class="bloco <?= $bloco['bloco_tamanho'] ?> bloco-<?= $bloco['bloco_tipo'] ?>">
                    <?php if ($bloco['bloco_tipo'] === 'texto'): ?>
                        <?= nl2br(htmlspecialchars($bloco['bloco_conteudo'])) ?>
                    <?php else: ?>
                        <?php 
                            $imgPath = "../../uploads/artigo-imagens/" . $bloco['bloco_conteudo'];
                            // No seu projeto real, as imagens estariam em uploads ou img. 
                            // Vou usar um placeholder se não encontrar para garantir que apareça algo.
                        ?>
                        <img src="<?= $imgPath ?>" onerror="this.src='https://via.placeholder.com/800x400?text=Imagem+Indisponível'" alt="Imagem do artigo">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer-meta">
            Este artigo é uma contribuição da comunidade Indexpedia.
        </div>
    </div>

</body>
</html>
