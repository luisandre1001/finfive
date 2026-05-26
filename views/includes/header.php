<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINFIVE - Sistema de Controlo de Aluguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .nav-link:hover {
            color: #198754 !important; /* Cor verde de destaque ao passar o rato */
        }
        .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #198754;
        }

        /* =========================================
   REGRAS DE OTIMIZAÇÃO MOBILE (Telas < 768px)
   ========================================= */
@media (max-width: 767.98px) {
    /* 1. Reset Global de Tipografia Mobile */
    body {
        font-size: 0.9rem !important; /* Corpo de texto ligeiramente menor */
    }

    /* 2. Ajuste de Títulos de Página */
    h2.h3 {
        font-size: 1.5rem !important; /* Títulos principais mais contidos */
        margin-bottom: 0.5rem !important;
    }
    
    p.text-muted {
        font-size: 0.85rem !important; /* Subtítulos menores */
    }

    /* 3. Ajuste Crítico: Números Grandes nos Cards do Dashboard */
    /* Substitua display-4 por uma classe personalizada ou ajuste nativamente */
    .display-1, .display-2, .display-3, .display-4,
    .dash-card-value {
        font-size: 2rem !important; /* Redução drástica: de display-4 (3.5rem) para 2rem */
        font-weight: 800 !important;
        margin-bottom: 0 !important;
    }
    
    /* A unidade "kz" deve ser ainda menor para manter a proporção */
    .display-1 span, .display-2 span, .display-3 span, .display-4 span,
    .dash-card-currency {
        font-size: 1rem !important; /* Quase o tamanho do texto normal */
        margin-left: 0.2rem;
    }

    /* 4. Ajuste de Títulos e Textos dentro dos Cards */
    .card-header, h6.text-uppercase {
        font-size: 0.8rem !important; /* Títulos menores */
        padding: 0.5rem 1rem !important;
    }
    
    .card-body p, .card-body div {
        font-size: 0.85rem !important;
    }

    /* 5. Ajuste Crítico de Tabela Mobile (A "Tabela Responsiva Real") */
    /* Isso transforma a tabela em cards individuais, o segredo do mobile profissional */
    
    .table-responsive {
        border: none !important;
    }

    .table thead {
        display: none !important; /* Oculta o cabeçalho original */
    }

    .table tbody tr {
        display: block !important;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .table tbody td {
        display: block !important;
        text-align: right !important;
        padding-left: 50% !important;
        position: relative;
        border-bottom: 1px solid #eee;
    }

    .table tbody td::before {
        content: attr(data-label); /* Usa o atributo data-label (que adicionaremos) como cabeçalho */
        position: absolute;
        left: 1rem;
        width: 45%;
        padding-right: 0.5rem;
        white-space: nowrap;
        font-weight: bold;
        text-align: left;
        color: #6c757d;
        font-size: 0.8rem;
    }

    /* Ajuste específico para a coluna de Ações em Mobile */
    .table tbody td:last-child {
        text-align: center !important;
        padding-left: 1rem !important;
        border-bottom: none !important;
    }

    /* 6. Ajuste de Botões em Mobile */
    .btn, .btn-group, .btn-sm {
        font-size: 0.8rem !important; /* Botões menores */
        width: 100% !important; /* Botões ocupam toda a largura em Mobile */
        margin-bottom: 0.5rem;
    }
    
    .btn-group .btn {
        width: auto !important; /* Botões de ação em grupo mantêm o tamanho */
    }
}
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-white" href="index.php?acao=dashboard">
                <i class="bi bi-shield-check text-success me-2"></i>FINFIVE
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item">
                        <a class="nav-link text-light <?= (!isset($_GET['acao']) || $_GET['acao'] == 'dashboard') ? 'active' : '' ?>" href="index.php?acao=dashboard">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    
                        <li class="nav-item">
                            <a class="nav-link text-light <?= (isset($_GET['acao']) && $_GET['acao'] == 'inquilinos') ? 'active' : '' ?>" href="index.php?acao=inquilinos">
                                <i class="bi bi-people-fill me-1"></i> Inquilinos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light <?= (isset($_GET['acao']) && $_GET['acao'] == 'pagamentos') ? 'active' : '' ?>" href="index.php?acao=pagamentos">
                                <i class="bi bi-cash-stack me-1"></i> Pagamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light <?= ($_GET['acao'] ?? '') == 'manutencao' ? 'active fw-bold' : '' ?>" href="index.php?acao=manutencao">
                                <i class="bi bi-tools me-2"></i>Manutenções
                            </a>
                        </li>
                    
                    
                    <li class="nav-item">
                        <a class="nav-link text-light <?= (isset($_GET['acao']) && $_GET['acao'] == 'relatorios') ? 'active' : '' ?>" href="index.php?acao=relatorios">
                            <i class="bi bi-graph-up-arrow me-1"></i> Relatórios
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-light <?= (isset($_GET['acao']) && $_GET['acao'] == 'configuracoes') ? 'active' : '' ?>" href="index.php?acao=configuracoes">
                                <i class="bi bi-gear-fill me-1"></i> Configurações
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3">
                        <span class="navbar-text text-white-50 me-2 small">Olá, <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?></span>
                        <a class="btn btn-sm btn-outline-danger" href="index.php?acao=logout">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5 flex-grow-1">