<?php
require_once 'config/database.php';
require_once 'models/Utilizador.php';

class AuthController {
    
    public function index() {
        // Mostra o ecrã de login
        require_once 'views/login.php';
    }

    public function logar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $auth = new Utilizador($db);

            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $utilizador = $auth->fazerLogin($email, $senha);

            if ($utilizador) {
                // Inicia a sessão segura
                $_SESSION['user_id'] = $utilizador['id'];
                $_SESSION['user_nome'] = $utilizador['nome'];
                $_SESSION['user_nivel'] = $utilizador['nivel'];
                
                header("Location: index.php?acao=dashboard");
                exit();
            } else {
                header("Location: index.php?acao=login&erro=dados_invalidos");
                exit();
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?acao=login");
        exit();
    }
}