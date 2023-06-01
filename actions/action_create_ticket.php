<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');
    require_once(__DIR__ . '/../database/hashtagClass.php');
    require_once(__DIR__ . '/../database/changesClass.php');

    $db = getDatabaseConnection();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf']) die(header("Location: /"));

    $date = new DateTime("now", new DateTimeZone('Europe/Lisbon'));
    $date_str = $date->format('d-m-Y H:i:s');

    if ($_POST['title'] !== null && trim($_POST['title']) !== '' 
        && $_POST['description'] !== null && trim($_POST['description']) !== '') {

        if ($_POST['department'] === '---') $department = 'General';
        else $department = $_POST['department'];

        if ($id = Ticket::createTicket($db, $_POST['title'], $_POST['description'], $date_str, $_POST['category'], $department, $session->getID())) {
            $changes_id = Changes::createNewTicketChange($db, $id, $session->getUsername(), $date_str);
            if ($changes_id === -1) {
                $session->addMessage('error', 'Error creating ticket');
                die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
            }

            if ($_POST['hashtag'] !== null) {
                $hashtags = explode(" ", $_POST['hashtag']);

                foreach ($hashtags as $hashtag) {
                    if (!Hashtag::hashtagExists($db, $hashtag)) {
                        Hashtag::createHashtag($db, $hashtag);
                    }
                    Hashtag::createHashtagTicket($db, $hashtag, $id);
                }
            }

            $session->addMessage('success', 'Ticket created successfully');
            header("Location:../pages/ticket_page.php");
        }
        else {
            $session->addMessage('error', 'Error creating ticket');
            die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
        }
    }
    else {
        $session->addMessage('error', 'Title, Department and Description can not be empty');
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }  
?>