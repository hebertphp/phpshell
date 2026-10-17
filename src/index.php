<?php
echo "<h2>Status das Conexões com Banco de Dados</h2>";

// 1. Teste MySQL
try {
    $mysql = new PDO("mysql:host=mysql_db;dbname=meubanco_mysql", "aluno", "alunopassword");
    echo "<p style='color:green;'>✅ Conexão com <strong>MySQL</strong> realizada com sucesso!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro no MySQL: " . $e->getMessage() . "</p>";
}

// 2. Teste PostgreSQL
try {
    $postgres = new PDO("pgsql:host=postgres_db;dbname=meubanco_postgres", "aluno", "alunopassword");
    echo "<p style='color:green;'>✅ Conexão com <strong>PostgreSQL</strong> realizada com sucesso!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro no PostgreSQL: " . $e->getMessage() . "</p>";
}

// 3. Teste SQLite
try {
    $sqlite = new PDO("sqlite:/var/www/html/database.sqlite");
    echo "<p style='color:green;'>✅ Conexão com <strong>SQLite</strong> realizada com sucesso!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro no SQLite: " . $e->getMessage() . "</p>";
}
?>
