<?php
/**
 * Navbar reutilizável do Indexpedia.
 *
 * Basta incluir este arquivo em qualquer página dentro de assets/pages/:
 *   <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
 *
 * Caso este arquivo seja incluído a partir de uma página em outro nível de
 * pastas, defina $navBaseAssets e $navBaseRoot ANTES do require, por exemplo:
 *   $navBaseAssets = 'assets/';   // caminho até a pasta assets/
 *   $navBaseRoot   = '';          // caminho até a raiz do projeto
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexao_banco.php';
require_once __DIR__ . '/../../actions/models/Usuario.php';

// Caminhos relativos padrão (assumindo que a página está em assets/pages/*.php)
$navBaseAssets = $navBaseAssets ?? '../';
$navBaseRoot   = $navBaseRoot ?? '../../';

$usuarioLogado = null;
if (isset($_SESSION['usuario_id'])) {
    $usuarioModel  = new Usuario($pdo);
    $usuarioLogado = $usuarioModel->pegarPorID($_SESSION['usuario_id']);
}

$nomeExibicao = $usuarioLogado['usuario_nome_exibicao']
    ?? $usuarioLogado['usuario_nome_fixo']
    ?? '';

$fotoPerfil = (!empty($usuarioLogado['usuario_foto_perfil']))
    ? $navBaseRoot . 'uploads/perfil-imagens/' . $usuarioLogado['usuario_foto_perfil']
    : $navBaseAssets . 'img/default-avatar.svg';
?>
<link rel="stylesheet" href="<?= htmlspecialchars($navBaseAssets) ?>css/navbar_style.css">

<nav class="indexpedia-navbar">

    <!-- Logo (esquerda) -->
    <a href="index.php" class="navbar-logo">
        <img src="<?= htmlspecialchars($navBaseAssets) ?>img/logo.svg" alt="Indexpedia">
    </a>

    <!-- Pesquisa (meio) -->
    <div class="navbar-search">
        <form class="navbar-search-form" id="navbar-search-form">
            <input
                type="text"
                id="navbar-search-input"
                class="navbar-search-input"
                name="q"
                placeholder="Pesquisar artigos e usuários..."
                autocomplete="off"
            >
            <button type="submit" class="navbar-search-btn" id="navbar-search-btn" aria-label="Pesquisar">
                <img src="<?= htmlspecialchars($navBaseAssets) ?>img/icons/lupa.svg" alt="">
            </button>
        </form>

        <div class="navbar-search-results" id="navbar-search-results"></div>
    </div>

    <!-- Perfil (direita) -->
    <?php if ($usuarioLogado): ?>
        <div class="navbar-perfil" id="navbar-perfil">
            <button
                type="button"
                class="navbar-perfil-btn"
                id="navbar-perfil-btn"
                aria-haspopup="true"
                aria-expanded="false"
            >
                <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de perfil" class="avatar">
                <span class="navbar-perfil-nome"><?= htmlspecialchars($nomeExibicao) ?></span>
                <span class="navbar-perfil-seta"></span>
            </button>

            <div class="navbar-perfil-menu" id="navbar-perfil-menu" role="menu">
                <a href="usuario_perfil.php?id=<?= (int) $usuarioLogado['usuario_id'] ?>" role="menuitem">
                    Perfil
                </a>
                <a href="usuario_configuracoes.php" role="menuitem">
                    Configurações
                </a>
                <hr class="separador">
                <a href="<?= htmlspecialchars($navBaseRoot) ?>actions/logout.php" class="item-sair" role="menuitem">
                    Sair
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="autenticacao.php" class="navbar-btn-login">Entrar</a>
    <?php endif; ?>

</nav>

<script>
    window.INDEXPEDIA_NAV_ACTIONS_PATH = "<?= htmlspecialchars($navBaseRoot, ENT_QUOTES) ?>actions/";
</script>
<script src="<?= htmlspecialchars($navBaseAssets) ?>js/navbar.js" defer></script>
