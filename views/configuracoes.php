<?php
require_once 'models/Proprietario.php';
$propModel = new Proprietario($db);
$dadosProp = $propModel->obterDados();

// Verifica qual aba deve iniciar ativa após o recarregamento
$aba_ativa = isset($_GET['aba']) ? $_GET['aba'] : 'proprietario';
?>

<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="h3"><i class="bi bi-gear-fill text-dark me-2"></i>Painel Administrativo</h2>
        <p class="text-muted">Gerencie as definições do proprietário e os acessos ao sistema.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Operação realizada com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php json_encode($_GET); endif; ?>
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'erro_autoexclusao'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Erro de segurança: Não pode eliminar o seu próprio utilizador enquanto está logado!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link <?= $aba_ativa == 'proprietario' ? 'active' : '' ?>" id="prop-tab" data-bs-toggle="tab" data-bs-target="#prop-panel" type="button" role="tab"><i class="bi bi-person-bounding-box me-2"></i>Proprietário & Assinatura</button>
            </li>
            <li class="nav-item">
                <button class="nav-link <?= $aba_ativa == 'usuarios' ? 'active' : '' ?>" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-panel" type="button" role="tab"><i class="bi bi-shield-lock me-2"></i>Utilizadores do Sistema</button>
            </li>
        </ul>

        <div class="tab-content" id="configTabsContent">
            
            <div class="tab-pane fade <?= $aba_ativa == 'proprietario' ? 'show active' : '' ?>" id="prop-panel" role="tabpanel">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form action="index.php?acao=salvar_configuracoes" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Nome do Proprietário / Empresa</label>
                                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dadosProp['nome'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Carregar Nova Assinatura Digital (Imagem)</label>
                                    <input type="file" name="assinatura" class="form-control" accept="image/*">
                                </div>
                                <div class="mb-4 text-center p-3 border bg-light rounded">
                                    <?php if(!empty($dadosProp['assinatura_path']) && file_exists($dadosProp['assinatura_path'])): ?>
                                        <img src="<?= $dadosProp['assinatura_path'] ?>" class="img-fluid bg-white p-2 border" style="max-height: 80px;" alt="Assinatura">
                                    <?php else: ?>
                                        <span class="text-danger small"><i class="bi bi-exclamation-circle"></i> Nenhuma assinatura digital configurada.</span>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Guardar Dados do Proprietário</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $aba_ativa == 'usuarios' ? 'show active' : '' ?>" id="users-panel" role="tabpanel">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white fw-bold">Criar Novo Utilizador</div>
                            <div class="card-body">
                                <form action="index.php?acao=salvar_utilizador" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nome Completo</label>
                                        <input type="text" name="nome" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">E-mail (Login)</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Palavra-passe</label>
                                        <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nível de Acesso</label>
                                        <select name="nivel" class="form-select">
                                            <option value="Comum">Comum (Apenas Dashboard e Relatórios)</option>
                                            <option value="Administrador">Administrador (Acesso Total)</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Registar Utilizador</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-dark text-white fw-bold">Utilizadores com Acesso</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome</th>
                                                <th>E-mail</th>
                                                <th>Data Registo</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($listaUtilizadores as $u): ?>
                                            <tr>
                                                <td class="fw-bold"><?= htmlspecialchars($u['nome']) ?></td>
                                                <td><?= htmlspecialchars($u['email']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($u['data_registo'])) ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="index.php?acao=editar_utilizador&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                        <a href="index.php?acao=eliminar_utilizador&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pretende mesmo remover o acesso deste utilizador?')"><i class="bi bi-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>