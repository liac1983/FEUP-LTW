<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/faqClass.php');

    $db = getDatabaseConnection();

    $faqID = intval($_GET['faq_id']);

    $faq = Faq::getFaq($db, $faqID);

    if ($faq) {
        if ($_POST['title'] !== null && trim($_POST['title']) !== '' && $_POST['content'] !== null && trim($_POST['content']) !== '') {
            $faq->title = $_POST['title'];
            $faq->content = $_POST['content'];
            $faq->save($db);
            $session->addMessage('success', 'FAQ updated successfully');
            header("Location:../pages/faq_page.php");
        }
        else {
            $session->addMessage('error', 'Title and content can not be empty');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
    }

?>