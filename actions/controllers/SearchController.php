<?php
    require_once __DIR__ . '/../models/Usuario.php';
    require_once __DIR__ . '/../models/Artigo.php';

    class SearchController {
        private $usuarioModel;
        private $artigoModel;

        public function __construct($pdo) {
            $this->usuarioModel = new Usuario($pdo);
            $this->artigoModel  = new Artigo($pdo);
        }

        /**
         * Pesquisa usuários e artigos que contenham o termo informado.
         * Retorna um array associativo pronto para ser convertido em JSON.
         */
        public function pesquisar($termo, $limite = 5) {
            $termo = trim($termo ?? '');

            if ($termo === '' || mb_strlen($termo) < 2) {
                return ['usuarios' => [], 'artigos' => []];
            }

            return [
                'usuarios' => $this->usuarioModel->pesquisar($termo, $limite),
                'artigos'  => $this->artigoModel->pesquisar($termo, $limite),
            ];
        }
    }
?>
