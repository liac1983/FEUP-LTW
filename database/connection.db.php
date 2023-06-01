<?php
    declare(strict_types = 1);

    function getDatabaseConnection() : PDO {
        $db = new PDO('sqlite:' . __DIR__ . '/../database/database.db');
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('PRAGMA FOREIGN_KEYS = ON');
        $stmt->execute(array());
        return $db;    
    }
    
?>