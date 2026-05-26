<style>
.screen-only { display: block; }
@media print {
    nav, .navbar, .sidebar, .btn, .screen-only, .card-header .btn, .col-xl-4, td:last-child, th:last-child { 
        display: none !important; 
    }
    .col-xl-8 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
    body, .container, .main-content, .card { background: #fff !important; color: #000 !important; margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; }
    table { width: 100% !important; border-collapse: collapse !important; }
    th { background-color: #f8f9fa !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
    td { border-bottom: 1px solid #dee2e6 !important; }
    body::before { content: "FINFIVE - RELATÓRIO DE CUSTOS E MANUTENÇÕES OFICIAL \A Emissão: <?= date('d/m/Y H:i') ?>\A\A"; white-space: pre; font-weight: bold; font-size: 14px; }
}
</style>

<div class="row screen-only">
    <div class="col-md-12 mb-4">
        <h2 class="h3"><i class="bi bi-tools text-danger me-2"></i>Gestão de Obras & Manutenções</h2>
        <p class="text-muted">Controle todos os custos de remodelações, reparos e melhorias nos anexos.</p>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 mb-4 screen-only">
        <div class=" <?= isset($manutencao) ? 'show' : '' ?>" id="formManutencao">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-file-earmark-plus me-2"></i>Formulário de Despesas
                </div>
                <div class="card-body">
                    <form action="index.php?acao=salvar_manutencao" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        <?php if(isset($manutencao)): ?>
                            <input type="hidden" name="id" value="<?= $manutencao['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição do Serviço / Remodelação</label>
                            <textarea name="descricao" class="form-control" rows="3" placeholder="Ex: Troca de azulejos e pintura no anexo 3..." required><?= isset($manutencao) ? htmlspecialchars($manutencao['descricao']) : '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Custo Total (Kz)</label>
                            <div class="input-group">
                                <span class="input-group-text">kz</span>
                                <input type="number" step="0.01" name="custo" class="form-control" placeholder="0.00" value="<?= isset($manutencao) ? $manutencao['custo'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Data do Gasto</label>
                            <input type="date" name="data_registo" class="form-control" value="<?= isset($manutencao) ? $manutencao['data_registo'] : date('Y-m-d') ?>" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-check-lg me-2"></i><?= isset($manutencao) ? 'Atualizar Custo' : 'Gravar Despesa' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 mb-4">
        <div class="card border-0 shadow-sm"> 
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center py-2">
                <span><i class="bi bi-list-check me-2"></i>Histórico de Gastos</span>
                <button onclick="window.print();" class="btn btn-sm btn-danger shadow-sm screen-only">
                    <i class="bi bi-printer me-1"></i> Imprimir
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Descrição do Custo</th>
                                <th>Valor Total</th>
                                <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                                <th class="text-center screen-only">Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($historico)): ?>
                                <?php foreach($historico as $m): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($m['data_registo'])) ?></td>
                                    <td class="text-wrap" style="max-width: 300px;"><?= htmlspecialchars($m['descricao']) ?></td>
                                    <td class="fw-bold text-danger"><?= number_format($m['custo'], 2, ',', '.') ?> kz</td>
                                    <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                                    <td class="text-center screen-only">
                                        <div class="btn-group">
                                            <a href="index.php?acao=editar_manutencao&id=<?= $m['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <a href="index.php?acao=eliminar_manutencao&id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apagar este registo de manutenção?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhuma manutenção registada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>