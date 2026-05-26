<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Comprovativo Geral de Pagamento - FINFIVE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-family: 'Courier New', Courier, monospace; }
        .recibo-box { border: 2px solid #000; padding: 30px; margin-top: 20px; }
        .signature-line { border-top: 1px solid #000; margin-top: 60px; text-align: center; font-size: 14px; }
        .signature-img { max-height: 60px; display: block; margin: 0 auto 5px auto; }
    </style>
</head>
<body>
<div class="container">
    <div class="recibo-box">
        <div class="text-center mb-4">
            <h2>FINFIVE</h2>
            <h4>COMPROVATIVO GERAL DE ARRENDAMENTO</h4>
            <p>Data de Emissão: <?= date('d/m/Y') ?></p>
        </div>
        
        <hr>

        <p class="fs-5">
            Declaramos para os devidos efeitos que o inquilino <strong><?= htmlspecialchars($dados['nome']) ?></strong>, 
            com o contacto telefónico <strong><?= htmlspecialchars($dados['telefone_principal']) ?></strong>, efetuou o pagamento 
            no valor de <span class="fw-bold"><?= number_format($dados['valor_pago'], 2, ',', '.') ?> kz</span>, correspondente ao 
            período de contrato estipulado de <strong><?= $dados['periodo_meses'] ?> meses</strong>.
        </p>

        <p class="mt-4"><strong>Descrição do Anexo Arrendado:</strong><br>
        <?= nl2br(htmlspecialchars($dados['descricao_anexo'])) ?></p>

        <p class="text-end mt-5">Status do Documento: <strong>QUÍTADO (PAGO)</strong></p>

        <!-- Área de Assinaturas Requisitada -->
        <div class="row mt-5 pt-4">
            <div class="col-6">
                <div class="signature-line">
                    <br>
                    <strong><?= htmlspecialchars($dados['nome']) ?></strong><br>
                    Inquilino
                </div>
            </div>
            <div class="col-6">
                <div class="signature-line">
                    <!-- Assinatura Digital Carregada Automaticamente -->
                    <?php if(!empty($prop['assinatura_path']) && file_exists($prop['assinatura_path'])): ?>
                        <img src="<?= $prop['assinatura_path'] ?>" class="signature-img" alt="Assinatura Digital">
                    <?php else: ?>
                        <div class="text-danger small">[Assinatura digital não configurada no sistema]</div>
                    <?php endif; ?>
                    <strong><?= htmlspecialchars($prop['nome'] ?? 'O Proprietário') ?></strong><br>
                    Proprietário
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4 no-print">
        <button onclick="window.print();" class="btn btn-primary btn-lg"><i class="bi bi-printer"></i> Imprimir Recibo</button>
    </div>
</div>
</body>
</html>