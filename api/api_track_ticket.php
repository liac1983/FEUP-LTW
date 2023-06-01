<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');

    $db = getDatabaseConnection();

    $ticketID = intval($_POST['ticket']);
    $ticket = Ticket::getTicket($db, $ticketID);

    $user;
    if ($ticket->client === $session->getID()) {
        $user = Roles::Client->value;
        if ($ticket->clientTrack === 1) die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }    
    else {
        $user = Roles::Agent->value;
        if ($ticket->agentTrack === 1) die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    Ticket::trackTicket($db, $ticketID, $user);
    
    $tickets;
    if ($user === Roles::Client->value) $tickets = Ticket::getTicketsFromUser($db, $session->getID(), null, null, null);
    else $tickets = Ticket::getAgentTickets($db, $session->getID(), null, null, null);
    
    $role = array();
    for ($i = 0; $i < count($tickets); $i++) {
        $role[] = array('role' => $user);
    }

    echo json_encode(array($role, $tickets));
?>