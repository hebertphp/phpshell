<?php
require_once 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: relatorio.php?msg=excluido");
} else {
    header("Location: relatorio.php");   // aberto direto pelo navegador: só volta
}
exit;
