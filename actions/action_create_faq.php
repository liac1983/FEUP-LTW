<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/faqClass.php');

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    $db = getDatabaseConnection();

    if (trim($_POST['title']) !== '' && $_POST['title'] !== null && trim($_POST['description']) && $_POST['description'] !== null) {
        if ($id = Faq::createFaq($db, $_POST['title'], $_POST['description'])) {
            $session->addMessage('success', 'FAQ created successfully');
            header("Location:../pages/faq_page.php");
        }
        else {
            $session->addMessage('error', 'Error creating FAQ');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
    }
    else {
        $session->addMessage('error', 'Title and description can not be empty');
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }
?>