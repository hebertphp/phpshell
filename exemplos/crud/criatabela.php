<?php
require_once 'conecta.php';

$sql = "CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT DEFAULT 0
)";
$pdo->exec($sql);

// Dados de exemplo: só entram se a tabela estiver vazia
$total = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
if ($total == 0) {
    $pdo->exec("INSERT INTO produtos (nome, preco, quantidade) VALUES
        ('Caderno', 12.90, 30),
        ('Caneta azul', 2.50, 100),
        ('Mochila', 89.90, 8)");
}

echo "<h1>Tabela criada com sucesso!</h1>";
echo "<p><a href='relatorio.php'>Ver o relatório</a></p>";
