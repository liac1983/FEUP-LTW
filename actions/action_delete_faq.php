<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/faqClass.php');

    $db = getDatabaseConnection();

    $faqID = intval($_GET['faq_id']);

    if (FAQ::deleteFaq($db, $faqID)) {
        $session->addMessage('success', 'FAQ deleted successfully');
    }
    else {
        $session->addMessage('error', 'Error deleting FAQ');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    header("Location:../pages/faq_page.php");

?>