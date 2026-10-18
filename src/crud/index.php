<?php
// Página inicial do exemplo: links para os arquivos do CRUD de produtos.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de produtos - exemplo da aula</title>
    <style>
        body { font-family: sans-serif; max-width: 720px; margin: 30px auto; padding: 0 16px; background: #f4f4f4; color: #222; }
        h1 { color: #2c3e50; }
        li { margin-bottom: 12px; line-height: 1.5; }
        a { color: #1a5fb4; font-weight: bold; }
        .dica { background: #fff; border-left: 4px solid #2c3e50; padding: 10px 14px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>CRUD de produtos</h1>
    <p>Exemplo pronto da aula de PHP com PDO e MySQL. Siga a ordem:</p>
    <ol>
        <li><a href="criatabela.php">criatabela.php</a>: cria a tabela <code>produtos</code> e 3 produtos de exemplo (só na primeira vez).</li>
        <li><a href="relatorio.php">relatorio.php</a>: lista os produtos (Read) e tem os botões Editar e Excluir.</li>
        <li><a href="insere.php">insere.php</a>: cadastra um produto novo (Create).</li>
    </ol>
    <p class="dica"><code>altera.php</code> (Update) e <code>exclui.php</code> (Delete) abrem pelos botões <strong>Editar</strong> e <strong>Excluir</strong> do relatório.</p>
    <p><a href="../">&larr; Voltar</a></p>
</body>
</html>
