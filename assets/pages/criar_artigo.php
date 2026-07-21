<?php
require_once __DIR__ . '/../includes/autenticacao_usuario.php';
require_once __DIR__ . '/../includes/conexao_banco.php';

// Simulação de categorias para o formulário (poderia vir do banco)
$stmt = $pdo->query("SELECT * FROM categoriaartigos ORDER BY categoria_nome ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $autor_id = $_SESSION['usuario_id'] ?? 1; // Fallback para admin se não houver sessão
        $categorias_selecionadas = $_POST['categorias'] ?? [];

        // 1. Inserir Artigo
        $stmtArtigo = $pdo->prepare("INSERT INTO artigo (artigo_titulo, artigo_breve_descricao, artigo_autor, artigo_status) VALUES (?, ?, ?, 'pendente')");
        $stmtArtigo->execute([$titulo, $descricao, $autor_id]);
        $artigo_id = $pdo->lastInsertId();

        // 2. Inserir Categorias
        if (!empty($categorias_selecionadas)) {
            $stmtCat = $pdo->prepare("INSERT INTO artigocategorias (artigo_id, categoria_id) VALUES (?, ?)");
            foreach ($categorias_selecionadas as $cat_id) {
                $stmtCat->execute([$artigo_id, $cat_id]);
            }
        }

        // 3. Inserir Versão Inicial
        $stmtVersao = $pdo->prepare("INSERT INTO versoes (artigo_id, numero_versao, nota_edicao) VALUES (?, 1, 'Criação inicial do artigo')");
        $stmtVersao->execute([$artigo_id]);
        $versao_id = $pdo->lastInsertId();

        // 4. Inserir Blocos
        if (isset($_POST['blocos']) && is_array($_POST['blocos'])) {
            $stmtBloco = $pdo->prepare("INSERT INTO artigobloco (artigo_id, bloco_versao_id, bloco_tipo, bloco_conteudo, bloco_ordem_atual, bloco_tamanho) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($_POST['blocos'] as $index => $bloco) {
                $tipo = $bloco['tipo'];
                $ordem = $bloco['ordem'];
                $tamanho = $bloco['tamanho'] ?? 'w-100';
                $conteudo = '';

                if ($tipo === 'texto') {
                    $conteudo = $bloco['conteudo'] ?? '';
                } else if ($tipo === 'imagem') {
                    // Lógica simples de upload de imagem
                    $fileKey = "bloco_imagem_" . $index;
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
                        $newName = "artigo_" . $artigo_id . "_bloco_" . $index . "_" . time() . "." . $ext;
                        $uploadDir = __DIR__ . '/../../uploads/artigo-imagens/';
                        
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                        
                        move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $newName);
                        $conteudo = $newName;
                    }
                }

                $stmtBloco->execute([$artigo_id, $versao_id, $tipo, $conteudo, $ordem, $tamanho]);
            }
        }

        $pdo->commit();
        echo "<script>alert('Artigo criado com sucesso!'); window.location.href='index.php';</script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Erro ao salvar artigo: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Artigo - Indexpedia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/criar_artigo_style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container">
        <a href="index.php" class="back-link">← Voltar para a página inicial</a>
        <h1>Criar Novo Artigo</h1>

        <form id="form-artigo" action="criar_artigo.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título do Artigo</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Ex: A História da Computação">
            </div>

            <div class="form-group">
                <label for="descricao">Breve Descrição</label>
                <textarea id="descricao" name="descricao" rows="3" required placeholder="Um pequeno resumo sobre o que trata este artigo..."></textarea>
            </div>

            <div class="dropdown">
                <button type="button" class="btn-main">Criar Bloco +</button>
                <div class="dropdown-content">
                    <a href="#" onclick="adicionarBloco('texto'); return false;">Bloco de Texto</a>
                    <a href="#" onclick="adicionarBloco('imagem'); return false;">Bloco de Imagem</a>
                </div>
            </div>

            <div id="container-mae">
                <div class="placeholder-text">Clique em "Criar Bloco" para começar a adicionar conteúdo.</div>
            </div>

            <div class="save-bar">
                <button type="submit" class="btn-save">Publicar Artigo</button>
            </div>
        </form>
    </div>

    <script>
        let blocoCount = 0;
        const containerMae = document.getElementById('container-mae');
        const sizes = ['w-25', 'w-50', 'w-75', 'w-100'];

        function adicionarBloco(tipo) {
            // Remove placeholder se existir
            const placeholder = containerMae.querySelector('.placeholder-text');
            if (placeholder) placeholder.remove();

            blocoCount++;
            const id = `bloco-${blocoCount}`;
            
            const div = document.createElement('div');
            div.className = 'bloco-item w-100'; // Tamanho padrão
            div.id = id;
            div.draggable = true;
            div.dataset.sizeIndex = 3; // Index do array sizes (w-100)

            let conteudoHtml = '';
            if (tipo === 'texto') {
                conteudoHtml = `
                    <label>Texto do Bloco</label>
                    <textarea name="blocos[${blocoCount}][conteudo]" rows="5" placeholder="Digite seu texto aqui..." required></textarea>
                    <input type="hidden" name="blocos[${blocoCount}][tipo]" value="texto">
                `;
            } else {
                conteudoHtml = `
                    <label>Imagem</label>
                    <input type="file" name="bloco_imagem_${blocoCount}" accept="image/*" required>
                    <input type="hidden" name="blocos[${blocoCount}][tipo]" value="imagem">
                `;
            }

            div.innerHTML = `
                <div class="bloco-controles">
                    <button type="button" class="btn-control btn-resize" onclick="redimensionarBloco('${id}')">Redimensionar</button>
                    <button type="button" class="btn-control btn-remove" onclick="removerBloco('${id}')">Remover</button>
                </div>
                ${conteudoHtml}
                <input type="hidden" name="blocos[${blocoCount}][ordem]" class="bloco-ordem" value="${blocoCount}">
                <input type="hidden" name="blocos[${blocoCount}][tamanho]" class="bloco-tamanho" value="w-100">
            `;

            // Eventos de Drag and Drop
            div.addEventListener('dragstart', handleDragStart);
            div.addEventListener('dragover', handleDragOver);
            div.addEventListener('drop', handleDrop);
            div.addEventListener('dragend', handleDragEnd);

            containerMae.appendChild(div);
            atualizarOrdem();
        }

        function removerBloco(id) {
            if (confirm('Deseja remover este bloco?')) {
                document.getElementById(id).remove();
                if (containerMae.children.length === 0) {
                    containerMae.innerHTML = '<div class="placeholder-text">Clique em "Criar Bloco" para começar a adicionar conteúdo.</div>';
                }
                atualizarOrdem();
            }
        }

        function redimensionarBloco(id) {
            const bloco = document.getElementById(id);
            let currentIndex = parseInt(bloco.dataset.sizeIndex);
            
            // Cicla entre os tamanhos: 25 -> 50 -> 75 -> 100 -> 25...
            let nextIndex = (currentIndex + 1) % sizes.length;
            
            // Remove classe antiga
            bloco.classList.remove(sizes[currentIndex]);
            // Adiciona nova classe
            bloco.classList.add(sizes[nextIndex]);
            
            // Atualiza dados
            bloco.dataset.sizeIndex = nextIndex;
            bloco.querySelector('.bloco-tamanho').value = sizes[nextIndex];
        }

        // Lógica de Drag and Drop
        let dragSrcEl = null;

        function handleDragStart(e) {
            this.style.opacity = '0.4';
            dragSrcEl = this;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }

        function handleDragOver(e) {
            if (e.preventDefault) e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDrop(e) {
            if (e.stopPropagation) e.stopPropagation();
            
            if (dragSrcEl !== this) {
                // Troca as posições no DOM
                const allBlocks = Array.from(containerMae.querySelectorAll('.bloco-item'));
                const srcIdx = allBlocks.indexOf(dragSrcEl);
                const destIdx = allBlocks.indexOf(this);

                if (srcIdx < destIdx) {
                    this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(dragSrcEl, this);
                }
                atualizarOrdem();
            }
            return false;
        }

        function handleDragEnd(e) {
            this.style.opacity = '1';
        }

        function atualizarOrdem() {
            const blocos = containerMae.querySelectorAll('.bloco-item');
            blocos.forEach((bloco, index) => {
                bloco.querySelector('.bloco-ordem').value = index + 1;
            });
        }
    </script>
</body>
</html>
