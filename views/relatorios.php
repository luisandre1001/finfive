<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="h3"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Relatórios e Estatísticas</h2>
        <p class="text-muted">Acompanhe a saúde financeira dos seus alugueres e gráficos de desempenho.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small text-uppercase">Total Arrecadado</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($totais['total_pago'] ?? 0, 2, ',', '.') ?> kz</h3>
                </div>
                <i class="bi bi-cash-coin fs-1 text-white-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small text-uppercase">Total Manutenções</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($totalManutencoes ?? 0, 2, ',', '.') ?> kz</h3>
                </div>
                <i class="bi bi-tools fs-1 text-white-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-dark-50 small text-uppercase">Previsão Pendente</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($totais['total_pendente'] ?? 0, 2, ',', '.') ?> kz</h3>
                </div>
                <i class="bi bi-clock-history fs-1 text-dark-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small text-uppercase">Total em Atraso</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($totais['total_atrasado'] ?? 0, 2, ',', '.') ?> kz</h3>
                </div>
                <i class="bi bi-exclamation-octagon fs-1 text-white-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold text-dark py-3 border-0">
                <i class="bi bi-activity text-primary me-2"></i>Evolução do Faturamento (Recebido)
            </div>
            <div class="card-body">
                <canvas id="chartFaturamento" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold text-dark py-3 border-0">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i>Status das Rendas
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartEstados" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- CONVERSÃO DOS DADOS DO PHP PARA JAVASCRIPT ---
    
    // 1. Configuração do Gráfico de Faturamento Mensal (Linha)
    const dadosMensais = <?= json_encode($faturamentoMensal) ?>;
    const labelsMensais = dadosMensais.map(item => item.mes);
    const valoresMensais = dadosMensais.map(item => item.total);

    const ctxFaturamento = document.getElementById('chartFaturamento').getContext('2d');
    new Chart(ctxFaturamento, {
        type: 'line',
        data: {
            labels: labelsMensais.length ? labelsMensais : ['Sem dados'],
            datasets: [{
                label: 'Faturamento (kz)',
                data: valoresMensais.length ? valoresMensais : [0],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // 2. Configuração do Gráfico de Divisão por Estado (Pizza)
    const dadosEstados = <?= json_encode($estadosPizza) ?>;
    const labelsEstados = dadosEstados.map(item => item.estado);
    const valoresEstados = dadosEstados.map(item => item.qtd);
    
    const coresEstados = labelsEstados.map(estado => {
        if(estado === 'Pago') return '#198754';
        if(estado === 'Pendente') return '#ffc107';
        if(estado === 'Atrasado') return '#dc3545';
        return '#6c757d';
    });

    const ctxEstados = document.getElementById('chartEstados').getContext('2d');
    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: labelsEstados.length ? labelsEstados : ['Nenhum registo'],
            datasets: [{
                data: valoresEstados.length ? valoresEstados : [1],
                backgroundColor: coresEstados.length ? coresEstados : ['#e9ecef'],
                borderWidth: 2
            }]
        },
        options: { responsive: true }
    });
</script>