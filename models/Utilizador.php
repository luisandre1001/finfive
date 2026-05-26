<?php
class Utilizador {
    private $conn;
    private $table = "utilizadores";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fazerLogin($email, $senha) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        $email_sanitizado = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email_sanitizado);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Segurança: Verifica se a senha bate com o hash criptografado
            if (password_verify($senha, $row['senha'])) {
                return $row; // Retorna os dados do utilizador
            }
        }
        return false;
    }

    public function listarTodos() {
        $query = "SELECT id, nome, email, data_registo FROM " . $this->table . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterPorId($id) {
        $query = "SELECT id, nome, email FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($nome, $email, $senha, $nivel = 'Comum') {
        $query = "INSERT INTO " . $this->table . " (nome, email, senha, nivel) VALUES (:nome, :email, :senha, :nivel)";
        $stmt = $this->conn->prepare($query);
        
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':nome', htmlspecialchars(strip_tags($nome)));
        $stmt->bindParam(':email', htmlspecialchars(strip_tags($email)));
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':nivel', $nivel);
        
        return $stmt->execute();
    }

    public function atualizar($id, $nome, $email, $senha = null, $nivel = 'Comum') {
        if (!empty($senha)) {
            $query = "UPDATE " . $this->table . " SET nome = :nome, email = :email, senha = :senha, nivel = :nivel WHERE id = :id";
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
        } else {
            $query = "UPDATE " . $this->table . " SET nome = :nome, email = :email, nivel = :nivel WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', htmlspecialchars(strip_tags($nome)));
        $stmt->bindParam(':email', htmlspecialchars(strip_tags($email)));
        $stmt->bindParam(':nivel', $nivel);
        
        if (!empty($senha)) {
            $stmt->bindParam(':senha', $senha_hash);
        }

        return $stmt->execute();
    }

    public function eliminar($id) {
        // Evitar que o utilizador logado apague a si próprio
        if ($id == $_SESSION['user_id']) {
            return false;
        }
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}