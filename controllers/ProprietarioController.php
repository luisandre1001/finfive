<?php
require_once 'config/database.php';
require_once 'models/Proprietario.php';

class ProprietarioController {
    public function atualizarConfiguracoes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $proprietario = new Proprietario($db);

            $nome = $_POST['nome'];
            $caminho_assinatura = null;

            // Verifica se o usuário enviou uma nova foto de assinatura
            if (!empty($_FILES['assinatura']['name'])) {
                $diretorio = "uploads/assinaturas/";
                $extensao = strtolower(pathinfo($_FILES["assinatura"]["name"], PATHINFO_EXTENSION));
                
                // Nome único para evitar conflito de cache no navegador
                $nome_arquivo = "ASSINA_" . uniqid() . "." . $extensao;
                $caminho_assinatura = $diretorio . $nome_arquivo;

                if (!move_uploaded_file($_FILES["assinatura"]["tmp_name"], $caminho_assinatura)) {
                    header("Location: index.php?acao=configuracoes&status=erro_upload");
                    exit();
                }
            }

            // Atualiza a base de dados usando o Model que criámos
            if ($proprietario->atualizar($nome, $caminho_assinatura)) {
                header("Location: index.php?acao=configuracoes&status=sucesso");
            } else {
                header("Location: index.php?acao=configuracoes&status=erro");
            }
            exit();
        }
    }
}