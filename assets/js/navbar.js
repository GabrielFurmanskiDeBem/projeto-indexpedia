/* =========================================================
   Navbar - Indexpedia
   - Pesquisa dinâmica (artigos + usuários)
   - Dropdown do perfil (Perfil / Configurações / Sair)
   ========================================================= */
(function () {
    "use strict";

    var ACTIONS_PATH = window.INDEXPEDIA_NAV_ACTIONS_PATH || "../../actions/";

    var searchInput   = document.getElementById("navbar-search-input");
    var searchForm    = document.getElementById("navbar-search-form");
    var searchResults = document.getElementById("navbar-search-results");
    var searchBtn     = document.getElementById("navbar-search-btn");

    var perfilBtn  = document.getElementById("navbar-perfil-btn");
    var perfilMenu = document.getElementById("navbar-perfil-menu");

    var debounceTimer = null;
    var requisicaoAtual = null;

    /* ---------------- Pesquisa ---------------- */

    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto || "";
        return div.innerHTML;
    }

    function mostrarResultados() {
        searchResults.classList.add("ativo");
    }

    function esconderResultados() {
        searchResults.classList.remove("ativo");
    }

    function renderCarregando() {
        searchResults.innerHTML = '<div class="navbar-search-loading">Pesquisando...</div>';
        mostrarResultados();
    }

    function renderVazio() {
        searchResults.innerHTML = '<div class="navbar-search-empty">Nenhum resultado encontrado.</div>';
        mostrarResultados();
    }

    function renderResultados(dados) {
        var usuarios = dados.usuarios || [];
        var artigos  = dados.artigos || [];

        if (usuarios.length === 0 && artigos.length === 0) {
            renderVazio();
            return;
        }

        var html = "";

        if (usuarios.length > 0) {
            html += '<div class="navbar-search-group-title">Usuários</div>';
            usuarios.forEach(function (usuario) {
                var nome = usuario.usuario_nome_exibicao || usuario.usuario_nome_fixo;
                var foto = usuario.usuario_foto_perfil
                    ? "../../uploads/perfil-imagens/" + usuario.usuario_foto_perfil
                    : "../img/default-avatar.svg";

                html += '' +
                    '<a class="navbar-search-item" href="usuario_perfil.php?id=' + encodeURIComponent(usuario.usuario_id) + '">' +
                        '<img class="avatar-mini" src="' + escapeHtml(foto) + '" alt="">' +
                        '<span class="item-texto">' +
                            '<span class="item-titulo">' + escapeHtml(nome) + '</span>' +
                            '<span class="item-sub">@' + escapeHtml(usuario.usuario_nome_fixo) + '</span>' +
                        '</span>' +
                    '</a>';
            });
        }

        if (artigos.length > 0) {
            html += '<div class="navbar-search-group-title">Artigos</div>';
            artigos.forEach(function (artigo) {
                html += '' +
                    '<a class="navbar-search-item" href="artigo.php?id=' + encodeURIComponent(artigo.artigo_id) + '">' +
                        '<span class="icone-artigo">A</span>' +
                        '<span class="item-texto">' +
                            '<span class="item-titulo">' + escapeHtml(artigo.artigo_titulo) + '</span>' +
                            '<span class="item-sub">' + escapeHtml(artigo.artigo_breve_descricao) + '</span>' +
                        '</span>' +
                    '</a>';
            });
        }

        searchResults.innerHTML = html;
        mostrarResultados();
    }

    function pesquisar(termo) {
        if (requisicaoAtual) {
            requisicaoAtual.abort();
        }

        var controller = new AbortController();
        requisicaoAtual = controller;

        renderCarregando();

        fetch(ACTIONS_PATH + "pesquisar.php?q=" + encodeURIComponent(termo), {
            signal: controller.signal
        })
            .then(function (resposta) {
                if (!resposta.ok) {
                    throw new Error("Falha na pesquisa");
                }
                return resposta.json();
            })
            .then(function (dados) {
                renderResultados(dados);
            })
            .catch(function (erro) {
                if (erro.name !== "AbortError") {
                    searchResults.innerHTML = '<div class="navbar-search-empty">Não foi possível pesquisar agora.</div>';
                    mostrarResultados();
                }
            });
    }

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            var termo = searchInput.value.trim();

            clearTimeout(debounceTimer);

            if (termo.length < 2) {
                esconderResultados();
                return;
            }

            debounceTimer = setTimeout(function () {
                pesquisar(termo);
            }, 300);
        });

        searchInput.addEventListener("focus", function () {
            if (searchResults.innerHTML.trim() !== "" && searchInput.value.trim().length >= 2) {
                mostrarResultados();
            }
        });
    }

    // Enter ou clique na lupa leva para a página de resultados completos
    function irParaPesquisaCompleta() {
        var termo = searchInput.value.trim();
        if (termo.length === 0) {
            return;
        }
        window.location.href = "lista_pesquisa_artigos.php?q=" + encodeURIComponent(termo);
    }

    if (searchForm) {
        searchForm.addEventListener("submit", function (evento) {
            evento.preventDefault();
            irParaPesquisaCompleta();
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener("click", function (evento) {
            evento.preventDefault();
            irParaPesquisaCompleta();
        });
    }

    /* ---------------- Dropdown do perfil ---------------- */

    if (perfilBtn && perfilMenu) {
        perfilBtn.addEventListener("click", function (evento) {
            evento.stopPropagation();
            var aberto = perfilMenu.classList.toggle("ativo");
            perfilBtn.setAttribute("aria-expanded", aberto ? "true" : "false");
        });
    }

    /* ---------------- Fechar ao clicar fora / Esc ---------------- */

    document.addEventListener("click", function (evento) {
        if (searchResults && !searchResults.contains(evento.target) && evento.target !== searchInput) {
            esconderResultados();
        }
        if (perfilMenu && perfilBtn && !perfilMenu.contains(evento.target) && !perfilBtn.contains(evento.target)) {
            perfilMenu.classList.remove("ativo");
            perfilBtn.setAttribute("aria-expanded", "false");
        }
    });

    document.addEventListener("keydown", function (evento) {
        if (evento.key === "Escape") {
            esconderResultados();
            if (perfilMenu && perfilBtn) {
                perfilMenu.classList.remove("ativo");
                perfilBtn.setAttribute("aria-expanded", "false");
            }
        }
    });
})();
