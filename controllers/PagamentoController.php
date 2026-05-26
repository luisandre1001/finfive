<?php
require_once 'config/database.php';
require_once 'models/Pagamento.php';

class PagamentoController {
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $pagamento = new Pagamento($db);

            $inquilino_id = $_POST['inquilino_id'];
            $periodo = $_POST['periodo_meses'];
            $valor = $_POST['valor_pago'];
            $data = $_POST['data_pagamento'];
            $estado = $_POST['estado'];

            // Validação de Segurança do Upload
            $diretorio = "uploads/comprovativos/";
            $extensao = strtolower(pathinfo($_FILES["comprovativo"]["name"], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['pdf', 'jpg', 'jpeg', 'png'];

            if (!in_array($extensao, $extensoes_permitidas)) {
                die("Erro: Apenas arquivos PDF ou Imagens são permitidos por segurança.");
            }

            $nome_unico = "COMP_" . uniqid() . "." . $extensao;
            $caminho_final = $diretorio . $nome_unico;

            if (move_uploaded_file($_FILES["comprovativo"]["tmp_name"], $caminho_final)) {
                $pagamento->criar($inquilino_id, $periodo, $valor, $data, $caminho_final, $estado);
                header("Location: index.php?status=sucesso");
            } else {
                header("Location: index.php?status=erro");
            }
        }
    }

    public function gerarRecibo($id) {
        $database = new Database();
        $db = $database->getConnection();
        $pagamentoModel = new Pagamento($db);
        
        $dados = $pagamentoModel->obterDetalhesRecibo($id);
        
        // Pega a assinatura digital do proprietário (id 1 padrão)
        $prop = $db->query("SELECT * FROM proprietario WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

        require_once 'views/recibo.php';
    }
    
    public function editar($id) {
        $database = new Database();
        $db = $database->getConnection();
        $pagamentoModel = new Pagamento($db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $caminho_final = null;
            if (!empty($_FILES["comprovativo"]["name"])) {
                $diretorio = "uploads/comprovativos/";
                $extensao = strtolower(pathinfo($_FILES["comprovativo"]["name"], PATHINFO_EXTENSION));
                $caminho_final = $diretorio . "COMP_" . uniqid() . "." . $extensao;
                move_uploaded_file($_FILES["comprovativo"]["tmp_name"], $caminho_final);
            }
            
            $pagamentoModel->atualizar($id, $_POST['periodo_meses'], $_POST['valor_pago'], $_POST['data_pagamento'], $_POST['estado'], $caminho_final);
            header("Location: index.php?acao=pagamentos&status=sucesso");
            exit();
        }
        
        $dados = $pagamentoModel->obterDetalhesRecibo($id);
        require_once 'views/includes/header.php';
        require_once 'views/pagamentos_editar.php'; // Criaremos esta mini-view
        require_once 'views/includes/footer.php';
    }

    public function deletar($id) {
        $database = new Database();
        $db = $database->getConnection();
        (new Pagamento($db))->eliminar($id);
        header("Location: index.php?acao=pagamentos&status=sucesso");
        exit();
    }
}