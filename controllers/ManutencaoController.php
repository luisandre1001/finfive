<?php
require_once 'config/database.php';
require_once 'models/Manutencao.php';

class ManutencaoController {
    
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $manutencaoModel = new Manutencao($db);

        $historico = $manutencaoModel->lerTodos();

        require_once 'views/includes/header.php';
        require_once 'views/manutencoes.php';
        require_once 'views/includes/footer.php';
    }

    public function salvar() {
        $database = new Database();
        $db = $database->getConnection();
        $manutencaoModel = new Manutencao($db);

        $manutencaoModel->descricao = $_POST['descricao'];
        $manutencaoModel->custo = $_POST['custo'];
        $manutencaoModel->data_registo = $_POST['data_registo'];

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $manutencaoModel->id = $_POST['id'];
            $manutencaoModel->atualizar();
        } else {
            $manutencaoModel->criar();
        }
        header("Location: index.php?acao=manutencao");
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $manutencaoModel = new Manutencao($db);

        $manutencao = $manutencaoModel->lerPorId($_GET['id']);
        $historico = $manutencaoModel->lerTodos();

        require_once 'views/includes/header.php';
        require_once 'views/manutencoes.php';
        require_once 'views/includes/footer.php';
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $manutencaoModel = new Manutencao($db);

        $manutencaoModel->eliminar($_GET['id']);
        header("Location: index.php?acao=manutencao");
    }
}