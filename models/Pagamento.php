<?php
class Pagamento {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function criar($inquilino_id, $periodo, $valor, $data, $comprovativo, $estado) {
        $query = "INSERT INTO pagamentos (inquilino_id, periodo_meses, valor_pago, data_pagamento, comprovativo, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$inquilino_id, $periodo, $valor, $data, $comprovativo, $estado]);
    }

    public function obterHistoricoGeral() {
        $query = "SELECT p.*, i.nome, i.telefone_principal FROM pagamentos p 
                  JOIN inquilinos i ON p.inquilino_id = i.id ORDER BY p.data_pagamento DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalGeralRecebido() {
        $query = "SELECT SUM(valor_pago) as total FROM pagamentos WHERE estado = 'Pago'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function totalPorInquilino() {
        $query = "SELECT i.nome, SUM(p.valor_pago) as total FROM pagamentos p 
                  JOIN inquilinos i ON p.inquilino_id = i.id WHERE p.estado = 'Pago' GROUP BY i.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalPorPeriodo() {
        $query = "SELECT periodo_meses, SUM(valor_pago) as total FROM pagamentos WHERE estado = 'Pago' GROUP BY periodo_meses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterDetalhesRecibo($pagamento_id) {
        $query = "SELECT p.*, i.nome, i.telefone_principal, i.descricao_anexo FROM pagamentos p 
                  JOIN inquilinos i ON p.inquilino_id = i.id WHERE p.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pagamento_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $periodo, $valor, $data, $estado, $comprovativo = null) {
        if ($comprovativo) {
            $query = "UPDATE pagamentos SET periodo_meses = ?, valor_pago = ?, data_pagamento = ?, estado = ?, comprovativo = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$periodo, $valor, $data, $estado, $comprovativo, $id]);
        } else {
            $query = "UPDATE pagamentos SET periodo_meses = ?, valor_pago = ?, data_pagamento = ?, estado = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$periodo, $valor, $data, $estado, $id]);
        }
    }

    public function eliminar($id) {
        // Primeiro buscamos o caminho do comprovativo para apagá-lo do disco do XAMPP
        $queryDoc = "SELECT comprovativo FROM pagamentos WHERE id = ?";
        $stmtDoc = $this->conn->prepare($queryDoc);
        $stmtDoc->execute([$id]);
        $pago = $stmtDoc->fetch(PDO::FETCH_ASSOC);
        
        if ($pago && file_exists($pago['comprovativo'])) {
            unlink($pago['comprovativo']); // Apaga o ficheiro físico da pasta uploads
        }

        $query = "DELETE FROM pagamentos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function obterResumoFinanceiro() {
        $query = "SELECT 
                    SUM(CASE WHEN estado = 'Pago' THEN valor_pago ELSE 0 END) as total_pago,
                    SUM(CASE WHEN estado = 'Pendente' THEN valor_pago ELSE 0 END) as total_pendente,
                    SUM(CASE WHEN estado = 'Atrasado' THEN valor_pago ELSE 0 END) as total_atrasado,
                    COUNT(id) as total_lancamentos
                  FROM pagamentos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obterFaturamentoMensal() {
        // Agrupa o faturamento pago por Ano e Mês nos últimos 6 meses
        $query = "SELECT 
                    DATE_FORMAT(data_pagamento, '%Y-%m') as mes, 
                    SUM(valor_pago) as total 
                  FROM pagamentos 
                  WHERE estado = 'Pago'
                  GROUP BY DATE_FORMAT(data_pagamento, '%Y-%m') 
                  ORDER BY mes ASC 
                  LIMIT 6";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterDivisaoPorEstado() {
        // Conta quantos registos existem para cada estado
        $query = "SELECT estado, COUNT(id) as qtd FROM pagamentos GROUP BY estado";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterTotalGeral() {
        $query = "SELECT SUM(valor_pago) as total FROM pagamentos WHERE estado = 'Pago'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function obterFaturamentoPorPeriodo() {
        $query = "SELECT periodo_meses, SUM(valor_pago) as total FROM pagamentos WHERE estado = 'Pago' GROUP BY periodo_meses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterAcumuladoPorInquilino() {
        $query = "SELECT i.nome, SUM(p.valor_pago) as total 
                  FROM pagamentos p 
                  INNER JOIN inquilinos i ON p.inquilino_id = i.id 
                  WHERE p.estado = 'Pago' 
                  GROUP BY p.inquilino_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}