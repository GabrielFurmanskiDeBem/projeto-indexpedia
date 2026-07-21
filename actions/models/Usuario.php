<?php
    class Usuario {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function criar($usuarioNome, $usuarioEmail, $usuarioSenha, $codigoVerificacao) {
            $hashed_password = password_hash($usuarioSenha, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO usuario (usuario_nome_fixo, usuario_email, usuario_senha, usuario_codigo_verificacao) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$usuarioNome, $usuarioEmail, $hashed_password, $codigoVerificacao]);
        }

        public function pegarPorEmail($usuarioEmail) {
            $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE usuario_email = ?");
            $stmt->execute([$usuarioEmail]);
            return $stmt->fetch();
        }

        public function login($usuarioEmail, $usuarioSenha) {
            $usuario = $this->pegarPorEmail($usuarioEmail);
            if ($usuario && password_verify($usuarioSenha, $usuario['usuario_senha'])) {
                if (!$usuario['usuario_verificado']) {
                    return ['status' => 'unverified', 'mensagem' => 'Por favor, verifique seu e-mail antes de logar.'];
                }
                return ['status' => 'success', 'user' => $usuario];
            }
            return ['status' => 'error', 'mensagem' => 'E-mail ou senha incorretos.'];
        }

        public function verificarEmail($codigoVerificacao) {
            $stmt = $this->pdo->prepare("UPDATE usuario SET usuario_verificado = TRUE, usuario_codigo_verificacao = NULL WHERE usuario_codigo_verificacao = ?");
            return $stmt->execute([$codigoVerificacao]);
        }

        public function pegarTudo() {
            $stmt = $this->pdo->query("SELECT usuario_id, usuario_nome_fixo, usuario_email, usuario_verificado, usuario_data_criacao FROM usuario");
            return $stmt->fetchAll();
        }

        public function pegarPorID($usuarioID) {
            $stmt = $this->pdo->prepare("SELECT usuario_id, usuario_nome_fixo, usuario_nome_exibicao, usuario_email, usuario_verificado, usuario_foto_perfil, usuario_nivel FROM usuario WHERE usuario_id = ?");
            $stmt->execute([$usuarioID]);
            return $stmt->fetch();
        }

        public function pesquisar($termo, $limite = 5) {
            $stmt = $this->pdo->prepare("
                SELECT usuario_id, usuario_nome_fixo, usuario_nome_exibicao, usuario_foto_perfil
                FROM usuario
                WHERE usuario_ativo = 1
                  AND (usuario_nome_fixo LIKE ? OR usuario_nome_exibicao LIKE ?)
                ORDER BY usuario_nome_exibicao ASC
                LIMIT ?
            ");
            $curinga = "%{$termo}%";
            $stmt->bindValue(1, $curinga, PDO::PARAM_STR);
            $stmt->bindValue(2, $curinga, PDO::PARAM_STR);
            $stmt->bindValue(3, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function atualizar($usuarioID, $usuarioNome, $usuarioEmail) {
            $stmt = $this->pdo->prepare("UPDATE usuario SET usuario_nome_fixo = ?, usuario_email = ? WHERE usuario_id = ?");
            return $stmt->execute([$usuarioNome, $usuarioEmail, $usuarioID]);
        }

        public function deletar($usuarioID) {
            $stmt = $this->pdo->prepare("UPDATE usuario SET usuario_ativo = 0 WHERE usuario_id = ?");
            return $stmt->execute([$usuarioID]);
        }
    }
?>