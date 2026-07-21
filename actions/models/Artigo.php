<?php
    class Artigo {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function pesquisar($termo, $limite = 5) {
            $stmt = $this->pdo->prepare("
                SELECT artigo_id, artigo_titulo, artigo_breve_descricao
                FROM artigo
                WHERE artigo_status = 'aprovado'
                  AND artigo_titulo LIKE ?
                ORDER BY artigo_titulo ASC
                LIMIT ?
            ");
            $curinga = "%{$termo}%";
            $stmt->bindValue(1, $curinga, PDO::PARAM_STR);
            $stmt->bindValue(2, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
