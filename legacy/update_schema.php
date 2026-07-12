<?php
require_once 'config/database.php';

try {
    // 1. Check if foto_suami and foto_istri exist
    $stmt = $pdo->query("DESCRIBE arsip");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $changes = [];

    if (!in_array('foto_suami', $columns)) {
        $pdo->exec("ALTER TABLE arsip ADD COLUMN foto_suami VARCHAR(255) NOT NULL AFTER nama_penghulu");
        $changes[] = "Added 'foto_suami' column.";
    }

    if (!in_array('foto_istri', $columns)) {
        $pdo->exec("ALTER TABLE arsip ADD COLUMN foto_istri VARCHAR(255) NOT NULL AFTER foto_suami");
        $changes[] = "Added 'foto_istri' column.";
    }

    if (in_array('file_gambar', $columns)) {
        $pdo->exec("ALTER TABLE arsip DROP COLUMN file_gambar");
        $changes[] = "Dropped 'file_gambar' column.";
    }

    if (empty($changes)) {
        echo "Database schema is already up to date.\n";
    } else {
        echo "Schema updated successfully:\n";
        foreach ($changes as $change) {
            echo "- $change\n";
        }
    }

} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
?>
