<?php 
    // Ajuste o caminho conforme sua estrutura real. 
    // Aqui estou usando a estrutura que criamos anteriormente.
    require_once __DIR__ . '/../includes/conexao_banco.php';
    require_once __DIR__ . '/../../actions/controllers/UserController.php';

    session_start();
    $controller = new UserController($pdo);
    $mensagem = "";

    // Processar formulários
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $acao = $_POST['acao'] ?? '';

        if ($acao === 'login') {
            $usuarioEmail = $_POST['usuario_email'] ?? '';
            $usuarioSenha = $_POST['usuario_senha'] ?? '';
            $resultado = $controller->login($usuarioEmail, $usuarioSenha);

            if ($resultado['status'] === 'success') {
                $_SESSION['usuario_id'] = $resultado['user']['usuario_id'];
                $_SESSION['usuario_nome_fixo'] = $resultado['user']['usuario_nome_fixo'];
                header('Location: index.php'); // Redireciona após login
                exit;
            } else {
                $mensagem = $resultado['mensagem'] ?? 'Erro ao logar usuário.';
            }
        } elseif ($acao === 'cadastro') {
            $usuarioNome = $_POST['usuario_nome_fixo'] ?? '';
            $usuarioEmail = $_POST['usuario_email'] ?? '';
            $usuarioSenha = $_POST['usuario_senha'] ?? '';
            
            $resultado = $controller->registrar($usuarioNome, $usuarioEmail, $usuarioSenha);
            $mensagem = $resultado['mensagem'] ?? 'Erro ao registrar usuário.';
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Autenticação</title>
          <link rel="stylesheet" href="../css/style.css">
          <link rel="stylesheet" href="../css/autenticacao_style.css">
          <style>
              #cadastro { 
               display: none; 
              }

              .alerta { 
                  padding: 10px; 
                  margin-bottom: 15px; 
                  border: 1px solid #d9534f; 
                  color: #d9534f; 
                  background-color: #f2dede;
                  border-radius: 4px;
              }
          </style>
     </head>
     <body>
          <main class="container">
               <?php if ($mensagem): ?>
               <div class="alerta"><?php echo $mensagem; ?></div>
               <?php endif; ?>

               <div id="login">
                    <h2>Fazer Login</h2>

                    <form method="POST" action="autenticacao.php">
                         <input type="hidden" name="acao" value="login">
                         <input type="email" name="usuario_email" placeholder="E-mail" required>
                         <input type="password" name="usuario_senha" placeholder="Senha" required>

                         <button type="submit">Entrar</button>
                    </form>

                    <p>
                         Não tem uma conta?
                         <a href="javascript:void(0)" onclick="mostrarCadastro()">Crie uma agora</a>
                    </p>
               </div>

               <div id="cadastro">
                    <h2>Fazer Cadastro</h2>

                    <form method="POST" action="autenticacao.php">
                         <input type="hidden" name="acao" value="cadastro">
                         <input type="text" name="usuario_nome_fixo" placeholder="Nome" required>
                         <input type="email" name="usuario_email" placeholder="E-mail" required>
                         <input type="password" name="usuario_senha" placeholder="Senha" required>

                         <button type="submit">Cadastrar</button>
                    </form>

                    <p>
                         Já tem uma conta?
                         <a href="javascript:void(0)" onclick="mostrarLogin()">Entre agora</a>
                    </p>
               </div>
          </main>

          <script>
          function mostrarCadastro(){
               document.getElementById("login").style.display = "none";
               document.getElementById("cadastro").style.display = "block";
          }

          function mostrarLogin(){
               document.getElementById("cadastro").style.display = "none";
               document.getElementById("login").style.display = "block";
          }
          </script>

     </body>
</html>
