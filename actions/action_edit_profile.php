<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/clientClass.php');

    $db = getDatabaseConnection();

    $user = Client::getClient($db, $session->getID());

    if ($user) {
        if ($_POST['username'] !== null && trim($_POST['username']) !== '' && $_POST['name'] !== null && trim($_POST['name']) !== '' && $_POST['email'] !== null && trim($_POST['email']) !== '') {
            $user->username = $_POST['username'];
            $session->setUsername($user->username);
            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->save($db);

            if (!is_null($_POST['password']) && strlen($_POST['password']) !== 0) {
                if (trim($_POST['password']) !== '') {
                    $user->password = hash('sha256', $_POST['password']);
                    $user->savePassword($db);
                }
                else {
                    $session->addMessage('error', 'Password can not be empty');
                }
            }
            $session->addMessage('success', 'Profile updated successfully');
        }
        else {
            $session->addMessage('error', 'Username, Name and Email can not be empty');
        }

        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
?>