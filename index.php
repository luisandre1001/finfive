<?php
// 1. Iniciar a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gerar Token CSRF se não existir na sessão
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verificarCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erro de Segurança: Validação CSRF falhou. Pedido bloqueado.");
        }
    }
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$acao = isset($_GET['acao']) ? $_GET['acao'] : 'dashboard';

// 2. Proteger as rotas contra utilizadores não logados
$rotas_publicas = ['login', 'autenticar'];

if (!isset($_SESSION['user_id']) && !in_array($acao, $rotas_publicas)) {
    header("Location: index.php?acao=login&erro=restrito");
    exit();
}

$rotas_admin = [
    'editar_inquilino', 'eliminar_inquilino',
    'editar_pagamento', 'eliminar_pagamento', 'editar_manutencao', 'eliminar_manutencao',
    'configuracoes', 'salvar_configuracoes', 'salvar_utilizador', 'editar_utilizador', 'eliminar_utilizador'
];

if (in_array($acao, $rotas_admin) && $_SESSION['user_nivel'] !== 'Administrador') {
    die("<h3>🚫 Erro 403: Acesso Negado. Você não tem permissões administrativas para ver esta página.</h3><a href='index.php'>Voltar ao Painel</a>");
}

// 3. Sistema de roteamento (Switch)
switch ($acao) {
    case 'login':
        require_once 'controllers/AuthController.php';
        (new AuthController())->index();
        break;

    case 'autenticar':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logar();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    case 'inquilinos':
        require_once 'config/database.php';
        require_once 'models/Inquilino.php';
        $db = (new Database())->getConnection();
        $listaInquilinos = (new Inquilino($db))->listarTodos();
        
        require_once 'views/includes/header.php';
        require_once 'views/inquilinos.php';
        require_once 'views/includes/footer.php';
        break;

    case 'pagamentos':
        require_once 'config/database.php';
        require_once 'models/Inquilino.php';
        require_once 'models/Pagamento.php';
        $db = (new Database())->getConnection();
        
        $listaInquilinos = (new Inquilino($db))->listarTodos();
        $historico = (new Pagamento($db))->obterHistoricoGeral();
        
        require_once 'views/includes/header.php';
        require_once 'views/pagamentos.php';
        require_once 'views/includes/footer.php';
        break;

    case 'salvar_inquilino':
        verificarCSRF();
        require_once 'controllers/InquilinoController.php';
        (new InquilinoController())->salvar();
        break;

    case 'editar_inquilino':
        verificarCSRF();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'controllers/InquilinoController.php';
        (new InquilinoController())->editar($id);
        break;

    case 'eliminar_inquilino':
        verificarCSRF();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'controllers/InquilinoController.php';
        (new InquilinoController())->deletar($id);
        break;

    case 'salvar_pagamento':
        verificarCSRF();
        require_once 'controllers/PagamentoController.php';
        (new PagamentoController())->salvar();
        break;

    case 'editar_pagamento':
        verificarCSRF();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'controllers/PagamentoController.php';
        (new PagamentoController())->editar($id);
        break;

    case 'eliminar_pagamento':
        verificarCSRF();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'controllers/PagamentoController.php';
        (new PagamentoController())->deletar($id);
        break;

    case 'recibo':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'controllers/PagamentoController.php';
        (new PagamentoController())->gerarRecibo($id);
        break;

    case 'configuracoes':
        require_once 'config/database.php';
        require_once 'models/Utilizador.php';
        $db = (new Database())->getConnection();
            
        // Carrega a lista de utilizadores para a aba correspondente
        $listaUtilizadores = (new Utilizador($db))->listarTodos();
            
        require_once 'views/includes/header.php';
        require_once 'views/configuracoes.php';
        require_once 'views/includes/footer.php';
        break;
    
    case 'salvar_utilizador':
        require_once 'config/database.php';
        require_once 'models/Utilizador.php';
        $db = (new Database())->getConnection();
            
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Utilizador($db))->criar($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['nivel']);
        }
        header("Location: index.php?acao=configuracoes&aba=usuarios&status=sucesso");
        exit();
        break;
    
    case 'editar_utilizador':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'config/database.php';
        require_once 'models/Utilizador.php';
        $db = (new Database())->getConnection();
        $utilizadorModel = new Utilizador($db);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilizadorModel->atualizar($id, $_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['nivel']);
            header("Location: index.php?acao=configuracoes&aba=usuarios&status=sucesso");
            exit();
            }
    
        $dados = $utilizadorModel->obterPorId($id);
        require_once 'views/includes/header.php';
        require_once 'views/utilizadores_editar.php';
        require_once 'views/includes/footer.php';
        break;
    
    case 'eliminar_utilizador':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        require_once 'config/database.php';
        require_once 'models/Utilizador.php';
        $db = (new Database())->getConnection();
            
        if ((new Utilizador($db))->eliminar($id)) {
            header("Location: index.php?acao=configuracoes&aba=usuarios&status=sucesso");
        } else {
            header("Location: index.php?acao=configuracoes&aba=usuarios&status=erro_autoexclusao");
        }
        exit();
        break;
    
    case 'relatorios':
        require_once 'config/database.php';
        require_once 'models/Pagamento.php';
        $db = (new Database())->getConnection();
        $pagamentoModel = new Pagamento($db);
            
        // 1. Estatísticas Gerais (Cards)
        $totais = $pagamentoModel->obterResumoFinanceiro();
            
        // 2. Dados para o Gráfico de Linha (Faturamento por Mês)
        $faturamentoMensal = $pagamentoModel->obterFaturamentoMensal();
            
        // 3. Dados para o Gráfico de Pizza (Estados das Rendas)
        $estadosPizza = $pagamentoModel->obterDivisaoPorEstado();


        require_once 'models/Manutencao.php';
        $manutencaoModel = new Manutencao($db);
        $totalManutencoes = $manutencaoModel->obterCustoTotal();
            
        require_once 'views/includes/header.php';
        require_once 'views/relatorios.php'; // Vamos criar esta View a seguir
        require_once 'views/includes/footer.php';
        break;

    case 'salvar_configuracoes':
        require_once 'controllers/ProprietarioController.php';
        (new ProprietarioController())->atualizarConfiguracoes();
        break;

    case 'manutencao':
        require_once 'controllers/ManutencaoController.php';
        (new ManutencaoController())->index();
        break;
    
    case 'salvar_manutencao':
        require_once 'controllers/ManutencaoController.php';
        (new ManutencaoController())->salvar();
        break;
    
    case 'editar_manutencao':
        require_once 'controllers/ManutencaoController.php';
        (new ManutencaoController())->editar();
        break;
    
    case 'eliminar_manutencao':
        require_once 'controllers/ManutencaoController.php';
        (new ManutencaoController())->eliminar();
        break;

    default:
        echo "Página não encontrada!";
        break;
}

