<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="h3"><i class="bi bi-people-fill text-primary me-2"></i>Gestão de Inquilinos</h2>
        <p class="text-muted">Cadastre novos moradores e veja as informações de contacto e anexos.</p>
    </div>
</div>

<?php if (isset($_SESSION['mensagem_sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['mensagem_sucesso']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['mensagem_sucesso']); // Limpa a mensagem da sessão ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensagem_erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['mensagem_erro']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['mensagem_erro']); // Limpa a mensagem da sessão ?>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-person-plus-fill me-2"></i>Novo Registo de Inquilino
            </div>
            <div class="card-body">
                <form action="index.php?acao=salvar_inquilino" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: Agostinho André" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telemóvel Principal</label>
                        <input type="text" name="telefone_principal" class="form-control" placeholder="9xxxxxxxx" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefone Alternativo</label>
                        <input type="text" name="telefone_alternativo" class="form-control" placeholder="Opcional">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição do Anexo (Imóvel)</label>
                        <textarea name="descricao_anexo" class="form-control" rows="4" placeholder="Ex: Anexo T1, Bloco A, entrada independente..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Guardar Inquilino
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">
                <i class="bi bi-list-stars me-2"></i>Inquilinos Registados
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contactos</th>
                                <th>Descrição do Anexo</th>
                                <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                                <th class="text-center">Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($listaInquilinos)): ?>
                                <?php foreach($listaInquilinos as $inq): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($inq['nome'] ?? '') ?></td>
                                    <td>
                                        <i class="bi bi-telephone-fill text-success small"></i> <?= htmlspecialchars($inq['telefone_principal'] ?? '') ?><br>
                                        <?php if(!empty($inq['telefone_alternativo'])): ?>
                                            <i class="bi bi-telephone text-muted small"></i> <?= htmlspecialchars($inq['telefone_alternativo'] ?? '') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><small class="text-muted"><?= nl2br(htmlspecialchars($inq['descricao_anexo'] ?? '')) ?></small></td>
                                    <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'Administrador'): ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="index.php?acao=editar_inquilino&id=<?= $inq['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?acao=eliminar_inquilino&id=<?= $inq['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza? Isso apagará também o histórico financeiro deste inquilino!')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum inquilino registado até ao momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>