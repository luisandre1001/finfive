<div class="card border-0 shadow-sm col-md-6 mx-auto">
    <div class="card-header bg-warning text-dark fw-bold">Modificar Utilizador</div>
    <div class="card-body">
        <form action="index.php?acao=editar_utilizador&id=<?= $dados['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dados['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail Corporativo</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($dados['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nível de Acesso</label>
                <select name="nivel" class="form-select">
                    <?php 
                    // Blindagem: Captura o nível correto independentemente de se chamar 'nivel' ou 'nivel_acesso'
                    $nivelAtual = $dados['nivel'] ?? $dados['nivel_acesso'] ?? 'Comum'; 
                    ?>
                    <option value="Comum" <?= $nivelAtual == 'Comum' ? 'selected' : '' ?>>Comum (Apenas Dashboard e Relatórios)</option>
                    <option value="Administrador" <?= $nivelAtual == 'Administrador' ? 'selected' : '' ?>>Administrador (Acesso Total)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Nova Palavra-passe</label>
                <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a atual">
            </div>
            <div class="d-flex gap-2">
                <a href="index.php?acao=configuracoes&aba=usuarios" class="btn btn-secondary w-50">Cancelar</a>
                <button type="submit" class="btn btn-warning w-50">Atualizar Cadastro</button>
            </div>
        </form>
    </div>
</div>