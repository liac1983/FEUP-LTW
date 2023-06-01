<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/messageClass.php');
    require_once(__DIR__ . '/../database/changesClass.php');

    $db = getDatabaseConnection();

    $date = new DateTime("now", new DateTimeZone('Europe/Lisbon'));
    $date_str = $date->format('d-m-Y H:i:s');

    if ($_POST['description'] !== null && trim($_POST['description']) !== '') {
        if ($id = Message::createMessage($db, intval($_POST['ticket_id']), $session->getID(), $date_str, $_POST['description'])) {
            
            $messages = Message::searchMessage($db, intval($_POST['ticket_id']));

            $session->addMessage('success', 'Comment successfully created');
        }
        else {
            $session->addMessage('error', 'Error creating comment');
        }
    }
    else {
        $session->addMessage('error', 'Comment can not be empty');
    }

    echo json_encode($messages);
?>