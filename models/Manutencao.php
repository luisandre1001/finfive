<?php
class Manutencao {
    private $conn;
    private $table_name = "manutencoes";

    public $id;
    public $descricao;
    public $custo;
    public $data_registo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (descricao, custo, data_registo) VALUES (:descricao, :custo, :data_registo)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":custo", $this->custo);
        $stmt->bindParam(":data_registo", $this->data_registo);

        return $stmt->execute();
    }

    public function lerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY data_registo DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table_name . " SET descricao = :descricao, custo = :custo, data_registo = :data_registo WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":custo", $this->custo);
        $stmt->bindParam(":data_registo", $this->data_registo);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function obterCustoTotal() {
        $query = "SELECT SUM(custo) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
}