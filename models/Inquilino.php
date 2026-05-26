<?php
class Inquilino {
    private $conn;
    private $table = "inquilinos";

    public function __construct($db) { 
        $this->conn = $db; 
    }

    public function listarTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // CORRIGIDO AQUI: Apenas um PDO::
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($nome, $tel1, $tel2, $anexo) {
        $query = "INSERT INTO " . $this->table . " (nome, telefone_principal, telefone_alternativo, descricao_anexo) VALUES (:nome, :tel1, :tel2, :anexo)";
        $stmt = $this->conn->prepare($query);
        
        // Sanitização contra XSS
        $stmt->bindParam(':nome', htmlspecialchars(strip_tags($nome)));
        $stmt->bindParam(':tel1', htmlspecialchars(strip_tags($tel1)));
        $stmt->bindParam(':tel2', htmlspecialchars(strip_tags($tel2)));
        $stmt->bindParam(':anexo', htmlspecialchars(strip_tags($anexo)));
        
        return $stmt->execute();
    }

    public function obterPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $tel1, $tel2, $anexo) {
        $query = "UPDATE " . $this->table . " SET nome = :nome, telefone_principal = :tel1, telefone_alternativo = :tel2, descricao_anexo = :anexo WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', htmlspecialchars(strip_tags($nome)));
        $stmt->bindParam(':tel1', htmlspecialchars(strip_tags($tel1)));
        $stmt->bindParam(':tel2', htmlspecialchars(strip_tags($tel2)));
        $stmt->bindParam(':anexo', htmlspecialchars(strip_tags($anexo)));
        
        return $stmt->execute();
    }

    public function eliminar($id) {
        // Devido à chave estrangeira (ON DELETE CASCADE) criada no banco de dados,
        // eliminar o inquilino apagará automaticamente todos os pagamentos dele.
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function obterContratosPrestesAVencer() {
        // Esta query calcula a data de término somando os meses pagos à data do pagamento.
        // Depois, filtra apenas os contratos que terminam nos próximos 30 dias (ou já venceram)
        // e que ainda não receberam um novo lançamento posterior.
        $query = "SELECT 
                    i.nome, 
                    i.telefone_principal,
                    p.data_pagamento,
                    p.periodo_meses,
                    DATE_ADD(p.data_pagamento, INTERVAL p.periodo_meses MONTH) as data_termino,
                    DATEDIFF(DATE_ADD(p.data_pagamento, INTERVAL p.periodo_meses MONTH), NOW()) as dias_restantes
                  FROM inquilinos i
                  INNER JOIN pagamentos p ON i.id = p.inquilino_id
                  WHERE p.id = (
                      SELECT MAX(id) 
                      FROM pagamentos 
                      WHERE inquilino_id = i.id
                  )
                  HAVING dias_restantes <= 30
                  ORDER BY dias_restantes ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}