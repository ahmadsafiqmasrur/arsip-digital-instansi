<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->query("DESCRIBE arsip");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Columns in 'arsip' table:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
