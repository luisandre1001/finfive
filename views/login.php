<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINFIVE - Acesso ao Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { max-width: 400px; margin-top: 10%; border: none; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card shadow login-card w-100 p-4">
        <div class="text-center mb-4">
            <h1 class="h3 fw-bold text-dark"><i class="bi bi-shield-check text-success me-2"></i>FINFIVE</h1>
            <p class="text-muted">Controlo Financeiro de Inquilinos</p>
        </div>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'dados_invalidos'): ?>
            <div class="alert alert-danger small p-2 text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> E-mail ou palavra-passe incorretos!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'restrito'): ?>
            <div class="alert alert-warning small p-2 text-center" role="alert">
                <i class="bi bi-lock-fill me-1"></i> Por favor, faça login para aceder.
            </div>
        <?php endif; ?>

        <form action="index.php?acao=autenticar" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="admin@finfive.com" required autocomplete="email">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Palavra-passe</label>
                <input type="password" name="senha" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar no Painel</button>
        </form>
    </div>
</div>

</body>
</html>