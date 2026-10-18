<?php
echo "<h2>Status do Ambiente PHP</h2>";
echo "<p>Exemplo da aula: <a href='crud/'>CRUD de produtos</a> (<a href='crud/criatabela.php'>criatabela.php</a> · <a href='crud/relatorio.php'>relatorio.php</a> · <a href='crud/insere.php'>insere.php</a>)</p>";
echo "<p>PHP " . PHP_VERSION . "</p>";

// 1. Teste MySQL
try {
    $mysql = new PDO("mysql:host=mysql_db;dbname=meubanco_mysql", "aluno", "alunopassword");
    echo "<p style='color:green;'>✅ Conexão com <strong>MySQL</strong> realizada com sucesso!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro no MySQL: " . $e->getMessage() . "</p>";
}

// 2. Teste SQLite
try {
    $sqlite = new PDO("sqlite:/var/www/html/database.sqlite");
    echo "<p style='color:green;'>✅ Conexão com <strong>SQLite</strong> realizada com sucesso!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro no SQLite: " . $e->getMessage() . "</p>";
}

// 3. Driver PostgreSQL (não há servidor PostgreSQL neste ambiente; use um servidor externo)
if (extension_loaded('pdo_pgsql')) {
    echo "<p style='color:green;'>✅ Driver <strong>PostgreSQL</strong> (pdo_pgsql) instalado. Conecte a um servidor externo.</p>";
} else {
    echo "<p style='color:red;'>❌ Driver PostgreSQL (pdo_pgsql) não encontrado.</p>";
}
?>
