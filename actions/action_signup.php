<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/clientClass.php');

    $db = getDatabaseConnection();

    if (Client::duplicateEmail($db, $_POST['email'])) {
        $_SESSION['ERROR'] = 'Email already exists';
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
    else if (Client::duplicateUsername($db, $_POST['username'])) {
        $_SESSION['ERROR'] = 'Username already exists';
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
    else if (($id = Client::createClient($db, $_POST['username'], $_POST['name'], $_POST['password'], $_POST['email'])) != -1) {
        $session->setID($id);
        $session->setUsername($_POST['username']);
        $session->setRole(1); // role por default é 1
        header("Location:../index.php");
    }
    else {
        $_SESSION['ERROR'] = 'ERROR';
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
?>