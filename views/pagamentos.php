<style>
/* Regras para o ecrã normal */
.screen-only { display: block; }

/* REGRAS EXCLUSIVAS PARA IMPRESSÃO / GERAR PDF */
@media print {
    /* 1. Esconde o menu lateral, topo, botões, coluna de ações e o FORMULÁRIO LATERAL */
    nav, 
    .navbar, 
    .sidebar, 
    .btn, 
    .screen-only,
    .card-header .btn,
    .col-xl-4, /* ESCONDE O FORMULÁRIO DE LANÇAMENTO */
    td:last-child, 
    th:last-child { 
        display: none !important; 
    }

    /* 2. Força a coluna da tabela (que era col-xl-8) a ocupar 100% da largura da folha */
    .col-xl-8 {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }

    /* 3. Limpa as margens e fundos da página */
    body, .container, .main-content, .card {
        background: #fff !important;
        color: #000 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    /* 4. Garante que a tabela use o espaço total disponível */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    
    th {
        background-color: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 2px solid #000 !important;
    }
    
    td {
        border-bottom: 1px solid #dee2e6 !important;
    }

    /* 5. Cabeçalho do documento impresso */
    body::before {
        content: "FINFIVE - HISTÓRICO FINANCEIRO DE RENDAS \A Data de Emissão: <?= date('d/m/Y H:i') ?>\A\A";
        white-space: pre;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }
}
</style>

<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="h3"><i class="bi bi-cash-stack text-success me-2"></i>Módulo de Lançamentos Financeiros</h2>
        <p class="text-muted">Registe as rendas pagas pelos inquilinos e associe os comprovativos correspondentes.</p>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-wallet2 me-2"></i>Registar Novo Pagamento
            </div>
            
            <div class="card-body">
                <form action="index.php?acao=salvar_pagamento" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Selecione o Inquilino</label>
                        <select name="inquilino_id" class="form-select" required>
                            <option value="">-- Escolha o Inquilino --</option>
                            <?php foreach($listaInquilinos as $i): ?>
                                <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Período (Meses)</label>
                            <input type="number" name="periodo_meses" class="form-control" value="6" min="1" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Valor Pago</label>
                            <div class="input-group">
                                <span class="input-group-text">kz</span>
                                <input type="number" step="0.01" name="valor_pago" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Data de Pagamento</label>
                            <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Estado Atual</label>
                            <select name="estado" class="form-select">
                                <option value="Pago">Pago</option>
                                <option value="Pendente">Pendente</option>
                                <option value="Atrasado">Atrasado</option>
                                <option value="Não Pago">Não Pago</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Anexar Comprovativo (Imagem ou PDF)</label>
                        <input type="file" name="comprovativo" class="form-control" accept="image/*,application/pdf" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-2"></i>Confirmar Recebimento</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8 mb-4">
        <div class="card border-0 shadow-sm"> 
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center py-2">
                <span>
                    <i class="bi bi-journal-text me-2"></i>Histórico Financeiro de Rendas
                </span>
                <button onclick="window.print();" class="btn btn-sm btn-danger shadow-sm screen-only">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>Imprimir / PDF
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Inquilino</th>
                                <th>Período Contrato</th>
                                <th>Valor Recebido</th>
                                <th>Data Registo</th>
                                <th>Estado</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($historico)): ?>
                                <?php foreach($historico as $h): 
                                    $badge = ['Pago'=>'success', 'Pendente'=>'warning text-dark', 'Atrasado'=>'danger', 'Não Pago'=>'secondary'][$h['estado']];
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold d-block text-dark"><?= htmlspecialchars($h['nome']) ?></span>
                                        <small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($h['telefone_principal']) ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= $h['periodo_meses'] ?> Meses</span></td>
                                    <td class="fw-bold text-success"><?= number_format($h['valor_pago'], 2, ',', '.') ?> kz</td>
                                    <td><?= date('d/m/Y', strtotime($h['data_pagamento'])) ?></td>
                                    <td><span class="badge bg-<?= $badge ?>"><?= $h['estado'] ?></span></td>
                                    
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= $h['comprovativo'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver Recibo Original"><i class="bi bi-file-earmark-image"></i> Ficheiro</a>
                                            <a href="index.php?acao=recibo&id=<?= $h['id'] ?>" target="_blank" class="btn btn-sm btn-success" title="Gerar Comprovativo Geral FINFIVE"><i class="bi bi-printer"></i> Recibo</a>
                                            <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                                            <a href="index.php?acao=editar_pagamento&id=<?= $h['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <a href="index.php?acao=eliminar_pagamento&id=<?= $h['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apagar este registo financeiro?')"><i class="bi bi-trash"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Nenhum pagamento registado no sistema.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>