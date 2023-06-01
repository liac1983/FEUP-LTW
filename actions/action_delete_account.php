<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/clientClass.php');

    $db = getDatabaseConnection();

    if (Client::deleteClient($db, $session->getID())) {
        $session->addMessage('success', 'Account deleted successfully');
    }
    else {
        $session->addMessage('error', 'Error deleting account');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }
    
    $session->logout();
    header("Location: /");
?>