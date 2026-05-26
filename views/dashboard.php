<h1 class="h2 mb-4"><i class="bi bi-speedometer2 me-2"></i>Painel Financeiro</h1>

<?php if (!empty($alertasContratos)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-start border-5 border-danger bg-white">
                <div class="card-header bg-white border-0 pt-3 fw-bold text-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-2 animate-pulse"></i> 
                    Atenção: Contratos Próximos do Vencimento (Falta 1 mês ou menos)
                </div>
                <div class="card-body py-2">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        <?php foreach ($alertasContratos as $alerta): 
                            $dias = $alerta['dias_restantes'];
                            // Define o tom do aviso dependendo da urgência
                            if ($dias < 0) {
                                $texto_dias = "Vencido há " . abs($dias) . " dias!";
                                $badge_cor = "bg-danger";
                            } elseif ($dias == 0) {
                                $texto_dias = "Termina hoje!";
                                $badge_cor = "bg-dark";
                            } else {
                                $texto_dias = "Resta(m) " . $dias . " dias";
                                $badge_cor = "bg-warning text-dark";
                            }
                        ?>
                            <div class="col">
                                <div class="p-3 rounded bg-light border d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark d-block"><?= htmlspecialchars($alerta['nome']) ?></strong>
                                        <span class="text-muted small"><i class="bi bi-telephone"></i> <?= htmlspecialchars($alerta['telefone_principal']) ?></span>
                                        <br>
                                        <span class="text-muted cs-small" style="font-size: 0.8rem;">Término: <?= date('d/m/Y', strtotime($alerta['data_termino'])) ?></span>
                                    </div>
                                    <span class="badge <?= $badge_cor ?> p-2 font-monospace"><?= $texto_dias ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3 text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Estes alertas desaparecerão automaticamente assim que um novo pagamento correspondente for registado para o inquilino.
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<style>
    /* Estilo de fonte personalizado para Desktop/Telas Médias */
    @media (min-width: 768px) {
        .dash-card-value {
            font-size: 3rem !important; /* Tamanho controlado em vez de display-4 */
            font-weight: 700;
        }
        .dash-card-currency {
            font-size: 1.5rem !important; /* Unidade monetária menor e mais elegante */
            font-weight: 400;
        }
    }
</style>
<!-- Cards de Resumo Geral -->

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <h6 class="text-uppercase small text-white-50">Total Geral Recebido</h6>
                <h2><?= number_format($totalGeral, 2, ',', '.') ?> kz</h2>
                <i class="bi bi-cash-coin position-absolute top-50 end-0 translate-middle-y me-3 opacity-25 fs-1"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <h6 class="text-uppercase small">Custos de Manutenção</h6>
                <h2><?= number_format($totalManutencao, 2, ',', '.') ?> kz</h2>
                <i class="bi bi-tools position-absolute top-50 end-0 translate-middle-y me-3 opacity-25 fs-1"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card <?= $saldoGeral >= 0 ? 'bg-info text-white' : 'bg-dark text-warning' ?> border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <h6 class="text-uppercase small">Saldo Geral Real</h6>
                <h2><?= number_format($saldoGeral, 2, ',', '.') ?> kz</h2>
                <i class="bi bi-wallet2 position-absolute top-50 end-0 translate-middle-y me-3 opacity-25 fs-1"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-dark text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <h6 class="text-uppercase small">Faturamento por Período</h6>
                <div style="max-height: 50px; overflow-y: auto; font-size: 0.85rem;">
                    <?php foreach($totaisPeriodos as $p): ?>
                        <div>Mês <?= $p['periodo_meses'] ?>: <strong><?= number_format($p['total'], 2, ',', '.') ?> kz</strong></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Botões de Controlo para Ocultar/Mostrar -->
    <div class="col-12 mb-3 d-flex gap-2">
        <button class="btn btn-dark shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formInquilino" aria-expanded="false" aria-controls="formInquilino">
            <i class="bi bi-person-plus-fill me-2"></i> Novo Inquilino
        </button>
        <button class="btn btn-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formPagamento" aria-expanded="false" aria-controls="formPagamento">
            <i class="bi bi-cash-stack me-2"></i> Efectuar Pagamento
        </button>
    </div>

    <!-- Formulário de Entrada unificado -->
    <div class="col-xl-4 mb-4">
    
        <!-- 1. Cadastrar Inquilino (Oculto por padrão) -->
        <div class="collapse mb-4" id="formInquilino">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>1. Cadastrar Inquilino
                </div>
                <div class="card-body">
                    <form action="index.php?acao=salvar_inquilino" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Agostinho André" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Telemóvel/Tel 1</label>
                                <input type="text" name="telefone_principal" class="form-control" placeholder="9xxxxxxxx" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Tel Alternativo</label>
                                <input type="text" name="telefone_alternativo" class="form-control" placeholder="Opcional">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição do Anexo</label>
                            <textarea name="descricao_anexo" class="form-control" rows="2" placeholder="Ex: Quarto 2, Bloco B, inclui água..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Cadastrar Inquilino</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. Lançar Novo Pagamento (Oculto por padrão) -->
        <div class="collapse" id="formPagamento">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-cash-stack me-2"></i>2. Efectuar Novo Pagamento
                </div>
                <div class="card-body">
                    <form action="index.php?acao=salvar_pagamento" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
                        <div class="mb-3">
                            <label class="form-label">Selecione o Inquilino</label>
                            <select name="inquilino_id" class="form-select" required>
                                <option value="">-- Escolha --</option>
                                <?php foreach($listaInquilinos as $i): ?>
                                    <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Período (Meses)</label>
                                <input type="number" name="periodo_meses" class="form-control" value="6" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Valor Pago</label>
                                <input type="number" step="0.01" name="valor_pago" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Data de Pgto</label>
                                <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="Pago">Pago</option>
                                    <option value="Pendente">Pendente</option>
                                    <option value="Atrasado">Atrasado</option>
                                    <option value="Não Pago">Não Pago</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comprovativo</label>
                            <input type="file" name="comprovativo" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registar Pagamento</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabela do Dashboard -->
    <div class="col-xl-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Painel Geral de Controlo</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Inquilino / Contato</th>
                                <th>Período</th>
                                <th>Valor</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($historico as $h): 
                                $badge = ['Pago'=>'success', 'Pendente'=>'warning text-dark', 'Atrasado'=>'danger', 'Não Pago'=>'secondary'][$h['estado']];
                            ?>
                            <tr>
                                <td data-label="Inquilino" class="fw-bold text-dark">
                                    <strong><?= htmlspecialchars($h['nome']) ?></strong><br>
                                    <small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($h['telefone_principal']) ?></small>
                                </td>
                                <td data-label="Período" class="text-nowrap"><span class="badge bg-light text-dark border"><?= $h['periodo_meses'] ?> Meses</span></td>
                                <td data-label="Valor" class="fw-bold text-success"><?= number_format($h['valor_pago'], 2, ',', '.') ?> kz</td>
                                <td data-label="Estado"><span class="badge bg-<?= $badge ?>"><?= $h['estado'] ?></span></td>
                                <td data-label="Ações" class="text-center">
                                    <a href="<?= $h['comprovativo'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver Comprovativo Enviado"><i class="bi bi-file-earmark-image"></i></a>
                                    <a href="index.php?acao=recibo&id=<?= $h['id'] ?>" target="_blank" class="btn btn-sm btn-success" title="Gerar Comprovativo Geral"><i class="bi bi-printer"></i></a>
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