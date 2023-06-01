<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');
    require_once(__DIR__ . '/../database/messageClass.php');
    require_once(__DIR__ . '/../database/clientClass.php');
    require_once(__DIR__ . '/../database/hashtagClass.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/css.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../templates/comments.tpl.php');

    $ticketID = intval($_GET['ticket_id']);

    $db = getDatabaseConnection();

    $ticket = Ticket::getTicket($db, $ticketID);

    $messages = Message::getMessagesFromTicket($db, $ticketID);

    $client = Client::getClient($db, $ticket->client);

    $usernames = array();

    foreach ($messages as $message) {
        $usernames[] = Client::getClient($db, $message->userID);
    }

    $foreignAgent = false;

    if ($ticket->client !== $session->getID() && $ticket->agent !== $session->getID()) $foreignAgent = true;

    $hashtags = Hashtag::getHashtags($db, $ticket->id);

    drawHeader();
    drawCSSStyle();
    drawCSSTicket();
    drawUserLoggedIn($session);
    drawAside($session);
    drawTicket($session, $ticket, $client->username, $hashtags);
    drawComments($messages, $usernames, $ticketID, $foreignAgent);
    drawFooter();  
?>
