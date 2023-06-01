<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/clientClass.php');

    $db = getDatabaseConnection();

    $client = Client::getClientWithPassoword($db, $_POST['username'], $_POST['password']);

    if ($client) {
        $session->setID($client->id);
        $session->setUsername($client->username);
        $session->setRole($client->role);
        header("Location:../index.php");
    }
    else {
        $session->addMessage('error', 'Incorrect username or password');
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
?>