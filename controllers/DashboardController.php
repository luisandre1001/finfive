<?php
require_once 'config/database.php';
require_once 'models/Pagamento.php';
require_once 'models/Inquilino.php';
require_once 'models/Manutencao.php';

class DashboardController {
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        
        // Instancia os models necessários
        $pagamentoModel = new Pagamento($db);
        $inquilinoModel = new Inquilino($db);
        $manutencaoModel = new Manutencao($db);
        
        // 1. Dados para a tabela e listagens do formulário
        $historico = $pagamentoModel->obterHistoricoGeral();
        $listaInquilinos = $inquilinoModel->listarTodos();
        
        // 2. Alertas de contratos a expirar
        $alertasContratos = $inquilinoModel->obterContratosPrestesAVencer();
        
        // 3. VARIÁVEIS EM FALTA QUE ESTAVAM A CAUSAR OS ERROS:
        $totalGeral = $pagamentoModel->obterTotalGeral();
        $totaisPeriodos = $pagamentoModel->obterFaturamentoPorPeriodo();
        $totaisInquilinos = $pagamentoModel->obterAcumuladoPorInquilino();
        $totalManutencao = $manutencaoModel->obterCustoTotal();
        $saldoGeral = $totalGeral - $totalManutencao;
        
        // Carrega as telas estruturadas
        require_once 'views/includes/header.php';
        require_once 'views/dashboard.php';
        require_once 'views/includes/footer.php';
    }
}