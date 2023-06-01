<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/messageClass.php');

    $db = getDatabaseConnection();

    $comments = Message::searchMessage($db, intval($_GET['ticket_id']));

    echo json_encode($comments);
?>