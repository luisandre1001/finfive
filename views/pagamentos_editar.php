<div class="card border-0 shadow-sm col-md-6 mx-auto">
    <div class="card-header bg-warning text-dark fw-bold">Editar Registo de Pagamento</div>
    <div class="card-body">
        <form action="index.php?acao=editar_pagamento&id=<?= $dados['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Inquilino: <strong><?= htmlspecialchars($dados['nome']) ?></strong></label>
            </div>
            <div class="mb-3">
                <label class="form-label">Período (Meses)</label>
                <input type="number" name="periodo_meses" class="form-control" value="<?= $dados['periodo_meses'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Valor Pago</label>
                <input type="number" step="0.01" name="valor_pago" class="form-control" value="<?= $dados['valor_pago'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Data de Pagamento</label>
                <input type="date" name="data_pagamento" class="form-control" value="<?= $dados['data_pagamento'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="Pago" <?= $dados['estado']=='Pago'?'selected':'' ?>>Pago</option>
                    <option value="Pendente" <?= $dados['estado']=='Pendente'?'selected':'' ?>>Pendente</option>
                    <option value="Atrasado" <?= $dados['estado']=='Atrasado'?'selected':'' ?>>Atrasado</option>
                    <option value="Não Pago" <?= $dados['estado']=='Não Pago'?'selected':'' ?>>Não Pago</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Substituir Comprovativo (Opcional)</label>
                <input type="file" name="comprovativo" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning w-100">Atualizar Dados Financeiros</button>
        </form>
    </div>
</div>