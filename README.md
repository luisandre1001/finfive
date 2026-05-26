# 🚀 FINFIVE - Gestão Financeira de Alugueres

O **FINFIVE** é um sistema completo e intuitivo para a gestão financeira de imóveis e alugueres. Desenvolvido com uma arquitetura robusta e focado na usabilidade, o sistema permite o controlo total de inquilinos, faturamento mensal, histórico de pagamentos e despesas com manutenções estruturais, centralizando toda a saúde financeira do negócio num dashboard estatístico inteligente.

---

## 📌 Funcionalidades Principais

* **📊 Dashboard Dinâmico:** Gráficos interativos (Evolução de Faturamento Mensal e Divisão de Status por Estado) alimentados em tempo real através da biblioteca **Chart.js**.
* **👥 Gestão Estatística de Inquilinos:** Registo completo de moradores, contactos principais/alternativos e descrição detalhada do imóvel/anexo ocupado.
* **💰 Controlo de Fluxo de Caixa (Rendas):** Monitorização de valores arrecadados, previsões pendentes e montantes em atraso com conversão e formatação monetária automática.
* **🛠️ Módulo de Manutenções:** Registo e acompanhamento de despesas com reparações estruturais, com impacto direto nos relatórios e balanços do sistema.
* **🔒 Segurança Avançada de Produção:** Sistema blindado contra vulnerabilidades comuns da web (detalhado abaixo).

---

## 🛠️ Tecnologias Utilizadas

O projeto foi construído utilizando tecnologias modernas e eficientes para o ecossistema web:

* **Backend:** PHP (Estruturado/MVC com separação clara de responsabilidades)
* **Base de Dados:** MySQL (Persistência de dados relacional)
* **Drivers de Conexão:** PDO (PHP Data Objects) para máxima segurança nas consultas
* **Frontend:** HTML5, CSS3, JavaScript (ES6+)
* **Estilização & Componentes:** Bootstrap 5 & Bootstrap Icons (Layout 100% Responsivo)
* **Gráficos:** Chart.js

---

## 🔒 Camadas de Segurança Implementadas

O **FINFIVE** adota padrões rigorosos de segurança para proteger informações sensíveis e financeiras:

1.  **Proteção contra SQL Injection:** Todas as operações na base de dados utilizam *Prepared Statements* nativos do PDO, impedindo a injeção de scripts maliciosos nas queries SQL.
2.  **Defesa contra Ataques CSRF (Cross-Site Request Forgery):** Implementação de Tokens criptográficos aleatórios de sessão (`csrf_token`) validados rigorosamente em todas as requisições do tipo `POST`.
3.  **Criptografia de Palavras-passe:** Armazenamento seguro de senhas administrativas utilizando o algoritmo robusto `PASSWORD_BCRYPT` com a função nativa `password_hash()` do PHP.
4.  **Blindagem no Upload de Ficheiros:** Filtro estrito de upload para comprovativos de pagamento que valida tanto a extensão (`.jpg`, `.png`, `.pdf`) quanto o tipo MIME real do ficheiro, além de renomeá-lo com hashes aleatórios para evitar execução de códigos no servidor.

---

## 🚀 Como Instalar e Executar o Projeto Localmente

### Pré-requisitos
* Servidor local instalado (Ex: **XAMPP**, WampServer ou Laragon) com PHP 8.1+ e MySQL ativos.

### Passo a Passo

1.  **Clonar o Repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/finfive.git](https://github.com/seu-usuario/finfive.git)
    ```
    *(Ou descarregue o arquivo .zip do repositório e extraia dentro da pasta `htdocs` do seu XAMPP).*

2.  **Configurar a Base de Dados:**
    * Aceda ao seu `http://localhost/phpmyadmin/`.
    * Crie uma nova base de dados chamada `finfive`.
    * Clique na base de dados criada, vá à aba **Importar**, selecione o ficheiro localizado em `/database/schema.sql` (ou o nome do seu arquivo SQL) e execute.

3.  **Configurar as Credenciais:**
    * Na pasta `config/`, duplique o ficheiro `database.php.example` e renomeie a cópia para `database.php`.
    * Abra o ficheiro `database.php` e insira as suas credenciais locais do MySQL:
    ```php
    private $host = "localhost";
    private $db_name = "finfive";
    private $username = "root";
    private $password = "";
    ```

4.  **Aceder ao Sistema:**
    * Abra o seu navegador e digite: `http://localhost/finfive/`

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---
Developed with 💻 and ☕ by [O Seu Nome].