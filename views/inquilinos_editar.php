<div class="card border-0 shadow-sm col-md-6 mx-auto">
    <div class="card-header bg-warning text-dark fw-bold">Editar Inquilino: <?= htmlspecialchars($dados['nome']) ?></div>
    <div class="card-body">
        <form action="index.php?acao=editar_inquilino&id=<?= $dados['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dados['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telemóvel Principal</label>
                <input type="text" name="telefone_principal" class="form-control" value="<?= htmlspecialchars($dados['telefone_principal']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefone Alternativo</label>
                <input type="text" name="telefone_alternativo" class="form-control" value="<?= htmlspecialchars($dados['telefone_alternativo']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição do Anexo</label>
                <textarea name="descricao_anexo" class="form-control" rows="3" required><?= htmlspecialchars($dados['descricao_anexo']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning w-100">Salvar Alterações</button>
        </form>
    </div>
</div>