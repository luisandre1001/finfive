<?php
class Database {
    // AJUSTE COM OS DADOS DO SEU SERVIDOR ONLINE:
    private $host = "sql301.infinityfree.com"; // Geralmente permanece localhost na maioria das hospedagens
    private $db_name = "if0_42019767_finfive"; 
    private $username = "if0_42019767";
    private $password = "23Agosti456";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            // Segurança extra para produção: desativar emulação de prepares
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Em produção, nunca exiba o erro real ($exception->getMessage()) para o cliente
            die("Erro ao conectar com o banco de dados de forma segura.");
        }
        return $this->conn;
    }
}