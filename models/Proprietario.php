<?php
class Proprietario {
    private $conn;
    private $table = "proprietario";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtém os dados do proprietário administrador (ID 1)
     */
    public function obterDados() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = 1 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza o nome do proprietário e o caminho da foto da assinatura digital
     */
    public function atualizar($nome, $assinatura_path = null) {
        if ($assinatura_path) {
            // Se uma nova assinatura foi carregada, atualiza tudo
            $query = "UPDATE " . $this->table . " SET nome = :nome, assinatura_path = :assinatura WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':assinatura', $assinatura_path);
        } else {
            // Se não enviou foto nova, atualiza apenas o nome
            $query = "UPDATE " . $this->table . " SET nome = :nome WHERE id = 1";
            $stmt = $this->conn->prepare($query);
        }

        // Proteção contra ataques XSS nos inputs de texto
        $nome_sanitizado = htmlspecialchars(strip_tags($nome));
        $stmt->bindParam(':nome', $nome_sanitizado);

        return $stmt->execute();
    }
}