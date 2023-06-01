<?php
    declare(strict_types = 1); 

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/changesClass.php');
    require_once(__DIR__ . '/../database/ticketClass.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/css.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');

    $db = getDatabaseConnection();
    $ticketID = intval($_GET['ticket_id']);

    $changes = Changes::getChangesFromTicket($db, $ticketID);

    $ticket = Ticket::getTicket($db, $ticketID);


    drawHeader();
    drawCSSStyle();
    drawCSSTicket();
    drawUserLoggedIn($session);
    drawAside($session);
    drawTicketChanges($ticket->title, $ticket->id, $changes);
    drawFooter(); 
?>