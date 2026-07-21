<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se o usuário NÃO está logado
    if (!isset($_SESSION['usuario_id'])) {
?>
        <!DOCTYPE html>
        <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <style>
                    .modal-overlay {
                        position: fixed;
                        top: 0; left: 0; width: 100%; height: 100%;
                        background: rgba(0, 0, 0, 0.7);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                    }
                    .modal-content {
                        background: white;
                        padding: 30px;
                        border-radius: 8px;
                        text-align: center;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                        max-width: 400px;
                        width: 90%;
                    }
                    .modal-content h2 { color: #333; margin-top: 0; }
                    .modal-content p { color: #666; margin-bottom: 20px; }
                    .modal-btn {
                        background: #0275d8;
                        color: white;
                        border: none;
                        padding: 10px 25px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 16px;
                        text-decoration: none;
                        display: inline-block;
                    }
                    .modal-btn:hover { background: #025aa5; }
                </style>
            </head>
            <body>
                <div class="modal-overlay">
                    <div class="modal-content">
                        <h2>Acesso Restrito</h2>
                        <p>Você precisa estar autenticado para acessar esta página.</p>
                        <a href="autenticacao.php" class="modal-btn">Ir para Login</a>
                    </div>
                </div>
            </body>
        </html>
<?php
        exit(); // Interrompe o carregamento do restante da página
    }
?>
