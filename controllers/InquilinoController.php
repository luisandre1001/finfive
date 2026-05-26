<?php
require_once 'config/database.php';
require_once 'models/Inquilino.php';

class InquilinoController {
    
    // Processa o salvamento do Inquilino
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $inquilino = new Inquilino($db);

            $nome = $_POST['nome'];
            $tel1 = $_POST['telefone_principal'];
            $tel2 = $_POST['telefone_alternativo'] ?? null;
            $anexo = $_POST['descricao_anexo'];

            if ($inquilino->criar($nome, $tel1, $tel2, $anexo)) {
                header("Location: index.php?status=sucesso");
                $_SESSION['mensagem_sucesso'] = "Inquilino gravado com sucesso!";
            } else {
                header("Location: index.php?status=erro");
                $_SESSION['mensagem_erro'] = "Erro ao tentar gravar o inquilino.";
            }
            exit();
        }
    }
    
    public function editar($id) {
        $database = new Database();
        $db = $database->getConnection();
        $inquilinoModel = new Inquilino($db);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inquilinoModel->atualizar($id, $_POST['nome'], $_POST['telefone_principal'], $_POST['telefone_alternativo'], $_POST['descricao_anexo']);
            header("Location: index.php?acao=inquilinos&status=sucesso");
            $_SESSION['mensagem_sucesso'] = "Inquilino alterado com sucesso!";
            exit();
    }
    
    $dados = $inquilinoModel->obterPorId($id);
    require_once 'views/includes/header.php';
    require_once 'views/inquilinos_editar.php';
    require_once 'views/includes/footer.php';
}

    public function deletar($id) {
        $database = new Database();
        $db = $database->getConnection();
        (new Inquilino($db))->eliminar($id);
        header("Location: index.php?acao=inquilinos&status=sucesso");
        exit();
    }
}
