<?php
require_once 'conecta.php';

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();
?>
<h1>Relatório de Estoque</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th><th>Nome</th><th>Preço</th><th>Quantidade</th>
    </tr>
    <?php foreach ($produtos as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nome']) ?></td>
        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
        <td><?= $p['quantidade'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<p><a href="insere.php">Novo produto</a></p>
