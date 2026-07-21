<?php
    require_once __DIR__ . '/../models/Usuario.php';
    require_once __DIR__ . '/../services/EmailService.php';

    class UserController {
        private $usuarioModel;
        private $emailService;

        public function __construct($pdo) {
            $this->usuarioModel = new Usuario($pdo);
            $this->emailService = new EmailService();
        }
        public function registrar($usuarioNome, $usuarioEmail, $usuarioSenha) {
            $codigoVerificacao = bin2hex(random_bytes(16));
            if ($this->usuarioModel->criar($usuarioNome, $usuarioEmail, $usuarioSenha, $codigoVerificacao)) {
                $this->emailService->enviarVerificacaoEmail($usuarioEmail, $codigoVerificacao);
                return ['status' => 'success', 'mensagem' => "Registro realizado com sucesso! Verifique seu e-mail."];
            }
            return ['status' => 'error', 'mensagem' => "Erro ao registrar usuário."];
        }

        public function login($usuarioEmail, $usuarioSenha) {
            return $this->usuarioModel->login($usuarioEmail, $usuarioSenha);
        }

        public function verificar($codigo) {
            if ($this->usuarioModel->verificarEmail($codigo)) {
                return "E-mail verificado com sucesso!";
            }
            return "Código de verificação inválido.";
        }

        public function listarUsuarios() {
            return $this->usuarioModel->pegarTudo();
        }

        public function atualizarUsuario($usuarioID, $usuarioNome, $usuarioEmail) {
            if ($this->usuarioModel->atualizar($usuarioID, $usuarioNome, $usuarioEmail)) {
                return "Usuário atualizado com sucesso!";
            }
            return "Erro ao atualizar usuário.";
        }

        public function deletarUsuario($usuarioID) {
            if ($this->usuarioModel->deletar($usuarioID)) {
                return "Usuário deletado com sucesso!";
            }
            return "Erro ao deletar usuário.";
        }
    }
?>
